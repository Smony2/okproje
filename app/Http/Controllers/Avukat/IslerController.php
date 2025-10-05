<?php
namespace App\Http\Controllers\Avukat;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\AvukatPuan;
use App\Models\IsTeklifi;
use App\Models\JobEvent;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Isler;
use App\Models\Adliye;
use App\Models\Katip;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\IsPuan;
use Illuminate\Support\Facades\DB;

class IslerController extends Controller
{
    public function adliyeSec() {
        $adliyeler = Adliye::where('aktif_mi', 1)
            ->with(['katipler' => fn($q) => $q->where('aktif_mi', 1)])
            ->get();

        return view('avukat.isler.adliye-sec', compact('adliyeler'));
    }

    public function katipleriListele($adliyeId)
    {
        $adliye = Adliye::with(['katipler' => function($q) {
            $q->where('aktif_mi', true)
                ->withCount(['isler as islemsayisi' => fn($q2) => $q2->where('durum', 'tamamlandi')]);
        }])->findOrFail($adliyeId);

        $katipler = $adliye->katipler;

        // Katiplerin ortalama puanlarını manuel olarak hesapla
        $katipIds = $katipler->pluck('id')->toArray();
        $ortalamaPuanlar = \App\Models\KatipPuan::select('katip_id', DB::raw('AVG(puan) as ortalama_puan'))
            ->whereIn('katip_id', $katipIds)
            ->groupBy('katip_id')
            ->pluck('ortalama_puan', 'katip_id')
            ->toArray();

        // Katiplere ortalama puanlarını ekle
        $katipler->each(function ($katip) use ($ortalamaPuanlar) {
            $katip->ortalama_puan = $ortalamaPuanlar[$katip->id] ?? null;
        });

        return view('avukat.isler.katipler-liste', compact('adliye', 'katipler'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'katip_id'   => 'required|exists:katips,id',
                'adliye_id'  => 'required|exists:adliyeler,id',
                'islem_tipi' => 'required',
                'aciliyet'   => 'required',
                'aciklama'   => 'required|string',
            ]);

            $avukat = auth('avukat')->user();

            // Subscription job limit enforcement
            if (!$avukat->canCreateJob()) {
                $message = 'Paket limitiniz dolu veya aktif bir aboneliğiniz bulunmuyor.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                    ], 403);
                }
                return back()->with('danger', $message);
            }

            // Konuşma oluştur veya mevcut konuşmayı al
            $conv = Conversation::firstOrCreate([
                'avukat_id' => $avukat->id,
                'katip_id'  => $request->katip_id,
            ]);

            // İşi kaydet
            $is = Isler::create([
                'conversation_id' => $conv->id,
                'avukat_id'       => $avukat->id,
                'katip_id'        => $request->katip_id,
                'adliye_id'       => $request->adliye_id,
                'islem_tipi'      => $request->islem_tipi,
                'aciliyet'        => $request->aciliyet,
                'aciklama'        => $request->aciklama,
                'avukat_onay'     => 0,
                'katip_onay'      => 0,
                'ucret'           => 0,
            ]);

            // JobEvent kaydı
            JobEvent::create([
                'is_id'       => $is->id,
                'event_type'  => 'İş Talebi',
                'description' => 'Avukat tarafından iş oluşturuldu.',
                'metadata'    => [
                    'islem_tipi'  => $is->islem_tipi,
                    'adliye'      => optional($is->adliye)->ad,
                    'aciliyet'    => $is->aciliyet,
                ],
                'creator_type' => get_class($avukat),
                'creator_id'   => $avukat->id,
            ]);

            // Sistem mesajı oluştur
            $items = [
                "İşlem No: #{$is->id}",
                "Tür: {$is->islem_tipi}",
                "Adliye: " . optional($is->adliye)->ad,
                "Aciliyet: {$is->aciliyet}",
                "Açıklama: {$is->aciklama}",
                "Tarih: " . now()->format('d.m.Y H:i'),
            ];
            $html = '<div class="system-notification">';
            $html .= '<strong>🆕 İş Oluşturuldu</strong>';
            $html .= '<ul class="job-details">';
            foreach ($items as $i) {
                $html .= "<li style=''>{$i}</li>";
            }
            $html .= '</ul></div>';

            // Mesajı kaydet ve yayınla
            $message = Message::create([
                'conversation_id' => $conv->id,
                'sender_type'     => 'Avukat',
                'sender_id'       => $avukat->id,
                'receiver_type'   => 'Katip',
                'receiver_id'     => $conv->katip_id,
                'message'         => $html,
            ]);

            broadcast(new \App\Events\MessageSent($message));

            // Kâtibe bildirim kaydı
            \App\Models\Notification::create([
                'user_id'   => $request->katip_id,
                'user_type' => 'App\Models\Katip',
                'is_id'           => $is->id,
                'type'            => 'is_olusturuldu',
                'message'         => "Avukat #{$avukat->username} yeni bir iş oluşturdu: {$is->islem_tipi}",
            ]);

            // Anlık bildirim yayını
            broadcast(new \App\Events\NotificationSent($request->katip_id, [
                'is_id'      => $is->id,
                'message'    => "Avukat #{$avukat->username} yeni bir iş oluşturdu: {$is->islem_tipi}",
                'created_at' => now()->format('H:i'),
            ]))->toOthers();

