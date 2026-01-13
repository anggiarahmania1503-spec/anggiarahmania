@extends('layouts.admin')

@section('title', 'Produk')

@push('styles')
<style>
/* ===== HOME STYLE ADMIN ===== */
.admin-wrap {
    --radius: 22px;
    --shadow-soft: 0 20px 50px rgba(0,0,0,.06);
}

.admin-card {
    border-radius: var(--radius);
    box-shadow: var(--shadow-soft);
    border: none;
    background: #fff;
}

.admin-filter input,
.admin-filter select {
    border-radius: 999px;
    padding: 12px 18px;
    border: 1px solid #e5e7eb;
    font-size: .9rem;
}

.admin-filter input:focus,
.admin-filter select:focus {
    box-shadow: none;
    border-color: var(--bs-primary);
}

.admin-table th {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #6b7280;
    border-bottom: none;
}

.admin-table td {
    padding: 18px 16px;
    border-top: none;
    vertical-align: middle;
}

.admin-table tbody tr {
    transition: .2s ease;
}

.admin-table tbody tr:hover {
    background: #f9fafb;
}

.admin-table img {
    border-radius: 16px;
}

.badge {
    border-radius: 999px;
    padding: 6px 12px;
    font-weight: 500;
}

.btn {
    border-radius: 999px;
    font-size: .8rem;
    padding: 6px 14px;
}
</style>
@endpush

@section('content')
<div class="admin-wrap">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Produk</h2>
            <p class="text-muted small mb-0">
                Kelola produk yang tampil di halaman utama
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4">
            + Produk
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="row g-3 mb-4 admin-filter">
        <div class="col-md-5">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari produk..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-outline-primary w-100">
                Filter
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $product->primaryImage?->image_url ?? asset('img/no-image.png') }}"
                                     width="56" height="56">

                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">
                                        Rp {{ number_format($product->price) }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        <td>{{ $product->category->name }}</td>

                        <td>Rp {{ number_format($product->price) }}</td>

                        <td>{{ $product->stock }}</td>

                        <td>
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="btn btn-outline-info">
                                    Detail
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="btn btn-outline-warning">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline mb-1" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Produk belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
