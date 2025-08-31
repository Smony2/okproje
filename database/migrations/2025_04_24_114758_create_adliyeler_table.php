<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adliyeler', function (Blueprint $table) {
            $table->id();
            $table->string('ad'); // Adliye Adı
            $table->string('ilce')->nullable();
            $table->string('il')->default('İstanbul');
            $table->text('adres')->nullable();
            $table->boolean('aktif_mi')->default(true);
            $table->integer('katip_sayisi')->default(0);
            $table->integer('sira_no')->default(0);
            $table->string('harita_linki')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adliyeler');
    }
};
