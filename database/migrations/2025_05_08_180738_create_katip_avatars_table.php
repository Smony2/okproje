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
        Schema::create('katip_avatars', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->unsignedBigInteger('katip_id')->nullable()->unique();
            $table->timestamps();

            $table->foreign('katip_id')->references('id')->on('katips')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('katip_avatars');
    }
};
