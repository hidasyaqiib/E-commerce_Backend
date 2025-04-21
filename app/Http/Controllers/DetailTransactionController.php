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
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = DetailTransaction::create([
            'transaction_id' => $request->transaction_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Detail transaction added successfully',
            'detail' => $detail,
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

