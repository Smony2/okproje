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
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // gönderen (avukat ya da katip)
            $table->string('sender_type'); // "avukat" ya da "katip"
            $table->unsignedBigInteger('receiver_id'); // alıcı (avukat ya da katip)
            $table->string('receiver_type'); // "avukat" ya da "katip"
            $table->text('message'); // mesaj içeriği
            $table->boolean('is_read')->default(false); // okunma durumu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
