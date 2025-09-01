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
            // Remove redundant columns, keep only call_metadata
            $table->dropColumn(['call_room', 'call_duration', 'call_status']);
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
            $table->string('call_room')->nullable()->after('type');
            $table->integer('call_duration')->nullable()->after('call_room');
            $table->string('call_status')->nullable()->after('call_duration');
        });
    }
};
