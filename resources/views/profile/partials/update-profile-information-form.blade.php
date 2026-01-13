<p class="text-muted small mb-4">
    Perbarui informasi profil dan alamat email kamu.
</p>

<style>
.profile-group {
    position: relative;
}

.profile-group i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #94a3b8;
}

.input-profile {
    padding-left: 42px;
    border-radius: 14px;
}

textarea.input-profile {
    padding-left: 14px;
}

.btn-profile {
    border-radius: 999px;
    padding: 10px 34px;
    font-weight: 600;
}

.verify-box {
    background: rgba(251, 191, 36, 0.12);
    border-left: 4px solid #f59e0b;
    padding: 10px 14px;
    border-radius: 12px;
}
</style>

<form id="send-verification" method="post" action="#">
    @csrf
</form>

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

        {{-- Email Verification Notice --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="verify-box mt-3">
                <p class="small mb-1 text-warning fw-semibold">
                    Email kamu belum diverifikasi.
                </p>
                <button form="send-verification"
                        class="btn btn-link p-0 text-decoration-none fw-semibold">
                    Kirim ulang email verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="text-success small fw-bold mt-1">
                        Link verifikasi telah dikirim ke email kamu.
                    </p>
                @endif
            </div>
        @endif
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
        <div class="form-text">
            Format: 08xxxxxxxxxx atau +628xxxxxxxxxx
        </div>
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

    <button type="submit" class="btn btn-primary btn-profile">
        Simpan Informasi
    </button>
</form>
