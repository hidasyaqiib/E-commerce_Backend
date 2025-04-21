<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_customer',
        'name',
        'email',
        'phone',
        'address',
        'grand_total',
        'payment_method',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaction::class, 'transaction_id');
    }
}
