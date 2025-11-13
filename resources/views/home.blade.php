@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')
    <style>
        .hero-banner {
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            padding: 80px 0;
            margin-bottom: 50px;
        }

        .hero-content h1 {
            color: white;
            font-weight: 800;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-hero {
            background: white;
            color: #6366F1;
            padding: 12px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-weight: 700;
            font-size: 36px;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .section-title p {
            color: #64748b;
            font-size: 16px;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            cursor: pointer;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.2);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .service-icon i {
            font-size: 40px;
            color: white;
        }

        .service-card h4 {
            font-weight: 700;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .service-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .portfolio-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .portfolio-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .portfolio-card:hover .portfolio-img {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
            padding: 25px;
            color: white;
        }

        .portfolio-category {
            background: #F97316;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .portfolio-overlay h5 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .portfolio-overlay p {
            font-size: 13px;
            margin: 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 24px;
            margin-right: 15px;
        }

        .testimonial-info h5 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
        }

        .testimonial-rating {
            color: #fbbf24;
        }

        .testimonial-text {
            color: #64748b;
            line-height: 1.8;
            font-style: italic;
        }

        .cta-section {
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            padding: 80px 0;
            margin-top: 80px;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-weight: 700;
            font-size: 42px;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-cta {
            background: white;
            color: #6366F1;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            font-size: 18px;
            transition: transform 0.3s;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
    </style>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="wow fadeInUp" data-wow-delay=".2s">Jasa Sablon Profesional untuk Semua Kebutuhan Anda</h1>
                        <p class="wow fadeInUp" data-wow-delay=".4s">Layanan sablon berkualitas tinggi dengan berbagai teknik
                            modern. DTF, Manual, Polyflex, dan Sublim dengan hasil terbaik dan harga terjangkau.</p>
                        <a href="#layanan" class="btn-hero wow fadeInUp" data-wow-delay=".6s">Lihat Layanan Kami</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    {{-- <img src="https://images.pexels.com/photos/6214452/pexels-photo-6214452.jpeg" alt="Sablon NNClothing"
                        class="img-fluid wow fadeInRight" data-wow-delay=".2s"> --}}
                    <img src="{{ asset('backend/assets/images/a124572d-dcd5-407d-96b7-d315f0876e18.jpg') }}"
                        alt="Sablon NNClothing" class="img-fluid wow fadeInRight" data-wow-delay=".2s">
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

    <!-- Portfolio Section -->
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

    <!-- Testimonial Section -->
    <section id="testimonial" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay=".2s">Testimoni Pelanggan</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">Apa kata mereka yang telah menggunakan layanan kami</p>
            </div>
            <div class="row">
                @foreach ($testimonials as $index => $testimonial)
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card wow fadeInUp" data-wow-delay="{{ 0.2 + $index * 0.1 }}s">
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
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-delay=".2s">Siap Memesan Sablon?</h2>
            <p class="wow fadeInUp" data-wow-delay=".4s">Dapatkan hasil sablon berkualitas dengan harga terjangkau</p>
            @auth
                <a href="{{ route('customer.order.create') }}" class="btn-cta wow fadeInUp" data-wow-delay=".6s">Buat Pesanan
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
    <script>
        function showServiceModal(jenisId) {
            var url = "{{ route('api.jenis-sablon', ['id' => 'JENIS_ID']) }}".replace('JENIS_ID', encodeURIComponent(
                jenisId));

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // judul + deskripsi
                    document.getElementById('serviceModalTitle').textContent = data.nama || data.title || '';
                    // build HTML utama dan tabel rows
                    var bodyHtml = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6>Deskripsi</h6>
                        <p>${data.deskripsi ?? ''}</p>
                    </div>
                    <div class="col-12">
                        <h6>Pilihan Layanan</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ukuran</th>
                                        <th>Regular</th>
                                        <th>Express</th>
                                    </tr>
                                </thead>
                                <tbody id="priceTable">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <a id="orderNowBtn" href="{{ route('customer.order.create') }}?jenis=${jenisId}" class="btn btn-primary">Pesan Sekarang</a>
                    </div>
                </div>
            `;
                    document.getElementById('serviceModalBody').innerHTML = bodyHtml;

                    // populate priceTable
                    var tbody = document.getElementById('priceTable');
                    if (tbody && Array.isArray(data.priceTable)) {
                        var rows = '';
                        data.priceTable.forEach(item => {
                            rows += `
                        <tr>
                            <td>${item.ukuran ?? ''}</td>
                            <td>${item.regular ?? ''}</td>
                            <td>${item.express ?? ''}</td>
                        </tr>
                    `;
                        });
                        tbody.innerHTML = rows;
                    } else {
                        if (tbody) tbody.innerHTML =
                            '<tr><td colspan="3" class="text-center">Tidak ada data harga</td></tr>';
                    }

                    // pastikan tombol Pesan mengarah ke url dengan query yang benar (escape)
                    var orderBtn = document.getElementById('orderNowBtn');
                    if (orderBtn) {
                        var href = "{{ route('customer.order.create') }}" + '?jenis=' + encodeURIComponent(jenisId);
                        orderBtn.setAttribute('href', href);
                    }

                    var myModal = new bootstrap.Modal(document.getElementById('serviceModal'));
                    myModal.show();
                })
                .catch(err => {
                    console.error('Error fetching service detail:', err);
                    // optional: tampilkan pesan ke user
                });
        }
    </script>
@endpush
