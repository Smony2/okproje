<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katip_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('katip_id')->constrained()->onDelete('cascade');
            $table->foreignId('is_id')->nullable()->constrained('isler')->onDelete('set null');
            $table->string('type'); // kazanç, ödeme, ceza vb.
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('tamamlandi'); // tamamlandi, bekliyor, iptal
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katip_transactions');
    }
};
