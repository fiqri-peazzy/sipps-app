<!-- NNCLOTHING Customer Footer Component -->
<footer class="bg-linear-to-b from-slate-50 to-slate-100 border-t border-slate-200 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Brand Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <x-nnclothing-logo type="icon" size="sm" />
                    <div>
                        <h3 class="font-black text-slate-900">{{ config('branding.brand.name') }}</h3>
                        <p class="text-xs text-slate-500">Premium Custom Apparel</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ config('branding.brand.description') }}
                </p>
                <div class="flex items-center gap-3">
                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-primary hover:bg-primary/10 transition-all">
                        <i class="lni lni-facebook-filled text-lg"></i>
                    </a>
                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-accent hover:bg-accent/10 transition-all">
                        <i class="lni lni-instagram-filled text-lg"></i>
                    </a>
                    <a href="#"
                        class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-green-500 hover:bg-green-50 transition-all">
                        <i class="lni lni-whatsapp text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-black text-slate-900 mb-6">Navigasi</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}"
                            class="text-slate-600 hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="{{ route('home') }}#layanan"
                            class="text-slate-600 hover:text-primary transition-colors">Layanan Kami</a></li>
                    <li><a href="{{ route('home') }}#portfolio"
                            class="text-slate-600 hover:text-primary transition-colors">Galeri</a></li>
                    <li><a href="{{ route('home') }}#faq"
                            class="text-slate-600 hover:text-primary transition-colors">FAQ</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="font-black text-slate-900 mb-6">Layanan</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-slate-600 hover:text-primary transition-colors">Sablon DTF</a>
                    </li>
                    <li><a href="#" class="text-slate-600 hover:text-primary transition-colors">Sablon Manual</a>
                    </li>
                    <li><a href="#" class="text-slate-600 hover:text-primary transition-colors">Sublimation</a>
                    </li>
                    <li><a href="#" class="text-slate-600 hover:text-primary transition-colors">Polyflex</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-black text-slate-900 mb-6">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="lni lni-map-marker text-primary mt-0.5 flex-shrink-0"></i>
                        <span
                            class="text-slate-600">{{ config('branding.brand.social.address', 'Jl. Jenderal Sudirman No. 123, Manado') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="lni lni-phone text-primary flex-shrink-0"></i>
                        <span
                            class="text-slate-600">{{ config('branding.brand.social.whatsapp', '+62 812 3456 7890') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="lni lni-envelope text-primary flex-shrink-0"></i>
                        <span class="text-slate-600">{{ config('branding.brand.support_email') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-slate-200"></div>

        <!-- Bottom Footer -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <!-- Copyright -->
            <div class="text-center md:text-left">
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ config('branding.brand.name') }}. All Rights Reserved.
                </p>
            </div>

            <!-- Brand Logos -->
            <div class="flex items-center justify-center md:justify-end gap-4">
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest">Powered by:</p>
                <x-nnclothing-logo type="icon" size="sm" class="opacity-75" />
            </div>
        </div>
    </div>
</footer>
