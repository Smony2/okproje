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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('type')->default('message')->after('message'); // 'message', 'call_started', 'call_ended', 'call_missed'
            $table->string('call_room')->nullable()->after('type'); // LiveKit room name
            $table->integer('call_duration')->nullable()->after('call_room'); // call duration in seconds
            $table->string('call_status')->nullable()->after('call_duration'); // 'answered', 'missed', 'rejected', 'ended'
            $table->json('call_metadata')->nullable()->after('call_status'); // additional call data
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['type', 'call_room', 'call_duration', 'call_status', 'call_metadata']);
        });
    }
};
