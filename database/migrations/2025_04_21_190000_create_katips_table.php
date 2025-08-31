<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatipsTable extends Migration
{
    public function up()
    {
        Schema::create('katips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('tc_no')->unique();
            $table->string('password');
            $table->string('avatar_url')->nullable();
            $table->string('adres')->nullable();
            $table->string('adliyet')->nullable();     // Eklendi
            $table->string('il')->nullable();           // Eklendi
            $table->string('unvan')->nullable();        // Katip, Başkatip vb.
            $table->boolean('aktif_mi')->default(1);
            $table->date('dogum_tarihi')->nullable();
            $table->string('cinsiyet')->nullable();
            $table->string('mezuniyet_okulu')->nullable();
            $table->integer('mezuniyet_yili')->nullable();
            $table->string('uzmanlik_alani')->nullable();
            $table->float('puan', 8, 2)->default(0);
            $table->timestamp('son_giris_at')->nullable();
            $table->integer('giris_sayisi')->default(0);
            $table->boolean('blokeli_mi')->default(0);
            $table->text('notlar')->nullable();
            $table->float('toplam_yildiz', 3, 2)->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Silinmiş verileri korumak için
        });
    }

    public function down()
    {
        Schema::dropIfExists('katips');
    }
}
