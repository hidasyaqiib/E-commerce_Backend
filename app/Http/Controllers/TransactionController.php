<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('customer', 'details.product')->get();
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|string',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        $grandTotal = 0;

        foreach ($request->details as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            $subtotal = $product->price * $detail['quantity'];
            $grandTotal += $subtotal;
        }

        $transaction = Transaction::create([
            'customer_id' => $request->customer_id,
            'grand_total' => $grandTotal,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        foreach ($request->details as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            DetailTransaction::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $detail['quantity'],
                'subtotal' => $product->price * $detail['quantity'],
                'status' => 'unpaid',
            ]);
        }

        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction->load('details.product')
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,canceled'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json([
            'message' => 'Transaction status updated',
            'transaction' => $transaction
        ]);
    }

    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Cannot cancel transaction that is already processed'], 400);
        }

        $transaction->status = 'canceled';
        $transaction->save();

        return response()->json([
            'message' => 'Transaction canceled successfully',
            'transaction' => $transaction
        ]);
    }
}
