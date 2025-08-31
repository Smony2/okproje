<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\KatipPuan;
use App\Models\IsTeklifi;
use App\Models\IsTeslimat;
use App\Models\JobEvent;
use Illuminate\Http\Request;
use App\Models\Isler;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IslerController extends Controller
{
    public function islertumu()
    {
        $katip = auth('katip')->user();

        $isler = Isler::where('katip_id', $katip->id)
            ->with([
                'adliye',
                'avukat',
                'katipPuanlar',
                'avukatPuanlar',
                'events.creator.avatar',
                'teklifler.katip.avatar',
                'teslimatlar.katip.avatar',
                'avukatPuanlar.avukat.avatar',
                'katipPuanlar.katip.avatar',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('katip.isler.tumu', compact('isler'));
    }

    public function isDetay($id)
    {
        $katip = auth('katip')->user();

        $islem = Isler::where('katip_id', $katip->id)
            ->where('id', $id)
            ->with([
                'adliye',
                'avukat',
                'katipPuanlar',
                'avukatPuanlar',
                'events.creator.avatar',
                'teklifler.katip.avatar',
                'teslimatlar.katip.avatar',
                'avukatPuanlar.avukat.avatar',
                'katipPuanlar.katip.avatar',
            ])
            ->firstOrFail();

        return view('katip.isler.detay', compact('islem'));
    }

    // Mevcut Onayla (Form tabanlı)
    public function onayla($id)
    {
        $is = Isler::where('id', $id)
            ->where('katip_id', auth('katip')->user()->id)
            ->where('durum', 'bekliyor')
            ->firstOrFail();

        // Durumu güncelle
        $is->update([
            'katip_onay' => true,
            'durum'      => 'devam ediyor',
        ]);

        $katip = auth('katip')->user();

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Katip Onayı',
            'description'  => 'Katip işi onayladı ve devreye aldı.',
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Konuşma kaydını al veya oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $is->avukat_id,
            'katip_id'  => $is->katip_id,
        ]);


        // Sistem bildirimi şeklinde HTML mesaj hazırla
        $html = '<div>';
        $html .= '<strong>✅ Katip İşi Aldı</strong>';
        $html .= '<p>Katip tarafından <code>İş #' . $is->id . '</code> alındı ve <em>devam ediyor</em> durumuna geçti.</p>';
        $html .= '</div>';

        // Mesajı veritabanına ekle
        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $is->avukat_id,
            'message'         => $html,
        ]);

        // Avukata bildirim
        \App\Models\Notification::create([
            'user_id'   => $is->avukat_id,
            'user_type' => 'App\Models\Avukat',
            'is_id'           => $is->id,
            'type'            => 'is_onaylandi',
            'message'         => "Kâtip #{$katip->username} işinizi onayladı: {$is->islem_tipi}",
            'data'            => [
                'is_id' => $is->id,
            ],
        ]);

        // Gerçek zamanlı bildirim ve mesaj yayını
        broadcast(new \App\Events\NotificationSent($is->avukat_id, [
            'is_id'      => $is->id,
            'message'    => "Kâtip #{$katip->username} işinizi onayladı: {$is->islem_tipi}",
            'created_at' => now()->format('H:i'),
        ]))->toOthers();

        broadcast(new MessageSent($message))->toOthers();


        return back()->with('success', 'İşi başarıyla aldınız ve avukat sohbet üzerinden bilgilendirildi.');
    }

    // Mevcut Reddet (Form tabanlı)
    public function reddet($id)
    {
        $is = Isler::where('katip_id', auth('katip')->user()->id)
            ->where('id', $id)
            ->where('durum', 'bekliyor')
            ->firstOrFail();

        $is->update([
            'katip_onay' => false,
            'durum'      => 'reddedildi',
        ]);

        $katip = auth('katip')->user();

        // Konuşma kaydını al veya oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $is->avukat_id,
            'katip_id'  => $is->katip_id,
        ]);

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Katip Reddi',
            'description'  => 'Katip işi reddetti.',
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);



        // Sistem bildirimi şeklinde HTML mesaj hazırla
        $html = '<div>';
        $html .= '<strong>❌ Katip İşi Reddetti</strong>';
        $html .= '<p>Katip tarafından <code>İş #' . $is->id . '</code> reddedildi.</p>';
        $html .= '</div>';

        // Mesajı veritabanına ekle
        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $is->avukat_id,
            'message'         => $html,
        ]);

        // Avukata bildirim
        \App\Models\Notification::create([
            'user_id'   => $is->avukat_id,
            'user_type' => 'App\Models\Avukat',
            'is_id'           => $is->id,
            'type'            => 'is_reddedildi',
            'message'         => "Kâtip #{$katip->username} işinizi reddetti: {$is->islem_tipi}",
            'data'            => [
                'is_id' => $is->id,
            ],
        ]);

        // Gerçek zamanlı bildirim ve mesaj yayını
        broadcast(new \App\Events\NotificationSent($is->avukat_id, [
            'is_id'      => $is->id,
            'message'    => "Kâtip #{$katip->username} işinizi reddetti: {$is->islem_tipi}",
            'created_at' => now()->format('H:i'),
        ]))->toOthers();

        broadcast(new MessageSent($message))->toOthers();

        return redirect()->route('katip.isler.tumu')->with('error', 'İş reddedildi.');
    }

    // Yeni AJAX Onayla
    public function ajaxOnayla($id)
    {
        $katip = auth('katip')->user();
        $is = Isler::where('id', $id)
            ->where('katip_id', $katip->id)
            ->where('durum', 'bekliyor')
            ->first();

        if (!$is) {
            return response()->json(['success' => false, 'error' => 'İş bulunamadı veya onay için uygun değil.'], 422);
        }

        if ($is->katip_onay) {
            return response()->json(['success' => false, 'error' => 'Bu iş zaten onaylanmış.'], 422);
        }

        // Durumu güncelle
        $is->update([
            'katip_onay' => true,
            'durum'      => 'devam ediyor',
        ]);

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Katip Onayı',
            'description'  => 'Katip işi onayladı ve devreye aldı.',
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Konuşma kaydını al veya oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $is->avukat_id,
            'katip_id'  => $is->katip_id,
        ]);

        // Sistem bildirimi şeklinde HTML mesaj hazırla
        $html = '<div>';
        $html .= '<strong>✅ Katip İşi Aldı</strong>';
        $html .= '<p>Katip tarafından <code>İş #' . $is->id . '</code> alındı ve <em>devam ediyor</em> durumuna geçti.</p>';
        $html .= '</div>';

        // Mesajı veritabanına ekle
        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $is->avukat_id,
            'message'         => $html,
        ]);

        // Avukata bildirim
        \App\Models\Notification::create([
            'user_id'   => $is->avukat_id,
            'user_type' => 'App\Models\Avukat',
            'is_id'           => $is->id,
            'type'            => 'is_onaylandi',
            'message'         => "Kâtip #{$katip->username} işinizi onayladı: {$is->islem_tipi}",
            'data'            => [
                'is_id' => $is->id,
            ],
        ]);

        // Gerçek zamanlı bildirim ve mesaj yayını
        broadcast(new \App\Events\NotificationSent($is->avukat_id, [
            'is_id'      => $is->id,
            'message'    => "Kâtip #{$katip->username} işinizi onayladı: {$is->islem_tipi}",
            'created_at' => now()->format('H:i'),
        ]))->toOthers();

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'İşi onayladınız, lütfen teklif verin.',
            'redirect' => route('katip.isler.detay', $is->id),
        ]);
    }

    // Yeni AJAX Reddet
    public function ajaxReddet($id)
    {
        $katip = auth('katip')->user();
        $is = Isler::where('id', $id)
            ->where('katip_id', $katip->id)
            ->where('durum', 'bekliyor')
            ->first();

        if (!$is) {
            return response()->json(['success' => false, 'error' => 'İş bulunamadı veya reddetmek için uygun değil.'], 422);
        }

        if ($is->katip_onay) {
            return response()->json(['success' => false, 'error' => 'Bu iş zaten onaylanmış.'], 422);
        }

        // Durumu güncelle
        $is->update([
            'katip_onay' => false,
            'durum'      => 'reddedildi',
        ]);

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Katip Reddi',
            'description'  => 'Katip işi reddetti.',
            'metadata'     => [
                'islem_tipi' => $is->islem_tipi,
                'aciliyet'   => $is->aciliyet,
                'adliye'     => optional($is->adliye)->ad,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Konuşma kaydını al veya oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $is->avukat_id,
            'katip_id'  => $is->katip_id,
        ]);

        // Sistem bildirimi şeklinde HTML mesaj hazırla
        $html = '<div>';
        $html .= '<strong>❌ Katip İşi Reddetti</strong>';
        $html .= '<p>Katip tarafından <code>İş #' . $is->id . '</code> reddedildi.</p>';
        $html .= '</div>';

        // Mesajı veritabanına ekle
        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $is->avukat_id,
            'message'         => $html,
        ]);

        // Avukata bildirim
        \App\Models\Notification::create([
            'user_id'   => $is->avukat_id,
            'user_type' => 'App\Models\Avukat',
            'is_id'           => $is->id,
            'type'            => 'is_reddedildi',
            'message'         => "Kâtip #{$katip->username} işinizi reddetti: {$is->islem_tipi}",
            'data'            => [
                'is_id' => $is->id,
            ],
        ]);

        // Gerçek zamanlı bildirim ve mesaj yayını
        broadcast(new \App\Events\NotificationSent($is->avukat_id, [
            'is_id'      => $is->id,
            'message'    => "Kâtip #{$katip->username} işinizi reddetti: {$is->islem_tipi}",
            'created_at' => now()->format('H:i'),
        ]))->toOthers();

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => 'İş reddedildi.']);
    }

    // Teklif Ver
    public function teklifVer(Request $request, $id)
    {
        $request->validate([
            'jeton' => 'required|numeric|min:1',
            'mesaj' => 'nullable|string|max:500',
        ]);

        $katip = auth('katip')->user();

        $islem = Isler::with(['adliye'])
            ->where('id', $id)
            ->where('katip_id', $katip->id)
            ->where('katip_onay', true)
            ->where('durum', 'devam ediyor')
            ->firstOrFail();

        // Mevcut bir teklif var mı (herhangi bir durumda)?
        $mevcutTeklif = IsTeklifi::where('is_id', $id)
            ->where('katip_id', $katip->id)
            ->first();

        // Aktif bir teklif (bekliyor veya kabul) var mı?
        $aktifTeklif = $mevcutTeklif && in_array($mevcutTeklif->durum, ['bekliyor', 'kabul']);

        if ($aktifTeklif) {
            return back()->with('error', 'Bu iş için zaten aktif bir teklifiniz var.');
        }

        // Reddedilmiş bir teklif varsa güncelle, yoksa yeni oluştur
        if ($mevcutTeklif && $mevcutTeklif->durum === 'reddedildi') {
            $mevcutTeklif->update([
                'jeton'    => $request->jeton,
                'mesaj'    => $request->mesaj,
                'durum'    => 'bekliyor',
                'updated_at' => now(),
            ]);
            $teklif = $mevcutTeklif;
        } else {
            // Yeni teklif kaydı
            $teklif = IsTeklifi::create([
                'is_id'    => $id,
                'katip_id' => $katip->id,
                'jeton'    => $request->jeton,
                'mesaj'    => $request->mesaj,
                'durum'    => 'bekliyor',
            ]);
        }

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $islem->id,
            'event_type'   => 'Teklif Verildi',
            'description'  => 'Katip tarafından teklif verildi: ' . $teklif->jeton . ' jeton.',
            'metadata'     => [
                'islem_tipi' => $islem->islem_tipi,
                'adliye'     => optional($islem->adliye)->ad,
                'teklif'     => $teklif->jeton,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Mesaj oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $islem->avukat_id,
            'katip_id'  => $katip->id,
        ]);

        $html = '<div class="">';
        $html .= '<strong>📩 Teklif Verildi</strong>';
        $html .= '<p>Katip <code>#' . $katip->username . '</code> bu iş için <strong>' . $teklif->jeton . ' jeton</strong> teklif etti.</p>';
        if ($request->filled('mesaj')) {
            $html .= '<p><em>"' . e($request->mesaj) . '"</em></p>';
        }
        $html .= '</div>';

        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $islem->avukat_id,
            'message'         => $html,
        ]);

        // Bildirim kaydı
        \App\Models\Notification::create([
            'user_id'   => $islem->avukat_id,
            'user_type' => 'App\Models\Avukat',
            'is_id'     => $id,
            'type'      => 'teklif_verildi',
            'message'   => 'Katip #' . $katip->username . ' ' . $teklif->jeton . ' jeton teklif etti.',
            'data'      => [
                'teklif_id' => $teklif->id,
            ],
        ]);

        // Gerçek zamanlı bildirim ve mesaj yayını
        broadcast(new \App\Events\NotificationSent($islem->avukat_id, [
            'type'            => 'teklif_verildi',
            'teklif_id'       => $teklif->id,
            'jeton'           => $teklif->jeton,
            'katip_username'  => $katip->username,
            'message'         => 'Katip #' . $katip->username . ' ' . $teklif->jeton . ' jeton teklif etti.',
            'created_at'      => now()->format('H:i'),
        ]))->toOthers();

        broadcast(new MessageSent($message))->toOthers();

        return back()->with('success', 'Teklifiniz başarıyla gönderildi.');
    }

    // Teslimat Yap
    public function teslimForm(Request $request, $id)
    {
        $islem = Isler::where('id', $id)
            ->where('katip_id', auth('katip')->id())
            ->where('durum', 'devam ediyor')
            ->firstOrFail();

        $request->validate([
            'aciklama' => 'required|string',
            'dosya'    => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,png',
        ]);

        $katip = auth('katip')->user();

        $path = null;
        if ($request->hasFile('dosya')) {
            $file = $request->file('dosya');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $destinationPath = public_path('uploads/dosyalar');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $path = 'uploads/dosyalar/' . $filename;
        }

        // Teslimat kaydı
        IsTeslimat::create([
            'is_id'      => $islem->id,
            'katip_id'   => $katip->id,
            'aciklama'   => $request->aciklama,
            'dosya_yolu' => $path,
        ]);

        // İşi tamamlandı olarak işaretle
        $islem->update(['durum' => 'tamamlandi']);

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $islem->id,
            'event_type'   => 'Teslim Edildi',
            'description'  => 'Katip işi teslim etti.',
            'metadata'     => [
                'aciklama' => $request->aciklama,
                'dosya'    => $path ? basename($path) : null,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Mesaj oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $islem->avukat_id,
            'katip_id'  => $katip->id,
        ]);

        $html = '<div class="">';
        $html .= '<strong>📎 İş Teslim Edildi</strong>';
        $html .= '<p>Katip <code>#' . $katip->username . '</code> işi teslim etti.</p>';
        $html .= '<p><strong>Açıklama:</strong> ' . e($request->aciklama) . '</p>';
        if ($path) {
            $html .= '<p><a href="' . asset($path) . '" target="_blank">📄 Dosyayı Görüntüle</a></p>';
        }
        $html .= '</div>';

        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $islem->avukat_id,
            'message'         => $html,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return back()->with('success', 'İş teslim edildi, avukata bilgi verildi.');
    }

    // Puan Ver
    public function puanla(Request $request, $id)
    {
        $request->validate([
            'puan'  => 'required|integer|min:1|max:5',
            'yorum' => 'nullable|string|max:1000',
        ]);

        $katip = auth('katip')->user();

        $is = Isler::where('id', $id)
            ->where('katip_id', $katip->id)
            ->where('avukat_onay', true)
            ->where('durum', 'tamamlandi')
            ->firstOrFail();

        $zatenVar = KatipPuan::where('is_id', $is->id)
            ->where('katip_id', $katip->id)
            ->exists();

        if ($zatenVar) {
            return back()->with('info', 'Bu iş için zaten puan verdiniz.');
        }

        // Puan kaydı
        KatipPuan::create([
            'is_id'    => $is->id,
            'katip_id' => $katip->id,
            'puan'     => $request->puan,
            'yorum'    => $request->yorum,
        ]);

        // JobEvent kaydı
        JobEvent::create([
            'is_id'        => $is->id,
            'event_type'   => 'Kâtip Puan Verdi',
            'description'  => 'Kâtip işe puan verdi: ' . $request->puan . ' yıldız.',
            'metadata'     => [
                'puan'     => $request->puan,
                'yorum'    => $request->yorum,
                'katip'    => $katip->username,
            ],
            'creator_type' => get_class($katip),
            'creator_id'   => $katip->id,
        ]);

        // Mesaj oluştur
        $conv = Conversation::firstOrCreate([
            'avukat_id' => $is->avukat_id,
            'katip_id'  => $katip->id,
        ]);

        $html = '<div>';
        $html .= '<strong>⭐ Kâtip Puan Verdi</strong>';
        $html .= '<p>' . $request->puan . ' yıldız puan verildi.</p>';
        if ($request->filled('yorum')) {
            $html .= '<p><em>"' . e($request->yorum) . '"</em></p>';
        }
        $html .= '</div>';

        $message = Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'Katip',
            'sender_id'       => $katip->id,
            'receiver_type'   => 'Avukat',
            'receiver_id'     => $is->avukat_id,
            'message'         => $html,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return back()->with('success', 'Puan başarıyla kaydedildi.');
    }

    public function markAsRead(Request $request)
    {
        $katip = auth('katip')->user();

        // Tüm okunmamış bildirimleri okundu olarak işaretle
        $katip->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Tüm bildirimler okundu olarak işaretlendi.'
        ]);
    }
}
