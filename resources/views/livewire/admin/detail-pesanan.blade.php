<div>
    @if ($order)
        <div class="row">
            <!-- Informasi Pesanan -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Nomor Order:</strong></p>
                                <p class="text-muted">{{ $order->order_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tanggal Order:</strong></p>
                                <p class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status:</strong></p>
                                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status Pembayaran:</strong></p>
                                @if ($order->payment_status == 'settlement')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </div>
                        </div>
                        @if ($order->catatan)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="mb-2"><strong>Catatan:</strong></p>
                                    <p class="text-muted">{{ $order->catatan }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Item Pesanan -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Item Pesanan</h5>
                        <a href="{{ route('admin.download.design.order', $order->id) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-download"></i> Download Semua Design
                        </a>
                    </div>
                    <div class="card-body">
                        @foreach ($order->items as $index => $item)
                            <div class="card mb-3 border">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Item #{{ $index + 1 }} - {{ $item->produk->nama_produk }}
                                        </h6>
                                        <a href="{{ route('admin.download.design.item', [$order->id, $item->id]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-download"></i> Download Design Item
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <small class="text-muted">Ukuran Kaos:</small>
                                            <p class="mb-0"><strong>{{ $item->ukuran_kaos }}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Warna:</small>
                                            <p class="mb-0"><strong>{{ $item->warna_kaos }}</strong></p>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Quantity:</small>
                                            <p class="mb-0"><strong>{{ $item->quantity }}</strong></p>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Harga:</small>
                                            <p class="mb-0"><strong>{{ $item->formatted_harga_satuan }}</strong></p>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Subtotal:</small>
                                            <p class="mb-0"><strong>{{ $item->formatted_subtotal }}</strong></p>
                                        </div>
                                    </div>

                                    @if ($item->catatan_item)
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i> <strong>Catatan:</strong>
                                            {{ $item->catatan_item }}
                                        </div>
                                    @endif

                                    <!-- Preview Design Area -->
                                    <div class="row g-2">
                                        @php
                                            $areas = [
                                                'front' => ['label' => 'Depan', 'icon' => 'ti-square'],
                                                'back' => ['label' => 'Belakang', 'icon' => 'ti-square-rotated'],
                                                'left_sleeve' => ['label' => 'Lengan Kiri', 'icon' => 'ti-arrows-left'],
                                                'right_sleeve' => [
                                                    'label' => 'Lengan Kanan',
                                                    'icon' => 'ti-arrows-right',
                                                ],
                                            ];
                                        @endphp

                                        @foreach ($areas as $areaKey => $areaData)
                                            @if ($this->hasDesignInArea($item->id, $areaKey))
                                                <div class="col-md-3">
                                                    <div class="card border-primary">
                                                        <div class="card-body text-center p-2">
                                                            <i class="ti {{ $areaData['icon'] }} text-primary"
                                                                style="font-size: 2rem;"></i>
                                                            <p class="mb-2 mt-2">
                                                                <small><strong>{{ $areaData['label'] }}</strong></small>
                                                            </p>
                                                            <button
                                                                wire:click="showDesignPreview({{ $item->id }}, '{{ $areaKey }}')"
                                                                class="btn btn-sm btn-primary w-100 mb-1">
                                                                <i class="ti ti-eye"></i> Preview
                                                            </button>
                                                            <a href="{{ route('admin.download.design.area', [$order->id, $item->id, $areaKey]) }}"
                                                                class="btn btn-sm btn-outline-primary w-100">
                                                                <i class="ti ti-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Total -->
                        <div class="table-responsive mt-3">
                            <table class="table">
                                <tfoot>
                                    <tr>
                                        <td class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end" style="width: 150px;">
                                            <strong>{{ $order->formatted_subtotal }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Ongkir:</strong></td>
                                        <td class="text-end"><strong>{{ $order->formatted_ongkir }}</strong></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong
                                                class="text-white fs-5">{{ $order->formatted_total_harga }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Kanan -->
            <div class="col-md-4">
                <!-- Informasi Customer -->
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Customer</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Nama:</strong></p>
                        <p class="text-muted">{{ $order->user->name }}</p>

                        <p class="mb-2 mt-3"><strong>Email:</strong></p>
                        <p class="text-muted">{{ $order->user->email }}</p>

                        <p class="mb-2 mt-3"><strong>Telepon:</strong></p>
                        <p class="text-muted">{{ $order->user->telepon ?? '-' }}</p>
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Penerima:</strong></p>
                        <p class="text-muted">{{ $order->penerima_nama }}</p>

                        <p class="mb-2 mt-3"><strong>Telepon:</strong></p>
                        <p class="text-muted">{{ $order->penerima_telepon }}</p>

                        <p class="mb-2 mt-3"><strong>Alamat:</strong></p>
                        <p class="text-muted">{{ $order->alamat_lengkap }}</p>
                        <p class="text-muted">
                            {{ $order->kelurahan }}, {{ $order->kecamatan }}<br>
                            {{ $order->kota }}, {{ $order->provinsi }} {{ $order->kode_pos }}
                        </p>

                        @if ($order->tipe_pengiriman)
                            <p class="mb-2 mt-3"><strong>Tipe Pengiriman:</strong></p>
                            <p class="text-muted">{{ ucwords(str_replace('_', ' ', $order->tipe_pengiriman)) }}</p>
                        @endif

                        @if ($order->kurir)
                            <p class="mb-2 mt-3"><strong>Kurir:</strong></p>
                            <p class="text-muted">{{ strtoupper($order->kurir) }} - {{ $order->service_kurir }}</p>
                        @endif

                        @if ($order->resi)
                            <p class="mb-2 mt-3"><strong>No. Resi:</strong></p>
                            <p class="text-muted">{{ $order->resi }}</p>
                        @endif
                    </div>
                </div>

                <!-- Aksi Verifikasi -->
                @if ($order->status == 'paid')
                    <div class="card mt-3">
                        <div class="card-header bg-warning">
                            <h5 class="text-white mb-0">Aksi Verifikasi</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Pesanan ini sudah dibayar dan menunggu verifikasi</p>
                            <button onclick="confirmVerifikasi()" class="btn btn-success w-100 mb-2">
                                <i class="ti ti-check"></i> Verifikasi Pesanan
                            </button>
                            <button onclick="confirmTolak()" class="btn btn-danger w-100">
                                <i class="ti ti-x"></i> Tolak Pesanan
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Preview Design -->
        @if ($showDesignModal && $selectedItem)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Preview Design -
                                {{ ucwords(str_replace('_', ' ', $selectedArea)) }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                @if ($selectedItem)
                                    <div id="design-preview-container"
                                        style="position: relative; width: 100%; max-width: 500px; margin: 0 auto;">
                                        <canvas id="preview-canvas" width="500" height="600"
                                            style="border: 1px solid #ddd;"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-muted mb-2">
                                            Ukuran: <strong>{{ $selectedItem->ukuran_kaos }}</strong> |
                                            Warna: <strong>{{ $selectedItem->warna_kaos }}</strong>
                                        </p>
                                        <a href="{{ route('admin.download.design.area', [$order->id, $selectedItem->id, $selectedArea]) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-download"></i> Download Design
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@push('styles')
    <style>
        #design-preview-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        #preview-canvas {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script>
        let previewCanvas = null;

        window.addEventListener('show-toast', event => {
            console.log('Toast event received:', event);
            const data = event.detail[0];
            if (data.type === 'success') {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        });

        function confirmVerifikasi() {
            Swal.fire({
                title: 'Verifikasi Pesanan?',
                text: "Pesanan akan diproses ke tahap produksi",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.verifikasiPesanan();
                }
            });
        }

        function confirmTolak() {
            Swal.fire({
                title: 'Tolak Pesanan?',
                text: "Pesanan akan dibatalkan",
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Alasan penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan harus diisi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.tolakPesanan(result.value);
                }
            });
        }

        // Listen untuk event modal dibuka
        document.addEventListener('livewire:init', () => {
            Livewire.on('designModalOpened', (eventData) => {
                console.log('Modal opened, event data received:', eventData);

                const data = eventData[0];
                setTimeout(() => {
                    renderDesignPreview(data);
                }, 300);
            });
        });

        function renderDesignPreview(data) {
            console.log('=== START RENDER ===');
            console.log('Data received:', {
                area: data.area,
                warna: data.warna,
                hasCanvas: data.hasCanvas,
                canvasJsonLength: data.canvasJson ? data.canvasJson.length : 0
            });

            const canvasEl = document.getElementById('preview-canvas');

            if (!canvasEl) {
                console.error('Canvas element not found!');
                return;
            }

            console.log('Canvas element found');

            // Destroy previous canvas instance
            if (previewCanvas) {
                previewCanvas.dispose();
                console.log('Previous canvas disposed');
            }

            // Create new canvas instance
            previewCanvas = new fabric.Canvas('preview-canvas', {
                selection: false,
                backgroundColor: 'rgba(255,255,255,0)'
            });

            console.log('New canvas created');

            // Check if has canvas data
            if (!data.hasCanvas || !data.canvasJson) {
                console.warn('No canvas data available for area:', data.area);
                previewCanvas.clear();
                const text = new fabric.Text('Tidak ada design untuk area ini', {
                    left: 150,
                    top: 300,
                    fontSize: 16,
                    fill: '#999',
                    selectable: false
                });
                previewCanvas.add(text);
                previewCanvas.renderAll();
                return;
            }

            try {
                // Parse canvas JSON string
                const canvasJsonRaw = JSON.parse(data.canvasJson);
                console.log('Canvas JSON parsed:', canvasJsonRaw);
                console.log('Objects count:', canvasJsonRaw.objects ? canvasJsonRaw.objects.length : 0);

                // Load design objects DULU
                console.log('Loading canvas objects...');
                previewCanvas.loadFromJSON(canvasJsonRaw, function() {
                    console.log('loadFromJSON callback executed');

                    const objectCount = previewCanvas.getObjects().length;
                    console.log('Objects loaded in canvas:', objectCount);

                    // Set all objects non-selectable
                    previewCanvas.forEachObject(function(obj) {
                        obj.set({
                            selectable: false,
                            evented: false
                        });
                        console.log('Object:', obj.type, 'at', obj.left, obj.top);
                    });

                    // BARU set background template SETELAH objects loaded
                    const templateUrl = '/frontend/assets/img/kaos-templates/' + data.warna + '-' + data.area +
                        '.png';
                    console.log('Loading template:', templateUrl);

                    fabric.Image.fromURL(templateUrl, function(img) {
                        if (img && img.width) {
                            console.log('Template loaded, dimensions:', img.width, 'x', img.height);
                            const scale = Math.min(500 / img.width, 600 / img.height);
                            img.scale(scale);
                            img.set({
                                left: 0,
                                top: 0,
                                selectable: false,
                                evented: false
                            });
                            previewCanvas.setBackgroundImage(img, previewCanvas.renderAll.bind(
                                previewCanvas));
                            console.log('✓ Background image set');
                        } else {
                            console.warn('Template image failed to load');
                        }

                        previewCanvas.renderAll();
                        console.log('✓ Canvas rendered successfully');
                    }, {
                        crossOrigin: 'anonymous'
                    });

                }, function(o, object) {
                    console.log('Loading object:', object.type);
                });

            } catch (error) {
                console.error('Error loading canvas:', error);
                console.error('Error stack:', error.stack);
                alert('Gagal memuat preview design: ' + error.message);
            }
        }
    </script>
@endpush
