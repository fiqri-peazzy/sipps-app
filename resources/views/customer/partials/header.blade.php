<!-- Customer Area Header with NNCLOTHING Logo -->
<div class="bg-linear-to-r from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden mb-8">
    <!-- Background Decoration -->
    <div class="absolute inset-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Left Side - Logo & Branding -->
            <div class="space-y-6 text-center md:text-left">
                <div class="flex justify-center md:justify-start mb-6">
                    <x-nnclothing-logo type="full" size="lg" class="drop-shadow-lg" />
                </div>
                
                <div class="space-y-3">
                    <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight">
                        Selamat Datang, <span class="text-transparent bg-linear-to-r from-primary to-secondary bg-clip-text">{{ auth()->user()->name }}</span>
                    </h1>
                    <p class="text-lg text-slate-300">
                        Kelola pesanan custom apparel Anda dengan mudah dan cepat melalui dashboard modern NNCLOTHING.
                    </p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 pt-6">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-primary">{{ $totalOrders ?? 0 }}</div>
                        <div class="text-xs sm:text-sm text-slate-300 mt-1">Total Pesanan</div>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-accent">{{ $pendingOrdersCount ?? 0 }}</div>
                        <div class="text-xs sm:text-sm text-slate-300 mt-1">Menunggu Verifikasi</div>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-green-400">{{ $completedOrders ?? 0 }}</div>
                        <div class="text-xs sm:text-sm text-slate-300 mt-1">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Logo Variations Showcase -->
            <div class="space-y-8">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-8">
                    <h3 class="text-white font-bold text-lg mb-6">Logo NNCLOTHING dalam Berbagai Format</h3>
                    
                    <div class="space-y-6">
                        <!-- Full Logo -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-slate-300 font-semibold">Format Lengkap</span>
                            <x-nnclothing-logo type="full" size="sm" />
                        </div>

                        <!-- Icon Logo -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-slate-300 font-semibold">Logo Ikon</span>
                            <x-nnclothing-logo type="icon" size="sm" />
                        </div>

                        <!-- Horizontal Logo -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-slate-300 font-semibold">Format Horizontal</span>
                            <x-nnclothing-logo type="horizontal" size="sm" />
                        </div>

                        <!-- Premium Logo -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-slate-300 font-semibold">Format Premium</span>
                            <x-nnclothing-logo type="premium" size="sm" />
                        </div>

                        <!-- Minimalist Logo -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-slate-300 font-semibold">Format Minimalis</span>
                            <x-nnclothing-logo type="minimalist" size="sm" />
                        </div>
                    </div>

                    <a href="{{ route('home') }}/customer/logo-examples"
                        class="w-full mt-6 inline-flex items-center justify-center gap-2 px-6 py-3 bg-linear-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                        <i class="lni lni-eye"></i> Lihat Semua Contoh
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
