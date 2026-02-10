<div class="animate-in fade-in duration-700">
    <div class="max-w-4xl mx-auto">
        <div class="card-modern">
            <div class="inline-flex items-center gap-3 mb-8">
                <div class="h-10 w-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="lni lni-reload text-xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Form Pengembalian Barang</h2>
            </div>

            <!-- Summary Order Ref -->
            <div
                class="bg-amber-50 rounded-2xl p-6 border border-amber-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Nomor Pesanan</p>
                    <h4 class="text-xl font-black text-amber-900">{{ $order->order_number }}</h4>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Batas Waktu Return</p>
                    <p class="text-xs font-bold text-amber-900 uppercase">
                        {{ $order->completed_at->addDays(7)->format('d F Y') }}
                    </p>
                </div>
            </div>

            <form wire:submit.prevent="submit" class="space-y-10">
                <!-- Item Selection -->
                <div class="space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-2">Pilih Item
                        Bermasalah (Bisa pilih lebih dari satu)</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($order->items as $item)
                            <label
                                class="relative flex items-center gap-6 p-6 rounded-3xl border-2 transition-all cursor-pointer {{ in_array($item->id, $selectedItemIds) ? 'border-primary bg-primary/5' : 'border-slate-50 hover:border-slate-100' }}">
                                <input type="checkbox" wire:model.live="selectedItemIds" value="{{ $item->id }}"
                                    class="h-5 w-5 text-primary border-slate-300 rounded focus:ring-primary">
                                <div class="flex-1">
                                    <h6 class="font-black text-slate-900">{{ $item->produk->jenisSablon->nama }}</h6>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        {{ $item->produk->ukuran->nama }} | Qty: {{ $item->quantity }}
                                        @if ($item->ukuran_kaos)
                                            | Size: {{ $item->ukuran_kaos }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="font-black text-slate-900">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedItemIds')
                        <p class="text-xs text-red-500 font-bold ml-4">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Kategori
                            Alasana</label>
                        <select wire:model.live="reason"
                            class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 focus:ring-2 focus:ring-primary/20 transition-all font-bold text-slate-700 appearance-none">
                            <option value="">Pilih Alasan</option>
                            <option value="wrong_size">Ukuran Salah</option>
                            <option value="wrong_color">Warna Tidak Sesuai</option>
                            <option value="print_quality">Kualitas Cetakan Buruk</option>
                            <option value="damage">Produk Rusak/Cacat</option>
                            <option value="not_as_described">Tidak Sesuai Deskripsi</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('reason')
                            <p class="text-xs text-red-500 font-bold ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason Details -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Jelaskan Masalah Secara
                        Lengkap</label>
                    <textarea wire:model="reasonDetail" rows="4"
                        placeholder="Contoh: Terdapat noda tinta pada bagian bahu kanan, atau ukuran XL terasa seperti M..."
                        class="w-full bg-slate-50 border-none rounded-3xl p-6 focus:ring-2 focus:ring-primary/20 transition-all font-semibold"></textarea>
                    @error('reasonDetail')
                        <p class="text-xs text-red-500 font-bold ml-4">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Evidence Upload -->
                <div class="space-y-6">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Unggah Foto Bukti (Maks.
                        5 Foto)</label>

                    <div class="flex flex-wrap gap-4">
                        @foreach ($evidencePhotos as $index => $photo)
                            <div class="relative h-32 w-32 rounded-3xl overflow-hidden group">
                                <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full object-cover">
                                <button type="button" wire:click="removePhoto({{ $index }})"
                                    class="absolute inset-0 bg-red-500/80 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="lni lni-trash-can text-2xl"></i>
                                </button>
                            </div>
                        @endforeach

                        @if (count($evidencePhotos) < 5)
                            <label
                                class="h-32 w-32 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 hover:border-primary hover:bg-primary/5 hover:text-primary transition-all cursor-pointer">
                                <input type="file" wire:model="evidencePhotos" class="hidden" multiple accept="image/*">
                                <i class="lni lni-plus text-2xl mb-1"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Tambah</span>
                            </label>
                        @endif
                    </div>

                    <div wire:loading wire:target="evidencePhotos"
                        class="flex items-center gap-2 text-xs font-bold text-primary animate-pulse">
                        <i class="lni lni-spinner-arrow animate-spin"></i> Sedang mengunggah...
                    </div>
                </div>

                <!-- Terms -->
                <div class="p-8 rounded-3xl bg-slate-900 text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 h-40 w-40 bg-white/5 rounded-full blur-2xl text-white"></div>
                    <h6 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-6">Ketentuan
                        Pengembalian</h6>
                    <ul class="space-y-3 text-xs font-medium text-slate-400">
                        <li class="flex items-start gap-3"><i class="lni lni-checkmark-circle text-primary mt-0.5"></i>
                            Foto bukti harus jelas dan menunjukkan area cacat/masalah.</li>
                        <li class="flex items-start gap-3"><i class="lni lni-checkmark-circle text-primary mt-0.5"></i>
                            Barang akan diganti dengan produk baru (No Refund).</li>
                        <li class="flex items-start gap-3"><i class="lni lni-checkmark-circle text-primary mt-0.5"></i>
                            Admin akan meninjau pengajuan Anda dalam 1x24 jam.</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div
                    class="pt-10 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('customer.orders.show', $order->id) }}"
                        class="flex items-center gap-2 font-black text-sm text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                        <i class="lni lni-arrow-left"></i> Kembali ke Detail
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                        class="btn-premium bg-amber-500! hover:bg-amber-600! shadow-amber-500/20! w-full md:w-auto px-12! flex items-center gap-3">
                        <span wire:loading.remove>Kirim Pengajuan</span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="lni lni-spinner-arrow animate-spin"></i> Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>