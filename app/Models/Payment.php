<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
         protected $fillable = [
        'order_id',
        'payment_method',
        'vnp_txn_ref',
        'transaction_id',
        'amount',
        'status',
        'paid_at',
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    
}
