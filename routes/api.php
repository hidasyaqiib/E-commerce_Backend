<?php

use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\API\CustomerSwaggerController;
use App\Http\Controllers\API\CategorySwaggerController;
use App\Http\Controllers\API\TransactionSwaggerController;
use App\Http\Controllers\API\ProductSwaggerController;
use App\Http\Controllers\AuthCustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAdminController;

// ==========================
// AUTH ADMIN
// ==========================
Route::post('/admin/register', [AuthAdminController::class, 'register']);
Route::post('/admin/login', [AuthAdminController::class, 'login']);
Route::post('/admin/logout', [AuthAdminController::class, 'logout'])->middleware('auth:sanctum');

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
Route::middleware(['auth:sanctum'])->group(function () {
    // Admin management - show all admins
    Route::get('/admins', [AuthAdminController::class, 'get']);
    
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

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('sales-reports', [SalesReportController::class, 'index']);
        Route::get('sales-reports/{id}', [SalesReportController::class, 'show']);
        Route::post('sales-reports', [SalesReportController::class, 'store']);
        Route::put('sales-reports/{id}', [SalesReportController::class, 'update']);
        Route::delete('sales-reports/{id}', [SalesReportController::class, 'destroy']);
    });
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

    Route::get('/Customer', [CustomerSwaggerController::class, 'index']);
    Route::get('/Customer/{post}', [CustomerSwaggerController::class, 'show']);
    Route::post('/Customer', [CustomerSwaggerController::class, 'store']);
    Route::put('/Customer/{post}', [CustomerSwaggerController::class, 'update']);
    Route::delete('/Customer/{post}', [CategorySwaggerController::class, 'destroy']);

    Route::get('/category', [CategorySwaggerController::class, 'index']);
    Route::get('/category/{post}', [CategorySwaggerController::class, 'show']);
    Route::post('/category', [CategorySwaggerController::class, 'store']);
    Route::put('/category/{post}', [CategorySwaggerController::class, 'update']);
    Route::delete('/category/{post}', [CategorySwaggerController::class, 'destroy']);

    Route::get('product', [ProductSwaggerController::class, 'index']);
    Route::get('/product/{post}', [ProductSwaggerController::class, 'show']);
    Route::post('/product', [ProductSwaggerController::class, 'store']);
    Route::put('/product/{post}', [ProductSwaggerController::class, 'update']);
    Route::delete('/product/{post}', [ProductSwaggerController::class, 'destroy']);

    Route::get('Transaction', [TransactionSwaggerController::class, 'index']);
    Route::get('/Transaction/{post}', [TransactionSwaggerController::class, 'show']);
    Route::post('/Transaction', [TransactionSwaggerController::class, 'store']);
    Route::put('/Transaction/{post}', [TransactionSwaggerController::class, 'update']);
    Route::delete('/Transaction/{post}', [TransactionSwaggerController::class, 'destroy']);
});