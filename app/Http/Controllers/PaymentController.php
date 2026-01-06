<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Mengambil Snap Token untuk order ini (API Endpoint).
     * Dipanggil via AJAX dari frontend saat user klik "Bayar".
     */
    public function getSnapToken(Order $order, MidtransService $midtransService)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Pesanan sudah dibayar.'], 400);
        }

        // PERBAIKAN: Jika sudah punya token di database, kirimkan yang sudah ada
        if ($order->snap_token) {
            return response()->json(['token' => $order->snap_token]);
        }

        try {
            $snapToken = $midtransService->createSnapToken($order);
            $order->update(['snap_token' => $snapToken]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman success setelah pembayaran berhasil
     */
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.success', compact('order'));
    }

    /**
     * Menampilkan halaman pending saat pembayaran masih diproses
     */
    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $payment_url = request()->query('payment_url');
        return view('orders.pending', compact('order', 'payment_url'));
    }
}