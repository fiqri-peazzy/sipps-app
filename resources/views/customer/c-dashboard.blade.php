@extends('layouts.customer')

@section('customer-content')
    <div class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Dashboard <span
                class="text-primary italic">Utama</span></h1>
        <p class="text-slate-500 mt-2 text-lg">Pantau status pesanan dan aktivitas produksi Anda secara real-time.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="card-modern !p-6 flex items-center gap-6">
            <div class="h-14 w-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="lni lni-cart text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Total Pesanan</p>
                <h3 class="text-2xl font-black text-slate-900">{{ array_sum($orderStats) }}</h3>
            </div>
        </div>

        <div class="card-modern !p-6 flex items-center gap-6">
            <div class="h-14 w-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i class="lni lni-timer text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Menunggu Bayar</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $orderStats['pending_payment'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="card-modern !p-6 flex items-center gap-6">
            <div class="h-14 w-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <i class="lni lni-package text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Dalam Produksi</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $orderStats['in_production'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="card-modern !p-6 flex items-center gap-6">
            <div class="h-14 w-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                <i class="lni lni-checkmark-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Sudah Selesai</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $orderStats['completed'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card-modern">
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-50">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                    <i class="lni lni-package text-xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-900">Pesanan Terbaru</h4>
            </div>
            <a href="{{ route('customer.orders.index') }}" class="text-sm font-bold text-primary hover:underline">Lihat
                Semua &rarr;</a>
        </div>

        @if (isset($recentOrders) && $recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach ($recentOrders as $order)
                    <div
                        class="group flex flex-col md:flex-row md:items-center justify-between p-6 rounded-3xl border border-slate-50 hover:border-primary/20 hover:bg-primary/5 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4 md:mb-0">
                            <div
                                class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-primary transition-colors">
                                <i class="lni lni-cart-full text-xl"></i>
                            </div>
                            <div>
                                <h6 class="font-bold text-slate-900">{{ $order->order_number }}</h6>
                                <p class="text-xs text-slate-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4">
                            <div class="text-right">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Total Harga</p>
                                <p class="font-black text-slate-900">{{ $order->formatted_total_harga }}</p>
                            </div>

                            <div
                                class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.15em] 
                                @if ($order->status == 'completed') bg-green-100 text-green-700
                                @elseif($order->status == 'pending_payment') bg-amber-100 text-amber-700
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ $order->status_label }}
                            </div>

                            <a href="{{ route('customer.orders.show', $order->id) }}"
                                class="h-10 w-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                <i class="lni lni-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-20 flex flex-col items-center text-center">
                <div class="h-24 w-24 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                    <i class="lni lni-package text-5xl"></i>
                </div>
                <h5 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Pesanan</h5>
                <p class="text-slate-500 max-w-sm mb-8">Anda belum melakukan pesanan apapun. Mulai kustomisasi kaos Anda
                    sekarang!</p>
                <a href="{{ route('customer.order.create') }}" class="btn-premium">
                    <i class="lni lni-plus mr-2"></i> Buat Pesanan Pertama
                </a>
            </div>
        @endif
    </div>
@endsection
