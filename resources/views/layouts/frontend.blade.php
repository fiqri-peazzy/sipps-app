<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ config('app.name') }} - @yield('title', 'Jasa Sablon Berkualitas')</title>
    <meta name="description"
        content="NNClothing - Jasa Sablon Profesional dengan berbagai pilihan teknik sablon DTF, Manual, Polyflex, dan Sublim" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('frontend/assets/img/logo/logo-nn.png') }}" type="image/png" />

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lindy-uikit.css') }}" />
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #F97316;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gradient: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar Styling */
        .header-2 {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            max-height: 60px;
        }

        .navbar-nav .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Button Styling */
        .button,
        .btn-primary {
            background: var(--gradient);
            border: none;
            color: white !important;
            font-weight: 600;
            padding: 12px 30px;
            transition: all 0.3s;
        }

        .button:hover,
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        }

        .button-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color) !important;
        }

        .button-outline:hover {
            background: var(--primary-color);
            color: white !important;
        }

        .btn-login {
            background: var(--gradient);
            color: white !important;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            margin-left: 15px;
        }

        .btn-dashboard {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color) !important;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 15px;
        }

        /* Hero Section */
        .hero-section {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        }

        .hero-content-wrapper h4 {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 18px;
        }

        .hero-content-wrapper h2 {
            color: var(--dark-color);
            font-weight: 800;
            font-size: 42px;
            line-height: 1.3;
        }

        /* Feature Section */
        .single-feature {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
            margin-bottom: 30px;
        }

        .single-feature:hover {
            transform: translateY(-10px);
        }

        .single-feature .icon {
            width: 70px;
            height: 70px;
            background: var(--gradient);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single-feature .icon i {
            color: white;
            font-size: 32px;
        }

        /* Pricing */
        .single-pricing {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .single-pricing:hover {
            transform: translateY(-10px);
        }

        .single-pricing.active {
            border: 3px solid var(--primary-color);
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
        }

        .footer .links li a {
            color: rgba(255, 255, 255, 0.8);
        }

        .footer .links li a:hover {
            color: var(--secondary-color);
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="preloader">
        <div class="loader">
            <div class="spinner">
                <div class="spinner-container">
                    <div class="spinner-rotator">
                        <div class="spinner-left">
                            <div class="spinner-circle"></div>
                        </div>
                        <div class="spinner-right">
                            <div class="spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="home" class="hero-section-wrapper-2">
        <header class="header header-2">
            <div class="navbar-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <nav class="navbar navbar-expand-lg">
                                <a class="navbar-brand" href="{{ route('home') }}">
                                    <img src="{{ asset('frontend/assets/img/logo/logo-nn.png') }}"
                                        alt="NNClothing Logo" />
                                </a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="toggler-icon"></span>
                                    <span class="toggler-icon"></span>
                                    <span class="toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                    <ul id="nav2" class="navbar-nav ml-auto">
                                        <li class="nav-item">
                                            <a class="page-scroll active" href="#home">Beranda</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="page-scroll" href="#layanan">Layanan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="page-scroll" href="#harga">Harga</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="page-scroll" href="#tentang">Tentang</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="page-scroll" href="#kontak">Kontak</a>
                                        </li>
                                    </ul>
                                    @auth
                                        <a href="{{ route('dashboard') }}"
                                            class="button button-sm radius-10 d-none d-lg-flex">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="button button-sm radius-10 d-none d-lg-flex">Login</a>
                                    @endauth
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </section>

    <footer class="footer footer-style-1">
        <div class="container">
            <div class="widget-wrapper">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
                            <div class="logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('frontend/assets/img/logo/logo-nn.png') }}" alt="NNClothing">
                                </a>
                            </div>
                            <p class="desc">NNClothing menyediakan jasa sablon berkualitas dengan berbagai teknik
                                modern. Percayakan kebutuhan sablon Anda kepada kami.</p>
                            <ul class="socials">
                                <li> <a href="#"> <i class="lni lni-facebook-filled"></i> </a> </li>
                                <li> <a href="#"> <i class="lni lni-instagram-filled"></i> </a> </li>
                                <li> <a href="#"> <i class="lni lni-whatsapp"></i> </a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".3s">
                            <h6>Menu</h6>
                            <ul class="links">
                                <li> <a href="#home">Beranda</a> </li>
                                <li> <a href="#layanan">Layanan</a> </li>
                                <li> <a href="#harga">Harga</a> </li>
                                <li> <a href="#kontak">Kontak</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".4s">
                            <h6>Layanan Kami</h6>
                            <ul class="links">
                                <li> <a href="#layanan">Sablon DTF</a> </li>
                                <li> <a href="#layanan">Sablon Manual</a> </li>
                                <li> <a href="#layanan">Polyflex</a> </li>
                                <li> <a href="#layanan">Sublim</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".5s">
                            <h6>Hubungi Kami</h6>
                            <ul class="links">
                                <li> <i class="lni lni-phone"></i> +62 812-3456-7890</li>
                                <li> <i class="lni lni-envelope"></i> info@nnclothing.com</li>
                                <li> <i class="lni lni-map-marker"></i> Manado, Sulawesi Utara</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
                <p>&copy; {{ date('Y') }} NNClothing. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <a href="#" class="scroll-top"> <i class="lni lni-chevron-up"></i> </a>

    <script src="{{ asset('frontend/assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/count-up.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
