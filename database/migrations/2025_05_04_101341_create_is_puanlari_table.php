<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('is_puanlari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('is_id')->constrained('isler')->onDelete('cascade');
            $table->unsignedBigInteger('veren_id'); // puanı veren kişi id'si (avukat/katip)
            $table->enum('veren_tipi', ['avukat', 'katip']); // kim verdi
            $table->tinyInteger('puan')->unsigned(); // 1-5 arası
            $table->text('yorum')->nullable();
            $table->timestamps();

            $table->index(['is_id', 'veren_id', 'veren_tipi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('is_puanlari');
    }
};
