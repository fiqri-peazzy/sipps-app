@extends('layouts.frontend')
@section('title', 'Solusi Sablon Cerdas & Berkualitas')

@push('styles')
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endpush

@section('content')
    <!-- Hero Section with NNCLOTHING Branding -->
    <section class="relative min-h-screen flex items-center overflow-hidden -mt-20 pt-32">
        <!-- Premium Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-primary/20"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary/30 to-transparent"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-secondary/20 rounded-full blur-3xl animate-blob"></div>
        <div
            class="absolute bottom-0 -right-20 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-blob animation-delay-2000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Branding & CTA -->
                <div class="space-y-8 max-w-2xl">
                    <!-- Logo & Brand Badge -->
                    <div class="animate-in fade-in slide-in-from-left-4 duration-700">
                        <x-navbar-logo />
                    </div>



                    <!-- Main Headline -->
                    <h1
                        class="text-3xl md:text-5xl lg:text-5xl font-black leading-[1.1] text-white animate-in fade-in slide-in-from-bottom-4 duration-700">
                        Kustomisasi <br>
                        <span class="bg-linear-to-r from-primary via-blue-400 to-accent bg-clip-text text-transparent">Gaya
                            Anda</span> <br>
                        Tanpa Batas.
                    </h1>

                    <!-- Description -->
                    <p
                        class="text-lg text-white/80 leading-relaxed max-w-xl animate-in fade-in slide-in-from-bottom-6 duration-1000">
                        NNCLOTHING menghadirkan identitas visual terbaik pada setiap helai kain. Didukung teknologi
                        penjadwalan produksi cerdas untuk hasil tepat waktu dan kualitas tanpa kompromi.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-4 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                        @auth
                            <a href="{{ route('customer.order.create') }}"
                                class="btn-premium px-8 py-3 text-lg shadow-xl shadow-primary/30 hover:shadow-2xl hover:shadow-primary/40 transition-all">
                                <i class="lni lni-plus mr-2"></i> Pesan Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="btn-premium px-8 py-3 text-lg shadow-xl shadow-primary/30 hover:shadow-2xl hover:shadow-primary/40 transition-all">
                                <i class="lni lni-rocket mr-2"></i> Mulai Sekarang
                            </a>
                        @endauth
                        <a href="#layanan"
                            class="group flex items-center gap-3 px-6 py-1 font-bold text-white hover:text-primary transition-all bg-white/10 hover:bg-white/20 border border-white/20 rounded-l">
                            Jelajahi Layanan
                            <span
                                class="h-10 w-10 rounded-full border border-white/30 flex items-center justify-center group-hover:border-primary group-hover:bg-primary/20 transition-all">
                                <i class="lni lni-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Right Side - Visual Showcase -->
                <div class="relative lg:block hidden">
                    <div class="absolute inset-0 bg-linear-to-br from-primary/30 to-secondary/20 rounded-full blur-[100px]">
                    </div>

                    <!-- Logo Showcase Card -->
                    <div class="relative z-10 animate-float">
                        <div class="bg-white/10 backdrop-blur border border-white/20 rounded-3xl p-12 space-y-8">
                            <!-- Logo Display -->
                            <div class="flex justify-center">
                                <div
                                    class="h-32 w-32 bg-linear-to-br from-primary to-secondary rounded-2xl flex items-center justify-center shadow-2xl">
                                    <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" class="w-24 h-24">
                                        <defs>
                                            <linearGradient id="heroBrandGrad" x1="0%" y1="0%" x2="100%"
                                                y2="100%">
                                                <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#f3f4f6;stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                        <circle cx="60" cy="60" r="58" fill="url(#heroBrandGrad)" />
                                        <text x="60" y="75" font-family="Arial" font-size="48" font-weight="900"
                                            fill="#3b82f6" text-anchor="middle" letter-spacing="2">NN</text>
                                        <line x1="30" y1="95" x2="90" y2="95" stroke="#3b82f6"
                                            stroke-width="2" opacity="0.8" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Brand Info -->
                            <div class="text-center space-y-3">
                                <h3 class="text-2xl font-black text-white">NNCLOTHING</h3>
                                <p class="text-sm text-white/70">Platform Custom Apparel Terpercaya</p>
                            </div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/10">
                                <div class="text-center">
                                    <div class="text-3xl font-black text-primary mb-2">50K+</div>
                                    <p class="text-xs text-white/60">Pesanan Selesai</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-black text-accent mb-2">100%</div>
                                    <p class="text-xs text-white/60">Kepuasan Pelanggan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section with NNCLOTHING Branding -->
    <section id="layanan" class="py-12 bg-white relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute -top-40 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 right-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-20">
                <div class="lg:col-span-2">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6">
                        <span class="h-2 w-2 rounded-full bg-primary"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-primary">Metode Sablon Kami</span>
                    </div>
                    <h2 class="text-5xl font-black text-slate-900 mb-4 leading-tight">
                        Pilih Teknik Sablon <span class="text-primary">Premium</span> <br> Dari NNCLOTHING
                    </h2>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-2xl">
                        Kami menyediakan berbagai metode sablon berkualitas premium untuk menghasilkan desain yang sempurna
                        pada setiap produk NNCLOTHING Anda.
                    </p>
                </div>

                <!-- Brand Shield -->
                <div class="hidden lg:flex items-center justify-center">
                    <div class="relative w-40 h-40">
                        <div
                            class="absolute inset-0 bg-linear-to-br from-primary to-secondary rounded-2xl opacity-10 blur-xl">
                        </div>
                        <div
                            class="relative bg-white rounded-2xl border border-primary/20 shadow-xl p-6 flex flex-col items-center justify-center h-full">
                            <div class="text-4xl font-black text-primary mb-2">✓</div>
                            <p class="text-xs font-bold text-slate-600 text-center">Sertifikasi Kualitas NNCLOTHING</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($jenisSablons as $index => $jenis)
                    <div class="group relative overflow-hidden rounded-2xl cursor-pointer transition-all duration-500"
                        onclick="showServiceModal({{ $jenis->id }})">
                        <!-- Background Card -->
                        <div
                            class="absolute inset-0 bg-white border border-slate-200 group-hover:border-primary/30 transition-all duration-500">
                        </div>
                        <div
                            class="absolute inset-0 bg-linear-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500">
                        </div>

                        <!-- Content -->
                        <div class="relative p-8 h-full flex flex-col z-10">
                            <!-- Icon -->
                            <div
                                class="h-20 w-20 rounded-2xl bg-linear-to-br from-primary/10 to-secondary/10 flex items-center justify-center text-primary group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-500 mb-8 border border-primary/10 group-hover:border-primary/30 shadow-md">
                                <i class="lni lni-brush text-4xl"></i>
                            </div>

                            <!-- Title -->
                            <h4
                                class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-primary transition-colors duration-300">
                                {{ $jenis->nama }}
                            </h4>

                            <!-- Description -->
                            <p class="text-slate-600 text-sm leading-relaxed mb-6 flex-grow">
                                {{ Str::limit($jenis->deskripsi, 85) }}
                            </p>

                            <!-- Footer -->
                            <div
                                class="pt-6 border-t border-slate-100 group-hover:border-primary/20 transition-colors flex items-center justify-between">
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 group-hover:text-primary transition-colors uppercase tracking-wider">
                                    <i class="lni lni-layout"></i>
                                    {{ $jenis->produks_count }} Varian
                                </span>
                                <span
                                    class="h-8 w-8 rounded-full border border-primary/30 flex items-center justify-center text-primary opacity-0 group-hover:opacity-100 transform group-hover:scale-110 transition-all duration-300">
                                    <i class="lni lni-arrow-right text-sm"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Hover Gradient Line -->
                        <div
                            class="absolute top-0 left-0 right-0 h-1 bg-linear-to-r from-primary to-secondary transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA Card -->
            <div
                class="mt-16 mb-12 bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-12 border border-white/10 shadow-2xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div>
                        <h3 class="text-3xl font-black text-white mb-3">Ingin Kustomisasi?</h3>
                        <p class="text-white/70 text-sm md:text-base">Konsultasikan kebutuhan desain Anda dengan tim expert
                            NNCLOTHING</p>
                    </div>
                    <div class="md:col-span-2 flex flex-col sm:flex-row gap-3 justify-end items-stretch sm:items-center">
                        @auth
                            <a href="{{ route('customer.order.create') }}"
                                class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 font-bold rounded-full inline-flex items-center justify-center gap-2 whitespace-nowrap hover:shadow-lg hover:shadow-primary/40 transition-all">
                                <i class="lni lni-pencil"></i> Buat Desain Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 font-bold rounded-full inline-flex items-center justify-center gap-2 whitespace-nowrap border border-white/30 transition-all">
                                <i class="lni lni-lock"></i> Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 font-bold rounded-full inline-flex items-center justify-center gap-2 whitespace-nowrap hover:shadow-lg hover:shadow-primary/40 transition-all">
                                <i class="lni lni-rocket"></i> Daftar Gratis
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section with NNCLOTHING Branding -->
    <section id="portfolio" class="py-14 bg-white relative overflow-hidden">
        <!-- Decorative background -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
                <div class="max-w-3xl">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary/10 border border-secondary/20 mb-6">
                        <span class="h-2 w-2 rounded-full bg-secondary"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-secondary">Portofolio & Galeri</span>
                    </div>
                    <h2 class="text-5xl font-black text-slate-900 mb-4 leading-tight">
                        Hasil Karya Premium <br> <span class="text-secondary">NNCLOTHING</span>
                    </h2>
                    <p class="text-lg text-slate-600">Lihat koleksi hasil produksi terbaik kami dan temukan inspirasi untuk
                        desain Anda berikutnya.</p>
                </div>
                <a href="/portfolio"
                    class="group inline-flex items-center gap-3 px-6 py-4 font-bold text-white bg-linear-to-r from-primary to-secondary rounded-full hover:shadow-lg hover:shadow-primary/30 transition-all shrink-0">
                    Lihat Semua Karya
                    <span class="group-hover:translate-x-1 transition-transform">
                        <i class="lni lni-arrow-right"></i>
                    </span>
                </a>
            </div>

            <!-- Portfolio Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($portfolios as $index => $portfolio)
                    <div
                        class="group relative aspect-4/5 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 cursor-pointer transform hover:scale-105">
                        <!-- Image Container -->
                        <div class="absolute inset-0 overflow-hidden bg-slate-200">
                            @if ($portfolio->image)
                                <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-125">
                            @else
                                <div
                                    class="absolute inset-0 bg-linear-to-br from-primary/30 to-secondary/30 flex items-center justify-center">
                                    <i class="lni lni-camera text-6xl text-white/40"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Overlay Gradient -->
                        <div
                            class="absolute inset-0 bg-linear-to-t from-slate-900 via-slate-900/30 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500">
                        </div>

                        <!-- Brand Badge -->
                        <div
                            class="absolute top-4 right-4 px-3 py-1 rounded-full bg-primary/90 backdrop-blur-md shadow-lg">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">NNCLOTHING</span>
                        </div>

                        <!-- Content -->
                        <div class="absolute inset-x-0 bottom-0 p-8">
                            <!-- Method Badge -->
                            @if ($portfolio->method)
                                <div
                                    class="flex items-center gap-2 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-75">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full bg-secondary/90 backdrop-blur-md text-[11px] font-bold text-white uppercase tracking-widest">
                                        {{ $portfolio->method }}
                                    </span>
                                </div>
                            @endif

                            <!-- Title & Description -->
                            <div
                                class="transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <h5 class="text-xl font-black text-white mb-2">{{ $portfolio->title }}</h5>
                                <p
                                    class="text-sm text-slate-300 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                    {{ $portfolio->description ?? 'Produksi berkualitas premium dengan detail sempurna' }}
                                </p>
                            </div>

                            <!-- View Button -->
                            <div
                                class="absolute bottom-8 right-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200">
                                <span
                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white/20 backdrop-blur border border-white/30 text-white hover:bg-white hover:text-primary transition-all">
                                    <i class="lni lni-arrow-top-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Portfolio Stats -->
            <div
                class="mt-20 grid grid-cols-3 gap-6 bg-linear-to-r from-slate-50 to-primary/5 rounded-3xl p-12 border border-slate-200">
                <div class="text-center">
                    <div class="text-4xl font-black text-primary mb-3">50K+</div>
                    <p class="text-slate-600 font-semibold">Produk Selesai</p>
                </div>
                <div class="h-full border-l border-r border-slate-200"></div>
                <div class="text-center">
                    <div class="text-4xl font-black text-secondary mb-3">99.8%</div>
                    <p class="text-slate-600 font-semibold">Kepuasan Pelanggan</p>
                </div>
                <div class="h-full border-l border-r border-slate-200"></div>
                <div class="text-center">
                    <div class="text-4xl font-black text-accent mb-3">24/7</div>
                    <p class="text-slate-600 font-semibold">Support Team</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section with NNCLOTHING Branding -->
    <section class="py-32 lg:py-40 relative overflow-hidden">
        <!-- Background (darker overlays for better text contrast) -->
        <div
            class="absolute inset-0 bg-linear-to-br from-slate-900/95 via-primary/80 to-slate-900/95 mix-blend-multiply backdrop-blur-sm">
        </div>
        <div class="absolute inset-0 bg-black/60 mix-blend-multiply pointer-events-none"></div>

        <div
            class="absolute -top-40 left-1/4 w-96 h-96 bg-primary/80 rounded-full blur-3xl opacity-90 pointer-events-none">
        </div>
        <div
            class="absolute -bottom-40 right-1/4 w-96 h-96 bg-secondary/80 rounded-full blur-3xl opacity-90 pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Content -->
                <div class="space-y-8">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-white">Siap Memulai?</span>
                    </div>

                    <!-- Headline -->
                    <div class="space-y-4">
                        <h2 class="text-5xl md:text-6xl font-black text-white leading-tight">
                            Wujudkan Desain <br>
                            <span class="bg-linear-to-r from-primary via-blue-300 to-accent bg-clip-text text-transparent">
                                Impianmu Sekarang
                            </span>
                        </h2>
                        <p class="text-lg text-white/80 leading-relaxed max-w-xl">
                            Bergabunglah dengan ribuan pelanggan NNCLOTHING yang telah mempercayai kami untuk mewujudkan
                            visi mereka menjadi kenyataan. Dapatkan konsultasi gratis dari tim expert kami.
                        </p>
                    </div>

                    <!-- Features List -->
                    <div class="space-y-4 pt-6">
                        <div class="flex items-center gap-4 group">
                            <div
                                class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center group-hover:bg-primary/30 transition-colors flex-shrink-0">
                                <i class="lni lni-check-circle text-primary text-lg"></i>
                            </div>
                            <span class="text-white font-semibold">Konsultasi Desain Gratis</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center group-hover:bg-primary/30 transition-colors flex-shrink-0">
                                <i class="lni lni-wallet text-primary text-lg"></i>
                            </div>
                            <span class="text-white font-semibold">Harga Kompetitif & Transparan</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center group-hover:bg-primary/30 transition-colors flex-shrink-0">
                                <i class="lni lni-truck-fast text-primary text-lg"></i>
                            </div>
                            <span class="text-white font-semibold">Pengiriman Cepat & Aman</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center group-hover:bg-primary/30 transition-colors flex-shrink-0">
                                <i class="lni lni-shield-check text-primary text-lg"></i>
                            </div>
                            <span class="text-white font-semibold">Garansi Kualitas 100%</span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4 pt-8">
                        @auth
                            <a href="{{ route('customer.order.create') }}"
                                class="group px-8 py-4 bg-linear-to-r from-primary to-secondary text-white font-bold rounded-full hover:shadow-2xl hover:shadow-primary/40 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                                <i class="lni lni-pencil-alt"></i>
                                Buat Pesanan Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="group px-8 py-4 bg-linear-to-r from-primary to-secondary text-white font-bold rounded-full hover:shadow-2xl hover:shadow-primary/40 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                                <i class="lni lni-play"></i>
                                Mulai Sekarang
                            </a>
                        @endauth
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="group px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-full border border-white/30 hover:border-white/50 transition-all duration-300 flex items-center gap-2 backdrop-blur-sm whitespace-nowrap">
                            <i class="lni lni-whatsapp"></i>
                            Chat Langsung
                        </a>
                    </div>
                </div>

                <!-- Right: Visual Card -->
                <div class="hidden lg:block relative">
                    <div class="absolute inset-0 bg-linear-to-br from-primary/30 to-secondary/20 rounded-3xl blur-2xl">
                    </div>
                    <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 space-y-8">
                        <!-- Icon Section -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-primary/20 flex items-center justify-center">
                                <i class="lni lni-palette text-primary text-3xl"></i>
                            </div>
                            <div class="h-16 w-16 rounded-2xl bg-secondary/20 flex items-center justify-center">
                                <i class="lni lni-star text-secondary text-3xl"></i>
                            </div>
                        </div>

                        <!-- Stat Card 1 -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <p class="text-white/60 text-sm font-semibold mb-2">TOTAL PELANGGAN</p>
                            <p class="text-4xl font-black text-white mb-2">10K+</p>
                            <p class="text-xs text-white/50">Yang puas dengan layanan NNCLOTHING</p>
                        </div>

                        <!-- Stat Card 2 -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <p class="text-white/60 text-sm font-semibold mb-2">RATING KEPUASAN</p>
                            <p class="text-4xl font-black text-primary mb-2">4.9/5 <i
                                    class="lni lni-star-fill text-yellow-400"></i></p>
                            <p class="text-xs text-white/50">Berdasarkan review dari pelanggan nyata</p>
                        </div>

                        <!-- Stat Card 3 -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <p class="text-white/60 text-sm font-semibold mb-2">PROSES CEPAT</p>
                            <p class="text-4xl font-black text-accent mb-2">3-5 Hari</p>
                            <p class="text-xs text-white/50">Dari pembayaran hingga produk siap kirim</p>
                        </div>

                        <!-- NNCLOTHING Logo -->
                        <div class="pt-4 border-t border-white/10">
                            <x-navbar-logo />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Detail Modal (Kept for functionality) -->
    <div class="fixed inset-0 z-60 hidden overflow-y-auto" id="serviceModal" aria-hidden="true" role="dialog">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeServiceModal()">
            </div>

            <div
                class="relative w-full max-w-2xl bg-white rounded-4xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                <div class="bg-linear-to-r from-primary to-secondary p-8 text-white relative">
                    <button type="button"
                        class="absolute top-6 right-6 h-10 w-10 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-colors"
                        onclick="closeServiceModal()">
                        <i class="lni lni-close"></i>
                    </button>
                    <h4 class="text-3xl font-black" id="modalServiceName">Informasi Layanan</h4>
                    <p class="text-white/80 mt-2 font-medium tracking-wide" id="modalServiceDescShort">Detail teknis dan
                        estimasi biaya</p>
                </div>

                <div class="p-8">
                    <div class="mb-8">
                        <h5 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-4">Tentang Layanan</h5>
                        <p class="text-slate-600 leading-relaxed" id="modalServiceFullDesc"></p>
                    </div>

                    <div>
                        <h5 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-4">Daftar Harga Estimasi
                        </h5>
                        <div class="overflow-hidden rounded-2xl border border-slate-100">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">
                                            Ukuran</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">
                                            Regular</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">
                                            Express</th>
                                    </tr>
                                </thead>
                                <tbody id="priceTable" class="divide-y divide-slate-50">
                                    {{-- Data injected via JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="p-8 pt-0 flex justify-end">
                    <a href="{{ route('customer.order.create') }}" class="btn-premium">Mulai Pesan Sekarang</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showServiceModal(id) {
            fetch(`/api/jenis-sablon${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalServiceName').innerText = data.nama;
                    document.getElementById('modalServiceFullDesc').innerText = data.deskripsi;

                    var tbody = document.getElementById('priceTable');
                    if (tbody && Array.isArray(data.priceTable)) {
                        var rows = '';
                        data.priceTable.forEach(item => {
                            rows += `
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-700">${item.ukuran ?? ''}</td>
                                    <td class="px-6 py-4 font-black text-primary">${item.regular ?? ''}</td>
                                    <td class="px-6 py-4 font-black text-secondary">${item.express ?? ''}</td>
                                </tr>
                            `;
                        });
                        tbody.innerHTML = rows;
                    }

                    document.getElementById('serviceModal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
@endpush
