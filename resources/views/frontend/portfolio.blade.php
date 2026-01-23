@extends('layouts.frontend')
@section('title', 'Portfolio Karya Terbaik')

@section('content')
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div
                class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="max-w-2xl">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-secondary mb-4">Galeri Inspirasi</h2>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-tight">Bukti Kualitas <br>
                        di Setiap Karya</h1>
                </div>
                <div class="flex flex-wrap gap-2 pb-2">
                    <button
                        class="px-6 py-2 rounded-full bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">Semua</button>
                    <button
                        class="px-6 py-2 rounded-full bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-colors">DTF</button>
                    <button
                        class="px-6 py-2 rounded-full bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-colors">Manual</button>
                    <button
                        class="px-6 py-2 rounded-full bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-colors">Sublim</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($portfolios as $index => $portfolio)
                    <div class="group relative aspect-3/4 rounded-4xl overflow-hidden shadow-2xl shadow-slate-200 animate-in fade-in slide-in-from-bottom-8 duration-700"
                        style="animation-delay: {{ $index * 100 }}ms">
                        @if ($portfolio->image)
                            <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        @else
                            <div class="absolute inset-0 bg-slate-200 flex items-center justify-center">
                                <i class="lni lni-camera text-6xl text-slate-400"></i>
                            </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-500">
                        </div>

                        <!-- Overlay Content -->
                        <div
                            class="absolute inset-x-0 bottom-0 p-10 transform translate-y-6 group-hover:translate-y-0 transition-transform duration-500">
                            @if ($portfolio->method)
                                <span
                                    class="inline-block px-4 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[10px] font-black text-white uppercase tracking-widest mb-4">
                                    {{ $portfolio->method }}
                                </span>
                            @endif
                            <h5 class="text-2xl font-black text-white mb-4 leading-tight">{{ $portfolio->title }}</h5>

                            <div
                                class="space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 mb-6">
                                <p class="text-sm text-slate-300 line-clamp-2">
                                    {{ $portfolio->description }}
                                </p>
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @if ($portfolio->material)
                                        <span
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-700 px-2 py-0.5 rounded">
                                            Bahan: {{ $portfolio->material }}
                                        </span>
                                    @endif
                                    @if ($portfolio->size)
                                        <span
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-700 px-2 py-0.5 rounded">
                                            Size: {{ $portfolio->size }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('customer.order.create') }}"
                                class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white hover:scale-110 active:scale-95 transition-all opacity-0 group-hover:opacity-100 group-hover:translate-x-0 translate-x-[-20px] delay-200">
                                <i class="lni lni-cart"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                {{ $portfolios->links() }}
            </div>
        </div>
    </section>

    <!-- Bottom Banner -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl font-black text-slate-900 mb-8">Punya Konsep Desain Sendiri?</h3>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('customer.order.create') }}" class="btn-premium">Mulai Produksi Sekarang</a>
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="px-8 py-3 rounded-full border-2 border-slate-200 font-bold text-slate-600 hover:bg-slate-100 transition-all flex items-center gap-2">
                    <i class="lni lni-whatsapp"></i> Chat Admin
                </a>
            </div>
        </div>
    </section>
@endsection
