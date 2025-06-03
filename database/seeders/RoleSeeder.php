<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',  // wajib set guard_name
        ]);

        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);
    }
}
