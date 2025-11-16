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

                    <!-- RAJAONGKIR: Provinsi Autocomplete -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                        <select class="form-control" id="provinsi-select" wire:model="provinsi_id">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" wire:model="provinsi">
                        @error('provinsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- RAJAONGKIR: Kota Autocomplete -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kota/Kabupaten <span class="text-danger">*</span></label>
                        <select class="form-control" id="kota-select" wire:model="kota_id" disabled>
                            <option value="">Pilih Kota</option>
                        </select>
                        <input type="hidden" wire:model="kota">
                        @error('kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
                        <label class="form-label fw-bold">Kelurahan/Desa</label>
                        <input type="text" class="form-control" wire:model="kelurahan"
                            placeholder="Nama kelurahan">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Kecamatan</label>
                        <input type="text" class="form-control" wire:model="kecamatan"
                            placeholder="Nama kecamatan">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Kode Pos</label>
                        <input type="text" class="form-control" wire:model="kode_pos" placeholder="96xxx">
                    </div>

                    <!-- Tipe Pengiriman & Pilihan Kurir -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Tipe Pengiriman</label>
                        <div id="shipping-type-info" class="alert alert-info">
                            <i class="lni lni-information"></i> Pilih kota tujuan terlebih dahulu
                        </div>
                        <input type="hidden" wire:model="tipe_pengiriman" id="tipe-pengiriman-hidden">
                    </div>

                    <!-- Container untuk Pilihan Kurir (hanya muncul untuk antar kota) -->
                    <div class="col-12 mb-3" id="courier-selection-container" style="display: none;">
                        <label class="form-label fw-bold">Pilih Layanan Pengiriman <span
                                class="text-danger">*</span></label>
                        <div id="courier-options-loading" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Menghitung ongkos kirim...</p>
                        </div>
                        <div id="courier-options-list" class="row g-2"></div>
                        <input type="hidden" wire:model="kurir_code" id="selected-kurir-code">
                        <input type="hidden" wire:model="kurir_service" id="selected-kurir-service">
                        <input type="hidden" wire:model="kurir_etd" id="selected-kurir-etd">
                        @error('kurir_code')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Catatan Tambahan (Optional)</label>
                        <textarea class="form-control" wire:model="catatan" rows="2" placeholder="Catatan untuk pesanan Anda"></textarea>
                    </div>
                </div>
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

            // ===== RAJAONGKIR INTEGRATION =====

            // Load provinces on page load
            $(document).ready(function() {
                loadProvinces();
            });

            function loadProvinces() {
                $.ajax({
                    url: '{{ route('customer.shipping.provinces') }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data) {
                            var select = $('#provinsi-select');
                            select.empty().append('<option value="">Pilih Provinsi</option>');

                            $.each(response.data, function(index, provinsi) {
                                select.append('<option value="' + provinsi.province_id + '" data-name="' +
                                    provinsi.province + '">' + provinsi.province + '</option>');
                            });

                            // Set default Gorontalo if exists
                            @this.set('provinsi', 'Gorontalo');
                        }
                    },
                    error: function() {
                        alert('Gagal memuat data provinsi');
                    }
                });
            }

            // Handle province selection
            $('#provinsi-select').on('change', function() {
                var provinceId = $(this).val();
                var provinceName = $(this).find(':selected').data('name');

                @this.set('provinsi_id', provinceId);
                @this.set('provinsi', provinceName);
                @this.set('kota_id', null);
                @this.set('kota', '');

                $('#kota-select').empty().append('<option value="">Pilih Kota</option>').prop('disabled', true);
                $('#courier-selection-container').hide();
                $('#shipping-type-info').html('<i class="lni lni-information"></i> Pilih kota tujuan terlebih dahulu')
                    .removeClass('alert-success').addClass('alert-info');

                if (provinceId) {
                    loadCities(provinceId);
                }
            });

            function loadCities(provinceId) {
                $.ajax({
                    url: '{{ route('customer.shipping.cities') }}',
                    type: 'GET',
                    data: {
                        province_id: provinceId
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            var select = $('#kota-select');
                            select.empty().append('<option value="">Pilih Kota</option>');

                            $.each(response.data, function(index, city) {
                                var cityLabel = city.type + ' ' + city.city_name;
                                select.append('<option value="' + city.city_id + '" data-name="' +
                                    cityLabel + '">' + cityLabel + '</option>');
                            });

                            select.prop('disabled', false);
                        }
                    }
                });
            }

            // Handle city selection
            $('#kota-select').on('change', function() {
                var cityId = $(this).val();
                var cityName = $(this).find(':selected').data('name');

                if (!cityId) return;

                @this.set('kota_id', cityId);
                @this.set('kota', cityName);

                selectedCityId = cityId;

                // Calculate shipping cost
                calculateShippingCost(cityId);
            });

            function calculateShippingCost(destinationCityId) {
                // Show loading
                $('#shipping-type-info').html('<i class="lni lni-spinner-arrow spinning"></i> Menghitung ongkos kirim...')
                    .removeClass('alert-success alert-danger').addClass('alert-info');
                $('#courier-selection-container').hide();
                $('#btn-submit-order').prop('disabled', true);

                $.ajax({
                    url: '{{ route('customer.shipping.calculate-cost') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        destination_city_id: destinationCityId,
                        weight: totalWeight
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.is_same_city) {
                                // Dalam kota Gorontalo
                                @this.set('tipe_pengiriman', 'dalam_kota');
                                @this.set('ongkir', 10000);
                                @this.set('kurir_code', 'local');
                                @this.set('kurir_service', 'FLAT');
                                @this.set('kurir_etd', '1-2 hari');

                                $('#shipping-type-info').html(
                                    '<i class="lni lni-truck"></i> <strong>Pengiriman Dalam Kota Gorontalo</strong><br><small>Ongkir: Rp 10.000 (Estimasi: 1-2 hari)</small>'
                                    ).removeClass('alert-info alert-danger').addClass('alert-success');
                                $('#courier-selection-container').hide();
                                $('#ongkir-display').text('Rp 10.000');
                                $('#btn-submit-order').prop('disabled', false);
                            } else {
                                // Antar kota
                                @this.set('tipe_pengiriman', 'antar_kota');
                                courierOptions = response.data;

                                if (courierOptions.length > 0) {
                                    displayCourierOptions(courierOptions);
                                    $('#shipping-type-info').html(
                                        '<i class="lni lni-truck"></i> <strong>Pengiriman Antar Kota</strong><br><small>Pilih layanan pengiriman di bawah</small>'
                                        ).removeClass('alert-info alert-danger').addClass('alert-success');
                                } else {
                                    $('#shipping-type-info').html(
                                        '<i class="lni lni-warning"></i> Tidak ada layanan pengiriman tersedia untuk kota tujuan'
                                        ).removeClass('alert-info alert-success').addClass('alert-danger');
                                    $('#btn-submit-order').prop('disabled', true);
                                }
                            }
                        } else {
                            $('#shipping-type-info').html('<i class="lni lni-warning"></i> ' + (response.message ||
                                'Gagal menghitung ongkir')).removeClass('alert-info alert-success').addClass(
                                'alert-danger');
                            $('#btn-submit-order').prop('disabled', true);
                        }
                    },
                    error: function() {
                        $('#shipping-type-info').html(
                                '<i class="lni lni-warning"></i> Terjadi kesalahan saat menghitung ongkir')
                            .removeClass('alert-info alert-success').addClass('alert-danger');
                        $('#btn-submit-order').prop('disabled', true);
                    }
                });
            }

            function displayCourierOptions(options) {
                var container = $('#courier-options-list');
                container.empty();

                $('#courier-options-loading').hide();
                $('#courier-selection-container').show();

                // Select cheapest by default
                var cheapest = options[0];

                $.each(options, function(index, option) {
                    var isChecked = index === 0 ? 'checked' : '';
                    var cardClass = index === 0 ? 'border-primary' : '';

                    var card = `
                        <div class="col-md-6 col-lg-4">
                            <div class="courier-option-card ${cardClass}">
                                <input type="radio" class="courier-radio" name="courier_option" value="${index}" ${isChecked}
                                    data-code="${option.code}"
                                    data-service="${option.service}"
                                    data-cost="${option.cost}"
                                    data-etd="${option.etd}">
                                <label>
                                    <div class="courier-name">${option.name}</div>
                                    <div class="courier-service">${option.service} - ${option.description}</div>
                                    <div class="courier-cost">Rp ${formatNumber(option.cost)}</div>
                                    <div class="courier-etd"><i class="lni lni-timer"></i> ${option.etd}</div>
                                </label>
                            </div>
                        </div>
                    `;

                    container.append(card);
                });

                // Set default values
                setSelectedCourier(cheapest);

                // Handle courier selection
                $('.courier-radio').on('change', function() {
                    var index = $(this).val();
                    var selected = courierOptions[index];
                    setSelectedCourier(selected);

                    $('.courier-option-card').removeClass('border-primary');
                    $(this).closest('.courier-option-card').addClass('border-primary');
                });

                $('#btn-submit-order').prop('disabled', false);
            }

            function setSelectedCourier(courier) {
                @this.set('kurir_code', courier.code);
                @this.set('kurir_service', courier.service);
                @this.set('kurir_etd', courier.etd);
                @this.set('ongkir', courier.cost);

                $('#selected-kurir-code').val(courier.code);
                $('#selected-kurir-service').val(courier.service);
                $('#selected-kurir-etd').val(courier.etd);
                $('#ongkir-display').text('Rp ' + formatNumber(courier.cost));
            }

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
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
