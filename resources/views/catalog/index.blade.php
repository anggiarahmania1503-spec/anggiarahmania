@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<div class="row">
    {{-- Sidebar Filter --}}
    <div class="col-lg-3 mb-4">
        <div class="card p-3">
            <h5 class="fw-bold"><i class="bi bi-funnel me-2"></i> Filter</h5>
            <form method="GET" action="{{ route('catalog.index') }}">
                @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" value="{{ $category->slug }}"
                        {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label">{{ $category->name }} <span class="badge bg-secondary">{{ $category->products_count }}</span></label>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-sm btn-outline-primary mt-2 w-100">Terapkan</button>
            </form>
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="col-lg-9">
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-md-4">
                    <div class="card card-hover">
                        <img src="{{ $product->image_url }}" class="card-img-top" style="height:200px;object-fit:contain;">
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $product->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
                                @if($product->has_discount)
                                    <span class="badge bg-danger">-{{ $product->discount_percentage }}%</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="btn btn-primary btn-sm w-100" @if($product->stock==0) disabled @endif>Tambah</button>
                                </form>
                                @auth
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-wishlist btn-outline-danger btn-sm w-100">
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
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
