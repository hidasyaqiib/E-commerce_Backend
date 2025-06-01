<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AuthCustomerService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole('customer');

        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

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
