<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^[0-9]+$/'],
            'address' => 'required|string',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::guard('customer')->user();
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $products = [];

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                $products[] = ['model' => $product, 'quantity' => $item['quantity']];
                $subtotal += $product->price * $item['quantity'];
            }

            if ($subtotal <= 0) {
                throw new \Exception('Subtotal must be greater than zero');
            }

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
            ]);

            foreach ($products as $item) {
                $status = $validated['payment_method'] === 'cash' ? 'pending' : 'paid';

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['model']->id,
                    'quantity' => $item['quantity'],
                    'status' => $status,
                ]);

                $item['model']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('details.product'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $transactions = Transaction::with('details.product')
            ->where('customer_id', $customer->id)
            ->get();

        return response()->json($transactions);
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $transaction = Transaction::with('details.product')
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return response()->json($transaction);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $transaction = Transaction::with('details')->findOrFail($id);

        foreach ($transaction->details as $detail) {
            $detail->update(['status' => $request->status]);
        }

        return response()->json([
            'message' => 'Transaction status updated to ' . $request->status
        ]);
    }
}
