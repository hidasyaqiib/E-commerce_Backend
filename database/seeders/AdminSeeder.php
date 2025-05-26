<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'admin')->first();

        if (!$role) {
            $this->command->error('Role "admin" belum tersedia. Jalankan RoleSeeder terlebih dahulu.');
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
            ]
        );

        $admin->assignRole($role);
    }
}
