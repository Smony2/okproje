<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avukats', function (Blueprint $table) {
            if (!Schema::hasColumn('avukats', 'extra_job_credits')) {
                $table->integer('extra_job_credits')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('avukats', function (Blueprint $table) {
            if (Schema::hasColumn('avukats', 'extra_job_credits')) {
                $table->dropColumn('extra_job_credits');
            }
        });
    }
};


