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
        Schema::create('katip_puanlari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('is_id')->constrained('isler')->onDelete('cascade');
            $table->foreignId('katip_id')->constrained('katips')->onDelete('cascade');
            $table->integer('puan');
            $table->text('yorum')->nullable();
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
        Schema::dropIfExists('katip_puanlari');
    }
};
