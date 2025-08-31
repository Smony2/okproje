<?php
// database/migrations/2025_05_15_000000_create_job_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobEventsTable extends Migration
{
    public function up()
    {
        Schema::create('job_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('is_id')->constrained('isler')->onDelete('cascade');

            $table->string('event_type');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            // creator_type ve creator_id sütunlarını otomatik ekler
            $table->morphs('creator');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_events');
    }
}
