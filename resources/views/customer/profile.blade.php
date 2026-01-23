@extends('layouts.customer')

@section('customer-title', 'Profil Saya')
@section('customer-subtitle', 'Kelola informasi pribadi dan keamanan akun Anda.')

@section('customer-content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            <div class="card-modern">
                <div class="flex items-center gap-3 mb-10 pb-6 border-b border-slate-50">
                    <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <i class="lni lni-user text-xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900">Informasi Pribadi</h4>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Nama
                                Lengkap</label>
                            <div
                                class="h-14 bg-slate-50 rounded-2xl px-6 flex items-center font-bold text-slate-700 border border-transparent">
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Alamat
                                Email</label>
                            <div
                                class="h-14 bg-slate-50 rounded-2xl px-6 flex items-center font-bold text-slate-700 border border-transparent">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Nomor
                                Telepon</label>
                            <div
                                class="h-14 bg-slate-50 rounded-2xl px-6 flex items-center font-bold text-slate-700 border border-transparent">
                                {{ auth()->user()->phone ?? 'Belum diatur' }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Terdaftar
                                Sejak</label>
                            <div
                                class="h-14 bg-slate-50 rounded-2xl px-6 flex items-center font-bold text-slate-700 border border-transparent">
                                {{ auth()->user()->created_at->format('d F Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Alamat
                            Default</label>
                        <div
                            class="p-6 bg-slate-50 rounded-3xl font-bold text-slate-700 border border-transparent leading-relaxed">
                            {{ auth()->user()->address ?? 'Alamat belum ditambahkan. Silakan lengkapi profil Anda.' }}
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50 flex justify-end">
                        <button class="btn-premium">
                            <i class="lni lni-pencil mr-2"></i> Perbarui Profil
                        </button>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="card-modern group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                            <i class="lni lni-lock text-xl"></i>
                        </div>
                        <div>
                            <h5 class="font-black text-slate-900">Keamanan Akun</h5>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Ubah kata sandi anda
                                secara berkala</p>
                        </div>
                    </div>
                    <button
                        class="h-10 px-6 rounded-xl bg-slate-900 text-white text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-colors">
                        Ganti Password
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="lg:col-span-4 space-y-6">
            <div class="card-modern text-center">
                <div class="relative inline-block mb-6">
                    <div
                        class="h-32 w-32 rounded-[2.5rem] bg-gradient-to-br from-primary via-secondary to-accent p-1 rotate-3 group overflow-hidden">
                        <div
                            class="h-full w-full bg-white rounded-[2.3rem] flex items-center justify-center text-4xl font-black text-slate-300">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl bg-green-500 text-white flex items-center justify-center border-4 border-white">
                        <i class="lni lni-checkmark"></i>
                    </div>
                </div>
                <h4 class="text-2xl font-black text-slate-900">{{ auth()->user()->name }}</h4>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-2 italic">Verified Customer
                </p>
            </div>

            <div class="card-modern space-y-6">
                <h5 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Analitik Akun</h5>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Pesanan</span>
                        <span class="font-black text-slate-900 text-lg">{{ $totalOrders ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Poin Loyalitas</span>
                        <span class="font-black text-primary text-lg">0</span>
                    </div>
                    <div
                        class="flex justify-between items-center p-6 rounded-3xl bg-slate-900 text-white shadow-lg shadow-slate-200">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Total
                                Pengeluaran</span>
                            <div class="text-xl font-black">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <i class="lni lni-wallet text-3xl text-slate-700"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
