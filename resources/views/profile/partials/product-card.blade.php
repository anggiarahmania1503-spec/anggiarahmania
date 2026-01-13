<style>
:root {
    --tkp-primary: #1e293b; /* abu gelap */
    --tkp-primary-light: #334155;
    --tkp-accent: #ff8000;
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --soft-bg: #f8fafc;
}

/* CARD WRAPPER */
.tkp-card {
    border: 1px solid rgba(100,116,139,0.15);
    border-radius: 16px;
    background: #fff;
    transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tkp-card:hover {
    box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    transform: translateY(-6px);
    border-color: var(--tkp-primary);
}

/* IMAGE AREA */
.tkp-img-wrapper {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: var(--soft-bg);
}

.tkp-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.tkp-card:hover .tkp-img-wrapper img {
    transform: scale(1.08);
}

/* WISHLIST BUTTON */
.tkp-wishlist {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 10;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.3);
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.tkp-wishlist:hover {
    background: #fff;
    color: var(--tkp-accent);
    box-shadow: 0 4px 12px rgba(255,128,0,0.2);
}

/* CONTENT AREA */
.tkp-content {
    padding: 12px 14px 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.tkp-title {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 40px;
    line-height: 1.4;
    transition: color 0.2s;
}

.tkp-card:hover .tkp-title {
    color: var(--tkp-primary);
}

.tkp-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.5px;
}

/* DISCOUNT BADGE */
.tkp-discount-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 4px 0;
}

.tkp-badge-disc {
    background: linear-gradient(135deg, #ff7675, #ef144a);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 6px;
}

.tkp-old-price {
    font-size: 11px;
    color: var(--text-muted);
    text-decoration: line-through;
}

/* SHOP INFO & RATING */
.tkp-shop-info {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: auto;
    padding-top: 8px;
}

.tkp-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 4px;
}

.bi-star-fill {
    color: #ffc400;
}

/* ================= ACTION BUTTON ================= */
.btn-add-cart {
    background: linear-gradient(135deg, var(--tkp-primary), var(--tkp-primary-light));
    color: #f8fafc;
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

<div class="tkp-card">
    {{-- IMAGE AREA --}}
    <div class="tkp-img-wrapper">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        </a>

        @auth
            <button type="button" 
                    onclick="toggleWishlist({{ $product->id }})" 
                    class="tkp-wishlist">
                <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @endauth
    </div>

    {{-- CONTENT AREA --}}
    <div class="tkp-content">
        <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none">
            <h3 class="tkp-title">{{ $product->name }}</h3>
        </a>

        <div class="tkp-price">{{ $product->formatted_price }}</div>

        @if($product->has_discount)
            <div class="tkp-discount-wrap">
                <span class="tkp-badge-disc">{{ $product->discount_percentage }}%</span>
                <span class="tkp-old-price">{{ $product->formatted_original_price }}</span>
            </div>
        @else
            <div style="height: 23px;"></div>
        @endif

        <div class="tkp-shop-info">
            <i class="bi bi-geo-alt-fill text-secondary"></i>
            <span>{{ $product->category->name }}</span>
        </div>

        <div class="tkp-rating">
            <div class="d-flex align-items-center">
                <i class="bi bi-star-fill me-1"></i>
                <span class="fw-bold text-dark">4.8</span>
            </div>
            <span class="mx-1">|</span>
            <span>Terjual 100+</span>
        </div>

        {{-- ACTION BUTTON --}}
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">

            <button type="submit" class="btn-add-cart" @if($product->stock == 0) disabled @endif>
                @if($product->stock == 0)
                    Habis
                @else
                    <i class="bi bi-cart-plus me-1"></i> + Keranjang
                @endif
            </button>
        </form>
    </div>
</div>
