<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Katip\KatipProfileController;
use App\Http\Controllers\Katip\KatipAuthController;
use App\Http\Controllers\Katip\KatipDasboardController;

// katip giriş yaptıktan sonra
Route::middleware(['auth:katip'])->prefix('katip')->name('katip.')->group(function () {

    // Dashboard (Herkes erişebilir)
    Route::get('/dashboard', [KatipDasboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [KatipAuthController::class, 'logout'])->name('logout');

});

// Profil Ayarları ve Şifre Değişimi (Herkese Açık)
Route::middleware(['auth:katip'])->group(function () {
    Route::get('/katip/profile/edit', [KatipProfileController::class, 'editProfile'])->name('katip.profile.edit');
    Route::put('/katip/profile/update', [KatipProfileController::class, 'updateProfile'])->name('katip.profile.update');
    Route::get('katip/password/change', [KatipProfileController::class, 'changePasswordForm'])->name('katip.password.change');
    Route::put('katip/password/update', [KatipProfileController::class, 'changePassword'])->name('katip.password.update');
});


// Login Ekranları (auth yok burada)
Route::prefix('katip')->middleware('guest:katip')
    ->group(function () {
    Route::get('/login', [KatipAuthController::class, 'showLoginForm'])->name('katip.login');
    Route::post('/login', [KatipAuthController::class, 'login'])->name('katip.login.post');
});



Route::prefix('katip')->name('katip.isler.')->middleware(['auth:katip'])->group(function () {
    // İş Listeleri
    Route::get('/isler', [\App\Http\Controllers\Katip\IslerController::class, 'islertumu'])->name('tumu');
    Route::get('/tamamlanan-isler', [\App\Http\Controllers\Katip\IslerController::class, 'tamamlananIsler'])->name('tamamlanan');
    Route::get('/iade-edilen-isler', [\App\Http\Controllers\Katip\IslerController::class, 'iadeEdilenIsler'])->name('iade_edilen');

    // İş Detayı
    Route::get('/is-detay/{id}', [\App\Http\Controllers\Katip\IslerController::class, 'isDetay'])->name('detay');

    // İş Onayla ve Reddet
    Route::post('/is/{id}/onayla', [\App\Http\Controllers\Katip\IslerController::class, 'onayla'])->name('onayla');
    Route::post('/is/{id}/reddet', [\App\Http\Controllers\Katip\IslerController::class, 'reddet'])->name('reddet');


    // Yeni AJAX Onayla ve Reddet
    Route::post('/is/{id}/ajax-onayla', [\App\Http\Controllers\Katip\IslerController::class, 'ajaxOnayla'])->name('ajax-onayla');
    Route::post('/is/{id}/ajax-reddet', [\App\Http\Controllers\Katip\IslerController::class, 'ajaxReddet'])->name('ajax-reddet');

    // Teklif Ver
    Route::post('/is/{id}/teklif-ver', [\App\Http\Controllers\Katip\IslerController::class, 'teklifVer'])->name('teklif_ver');

    // Teslimat Yap
    Route::post('/is/{id}/teslimat-yap', [\App\Http\Controllers\Katip\IslerController::class, 'teslimForm'])->name('teslimat_yap');

    // Puan Ver
    Route::post('/is/{id}/puanla', [\App\Http\Controllers\Katip\IslerController::class, 'puanla'])->name('puanla');

    // Bildirimleri Okundu İşaretle
    Route::post('/notifications/mark-as-read', [\App\Http\Controllers\Katip\IslerController::class, 'markAsRead'])->name('markAsRead');
});

Route::prefix('katip')->name('katip.')->middleware(['auth:katip'])->group(function () {
    Route::get('/avukat-degerlendirmelerim', [\App\Http\Controllers\Katip\KatipDegerlendirmeController::class, 'index'])->name('degerlendirme.avukatlar');
    Route::get('/avukat/{id}/profil', [\App\Http\Controllers\Katip\KatipDegerlendirmeController::class, 'profil'])->name('avukatlar.profil');
});

Route::prefix('katip')->name('katip.')->middleware(['auth:katip'])->group(function () {

    // Kazançlarım
    Route::get('kazanclarim', [\App\Http\Controllers\Katip\KazancController::class, 'index'])->name('kazanclar');

    // Tekliflerim
    Route::get('tekliflerim', [\App\Http\Controllers\Katip\TeklifController::class, 'index'])->name('tekliflerim');

    // Teslimlerim
    Route::get('teslimlerim', [\App\Http\Controllers\Katip\TeslimController::class, 'index'])->name('teslimlerim');
});

