@extends('layouts.app')

@section('title', 'Pembayaran Sukses')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            {{-- Card Utama dengan Shadow yang halus --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                {{-- Header dengan Warna Cerah --}}
                <div class="card-header bg-success bg-gradient py-4 text-center border-0">
                    <div class="mb-3">
                        {{-- Icon Checkmark Besar --}}
                        <i class="bi bi-check-circle-fill text-white" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-white fw-bold">Pembayaran Berhasil!</h2>
                </div>

                <div class="card-body p-4 p-md-5 text-center">
                    <p class="text-muted mb-4">
                        Hore! Transaksi Anda telah kami terima. Tim kami akan segera menyiapkan pesanan Anda untuk segera dikirim.
                    </p>

                    @if(isset($order))
                        {{-- Detail Pesanan dalam Box Terpisah --}}
                        <div class="bg-light rounded-3 p-4 mb-4 text-start">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Detail Transaksi</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Nomor Pesanan</span>
                                <span class="fw-bold text-dark">#{{ $order->order_number ?? $order->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Waktu Pembayaran</span>
                                <span class="text-dark">{{ now()->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Metode Pembayaran</span>
                                <span class="text-dark">Midtrans (Otomatis)</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 fw-bold mb-0">Total Bayar</span>
                                <span class="h5 fw-bold text-primary mb-0">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-2">
                        @if(isset($order))
                            <a href="{{ route('orders.index', $order->id) }}" class="btn btn-primary btn-lg px-4 rounded-pill">
                                <i class="bi bi-box-seam me-2"></i>Cek Status Pesanan
                            </a>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection