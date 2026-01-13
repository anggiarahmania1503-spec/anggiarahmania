@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Card Utama --}}
            <div class="card shadow-sm border-0" style="border-radius: 20px; box-shadow: 0 18px 40px rgba(0,0,0,0.08);">
                
                {{-- Header --}}
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-dark">Detail Pesanan #{{ $order->order_number }}</h4>
                    <span class="badge rounded-pill px-3 py-2"
                        @if($order->status == 'pending') 
                            style="background: #fbbf24; color: #1e1e1e;"
                        @elseif($order->status == 'success') 
                            style="background: linear-gradient(135deg, #3B6181, #5a8fb9); color: white;"
                        @else 
                            style="background: #6b7280; color: white;"
                        @endif>
                        {{ strtoupper($order->status) }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted small mb-4">Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}</p>

                    {{-- Produk --}}
                    <h6 class="fw-bold mb-3">Produk yang Dipesan</h6>
                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-0">
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Ongkos Kirim:</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="fs-5">
                                    <td colspan="3" class="text-end fw-bold">TOTAL BAYAR:</td>
                                    <td class="text-end fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Alamat Pengiriman --}}
                    <div class="bg-white bg-opacity-90 p-4 rounded-4 mb-4" style="backdrop-filter: blur(14px); box-shadow: 0 12px 24px rgba(0,0,0,0.06);">
                        <h6 class="fw-bold mb-2">Alamat Pengiriman</h6>
                        <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                        <p class="mb-1 text-muted">{{ $order->shipping_phone }}</p>
                        <p class="mb-0 text-muted">{{ $order->shipping_address }}</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 text-center">
                        @if($order->payment_status !== 'paid')
                            <button 
                                class="btn btn-lg rounded-pill"
                                style="background: linear-gradient(135deg, #3B6181, #5a8fb9); color: white; font-weight: 600;"
                                id="pay-button"
                                data-order-id="{{ $order->id }}">
                                Bayar Sekarang
                            </button>
                        @endif

                        <a href="{{ url('/') }}" 
                           class="btn btn-outline-secondary btn-lg px-4 rounded-pill mt-2"
                           style="border-color:#cbd5e1; color:#3B6181;">
                           &larr; Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Midtrans --}}
@if($order->payment_status !== 'paid')
@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
    const orderId = this.getAttribute('data-order-id');
    
    fetch("{{ route('payment.snap', $order) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.token) {
            alert(data.error ?? 'Gagal mendapatkan token');
            return;
        }

        snap.pay(data.token, {
            onSuccess: function (result) {
                window.location.href = "/orders/" + orderId + "/success";
            },
            onPending: function (result) {
                window.location.href = "/orders/" + orderId + "/pending";
            },
            onError: function () {
                alert('Pembayaran gagal');
            },
            onClose: function () {
                alert('Pembayaran dibatalkan');
            }
        });
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
});
</script>
@endpush
@endif

@endsection
