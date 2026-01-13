<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function getSnapToken(Order $order, MidtransService $midtransService)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        
        try {
            // Kita buat token baru agar sinkron dengan ID unik
            $response = $midtransService->createSnapToken($order);
            $snapToken = is_array($response) ? $response['token'] : $response;
            $midtransOrderId = is_array($response) ? $response['midtrans_order_id'] : null;

            $order->update(['snap_token' => $snapToken]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'midtrans_order_id' => $midtransOrderId,
                    'snap_token' => $snapToken,
                    'gross_amount' => $order->total_amount,
                    'status' => 'pending',
                ]
            );

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // File: app/Http/Controllers/PaymentController.php

public function notificationHandler(Request $request)
{
    $json = json_decode($request->getContent());
    
    // Log ini sangat penting untuk melihat apakah Midtrans benar-benar memanggil laptop kamu
    \Log::info('Webhook Midtrans Masuk: ' . $json->order_id);

    // Memecah "ORD-12345-15" menjadi array, kita ambil angka 15 (ID Database)
    $orderParts = explode('-', $json->order_id);
    $orderIdInDb = end($orderParts); 

    $order = Order::find($orderIdInDb);

    if ($order) {
        $status = $json->transaction_status;

        // Jika statusnya 'settlement' (Lunas) atau 'capture' (Kartu Kredit Berhasil)
        if ($status == 'settlement' || $status == 'capture') {
            $order->update([
                'status'         => 'processing',
                'payment_status' => 'paid'
            ]);
            
            // Update juga tabel payment jika kamu memakainya
            Payment::where('order_id', $order->id)->update(['status' => 'success']);
            
            \Log::info('DATABASE BERHASIL DIUPDATE untuk Order ID: ' . $order->id);
        }
    }

    return response()->json(['status' => 'success']);
}
    public function success(Order $order) {
        return view('orders.success', compact('order'));
    }

    public function pending(Order $order) {
        $payment_url = request()->query('payment_url');
        return view('orders.pending', compact('order', 'payment_url'));
    }
}