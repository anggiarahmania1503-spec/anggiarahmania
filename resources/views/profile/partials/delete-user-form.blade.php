<style>
.delete-card {
    background: linear-gradient(135deg, #fee2e2, #fef2f2);
    border-radius: 20px;
    padding: 20px;
}

.btn-danger-soft {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 10px 18px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-danger-soft:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
}

.modal-content {
    border-radius: 22px;
    border: none;
    box-shadow: 0 30px 80px rgba(0,0,0,.2);
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}

.warning-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #b91c1c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
</style>

<div class="delete-card">
    <p class="small text-muted mb-3">
        Tindakan ini <strong>tidak bisa dibatalkan</strong>.  
        Semua data, pesanan, dan informasi akun akan terhapus permanen.
    </p>

    <button type="button"
            class="btn btn-danger-soft"
            data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletionModal">
        <i class="bi bi-trash3 me-1"></i> Hapus Akun
    </button>
</div>

{{-- MODAL --}}
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')

            <div class="modal-body p-4">
                <div class="d-flex gap-3 align-items-start mb-3">
                    <div class="warning-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Hapus Akun?</h5>
                        <p class="small text-muted mb-0">
                            Aksi ini akan menghapus akun kamu secara permanen.
                        </p>
                    </div>
                </div>

                <p class="small text-muted">
                    Masukkan password untuk konfirmasi.
                </p>

                <div class="mb-3">
                    <input type="password"
                           name="password"
                           class="form-control form-control-lg @error('password', 'userDeletion') is-invalid @enderror"
                           placeholder="Password kamu"
                           required>
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn btn-danger-soft">
                        Ya, Hapus Akun
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script type="module">
    const modal = new bootstrap.Modal(
        document.getElementById('confirmUserDeletionModal')
    );
    modal.show();
</script>
@endif
