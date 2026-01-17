{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman keranjang belanja dengan gaya Navy Dark
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

<style>
    /* 1. Global Navy Button Style (Tombol Mulai Belanja & Checkout) */
    .btn-navy {
        background-color: #0d1425 !important;
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        font-size: 14px;
        border-radius: 999px;
        padding: 12px 30px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(13, 20, 37, 0.15);
    }

    .btn-navy:hover {
        background-color: #1a253d !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(13, 20, 37, 0.25);
    }

    /* 2. Style untuk tombol Lanjut Belanja (Outline Navy) */
    .btn-outline-navy {
        border: 2px solid #0d1425 !important;
        color: #0d1425 !important;
        background: transparent !important;
        font-weight: 700;
        border-radius: 999px;
        padding: 10px 24px;
        transition: all 0.3s ease;
    }

    .btn-outline-navy:hover {
        background-color: #0d1425 !important;
        color: #ffffff !important;
    }

    /* 3. Empty Cart Styling */
    .empty-cart-container {
        padding: 60px 0;
        text-align: center;
    }
    
    .empty-cart-image {
        width: 180px;
        margin-bottom: 25px;
        opacity: 0.8;
    }

    /* 4. Item Card Styling */
    .cart-item-card {
        transition: all 0.2s ease;
    }
    
    .cart-item-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important;
    }
</style>

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4" style="color: #0d1425;">
        <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
    </h2>

    @if($cart && $cart->items->count())
        <div class="row g-4">

            {{-- SEKSI KIRI: Daftar Barang --}}
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach($cart->items as $item)
                        <div class="col-12">
                            <div class="card cart-item-card shadow-sm border-0 rounded-4 overflow-hidden">
                                <div class="row g-0 align-items-center">
                                    <div class="col-auto p-3">
                                        <img src="{{ $item->product->image_url }}" 
                                             class="rounded-3" 
                                             style="width:90px; height:90px; object-fit: cover;">
                                    </div>
                                    <div class="col">
                                        <div class="card-body py-3 px-2">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <a href="{{ route('catalog.show', $item->product->slug) }}" 
                                                       class="fw-bold text-decoration-none text-dark fs-6">
                                                        {{ Str::limit($item->product->name, 45) }}
                                                    </a>
                                                    <div class="text-muted small">
                                                        {{ $item->product->category->name }}
                                                    </div>
                                                </div>
                                                
                                                {{-- Remove Button --}}
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn text-danger p-0" onclick="return confirm('Hapus item ini?')">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between mt-2">
                                                <div class="small text-muted">
                                                    Qty: {{ $item->quantity }}
                                                </div>
                                                
                                                <div class="text-end">
                                                    @if($item->product->has_discount)
                                                        <div class="fw-bold text-primary" style="color: #2563eb !important;">
                                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                        </div>
                                                        <small class="text-muted text-decoration-line-through">
                                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                        </small>
                                                    @else
                                                        <div class="fw-bold text-primary" style="color: #2563eb !important;">
                                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SEKSI KANAN: Ringkasan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                            <span>Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-3 opacity-25">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total Tagihan</span>
                            <span class="fw-bold fs-5" style="color: #2563eb;">
                                Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn-navy py-3">
                                <i class="bi bi-credit-card me-2"></i> Checkout
                            </a>
                            <a href="{{ route('catalog.index') }}" class="btn-outline-navy py-3 mt-2 text-center text-decoration-none">
                                <i class="bi bi-arrow-left me-2"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- TAMPILAN KERANJANG KOSONG (SESUAI GAMBAR) --}}
        <div class="empty-cart-container">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329073.png" class="empty-cart-image" alt="Keranjang Kosong">
            <h4 class="fw-bold mt-2">Keranjang Kosong</h4>
            <p class="text-muted mb-4">Belum ada produk di keranjang belanja kamu</p>
            
            <div class="d-flex justify-content-center">
                <a href="{{ route('catalog.index') }}" class="btn-navy" style="width: auto !important; min-width: 250px;">
                    <i class="bi bi-bag me-2"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection