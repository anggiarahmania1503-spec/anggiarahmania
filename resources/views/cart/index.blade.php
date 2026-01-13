{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman keranjang belanja (desain mirip Home)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">
        <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
    </h2>

    @if($cart && $cart->items->count())
        <div class="row g-4">

            {{-- LEFT: Cart Items --}}
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach($cart->items as $item)
                        <div class="col-12">
                            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                                <div class="row g-0 align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ $item->product->image_url }}" 
                                             class="img-fluid rounded-start" 
                                             style="width:100px; height:100px; object-fit: cover;">
                                    </div>
                                    <div class="col">
                                        <div class="card-body py-3 px-4">
                                            <a href="{{ route('catalog.show', $item->product->slug) }}" 
                                               class="fw-bold text-decoration-none text-dark fs-6">
                                                {{ Str::limit($item->product->name, 50) }}
                                            </a>
                                            <div class="text-muted small mb-2">
                                                {{ $item->product->category->name }}
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                {{-- Quantity --}}
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" class="btn btn-outline-secondary btn-sm me-1"
                                                            onclick="this.nextElementSibling.stepDown()">-</button>
                                                    <input type="number" name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" max="{{ $item->product->stock }}" 
                                                           class="form-control form-control-sm text-center" 
                                                           style="width:60px;" 
                                                           onchange="this.form.submit()">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-1"
                                                            onclick="this.previousElementSibling.stepUp()">+</button>
                                                </form>

                                                {{-- Subtotal --}}
                                                <div class="fw-bold text-primary">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </div>

                                                {{-- Remove --}}
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus item ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                            <span>Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h4 class="fw-bold mt-3">Keranjang Kosong</h4>
            <p class="text-muted">Belum ada produk di keranjang belanja kamu</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
