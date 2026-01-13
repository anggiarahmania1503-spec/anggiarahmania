@extends('layouts.app')

@section('title', 'Masuk Akun')

@push('styles')
<style>
.login-wrap {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255,255,255,.75);
    backdrop-filter: blur(18px);
    border-radius: 32px;
    padding: 40px;
    box-shadow:
        0 40px 80px rgba(0,0,0,.18),
        inset 0 0 0 1px rgba(255,255,255,.5);
}

.login-card h1 {
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    letter-spacing: -0.6px;
}

.login-card .form-control {
    border-radius: 999px;
    padding: 14px 18px;
    border: none;
    background: rgba(255,255,255,.85);
    box-shadow: inset 0 0 0 1px rgba(0,0,0,.08);
}

.login-card .form-control:focus {
    outline: none;
    box-shadow:
        inset 0 0 0 2px #c084fc,
        0 10px 25px rgba(192,132,252,.25);
}

.login-card .btn-login {
    width: 100%;
    border-radius: 999px;
    padding: 14px;
    font-weight: 700;
    background: linear-gradient(135deg, #c084fc, #fb7185);
    border: none;
    color: white;
    box-shadow: 0 20px 40px rgba(251,113,133,.45);
    transition: .35s ease;
}

.login-card .btn-login:hover {
    transform: translateY(-3px) scale(1.03);
}

.login-card .helper {
    font-size: .9rem;
    opacity: .75;
}
</style>
@endpush

@section('content')
<div class="login-wrap">
    <div class="login-card">
        <h1 class="mb-2">Masuk Akun</h1>
        <p class="text-muted mb-4">
            Kelola pesanan & favorit Anda dengan mudah
        </p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Email"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Password"
                       required>
            </div>

            {{-- Remember --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember">
                    <span class="helper">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="helper text-decoration-none">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                Masuk
            </button>
        </form>

        <p class="text-center mt-4 helper">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-bold text-decoration-none">
                Daftar sekarang
            </a>
        </p>
    </div>
</div>
@endsection
