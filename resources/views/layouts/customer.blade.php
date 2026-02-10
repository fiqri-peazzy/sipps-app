@extends('layouts.frontend')

@section('title', 'Area Pelanggan')

@push('styles')
    <style>
        .sidebar-item-active {
            @apply bg-primary/10 text-primary font-bold shadow-sm border-r-4 border-primary;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-80 shrink-0">
                <div class="sticky top-28 space-y-6">
                    <!-- User Profile Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="bg-slate-900 p-6 text-white relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
                            <div class="relative z-10 flex flex-col items-center">
                                <div
                                    class="h-20 w-20 rounded-3xl bg-linear-to-br from-primary to-secondary p-1 shadow-xl mb-4">
                                    <div
                                        class="h-full w-full rounded-[1.25rem] bg-slate-900 flex items-center justify-center text-3xl font-white">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </div>
                                <h5 class="text-xl text-white tracking-tight">{{ auth()->user()->name }}</h5>

                            </div>
                        </div>

                        <!-- Main Nav -->
                        <nav class="p-4">
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('customer.dashboard') }}"
                                        class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group {{ request()->routeIs('customer.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <i class="lni lni-grid-alt text-xl group-hover:scale-110 transition-transform"></i>
                                        <span class="font-bold">Dashboard Overview</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.orders.index') }}"
                                        class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group {{ request()->routeIs('customer.orders.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <i class="lni lni-cart text-xl group-hover:scale-110 transition-transform"></i>
                                        <span class="font-bold">Pesanan Saya</span>
                                        @if (isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                                            <span
                                                class="ml-auto bg-accent text-white text-[10px] font-black px-2 py-0.5 rounded-lg">{{ $pendingOrdersCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.profile') }}"
                                        class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group {{ request()->routeIs('customer.profile') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <i class="lni lni-user text-xl group-hover:scale-110 transition-transform"></i>
                                        <span class="font-bold">Profil Akun</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Action Nav -->
                        <div class="p-4 pt-0">
                            <div class="h-px bg-slate-50 mb-4"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-red-400 hover:bg-red-50 hover:text-red-500 transition-all duration-300 group">
                                    <i class="lni lni-exit text-xl group-hover:-translate-x-1 transition-transform"></i>
                                    <span class="font-bold">Keluar Sesi</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Create Order Promo Card -->
                    <div
                        class="bg-linear-to-br from-primary to-secondary p-6 rounded-3xl text-white shadow-xl shadow-primary/20 relative overflow-hidden group">
                        <div
                            class="absolute -bottom-6 -right-6 h-32 w-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <h6 class="text-xs font-black uppercase tracking-widest text-white/70 mb-2">Mau Cetak Lagi?</h6>
                        <h4 class="text-xl font-black mb-6 leading-tight">Mulai kustomisasi desain baru Anda sekarang.</h4>
                        <a href="{{ route('customer.order.create') }}"
                            class="inline-flex items-center justify-center w-full py-4 rounded-xl bg-white text-primary font-black text-sm hover:bg-slate-100 transition-all">
                            Buat Pesanan &rarr;
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0">
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-1000">
                    @yield('customer-content')
                </div>
            </main>
        </div>
    </div>
@endsection