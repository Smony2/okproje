<?php
// database/migrations/2025_05_06_000000_create_avukat_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvukatTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('avukat_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avukat_id')
                ->constrained('avukats')
                ->cascadeOnDelete();

            $table->enum('type', ['deposit', 'withdrawal'])
                ->comment('deposit = bakiye yükleme, withdrawal = bakiye çekme veya ücret kesinti');

            $table->decimal('amount', 12, 2)
                ->comment('İşlem tutarı');

            $table->string('description')
                ->nullable()
                ->comment('İşleme ait açıklama');

            $table->enum('status', ['pending', 'completed', 'failed'])
                ->default('pending')
                ->comment('İşlem onay durumu');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avukat_transactions');
    }
}
