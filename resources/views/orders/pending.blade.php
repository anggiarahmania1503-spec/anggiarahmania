@extends('layouts.app')

@section('title', 'Pembayaran Tertunda')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body">
                    <h1 class="display-4 text-warning">Pembayaran Tertunda</h1>
                    <p class="lead">Pembayaran Anda sedang diproses atau menunggu konfirmasi. Silakan tunggu beberapa saat atau coba langkah di bawah ini.</p>

                    @if(isset($order))
                        <p><strong>Nomor Pesanan:</strong> {{ $order->id }}</p>
                        <p><strong>Total:</strong> Rp {{ number_format($order->total ?? $order->grand_total ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-warning text-dark">Pending</span></p>
                    @endif

                    <div class="mt-4">
                        @if(!empty($payment_url))
                            <a href="{{ $payment_url }}" target="_blank" class="btn btn-primary">Lanjutkan Pembayaran</a>
                        @elseif(isset($order))
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Cek Pesanan</a>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
