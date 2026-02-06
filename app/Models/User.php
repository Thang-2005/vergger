<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone_number',
        'avata',
        'address',
        'activation_token',
        'role_id',
        'google_id',

    ];
     public function roles()
        {
            return $this->belongTo(Permission::class);
        }
    public function review(){
        return $this->hasMany(Review::class);
    }
    public function shippingAdress(){
        return $this->hasMany(ShippingAdress::class);
    }
    // check status
    public function isPending(){
        return $this->status === 'active';
    }
    public function isActive(){
        return $this->status === 'active';
    }
    public function isBanned(){
        return $this->status === 'active';
    }
    public function isDeleted(){
        return $this->status === 'active';
    }
}
