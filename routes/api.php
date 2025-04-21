<?php

use App\Http\Controllers\API\CategorySwaggerController;
use App\Http\Controllers\AuthCustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// ==========================
// AUTH CUSTOMER
// ==========================
Route::post('customer/register', [AuthCustomerController::class, 'register']);
Route::post('customer/login', [AuthCustomerController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('customer/logout', [AuthCustomerController::class, 'logout']);
    Route::get('customer/profile', [AuthCustomerController::class, 'profile']);
});

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Customer CRUD - hanya admin
    Route::apiResource('/customers', CustomerController::class);

    // Category CRUD
    Route::apiResource('/categories', CategoryController::class);

    // Product CRUD
    Route::apiResource('/products', ProductController::class);

    // Transaction management (semua transaksi)
    Route::apiResource('/transactions', TransactionController::class);
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    Route::put('/transactions/{id}/cancel', [TransactionController::class, 'cancel']);

    // Detail Transaction management
    Route::apiResource('/detail-transactions', DetailTransactionController::class)->except(['show', 'index']);

    // Optional: jika admin bisa lihat semua detail transaksi
    Route::get('/detail-transactions', [DetailTransactionController::class, 'index']);
});

// ==========================
// CUSTOMER ROUTES
// ==========================
Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // Customer melihat transaksinya sendiri (bisa kamu buat nanti di TransactionController)
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions']);

    // Melihat detail transaksi mereka sendiri
    Route::get('detail-transactions/{transaction_id}', [DetailTransactionController::class, 'show']);
});

// ==========================
// Swaggger Route
// ==========================

Route::group([], function () {
    Route::get('category', [CategorySwaggerController::class, 'listCategory']);
});

