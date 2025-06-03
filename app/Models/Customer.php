<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'user_id',    // TAMBAHKAN INI
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
