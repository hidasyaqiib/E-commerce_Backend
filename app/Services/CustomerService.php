<?php

// ============================================
// 4. CUSTOMER SERVICE
// ============================================
// File: app/Services/CustomerService.php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function getCustomer($id)
    {
        return Customer::findOrFail($id);
    }

    public function updateCustomer(Customer $customer, array $data)
    {
        $customer->update($data);
        return $customer->fresh();
    }

    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return true;
    }
}
