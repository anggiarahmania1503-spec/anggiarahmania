<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray; // Tambahkan ini
use Maatwebsite\Excel\Concerns\WithHeadings; // Agar lebih rapi

class SalesReportExport implements FromArray
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    /**
     * Laravel Excel akan otomatis memanggil fungsi array() ini
     */
    public function array(): array
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->orderBy('created_at', 'asc')
            ->get();

        // Header tabel
        $data = [
            ['No. Order', 'Tanggal Transaksi', 'Nama Customer', 'Email', 'Jumlah Item', 'Total Belanja (Rp)', 'Status']
        ];

        // Isi data
        foreach ($orders as $order) {
            $data[] = [
                $order->order_number,
                $order->created_at->format('d/m/Y H:i'),
                $order->user->name ?? 'Guest', // Menghindari error jika user null
                $order->user->email ?? '-',
                $order->items->sum('quantity'),
                $order->total_amount,
                ucfirst($order->status),
            ];
        }

        return $data;
    }
}