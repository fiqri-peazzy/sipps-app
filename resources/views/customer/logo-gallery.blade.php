<!-- NNCLOTHING Logo Gallery Page -->
@extends('layouts.customer')

@section('customer-content')
    <!-- Hero Section -->
    <div class="bg-linear-to-r from-primary via-blue-500 to-secondary relative overflow-hidden rounded-3xl mb-12">
        <div class="absolute inset-0">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 px-8 py-16 text-center text-white">
            <x-nnclothing-logo type="full" size="lg" class="mx-auto mb-8 drop-shadow-lg" />
            <h1 class="text-4xl md:text-5xl font-black mb-4">Logo NNCLOTHING</h1>
            <p class="text-lg text-white/90 mb-8">Eksplorasi berbagai varian dan penggunaan logo brand kami yang modern dan profesional.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-16">
        <!-- Section 1: All Logo Variations -->
        <section>
            <h2 class="text-3xl font-black text-slate-900 mb-8">Semua Varian Logo</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Full Logo -->
                <div class="card-modern p-8 text-center hover:shadow-lg transition-all">
                    <div class="bg-slate-50 rounded-xl p-6 mb-4 h-32 flex items-center justify-center">
                        <x-nnclothing-logo type="full" size="md" />
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Full Logo</h3>
                    <p class="text-xs text-slate-600 mb-4">Format lengkap dengan nama brand</p>
                    <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded px-2 py-1">400x120px</p>
                </div>

                <!-- Icon Logo -->
                <div class="card-modern p-8 text-center hover:shadow-lg transition-all">
                    <div class="bg-slate-50 rounded-xl p-6 mb-4 h-32 flex items-center justify-center">
                        <x-nnclothing-logo type="icon" size="md" />
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Icon Logo</h3>
                    <p class="text-xs text-slate-600 mb-4">Ikon saja, format persegi</p>
                    <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded px-2 py-1">120x120px</p>
                </div>

                <!-- Horizontal Logo -->
                <div class="card-modern p-8 text-center hover:shadow-lg transition-all">
                    <div class="bg-slate-50 rounded-xl p-6 mb-4 h-32 flex items-center justify-center">
                        <x-nnclothing-logo type="horizontal" size="md" />
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Horizontal</h3>
                    <p class="text-xs text-slate-600 mb-4">Ikon & teks horizontal</p>
                    <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded px-2 py-1">300x80px</p>
                </div>

                <!-- Premium Logo -->
                <div class="card-modern p-8 text-center hover:shadow-lg transition-all">
                    <div class="bg-slate-50 rounded-xl p-6 mb-4 h-32 flex items-center justify-center">
                        <x-nnclothing-logo type="premium" size="md" />
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Premium</h3>
                    <p class="text-xs text-slate-600 mb-4">Desain detail lengkap</p>
                    <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded px-2 py-1">400x200px</p>
                </div>

                <!-- Minimalist Logo -->
                <div class="card-modern p-8 text-center hover:shadow-lg transition-all">
                    <div class="bg-slate-50 rounded-xl p-6 mb-4 h-32 flex items-center justify-center">
                        <x-nnclothing-logo type="minimalist" size="md" />
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">Minimalist</h3>
                    <p class="text-xs text-slate-600 mb-4">Desain minimal & bersih</p>
                    <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded px-2 py-1">100x100px</p>
                </div>
            </div>
        </section>

        <!-- Section 2: Size Variations -->
        <section>
            <h2 class="text-3xl font-black text-slate-900 mb-8">Variasi Ukuran</h2>
            <div class="space-y-6">
                <!-- Small Size -->
                <div class="card-modern p-6">
                    <p class="text-sm font-bold text-slate-600 mb-3">Small (w-24 / 96px)</p>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg">
                        <x-nnclothing-logo type="full" size="sm" />
                        <x-nnclothing-logo type="horizontal" size="sm" />
                        <x-nnclothing-logo type="icon" size="sm" />
                    </div>
                </div>

                <!-- Medium Size -->
                <div class="card-modern p-6">
                    <p class="text-sm font-bold text-slate-600 mb-3">Medium (w-40 / 160px)</p>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg">
                        <x-nnclothing-logo type="full" size="md" />
                        <x-nnclothing-logo type="horizontal" size="md" />
                        <x-nnclothing-logo type="icon" size="md" />
                    </div>
                </div>

                <!-- Large Size -->
                <div class="card-modern p-6">
                    <p class="text-sm font-bold text-slate-600 mb-3">Large (w-56 / 224px)</p>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg">
                        <x-nnclothing-logo type="full" size="lg" />
                        <x-nnclothing-logo type="horizontal" size="lg" />
                    </div>
                </div>

                <!-- XL Size -->
                <div class="card-modern p-6">
                    <p class="text-sm font-bold text-slate-600 mb-3">XL (w-72 / 288px)</p>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg overflow-x-auto">
                        <x-nnclothing-logo type="premium" size="xl" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Use Cases -->
        <section>
            <h2 class="text-3xl font-black text-slate-900 mb-8">Contoh Penggunaan</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Navigation -->
                <div class="card-modern p-8 bg-white">
                    <h3 class="font-bold text-slate-900 mb-4">Navigasi Website</h3>
                    <div class="bg-slate-900 rounded-lg p-4 mb-4 flex items-center justify-between">
                        <x-nnclothing-logo type="icon" size="sm" class="brightness-0 invert" />
                        <div class="flex items-center gap-4 text-white text-sm">
                            <a href="#" class="hover:text-primary transition-colors">Beranda</a>
                            <a href="#" class="hover:text-primary transition-colors">Pesanan</a>
                            <a href="#" class="hover:text-primary transition-colors">Profil</a>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">Gunakan ikon logo di navigation bar untuk branding konsisten.</p>
                </div>

                <!-- Hero Section -->
                <div class="card-modern p-8 bg-white">
                    <h3 class="font-bold text-slate-900 mb-4">Hero Section</h3>
                    <div class="bg-linear-to-r from-primary to-secondary rounded-lg p-8 text-center mb-4">
                        <x-nnclothing-logo type="full" size="lg" class="mx-auto drop-shadow-lg mb-4" />
                        <p class="text-white text-sm font-semibold">Selamat datang di NNCLOTHING</p>
                    </div>
                    <p class="text-sm text-slate-600">Full logo dengan background gradien untuk tampilan maksimal.</p>
                </div>

                <!-- Footer -->
                <div class="card-modern p-8 bg-white">
                    <h3 class="font-bold text-slate-900 mb-4">Footer Website</h3>
                    <div class="bg-slate-900 rounded-lg p-6 mb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <x-nnclothing-logo type="icon" size="sm" class="brightness-0 invert" />
                            <p class="text-white font-bold">NNCLOTHING</p>
                        </div>
                        <p class="text-slate-400 text-xs">Premium Custom Apparel © 2024</p>
                    </div>
                    <p class="text-sm text-slate-600">Horizontal layout ideal untuk footer dengan space terbatas.</p>
                </div>

                <!-- Mobile Menu -->
                <div class="card-modern p-8 bg-white">
                    <h3 class="font-bold text-slate-900 mb-4">Mobile Menu</h3>
                    <div class="bg-slate-900 rounded-lg p-4 mb-4 text-center space-y-3">
                        <x-nnclothing-logo type="icon" size="md" class="mx-auto brightness-0 invert" />
                        <a href="#" class="block text-white text-sm hover:text-primary">Beranda</a>
                        <a href="#" class="block text-white text-sm hover:text-primary">Pesanan</a>
                        <a href="#" class="block text-white text-sm hover:text-primary">Profil</a>
                    </div>
                    <p class="text-sm text-slate-600">Minimalist ikon untuk tampilan mobile yang bersih.</p>
                </div>

                <!-- Print Materials -->
                <div class="card-modern p-8 bg-white">
                    <h3 class="font-bold text-slate-900 mb-4">Materi Cetak</h3>
                    <div class="bg-white rounded-lg border border-slate-200 p-6 mb-4 text-center">
                        <x-nnclothing-logo type="premium" size="lg" class="mx-auto mb-3" />
                        <p class="font-bold text-slate-900">NNCLOTHING</p>
                        <p class="text-xs text-slate-600">Premium Custom Apparel</p>
                    </div>
                    <p class="text-sm text-slate-600">Premium logo dengan detail lengkap untuk bisnis card dan poster.</p>
                </div>
            </div>
        </section>

        <!-- Section 4: Color Palette -->
        <section>
            <h2 class="text-3xl font-black text-slate-900 mb-8">Palet Warna Brand</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach(config('branding.logo.colors') as $colorName => $colorHex)
                    <div class="card-modern overflow-hidden hover:shadow-lg transition-all">
                        <div class="h-24 transition-all" style="background-color: {{ $colorHex }}"></div>
                        <div class="p-4">
                            <p class="font-bold text-slate-900 text-sm capitalize">{{ str_replace('_', ' ', $colorName) }}</p>
                            <p class="font-mono text-xs text-slate-600 mt-1">{{ $colorHex }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Section 5: Guidelines -->
        <section>
            <h2 class="text-3xl font-black text-slate-900 mb-8">Panduan Penggunaan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Do's -->
                <div class="card-modern p-8 border-l-4 border-green-500">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="lni lni-checkmark-circle text-3xl text-green-500"></i>
                        <h3 class="text-xl font-bold text-slate-900">Yang Diperbolehkan</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2">
                            <i class="lni lni-check-mark-circle text-green-500 flex-shrink-0"></i>
                            <span>Gunakan dalam berbagai ukuran</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-check-mark-circle text-green-500 flex-shrink-0"></i>
                            <span>Pertahankan aspect ratio original</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-check-mark-circle text-green-500 flex-shrink-0"></i>
                            <span>Gunakan warna dari palet brand</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-check-mark-circle text-green-500 flex-shrink-0"></i>
                            <span>Beri ruang kosong di sekitar logo</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-check-mark-circle text-green-500 flex-shrink-0"></i>
                            <span>Gunakan di berbagai background</span>
                        </li>
                    </ul>
                </div>

                <!-- Don'ts -->
                <div class="card-modern p-8 border-l-4 border-red-500">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="lni lni-close-circle text-3xl text-red-500"></i>
                        <h3 class="text-xl font-bold text-slate-900">Yang Tidak Diperbolehkan</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2">
                            <i class="lni lni-close text-red-500 flex-shrink-0"></i>
                            <span>Mengubah proporsi logo</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-close text-red-500 flex-shrink-0"></i>
                            <span>Mengganti warna dengan tidak tepat</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-close text-red-500 flex-shrink-0"></i>
                            <span>Menambahkan efek berlebihan</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-close text-red-500 flex-shrink-0"></i>
                            <span>Menggunakan tanpa ruang kosong</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="lni lni-close text-red-500 flex-shrink-0"></i>
                            <span>Memutarkan atau mencerminkan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <!-- CTA Section -->
    <div class="bg-linear-to-r from-primary to-secondary rounded-3xl p-12 text-white text-center mt-16">
        <h3 class="text-3xl font-bold mb-4">Siap untuk Mulai Membuat Pesanan?</h3>
        <p class="text-lg text-white/90 mb-8">Buat custom apparel Anda sekarang dengan kualitas terbaik dari NNCLOTHING.</p>
        <a href="{{ route('customer.order.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-bold rounded-xl hover:bg-slate-100 transition-colors">
            <i class="lni lni-plus"></i> Buat Pesanan Sekarang
        </a>
    </div>
@endsection
