<?php

namespace Database\Seeders;

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
            // Insert only if it does not exist
            DB::table('roles')->updateOrInsert(
                ['name' => $role], // condition to check existing
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
