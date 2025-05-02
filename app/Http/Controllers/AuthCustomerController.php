<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthCustomerService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;

class AuthCustomerController extends Controller
{
    protected $authService;
    protected $customerService;

    public function __construct(AuthCustomerService $authService, CustomerService $customerService)
    {
        $this->authService = $authService;
        $this->customerService = $customerService;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // <- validasi ke tabel users
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $result = $this->authService->register($request->all());

        return response()->json([
            'message' => 'Customer registered successfully',
            'user' => $result['user'],
            'customer' => $result['customer'],
            'token' => $result['token'],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->email, $request->password);

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'customer' => $result['customer'],
            'token' => $result['token'],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string',
        ]);

        // Update data user
        $user->update($request->only('email'));

        // Update data customer
        $customer->update($request->only('name', 'phone', 'address'));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            'customer' => $customer,
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        return response()->json([
            'user' => $user,
            'customer' => $customer,
            'role' => $user->getRoleNames()
        ]);
    }
}
