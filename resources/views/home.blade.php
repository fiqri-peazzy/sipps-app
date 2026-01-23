@extends('layouts.frontend')
@section('title', 'Solusi Sablon Cerdas & Berkualitas')

@push('styles')
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center overflow-hidden bg-slate-50 pt-10">
        <!-- Decoration Background -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-linear-to-l from-primary/10 to-transparent"></div>
        <div class="absolute -top-24 -left-20 w-96 h-96 bg-secondary/10 rounded-full blur-3xl animate-blob"></div>
        <div
            class="absolute bottom-20 right-10 w-64 h-64 bg-accent/10 rounded-full blur-3xl animate-blob animation-delay-2000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm animate-in fade-in slide-in-from-left-4 duration-1000">
                        <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Sistem Penjadwalan Sablon
                            Terpadu</span>
                    </div>

                    <h1
                        class="text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] text-slate-900 animate-in fade-in slide-in-from-bottom-4 duration-700">
                        Kustomisasi <br>
                        <span class="bg-linear-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">Gaya
                            Anda</span> <br>
                        Tanpa Batas.
                    </h1>

                    <p
                        class="text-lg text-slate-600 leading-relaxed max-w-xl animate-in fade-in slide-in-from-bottom-6 duration-1000">
                        Hadirkan identitas visual terbaik pada setiap helai kain. Didukung teknologi penjadwalan produksi
                        cerdas untuk hasil tepat waktu dan kualitas tanpa kompromi.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 animate-in fade-in slide-in-from-bottom-8 duration-1000">
                        @auth
                            <a href="{{ route('customer.order.create') }}" class="btn-premium">
                                <i class="lni lni-plus mr-2"></i> Mulai Pesan Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-premium">
                                <i class="lni lni-rocket mr-2"></i> Join SIPPS Community
                            </a>
                        @endauth
                        <a href="#layanan"
                            class="group flex items-center gap-3 px-6 py-3 font-bold text-slate-700 hover:text-primary transition-all">
                            Eksplor Layanan
                            <span
                                class="h-10 w-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                <i class="lni lni-arrow-right"></i>
                            </span>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="pt-8 flex items-center gap-8 grayscale opacity-50">
                        {{-- <img src="/assets/logos/client1.svg" class="h-6" alt=""> --}}
                        {{-- <img src="/assets/logos/client2.svg" class="h-6" alt=""> --}}
                    </div>
                </div>

                <div class="relative lg:block hidden">
                    <div
                        class="absolute inset-0 bg-linear-to-br from-primary/20 to-secondary/20 rounded-full blur-[100px] animate-pulse">
                    </div>
                    <div class="relative z-10 animate-float">
                        <dotlottie-player src="https://lottie.host/b9e2573e-a873-4be6-9c77-dcd667447ee9/rMQdTVTLe0.lottie"
                            background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay>
                        </dotlottie-player>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-4">Layanan Unggulan Kami</h2>
                <h3 class="text-4xl font-extrabold text-slate-900 mb-6">Pilih Teknik Sablon Terbaik <br> Untuk Kebutuhan
                    Anda</h3>
                <div class="h-1.5 w-20 bg-linear-to-r from-primary to-secondary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($jenisSablons as $index => $jenis)
                    <div class="card-modern group hover:-translate-y-4 cursor-pointer"
                        onclick="showServiceModal({{ $jenis->id }})">
                        <div
                            class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-500 mb-8 border border-slate-100">
                            <i class="lni lni-brush text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-4 group-hover:text-primary transition-colors">{{ $jenis->nama }}
                        </h4>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ Str::limit($jenis->deskripsi, 100) }}</p>
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <span
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $jenis->produks_count }}
                                Varian</span>
                            <span
                                class="text-primary opacity-0 group-hover:opacity-100 transition-all transform translate-x-[-10px] group-hover:translate-x-0">
                                <i class="lni lni-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
                <div class="max-w-2xl">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-secondary mb-4">Galeri Produksi</h2>
                    <h3 class="text-4xl font-extrabold text-slate-900">Karya Terbaik Dari <br> Workshop Kami</h3>
                </div>
                <a href="#"
                    class="inline-flex items-center gap-2 font-bold text-slate-600 hover:text-primary transition-colors">
                    Lihat Semua Karya <i class="lni lni-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($portfolios as $index => $portfolio)
                    <div class="group relative aspect-4/5 rounded-4xl overflow-hidden shadow-lg shadow-slate-200/50">
                        @if ($portfolio->image)
                            <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="absolute inset-0 bg-slate-200 flex items-center justify-center">
                                <i class="lni lni-camera text-4xl text-slate-400"></i>
                            </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-linear-to-t from-slate-900 via-slate-900/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500">
                        </div>

                        <div
                            class="absolute inset-x-0 bottom-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            @if ($portfolio->method)
                                <span
                                    class="inline-block px-3 py-1 rounded-full bg-primary/90 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-widest mb-4">
                                    {{ $portfolio->method }}
                                </span>
                            @endif
                            <h5 class="text-xl font-bold text-white mb-2">{{ $portfolio->title }}</h5>
                            <p
                                class="text-sm text-slate-300 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                {{ $portfolio->description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[3rem] p-12 md:p-20 relative overflow-hidden text-center md:text-left">
                <!-- Abstract Decor -->
                <div class="absolute top-0 right-0 w-1/2 h-full bg-linear-to-br from-primary/20 to-transparent"></div>
                <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="max-w-2xl">
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Siap Mewujudkan Ide <br> Desain Anda?
                        </h2>
                        <p class="text-lg text-slate-400 leading-relaxed">Konsultasikan kebutuhan sablon Anda dengan tim
                            profesional kami dan dapatkan penawaran terbaik hari ini.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                        <a href="{{ route('customer.order.create') }}"
                            class="btn-premium bg-white! text-white! hover:bg-slate-50! shadow-none!">
                            <i class="lni lni-cart-full mr-2"></i> Pesan Sekarang
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="inline-flex items-center justify-center px-8 py-3 font-bold text-white border-2 border-white/20 rounded-full hover:bg-white/10 transition-all">
                            <i class="lni lni-whatsapp mr-2"></i> Chat Admin
                        </a>
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
