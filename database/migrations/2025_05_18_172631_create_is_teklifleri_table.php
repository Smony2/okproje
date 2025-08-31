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
        Schema::create('is_teklifleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('is_id')->constrained('isler')->onDelete('cascade');
            $table->foreignId('katip_id')->constrained('katips')->onDelete('cascade');
            $table->decimal('jeton', 8, 2);
            $table->text('mesaj')->nullable();
            $table->enum('durum', ['bekliyor', 'kabul', 'reddedildi'])->default('bekliyor');
            $table->timestamps();

            $table->unique(['is_id', 'katip_id']); // aynı işe aynı katip 2 kere teklif veremesin
        });
    }

    public function down()
    {
        Schema::dropIfExists('is_teklifleri');
    }
};
