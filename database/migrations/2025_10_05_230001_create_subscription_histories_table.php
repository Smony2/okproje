<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avukat_id');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('performed_by_admin_id')->nullable();
            $table->string('change_type'); // assigned, updated, unassigned, extra_jobs_added

            // Snapshots / change details
            $table->unsignedBigInteger('old_subscription_id')->nullable();
            $table->unsignedBigInteger('new_subscription_id')->nullable();
            $table->integer('old_duration_days')->nullable();
            $table->integer('new_duration_days')->nullable();
            $table->integer('old_max_jobs')->nullable();
            $table->integer('new_max_jobs')->nullable();
            $table->timestamp('old_start_date')->nullable();
            $table->timestamp('old_end_date')->nullable();
            $table->timestamp('new_start_date')->nullable();
            $table->timestamp('new_end_date')->nullable();
            $table->integer('extra_jobs_delta')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('avukat_id')->references('id')->on('avukats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_histories');
    }
};


