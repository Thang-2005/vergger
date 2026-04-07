<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   
        public function boot(): void
{
    View::composer('*', function ($view) {

        if (Auth::check()) {

            $cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product.firstImage'])
                ->latest()
                ->take(5)
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
                fn($item) =>
                $item->product->price * $item->quantity
            );

        } else {
            $cartItems = collect();
            $total = 0;
        }

        $view->with([
            'cartItems' => $cartItems,
            'cartTotal' => $total
        ]);
    });
}
    
}
