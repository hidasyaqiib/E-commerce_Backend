<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="E-Commerce API Documentation",
 *      description="Dokumentasi API e-commerce MK3",
 *      @OA\Contact(
 *          email="kamu@example.com"
 *      )
 * )
 */

class AdminSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/register",
     *     tags={"Auth - Admin"},
     *     summary="Register admin baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Admin Baru"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil register"),
     *     @OA\Response(response=500, description="Gagal register")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/api/admin/login",
     *     tags={"Auth - Admin"},
     *     summary="Login admin dan dapatkan token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login berhasil"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login() {}

    /**
     * @OA\Get(
     *     path="/api/admin/get",
     *     tags={"Admin"},
     *     summary="Ambil semua admin",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Daftar admin")
     * )
     */
    public function getAllAdmins() {}

    /**
     * @OA\Post(
     *     path="/api/admin/logout",
     *     tags={"Auth - Admin"},
     *     summary="Logout dan hapus semua token",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Berhasil logout")
     * )
     */
    public function logout() {}

    /**
     * @OA\Delete(
     *     path="/api/admin/delete",
     *     tags={"Auth - Admin"},
     *     summary="Hapus akun admin",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Berhasil menghapus akun")
     * )
     */
    public function delete() {}

    /**
     * @OA\Put(
     *     path="/api/admin/update-profile",
     *     tags={"Auth - Admin"},
     *     summary="Update profile admin",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nama Baru"),
     *             @OA\Property(property="email", type="string", example="emailbaru@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile berhasil diupdate")
     * )
     */
    public function updateProfile() {}
}
