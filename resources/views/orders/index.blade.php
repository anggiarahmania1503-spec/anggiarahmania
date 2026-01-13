@extends('layouts.app')

@section('title', $pageTitle ?? 'Halaman')

@section('content')
<div class="container py-5">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert flash-alert flash-success alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
        <div class="icon-wrap"><i class="bi bi-check-lg"></i></div>
        <div class="flex-grow-1">
            <strong>Berhasil</strong>
            <div class="small mt-1">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert flash-alert flash-error alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
        <div class="icon-wrap"><i class="bi bi-x-lg"></i></div>
        <div class="flex-grow-1">
            <strong>Gagal</strong>
            <div class="small mt-1">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Payment Success --}}
    @if(isset($order) && $order->payment_status === 'paid')
    <div class="row justify-content-center mb-4">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-success py-4 text-center border-0">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-white" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-white fw-bold">Pembayaran Berhasil!</h2>
                </div>
                <div class="card-body p-4 p-md-5 text-center">
                    <p class="text-muted mb-4">
                        Hore! Transaksi Anda telah kami terima. Tim kami akan segera menyiapkan pesanan Anda.
                    </p>

                    {{-- Detail Transaksi --}}
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
                                Rp {{ number_format($order->total_amount,0,',','.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-2">
                        <a href="{{ route('orders.index', $order->id) }}" class="btn btn-primary btn-lg px-4 rounded-pill">
                            <i class="bi bi-box-seam me-2"></i>Cek Status Pesanan
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Pending --}}
    @if(isset($order) && $order->payment_status === 'pending')
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="bi bi-clock-history text-warning" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="fw-bold text-dark">Pembayaran Tertunda</h1>
                    <p class="text-muted lead">
                        Pesanan Anda telah kami terima, namun kami masih menunggu konfirmasi pembayaran.
                    </p>
                    <hr class="my-4" style="border-top: 2px dashed #dee2e6;">

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        @if(!empty($payment_url))
                        <a href="{{ $payment_url }}" target="_blank" class="btn btn-warning btn-lg px-4 fw-bold">
                            <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                        </a>
                        @elseif(isset($order))
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-search me-2"></i>Cek Detail Pesanan
                        </a>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg px-4">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Order History --}}
    @if(isset($orders) && $orders->count())
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">Riwayat Pesanan</h2>
            <p class="text-muted mb-0">Kelola dan pantau status pengiriman belanjaan Anda.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="p-3 bg-white border rounded-pill d-inline-flex align-items-center shadow-sm">
                <i class="bi bi-clock-history text-primary me-2 fs-5"></i>
                <span class="fw-bold">{{ $orders->total() }} Transaksi</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach ($orders as $order)
        @php
            $statusColor = [
                'pending' => 'warning',
                'processing' => 'success',
                'completed' => 'primary',
                'cancelled' => 'danger'
            ][$order->status] ?? 'secondary';
        @endphp
        <div class="col-12">
            <div class="card border-0 shadow-sm transition-hover overflow-hidden" style="transition: transform .2s;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-auto bg-{{ $statusColor }}" style="width: 8px;"></div>
                        <div class="col p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-light text-{{ $statusColor }} border border-{{ $statusColor }} me-2">
                                            ID #{{ $order->id }}
                                        </span>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                    </div>
                                    <div class="fw-bold text-dark fs-5 text-uppercase">{{ $order->order_number }}</div>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <div class="small text-muted mb-1">Total Pembayaran</div>
                                    <div class="fw-bold text-primary fs-5">
                                        Rp {{ number_format($order->getRawOriginal('total_amount') ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <div class="small text-muted mb-1">Status Pesanan</div>
                                    <span class="badge rounded-pill bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 border border-{{ $statusColor }}">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-{{ $statusColor }} rounded-pill px-4 btn-sm fw-bold">
                                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
    @endif

</div>

@push('styles')
<style>
    .btn-primary {
        background: linear-gradient(135deg, #c084fc, #fb7185);
        border: none;
        border-radius: 999px;
        padding: 10px 34px;
        font-weight: 600;
        color: #fff;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #fb7185, #c084fc);
    }
    .btn-outline-secondary {
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 10px 34px;
    }
    .btn-outline-secondary:hover {
        background: rgba(251,191,36,0.12);
    }

    .card {
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #86efac, #16a34a);
    }

    .text-primary {
        color: #c084fc !important;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }

    .bg-opacity-10 { --bs-bg-opacity: 0.1; }
</style>
@endpush

@endsection
