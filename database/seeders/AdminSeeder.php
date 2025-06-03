<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'admin')->first();

        if (!$role) {
            $this->command->error('❌ Role "admin" belum tersedia. Jalankan RoleSeeder terlebih dahulu.');
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'), // Lebih aman pakai Hash
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
            $this->command->info('✅ Admin role assigned to user admin@app.com');
        } else {
            $this->command->warn('⚠️ User admin@app.com already has admin role.');
        }
    }
}
