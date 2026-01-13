<style>
/* ================= FOOTER MODERN ================= */
.site-footer {
    background: linear-gradient(135deg, #1f2937, #111827); /* gelap tapi kontras */
    border-top-left-radius: 48px;
    border-top-right-radius: 48px;
    color: #f1f5f9;
    padding: 60px 0 40px;
    font-family: 'Inter', sans-serif;
}

.site-footer h5,
.site-footer h6 {
    font-weight: 600;
    letter-spacing: .3px;
    color: #f8fafc;
}

.site-footer a {
    transition: all .25s ease;
    color: #cbd5e1;
}

.site-footer a:hover {
    color: #ffffff !important;
    transform: translateX(4px);
}

.footer-social a {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
    transition: all .3s ease;
    color: #f1f5f9;
    font-size: 1.1rem;
}

.footer-social a:hover {
    background: rgba(241,245,249,.2);
    transform: translateY(-4px);
    color: #111827;
}

.footer-divider {
    border-color: rgba(255,255,255,.15);
}

/* Footer lists */
.site-footer ul li {
    margin-bottom: 12px;
}
.site-footer ul li a {
    text-decoration: none;
    color: #cbd5e1;
}
.site-footer ul li a:hover {
    color: #f1f5f9;
    text-decoration: underline;
}

/* Footer small text */
.site-footer p,
.site-footer .small {
    color: #94a3b8;
}
</style>

<footer class="site-footer mt-5">
    <div class="container">
        <div class="row g-4">
            {{-- Brand --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-3">
                    <i class="bi bi-bag-heart-fill me-2 text-white"></i> TokoOnline
                </h5>
                <p class="text-secondary">
                    Toko online seragam & kaos custom. Produksi rapi, harga masuk akal, hasil maksimal.
                </p>

                <div class="d-flex gap-3 mt-4 footer-social">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Menu --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="mb-3">Menu</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('catalog.index') }}">Katalog Produk</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="mb-3">Bantuan</h6>
                <ul class="list-unstyled">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Cara Pesan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled text-secondary">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Bandung, Jawa Barat</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> 0895800899494</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> @bajoo@gmail.com</li>
                </ul>
            </div>
        </div>

        <hr class="my-4 footer-divider">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0">&copy; {{ date('Y') }} TokoOnline — dibuat serius, bukan asal jadi.</p>
            </div>

            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <img src="{{ asset('images/payment-methods.png') }}" alt="Payment Methods" height="30" class="opacity-75">
            </div>
        </div>
    </div>
</footer>
