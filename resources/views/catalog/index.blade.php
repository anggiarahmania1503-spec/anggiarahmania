@extends('layouts.app')
@section('title', 'Katalog Produk')

<style>
    /* Custom Button untuk Katalog */
    .btn-bajoo {
        background-color: #15213e !important; /* Warna Navy Navbar */
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        font-size: 13px;
        border-radius: 8px; /* Sudut sedikit membulat */
        padding: 8px 15px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%; /* Memenuhi container form yang kecil */
    }

    .btn-bajoo:hover {
        background-color: #1a253d !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-bajoo:disabled {
        background-color: #e2e8f0 !important;
        color: #94a3b8 !important;
        cursor: not-allowed;
    }

    /* Memperbaiki card hover */
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .pagination .page-item.active .page-link {
        background-color: #0d1425 !important; /* Biru Gelap Navy */
        border-color: #0d1425 !important;
        color: white !important;
    }

    .pagination .page-link {
        color: #0d1425 !important; /* Warna angka saat tidak aktif */
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa !important;
    }

    /* 2. Menyelaraskan Warna Filter (Radio Button & Badge) */
    .form-check-input:checked {
        background-color: #0d1425 !important;
        border-color: #0d1425 !important;
    }

    /* 3. Menyelaraskan Tombol "Terapkan" di Sidebar */
    .btn-outline-primary {
        color: #0d1425 !important;
        border-color: #0d1425 !important;
    }

    .btn-outline-primary:hover {
        background-color: #0d1425 !important;
        color: white !important;
    }
</style>

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Sidebar Filter --}}
        <div class="col-lg-3 mb-4">
            <div class="card p-3 border-0 shadow-sm rounded-3">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i> Filter</h5>
                <form method="GET" action="{{ route('catalog.index') }}">
                    @foreach($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category" value="{{ $category->slug }}"
                            {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label d-flex justify-content-between align-items-center">
                                {{ $category->name }} 
                                <span class="badge bg-light text-dark border">{{ $category->products_count }}</span>
                            </label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-outline-secondary mt-2 w-100">Terapkan</button>
                </form>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="col-lg-9">
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-6 col-md-4">
                        <div class="card h-100 card-hover rounded-3 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" class="card-img-top" style="height:200px;object-fit:contain;background:#f8f9fa;">
                                @if($product->has_discount)
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">-{{ $product->discount_percentage }}%</span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($product->name, 40) }}
                                    </a>
                                </h6>
                                <p class="text-primary fw-bold mb-3">{{ $product->formatted_price }}</p>
                                
                                <div class="mt-auto d-flex gap-2">
                                    {{-- Tombol Tambah Keranjang --}}
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button class="btn-bajoo" @if($product->stock == 0) disabled @endif>
                                            <i class="bi bi-cart-plus me-1"></i> Tambah
                                        </button>
                                    </form> 

                                    {{-- Tombol Wishlist --}}
                                    @auth
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-outline-danger border-0 rounded-3">
                                            <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>
                                    </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection