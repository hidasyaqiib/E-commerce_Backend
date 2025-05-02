<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Buat user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
            ]
        );

        // Assign role
        $admin->assignRole($role);
    }
}
