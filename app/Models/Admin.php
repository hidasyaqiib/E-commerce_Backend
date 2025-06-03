<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

     protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function salesReports()
    {
        return $this->hasMany(SalesReport::class);
    }

    public function Store()
    {
        return $this->hasOne(Store::class);
    }
}

