<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ana sayfa yönlendirmesi
Route::get('/', function () {
    return view('welcome');
})->name('website');


// web.php'ye ekleyin:

Route::middleware(['web', 'auth:avukat'])->prefix('avukat/mesajlar')->name('avukat.chat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'index'])->name('index');
    Route::get('/{conversation}', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/mesaj-gonder', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'sendMessage'])->name('send');
    Route::post('/{conversation}/okundu-isaretle', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'markMessagesAsRead'])->name('mark.read'); // YENİ
    Route::post('/baslat', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'startConversation'])->name('start');
    Route::post('/yeni-sohbet', [\App\Http\Controllers\Avukat\AvukatChatController::class, 'newchat'])->name('newchat');

    // Call routes
    Route::post('/{conversation}/call/invite', [\App\Http\Controllers\CallController::class, 'invite'])->name('call.invite');
    Route::post('/{conversation}/call/accept', [\App\Http\Controllers\CallController::class, 'accept'])->name('call.accept');
    Route::post('/{conversation}/call/token', [\App\Http\Controllers\CallController::class, 'token'])->name('call.token');
    Route::post('/{conversation}/call/end', [\App\Http\Controllers\CallController::class, 'end'])->name('call.end');
});

Route::middleware(['web', 'auth:katip'])->prefix('katip/mesajlar')->name('katip.chat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Katip\KatipChatController::class, 'index'])->name('index');
    Route::get('/{conversation}', [\App\Http\Controllers\Katip\KatipChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/mesaj-gonder', [\App\Http\Controllers\Katip\KatipChatController::class, 'sendMessage'])->name('send');
    Route::post('/{conversation}/okundu-isaretle', [\App\Http\Controllers\Katip\KatipChatController::class, 'markMessagesAsRead'])->name('mark.read'); // YENİ
    Route::post('/baslat', [\App\Http\Controllers\Katip\KatipChatController::class, 'startConversation'])->name('start');
    Route::post('/yeni-sohbet', [\App\Http\Controllers\Katip\KatipChatController::class, 'newchat'])->name('newchat');

    // Call routes
    Route::post('/{conversation}/call/invite', [\App\Http\Controllers\CallController::class, 'invite'])->name('call.invite');
    Route::post('/{conversation}/call/accept', [\App\Http\Controllers\CallController::class, 'accept'])->name('call.accept');
    Route::post('/{conversation}/call/token', [\App\Http\Controllers\CallController::class, 'token'])->name('call.token');
    Route::post('/{conversation}/call/end', [\App\Http\Controllers\CallController::class, 'end'])->name('call.end');
});




