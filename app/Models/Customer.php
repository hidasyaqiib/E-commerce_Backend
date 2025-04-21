<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }
}
