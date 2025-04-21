<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = $this->customerService->getAll();
        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = $this->customerService->findById($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'email'   => 'sometimes|email|unique:customers,email,' . $id . ',id_customer',
            'phone'   => 'sometimes|string|max:15',
            'address' => 'sometimes|string',
        ]);

        $customer = $this->customerService->update($id, $request->all());

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(['message' => 'Customer updated successfully', 'data' => $customer]);
    }

    public function destroy($id)
    {
        $deleted = $this->customerService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
