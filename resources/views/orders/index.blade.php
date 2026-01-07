@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
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

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <div class="mb-4">
                    <i class="bi bi-bag-x text-light-emphasis" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold">Belum ada pesanan</h4>
                <p class="text-muted">Sepertinya Anda belum melakukan transaksi apapun.</p>
                <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($orders as $order)
            <div class="col-12">
                <div class="card border-0 shadow-sm transition-hover overflow-hidden" style="transition: transform .2s;">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            {{-- Warna Samping Dinamis --}}
                            @php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'processing' => 'success',
                                    'completed' => 'primary',
                                    'cancelled' => 'danger'
                                ][$order->status] ?? 'secondary';
                            @endphp
                            
                            <div class="col-auto bg-{{ $statusColor }}" style="width: 8px;"></div>
                            
                            <div class="col p-4">
                                <div class="row align-items-center">
                                    {{-- Info Order --}}
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-light text-{{ $statusColor }} border border-{{ $statusColor }} me-2">
                                                ID #{{ $order->id }}
                                            </span>
                                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                        </div>
                                        <div class="fw-bold text-dark fs-5 text-uppercase">{{ $order->order_number }}</div>
                                    </div>
                                    
                                    {{-- Total --}}
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <div class="small text-muted mb-1">Total Pembayaran</div>
                                        <div class="fw-bold text-primary fs-5">
                                            Rp {{ number_format($order->getRawOriginal('total_amount') ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    
                                    {{-- Status --}}
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <div class="small text-muted mb-1">Status Pesanan</div>
                                        <span class="badge rounded-pill bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 border border-{{ $statusColor }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    
                                    {{-- Action --}}
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

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<style>
    /* Efek Hover untuk Card */
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
</style>
@endsection