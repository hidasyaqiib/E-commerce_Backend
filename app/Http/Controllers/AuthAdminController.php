<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|min:6',
            ]);

            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'Admin registered successfully'], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Nanti kamu bisa tambahin token di sini
            return response()->json([
                'message' => 'Login successful',
                'admin' => $admin,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function get()
    {
        $admins = Admin::select('id', 'name', 'email', 'created_at', 'updated_at')->get();

        return response()->json([
            'message' => 'Admins retrieved successfully',
            'data' => $admins,
            'total' => $admins->count()
        ]);
    }
}
