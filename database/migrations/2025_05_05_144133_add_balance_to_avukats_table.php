<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('avukats', function (Blueprint $table) {
            // 10 hane, 2 ondalık; varsayılan 0
            $table->decimal('balance', 10, 2)
                ->default(0)
                ->after('remember_token');
        });
    }

    public function down()
    {
        Schema::table('avukats', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};

