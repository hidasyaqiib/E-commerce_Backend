<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AuthCustomerService
{
    public function register(array $data)
    {
        // 1. Buat user baru
        $user = User::create([
            'name' => $data['name'], // Optional
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // 2. Beri role customer
        $user->assignRole('customer');

        // 3. Buat data customer dan hubungkan ke user
        $customer = $user->customer()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        // 4. Buat token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function login($email, $password)
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            abort(401, 'Invalid login credentials');
        }

        $user = Auth::user();
        $customer = $user->customer;
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
    }
}
