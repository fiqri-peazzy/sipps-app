@extends('layouts.frontend')

@section('title', 'Buat Pesanan Baru')

@section('content')
    <div class="pt-32 pb-20 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}"
                                class="text-xs font-bold text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">Beranda</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="lni lni-chevron-right text-[10px] text-slate-300 mx-2"></i>
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-widest">Buat Pesanan</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Buat <span
                        class="text-primary italic">Pesanan</span> Baru</h1>
                <p class="text-slate-500 mt-4 text-lg max-w-2xl leading-relaxed">Wujudkan desain impian Anda dengan kualitas
                    sablon terbaik dari workshop kami. Proses cepat, hasil presisi.</p>
            </div>

            <!-- Form Area -->
            <div class="mt-8">
                @livewire('customer.place-order-form', [
                    'jenisSablons' => $jenisSablons,
                    'ukurans' => $ukurans,
                    'selectedJenis' => $selectedJenis,
                ])
            </div>
        </div>
    </div>
@endsection
