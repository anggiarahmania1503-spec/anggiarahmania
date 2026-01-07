<?php
// app/Http/Controllers/MidtransNotificationController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Events\OrderPaidEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{

    // app/Http/Controllers/MidtransNotificationController.php

private function setSuccess(Order $order)
{
    $order->update([
        'status' => 'paid',
        'payment_status' => 'success',
    ]);

    // Fire & Forget
    event(new OrderPaidEvent($order));
}
    /**
     * Handle incoming webhook notification from Midtrans.
     * URL: POST /midtrans/notification
     */
    public function handle(Request $request)
    {
        // 1. Ambil payload
        $payload = $request->all();

        Log::info('Midtrans Notification Received', $payload);

        // 2. Ambil field penting
        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;

        // 3. Validasi awal
        if (!$orderId || !$transactionStatus || !$signatureKey) {
            Log::warning('Midtrans Notification: Missing required fields', $payload);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 4. Validasi signature
        $serverKey = config('midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', [
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 5. Cari order (PAKAI order_number)
        // Midtrans `order_id` may include a suffix we generated when creating Snap,
        // e.g. "ORD-XXXX-123-605f3a...". Try exact match first, then fall back
        // to extracting the base order number (before first dash).
        $searchOrderNumber = $orderId;
        if (is_string($orderId) && str_contains($orderId, '-')) {
            $searchOrderNumber = explode('-', $orderId)[0];
        }

        $order = Order::where('order_number', $orderId)
            ->orWhere('order_number', $searchOrderNumber)
            ->first();

        if (!$order) {
            Log::warning('Midtrans Notification: Order not found', [
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 6. Idempotency check
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            Log::info('Order already processed', [
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Already processed'], 200);
        }

        // 7. Update payment tambahan
        $payment = $order->payment;
        if ($payment) {
            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_type'            => $paymentType,
                'raw_response'            => json_encode($payload),
            ]);
        }

        // 8. Mapping status Midtrans
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $this->handlePending($order, $payment, 'Fraud challenge');
                } else {
                    $this->handleSuccess($order, $payment);
                }
                break;

            case 'settlement':
                $this->handleSuccess($order, $payment);
                break;

            case 'pending':
                $this->handlePending($order, $payment, 'Menunggu pembayaran');
                break;

            case 'deny':
                $this->handleFailed($order, $payment, 'Pembayaran ditolak');
                break;

            case 'expire':
                $this->handleFailed($order, $payment, 'Pembayaran kadaluarsa');
                break;

            case 'cancel':
                $this->handleFailed($order, $payment, 'Pembayaran dibatalkan');
                break;

            case 'refund':
            case 'partial_refund':
                $this->handleRefund($order, $payment);
                break;

            default:
                Log::info('Unknown Midtrans status', [
                    'status' => $transactionStatus,
                ]);
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }

    /**
     * Handle pembayaran sukses.
     */
    protected function handleSuccess(Order $order, ?Payment $payment): void
    {
        Log::info("Payment SUCCESS for Order {$order->order_number}");

        $order->update([
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        if ($payment) {
            $payment->update([
                'status'  => 'success',
                'paid_at' => now(),
            ]);
        }

        // 🔥 INI YANG BIKIN MASUK QUEUE
        event(new OrderPaidEvent($order));
    }

    /**
     * Handle pembayaran pending.
     */
    protected function handlePending(Order $order, ?Payment $payment, string $message = ''): void
    {
        Log::info("Payment PENDING for Order {$order->order_number}", [
            'message' => $message,
        ]);

        if ($payment) {
            $payment->update(['status' => 'pending']);
        }
    }

    /**
     * Handle pembayaran gagal.
     */
    protected function handleFailed(Order $order, ?Payment $payment, string $reason = ''): void
    {
        Log::info("Payment FAILED for Order {$order->order_number}", [
            'reason' => $reason,
        ]);

        $order->update([
            'status' => 'cancelled',
        ]);

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        // Restock
        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }
    }

    /**
     * Handle refund.
     */
    protected function handleRefund(Order $order, ?Payment $payment): void
    {
        Log::info("Payment REFUND for Order {$order->order_number}");

        if ($payment) {
            $payment->update(['status' => 'refunded']);
        }
    }
}