<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // 1 cart item thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 cart item thuộc về 1 product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
