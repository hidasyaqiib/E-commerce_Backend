<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

        protected $fillable = [
            'name',
            'store_id',
            'admin_id'
        ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
