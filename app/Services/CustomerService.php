<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function getAll()
    {
        return Customer::all();
    }   

    public function findById($id)
    {
        return Customer::find($id);
    }

    public function update($id, array $data)
    {
        $customer = Customer::find($id);
        if (!$customer) return null;

        $customer->update($data);
        return $customer;
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        if (!$customer) return false;

        $customer->delete();
        return true;
    }
}
