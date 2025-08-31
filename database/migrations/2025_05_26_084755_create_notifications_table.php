<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Bildirimin sahibi (avukat veya katip)
            $table->string('user_type'); // Polymorphic için: Avukat, Katip
            $table->unsignedBigInteger('is_id')->nullable(); // İlgili iş ID’si
            $table->string('type'); // Bildirim tipi (örn: teklif_verildi, teklif_durum)
            $table->text('message'); // Bildirim mesajı
            $table->timestamp('read_at')->nullable(); // Okunma tarihi
            $table->timestamps();

            // Index’ler
            $table->index(['user_id', 'user_type']);
            $table->index('is_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
