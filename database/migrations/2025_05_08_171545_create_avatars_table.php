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
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // avatar resminin yolu
            $table->unsignedBigInteger('avukat_id')->nullable()->unique(); // bir avukat sadece bir avatara sahip olabilir
            $table->timestamps();

            $table->foreign('avukat_id')->references('id')->on('avukats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatars');
    }
};
