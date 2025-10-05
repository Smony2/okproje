<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Başlangıç',
                'slug' => 'baslangic',
                'description' => 'Yeni başlayan avukatlar için temel paket',
                'price' => 0.00,
                'monthly_price' => 0.00,
                'duration_days' => 30,
                'max_jobs' => 5,
                
                'priority_support' => false,
                'advanced_analytics' => false,
                'custom_branding' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Profesyonel',
                'slug' => 'profesyonel',
                'description' => 'Aktif kullanım için ideal paket',
                'price' => 199.90,
                'monthly_price' => 199.90,
                'duration_days' => 30,
                'max_jobs' => 50,
                
                'priority_support' => true,
                'advanced_analytics' => true,
                'custom_branding' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kurumsal',
                'slug' => 'kurumsal',
                'description' => 'Büyük ölçekli kullanım için sınırsız paket',
                'price' => 499.90,
                'monthly_price' => 499.90,
                'duration_days' => 30,
                'max_jobs' => null,
                
                'priority_support' => true,
                'advanced_analytics' => true,
                'custom_branding' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($data as $attributes) {
            Subscription::updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes
            );
        }
    }
}
