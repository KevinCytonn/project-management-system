<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'product_manager',
            'design_manager',
            'development_manager',
            'analyst',
            'designer',
            'developer',
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}