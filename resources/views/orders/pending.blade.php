@extends('layouts.app')

@section('title', 'Pembayaran Tertunda')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Card Utama --}}
            <div class="card shadow-sm border-0 text-center p-4" style="border-radius: 20px; box-shadow: 0 18px 40px rgba(0,0,0,0.08);">
                <div class="card-body">

                    {{-- Ikon Visual --}}
                    <div class="mb-4">
                        <i class="bi bi-clock-history text-warning" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="fw-bold text-dark">Pembayaran Tertunda</h1>
                    <p class="text-muted lead">Pesanan Anda telah kami terima, namun kami masih menunggu konfirmasi pembayaran Anda.</p>

                    <hr class="my-4" style="border-top: 2px dashed #dee2e6;">

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        @if(!empty($payment_url))
                            <a href="{{ $payment_url }}" target="_blank" 
                               class="btn btn-lg rounded-pill"
                               style="background: linear-gradient(135deg, #3B6181, #5a8fb9); color: white; font-weight: 600;">
                               <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                            </a>
                        @elseif(isset($order))
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="btn btn-lg rounded-pill"
                               style="background: linear-gradient(135deg, #3B6181, #5a8fb9); color: white; font-weight: 600;">
                               <i class="bi bi-search me-2"></i>Cek Detail Pesanan
                            </a>
                        @endif

                        <a href="{{ url('/') }}" 
                           class="btn btn-outline-secondary btn-lg rounded-pill"
                           style="border-color:#cbd5e1; color:#3B6181;">
                           Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
