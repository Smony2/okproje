<?php
// database/migrations/2025_04_30_121850_create_katip_adliye_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatipAdliyeTable extends Migration
{
    public function up()
    {
        Schema::create('katip_adliye', function (Blueprint $table) {
            $table->foreignId('katip_id')
                ->constrained('katips')      // Katip tablonuzun adı buysa
                ->cascadeOnDelete();
            $table->foreignId('adliye_id')
                ->constrained('adliyeler')   // Adliye tablonuzun adı buysa
                ->cascadeOnDelete();
            $table->primary(['katip_id', 'adliye_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('katip_adliye');
    }
}
