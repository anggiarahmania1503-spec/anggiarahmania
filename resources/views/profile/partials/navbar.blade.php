<style>
/* =========================
   NAVBAR GRADIENT ROUNDED
   ========================= */
.navbar-final {
    background: linear-gradient(135deg, #3f4a65, #2e3745);
    padding: 18px 0;
    border-bottom: 1px solid rgba(255,255,255,.06);
}

/* BRAND */
.navbar-final .navbar-brand {
    font-weight: 800;
    font-size: 1.4rem;
    color: #f8fafc !important;
}

.navbar-final .navbar-brand i {
    font-size: 1.45rem;
    color: #c7d2fe;
}

/* SEARCH */
.navbar-final .search-box {
    max-width: 440px;
    width: 100%;
}

.navbar-final .search-box input {
    height: 48px;
    background: rgba(2,6,23,.9);
    border: 1px solid rgba(255,255,255,.15);
    color: #f8fafc;
    border-radius: 999px 0 0 999px;
}

.navbar-final .search-box input::placeholder {
    color: #cbd5f5;
}

.navbar-final .search-box button {
    height: 48px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.15);
    border-left: none;
    color: #fff;
    border-radius: 0 999px 999px 0;
}

.navbar-final .search-box button:hover {
    background: rgba(255,255,255,.22);
}

/* LINKS */
.navbar-final .nav-link {
    color: #e5e7eb !important;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 999px;
}

.navbar-final .nav-link:hover {
    background: rgba(255,255,255,.14);
}

/* ICON */
.navbar-final .nav-icon {
    font-size: 1.2rem;
    color: #f8fafc;
    position: relative;
}

/* USER */
.navbar-final .user-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: rgba(255,255,255,.12);
    border-radius: 999px;
    color: #fff;
}

.navbar-final .user-pill img {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
}

/* DROPDOWN */
.navbar-final .dropdown-menu {
    background: #020617;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,.15);
}

.navbar-final .dropdown-item {
    color: #e5e7eb;
}

.navbar-final .dropdown-item:hover {
    background: rgba(255,255,255,.12);
}
</style>

<nav class="navbar navbar-expand-lg navbar-final sticky-top">
    <div class="container">

        {{-- BRAND --}}
        <a class="navbar-brand d-flex align-items-center"
           href="{{ route('home') }}">
            <i class="bi bi-bag-heart-fill me-2"></i>
            TokoOnline
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler text-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- SEARCH --}}
            <form class="search-box mx-auto d-flex"
                  action="{{ route('catalog.index') }}"
                  method="GET">
                <input type="text" name="q"
                       class="form-control"
                       placeholder="Cari produk..."
                       value="{{ request('q') }}">
                <button class="btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-auto align-items-center gap-1">

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid me-1"></i> Katalog
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link nav-icon"
                           href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart"></i>
                            @if(auth()->user()->wishlistProducts()->count())
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    {{ auth()->user()->wishlistProducts()->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-icon"
                           href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            @php
                                $cartCount = auth()->user()->cart?->items()->count() ?? 0;
                            @endphp
                            @if($cartCount)
                                <span class="badge bg-primary position-absolute top-0 start-100 translate-middle">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown ms-2">
                        <a class="user-pill dropdown-toggle"
                           data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}">
                            <span>{{ auth()->user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan Saya</a></li>

                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-info"
                                       href="{{ route('admin.dashboard') }}">
                                        Admin Panel
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm ms-2" href="{{ route('register') }}">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
