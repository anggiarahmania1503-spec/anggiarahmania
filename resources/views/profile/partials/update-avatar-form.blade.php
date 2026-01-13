@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
/* ================= GENERAL ================= */
.profile-wrap {
    max-width: 960px;
    margin: auto;
    padding-bottom: 80px;
}
.profile-title {
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    letter-spacing: -0.6px;
}

/* ================= INPUTS ================= */
.profile-group, .password-group {
    position: relative;
}

.profile-group i, .password-group i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #64748b; /* abu lembut */
}

.input-profile, .input-password {
    padding-left: 42px;
    border-radius: 14px;
    border: 1px solid rgba(100,116,139,0.3);
    background: rgba(243,244,246,0.8);
    transition: all 0.35s ease;
    color: #0f172a;
}

.input-profile:focus, .input-password:focus {
    border-color: #94a3b8;
    box-shadow: 0 0 8px rgba(148,163,184,0.25);
    outline: none;
}

textarea.input-profile {
    padding-left: 14px;
}

/* ================= BUTTONS ================= */
.btn-profile, .btn-password, .btn-save-avatar {
    background: linear-gradient(135deg, #1e293b, #334155);
    color: #f8fafc;
    border-radius: 999px;
    padding: 10px 34px;
    font-weight: 700;
    border: none;
    transition: all 0.35s ease;
}

.btn-profile:hover, .btn-password:hover, .btn-save-avatar:hover {
    background: linear-gradient(135deg, #334155, #1e293b);
    transform: translateY(-3px);
    color: #f8fafc;
}

/* ================= VERIFY BOX ================= */
.verify-box {
    background: rgba(251, 191, 36, 0.12);
    border-left: 4px solid #f59e0b;
    padding: 10px 14px;
    border-radius: 12px;
}

/* ================= AVATAR ================= */
.avatar-wrapper {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    padding: 4px;
    background: linear-gradient(135deg, #c084fc, #fb7185);
}

.avatar-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    background: white;
}

.avatar-delete {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    font-size: 14px;
    line-height: 1;
}

.file-modern {
    border-radius: 14px;
    padding: 12px 14px;
}

/* ================= DANGER ================= */
.profile-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-radius: 32px;
    border: none;
}

/* ================= SAVED BADGE ================= */
.saved-badge {
    background: linear-gradient(135deg, #d1d5db, #e5e7eb);
    color: #0f172a;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.75rem;
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

        {{-- ================= AVATAR ================= --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Foto Profil</div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Upload foto profil kamu. Format JPG, PNG, WebP. Maksimal 2MB.
                </p>
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="position-relative">
                            <div class="avatar-wrapper">
                                <img id="avatar-preview"
                                     src="{{ $user->avatar_url ? asset('storage/avatars/' . $user->avatar_url) : asset('images/default-avatar.png') }}"
                                     alt="{{ $user->name }}">
                            </div>
                            @if($user->avatar)
                                <button type="button"
                                        onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit()"
                                        class="btn btn-danger avatar-delete position-absolute top-0 start-100 translate-middle"
                                        title="Hapus foto">&times;</button>
                            @endif
                        </div>
                        <div class="flex-grow-1" style="min-width: 240px;">
                            <input type="file"
                                   name="avatar"
                                   id="avatar"
                                   accept="image/*"
                                   onchange="previewAvatar(event)"
                                   class="form-control file-modern @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-save-avatar">Simpan Foto</button>
                    </div>
                </form>
                <form id="delete-avatar-form" action="#" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
                <script>
                    function previewAvatar(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                </script>
            </div>
        </div>

        {{-- ================= UPDATE PROFILE ================= --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Informasi Akun</div>
            <div class="card-body">
                <p class="text-muted small mb-4">Perbarui informasi profil dan alamat email kamu.</p>
                <form method="post" action="#">
                    @csrf
                    @method('patch')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                        <div class="profile-group">
                            <i class="bi bi-person-fill"></i>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control input-profile @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <div class="profile-group">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control input-profile @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                        <div class="profile-group">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   class="form-control input-profile @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Format: 08xxxxxxxxxx atau +628xxxxxxxxxx</div>
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="address"
                                  id="address"
                                  rows="3"
                                  class="form-control input-profile @error('address') is-invalid @enderror"
                                  placeholder="Alamat lengkap untuk pengiriman">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-profile">Simpan Informasi</button>
                </form>
            </div>
        </div>

        {{-- ================= UPDATE PASSWORD ================= --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Keamanan Akun</div>
            <div class="card-body">
                <p class="text-muted small mb-4">
                    Gunakan password yang kuat dan sulit ditebak untuk menjaga keamanan akun kamu.
                </p>
                <form method="post" action="#">
                    @csrf
                    @method('put')

                    {{-- Current Password --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                        <div class="password-group">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   class="form-control input-password @error('current_password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password Baru</label>
                        <div class="password-group">
                            <i class="bi bi-shield-lock-fill"></i>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control input-password @error('password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <div class="password-group">
                            <i class="bi bi-check-circle-fill"></i>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control input-password @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <button type="submit" class="btn btn-password">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <span class="saved-badge fade-out">Password diperbarui</span>
                            <script>
                                setTimeout(() => {
                                    const el = document.querySelector('.fade-out');
                                    if (el) el.style.display = 'none';
                                }, 2200);
                            </script>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- ================= CONNECTED ACCOUNTS ================= --}}
        <div class="card profile-card mb-4">
            <div class="card-header">Akun Terhubung</div>
            <div class="card-body">
                @include('profile.partials.connected-accounts')
            </div>
        </div>

        {{-- ================= DELETE ACCOUNT ================= --}}
        <div class="card profile-danger mt-5">
            <div class="card-header text-danger">Zona Berbahaya</div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
