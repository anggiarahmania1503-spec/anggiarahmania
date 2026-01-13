<p class="text-muted small mb-4">
    Gunakan password yang kuat dan sulit ditebak untuk menjaga keamanan akun kamu.
</p>

<style>
.password-group {
    position: relative;
}

.password-group i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #64748b; /* abu lembut, sama dengan form info */
}

.input-password {
    padding-left: 42px;
    border-radius: 14px;
    border: 1px solid rgba(100,116,139,0.3); /* abu lembut */
    background: rgba(243,244,246,0.8); /* abu soft */
    transition: all 0.35s ease;
    color: #0f172a;
}

.input-password:focus {
    border-color: #94a3b8; /* gradasi lembut */
    box-shadow: 0 0 8px rgba(148,163,184,0.25);
    outline: none;
}

/* Tombol disamakan dengan btn-profile dari Update Info */
.btn-password {
    background: linear-gradient(135deg, #1e293b, #334155); /* gradasi gelap seperti btn-creative */
    color: #f8fafc; /* teks putih */
    border-radius: 999px;
    padding: 10px 34px;
    font-weight: 700;
    border: none;
    transition: all 0.35s ease;
}

.btn-password:hover {
    background: linear-gradient(135deg, #334155, #1e293b); /* kebalikan gradasi saat hover */
    transform: translateY(-3px);
    color: #f8fafc;
}

.saved-badge {
    background: linear-gradient(135deg, #d1d5db, #e5e7eb); /* abu lembut */
    color: #0f172a;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.75rem;
}
</style>

<form method="post" action="#">
    @csrf
    @method('put')

    {{-- Current Password --}}
    <div class="mb-3">
        <label for="current_password" class="form-label fw-semibold">
            Password Saat Ini
        </label>
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
        <label for="password" class="form-label fw-semibold">
            Password Baru
        </label>
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
        <label for="password_confirmation" class="form-label fw-semibold">
            Konfirmasi Password Baru
        </label>
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
        <button type="submit" class="btn btn-password">
            Update Password
        </button>

        @if (session('status') === 'password-updated')
            <span class="saved-badge fade-out">
                Password diperbarui
            </span>
            <script>
                setTimeout(() => {
                    const el = document.querySelector('.fade-out');
                    if (el) el.style.display = 'none';
                }, 2200);
            </script>
        @endif
    </div>
</form>
