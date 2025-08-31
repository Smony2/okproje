<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRole;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'superadmin'],
            ['name' => 'Finans Admini', 'slug' => 'finansadmin'],
            ['name' => 'Yetkisiz Admin', 'slug' => 'yetkisizadmin'],
        ];

        foreach ($roles as $role) {
            AdminRole::create($role);
        }
    }
}
