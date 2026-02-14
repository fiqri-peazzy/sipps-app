<div class="design-editor-container" id="designEditorContainer" data-item-index="{{ $itemIndex }}">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Canvas Preview -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 h-full flex flex-col">
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-50">
                    <h5 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="lni lni-eye text-primary"></i> Preview Desain
                    </h5>

                    <!-- Area Selector -->
                    <div class="flex flex-wrap gap-2 p-1 bg-slate-100 rounded-2xl w-fit">
                        <button type="button"
                            class="area-btn px-4 py-2 rounded-xl text-xs font-bold transition-all active bg-white text-primary shadow-sm"
                            data-area="front">
                            Depan
                        </button>
                        <button type="button"
                            class="area-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-700"
                            data-area="back">
                            Belakang
                        </button>
                        <button type="button"
                            class="area-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-700"
                            data-area="left_sleeve">
                            Lengan Kiri
                        </button>
                        <button type="button"
                            class="area-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-700"
                            data-area="right_sleeve">
                            Lengan Kanan
                        </button>
                    </div>
                </div>

                <!-- Canvas Preview Area Area -->
                <div
                    class="relative bg-slate-50 flex-1 min-h-[400px] md:min-h-[500px] flex items-center justify-center p-4">
                    <!-- Front -->
                    <div class="canvas-area relative w-full aspect-[3/4] max-w-[500px] mx-auto flex items-center justify-center" data-area="front"
                        style="display: flex;">
                        <img src="{{ asset('frontend/assets/img/kaos-templates/putih-front.png') }}" alt="Kaos Depan"
                            class="kaos-template absolute inset-0 w-full h-full object-contain z-10 pointer-events-none"
                            data-area="front" crossorigin="anonymous" onerror="this.style.display='none'">
                        <canvas id="canvas-front" width="1000" height="1333" class="relative z-20"></canvas>
                    </div>

                    <!-- Back -->
                    <div class="canvas-area relative w-full aspect-[3/4] max-w-[500px] mx-auto flex items-center justify-center" data-area="back"
                        style="display: none;">
                        <img src="{{ asset('frontend/assets/img/kaos-templates/putih-back.png') }}" alt="Kaos Belakang"
                            class="kaos-template absolute inset-0 w-full h-full object-contain z-10 pointer-events-none"
                            data-area="back" crossorigin="anonymous" onerror="this.style.display='none'">
                        <canvas id="canvas-back" width="1000" height="1333" class="relative z-20"></canvas>
                    </div>

                    <!-- Left Sleeve -->
                    <div class="canvas-area relative w-full aspect-[3/4] max-w-[500px] mx-auto flex items-center justify-center"
                        data-area="left_sleeve" style="display: none;">
                        <div
                            class="absolute inset-x-8 inset-y-12 border-4 border-dashed border-slate-200 rounded-3xl flex items-center justify-center bg-white/50 z-10 pointer-events-none">
                            <span class="text-slate-400 font-bold uppercase tracking-widest text-xs">Area Lengan
                                Kiri</span>
                        </div>
                        <canvas id="canvas-left_sleeve" width="1000" height="1333" class="relative z-20"></canvas>
                    </div>

                    <!-- Right Sleeve -->
                    <div class="canvas-area relative w-full aspect-[3/4] max-w-[500px] mx-auto flex items-center justify-center"
                        data-area="right_sleeve" style="display: none;">
                        <div
                            class="absolute inset-x-8 inset-y-12 border-4 border-dashed border-slate-200 rounded-3xl flex items-center justify-center bg-white/50 z-10 pointer-events-none">
                            <span class="text-slate-400 font-bold uppercase tracking-widest text-xs">Area Lengan
                                Kanan</span>
                        </div>
                        <canvas id="canvas-right_sleeve" width="1000" height="1333" class="relative z-20"></canvas>
                    </div>
                </div>

                <!-- Canvas Tools -->
                <div class="mt-6 pt-6 border-t border-slate-50 flex flex-wrap gap-3">
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button type="button"
                            class="px-4 py-2 rounded-lg text-slate-600 hover:bg-white hover:text-primary transition-all text-xs font-bold flex items-center gap-2"
                            id="btn-bring-front">
                            <i class="lni lni-layers"></i> <span class="hidden sm:inline">Ke Depan</span>
                        </button>
                        <button type="button"
                            class="px-4 py-2 rounded-lg text-slate-600 hover:bg-white hover:text-primary transition-all text-xs font-bold flex items-center gap-2"
                            id="btn-send-back">
                            <i class="lni lni-layers"></i> <span class="hidden sm:inline">Ke Belakang</span>
                        </button>
                    </div>
                    <button type="button"
                        class="px-4 py-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all text-xs font-bold flex items-center gap-2"
                        id="btn-delete">
                        <i class="lni lni-trash-can"></i> Hapus Objek
                    </button>
                    <button type="button"
                        class="ml-auto px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-all text-xs font-bold flex items-center gap-2"
                        id="btn-reset">
                        <i class="lni lni-reload"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Design Controls -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Alert Messages -->
            <div id="design-alert"
                class="hidden animate-in fade-in slide-in-from-top-2 p-4 rounded-2xl text-sm font-bold shadow-lg"
                role="alert">
                <span id="design-alert-message"></span>
            </div>

            <!-- Kaos Settings -->
            <div class="bg-white rounded-4xl p-6 shadow-sm border border-slate-100">
                <h6 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-6">
                    <i class="lni lni-shirt text-primary"></i> Pengaturan Kaos
                </h6>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Warna
                            Dasar</label>
                        <div class="grid grid-cols-4 gap-3">
                            @php
                                $colors = [
                                    'putih' => ['hex' => '#FFFFFF', 'border' => 'border-slate-200'],
                                    'hitam' => ['hex' => '#000000', 'border' => 'border-black'],
                                    'merah' => ['hex' => '#EF4444', 'border' => 'border-red-500'],
                                    'biru' => ['hex' => '#3B82F6', 'border' => 'border-blue-500'],
                                    'hijau' => ['hex' => '#10B981', 'border' => 'border-emerald-500'],
                                    'kuning' => ['hex' => '#F59E0B', 'border' => 'border-amber-500'],
                                    'navy' => ['hex' => '#1e3a8a', 'border' => 'border-blue-900'],
                                ];
                            @endphp
                            @foreach ($colors as $name => $data)
                                <div class="color-option group relative h-10 w-full rounded-lg cursor-pointer transition-all hover:scale-110 {{ $name == 'putih' ? 'active' : '' }}"
                                    data-color="{{ $name }}" title="{{ ucfirst($name) }}"
                                    style="background-color: {{ $data['hex'] }}; border: 1px solid rgba(0,0,0,0.1);">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-[.active]:opacity-100 transition-opacity pointer-events-none">
                                        <i
                                            class="lni lni-checkmark text-xs {{ $name == 'putih' || $name == 'kuning' ? 'text-slate-900' : 'text-white' }}"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- UI Addon: Tools -->
            <div class="bg-slate-900 rounded-4xl p-6 text-white shadow-xl shadow-slate-200">
                <div class="space-y-6">
                    <!-- Upload Tool -->
                    <div class="space-y-4">
                        <h6
                            class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-2">
                            <i class="lni lni-cloud-upload"></i> Assets / File
                        </h6>
                        <div class="relative group">
                            <input type="file" id="upload-image" class="hidden" accept="image/*">
                            <label for="upload-image"
                                class="flex flex-col items-center justify-center gap-3 p-6 rounded-2xl border-2 border-dashed border-slate-700 hover:border-primary hover:bg-primary/5 transition-all cursor-pointer">
                                <i class="lni lni-image text-2xl text-slate-600 group-hover:text-primary"></i>
                                <span class="text-xs font-bold text-slate-400 group-hover:text-primary">Klik untuk
                                    upload gambar</span>
                            </label>
                        </div>
                        <button type="button"
                            class="w-full py-4 rounded-xl bg-primary hover:bg-primary-dark text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2"
                            id="btn-upload">
                            <i class="lni lni-upload"></i> Tambahkan
                        </button>
                    </div>

                    <div class="h-px bg-slate-800"></div>

                    <!-- Text Tool -->
                    <div class="space-y-4">
                        <h6
                            class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-2">
                            <i class="lni lni-text-format"></i> Typography
                        </h6>
                        <input type="text" id="text-input" placeholder="Tulis teks disini..."
                            class="w-full h-12 bg-slate-800 border-none rounded-xl px-4 text-sm font-bold placeholder:text-slate-600 focus:ring-2 focus:ring-primary/50 transition-all">
                        <button type="button"
                            class="w-full py-4 rounded-xl bg-white text-slate-900 hover:bg-slate-100 font-black text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2"
                            id="btn-add-text">
                            <i class="lni lni-plus"></i> Tambah Teks
                        </button>
                    </div>
                </div>
            </div>

            <!-- Design Summary -->
            <div class="bg-white rounded-4xl p-6 shadow-sm border border-slate-100">
                <h6 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-4">
                    <i class="lni lni-layers text-primary"></i> Layer Info
                </h6>
                <div class="space-y-2">
                    @foreach (['front' => 'Depan', 'back' => 'Belakang', 'left_sleeve' => 'Lengan Kiri', 'right_sleeve' => 'Lengan Kanan'] as $area => $label)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl summary-item"
                            data-summary-area="{{ $area }}">
                            <span class="text-xs font-bold text-slate-600">{{ $label }}</span>
                            <span
                                class="text-[10px] font-black uppercase px-2 py-1 rounded-lg border border-slate-100 text-slate-400 bg-white status-badge">
                                Belum ada
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .canvas-area canvas {
        cursor: crosshair;
        width: 100% !important;
        height: 100% !important;
    }

    .canvas-area .canvas-container {
        position: absolute !important;
        inset: 0;
        width: 100% !important;
        height: 100% !important;
        z-index: 20;
    }

    .color-option.active {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }
</style>