            // AJAX isteği için JSON yanıt dön
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'İş talebiniz ve detaylı mesajınız gönderildi.',
                    'redirect' => route('avukat.chat.index', ['conversation_id' => $conv->id]),
                ]);
            }

            // Standart HTTP isteği için yönlendirme
            return redirect()
                ->route('avukat.chat.index', ['conversation_id' => $conv->id])
                ->with('success', 'İş talebiniz ve detaylı mesajınız gönderildi.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->errors()[array_key_first($e->errors())][0],
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bir hata oluştu: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function index() {
        $islerim = Isler::where('avukat_id', Auth::guard('avukat')->id())
            ->latest()
            ->paginate(10);

        return view('avukat.isler.isler', compact('islerim'));
    }

    public function detay($id)
    {
        $avukat = auth('avukat')->user();

        $is = \App\Models\Isler::where('avukat_id', $avukat->id)
            ->with([
                'adliye',
                'katip',
                'avukatPuanlar',
                'events.creator.avatar',
                'teklifler.katip.avatar',
                'teslimatlar.katip.avatar',
                'avukatPuanlar.avukat.avatar',
                'katipPuanlar.katip.avatar',
            ])
            ->findOrFail($id);

        return view('avukat.isler.detay', compact('is'));
    }

    public function duzenle($id) {
        $is = Isler::where('avukat_id', auth('avukat')->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('avukat.isler.duzenle', compact('is'));
    }

    public function guncelle(Request $request, $id) {
        $request->validate([
            'aciklama' => 'required|string|max:1000',
        ]);

        $is = Isler::where('avukat_id', auth('avukat')->id())
            ->where('id', $id)
            ->firstOrFail();

        $is->aciklama = $request->aciklama;
        $is->save();

        return redirect()->route('avukat.isler.detay', $id)
            ->with('success', 'İş açıklaması başarıyla güncellendi!');
    }

    public function puanla(Request $request, $id)
    {
        $request->validate([
            'puan'  => 'required|integer|min:1|max:5',
            'yorum' => 'nullable|string|max:1000',
        ]);

        $is = Isler::where('avukat_id', auth('avukat')->id())
            ->where('id', $id)
            ->firstOrFail();

        if (!$is->avukat_onay) {
            return back()->with('error', 'Önce işi onaylamalısınız.');
        }

        if ($is->avukatPuan) {
            return back()->with('info', 'Bu iş için zaten puan verdiniz.');
        }

        $avukat = auth('avukat')->user(); // oturumdaki avukat

        AvukatPuan::create([
            'is_id'     => $is->id,
            'avukat_id' => $avukat->id,
            'puan'      => $request->puan,
            'yorum'     => $request->yorum,
        ]);

        // 1) job_events tablosuna event kaydı yazalım
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Avukat Puanladı',
            'description'  => 'Avukat işe puan verdi. Verilen puan: ' . $request->puan,
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($avukat),
            'creator_id'   => $avukat->id,
        ]);

        return redirect()->route('avukat.isler.detay', $id)->with('success', 'Puan başarıyla verildi.');
    }
    public function onayla(Request $request, $id) {


        $is = Isler::where('avukat_id', auth('avukat')->id())
            ->where('id', $id)
            ->firstOrFail();

        // zaten onaylanmışsa tekrar onaylama
        if ($is->avukat_onay) {
            return redirect()->back()->with('danger', 'Bu iş zaten onaylanmış.');
        }

        // iş onaylanıyor
        $is->avukat_onay = 1;
        $is->save();

        $avukat  = auth('avukat')->user();          // oturumdaki katip


        // 1) job_events tablosuna event kaydı yazalım
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Avukat Onay',             // daha anlamlı bir anahtar
            'description'  => 'Avukat işi onayladı.',
            'metadata'     => [                         // ↓ JobEvent::$casts içinde 'metadata'=>'array' olmalı
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($avukat),
            'creator_id'   => $avukat->id,
        ]);

        // === mesajlaşma ===
        $conversation = Conversation::firstOrCreate([
            'avukat_id' => $avukat->id,
            'katip_id'  => $is->katip_id,
        ]);

        $html = '<div class="">
        <strong>✅ İş Onaylandı</strong>
        <p>Avukat <code>#' . $avukat->username . '</code> işi inceledi ve onayladı.</p>
    </div>';

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'Avukat',
            'sender_id'       => $avukat->id,
            'receiver_type'   => 'Katip',
            'receiver_id'     => $is->katip_id,
            'message'         => $html,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return redirect()->route('avukat.isler.detay', $id)->with('success', 'İş başarıyla onaylandı.');
    }



    public function teklifKabul(Request $request, $is_id, $teklif_id)
    {
        return back()->with('danger', 'Teklif akışı devre dışı bırakıldı.');
    }

    public function teklifReddet(Request $request, $is_id, $teklif_id)
    {
        return back()->with('danger', 'Teklif akışı devre dışı bırakıldı.');
    }

    public function ajaxTeklifOnayla($teklifId)
    {
        return response()->json(['success' => false, 'error' => 'Teklif akışı devre dışı bırakıldı.'], 410);
    }

    public function ajaxTeklifReddet($teklifId)
    {
        return response()->json(['success' => false, 'error' => 'Teklif akışı devre dışı bırakıldı.'], 410);
    }

    public function markAsRead(Request $request)
    {
        $avukat = auth('avukat')->user();

        // Tüm okunmamış bildirimleri okundu olarak işaretle
        $avukat->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Tüm bildirimler okundu olarak işaretlendi.'
        ]);
    }
}
