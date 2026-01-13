<style>
.social-card {
    background: rgba(255,255,255,.85);
    backdrop-filter: blur(14px);
    border-radius: 18px;
    padding: 18px 20px;
    box-shadow: 0 18px 40px rgba(0,0,0,.08);
}

.social-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
}

.status-connected {
    color: #16a34a;
    font-weight: 600;
}

.status-disconnected {
    color: #64748b;
}

/* Tombol konsisten dengan Profile/Home */
.btn-social {
    border-radius: 12px;
    padding: 6px 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-social-connect {
    background: linear-gradient(135deg, #3B6181, #5a8fb9);
    color: #fff;
    border: none;
}

.btn-social-connect:hover {
    background: linear-gradient(135deg, #5a8fb9, #3B6181);
    transform: translateY(-2px);
}

.btn-social-disconnect {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    border: none;
}

.btn-social-disconnect:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
}
</style>

<div class="social-card d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        {{-- Google Icon --}}
        <div class="social-icon">
            {{-- ... svg icon ... --}}
        </div>

        <div>
            <h6 class="mb-1 fw-bold">Google</h6>
            @if($user->google_id)
                <span class="status-connected">
                    <i class="bi bi-check-circle-fill me-1"></i> Terhubung
                </span>
            @else
                <span class="status-disconnected">
                    Belum terhubung
                </span>
            @endif
        </div>
    </div>

    <div>
        @if($user->google_id)
            <form action="#" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-social btn-social-disconnect"
                        onclick="return confirm('Putuskan koneksi dengan Google?')">
                    Putuskan
                </button>
            </form>
        @else
            <a href="{{ route('auth.google') }}"
               class="btn btn-social btn-social-connect">
                Hubungkan
            </a>
        @endif
    </div>
</div>
