<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthCustomerService
{
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $customer = Customer::create($data);
        $token = $customer->createToken('customer-token')->plainTextToken;

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function login(string $email, string $password)
    {
        $customer = Customer::where('email', $email)->first();

        if (!$customer || !Hash::check($password, $customer->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }
}
