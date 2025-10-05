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
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Subscription paket adı (örn: "Temel Paket", "Premium Paket")
                $table->string('slug')->unique(); // URL dostu benzersiz anahtar
                $table->text('description')->nullable(); // Paket açıklaması
                $table->decimal('price', 10, 2); // Aylık ücret
                $table->integer('duration_days')->default(30); // Paket süresi (gün cinsinden)
                $table->integer('max_jobs')->nullable(); // Maksimum iş sayısı (null = sınırsız)
                $table->integer('max_katips')->nullable(); // Maksimum katip sayısı (null = sınırsız)
                $table->boolean('priority_support')->default(false); // Öncelikli destek
                $table->boolean('advanced_analytics')->default(false); // Gelişmiş analitikler
                $table->boolean('custom_branding')->default(false); // Özel markalama
                $table->boolean('is_active')->default(true); // Aktif mi
                $table->integer('sort_order')->default(0); // Sıralama
                $table->timestamps();
            });
        } else {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('subscriptions', 'slug')) {
                    $table->string('slug')->unique();
                }
                if (!Schema::hasColumn('subscriptions', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'price')) {
                    $table->decimal('price', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'duration_days')) {
                    $table->integer('duration_days')->default(30);
                }
                if (!Schema::hasColumn('subscriptions', 'max_jobs')) {
                    $table->integer('max_jobs')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'max_katips')) {
                    $table->integer('max_katips')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'priority_support')) {
                    $table->boolean('priority_support')->default(false);
                }
                if (!Schema::hasColumn('subscriptions', 'advanced_analytics')) {
                    $table->boolean('advanced_analytics')->default(false);
                }
                if (!Schema::hasColumn('subscriptions', 'custom_branding')) {
                    $table->boolean('custom_branding')->default(false);
                }
                if (!Schema::hasColumn('subscriptions', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                if (!Schema::hasColumn('subscriptions', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
                if (!Schema::hasColumn('subscriptions', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
