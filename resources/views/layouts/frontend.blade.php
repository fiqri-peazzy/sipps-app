<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ config('app.name') }} - @yield('title', 'Jasa Sablon Berkualitas')</title>
    <meta name="description"
        content="NClothing - Jasa Sablon Profesional dengan berbagai pilihan teknik sablon DTF, Manual, Polyflex, dan Sublim" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('frontend/assets/img/logo/logo.svg') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap-5.0.0-alpha-2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}" />
    <style>
        /* Navbar Modern */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-modern.scrolled {
            padding: 0.8rem 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .logo-wrapper {
            position: relative;
            overflow: hidden;
        }

        .logo-wrapper img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logo-wrapper:hover img {
            transform: scale(1.05);
        }

        /* Search Bar Modern */
        .search-modern {
            position: relative;
            flex: 1;
            max-width: 550px;
            margin: 0 2rem;
        }

        .search-modern input {
            width: 100%;
            padding: 0.75rem 3rem 0.75rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 50px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .search-modern input:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-modern .search-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.2rem;
        }

        /* Icon Navigation */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .icon-btn {
            position: relative;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f3f4f6;
            color: #374151;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .icon-btn:hover {
            background: #6366f1;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .icon-btn i {
            font-size: 1.3rem;
        }

        .badge-notification {
            position: absolute;
            top: -4px;
            right: -4px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.45rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Button Login Modern */
        .btn-login-modern {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            padding: 0.65rem 1.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login-modern:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        /* User Avatar Modern */
        .user-profile {
            position: relative;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .avatar-circle:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .dropdown-menu-modern {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.75rem;
            margin-top: 0.75rem;
            min-width: 200px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu-modern .dropdown-item {
            padding: 0.65rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .dropdown-menu-modern .dropdown-item:hover {
            background: #f3f4f6;
            color: #6366f1;
            transform: translateX(4px);
        }

        .dropdown-menu-modern .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e5e7eb;
        }

        /* Category Navigation Modern */
        .category-nav-modern {
            background: #fff;
            padding: 0.5rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            /* margin-top: 40px; */
        }

        .category-link {
            color: #6b7280;
            text-decoration: none;
            padding: 0.65rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .category-link:hover {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: #6366f1;
            transform: translateY(-1px);
        }

        .category-link.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Footer Modern */
        .footer-modern {
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            color: #94a3b8;
            padding: 4rem 0 2rem;
            margin-top: 6rem;
            position: relative;
            overflow: hidden;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
        }

        .footer-title {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 2px;
        }

        .footer-link-modern {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            position: relative;
            padding-left: 1rem;
        }

        .footer-link-modern::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 2px;
            background: #6366f1;
            transition: width 0.3s ease;
        }

        .footer-link-modern:hover {
            color: #6366f1;
            padding-left: 1.5rem;
        }

        .footer-link-modern:hover::before {
            width: 12px;
        }

        .social-modern {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .social-icon-modern {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon-modern:hover {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            transform: translateY(-4px) rotate(360deg);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .footer-bottom {
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-modern {
                display: none;
            }

            .nav-actions {
                gap: 1rem;
            }

            .icon-btn {
                width: 38px;
                height: 38px;
            }
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>

<body>
    <!-- Navbar Modern -->
    <nav class="navbar-modern">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Logo -->
                <a href="{{ route('home') }}" style="text-decoration: none" class="logo-wrapper">
                    {{-- <img src="{{ asset('frontend/assets/img/logo/logo.svg') }}" alt="NClothing Logo" />
                     --}}
                    <h3 class="text-primary">NClothing</h3>
                </a>

                <!-- Search Bar -->
                <div class="search-modern d-none d-lg-block">
                    <input type="text" placeholder="Cari jenis layanan sablon...">
                    <i class="lni lni-search search-icon"></i>
                </div>

                <!-- Navigation Actions -->
                <div class="nav-actions">
                    @auth
                        <a href="{{ route('customer.orders.index') }}" class="icon-btn">
                            <i class="lni lni-cart"></i>
                            <span class="badge-notification">3</span>
                        </a>

                        <div class="user-profile dropdown">
                            <button type="button" class="avatar-circle" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </button>
                            <ul class="dropdown-menu    ">
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                        <i class="lni lni-dashboard me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                        <i class="lni lni-user me-2"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="lni lni-exit me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-modern">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer Modern -->
    <footer class="footer-modern">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <img src="{{ asset('frontend/assets/img/logo/logo.svg') }}" alt="NClothing"
                        style="height: 50px; margin-bottom: 1.5rem;">
                    <p style="line-height: 1.8;">
                        NClothing menyediakan jasa sablon berkualitas dengan berbagai teknik modern untuk hasil terbaik
                        pesanan Anda.
                    </p>
                    <div class="social-modern">
                        <a href="#" class="social-icon-modern">
                            <i class="lni lni-facebook-filled"></i>
                        </a>
                        <a href="#" class="social-icon-modern">
                            <i class="lni lni-instagram-filled"></i>
                        </a>
                        <a href="#" class="social-icon-modern">
                            <i class="lni lni-whatsapp"></i>
                        </a>
                        <a href="#" class="social-icon-modern">
                            <i class="lni lni-twitter-filled"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Menu</h5>
                    <a href="{{ route('home') }}" class="footer-link-modern">Beranda</a>
                    <a href="#layanan" class="footer-link-modern">Layanan</a>
                    <a href="#portfolio" class="footer-link-modern">Portfolio</a>
                    <a href="#kontak" class="footer-link-modern">Kontak</a>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Layanan Sablon</h5>
                    <a href="#layanan" class="footer-link-modern">Sablon DTF</a>
                    <a href="#layanan" class="footer-link-modern">Sablon Manual</a>
                    <a href="#layanan" class="footer-link-modern">Polyflex</a>
                    <a href="#layanan" class="footer-link-modern">Sublim</a>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Hubungi Kami</h5>
                    <div style="line-height: 2;">
                        <p><i class="lni lni-phone me-2"></i> +62 812-3456-7890</p>
                        <p><i class="lni lni-envelope me-2"></i> info@nclothing.com</p>
                        <p><i class="lni lni-map-marker me-2"></i> Manado, Sulawesi Utara</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} NClothing. All rights reserved. Made with ❤️ in Manado
                </p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.5.0.0.alpha-2-min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-modern');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>

</html>
