<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SalesReportExport;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan di browser.
     */
    /**
     * Menampilkan halaman laporan di browser.
     * Fitur:
     * 1. Filter Rentang Tanggal (Date Range)
     * 2. Summary Statistik
     * 3. Grafik Penjualan per Kategori (Analitik)
     * 4. Tabel Detail Transaksi dengan Pagination
     */
    public function sales(Request $request)
    {
        // 1. Tentukan Default Tanggal
        // Jika user tidak memilih tanggal, kita set default ke BULAN INI.
        // startOfMonth() otomatis mengambil tanggal 1 bulan berjalan.
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to ?? now()->toDateString();

        // 2. Query Utama (Tabel Transaksi Detail)
        // Kita gunakan paginate() agar beban server ringan meskipun datanya ribuan.
        $ordersQuery = Order::with(['items', 'user']) // Eager Load relasi
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        // Jika user ingin memfilter hanya yang sudah dibayar, kirim query param `payment_status=paid`.
        if ($request->filled('payment_status')) {
            $ordersQuery->where('payment_status', $request->payment_status);
        }

        $orders = $ordersQuery->latest()->paginate(20);

        // 3. Query Summary (Total Omset di periode ini)
        // Perhatikan: Kita tidak menggunakan data pagination ($orders) untuk menghitung total.
        // Kenapa? Karena $orders hanya berisi 20 data per halaman.
        // Kita butuh TOTAL SEBENARNYA dari seluruh data yang difilter.
        // Maka kita buat query aggregat terpisah yang sangat ringan (hanya select COUNT dan SUM).
        $summaryQuery = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($request->filled('payment_status')) {
            $summaryQuery->where('payment_status', $request->payment_status);
        }

        $summary = $summaryQuery->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_revenue')->first();

        // 4. Query Analitik: Penjualan per Kategori
        // Logika: Kita ingin tahu Kategori mana yang paling laku.
        // Masalah: Tabel 'categories' tidak berhubungan langsung dengan 'order_items'.
        // Solusi: JOIN 4 tabel! (Categories -> Products -> OrderItems -> Orders)
        $byCategoryQuery = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            // Filter tanggal berdasarkan table ORDERS (kapan transaksi terjadi)
            ->whereDate('orders.created_at', '>=', $dateFrom)
            ->whereDate('orders.created_at', '<=', $dateTo);

        if ($request->filled('payment_status')) {
            $byCategoryQuery->where('orders.payment_status', $request->payment_status);
        }

        // Grouping berdasarkan Kategori untuk mendapat SUM per kategori
        $byCategory = $byCategoryQuery
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.subtotal) as total') // Hitung total duit per kategori
            )
            ->orderByDesc('total') // Urutkan dari yang omsetnya paling besar
            ->get();

        return view('admin.reports.sales', compact('orders', 'summary', 'byCategory', 'dateFrom', 'dateTo'));
    }

    /**
     * Handle download Excel.
     */
    public function exportSales(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        // Download file
        return Excel::download(
            new SalesReportExport($dateFrom, $dateTo),
            "laporan-penjualan-{$dateFrom}-sd-{$dateTo}.xlsx"
        );
    }
}