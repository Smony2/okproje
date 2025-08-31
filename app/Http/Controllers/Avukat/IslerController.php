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
        $avukat = auth('avukat')->user();

        $is = Isler::where('avukat_id', $avukat->id)
            ->where('id', $is_id)
            ->firstOrFail();

        $teklif = IsTeklifi::where('is_id', $is->id)
            ->where('id', $teklif_id)
            ->where('durum', 'bekliyor')
            ->firstOrFail();

        // Teklif tutarını al
        $tutar = $teklif->jeton;

        // Avukatın bakiyesini kontrol et
        if ($avukat->balance < $tutar) {
            return back()->with('danger', 'Bakiyeniz yetersiz!');
        }

        // İşlemi başlat: Veritabanı bütünlüğü için transaction kullanıyoruz
        DB::beginTransaction();
        try {
            // Teklifi kabul et
            $teklif->update(['durum' => 'kabul']);

            // Diğer teklifleri reddet
            IsTeklifi::where('is_id', $is->id)
                ->where('id', '!=', $teklif->id)
                ->update(['durum' => 'reddedildi']);

            // Avukat bakiyesinden düş
            $avukat->decrement('balance', $tutar);

            // Kâtip bakiyesine ekle
            $katip = \App\Models\Katip::findOrFail($teklif->katip_id);
            $katip->increment('balance', $tutar);

            // KatipTransaction kaydı oluştur
            \App\Models\KatipTransaction::create([
                'katip_id'    => $teklif->katip_id,
                'is_id'       => $is->id,
                'type'        => 'kazanc',
                'amount'      => $tutar,
                'status'      => 'tamamlandi',
                'description' => "İş #{$is->id} için teklif kabul edildi: {$is->islem_tipi}, {$tutar} jeton",
            ]);

            // JobEvent kaydı
            JobEvent::create([
                'is_id'        => $is->id,
                'event_type'   => 'Teklif Kabul Edildi',
                'description'  => 'Avukat, kâtibin teklifini kabul etti: ' . $tutar . ' jeton.',
                'metadata'     => [
                    'islem_tipi' => $is->islem_tipi,
                    'adliye'     => optional($is->adliye)->ad,
                    'teklif'     => $tutar,
                ],
                'creator_type' => get_class($avukat),
                'creator_id'   => $avukat->id,
            ]);

            // Mesaj oluştur
            $conv = Conversation::firstOrCreate([
                'avukat_id' => $avukat->id,
                'katip_id'  => $teklif->katip_id,
            ]);

            $html = '<div class="">';
            $html .= '<strong>✅ Teklif Kabul Edildi</strong>';
            $html .= '<p>Avukat, <code>#' . $teklif->katip->username . '</code> tarafından verilen <strong>' . $tutar . ' jeton</strong> teklifini kabul etti.</p>';
            $html .= '</div>';

            $message = Message::create([
                'conversation_id' => $conv->id,
                'sender_type'     => 'Avukat',
                'sender_id'       => $avukat->id,
                'receiver_type'   => 'Katip',
                'receiver_id'     => $teklif->katip_id,
                'message'         => $html,
            ]);

            broadcast(new MessageSent($message))->toOthers();

            // Kâtibe bildirim (Opsiyonel)
            \App\Models\Notification::create([
                'user_id'   => $teklif->katip_id,
                'user_type' => 'App\Models\Katip',
                'is_id'     => $is->id,
                'type'      => 'teklif_kabul',
                'message'   => "Avukat #{$avukat->username} teklifinizi kabul etti: {$is->islem_tipi}, {$tutar} jeton",
                'data'      => [
                    'is_id'   => $is->id,
                    'teklif'  => $tutar,
                ],
            ]);

            // İşlem başarılı, commit yap
            DB::commit();
            return back()->with('success', 'Teklif başarıyla kabul edildi.');
        } catch (\Exception $e) {
            // Hata olursa rollback yap
            DB::rollBack();
            return back()->with('danger', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function teklifReddet(Request $request, $is_id, $teklif_id)
    {
        $avukat = auth('avukat')->user();

        $is = \App\Models\Isler::where('avukat_id', $avukat->id)
            ->where('id', $is_id)
            ->firstOrFail();

        $teklif = \App\Models\IsTeklifi::where('is_id', $is->id)
            ->where('id', $teklif_id)
            ->where('durum', 'bekliyor')
            ->firstOrFail();

        // Teklifi reddet
        $teklif->update(['durum' => 'reddedildi']);

        // JobEvent kaydı
        \App\Models\JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Teklif Reddedildi',
            'description'  => 'Avukat, kâtibin teklifini reddetti: ' . $teklif->jeton . ' jeton.',
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'adliye'     => optional($is->adliye)->ad,
                'teklif'     => $teklif->jeton,
            ],
            'creator_type' => get_class($avukat),
            'creator_id'   => $avukat->id,
        ]);

        // Mesaj oluştur
        $conv = \App\Models\Conversation::firstOrCreate([
            'avukat_id' => $avukat->id,
            'katip_id'  => $teklif->katip_id,
        ]);

        $html = '<div class="">';
        $html .= '<strong>❌ Teklif Reddedildi</strong>';
        $html .= '<p>Avukat, <code>#' . $teklif->katip->username . '</code> tarafından verilen <strong>' . $teklif->jeton . ' jeton</strong> teklifini reddetti.</p>';
        $html .= '</div>';

        $message = \App\Models\Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Avukat',
            'sender_id'       => $avukat->id,
            'receiver_type'   => 'Katip',
            'receiver_id'     => $teklif->katip_id,
            'message'         => $html,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return back()->with('success', 'Teklif reddedildi.');
    }


    public function ajaxTeklifOnayla($teklifId)
    {
        $avukat = auth('avukat')->user();

        // Teklif ve ilgili işi al
        $teklif = \App\Models\IsTeklifi::where('id', $teklifId)
            ->whereHas('isleri', function ($query) use ($avukat) {
                $query->where('avukat_id', $avukat->id);
            })
            ->with('isleri', 'katip') // İlişkileri yükle
            ->firstOrFail();

        if ($teklif->durum !== 'bekliyor') {
            return response()->json(['success' => false, 'error' => 'Bu teklif zaten işlenmiş.'], 422);
        }

        // Teklifi kabul et
        $teklif->update(['durum' => 'kabul']);

        // Diğer teklifleri reddet
        \App\Models\IsTeklifi::where('is_id', $teklif->is_id)
            ->where('id', '!=', $teklif->id)
            ->update(['durum' => 'reddedildi']);


        $is = \App\Models\Isler::where('avukat_id', $avukat->id)
            ->where('id', $teklif->is_id)
            ->firstOrFail();

        // JobEvent kaydı
        \App\Models\JobEvent::create([
            'is_id'        => $teklif->is_id,
            'event_type'   => 'Teklif Kabul Edildi',
            'description'  => "Avukat, kâtibin teklifini kabul etti: {$teklif->jeton} jeton.",
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'adliye'     => optional($is->adliye)->ad,
                'teklif'     => $teklif->jeton,
            ],
            'creator_type' => get_class($avukat),
            'creator_id'   => $avukat->id,
        ]);

        // Mesaj oluştur
        $conv = \App\Models\Conversation::firstOrCreate([
            'avukat_id' => $avukat->id,
            'katip_id'  => $teklif->katip_id,
        ]);

        $html = '<div>';
        $html .= '<strong>✅ Teklif Kabul Edildi</strong>';
        $html .= '<p>Avukat, <code>#' . ($teklif->katip->username ?? 'Bilinmeyen Kâtip') . '</code> tarafından verilen <strong>' . $teklif->jeton . ' jeton</strong> teklifini kabul etti.</p>';
        $html .= '</div>';

        $message = \App\Models\Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Avukat',
            'sender_id'       => $avukat->id,
            'receiver_type'   => 'Katip',
            'receiver_id'     => $teklif->katip_id,
            'message'         => $html,
        ]);

        // Kâtibe bildirim
        \App\Models\Notification::create([
            'user_id'   => $teklif->katip_id,
            'user_type' => 'App\Models\Katip',
            'is_id'           => $is->id,
            'type'            => 'teklif_onaylandi',
            'message'         => "Avukat #{$avukat->username} teklifinizi onayladı: {$teklif->jeton} jeton.",
            'data'            => [
                'teklif_id' => $teklif->id,
                'is_id'     => $is->id,
            ],
        ]);


        // Gerçek zamanlı bildirim yayını (Kâtip için)
        broadcast(new \App\Events\NotificationSent($teklif->katip_id, [
            'is_id'      => $is->id,
            'teklif_id'  => $teklif->id,
            'type'       => 'teklif_onaylandi',
            'message'    => "Avukat #{$avukat->username} teklifinizi onayladı: {$teklif->jeton} jeton.",
            'created_at' => now()->format('H:i'),
            'katip_username' => $teklif->katip->username ?? 'Bilinmeyen Kâtip',
            'jeton'         => $teklif->jeton,
        ]))->toOthers();


        // Mesaj yayını
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => 'Teklif onaylandı.']);
    }

    public function ajaxTeklifReddet($teklifId)
    {
        $avukat = auth('avukat')->user();

        $teklif = \App\Models\IsTeklifi::where('id', $teklifId)
            ->whereHas('isleri', function ($query) use ($avukat) {
                $query->where('avukat_id', $avukat->id);
            })
            ->with('isleri', 'katip') // İlişkileri yükle
            ->firstOrFail();

        if ($teklif->durum !== 'bekliyor') {
            return response()->json(['success' => false, 'error' => 'Bu teklif zaten işlenmiş.'], 422);
        }

        // Teklifi red et
        $teklif->update(['durum' => 'reddedildi']);



        $is = \App\Models\Isler::where('avukat_id', $avukat->id)
            ->where('id', $teklif->is_id)
            ->firstOrFail();

        // JobEvent kaydı
        \App\Models\JobEvent::create([
            'is_id'        => $teklif->is_id,
            'event_type'   => 'Teklif Red Edildi',
            'description'  => "Avukat, kâtibin teklifini red etti: {$teklif->jeton} jeton.",
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'adliye'     => optional($is->adliye)->ad,
                'teklif'     => $teklif->jeton,
            ],
            'creator_type' => get_class($avukat),
            'creator_id'   => $avukat->id,
        ]);

        // Mesaj oluştur
        $conv = \App\Models\Conversation::firstOrCreate([
            'avukat_id' => $avukat->id,
            'katip_id'  => $teklif->katip_id,
        ]);

        $html = '<div>';
        $html .= '<strong>✅ Teklif Red Edildi</strong>';
        $html .= '<p>Avukat, <code>#' . ($teklif->katip->username ?? 'Bilinmeyen Kâtip') . '</code> tarafından verilen <strong>' . $teklif->jeton . ' jeton</strong> teklifini red etti.</p>';
        $html .= '</div>';

        $message = \App\Models\Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Avukat',
            'sender_id'       => $avukat->id,
            'receiver_type'   => 'Katip',
            'receiver_id'     => $teklif->katip_id,
            'message'         => $html,
        ]);

        // Kâtibe bildirim
        \App\Models\Notification::create([
            'user_id'   => $teklif->katip_id,
            'user_type' => 'App\Models\Katip',
            'is_id'           => $is->id,
            'type'            => 'teklif_reddedildi',
            'message'         => "Avukat #{$avukat->username} teklifinizi reddetti: {$teklif->jeton} jeton.",
            'data'            => [
                'teklif_id' => $teklif->id,
                'is_id'     => $is->id,
            ],
        ]);


        // Gerçek zamanlı bildirim yayını (Kâtip için)
        broadcast(new \App\Events\NotificationSent($teklif->katip_id, [
            'is_id'      => $is->id,
            'teklif_id'  => $teklif->id,
            'type'       => 'teklif_reddedildi',
            'message'    => "Avukat #{$avukat->username} teklifinizi reddetti: {$teklif->jeton} jeton.",
            'created_at' => now()->format('H:i'),
            'katip_username' => $teklif->katip->username ?? 'Bilinmeyen Kâtip',
            'jeton'         => $teklif->jeton,
        ]))->toOthers();


        // Mesaj yayını
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => 'Teklif reddedildi.']);
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
