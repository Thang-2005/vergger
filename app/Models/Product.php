<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{   use HasFactory;
     protected $fillable = [
        'name',
        'slug ',
        'price',
        'description',
        'category_id',
        'stock',
        'status',
        'unit',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }

        public function image(){
        return $this->hasMany(ProductImage::class);
    }

    public function review(){
        return $this->hasMany(Review::class);
    }
    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }
    public function fristImage(){
        return $this->hasOne(ProductImage::class)->orderBy('id', 'asc');
    }
    
}
