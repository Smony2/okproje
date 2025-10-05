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
        Schema::table('avukats', function (Blueprint $table) {
            if (!Schema::hasColumn('avukats', 'subscription_id')) {
                $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('avukats', 'subscription_start_date')) {
                $table->timestamp('subscription_start_date')->nullable();
            }
            if (!Schema::hasColumn('avukats', 'subscription_end_date')) {
                $table->timestamp('subscription_end_date')->nullable();
            }
            if (!Schema::hasColumn('avukats', 'subscription_is_active')) {
                $table->boolean('subscription_is_active')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avukats', function (Blueprint $table) {
            if (Schema::hasColumn('avukats', 'subscription_id')) {
                // dropForeign may fail if the constraint name differs; ignore in production rolls
                try { $table->dropForeign(['subscription_id']); } catch (\Throwable $e) {}
                $table->dropColumn('subscription_id');
            }
            if (Schema::hasColumn('avukats', 'subscription_start_date')) {
                $table->dropColumn('subscription_start_date');
            }
            if (Schema::hasColumn('avukats', 'subscription_end_date')) {
                $table->dropColumn('subscription_end_date');
            }
            if (Schema::hasColumn('avukats', 'subscription_is_active')) {
                $table->dropColumn('subscription_is_active');
            }
        });
    }
};
