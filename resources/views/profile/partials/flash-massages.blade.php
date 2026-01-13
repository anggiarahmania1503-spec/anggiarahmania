<style>
.flash-alert {
    border-radius: 18px;
    padding: 16px 20px;
    box-shadow: 0 12px 30px rgba(0,0,0,.08);
    border: none;
    transition: all 0.35s ease;
}

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

/* SUCCESS */
.flash-success {
    background: linear-gradient(135deg, #f0fdf4, #d1fae5);
    color: #065f46;
}
.flash-success .icon-wrap {
    background: linear-gradient(135deg, #22c55e, #10b981);
    color: white;
}

/* ERROR */
.flash-error {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    color: #7f1d1d;
}
.flash-error .icon-wrap {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

/* INFO */
.flash-info {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    color: #1e3a8a;
}
.flash-info .icon-wrap {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

/* Close button hover */
.flash-alert .btn-close {
    opacity: 0.7;
    transition: all 0.25s ease;
}
.flash-alert .btn-close:hover {
    opacity: 1;
}
</style>

{{-- SUCCESS --}}
@if(session('success'))
<div class="alert flash-alert flash-success alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
    <div class="icon-wrap">
        <i class="bi bi-check-lg"></i>
    </div>
    <div class="flex-grow-1">
        <strong>Berhasil</strong>
        <div class="small mt-1">{{ session('success') }}</div>
    </div>
    <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ERROR --}}
@if(session('error'))
<div class="alert flash-alert flash-error alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
    <div class="icon-wrap">
        <i class="bi bi-x-lg"></i>
    </div>
    <div class="flex-grow-1">
        <strong>Gagal</strong>
        <div class="small mt-1">{{ session('error') }}</div>
    </div>
    <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- INFO --}}
@if(session('info'))
<div class="alert flash-alert flash-info alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
    <div class="icon-wrap">
        <i class="bi bi-info-lg"></i>
    </div>
    <div class="flex-grow-1">
        <strong>Info</strong>
        <div class="small mt-1">{{ session('info') }}</div>
    </div>
    <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- VALIDATION ERROR --}}
@if($errors->any())
<div class="alert flash-alert flash-error alert-dismissible fade show d-flex gap-3 align-items-start" role="alert">
    <div class="icon-wrap">
        <i class="bi bi-exclamation-lg"></i>
    </div>
    <div class="flex-grow-1">
        <strong>Periksa lagi</strong>
        <ul class="small mt-2 mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="btn-close mt-1" data-bs-dismiss="alert"></button>
</div>
@endif
