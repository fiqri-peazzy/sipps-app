@extends('layouts.frontend')
@section('title', 'Beranda')

@push('styles')
    <style>
        /* Hero Section Modern */
        .hero-banner {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-content p {
            font-size: 1.15rem;
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .btn-hero {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .lottie-hero {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 2px;
        }

        .section-title p {
            color: #64748b;
            font-size: 1.1rem;
        }

        /* Service Card */
        .service-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .service-icon i {
            font-size: 2.5rem;
            color: #fff;
        }

        .service-card h4 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        /* Portfolio Card dengan Hover Animation */
        .portfolio-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
        }

        .portfolio-card:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
        }

        .portfolio-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .portfolio-card:hover .portfolio-img {
            transform: scale(1.2);
            filter: brightness(0.7);
        }

        .portfolio-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            color: #fff;
            padding: 2rem 1.5rem;
            transform: translateY(60%);
            transition: all 0.4s ease;
        }

        .portfolio-card:hover .portfolio-overlay {
            transform: translateY(0);
        }

        .portfolio-category {
            display: inline-block;
            background: rgba(99, 102, 241, 0.9);
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .portfolio-overlay h5 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .portfolio-overlay p {
            font-size: 0.9rem;
            margin: 0;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.1s;
        }

        .portfolio-card:hover .portfolio-overlay p {
            opacity: 1;
            transform: translateY(0);
        }

        /* Testimonial Slider */
        .testimonial-slider {
            position: relative;
            overflow: hidden;
            padding: 2rem 0;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .testimonial-slide {
            min-width: 100%;
            padding: 0 15px;
        }

        .testimonial-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .testimonial-info h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .testimonial-rating {
            color: #f59e0b;
            margin: 0.3rem 0;
        }

        .testimonial-text {
            color: #64748b;
            line-height: 1.8;
            font-style: italic;
        }

        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2rem;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background: #6366f1;
            width: 35px;
            border-radius: 10px;
        }

        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .slider-arrow:hover {
            background: #6366f1;
            color: #fff;
            transform: translateY(-50%) scale(1.1);
        }

        .slider-arrow.prev {
            left: -20px;
        }

        .slider-arrow.next {
            right: -20px;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            padding: 5rem 0;
            text-align: center;
            margin: 4rem 0 0;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-cta {
            background: #fff;
            color: #6366f1;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #4f46e5;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem 2rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .slider-arrow {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Category Navigation -->
    <div class="category-nav-modern">
        <div class="container">
            <div class="d-flex justify-content-center flex-wrap">
                <a href="{{ route('home') }}" class="category-link active">Beranda</a>
                <a href="#layanan" class="category-link">Layanan Kami</a>
                <a href="#portfolio" class="category-link">Portfolio</a>
                <a href="#testimonial" class="category-link">Testimonial</a>
                <a href="#kontak" class="category-link">Kontak</a>
            </div>
        </div>
    </div>

    <!-- Hero Banner dengan Lottie -->
    <section class="hero-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="wow fadeInUp" data-wow-delay=".2s">Jasa Sablon Profesional untuk Semua Kebutuhan Anda
                        </h1>
                        <p class="wow fadeInUp" data-wow-delay=".4s">Layanan sablon berkualitas tinggi dengan berbagai
                            teknik
                            modern. DTF, Manual, Polyflex, dan Sublim dengan hasil terbaik dan harga terjangkau.</p>
                        <a href="#layanan" class="btn-hero wow fadeInUp" data-wow-delay=".6s">Lihat Layanan Kami</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{-- <div data-wow-delay=".2s" id="lottieHero"></div> --}}
                    <dotlottie-wc class="lottie-hero wow fadeInRight"
                        src="https://lottie.host/b9e2573e-a873-4be6-9c77-dcd667447ee9/rMQdTVTLe0.lottie"
                        style="width: 300px;height: 300px" autoplay loop></dotlottie-wc>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay=".2s">Layanan Sablon Kami</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">Berbagai teknik sablon profesional untuk memenuhi kebutuhan
                    Anda</p>
            </div>
            <div class="row">
                @foreach ($jenisSablons as $index => $jenis)
                    <div class="col-lg-3 col-md-6">
                        <div class="service-card wow fadeInUp" data-wow-delay="{{ 0.2 + $index * 0.1 }}s"
                            onclick="showServiceModal({{ $jenis->id }})">
                            <div class="service-icon">
                                <i class="lni lni-brush"></i>
                            </div>
                            <h4>{{ $jenis->nama }}</h4>
                            <p>{{ Str::limit($jenis->deskripsi, 100) }}</p>
                            <small style="color: #6366F1; font-weight: 600;">{{ $jenis->produks_count }} Varian
                                Tersedia</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Portfolio Section dengan Animasi -->
    <section id="portfolio" class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay=".2s">Portfolio Karya Kami</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">Hasil sablon yang telah kami kerjakan dengan kualitas terbaik
                </p>
            </div>
            <div class="row">
                @foreach ($portfolios as $index => $portfolio)
                    <div class="col-lg-3 col-md-6">
                        <div class="portfolio-card wow fadeInUp" data-wow-delay="{{ 0.2 + $index * 0.1 }}s">
                            <img src="{{ $portfolio['image'] }}" alt="{{ $portfolio['title'] }}" class="portfolio-img">
                            <div class="portfolio-overlay">
                                <span class="portfolio-category">{{ $portfolio['category'] }}</span>
                                <h5>{{ $portfolio['title'] }}</h5>
                                <p>{{ $portfolio['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonial Slider Section -->
    <section id="testimonial" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay=".2s">Testimoni Pelanggan</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">Apa kata mereka yang telah menggunakan layanan kami</p>
            </div>

            <div class="testimonial-slider position-relative">
                <div class="slider-arrow prev" onclick="moveSlide(-1)">
                    <i class="lni lni-chevron-left"></i>
                </div>

                <div class="testimonial-track" id="testimonialTrack">
                    @foreach ($testimonials as $index => $testimonial)
                        <div class="testimonial-slide">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="testimonial-card">
                                        <div class="testimonial-header">
                                            <div class="testimonial-avatar">
                                                {{ substr($testimonial['name'], 0, 1) }}
                                            </div>
                                            <div class="testimonial-info">
                                                <h5>{{ $testimonial['name'] }}</h5>
                                                <div class="testimonial-rating">
                                                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                                                        <i class="lni lni-star-filled"></i>
                                                    @endfor
                                                </div>
                                                <small style="color: #94a3b8;">{{ $testimonial['date'] }}</small>
                                            </div>
                                        </div>
                                        <p class="testimonial-text">"{{ $testimonial['text'] }}"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="slider-arrow next" onclick="moveSlide(1)">
                    <i class="lni lni-chevron-right"></i>
                </div>
            </div>

            <div class="slider-dots" id="sliderDots"></div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-delay=".2s">Siap Memesan Sablon?</h2>
            <p class="wow fadeInUp" data-wow-delay=".4s">Dapatkan hasil sablon berkualitas dengan harga terjangkau</p>
            @auth
                <a href="{{ route('customer.order.create') }}" class="btn-cta wow fadeInUp" data-wow-delay=".6s">Buat
                    Pesanan
                    Sekarang</a>
            @else
                <a href="{{ route('login') }}" class="btn-cta wow fadeInUp" data-wow-delay=".6s">Login untuk Memesan</a>
            @endauth
        </div>
    </section>

    <!-- Modal Detail Layanan -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalTitle">Detail Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="serviceModalBody">
                    Loading...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        // Load Lottie Animation untuk Hero
        // INSTRUKSI: Ganti URL di bawah dengan URL JSON dari lottiefiles.com
        // Cara: 1. Buka lottiefiles.com, 2. Cari dengan keyword "printing press animation", 
        //       3. Pilih animasi, 4. Klik "Lottie Animation URL", 5. Copy paste ke sini
        const lottieUrl =
            'https://lottie.host/b9e2573e-a873-4be6-9c77-dcd667447ee9/rMQdTVTLe0.lottie'; // Contoh: 'https://lottie.host/xxxxx.json'

        if (lottieUrl !== 'https://lottie.host/b9e2573e-a873-4be6-9c77-dcd667447ee9/rMQdTVTLe0.lottie') {
            lottie.loadAnimation({
                container: document.getElementById('lottieHero'),
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: lottieUrl
            });
        }

        // Testimonial Slider
        let currentSlide = 0;
        const totalSlides = {{ count($testimonials) }};
        const track = document.getElementById('testimonialTrack');
        const dotsContainer = document.getElementById('sliderDots');

        // Create dots
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
            dot.onclick = () => goToSlide(i);
            dotsContainer.appendChild(dot);
        }

        function moveSlide(direction) {
            currentSlide += direction;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            if (currentSlide >= totalSlides) currentSlide = 0;
            updateSlider();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
        }

        function updateSlider() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;

            // Update dots
            const dots = document.querySelectorAll('.slider-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Auto slide setiap 5 detik
        setInterval(() => {
            moveSlide(1);
        }, 5000);

        // Service Modal Function
        function showServiceModal(jenisId) {
            var url = "{{ route('api.jenis-sablon', ['id' => 'JENIS_ID']) }}".replace('JENIS_ID', encodeURIComponent(
                jenisId));

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('serviceModalTitle').textContent = data.nama || data.title || '';

                    var bodyHtml = `
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 style="font-weight: 700; color: #1e293b;">Deskripsi</h6>
                            <p style="color: #64748b;">${data.deskripsi ?? ''}</p>
                        </div>
                        <div class="col-12">
                            <h6 style="font-weight: 700; color: #1e293b;">Pilihan Layanan</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead style="background: #f8fafc;">
                                        <tr>
                                            <th>Ukuran</th>
                                            <th>Regular</th>
                                            <th>Express</th>
                                        </tr>
                                    </thead>
                                    <tbody id="priceTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <a id="orderNowBtn" href="{{ route('customer.order.create') }}?jenis=${jenisId}" 
                               class="btn btn-primary" style="border-radius: 50px; padding: 0.75rem 2rem;">
                               Pesan Sekarang
                            </a>
                        </div>
                    </div>
                `;

                    document.getElementById('serviceModalBody').innerHTML = bodyHtml;

                    var tbody = document.getElementById('priceTable');
                    if (tbody && Array.isArray(data.priceTable)) {
                        var rows = '';
                        data.priceTable.forEach(item => {
                            rows += `
                            <tr>
                                <td>${item.ukuran ?? ''}</td>
                                <td style="color: #6366f1; font-weight: 600;">${item.regular ?? ''}</td>
                                <td style="color: #f59e0b; font-weight: 600;">${item.express ?? ''}</td>
                            </tr>
                        `;
                        });
                        tbody.innerHTML = rows;
                    } else {
                        if (tbody) tbody.innerHTML =
                            '<tr><td colspan="3" class="text-center">Tidak ada data harga</td></tr>';
                    }

                    var myModal = new bootstrap.Modal(document.getElementById('serviceModal'));
                    myModal.show();
                })
                .catch(err => {
                    console.error('Error fetching service detail:', err);
                });
        }

        // Initialize WOW.js
        new WOW().init();
    </script>
@endpush
