@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<style>
    /* Style kamu tetap sama, saya tidak ubah */
    body { background: #f4f6f9; }
    .checkout-container { margin-top: 110px; padding-bottom: 100px; }
    .section-title { font-weight: 700; font-size: 1.9rem; letter-spacing: -.5px; }
    .soft-card { background: #ffffff; border-radius: 20px; padding: 24px; box-shadow: 0 12px 35px rgba(0,0,0,.06); border: 1px solid #f1f1f1; }
    .soft-card + .soft-card { margin-top: 20px; }
    .label { font-weight: 600; font-size: 14px; margin-bottom: 6px; }
    .form-control { border-radius: 14px; padding: 14px 16px; border: 1px solid #e5e7eb; }
    .form-control:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.2); }
    .checkout-summary { position: sticky; top: 110px; }
    .product-row { display: flex; justify-content: space-between; margin-bottom: 14px; }
    .product-row small { color: #6b7280; }
    .total-box { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); padding: 16px; border-radius: 14px; font-weight: bold; font-size: 1.1rem; }
    .checkout-btn { background: linear-gradient(135deg, #22c55e, #16a34a); border: none; color: white; padding: 16px; font-weight: 600; border-radius: 14px; transition: .3s; }
    .checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(34,197,94,.4); }
    .fade-in { animation: fade .5s ease; }
    @keyframes fade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container checkout-container fade-in">

    <div class="text-center mb-5">
        <h1 class="section-title">Checkout</h1>
        <p class="text-muted">Lengkapi informasi untuk menyelesaikan pesanan</p>
    </div>

    @php
        $subtotal = $cart->items->sum(fn($i) => ($i->product?->price ?? 0) * $i->quantity);
        $shipping = 15000;
        $total = $subtotal + $shipping;
    @endphp

    <!-- TAMBAHAN: Form dengan method POST dan CSRF -->
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-7">

                <div class="soft-card">
                    <h5 class="mb-3 fw-bold">📦 Data Penerima</h5>

                    <div class="mb-3">
                        <label class="label">Nama Penerima</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="label">No. HP</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <div class="soft-card">
                    <h5 class="mb-3 fw-bold">📍 Alamat Pengiriman</h5>
                    <textarea name="address" class="form-control" rows="4" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                </div>

                <div class="soft-card">
                    <h5 class="mb-3 fw-bold">📝 Catatan (Opsional)</h5>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-5">
                <div class="soft-card checkout-summary">

                    <h5 class="fw-bold mb-3">🧾 Ringkasan Pesanan</h5>

                    @foreach($cart->items as $item)
                    <div class="product-row">
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            <small>{{ $item->quantity }} x Rp {{ number_format($item->product->price) }}</small>
                        </div>
                        <div>
                            <strong>Rp {{ number_format($item->quantity * $item->product->price) }}</strong>
                        </div>
                    </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal) }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Ongkir</span>
                        <span>Rp {{ number_format($shipping) }}</span>
                    </div>

                    <div class="total-box mt-3">
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span>Rp {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <!-- Button sekarang berada di dalam form, jadi bisa submit -->
                    <button type="submit" class="checkout-btn w-100 mt-4">
                        🔒 Buat Pesanan
                    </button>

                    <p class="text-muted text-center mt-3 small">
                        🔒 Pembayaran aman & terenkripsi
                    </p>

                </div>
            </div>

        </div>
    </form>
    <!-- AKHIR FORM -->

</div>

@endsection