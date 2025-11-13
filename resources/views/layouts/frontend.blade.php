<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ config('app.name') }} - @yield('title', 'Jasa Sablon Berkualitas')</title>
    <meta name="description"
        content="NNClothing - Jasa Sablon Profesional dengan berbagai pilihan teknik sablon DTF, Manual, Polyflex, dan Sublim" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('frontend/assets/img/logo/logo-nn.png') }}" type="image/png" />

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}" />
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #F97316;
            --success-color: #22c55e;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-color: #64748b;
            --gradient: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            background: var(--light-color);
        }

        /* Navbar E-commerce Style */
        .navbar-ecommerce {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-brand img {
            max-height: 50px;
        }

        .nav-search {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
        }

        .nav-search input {
            width: 100%;
            padding: 10px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s;
        }

        .nav-search input:focus {
            border-color: var(--primary-color);
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-icon-item {
            position: relative;
            cursor: pointer;
            color: var(--dark-color);
            font-size: 24px;
            transition: color 0.3s;
        }

        .nav-icon-item:hover {
            color: var(--primary-color);
        }

        .nav-icon-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-login {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 8px 25px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }

        .user-dropdown {
            position: relative;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
        }

        /* Category Nav */
        .category-nav {
            background: white;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 0;
        }

        .category-nav ul {
            display: flex;
            gap: 30px;
            list-style: none;
            margin: 0;
            padding: 0;
            justify-content: center;
        }

        .category-nav li a {
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .category-nav li a:hover,
        .category-nav li a.active {
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 300px);
            padding-top: 30px;
        }

        /* Footer */
        .footer-ecommerce {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 60px 0 30px;
            margin-top: 80px;
        }

        .footer-widget h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
        }

        .footer-socials {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .footer-socials a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-socials a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-search {
                display: none;
            }

            .category-nav ul {
                flex-wrap: wrap;
                gap: 15px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar-ecommerce">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <img src="{{ asset('frontend/assets/img/logo/logo-nn.png') }}" alt="NNClothing Logo" />
                    </a>
                </div>
                <div class="col">
                    <div class="nav-search">
                        <input type="text" placeholder="Cari jenis layanan sablon...">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="nav-icons">
                        @auth
                            <a href="{{ route('customer.orders.index') }}" class="nav-icon-item" title="Pesanan Saya">
                                <i class="lni lni-cart"></i>
                                <span class="nav-icon-badge">3</span>
                            </a>
                            <div class="user-dropdown">
                                <div class="user-avatar" onclick="toggleUserMenu()">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="dropdown-menu" id="userMenu" style="display: none;">
                                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                                    <a href="{{ route('customer.profile') }}">Profile</a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Category Nav -->
    <div class="category-nav">
        <div class="container">
            <ul>
                <li><a href="{{ route('home') }}" class="active">Beranda</a></li>
                <li><a href="#layanan">Layanan Kami</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#testimonial">Testimonial</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer-ecommerce">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <img src="{{ asset('frontend/assets/img/logo/logo-nn.png') }}" alt="NNClothing"
                            style="max-height: 50px; margin-bottom: 20px;">
                        <p style="color: rgba(255,255,255,0.7); margin-top: 15px;">NNClothing menyediakan jasa sablon
                            berkualitas dengan berbagai teknik modern untuk hasil terbaik pesanan Anda.</p>
                        <div class="footer-socials">
                            <a href="#"><i class="lni lni-facebook-filled"></i></a>
                            <a href="#"><i class="lni lni-instagram-filled"></i></a>
                            <a href="#"><i class="lni lni-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5>Menu</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Beranda</a></li>
                            <li><a href="#layanan">Layanan</a></li>
                            <li><a href="#portfolio">Portfolio</a></li>
                            <li><a href="#kontak">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5>Layanan Sablon</h5>
                        <ul class="footer-links">
                            <li><a href="#layanan">Sablon DTF</a></li>
                            <li><a href="#layanan">Sablon Manual</a></li>
                            <li><a href="#layanan">Polyflex</a></li>
                            <li><a href="#layanan">Sublim</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h5>Hubungi Kami</h5>
                        <ul class="footer-links">
                            <li><i class="lni lni-phone"></i> +62 812-3456-7890</li>
                            <li><i class="lni lni-envelope"></i> info@nnclothing.com</li>
                            <li><i class="lni lni-map-marker"></i> Manado, Sulawesi Utara</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} NNClothing. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('frontend/assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        document.addEventListener('click', function(e) {
            const userDropdown = document.querySelector('.user-dropdown');
            if (userDropdown && !userDropdown.contains(e.target)) {
                document.getElementById('userMenu').style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
