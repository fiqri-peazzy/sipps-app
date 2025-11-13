@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section hero-style-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content-wrapper">
                        <h4 class="wow fadeInUp" data-wow-delay=".2s">Selamat Datang di</h4>
                        <h2 class="mb-30 wow fadeInUp" data-wow-delay=".4s">NNClothing Sablon Profesional</h2>
                        <p class="mb-50 wow fadeInUp" data-wow-delay=".6s" style="font-size: 18px; color: #64748b;">Layanan
                            sablon berkualitas tinggi dengan berbagai teknik modern. DTF, Manual, Polyflex, dan Sublim untuk
                            hasil terbaik pesanan Anda.</p>
                        <div class="buttons">
                            <a href="#harga" class="button button-lg radius-10 wow fadeInUp" data-wow-delay=".7s">Lihat
                                Harga</a>
                            <a href="#layanan" class="button button-lg button-outline radius-10 wow fadeInUp ms-3"
                                data-wow-delay=".8s">Layanan Kami</a>
                        </div>
                        <div class="mt-4 wow fadeInUp" data-wow-delay=".9s">
                            @auth
                                <a href="{{ route('customer.order.create') }}" class="text-primary"
                                    style="font-size: 16px; font-weight: 600;">
                                    <i class="lni lni-shopping-basket"></i> Buat Pesanan Sekarang →
                                </a>
                            @else
                                <p style="color: #64748b; font-size: 14px;">
                                    <i class="lni lni-information"></i> Login untuk mulai memesan
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="{{ asset('frontend/assets/img/hero/sablon-hero.svg') }}" alt="Sablon NNClothing"
                            class="wow fadeInRight" data-wow-delay=".2s">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layanan Section -->
    <section id="layanan" class="feature-section feature-style-2 pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-8 col-md-9">
                            <div class="section-title text-center mb-60">
                                <h3 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Jenis Layanan Sablon Kami</h3>
                                <p class="wow fadeInUp" data-wow-delay=".4s">Kami menyediakan berbagai teknik sablon
                                    profesional untuk memenuhi kebutuhan Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($jenisSablons as $index => $jenis)
                            <div class="col-lg-6 col-md-6">
                                <div class="single-feature wow fadeInUp" data-wow-delay="{{ 0.2 + $index * 0.2 }}s">
                                    <div class="icon">
                                        <i class="lni lni-brush"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="mb-25">{{ $jenis->nama }}</h5>
                                        <p>{{ $jenis->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Harga Section -->
    <section id="harga" class="pricing-section pricing-style-1 bg-white pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-10">
                    <div class="section-title text-center mb-60">
                        <h3 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Paket Layanan Kami</h3>
                        <p class="wow fadeInUp" data-wow-delay=".4s">Pilih paket layanan yang sesuai dengan kebutuhan Anda.
                            Harga transparan dan kualitas terjamin!</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="single-pricing wow fadeInUp" data-wow-delay=".2s">
                        <div class="icon mb-4">
                            <i class="lni lni-timer" style="font-size: 48px; color: #22c55e;"></i>
                        </div>
                        <h6 style="color: #22c55e; font-weight: 600;">Layanan</h6>
                        <h4 style="font-weight: 700;">Regular</h4>
                        <h5 class="mb-3" style="color: #64748b;">Estimasi 2 Hari Kerja</h5>
                        <ul>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #22c55e;"></i>
                                Kualitas Terjamin</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #22c55e;"></i>
                                Harga Terjangkau</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #22c55e;"></i>
                                Cocok untuk pesanan tidak mendesak</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #22c55e;"></i>
                                Gratis Konsultasi Desain</li>
                        </ul>
                        <div class="mt-4 mb-3">
                            <span style="font-size: 14px; color: #64748b;">Mulai dari</span>
                            <h3 style="color: #22c55e; font-weight: 700;">Rp 35.000</h3>
                        </div>
                        <a href="#kontak" class="button radius-30" style="width: 100%;">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="single-pricing active wow fadeInUp" data-wow-delay=".4s">
                        <span class="button button-sm radius-30 popular-badge"
                            style="background: linear-gradient(135deg, #6366F1 0%, #F97316 100%); position: absolute; top: -15px; right: 20px;">Populer</span>
                        <div class="icon mb-4">
                            <i class="lni lni-bolt" style="font-size: 48px; color: #ef4444;"></i>
                        </div>
                        <h6 style="color: #ef4444; font-weight: 600;">Layanan</h6>
                        <h4 style="font-weight: 700;">Express</h4>
                        <h5 class="mb-3" style="color: #64748b;">Estimasi 1 Hari Kerja</h5>
                        <ul>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #ef4444;"></i>
                                Prioritas Tertinggi</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #ef4444;"></i>
                                Pengerjaan Cepat</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #ef4444;"></i>
                                Cocok untuk pesanan mendesak</li>
                            <li style="color: #64748b;"> <i class="lni lni-checkmark-circle" style="color: #ef4444;"></i>
                                Gratis Konsultasi Desain</li>
                        </ul>
                        <div class="mt-4 mb-3">
                            <span style="font-size: 14px; color: #64748b;">Mulai dari</span>
                            <h3 style="color: #ef4444; font-weight: 700;">Rp 52.500</h3>
                        </div>
                        <a href="#kontak" class="button radius-30" style="width: 100%;">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10 text-center">
                    <p class="text-muted wow fadeInUp" data-wow-delay=".6s">
                        *Harga dapat bervariasi tergantung jenis sablon, ukuran, dan tingkat kerumitan desain.
                        <a href="#kontak" class="text-primary">Hubungi kami</a> untuk informasi detail.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="about-section about-style-3 pt-100 pb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image wow fadeInLeft" data-wow-delay=".2s">
                        <img src="{{ asset('frontend/assets/img/about/about-nn.jpg') }}" alt="Tentang NNClothing">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content-wrapper">
                        <div class="section-title mb-40">
                            <h3 class="mb-25 wow fadeInUp" data-wow-delay=".2s">Mengapa Memilih NNClothing?</h3>
                            <p class="wow fadeInUp" data-wow-delay=".4s">
                                NNClothing adalah penyedia jasa sablon profesional yang berpengalaman dengan komitmen
                                memberikan hasil terbaik untuk setiap pesanan. Kami menggunakan teknologi modern dan bahan
                                berkualitas tinggi untuk memastikan kepuasan pelanggan.
                            </p>
                        </div>
                        <div class="counter-up-wrapper mb-40 wow fadeInUp" data-wow-delay=".6s">
                            <div class="single-counter">
                                <h4 class="countup" id="secondo1" cup-end="500" cup-append="+">500+</h4>
                                <h6>Pelanggan Puas</h6>
                            </div>
                            <div class="single-counter">
                                <h4 class="countup" id="secondo2" cup-end="1000" cup-append="+">1000+</h4>
                                <h6>Pesanan Selesai</h6>
                            </div>
                            <div class="single-counter">
                                <h4 class="countup" id="secondo3" cup-end="5" cup-append=" Tahun">5 Tahun</h4>
                                <h6>Pengalaman</h6>
                            </div>
                        </div>
                        <a href="#kontak" class="button button-lg radius-3 wow fadeInUp" data-wow-delay=".7s">Hubungi
                            Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="contact-section contact-style-6 pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <div class="section-title mb-40">
                            <h3 class="mb-15">Hubungi Kami</h3>
                            <p>Punya pertanyaan atau ingin konsultasi? Kirim pesan kepada kami dan tim kami akan segera
                                merespons!</p>
                        </div>
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-input">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" class="form-input"
                                            placeholder="Nama Anda" required>
                                        <i class="lni lni-user"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-input">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-input"
                                            placeholder="Email Anda" required>
                                        <i class="lni lni-envelope"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-input">
                                        <label for="phone">Nomor WhatsApp</label>
                                        <input type="text" id="phone" name="phone" class="form-input"
                                            placeholder="08xx xxxx xxxx" required>
                                        <i class="lni lni-phone"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-input">
                                        <label for="subject">Subjek</label>
                                        <input type="text" id="subject" name="subject" class="form-input"
                                            placeholder="Subjek Pesan" required>
                                        <i class="lni lni-text-format"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="single-input">
                                        <label for="message">Pesan</label>
                                        <textarea name="message" id="message" class="form-input" placeholder="Tulis pesan Anda..." rows="6"
                                            required></textarea>
                                        <i class="lni lni-comments-alt"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-button">
                                        <button type="submit" class="button radius-10">Kirim Pesan <i
                                                class="lni lni-telegram-original"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 order-first order-lg-last">
                    <div class="left-wrapper">
                        <div class="section-title mb-40">
                            <h3 class="mb-15">Informasi Kontak</h3>
                            <p>Jangan ragu untuk menghubungi kami kapan saja. Kami siap membantu kebutuhan sablon Anda!</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-6">
                                <div class="single-item">
                                    <div class="icon">
                                        <i class="lni lni-phone"></i>
                                    </div>
                                    <div class="text">
                                        <p>+62 812-3456-7890</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="single-item">
                                    <div class="icon">
                                        <i class="lni lni-envelope"></i>
                                    </div>
                                    <div class="text">
                                        <p>info@nnclothing.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="single-item">
                                    <div class="icon">
                                        <i class="lni lni-whatsapp"></i>
                                    </div>
                                    <div class="text">
                                        <p>WhatsApp: +62 812-3456-7890</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="single-item">
                                    <div class="icon">
                                        <i class="lni lni-map-marker"></i>
                                    </div>
                                    <div class="text">
                                        <p>Manado, Sulawesi Utara, Indonesia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
