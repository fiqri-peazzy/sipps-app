<div>
    <form wire:submit.prevent="submit">
        <div class="row">
            <!-- Left Column - Order Items -->
            <div class="col-lg-8">
                <!-- Items Section -->
                <div class="section-header">
                    <h4><i class="lni lni-package"></i> Detail Pesanan</h4>
                </div>
                @foreach ($orderItems as $index => $item)
                    <div class="order-item-card">
                        @if (count($orderItems) > 1)
                            <button type="button" class="remove-item-btn" wire:click="removeItem({{ $item['id'] }})"
                                title="Hapus Item">
                                <i class="lni lni-trash-can"></i>
                            </button>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Sablon</label>
                                <select class="form-control"
                                    wire:model.live="orderItems.{{ $index }}.jenis_sablon_id">
                                    <option value="">Pilih Jenis Sablon</option>
                                    @foreach ($jenisSablons as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                    @endforeach
                                </select>
                                @error("orderItems.$index.jenis_sablon_id")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pilih Produk (Ukuran & Layanan)</label>
                                <select class="form-control" wire:model.live="orderItems.{{ $index }}.produk_id">
                                    <option value="">Pilih Produk</option>
                                    @if (isset($item['jenis_sablon_id']))
                                        @php
                                            $jenis = $jenisSablons->find($item['jenis_sablon_id']);
                                        @endphp
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
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jumlah</label>
                                <input type="number" class="form-control"
                                    wire:model.live="orderItems.{{ $index }}.quantity" min="1"
                                    placeholder="Jumlah">
                                @error("orderItems.$index.quantity")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Ukuran Kaos</label>
                                <select class="form-control" wire:model="orderItems.{{ $index }}.ukuran_kaos">
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="XXXL">XXXL</option>
                                </select>
                                @error("orderItems.$index.ukuran_kaos")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Subtotal</label>
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($item['subtotal'], 0, ',', '.') }}" readonly>
                            </div>
                            <!-- Design Button -->
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-design w-100 btn-open-design-editor"
                                    data-item-index="{{ $index }}" wire:key="design-btn-{{ $index }}"
                                    @if (!isset($item['produk_id']) || empty($item['produk_id'])) disabled @endif>
                                    <i class="lni lni-brush"></i>
                                    @if (isset($item['design_config']) && $item['design_config'])
                                        Edit Desain Kaos
                                    @else
                                        Desain Kaos Anda
                                    @endif
                                </button>
                                @if (!isset($item['produk_id']) || empty($item['produk_id']))
                                    <small class="text-muted d-block mt-1">Pilih produk terlebih dahulu untuk
                                        mendesain</small>
                                @endif
                                @if (isset($item['design_config']) && $item['design_config'])
                                    <div class="design-preview-badge mt-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="lni lni-checkmark-circle text-success"></i>
                                                <span class="text-success fw-bold">Desain sudah dibuat</span>
                                                <small class="d-block text-muted">
                                                    Ukuran: {{ $item['design_config']['ukuran_kaos'] ?? 'M' }} |
                                                    Warna:
                                                    {{ ucfirst($item['design_config']['warna_kaos'] ?? 'putih') }}
                                                </small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="clearDesign({{ $index }})"
                                                wire:confirm="Yakin ingin hapus desain ini?">
                                                <i class="lni lni-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" class="design-config-data"
                                        data-item-index="{{ $index }}"
                                        value="{{ json_encode($item['design_config']) }}">
                                @else
                                    <input type="hidden" class="design-config-data"
                                        data-item-index="{{ $index }}" value="">
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Catatan Item (Optional)</label>
                                <textarea class="form-control" wire:model="orderItems.{{ $index }}.catatan_item" rows="2"
                                    placeholder="Contoh: Warna merah, tulisan bold, dll"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button" class="btn btn-add-item mb-4" wire:click="addItem">
                    <i class="lni lni-plus"></i> Tambah Item Pesanan
                </button>

                <!-- Shipping Address Section -->
                <div class="section-header mt-5">
                    <h4><i class="lni lni-map-marker"></i> Alamat Pengiriman</h4>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Penerima</label>
                        <input type="text" class="form-control" wire:model="penerima_nama"
                            placeholder="Nama lengkap penerima">
                        @error('penerima_nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <input type="text" class="form-control" wire:model="penerima_telepon"
                            placeholder="08xxxxxxxxxx">
                        @error('penerima_telepon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Provinsi -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                        <select class="form-control" wire:model.live="provinsi_id">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                        @error('provinsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Kota/Kabupaten -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kota/Kabupaten <span class="text-danger">*</span></label>
                        <select class="form-control" wire:model.live="kota_id"
                            {{ empty($cities) ? 'disabled' : '' }}>
                            <option value="">
                                @if ($loadingCities)
                                    Memuat kota...
                                @else
                                    Pilih Kota
                                @endif
                            </option>
                            @foreach ($cities as $city)
                                <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                            @endforeach
                        </select>
                        @error('kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kecamatan <span class="text-danger">*</span></label>
                        <select class="form-control" wire:model.live="district_id"
                            {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">
                                @if ($loadingDistricts)
                                    Memuat kecamatan...
                                @else
                                    Pilih Kecamatan
                                @endif
                            </option>
                            @foreach ($districts as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Kelurahan (Optional) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kelurahan/Desa</label>
                        <select class="form-control" wire:model.live="subdistrict_id"
                            {{ empty($subdistricts) ? 'disabled' : '' }}>
                            <option value="">
                                @if ($loadingSubdistricts)
                                    Memuat kelurahan...
                                @else
                                    Pilih Kelurahan
                                @endif
                            </option>
                            @foreach ($subdistricts as $subdistrict)
                                <option value="{{ $subdistrict['id'] }}">{{ $subdistrict['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea class="form-control" wire:model="alamat_lengkap" rows="3"
                            placeholder="Jalan, No. Rumah, RT/RW, dll"></textarea>
                        @error('alamat_lengkap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Kode Pos</label>
                        <input type="text" class="form-control" wire:model="kode_pos" placeholder="96xxx">
                    </div>

                    <!-- Tipe Pengiriman Info -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Tipe Pengiriman</label>

                        @if ($loadingShippingCost)
                            <div class="alert alert-info">
                                <i class="lni lni-spinner-arrow spinning"></i> Menghitung ongkos kirim...
                            </div>
                        @elseif($tipe_pengiriman === 'dalam_kota')
                            <div class="alert alert-success">
                                <i class="lni lni-truck"></i> <strong>Pengiriman Dalam Kota Gorontalo</strong><br>
                                <small>Ongkir: Rp {{ number_format($ongkir, 0, ',', '.') }} (Estimasi:
                                    {{ $kurir_etd }})</small>
                            </div>
                        @elseif($tipe_pengiriman === 'antar_kota' && !empty($courierOptions))
                            <div class="alert alert-success">
                                <i class="lni lni-truck"></i> <strong>Pengiriman Antar Kota</strong><br>
                                <small>Pilih layanan pengiriman di bawah</small>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="lni lni-information"></i> Pilih kota tujuan terlebih dahulu
                            </div>
                        @endif
                    </div>

                    <!-- Pilihan Kurir (Antar Kota) -->
                    @if ($tipe_pengiriman === 'antar_kota' && !empty($courierOptions))
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Pilih Layanan Pengiriman <span
                                    class="text-danger">*</span></label>

                            <div class="row g-2">
                                @foreach ($courierOptions as $index => $courier)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="courier-option-card {{ $kurir_code === $courier['code'] && $kurir_service === $courier['service'] ? 'border-primary' : '' }}"
                                            wire:click="selectCourier({{ $index }})" style="cursor: pointer;">
                                            <input type="radio" name="courier_option" value="{{ $index }}"
                                                {{ $kurir_code === $courier['code'] && $kurir_service === $courier['service'] ? 'checked' : '' }}>
                                            <label style="pointer-events: none;">
                                                <div class="courier-name">{{ $courier['name'] }}</div>
                                                <div class="courier-service">{{ $courier['service'] }} -
                                                    {{ $courier['description'] }}</div>
                                                <div class="courier-cost">Rp
                                                    {{ number_format($courier['cost'], 0, ',', '.') }}</div>
                                                <div class="courier-etd"><i class="lni lni-timer"></i>
                                                    {{ $courier['etd'] }}</div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('kurir_code')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label fw-bold">Catatan Tambahan (Optional)</label>
                        <textarea class="form-control" wire:model="catatan" rows="2" placeholder="Catatan untuk pesanan Anda"></textarea>
                    </div>
                </div>

                <style>
                    .spinning {
                        animation: spin 1s linear infinite;
                    }

                    @keyframes spin {
                        from {
                            transform: rotate(0deg);
                        }

                        to {
                            transform: rotate(360deg);
                        }
                    }

                    .courier-option-card {
                        border: 2px solid #dee2e6;
                        border-radius: 8px;
                        padding: 15px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        position: relative;
                    }

                    .courier-option-card:hover {
                        border-color: #0d6efd;
                        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
                    }

                    .courier-option-card.border-primary {
                        border-color: #0d6efd !important;
                        background-color: #f8f9ff;
                    }

                    .courier-option-card input[type="radio"] {
                        position: absolute;
                        opacity: 0;
                    }

                    .courier-option-card label {
                        cursor: pointer;
                        margin: 0;
                        width: 100%;
                    }

                    .courier-name {
                        font-weight: bold;
                        font-size: 1rem;
                        color: #212529;
                        margin-bottom: 5px;
                    }

                    .courier-service {
                        font-size: 0.85rem;
                        color: #6c757d;
                        margin-bottom: 8px;
                    }

                    .courier-cost {
                        font-size: 1.1rem;
                        font-weight: bold;
                        color: #0d6efd;
                        margin-bottom: 5px;
                    }

                    .courier-etd {
                        font-size: 0.85rem;
                        color: #6c757d;
                    }
                </style>
            </div>

            <!-- Right Column - Price Summary -->
            <div class="col-lg-4">
                <div class="price-summary">
                    <h4 class="mb-4"><i class="lni lni-calculator"></i> Ringkasan Pesanan</h4>
                    <div class="price-row">
                        <span>Subtotal ({{ count($orderItems) }} item)</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Info Berat Total -->
                    <div class="price-row">
                        <span>Berat Total</span>
                        <strong id="total-weight-display">{{ $totalWeight }} gram</strong>
                    </div>

                    <div class="price-row">
                        <span>Ongkos Kirim</span>
                        <strong id="ongkir-display">Rp {{ number_format($ongkir, 0, ',', '.') }}</strong>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>

                    <button type="submit" class="btn btn-submit-order" wire:loading.attr="disabled"
                        id="btn-submit-order">
                        <span wire:loading.remove>
                            <i class="lni lni-checkmark-circle"></i> Buat Pesanan
                        </span>
                        <span wire:loading>
                            <i class="lni lni-spinner-arrow spinning"></i> Memproses...
                        </span>
                    </button>

                    <div class="mt-3 text-center">
                        <small style="opacity: 0.8;">
                            <i class="lni lni-lock"></i> Transaksi Aman & Terpercaya
                        </small>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- Bootstrap Modal untuk Design Editor -->
    <div class="modal fade" id="designEditorModal" tabindex="-1" aria-labelledby="designEditorModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%); color: white;">
                    <h5 class="modal-title" id="designEditorModalLabel">
                        <i class="lni lni-brush"></i> Desain Kaos - <span id="modal-item-title">Item #1</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @include('customer.partials.design-editor')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="lni lni-close"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save-design-final">
                        <i class="lni lni-save"></i> Simpan Desain
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('frontend/assets/js/design-editor.js') }}"></script>
        <script>
            console.log('===== SCRIPT INIT =====');
            var currentItemIndex = null;
            var selectedCityId = null;
            var totalWeight = {{ $totalWeight }};
            var courierOptions = [];

            // Design Editor Scripts (existing)
            $(document).on('click', '.btn-open-design-editor', function() {
                if ($(this).is(':disabled')) return;
                var itemIndex = parseInt($(this).data('item-index'));
                currentItemIndex = itemIndex;
                var existingConfigStr = $('.design-config-data[data-item-index="' + itemIndex + '"]').val();
                var existingConfig = null;

                if (existingConfigStr && existingConfigStr !== '' && existingConfigStr !== 'null') {
                    try {
                        existingConfig = JSON.parse(existingConfigStr);
                    } catch (e) {
                        console.error('Error parsing config:', e);
                    }
                }

                $('#modal-item-title').text('Item #' + (itemIndex + 1));
                $(this).data('parsed-config', existingConfig);
                $('#designEditorModal').modal('show');
            });

            $('#designEditorModal').on('shown.bs.modal', function(event) {
                if (currentItemIndex === null) return;
                var existingConfig = $('.btn-open-design-editor[data-item-index="' + currentItemIndex + '"]').data(
                    'parsed-config');

                setTimeout(function() {
                    if (typeof DesignEditor !== 'undefined' && typeof fabric !== 'undefined') {
                        DesignEditor.init(currentItemIndex, existingConfig);
                    }
                }, 300);
            });

            $('#designEditorModal').on('hidden.bs.modal', function() {
                currentItemIndex = null;
                $('#upload-image').val('');
                $('#text-input').val('');
                $('.btn-open-design-editor').removeData('parsed-config');
            });

            $(document).on('click', '#btn-save-design-final', function() {
                if (typeof DesignEditor === 'undefined' || currentItemIndex === null) return;

                var itemIndex = DesignEditor.itemIndex;
                var designConfig = DesignEditor.getDesignConfig();

                $(this).prop('disabled', true).html('<i class="lni lni-spinner-arrow spinning"></i> Menyimpan...');
                $('.design-config-data[data-item-index="' + itemIndex + '"]').val(JSON.stringify(designConfig));

                @this.handleDesignConfigSaved(itemIndex, designConfig)
                    .then(function() {
                        $('#designEditorModal').modal('hide');
                        setTimeout(function() {
                            alert('Desain berhasil disimpan!');
                        }, 300);
                    })
                    .finally(function() {
                        $('#btn-save-design-final').prop('disabled', false).html(
                            '<i class="lni lni-save"></i> Simpan Desain');
                    });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .spinning {
                display: inline-block;
                animation: spin 1s linear infinite;
            }

            .btn-design {
                background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
                color: white;
                border: none;
                padding: 15px;
                border-radius: 15px;
                font-weight: 600;
                font-size: 16px;
                transition: all 0.3s;
            }

            .btn-design:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
                color: white;
            }

            .btn-design:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .design-preview-badge {
                background: #f0fdf4;
                border: 2px solid #86efac;
                border-radius: 10px;
                padding: 10px 15px;
            }

            /* Courier Option Card */
            .courier-option-card {
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                cursor: pointer;
                transition: all 0.3s;
                position: relative;
            }

            .courier-option-card:hover {
                border-color: #8B5CF6;
                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
                transform: translateY(-2px);
            }

            .courier-option-card.border-primary {
                border-color: #8B5CF6;
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
            }

            .courier-radio {
                position: absolute;
                top: 15px;
                right: 15px;
                width: 20px;
                height: 20px;
                cursor: pointer;
            }

            .courier-option-card label {
                cursor: pointer;
                margin: 0;
                display: block;
                padding-right: 35px;
            }

            .courier-name {
                font-weight: 700;
                font-size: 16px;
                color: #1e293b;
                margin-bottom: 5px;
            }

            .courier-service {
                font-size: 13px;
                color: #64748b;
                margin-bottom: 8px;
            }

            .courier-cost {
                font-size: 18px;
                font-weight: 700;
                color: #8B5CF6;
                margin-bottom: 5px;
            }

            .courier-etd {
                font-size: 13px;
                color: #10b981;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            /* Select2 Style Override (if needed) */
            #provinsi-select,
            #kota-select {
                height: 45px;
                border-radius: 8px;
                border: 2px solid #e2e8f0;
                padding: 8px 15px;
                font-size: 14px;
            }

            #provinsi-select:focus,
            #kota-select:focus {
                border-color: #8B5CF6;
                outline: none;
                box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            }

            /* Alert Styles */
            .alert {
                border-radius: 10px;
                border: none;
                padding: 15px;
            }

            .alert-info {
                background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
                color: #0369a1;
            }

            .alert-success {
                background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
                color: #047857;
            }

            .alert-danger {
                background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
                color: #dc2626;
            }

            /* Price Summary Enhancement */
            .price-summary {
                background: white;
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                sticky: top;
                top: 20px;
            }

            .price-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .price-row.total {
                border-bottom: none;
                padding: 20px 0;
                margin-top: 10px;
                border-top: 2px solid #e2e8f0;
            }

            .price-row.total span,
            .price-row.total strong {
                font-size: 20px;
                color: #1e293b;
            }

            .btn-submit-order {
                width: 100%;
                padding: 18px;
                border-radius: 15px;
                font-weight: 700;
                font-size: 16px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border: none;
                color: white;
                transition: all 0.3s;
                margin-top: 20px;
            }

            .btn-submit-order:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            }

            .btn-submit-order:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            /* Section Header */
            .section-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 20px;
                border-radius: 15px;
                margin-bottom: 20px;
                border-left: 5px solid #8B5CF6;
            }

            .section-header h4 {
                margin: 0;
                color: #1e293b;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            /* Order Item Card */
            .order-item-card {
                background: white;
                border: 2px solid #e2e8f0;
                border-radius: 15px;
                padding: 25px;
                margin-bottom: 20px;
                position: relative;
                transition: all 0.3s;
            }

            .order-item-card:hover {
                border-color: #8B5CF6;
                box-shadow: 0 4px 20px rgba(139, 92, 246, 0.1);
            }

            .remove-item-btn {
                position: absolute;
                top: 15px;
                right: 15px;
                background: #fee2e2;
                color: #dc2626;
                border: none;
                width: 35px;
                height: 35px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
            }

            .remove-item-btn:hover {
                background: #dc2626;
                color: white;
                transform: rotate(90deg);
            }

            .btn-add-item {
                width: 100%;
                padding: 15px;
                border: 2px dashed #8B5CF6;
                background: transparent;
                color: #8B5CF6;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .btn-add-item:hover {
                background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
                color: white;
                border-style: solid;
            }

            /* Weight Display */
            #total-weight-display {
                color: #0ea5e9;
                font-weight: 600;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .courier-option-card {
                    margin-bottom: 10px;
                }

                .price-summary {
                    margin-top: 30px;
                }
            }
        </style>
    @endpush
</div>
