<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
       $orders = Order::latest()->paginate(10);
    return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pesanan berhasil diubah');
    }
}
