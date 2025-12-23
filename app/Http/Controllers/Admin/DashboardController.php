<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalRevenue' => Order::where('status', 'completed')->sum('total_amount'),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'lowStockProducts' => Product::where('stock', '<=', 5)->count(),
            'latestOrders' => Order::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}