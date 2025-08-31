<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIslerTable extends Migration
{
    public function up()
    {
        Schema::create('isler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avukat_id'); // işi oluşturan avukat
            $table->unsignedBigInteger('katip_id')->nullable(); // işi alacak katip (ilk başta null olabilir)
            $table->string('adliye');
            $table->string('islem_tipi'); // Evrak Takibi, Duruşma Listesi vs.
            $table->text('aciklama')->nullable();
            $table->enum('durum', ['bekliyor', 'devam ediyor', 'tamamlandi', 'iptal'])->default('bekliyor');
            $table->decimal('ucret', 10, 2)->default(0); // ₺ iş ücreti
            $table->boolean('avukat_onay')->default(0); // Avukat işin tamamlandığını onayladı mı
            $table->boolean('katip_onay')->default(0);  // Katip işi kabul etti mi
            $table->timestamp('is_tamamlandi_at')->nullable(); // işin tam bitiş zamanı
            $table->timestamps();

            // İlişkiler
            $table->foreign('avukat_id')->references('id')->on('avukats')->onDelete('cascade');
            $table->foreign('katip_id')->references('id')->on('katips')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('isler');
    }
}
