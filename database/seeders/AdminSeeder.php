<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('yoneticiler')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'), // Şifremiz: 12345678
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
