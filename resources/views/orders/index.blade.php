@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Pesanan Saya</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>Nomor Pesanan</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                {{-- Di sini $order adalah satu baris data, jadi order_number bisa dipanggil --}}
                <td>{{ $order->order_number }}</td>
               {{-- Gunakan kode ini untuk memaksa pengambilan data asli --}}
<td>Rp {{ number_format($order->getRawOriginal('total_amount') ?? 0, 0, ',', '.') }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Menampilkan tombol navigasi halaman (Next/Previous) --}}
    {{ $orders->links() }}
</div>
@endsection