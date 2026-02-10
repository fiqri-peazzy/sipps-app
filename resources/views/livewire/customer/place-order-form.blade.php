<div class="animate-in fade-in duration-700">
    <form wire:submit.prevent="submit">
        @if ($errors->any())
            <div
                class="mb-8 p-6 rounded-4xl bg-red-50 border border-red-100 flex items-start gap-4 animate-in slide-in-from-top-4 duration-500">
                <div
                    class="h-10 w-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-200">
                    <i class="lni lni-warning text-xl"></i>
                </div>
                <div>
                    <h6 class="text-base font-black text-red-900">Pesanan Belum Lengkap</h6>
                    <p class="text-sm text-red-700 font-medium mt-1">Harap periksa kembali isian formulir Anda:</p>
                    <ul class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2 text-xs text-red-600 font-bold">
                                <span class="h-1 w-1 rounded-full bg-red-400"></span> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div
                class="mb-8 p-6 rounded-4xl bg-red-50 border border-red-100 flex items-center gap-4 animate-in zoom-in-95">
                <div class="h-10 w-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0">
                    <i class="lni lni-warning text-xl"></i>
                </div>
                <p class="text-sm font-bold text-red-900">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <!-- Left Column: Form Details -->
            <div class="lg:col-span-8 space-y-10">

                <!-- Order Items Card Section -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <i class="lni lni-package text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Pesanan</h2>
                        </div>
                        <button type="button" wire:click="addItem"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-dashed border-primary/30 text-primary font-bold text-sm hover:bg-primary/5 transition-all">
                            <i class="lni lni-plus"></i> Tambah Item
                        </button>
                    </div>

                    @foreach ($orderItems as $index => $item)
                        <div
                            class="group relative bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 p-8">
                            <!-- Remove Button -->
                            @if (count($orderItems) > 1)
                                <button type="button" wire:click="removeItem({{ $item['id'] }})"
                                    class="absolute top-6 right-6 h-10 w-10 rounded-full bg-red-50 text-red-500 opacity-0 group-hover:opacity-100 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center transform scale-90 group-hover:scale-100">
                                    <i class="lni lni-trash-can"></i>
                                </button>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Jenis
                                        Sablon</label>
                                    <select wire:model.live="orderItems.{{ $index }}.jenis_sablon_id"
                                        class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-slate-700 appearance-none">
                                        <option value="">Pilih Sablon</option>
                                        @foreach ($jenisSablons as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("orderItems.$index.jenis_sablon_id")
                                        <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Pilih
                                        Produk</label>
                                    <select wire:model.live="orderItems.{{ $index }}.produk_id"
                                        class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-slate-700 appearance-none">
                                        <option value="">Pilih Ukuran & Layanan</option>
                                        @if (isset($item['jenis_sablon_id']))
                                            @php $jenis = $jenisSablons->find($item['jenis_sablon_id']); @endphp
                                            @if ($jenis)
                                                @foreach ($jenis->produks as $produk)
                                                    <option value="{{ $produk->id }}">
                                                        {{ $produk->ukuran->nama }} - {{ $produk->tipe_layanan_label }}
                                                        ({{ $produk->formatted_harga }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                    @error("orderItems.$index.produk_id")
                                        <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black uppercase tracking-widest text-slate-400">Jumlah</label>
                                        <input type="number" wire:model.live="orderItems.{{ $index }}.quantity"
                                            min="1"
                                            class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-bold text-slate-700">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black uppercase tracking-widest text-slate-400">Ukuran</label>
                                        <select wire:model="orderItems.{{ $index }}.ukuran_kaos"
                                            class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-bold text-slate-700 appearance-none ukuran-kaos-select"
                                            data-index="{{ $index }}">
                                            @foreach (['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-black uppercase tracking-widest text-slate-400">Tipe Lengan</label>
                                        <select wire:model.live="orderItems.{{ $index }}.tipe_lengan"
                                            class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-bold text-slate-700 appearance-none tipe-lengan-select"
                                            data-index="{{ $index }}">
                                            <option value="pendek">Lengan Pendek</option>
                                            <option value="panjang">Lengan Panjang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Total
                                        Biaya Item</label>
                                    <div
                                        class="h-14 bg-primary/5 rounded-2xl px-6 flex items-center font-black text-primary text-lg">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Design Actions -->
                            <div
                                class="mt-8 pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center gap-6">
                                <div class="flex-1">
                                    <h5 class="text-sm font-bold text-slate-700">Kustomisasi Desain</h5>
                                    <p class="text-xs text-slate-500">Unggah aset desain atau gunakan alat bantu editor
                                        kami.</p>
                                </div>

                                <button type="button"
                                    class="w-full md:w-auto btn-premium bg-slate-900! rounded-2xl! flex items-center gap-2 group btn-open-design-editor"
                                    data-item-index="{{ $index }}" wire:key="design-btn-{{ $index }}"
                                    @if (!isset($item['produk_id']) || empty($item['produk_id'])) disabled @endif>
                                    <i class="lni lni-brush group-hover:rotate-12 transition-transform"></i>
                                    {{ isset($item['design_config']) && $item['design_config'] ? 'Edit Desain Kaos' : 'Mulai Desain Kaos' }}
                                </button>
                            </div>

                            @if (isset($item['design_config']) && $item['design_config'])
                                <div
                                    class="mt-4 p-4 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                                            <i class="lni lni-checkmark"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-green-700">Desain Siap Produksi</p>
                                            <p class="text-[10px] text-green-600 font-medium uppercase tracking-widest">
                                                Warna: {{ $item['design_config']['warna_kaos'] }} | Ukuran:
                                                {{ $item['design_config']['ukuran_kaos'] }}</p>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="clearDesign({{ $index }})"
                                        wire:confirm="Yakin ingin menghapus desain ini?"
                                        class="text-green-700 hover:text-red-500">
                                        <i class="lni lni-trash"></i>
                                    </button>
                                </div>
                                <input type="hidden" class="design-config-data" data-item-index="{{ $index }}"
                                    value="{{ json_encode($item['design_config']) }}">
                            @else
                                <input type="hidden" class="design-config-data" data-item-index="{{ $index }}"
                                    value="">
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Shipping Details Card Section -->
                <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm space-y-8">
                    <div class="inline-flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="lni lni-map-marker text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Informasi Pengiriman</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Nama
                                Penerima</label>
                            <input type="text" wire:model="penerima_nama" placeholder="Contoh: Siti Nurhaliza"
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                            @error('penerima_nama')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Nomor
                                WhatsApp/Telepon</label>
                            <input type="text" wire:model="penerima_telepon" placeholder="Contoh: 081234567890"
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold">
                            @error('penerima_telepon')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Provinsi</label>
                            <select wire:model.live="provinsi_id"
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold appearance-none">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                @endforeach
                            </select>
                            @error('provinsi')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-black uppercase tracking-widest text-slate-400">Kota/Kabupaten</label>
                            <select wire:model.live="kota_id" {{ empty($cities) ? 'disabled' : '' }}
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold appearance-none disabled:opacity-50">
                                <option value="">{{ $loadingCities ? 'Memuat...' : 'Pilih Kota' }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                                @endforeach
                            </select>
                            @error('kota')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-black uppercase tracking-widest text-slate-400">Kecamatan</label>
                            <select wire:model.live="district_id" {{ empty($districts) ? 'disabled' : '' }}
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold appearance-none disabled:opacity-50">
                                <option value="">{{ $loadingDistricts ? 'Memuat...' : 'Pilih Kecamatan' }}
                                </option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Kelurahan
                                (Opsional)</label>
                            <select wire:model.live="subdistrict_id" {{ empty($subdistricts) ? 'disabled' : '' }}
                                class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold appearance-none disabled:opacity-50">
                                <option value="">{{ $loadingSubdistricts ? 'Memuat...' : 'Pilih Kelurahan' }}
                                </option>
                                @foreach ($subdistricts as $sub)
                                    <option value="{{ $sub['id'] }}">{{ $sub['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Alamat
                                Lengkap</label>
                            <textarea wire:model="alamat_lengkap" rows="3" placeholder="Jl. Raya No. 123, RT 01/RW 02..."
                                class="w-full bg-slate-50 border-none rounded-2xl p-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold"></textarea>
                            @error('alamat_lengkap')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Catatan Pesanan
                                (Opsional)</label>
                            <textarea wire:model="catatan" rows="2" placeholder="Contoh: Tolong bungkus plastik masing-masing kaos..."
                                class="w-full bg-slate-50 border-none rounded-2xl p-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold"></textarea>
                            @error('catatan')
                                <span class="text-xs text-red-500 font-bold ml-4">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Courier Selection -->
                    @if ($tipe_pengiriman === 'antar_kota' && !empty($courierOptions))
                        <div class="pt-8 border-t border-slate-50">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-6">Pilih
                                Layanan Kurir</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($courierOptions as $index => $courier)
                                    <label
                                        class="relative flex flex-col p-6 rounded-3xl border-2 transition-all cursor-pointer {{ $kurir_code === $courier['code'] && $kurir_service === $courier['service'] ? 'border-primary bg-primary/5' : 'border-slate-100 hover:border-primary/30' }}">
                                        <input type="radio" wire:click="selectCourier({{ $index }})"
                                            class="absolute top-6 right-6 h-5 w-5 text-primary border-slate-300 focus:ring-primary"
                                            {{ $kurir_code === $courier['code'] && $kurir_service === $courier['service'] ? 'checked' : '' }}>
                                        <span
                                            class="text-sm font-black text-slate-800 uppercase tracking-widest mb-1">{{ $courier['name'] }}</span>
                                        <span
                                            class="text-xs font-bold text-slate-400 mb-4">{{ $courier['service'] }}</span>
                                        <span class="text-xl font-black text-primary mb-2">Rp
                                            {{ number_format($courier['cost'], 0, ',', '.') }}</span>
                                        <div
                                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-green-600">
                                            <i class="lni lni-timer"></i> {{ $courier['etd'] }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Order Summary (Sticky) -->
            <div class="lg:col-span-4 lg:sticky lg:top-28">
                <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-primary/20 space-y-10">
                    <div class="space-y-2">
                        <h2 class="text-2xl font-black tracking-tight italic">Checkout</h2>
                        <div class="h-1 w-12 bg-primary rounded-full"></div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm font-medium text-slate-400">
                            <span>Total Item ({{ count($orderItems) }})</span>
                            <span class="text-white font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-slate-400">
                            <span>Berat Estimasi</span>
                            <span class="text-white font-bold">{{ $totalWeight }}g</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-slate-400">
                            <span>Biaya Kirim</span>
                            <span class="text-white font-bold">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-6 border-t border-slate-800 flex justify-between items-end">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Total
                                    Pembayaran</span>
                                <div class="text-3xl font-black text-white">Rp
                                    {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-primary hover:bg-primary-dark text-white h-16 rounded-[1.25rem] font-black text-lg shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-3">
                        <span wire:loading.remove class="flex items-center gap-3">
                            Konfirmasi Pesanan <i class="lni lni-chevron-right"></i>
                        </span>
                        <span wire:loading class="flex items-center gap-3">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>

                    <div
                        class="pt-6 flex items-center justify-center gap-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                        <div class="flex items-center gap-1"><i class="lni lni-shield"></i> Aman</div>
                        <div class="h-1 w-1 bg-slate-700 rounded-full"></div>
                        <div class="flex items-center gap-1"><i class="lni lni-timer"></i> Terjadwal</div>
                    </div>
                </div>

                <div class="mt-8 p-6 rounded-4xl bg-amber-50 border border-amber-100 flex items-start gap-4">
                    <i class="lni lni-information text-amber-500 text-xl mt-1"></i>
                    <div>
                        <h6 class="text-sm font-bold text-amber-800">Catatan Produksi</h6>
                        <p class="text-xs text-amber-700 leading-relaxed mt-1">Estimasi pengerjaan dihitung sejak
                            pembayaran dikonfirmasi oleh sistem kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Design Editor -->
    <div class="fixed inset-0 z-100 hidden overflow-y-auto" id="designEditorModal" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-0 md:p-6">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl transition-opacity"
                onclick="closeDesignModal()"></div>

            <div
                class="relative w-full h-full md:h-auto md:max-w-6xl bg-white md:rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-500">
                <div class="bg-slate-900 p-6 md:p-8 text-white flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-primary flex items-center justify-center text-2xl">
                            <i class="lni lni-brush"></i>
                        </div>
                        <div>
                            <h4 class="text-xl text-white flex items-center gap-2" id="modal-item-title">Design
                                Workspace <span class="hidden md:inline text-slate-500 font-medium">| SIPPS
                                    Interactive</span></h4>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Sesuaikan desain
                                kaos anda dengan mudah</p>
                        </div>
                    </div>
                    <button type="button"
                        class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20 transition-colors"
                        onclick="closeDesignModal()">
                        <i class="lni lni-close text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 md:p-10 bg-slate-50">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 md:p-8">
                        @include('customer.partials.design-editor')
                    </div>
                </div>

                <div
                    class="p-6 md:p-8 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-3 text-slate-400 text-sm font-medium">
                        <i class="lni lni-save text-xl"></i> Desain tersimpan otomatis di browser selama sesi aktif
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <button type="button"
                            class="flex-1 md:flex-none px-10 py-4 rounded-2xl font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all"
                            onclick="closeDesignModal()">Urungkan</button>
                        <button type="button" id="btn-save-design-final"
                            class="flex-1 md:flex-none btn-premium px-12!">Terapkan Desain</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.DesignEditorConfig = {
            baseUrl: "{{ asset('frontend/assets/img/kaos-templates') }}/"
        };
    </script>
    <script src="{{ asset('frontend/assets/js/design-editor.js') }}"></script>
    <script>
        var currentItemIndex = null;

        function closeDesignModal() {
            $('#designEditorModal').addClass('hidden');
            $('body').removeClass('overflow-hidden');
            currentItemIndex = null;
        }

        $(document).on('click', '.btn-open-design-editor', function() {
            if ($(this).is(':disabled')) return;
            var itemIndex = parseInt($(this).data('item-index'));
            console.log('Opening design editor for item index:', itemIndex);

            currentItemIndex = itemIndex;
            
            // Sync current size and sleeve from form
            var $sizeSelect = $('.ukuran-kaos-select[data-index="' + itemIndex + '"]');
            var $sleeveSelect = $('.tipe-lengan-select[data-index="' + itemIndex + '"]');
            
            var currentSize = $sizeSelect.val() || 'M';
            var currentSleeve = $sleeveSelect.val() || 'pendek';

            var existingConfigStr = $('.design-config-data[data-item-index="' + itemIndex + '"]').val();
            var existingConfig = null;
            if (existingConfigStr && existingConfigStr !== '' && existingConfigStr !== 'null') {
                try {
                    existingConfig = JSON.parse(existingConfigStr);
                } catch (e) {
                    console.error('Error parsing config:', e);
                }
            } else {
                // Initial config if none exists
                existingConfig = {
                    ukuran_kaos: currentSize,
                    tipe_lengan: currentSleeve,
                    warna_kaos: 'putih'
                };
            }

            // Ensure config is updated with current form values
            if (existingConfig) {
                existingConfig.ukuran_kaos = currentSize;
                existingConfig.tipe_lengan = currentSleeve;
            }

            $(this).data('parsed-config', existingConfig);

            var $modal = $('#designEditorModal');
            if ($modal.length) {
                $modal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
                console.log('Modal element found and show command executed');
            } else {
                console.error('Modal element #designEditorModal not found in DOM!');
            }

            setTimeout(function() {
                if (typeof DesignEditor !== 'undefined' && typeof fabric !== 'undefined') {
                    DesignEditor.init(currentItemIndex, existingConfig);
                } else {
                    console.error('DesignEditor or Fabric.js not found during initialization');
                }
            }, 300);
        });

        $(document).on('click', '#btn-save-design-final', function() {
            if (typeof DesignEditor === 'undefined' || currentItemIndex === null) return;
            var itemIndex = DesignEditor.itemIndex;
            var designConfig = DesignEditor.getDesignConfig();

            $(this).prop('disabled', true).html(
                '<i class="lni lni-spinner-arrow animate-spin mr-2"></i> Menyimpan...');

            @this.handleDesignConfigSaved(itemIndex, designConfig)
                .then(function() {
                    $('.design-config-data[data-item-index="' + itemIndex + '"]').val(JSON.stringify(
                        designConfig));
                    closeDesignModal();
                })
                .finally(function() {
                    $('#btn-save-design-final').prop('disabled', false).text('Terapkan Desain');
                });
        });
    </script>
@endpush
