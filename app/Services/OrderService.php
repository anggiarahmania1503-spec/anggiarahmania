<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {
            
            // 1. Hitung total menggunakan subtotal yang sudah mendukung diskon
            $totalAmount = $cart->items->sum('subtotal');

            // 2. Buat Header Order
            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => 'ORD-' . strtoupper(Str::random(10)),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'shipping_name'    => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone'   => $shippingData['phone'],
                'total_amount'     => $totalAmount,
            ]);

            // 3. Pindahkan Items & Simpan Harga Saat Ini (Snapshot)
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->display_price, // Harga diskon Rp 100rb
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->subtotal, // Quantity * 100rb
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            // 4. Hapus Keranjang
            $cart->items()->delete();

            return $order;
        });
    }
}