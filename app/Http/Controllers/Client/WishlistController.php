<?php

namespace App\Http\Controllers\client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login.customer');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.firstImage')
            ->get();

        return view('clients.pages.wishlist', compact('wishlist'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message'  => 'Vui lòng đăng nhập',
                'redirect' => route('login.customer')
            ], 401);
        }
        
        $product = Product::findOrFail($request->product_id);

        Wishlist::firstOrCreate([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
        ]);

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'status'         => 'success',
            'message'        => "Đã thêm {$product->name} vào yêu thích",
            'wishlist_count' => $count,
        ]);
    }

    public function remove($id)
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wishlist->delete();

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'status'         => 'success',
            'message'        => 'Đã xóa sản phẩm khỏi yêu thích',
            'wishlist_count' => $count,
        ]);
    }

    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return response()->json([
            'status'         => 'success',
            'message'        => 'Đã xóa toàn bộ sản phẩm yêu thích',
            'wishlist_count' => 0,
        ]);
    }

    public function count()
    {
        $count = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->count()
            : 0;

        return response()->json(['count' => $count]);
    }
}