<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
{
    $categories = Category::with('products.fristImage')->get();

    foreach ($categories as $category) {
        foreach ($category->products as $product) {
            $product->image_url =
                $product->fristImage && $product->fristImage->image
                ? asset('storage/uploads/product/'.$product->fristImage->image)
                : asset('storage/uploads/product/default_product.jpg');
        }
    }

    $bestSellingProduct = Product::with('fristImage')
        ->select(
            'products.*',
            DB::raw('SUM(order_items.quantity) as total_sold')
        )
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->groupBy(
            'products.id',
            'products.name',
            'products.price',
            'products.description',
            'products.category_id',
            'products.stock',
            'products.unit'
        )
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

    foreach ($bestSellingProduct as $product) {
        $product->image_url =
            $product->fristImage && $product->fristImage->image
            ? asset('storage/uploads/products/'.$product->fristImage->image)
            : asset('storage/uploads/products/default_product.png');
    }

    return view('clients.pages.home',
        compact('categories','bestSellingProduct'));
}
}