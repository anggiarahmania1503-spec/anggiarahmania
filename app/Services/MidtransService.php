<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    /**
     * Inisialisasi konfigurasi Midtrans dari file config/midtrans.php
     */
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Membuat Snap Token
     */
    public function createSnapToken(Order $order)
    {
        if ($order->items->isEmpty()) {
            throw new Exception('Order tidak memiliki item.');
        }

        /**
         * PENTING: Format Order ID Midtrans
         * Kita gabungkan: NOMOR_ORDER - ID_DATABASE - UNIQID
         * Contoh: ORD-7OK4SHIVT4-15-659b123
         * Ini supaya Controller bisa melakukan 'explode' dan mendapatkan ID Database (angka 15).
         */
        // Di dalam MidtransService.php
        // Di dalam file MidtransService.php
        $midtransOrderId = $order->order_number . '-' . $order->id;
        $transactionDetails = [
            'order_id'     => $midtransOrderId,
            'gross_amount' => (int) $order->total_amount,
        ];

        $customerDetails = [
            'first_name' => $order->user->name,
            'email'      => $order->user->email,
            'phone'      => $order->shipping_phone ?? '',
        ];

        $itemDetails = $order->items->map(function ($item) {
            return [
                'id'       => (string) $item->product_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => substr($item->product_name ?? 'Produk', 0, 50),
            ];
        })->toArray();

        // Tambahkan ongkir jika ada
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Biaya Pengiriman',
            ];
        }

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details'    => $customerDetails,
            'item_details'        => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Kembalikan token DAN ID unik Midtrans agar bisa disimpan di tabel Payment
            return [
                'token' => $snapToken,
                'midtrans_order_id' => $midtransOrderId,
            ];
        } catch (Exception $e) {
            logger()->error('Midtrans Snap Error: ' . $e->getMessage());
            throw new Exception('Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Mengecek status transaksi ke server Midtrans (Opsional)
     */
    public function checkStatus(string $midtransOrderId)
    {
        try {
            return Transaction::status($midtransOrderId);
        } catch (Exception $e) {
            return null;
        }
    }
}