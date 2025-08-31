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
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('is_id')
                ->nullable()
                ->constrained('isler')
                ->onDelete('cascade')
                ->after('katip_id');
        });
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['is_id']);
            $table->dropColumn('is_id');
        });
    }
};
