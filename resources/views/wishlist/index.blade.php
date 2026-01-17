@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<style>
    :root {
        --tkp-primary: #3B6181;
        --tkp-primary-light: #5a8fb9;
    }
    
   .btn-add-cart {
    background: linear-gradient(135deg, var(--tkp-primary), var(--tkp-primary-light));
    color: #ffffff;
    border: none;
    font-weight: 700;
    font-size: 13px;
    width: 100%;
    border-radius: 999px;
    padding: 8px;
    margin-top: 12px;
    transition: all 0.35s ease;
    box-shadow: 0 4px 10px rgba(59, 97, 129, 0.2);
}

.btn-add-cart:hover {
    background: linear-gradient(135deg, var(--tkp-primary-light), var(--tkp-primary));
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(59, 97, 129, 0.25);
}

.btn-add-cart:disabled {
    background: #e0e0e0;
    color: #a0a0a0;
    box-shadow: none;
    transform: none;
}
</style>

<div class="container py-5" style="margin-top: 80px;">
    <h1 class="h3 fw-bold mb-4">Wishlist Saya</h1>

    @if($products->count())
        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-2" style="border-radius: 15px;">
                        <x-product-card :product="$product" />
                        
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            {{-- Memastikan quantity terkirim agar tidak error --}}
                            <input type="hidden" name="quantity" value="1"> 

                            <button type="submit" class="btn-add-cart" 
                                @if($product->stock == 0) disabled @endif>
                                <i class="bi bi-cart-plus me-1"></i>
                                {{ $product->stock == 0 ? 'Habis' : 'Tambah Keranjang' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5" 
             style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 20px; box-shadow: 0 12px 30px rgba(0,0,0,.08);">
            <div class="mb-3">
                <i class="bi bi-heart text-secondary" style="font-size: 4rem;"></i>
            </div>
            <h3 class="h5 fw-medium text-dark">Wishlist Kosong</h3>
            <p class="text-muted mt-1">Simpan produk yang kamu suka di sini.</p>
            <a href="{{ route('catalog.index') }}" 
               class="btn btn-primary"
               style="border-radius: 999px; padding: 10px 28px; font-weight: 600;
                      background: linear-gradient(135deg, #3B6181, #5a8fb9); border: none; color: white;">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection