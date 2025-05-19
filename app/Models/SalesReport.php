<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'total_sales',
        'total_revenue',
    ];

    // Tentukan relasi dengan model Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

