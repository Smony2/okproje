<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Avukat\AvukatActivityLogController;
use App\Http\Controllers\Avukat\AvukatAuthController;
use App\Http\Controllers\Avukat\AvukatDasboardController;
use App\Http\Controllers\Avukat\AvukatProfileController;


// Admin giriş yaptıktan sonra
Route::middleware(['auth:avukat'])->prefix('avukat')->name('avukat.')->group(function () {

    Route::get('/dashboard', [AvukatDasboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AvukatAuthController::class, 'logout'])->name('logout');

});

// Profil Ayarları ve Şifre Değişimi (Herkese Açık)
Route::middleware(['auth:avukat','update.last.active'])->group(function () {
    Route::get('/avukat/profile/edit', [AvukatProfileController::class, 'editProfile'])->name('avukat.profile.edit');
    Route::put('/avukat/profile/update', [AvukatProfileController::class, 'updateProfile'])->name('avukat.profile.update');
    Route::get('/avukat/password/change', [AvukatProfileController::class, 'changePasswordForm'])->name('avukat.password.change');
    Route::put('/avukat/password/update', [AvukatProfileController::class, 'changePassword'])->name('avukat.password.update');
    Route::get('/avukat/avatar-sec', [AvukatProfileController::class, 'index'])->name('avukat.avatar-sec');
    Route::post('/avukat/avatar-sec', [AvukatProfileController::class, 'sec'])->name('avukat.avatar-sec.post');
});


// Login Ekranları (auth yok burada)
Route::prefix('avukat')->name('avukat.')->middleware('guest:avukat')->group(function () {
    Route::get('/login', [AvukatAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AvukatAuthController::class, 'login'])->name('login.post');

});

Route::middleware(['auth:avukat', 'avukatrole:finansadmin'])->prefix('avukat')->name('avukat.')->group(function () {
    Route::get('/activity-log', [AvukatActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{id}', [AvukatActivityLogController::class, 'show'])->name('activity-log.show');
});


// Avukat İşler Yönetimi
Route::prefix('avukat')->name('avukat.isler.')->middleware(['auth:avukat','update.last.active'])->group(function () {
    Route::get('/adliye-sec', [\App\Http\Controllers\Avukat\IslerController::class, 'adliyeSec'])->name('adliye_sec');
    Route::get('/katipler/{id}', [\App\Http\Controllers\Avukat\IslerController::class, 'katipleriListele'])->name('listele');
    Route::post('/is/store', [\App\Http\Controllers\Avukat\IslerController::class, 'store'])->name('store');
    Route::get('/islerim', [\App\Http\Controllers\Avukat\IslerController::class, 'index'])->name('index');
    Route::get('/is/{id}/detay', [\App\Http\Controllers\Avukat\IslerController::class, 'detay'])->name('detay');
    Route::get('/is/{id}/duzenle', [\App\Http\Controllers\Avukat\IslerController::class, 'duzenle'])->name('duzenle');
    Route::post('/is/{id}/guncelle', [\App\Http\Controllers\Avukat\IslerController::class, 'guncelle'])->name('guncelle');
    Route::post('/is/{id}/onayla', [\App\Http\Controllers\Avukat\IslerController::class, 'onayla'])->name('onayla');
    Route::post('/is/{id}/puanla', [\App\Http\Controllers\Avukat\IslerController::class, 'puanla'])->name('puanla');

    // Teklif Onaylama ve Reddettirme Routeları
    Route::post('/is/{is_id}/teklif/{teklif_id}/kabul', [\App\Http\Controllers\Avukat\IslerController::class, 'teklifKabul'])->name('teklifKabul');
    Route::post('/is/{is_id}/teklif/{teklif_id}/reddet', [\App\Http\Controllers\Avukat\IslerController::class, 'teklifReddet'])->name('teklifReddet');

    // AJAX Teklif Onayla ve Reddet
    Route::post('/is/{teklifId}/ajax-teklif-onayla', [\App\Http\Controllers\Avukat\IslerController::class, 'ajaxTeklifOnayla'])->name('teklif.ajax-onayla');
    Route::post('/is/{teklifId}/ajax-teklif-reddet', [\App\Http\Controllers\Avukat\IslerController::class, 'ajaxTeklifReddet'])->name('teklif.ajax-reddet');

    Route::post('/notifications/mark-as-read', [\App\Http\Controllers\Avukat\IslerController::class, 'markAsRead'])->name('notifications.markAsRead');
});

Route::prefix('avukat')->name('avukat.')->middleware(['auth:avukat'])->group(function () {
    Route::get('/katip-degerlendirmelerim', [\App\Http\Controllers\Avukat\DegerlendirmeController::class, 'index'])->name('degerlendirme.katipler');
    Route::get('/katip/{id}/profil', [\App\Http\Controllers\Avukat\DegerlendirmeController::class, 'profil'])->name('katip.profil');
    Route::get('/favori-katipler', [\App\Http\Controllers\Avukat\DegerlendirmeController::class, 'favori'])->name('favori.katipler');

});

Route::middleware('auth:avukat')->prefix('avukat')->name('avukat.')->group(function(){
    Route::get('odeme/yukle', [\App\Http\Controllers\Avukat\OdemeController::class,'create'])->name('odeme.yukle');
    Route::post('odeme/yukle', [\App\Http\Controllers\Avukat\OdemeController::class,'store'])->name('odeme.yukle.store');
    Route::get('odeme/gecmis', [\App\Http\Controllers\Avukat\OdemeController::class,'history'])->name('odeme.gecmis');
});




