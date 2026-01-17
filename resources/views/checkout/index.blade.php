@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<style>
    body { background: #f4f6f9; }
    .checkout-container { margin-top: 110px; padding-bottom: 100px; }
    .section-title { font-weight: 700; font-size: 1.9rem; letter-spacing: -.5px; }
    
    .soft-card {
        background: #ffffff; 
        border-radius: 20px; 
        padding: 24px; 
        box-shadow: 0 12px 35px rgba(0,0,0,.06); 
        border: 1px solid #f1f1f1; 
        transition: 0.3s;
    }
    .soft-card:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(0,0,0,.08); }
    .soft-card + .soft-card { margin-top: 20px; }

    .label { font-weight: 600; font-size: 14px; margin-bottom: 6px; }
    .form-control { border-radius: 14px; padding: 14px 16px; border: 1px solid #e5e7eb; }
    .form-control:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.2); }

    .checkout-summary { position: sticky; top: 110px; }

    .product-row { display: flex; justify-content: space-between; margin-bottom: 14px; }
    .product-row small { color: #6b7280; }

    .checkout-btn { 
        background: linear-gradient(135deg, #0e2246, #131843); 
        border: none; 
        color: white; 
        padding: 16px; 
        font-weight: 600; 
        border-radius: 14px; 
        transition: .3s; 
        cursor: pointer;
    }
    .checkout-btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 12px 30px rgba(34,197,94,.4); 
    }

    .fade-in { animation: fade .5s ease; }
    @keyframes fade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container checkout-container fade-in">

    <div class="text-center mb-5">
        <h1 class="section-title">Checkout</h1>
        <p class="text-muted">Pastikan data pengiriman sudah benar</p>
    </div>

    @php
        /** * MENGAMBIL TOTAL LANGSUNG DARI DATABASE
         * Tanpa Ongkir, Tanpa Angka Statis.
         */
        $totalTagihan = $cart->items->sum('subtotal');
    @endphp

    <form action="{{ route('checkout.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row g-4">

            {{-- BAGIAN FORM: KOSONG TANPA AUTO-FILL DARI PROFIL --}}
            <div class="col-lg-7">
                <div class="soft-card">
                    <h5 class="mb-3 fw-bold">📦 Data Penerima</h5>
                    <div class="mb-3">
                        <label class="label">Nama Penerima</label>
                        {{-- Menggunakan value old() saja agar tetap kosong saat pertama buka --}}
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ketik nama lengkap penerima" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="label">No. HP</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                    </div>
                </div>

                <div class="soft-card">
                    <h5 class="mb-3 fw-bold">📍 Alamat Pengiriman</h5>
                    <textarea name="address" class="form-control" rows="4" placeholder="Alamat lengkap (Jalan, No, RT/RW, Kec, Kota)" required>{{ old('address') }}</textarea>
                </div>
            </div>

            {{-- BAGIAN RINGKASAN: HARGA OTOMATIS DARI ADMIN PANEL --}}
            <div class="col-lg-5">
                <div class="soft-card checkout-summary">
                    <h5 class="fw-bold mb-3">🧾 Ringkasan Pesanan</h5>

                    @foreach($cart->items as $item)
                    <div class="product-row">
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            {{-- Harga Satuan (Bisa Harga Normal/Diskon sesuai database) --}}
                            <small>{{ $item->quantity }} x Rp {{ number_format($item->product->display_price, 0, ',', '.') }}</small>
                        </div>
                        <div>
                            {{-- Subtotal per item sesuai hitungan di database --}}
                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach

                    <hr>

                    {{-- TOTAL AKHIR: TANPA BIAYA TAMBAHAN APAPUN --}}
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4 p-3 bg-light rounded-3">
                        <span>Total Tagihan</span>
                        <span class="text-primary">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit" class="checkout-btn w-100">
                        🔒 Buat Pesanan Sekarang
                    </button>

                    <p class="text-muted text-center mt-3 small">
                        Aman & Terenkripsi
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection