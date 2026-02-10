<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title', 'Jasa Sablon Berkualitas')</title>
    <meta name="description"
        content="SIPPS - Sistem Informasi Penjadwalan Produksi Sablon Profesional dengan berbagai pilihan teknik sablon DTF, Manual, Polyflex, dan Sublim" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('backend/assets/images/sipps.png') }}" type="image/png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}" />

    @livewireStyles
    @stack('styles')
</head>

<body class="bg-gray-50 text-slate-900 font-sans">

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="main-header">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="glass-morphism rounded-2xl md:rounded-full px-6 py-2 flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 group transition-transform duration-300 hover:scale-102">
                    <img src="{{ asset('backend/assets/images/sipps.png') }}" alt="SIPPS Logo"
                        class="h-10 w-auto object-contain" />
                    <span
                        class="text-xl font-extrabold tracking-tight bg-linear-to-r from-primary to-secondary bg-clip-text text-transparent">SIPPS</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="nav-link-modern {{ request()->routeIs('home') ? 'text-primary after:w-full' : '' }}">Beranda</a>
                    <a href="{{ route('layanan') }}"
                        class="nav-link-modern {{ request()->routeIs('layanan') ? 'text-primary after:w-full' : '' }}">Layanan</a>
                    <a href="{{ route('portfolio') }}"
                        class="nav-link-modern {{ request()->routeIs('portfolio') ? 'text-primary after:w-full' : '' }}">Portfolio</a>
                    <a href="{{ route('home') }}#faq" class="nav-link-modern">FAQ</a>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3">
                            <a href="{{ route('customer.dashboard') }}"
                                class="hidden sm:flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-primary transition-colors">
                                <i class="lni lni-dashboard"></i> Dashboard
                            </a>
                            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                            <div class="relative group" id="user-dropdown-container">
                                <button type="button"
                                    class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition-colors">
                                    <div
                                        class="h-9 w-9 rounded-full bg-linear-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <i class="lni lni-chevron-down text-xs text-slate-400"></i>
                                </button>
                                <!-- Dropdown Menu -->
                                <div
                                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right py-2">
                                    <a href="{{ route('customer.profile') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">
                                        <i class="lni lni-user"></i> Profil Saya
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">
                                        <i class="lni lni-cart"></i> Pesanan Saya
                                    </a>
                                    <hr class="my-1 border-slate-100">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="lni lni-exit"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-slate-700 hover:text-primary px-4 py-2 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="btn-premium px-6! py-2! text-sm shadow-md">Daftar</a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button class="md:hidden p-2 text-slate-600" id="mobile-menu-btn">
                        <i class="lni lni-menu text-2xl"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden hidden absolute top-full left-0 right-0 mt-2 px-4" id="mobile-menu">
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 flex flex-col gap-4">
                <a href="{{ route('home') }}"
                    class="text-lg font-semibold text-slate-700 hover:text-primary transition-colors">Beranda</a>
                <a href="{{ route('home') }}#layanan"
                    class="text-lg font-semibold text-slate-700 hover:text-primary transition-colors">Layanan</a>
                <a href="{{ route('home') }}#portfolio"
                    class="text-lg font-semibold text-slate-700 hover:text-primary transition-colors">Portfolio</a>
                @guest
                    <hr class="border-slate-100">
                    <a href="{{ route('login') }}"
                        class="text-lg font-semibold text-slate-700 hover:text-primary transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-premium w-full text-center">Daftar</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen pt-24">
        @yield('content')
    </main>

    <!-- Footer Modern -->
    <footer class="bg-slate-900 text-slate-400 mt-20 relative overflow-hidden">
        <!-- Decoration Blowers -->
        <div class="absolute -top-24 -left-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-20 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                <!-- Brand Info -->
                <div class="space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('backend/assets/images/sipps.png') }}" alt="SIPPS Logo"
                            class="h-12 w-auto brightness-0 invert" />
                        <span class="text-2xl font-black tracking-tight text-white">SIPPS</span>
                    </a>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        Sistem Informasi Penjadwalan Produksi Sablon (SIPPS) menghadirkan kualitas terbaik dengan
                        efisiensi penjadwalan cerdas untuk kebutuhan sandang Anda.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#"
                            class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-primary hover:scale-110 transition-all">
                            <i class="lni lni-facebook-filled"></i>
                        </a>
                        <a href="#"
                            class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-accent hover:scale-110 transition-all">
                            <i class="lni lni-instagram-filled"></i>
                        </a>
                        <a href="#"
                            class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-green-500 hover:scale-110 transition-all">
                            <i class="lni lni-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h5 class="text-white font-bold text-lg mb-8 relative inline-block">
                        Navigasi
                        <div class="absolute -bottom-2 left-0 w-1/2 h-1 bg-primary rounded-full"></div>
                    </h5>
                    <ul class="space-y-4 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Layanan
                                Kami</a></li>
                        <li><a href="{{ route('home') }}#portfolio" class="hover:text-white transition-colors">Galeri
                                Karya</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-white transition-colors">Pertanyaan
                                Umum</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h5 class="text-white font-bold text-lg mb-8 relative inline-block">
                        Layanan
                        <div class="absolute -bottom-2 left-0 w-1/2 h-1 bg-secondary rounded-full"></div>
                    </h5>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Sablon DTF Modern</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sablon Manual High-End</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors">Sublimation Full-Print</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors">Polyflex Precision</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h5 class="text-white font-bold text-lg mb-8 relative inline-block">
                        Kontak Kami
                        <div class="absolute -bottom-2 left-0 w-1/2 h-1 bg-accent rounded-full"></div>
                    </h5>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="lni lni-map-marker text-primary text-xl"></i>
                            <span>Jl. Jenderal Sudirman No. 123, Manado, Sulawesi Utara</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="lni lni-phone text-primary text-xl"></i>
                            <span>+62 812 3456 7890</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="lni lni-envelope text-primary text-xl"></i>
                            <span>hello@sipps-app.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-20 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium uppercase tracking-widest">
                <p>&copy; {{ date('Y') }} SIPPS PRODUCTION. SELURUH HAK CIPTA DILINDUNGI.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-white transition-colors">KEBIJAKAN PRIVASI</a>
                    <a href="#" class="hover:text-white transition-colors">SYARAT & KETENTUAN</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

    <script>
        // Header Scroll Effect
        window.addEventListener('scroll', function () {
            const header = document.getElementById('main-header');
            if (window.scrollY > 20) {
                header.classList.add('py-2');
                header.querySelector('nav').classList.remove('py-4');
                header.querySelector('nav').classList.add('py-2');
            } else {
                header.classList.remove('py-2');
                header.querySelector('nav').classList.remove('py-2');
                header.querySelector('nav').classList.add('py-4');
            }
        });

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const icon = menuBtn.querySelector('i');
            icon.classList.toggle('lni-menu');
            icon.classList.toggle('lni-close');
        });

        // Initialize WOW
        new WOW().init();
    </script>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>