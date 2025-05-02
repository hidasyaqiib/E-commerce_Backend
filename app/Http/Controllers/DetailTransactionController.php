<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use App\Models\Transaction;

class DetailTransactionController extends Controller
{
    public function index()
    {
        $details = DetailTransaction::with(['transaction.customer', 'product'])->get();
        return response()->json($details);
    }

    public function show($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $details = DetailTransaction::where('transaction_id', $transaction_id)->with('product')->get();

        return response()->json([
            'transaction' => $transaction,
            'details' => $details,
        ]);
    }

    public function store(Request $request)
    {
        // Hanya admin yang boleh akses
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validasi input
    $validated = $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Simpan detail transaksi
    $detail = DetailTransaction::create([
        'transaction_id' => $validated['transaction_id'],
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
        'status' => 'pending',
    ]);

    // Ambil data transaksi & detail untuk ditampilkan kembali
    $transaction = Transaction::findOrFail($validated['transaction_id']);
    $details = DetailTransaction::where('transaction_id', $validated['transaction_id'])
        ->with('product')
        ->get();

    return response()->json([
        'message' => 'Detail transaction added successfully',
        'transaction' => $transaction,
        'details' => $details,
    ], 201);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = DetailTransaction::findOrFail($id);
        $detail->quantity = $request->quantity;
        $detail->save();

        return response()->json([
            'message' => 'Detail transaction updated successfully',
            'detail' => $detail,
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $detail = DetailTransaction::findOrFail($id);
        $detail->delete();

        return response()->json(['message' => 'Detail transaction deleted successfully']);
    }
}

