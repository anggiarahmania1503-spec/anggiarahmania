@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
:root {
    --primary-dark: #0f172a;
    --primary: #334155;
    --accent: #3b82f6;
    --bg-gradient-start: #e2e6eb;
    --bg-gradient-end: #cbd2d9;
    --transition-standard: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

body {
    background: linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--primary-dark);
}

.profile-wrap {
    max-width: 960px;
    margin: auto;
}

.profile-title {
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    letter-spacing: -0.6px;
    color: var(--primary-dark);
}

.profile-card {
    background: rgba(255,255,255,.8);
    backdrop-filter: blur(18px);
    border-radius: 32px;
    border: none;
    box-shadow: 0 25px 50px rgba(0,0,0,.12);
    transition: var(--transition-standard);
}

.profile-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 35px 60px rgba(0,0,0,.15);
}

.profile-card .card-header {
    background: transparent;
    border: none;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 28px 32px 10px;
    color: var(--primary-dark);
}

.profile-card .card-body {
    padding: 24px 32px 32px;
}

.profile-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-radius: 32px;
    border: none;
}

.btn-profile {
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    padding: 10px 24px;
    border-radius: 999px;
    transition: var(--transition-standard);
    border: none;
}

.btn-profile:hover {
    background: var(--primary-dark);
    transform: translateY(-3px);
    color: #fff;
}

input.form-control, select.form-select {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.15);
    padding: 10px 15px;
    transition: var(--transition-standard);
}

input.form-control:focus, select.form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 8px rgba(59,130,246,.3);
    outline: none;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="profile-wrap">

        <h2 class="profile-title mb-4">Profil Saya</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FOTO PROFIL --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Foto Profil</div>
            <div class="card-body">
                @include('profile.partials.update-avatar-form')
            </div>
        </div>

        {{-- INFORMASI PROFIL --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Informasi Akun</div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- UPDATE PASSWORD --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Keamanan Akun</div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- CONNECTED ACCOUNTS --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Akun Terhubung</div>
            <div class="card-body">
                @include('profile.partials.connected-accounts')
            </div>
        </div>

        {{-- DELETE ACCOUNT --}}
        <div class="card profile-danger mt-5">
            <div class="card-header text-danger">Zona Berbahaya</div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
