<?php

namespace App\Exports;

use App\Models\Order;

class SalesReportExport
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    /**
     * Fetch data untuk export
     */
    public function getData()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->orderBy('created_at', 'asc')
            ->get();

        // Format data dengan headers
        $data = [
            ['No. Order', 'Tanggal Transaksi', 'Nama Customer', 'Email', 'Jumlah Item', 'Total Belanja (Rp)', 'Status']
        ];

        foreach ($orders as $order) {
            $data[] = [
                $order->order_number,
                $order->created_at->format('d/m/Y H:i'),
                $order->user->name,
                $order->user->email,
                $order->items->sum('quantity'),
                $order->total_amount,
                ucfirst($order->status),
            ];
        }

        return $data;
    }
}