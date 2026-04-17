<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function index()
    {
        // Total Users Count
        $totalUsers = User::count();
        $totalUsersLastWeek = User::whereBetween('created_at', [
            now()->subDays(14),
            now()->subDays(7)
        ])->count();
        $usersGrowth = $this->calculateGrowth($totalUsersLastWeek, User::whereBetween('created_at', [
            now()->subDays(7),
            now()
        ])->count());

        // Total Products
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 1)->count();
        $productsLastWeek = Product::whereBetween('created_at', [
            now()->subDays(14),
            now()->subDays(7)
        ])->count();
        $productsGrowth = $this->calculateGrowth($productsLastWeek, Product::whereBetween('created_at', [
            now()->subDays(7),
            now()
        ])->count());

        // Total Orders
        $totalOrders = Order::count();
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ordersLastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $ordersGrowth = $this->calculateGrowth($ordersLastMonth, $ordersThisMonth);

        // Revenue
        $totalRevenue = Order::sum('total_price');
        $revenueThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $revenueLastMonth = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');
        $revenueGrowth = $this->calculateGrowth($revenueLastMonth, $revenueThisMonth);

        // Total Categories
        $totalCategories = Category::count();

        // Recent Orders (Last 10)
        $recentOrders = Order::with(['user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Order Status Distribution
        $orderStatusDistribution = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Monthly Revenue (Last 12 months)
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Top Products
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.pages.dashboard', [
            'totalUsers' => $totalUsers,
            'usersGrowth' => $usersGrowth,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'productsGrowth' => $productsGrowth,
            'totalOrders' => $totalOrders,
            'ordersThisMonth' => $ordersThisMonth,
            'ordersGrowth' => $ordersGrowth,
            'totalRevenue' => $totalRevenue ?? 0,
            'revenueThisMonth' => $revenueThisMonth ?? 0,
            'revenueGrowth' => $revenueGrowth,
            'totalCategories' => $totalCategories,
            'recentOrders' => $recentOrders,
            'orderStatusDistribution' => $orderStatusDistribution,
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Calculate percentage growth
     */
    private function calculateGrowth($previousValue, $currentValue)
    {
        if ($previousValue == 0) {
            return $currentValue > 0 ? 100 : 0;
        }
        return round((($currentValue - $previousValue) / $previousValue) * 100, 1);
    }
}
