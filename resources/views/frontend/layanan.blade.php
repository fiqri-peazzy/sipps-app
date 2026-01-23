@extends('layouts.frontend')
@section('title', 'Layanan Sablon Profesional')

@section('content')
    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-96 bg-primary/5 blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <h2 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-4">Eksplorasi Teknik</h2>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 leading-tight">Layanan Sablon <br>
                    Standar Workshop Modern</h1>
                <p class="text-lg text-slate-500 leading-relaxed">Kami menghadirkan berbagai pilihan teknik sablon terbaik
                    yang menyesuaikan dengan kebutuhan budget dan kualitas visual Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($jenisSablons as $index => $jenis)
                    <div class="card-modern group animate-in fade-in slide-in-from-bottom-8 duration-700"
                        style="animation-delay: {{ $index * 100 }}ms">
                        <div
                            class="h-20 w-20 rounded-4xl bg-slate-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-500 mb-10 border border-slate-100 rotate-3">
                            <i class="lni lni-brush text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black mb-4 group-hover:text-primary transition-colors">{{ $jenis->nama }}
                        </h4>
                        <p class="text-slate-500 leading-relaxed mb-8">{{ $jenis->deskripsi }}</p>

                        <div
                            class="p-6 rounded-3xl bg-slate-50 border border-slate-100 group-hover:bg-white group-hover:shadow-lg group-hover:shadow-slate-200/50 transition-all duration-500">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</span>
                                <span
                                    class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 text-[8px] font-black uppercase tracking-widest">Available</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600">{{ $jenis->produks_count }} Varian
                                    Tersedia</span>
                                <a href="{{ route('customer.order.create', ['jenis' => $jenis->id]) }}"
                                    class="text-primary font-black text-xs hover:underline">Pesan &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-modern bg-slate-900! text-white! p-12! md:p-20! relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-black mb-6">Bingung Pilih Teknik Sablon?</h2>
                        <p class="text-slate-400 leading-relaxed mb-8">Konsultasikan gratis dengan tim ahli kami untuk
                            menentukan teknik sablon mana yang paling cocok untuk material kain dan desain Anda.</p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="btn-premium bg-white! text-slate-900! border-none shadow-none">
                            Tanya Ahli Sablon
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                            <i class="lni lni-timer text-3xl text-primary mb-4"></i>
                            <h6 class="font-black text-sm uppercase tracking-widest leading-tight">Proses Cepat</h6>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                            <i class="lni lni-shield text-3xl text-primary mb-4"></i>
                            <h6 class="font-black text-sm uppercase tracking-widest leading-tight">Garansi Hasil</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
