<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function show_product()
    {
        $categories = Category::with('products.firstImage')->get();
        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $product->image_url =
                    $product->firstImage && $product->firstImage->image
                    ? asset('storage/uploads/product/'.$product->firstImage->image)
                    : asset('storage/uploads/product/default_product.jpg');
            }
        }
        $products = Product::with('firstImage')->where('status', 'in_stock')->paginate(9);
        foreach ($products as $product) {
            $product->image_url =
                $product->firstImage && $product->firstImage->image
                ? asset('storage/uploads/product/'.$product->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');
                }
      $products_favorite = Product::with('firstImage')
            ->where('status', 'in_stock')
            ->inRandomOrder()
            ->first();

        if ($products_favorite) {
            $products_favorite->image_url =
                $products_favorite->firstImage && $products_favorite->firstImage->image
                ? asset('storage/uploads/product/'.$products_favorite->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');
        }
        

            
        return view('clients.pages.products', compact('categories', 'products', 'products_favorite'));
    }

   public function filter_products(Request $request)
{
    try {
        $query = Product::with('firstImage')->where('status', 'in_stock');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        switch ($request->sort_by) {
            case 'latest':
                $query->latest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // ✅ Thêm image_url giống show_product
        foreach ($products as $product) {
            $product->image_url =
                $product->firstImage && $product->firstImage->image
                ? asset('storage/uploads/product/' . $product->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');
        }

        return response()->json([
            'products'   => view('clients.components.models.product_list', compact('products'))->render(),
            'pagination' => $products->links('clients.components.panimation.panimation_customer')->render(),
            'total'      => $products->total(),
            'showing'    => $products->count(),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
        ], 500);
    }
}


    public function search_products(Request $request)
    {
        $searchTerm = $request->input('search');
        $categories = Category::with('products.firstImage')->get();
        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $product->image_url =
                    $product->firstImage && $product->firstImage->image
                    ? asset('storage/uploads/product/'.$product->firstImage->image)
                    : asset('storage/uploads/product/default_product.jpg');
            }
        }
        $products = Product::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->where('status', 'in_stock')
            ->with('firstImage')
            ->paginate(9);

        foreach ($products as $product) {
            $product->image_url =
                $product->firstImage && $product->firstImage->image
                ? asset('storage/uploads/product/'.$product->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');
        }

        $products_favorite = Product::with('firstImage')
            ->where('status', 'in_stock')
            ->inRandomOrder()
            ->first();
        if ($products_favorite) {
            $products_favorite->image_url =
                $products_favorite->firstImage && $products_favorite->firstImage->image
                ? asset('storage/uploads/product/'.$products_favorite->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');
        }

        return view('clients.pages.products', compact('products', 'categories', 'products_favorite'));
    }

   public function show_product_detail($slug)
{
    $product = Product::where('slug', $slug)
        ->with(['image', 'firstImage', 'category'])
        ->firstOrFail();

    $product->image_url =
        $product->firstImage && $product->firstImage->image
        ? asset('storage/uploads/product/'.$product->firstImage->image)
        : asset('storage/uploads/product/default_product.jpg');

    $relatedProduct = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 'in_stock')
        ->with('firstImage')
        ->take(6)
        ->get();

    foreach ($relatedProduct as $prod) {
        $prod->image_url =
            $prod->firstImage && $prod->firstImage->image
            ? asset('storage/uploads/product/'.$prod->firstImage->image)
            : asset('storage/uploads/product/default_product.jpg');
    }

    return view(
        'clients.pages.product_details',
        compact('product', 'relatedProduct')
    );
}

// public function byCategory($slug)
// {
//     $categories = Category::where('slug', $slug)->firstOrFail();

//     $products = Product::with('firstImage')
//         ->where('category_id', $categories->id)
//         ->paginate(12);

//     foreach ($products as $product) {
//         $product->image_url =
//             $product->firstImage && $product->firstImage->image
//             ? asset('storage/uploads/product/'.$product->firstImage->image)
//             : asset('storage/uploads/product/default_product.jpg');
//     }

//     return view('clients.pages.products',
//         compact('products','categories'));
// }

}
