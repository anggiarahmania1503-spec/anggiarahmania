{{-- ================================================
     FILE: resources/views/layouts/admin.blade.php
     FUNGSI: Master layout untuk halaman admin (Modern, konsisten dengan user dashboard)
     ================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #0f172a 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            border-radius: 12px;
            margin: 4px 12px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar .nav-link i {
            width: 24px;
        }

        /* TOPBAR */
        header {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .btn-primary-gradient {
            background: linear-gradient(135deg, #c084fc, #fb7185);
            border: none;
            border-radius: 999px;
            padding: 10px 28px;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }
        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, #fb7185, #c084fc);
        }
        .btn-outline-secondary {
            color: #6b7280;
            border: 1px solid #d1d5db;
            border-radius: 999px;
            padding: 10px 28px;
        }

        /* FLASH ALERT */
        .flash-alert {
            border-radius: 18px;
            padding: 16px 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
            border: none;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .flash-success { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #065f46; }
        .flash-error { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #7f1d1d; }
        .flash-info { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1e3a8a; }
        .flash-alert .icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .flash-success .icon-wrap { background: #10b981; color: #fff; }
        .flash-error .icon-wrap { background: #ef4444; color: #fff; }
        .flash-info .icon-wrap { background: #3b82f6; color: #fff; }

        /* MAIN CONTENT CARD */
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,.05);
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        /* BADGE */
        .badge-status {
            border-radius: 12px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .bg-pending { background-color: #facc15; color: #1f2937; }
        .bg-processing { background-color: #22c55e; color: #ffffff; }
        .bg-completed { background-color: #3b82f6; color: #ffffff; }
        .bg-cancelled { background-color: #ef4444; color: #ffffff; }

        /* TRANSITION HOVER CARD LIST */
        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .bg-opacity-10 { --bs-bg-opacity: 0.1; }

    </style>
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex">

        {{-- SIDEBAR --}}
        <div class="sidebar d-flex flex-column" style="width: 260px;">
            <div class="p-3 border-bottom border-secondary">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <i class="bi bi-shop fs-4 me-2"></i>
                    <span class="fs-5 fw-bold">Admin Panel</span>
                </a>
            </div>
            <nav class="flex-grow-1 py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam me-2"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-folder me-2"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt me-2"></i> Pesanan
                            @php
                                $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-pending ms-auto">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.sales') }}"
                           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up me-2"></i> Laporan Penjualan
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- USER INFO --}}
            <a href="{{ route('profile.edit') }}">
                <div class="p-3 border-top border-secondary">
                    <div class="d-flex align-items-center text-white">
                        <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle me-2" width="36" height="36">
                        <div class="flex-grow-1">
                            <div class="small fw-medium">{{ auth()->user()->name }}</div>
                            <div class="small text-white">Administrator</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-grow-1">
            {{-- TOPBAR --}}
            <header class="bg-white shadow-sm py-3 px-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm me-2" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Toko
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- FLASH MESSAGES --}}
            <div class="px-4 pt-3">
                @include('profile.partials.flash-massages')
            </div>

            {{-- PAGE CONTENT --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
