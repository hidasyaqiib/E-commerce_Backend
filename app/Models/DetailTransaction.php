<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'transaction_id',
        'product_id',
        'quantity',
        'subtotal',
        'status'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
