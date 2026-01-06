@extends('layouts.app')

@section('title', 'Pembayaran Sukses')

@section('content')
<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card text-center">
				<div class="card-body">
					<h1 class="display-4 text-success">Pembayaran Berhasil</h1>
					<p class="lead">Terima kasih! Pesanan Anda telah berhasil dibayar.</p>

					@if(isset($order))
						<p><strong>Nomor Pesanan:</strong> {{ $order->id }}</p>
						<p><strong>Total:</strong> Rp {{ number_format($order->total ?? $order->grand_total ?? 0, 0, ',', '.') }}</p>
						<p><strong>Status:</strong> <span class="badge bg-success">Selesai</span></p>
					@endif

					<div class="mt-4">
						@if(isset($order))
							<a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Lihat Pesanan</a>
						@endif
						<a href="{{ url('/') }}" class="btn btn-secondary">Kembali ke Beranda</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
