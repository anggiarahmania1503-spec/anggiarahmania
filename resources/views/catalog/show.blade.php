@extends('layouts.app')

@section('title', $product->name)

<style>
    /* Custom Button Style untuk Menyamakan dengan Navbar */
    .btn-add-cart {
        /* Warna Biru Gelap Navbar */
        background-color: #0d1425; 
        color: #ffffff !important;
        border: none;
        font-weight: 700;
        font-size: 14px;
        width: 100%;
        border-radius: 999px;
        padding: 12px 24px;
        margin-top: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(13, 20, 37, 0.15);
    }

    .btn-add-cart:hover {
        background-color: #1a253d; /* Warna hover sedikit lebih terang */
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(13, 20, 37, 0.25);
        color: #ffffff;
    }

    .btn-add-cart:active {
        transform: translateY(0);
    }

    .btn-add-cart:disabled {
        background-color: #e2e8f0;
        color: #94a3b8 !important;
        box-shadow: none;
        transform: none;
        cursor: not-allowed;
    }

    /* Memperbaiki ukuran ikon di dalam tombol */
    .btn-add-cart i {
        font-size: 1.2rem;
    }

    /* Cursor pointer untuk thumbnail gambar */
    .cursor-pointer {
        cursor: pointer;
        transition: 0.2s;
    }
    .cursor-pointer:hover {
        border-color: #0d1425 !important;
        opacity: 0.8;
    }
</style>

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="text-decoration-none text-muted">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active fw-bold" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Bagian Kiri: Gambar Produk --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $product->image_url }}" id="main-image" 
                         class="card-img-top" alt="{{ $product->name }}" 
                         style="height: 450px; object-fit: contain; background: #ffffff;">

                    @if($product->has_discount)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6 px-3 py-2 rounded-pill">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif
                </div>

                @if($product->images->count() > 0)
                <div class="card-body bg-light-subtle">
                    <div class="d-flex gap-2 overflow-auto pb-2">
                        {{-- Thumbnail Gambar Utama --}}
                        <img src="{{ $product->image_url }}" 
                             class="rounded border cursor-pointer bg-white" 
                             style="width: 70px; height: 70px; object-fit: contain;" 
                             onclick="document.getElementById('main-image').src = this.src">
                        
                        {{-- Thumbnail Gambar Tambahan --}}
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="rounded border cursor-pointer bg-white" 
                                 style="width: 70px; height: 70px; object-fit: contain;" 
                                 onclick="document.getElementById('main-image').src = this.src">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Bagian Kanan: Info Produk --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    {{-- Kategori --}}
                     <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                       class="badge bg-light text-dark mb-2 text-decoration-none">
                        {{ $product->category->name }}
                    </a>

                    {{-- Judul --}}
                    <h2 class="fw-bold mb-3" style="color: #334155;">{{ $product->name }}</h2>

                    {{-- Harga --}}
                    <div class="mb-4">
                        @if($product->has_discount)
                            <div class="text-muted text-decoration-line-through small">
                                {{ $product->formatted_original_price }}
                            </div>
                        @endif
                        <div class="h2 fw-bold mb-0" style="color: #2563eb;">
                            {{ $product->formatted_price }}
                        </div>
                    </div>

                    {{-- Status Stok --}}
                    <div class="mb-4">
                        @if($product->stock > 10)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Stok Tersedia
                            </span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Stok Tinggal {{ $product->stock }}
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                            </span>
                        @endif
                    </div>

                    {{-- Form Add to Cart --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="d-grid">
                            <button type="submit" class="btn-add-cart"
                                    @if($product->stock == 0) disabled @endif>
                                <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>

                    {{-- Wishlist --}}
                    @auth
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 border-2 fw-bold py-2 mb-4 rounded-pill">
                            <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                            {{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                        </button>
                    </form>
                    @endauth

                    <hr class="my-4 text-muted opacity-25">

                    {{-- Detail Produk --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Deskripsi</h6>
                        <p class="text-secondary lh-lg" style="font-size: 0.95rem;">
                            {!! nl2br(e($product->description)) !!}
                        </p>
                    </div>

                    <div class="row g-3 text-muted small">
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-light-subtle">
                                <i class="bi bi-box-seam me-2"></i> Berat: {{ $product->weight }} gr
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-light-subtle">
                                <i class="bi bi-hash me-2"></i> SKU: PROD-{{ $product->id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script pendukung jika dibutuhkan di masa depan
</script>
@endpush

@endsection