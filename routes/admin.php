<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AvukatController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KatipController;
use App\Http\Controllers\Admin\AdminTransactionController;

// Admin giriş yaptıktan sonra
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Settings (Sadece Superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/site-settings', [SettingsController::class, 'index'])->name('site-settings.index');
        Route::put('/site-settings', [SettingsController::class, 'update'])->name('site-settings.update');
    });

});

// Profil Ayarları ve Şifre Değişimi (Herkese Açık)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/password/change', [AdminController::class, 'changePasswordForm'])->name('admin.password.change');
    Route::put('/password/update', [AdminController::class, 'changePassword'])->name('admin.password.update');
});

// Yöneticiler Yönetimi (Sadece Superadmin)
Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/yoneticiler', [AdminController::class, 'index'])->name('yoneticiler.index');
    Route::get('/yoneticiler/create', [AdminController::class, 'create'])->name('yoneticiler.create');
    Route::post('/yoneticiler', [AdminController::class, 'store'])->name('yoneticiler.store');
    Route::get('/yoneticiler/{admin}/edit', [AdminController::class, 'edit'])->name('yoneticiler.edit');
    Route::put('/yoneticiler/{admin}', [AdminController::class, 'update'])->name('yoneticiler.update');
    Route::delete('/yoneticiler/{admin}', [AdminController::class, 'destroy'])->name('yoneticiler.destroy');
});

// Login Ekranları (auth yok burada)
Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
});

Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-log.show');
});


// Yöneticiler Avukat Yönetimi (Sadece Superadmin)
Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/avukatlar', [AvukatController::class, 'index'])->name('avukatlar.index');
    Route::get('/avukatlar/create', [AvukatController::class, 'create'])->name('avukatlar.create');
    Route::post('/avukatlar', [AvukatController::class, 'store'])->name('avukatlar.store');
    Route::get('/avukatlar/{id}', [AvukatController::class, 'show'])->name('avukatlar.show');
    Route::get('/avukatlar/{id}/edit', [AvukatController::class, 'edit'])->name('avukatlar.edit');
    Route::put('/avukatlar/{id}', [AvukatController::class, 'update'])->name('avukatlar.update');
    Route::delete('/avukatlar/{id}', [AvukatController::class, 'destroy'])->name('avukatlar.destroy');

    Route::patch('/avukatlar/{id}/ban',   [AvukatController::class,'ban'])->name('avukatlar.ban');
    Route::patch('/avukatlar/{id}/unban', [AvukatController::class,'unban'])->name('avukatlar.unban');
});


// Yöneticiler Avukat Yönetimi (Sadece Superadmin)
Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/katipler', [KatipController::class, 'index'])->name('katipler.index');


    Route::get('/katipler/create', [KatipController::class, 'create'])->name('katipler.create');
    Route::post('/katipler', [KatipController::class, 'store'])->name('katipler.store');

    Route::get('/katipler/{id}', [KatipController::class, 'show'])->name('katipler.show');
    Route::get('/katipler/{id}/edit', [KatipController::class, 'edit'])->name('katipler.edit');
    Route::put('/katipler/{id}', [KatipController::class, 'update'])->name('katipler.update');
    Route::delete('/katipler/{id}', [KatipController::class, 'destroy'])->name('katipler.destroy');

    Route::patch('/katipler/{id}/ban',   [KatipController::class,'ban'])->name('katipler.ban');
    Route::patch('/katipler/{id}/unban', [KatipController::class,'unban'])->name('katipler.unban');

    Route::patch('/katipler/{katip}/syncadliyeler', [KatipController::class,'syncAdliyeler'])->name('katipler.syncadliyeler');


});

// Adliye Yönetimi (Sadece Superadmin)
Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/adliyeler', [\App\Http\Controllers\Admin\AdliyeController::class, 'index'])->name('adliyeler.index');
    Route::get('/adliyeler/create', [\App\Http\Controllers\Admin\AdliyeController::class, 'create'])->name('adliyeler.create');
    Route::post('/adliyeler', [\App\Http\Controllers\Admin\AdliyeController::class, 'store'])->name('adliyeler.store');
    Route::get('/adliyeler/{id}/edit', [\App\Http\Controllers\Admin\AdliyeController::class, 'edit'])->name('adliyeler.edit');
    Route::put('/adliyeler/{id}', [\App\Http\Controllers\Admin\AdliyeController::class, 'update'])->name('adliyeler.update');
    Route::delete('/adliyeler/{id}', [\App\Http\Controllers\Admin\AdliyeController::class, 'destroy'])->name('adliyeler.destroy');
});

Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/avatarlar', [\App\Http\Controllers\Admin\AvatarController::class, 'index'])->name('avatarlar.index');
    Route::get('/avatarlar/create', [\App\Http\Controllers\Admin\AvatarController::class, 'create'])->name('avatarlar.create');
    Route::post('/avatarlar', [\App\Http\Controllers\Admin\AvatarController::class, 'store'])->name('avatarlar.store');
    Route::get('/avatarlar/{id}/edit', [\App\Http\Controllers\Admin\AvatarController::class, 'edit'])->name('avatarlar.edit');
    Route::put('/avatarlar/{id}', [\App\Http\Controllers\Admin\AvatarController::class, 'update'])->name('avatarlar.update');
    Route::delete('/avatarlar/{id}', [\App\Http\Controllers\Admin\AvatarController::class, 'destroy'])->name('avatarlar.destroy');
});



Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/katip-avatarlar', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'index'])->name('katip-avatarlar.index');
    Route::get('/katip-avatarlar/create', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'create'])->name('katip-avatarlar.create');
    Route::post('/katip-avatarlar', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'store'])->name('katip-avatarlar.store');
    Route::get('/katip-avatarlar/{id}/edit', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'edit'])->name('katip-avatarlar.edit');
    Route::put('/katip-avatarlar/{id}', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'update'])->name('katip-avatarlar.update');
    Route::delete('/katip-avatarlar/{id}', [\App\Http\Controllers\Admin\KatipAvatarController::class, 'destroy'])->name('katip-avatarlar.destroy');
});



Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/odeme-talep', [AdminTransactionController::class, 'index'])->name('odeme.index');
    Route::post('/odeme/onayla/{id}', [AdminTransactionController::class, 'approve'])->name('odeme.approve');
    Route::post('/odeme/reddet/{id}', [AdminTransactionController::class, 'reject'])->name('odeme.reject');
});
Route::middleware(['auth:admin', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {

    // İşler
    Route::get('isler', [\App\Http\Controllers\Admin\AdminIslerController::class, 'index'])->name('isler.index');
    Route::get('isler/{id}', [\App\Http\Controllers\Admin\AdminIslerController::class, 'show'])->name('isler.show');

    // Avukat değerlendirmeleri
    Route::get('degerlendirmeler/avukat', [\App\Http\Controllers\Admin\AdminIslerController::class, 'avukatDegerlendirmeleri'])->name('degerlendirme.avukat');

    // Katip değerlendirmeleri
    Route::get('degerlendirmeler/katip', [\App\Http\Controllers\Admin\AdminIslerController::class, 'katipDegerlendirmeleri'])->name('degerlendirme.katip');

    // İş teklifleri
    Route::get('teklifler', [\App\Http\Controllers\Admin\AdminIslerController::class, 'teklifler'])->name('teklifler.index');

    // İş teslimleri
    Route::get('teslimler', [\App\Http\Controllers\Admin\AdminIslerController::class, 'teslimler'])->name('teslimler.index');

    // Katip kazançları
    Route::get('kazanc/katip', [\App\Http\Controllers\Admin\AdminIslerController::class, 'katipKazanc'])->name('kazanc.katip');

});
