<?php

// ============================================
// 3. AUTH CUSTOMER SERVICE
// ============================================
// File: app/Services/AuthCustomerService.php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthCustomerService
{
    public function register(array $data)
    {

        // Create customer
        $customer = Customer::create([

            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
        ]);


        // Create token
        $token = $customer->createToken('customer-token')->plainTextToken;
        $customer->assignRole('customer');

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function login(string $email, string $password)
    {
        $customer = Customer::where('email', $email)->first();

        if (!$customer || !Hash::check($password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke existing tokens
        $customer->tokens()->delete();

        // Create new token
        $token = $customer->createToken('customer-token')->plainTextToken;

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }

    public function logout(Customer $customer)
    {
        $customer->tokens()->delete();
        return true;
    }
}
