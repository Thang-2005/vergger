<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ===== THÊM VÀO GIỎ =====
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng',
                'redirect' => route('login.customer'),
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Nếu đã có trong giỏ → cộng thêm số lượng
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'status'     => 'success',
            'message'    => "Đã thêm \"{$product->name}\" vào giỏ hàng",
            'cart_count' => $cartCount,
        ]);
    }

    // ===== XEM GIỎ HÀNG =====
    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login.customer');
    }

    $cartItems = CartItem::where('user_id', Auth::id())
        ->with(['product.firstImage'])
        ->get()
        ->map(function ($item) {

            $item->product->image_url =
                optional($item->product->firstImage)->image
                ? asset('storage/uploads/product/' .
                    $item->product->firstImage->image)
                : asset('storage/uploads/product/default_product.jpg');

            return $item;
        });

    $total = $cartItems->sum(
        fn($item) => $item->product->price * $item->quantity
    );

    return view(
        'clients.pages.cart',
        compact('cartItems', 'total')
    );
}

    // ===== CẬP NHẬT SỐ LƯỢNG =====
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        $itemTotal = $cartItem->product->price * $cartItem->quantity;
        $total     = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Đã cập nhật giỏ hàng',
            'cart_count' => $cartCount,
            'item_total' => number_format($itemTotal, 0, ',', '.') . 'đ',
            'total'      => number_format($total, 0, ',', '.') . 'đ',
        ]);
    }

    // ===== XÓA 1 SẢN PHẨM =====
    public function remove($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        $total     = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => $cartCount,
            'total'      => number_format($total, 0, ',', '.') . 'đ',
        ]);
    }

    // ===== XÓA TOÀN BỘ GIỎ =====
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'status'     => 'success',
            'message'    => 'Đã xóa toàn bộ giỏ hàng',
            'cart_count' => 0,
        ]);
    }

    // ===== ĐẾM SỐ LƯỢNG GIỎ (dùng cho header badge) =====
    public function count()
    {
        $count = Auth::check()
            ? CartItem::where('user_id', Auth::id())->sum('quantity')
            : 0;

        return response()->json(['cart_count' => $count]);
    }
}