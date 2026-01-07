<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Cards)
        // Kita menghitung data real-time dari database.
        // Konsep: Gunakan method agregat database (SUM, COUNT) daripada menarik data ke PHP (get() -> count()).
        // Alasan: Jauh lebih hemat memori server.

        $stats = [
            'total_revenue' => Order::whereIn('status', ['processing', 'completed'])
                                    ->sum('total_amount'), // SQL: SELECT SUM(total_amount) FROM orders WHERE ...

            'total_orders' => Order::count(), // SQL: SELECT COUNT(*) FROM orders

            // Pending Orders: Yang perlu tindakan segera admin
            'pending_orders' => Order::where('status', 'pending')
                                     ->where('payment_status', 'paid') // Sudah bayar tapi belum diproses
                                     ->count(),

            'total_products' => Product::count(),

            'total_customers' => User::where('role', 'customer')->count(),

            // Stok Rendah: Produk dengan stok <= 5
            // Berguna untuk notifikasi re-stock
            'low_stock' => Product::where('stock', '<=', 5)->count(),
        ];

        // 2. Data Tabel Pesanan Terbaru (5 transaksi terakhir)
        // Eager load 'user' untuk menghindari N+1 Query Problem saat menampilkan nama customer di blade.
        $recentOrders = Order::with('user')
            ->latest() // alias orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. Produk Terlaris
        // Tantangan: Menghitung total qty terjual dari tabel relasi (order_items)
        // Solusi: JOIN dengan subquery untuk data
        $topProducts = DB::table('products')
            ->select('products.id', 'products.name', DB::raw('COALESCE(SUM(order_items.quantity), 0) as sold'))
            ->selectRaw('COALESCE(product_images.image_path, "/assets/images/no-image.png") as image_url')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('orders', function($join) {
                $join->on('orders.id', '=', 'order_items.order_id');
            })
            ->leftJoin('product_images', function($join) {
                $join->on('product_images.product_id', '=', 'products.id')
                     ->where('product_images.is_primary', '=', true);
            })
            ->groupBy('products.id', 'products.name', 'product_images.image_path')
            ->having('sold', '>', 0)
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        // 4. Data Grafik Pendapatan (7 Hari Terakhir)
        // Kasus: Grouping data per tanggal
        // Kita gunakan DB::raw untuk format tanggal dari timestamp 'created_at'
        $startDate = now()->subDays(6)->startOfDay();
        
        $revenueChart = Order::select([
                DB::raw('DATE(created_at) as date'), // Ambil tanggalnya saja (2024-12-10)
                DB::raw('SUM(total_amount) as total') // Total omset hari itu
            ])
            ->where('created_at', '>=', $startDate)
            ->groupBy('date') // Kelompokkan baris berdasarkan tanggal
            ->orderBy('date', 'asc') // Urutkan kronologis
            ->get();
        
        // Jika tidak ada data, buat data dummy 7 hari terakhir dengan nilai 0
        if ($revenueChart->isEmpty()) {
            $revenueChart = collect();
            for ($i = 6; $i >= 0; $i--) {
                $revenueChart->push((object)[
                    'date' => now()->subDays($i)->format('Y-m-d'),
                    'total' => 0
                ]);
            }
        }
        
        // Format data untuk Chart.js
        $revenueChart = $revenueChart->map(function($item) {
            return [
                'date' => \Carbon\Carbon::createFromFormat('Y-m-d', $item->date)->format('d/m'),
                'total' => (float) $item->total
            ];
        });

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'revenueChart'));
    }
}