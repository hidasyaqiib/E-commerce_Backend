<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends Controller
{
    // Register admin baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
        ]);

        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $admin->assignRole('admin');

            return response()->json([
                'message' => 'Admin registered successfully',
                'admin' => $admin,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Login admin dan buat token Sanctum
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    // Ambil semua data admin
    public function get()
    {
        $admins = Admin::select('id', 'name', 'email', 'created_at', 'updated_at')->get();

        return response()->json([
            'message' => 'Admins retrieved successfully',
            'data' => $admins,
            'total' => $admins->count(),
        ]);
    }

    // Logout dan hapus semua token admin yang aktif
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function destroy(Request $request)
{
    $admin = $request->user();

    // Hapus semua token dulu (biar logout semua perangkat)
    $admin->tokens()->delete();

    // Hapus akun admin dari database
    $admin->delete();

    return response()->json([
        'message' => 'Admin account deleted successfully'
    ]);
}
}
