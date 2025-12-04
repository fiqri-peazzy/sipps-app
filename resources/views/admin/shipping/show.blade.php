<x-app-layout>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Proses Pengiriman</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Detail Pengiriman</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Info Order -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>No. Order:</strong></div>
                        <div class="col-sm-8">{{ $order->order_number }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Status Order:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Penerima:</strong></div>
                        <div class="col-sm-8">
                            {{ $order->penerima_nama }}<br>
                            <small class="text-muted">{{ $order->penerima_telepon }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Alamat Lengkap:</strong></div>
                        <div class="col-sm-8">
                            {{ $order->alamat_lengkap }}<br>
                            {{ $order->kecamatan }}, {{ $order->kota }}<br>
                            {{ $order->provinsi }} {{ $order->kode_pos }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Kurir:</strong></div>
                        <div class="col-sm-8">
                            {{ $order->kurir }} - {{ $order->service_kurir }}<br>
                            <small class="text-muted">Estimasi: {{ $order->estimasi_pengiriman }}</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Berat Total:</strong></div>
                        <div class="col-sm-8">{{ $order->berat_total }} gram</div>
                    </div>

                    <hr>

                    <!-- Form Input Resi -->
                    @if ($order->status == 'ready_to_ship' && !$order->resi)
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i> Pesanan siap untuk dikirim. Silakan input nomor resi
                            pengiriman.
                        </div>
                        <form action="{{ route('admin.shipping.input-resi', $order->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Resi</label>
                                        <input type="text" name="resi" class="form-control"
                                            placeholder="Masukkan nomor resi" required>
                                        <small class="text-muted">Kurir akan otomatis terdeteksi dari nomor resi</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-check"></i> Kirim Paket
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <!-- Setelah form input resi, tambahkan form update tracking -->
                    @if ($order->resi)
                        <hr class="my-4">

                        <!-- Form Update Tracking Manual -->
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="ti ti-edit"></i> Update Status Tracking</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.shipping.update-tracking', $order->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Status Pengiriman <span
                                                        class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="picked_up">📦 Diambil Kurir</option>
                                                    <option value="in_transit">🚚 Dalam Perjalanan</option>
                                                    <option value="delivered">✅ Terkirim</option>
                                                    <option value="returned">↩️ Dikembalikan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Lokasi</label>
                                                <input type="text" name="location" class="form-control"
                                                    placeholder="Contoh: Jakarta Pusat">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="3"
                                            placeholder="Contoh: Paket sedang dalam perjalanan ke kota tujuan" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-info">
                                        <i class="ti ti-plus"></i> Tambah Update Tracking
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tracking History -->
            @if ($order->resi)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Riwayat Tracking</h5>
                    </div>
                    <div class="card-body">
                        @if ($order->shippingTrackings->count() > 0)
                            <div class="timeline-container">
                                @foreach ($order->shippingTrackings as $tracking)
                                    <div class="timeline-item">
                                        <div
                                            class="timeline-badge bg-{{ $tracking->status == 'delivered' ? 'success' : ($tracking->status == 'in_transit' ? 'primary' : 'secondary') }}">
                                            <i
                                                class="ti ti-{{ $tracking->status == 'delivered' ? 'check' : 'truck' }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">{{ $tracking->description }}</h6>
                                            @if ($tracking->location)
                                                <p class="text-muted mb-1">
                                                    <i class="ti ti-map-pin"></i> {{ $tracking->location }}
                                                </p>
                                            @endif
                                            <small class="text-muted">
                                                <i class="ti ti-clock"></i>
                                                {{ \Carbon\Carbon::parse($tracking->tracked_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-package" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="text-muted mt-2">Belum ada riwayat tracking</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Info Items -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Item Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach ($order->items as $item)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1">{{ $item->produk->jenisSablon->nama }}</h6>
                            <small class="text-muted">
                                {{ $item->produk->ukuran->nama }} |
                                Qty: {{ $item->quantity }} |
                                @if ($item->ukuran_kaos)
                                    Ukuran: {{ $item->ukuran_kaos }}
                                @endif
                            </small>
                            <p class="mb-0 mt-1"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    @endforeach

                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkir:</span>
                            <strong>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-primary">Rp
                                {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Status Pengiriman</h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $statusIcons = [
                            'pending' => 'package',
                            'picked_up' => 'truck',
                            'in_transit' => 'plane',
                            'delivered' => 'circle-check',
                            'returned' => 'arrow-back',
                        ];
                        $statusColors = [
                            'pending' => 'secondary',
                            'picked_up' => 'info',
                            'in_transit' => 'primary',
                            'delivered' => 'success',
                            'returned' => 'danger',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'picked_up' => 'Diambil Kurir',
                            'in_transit' => 'Dalam Perjalanan',
                            'delivered' => 'Terkirim',
                            'returned' => 'Dikembalikan',
                        ];
                    @endphp
                    <i class="ti ti-{{ $statusIcons[$order->status_pengiriman] ?? 'package' }}"
                        style="font-size: 64px; color: var(--bs-{{ $statusColors[$order->status_pengiriman] ?? 'secondary' }});"></i>
                    <h4 class="mt-3">{{ $statusLabels[$order->status_pengiriman] ?? $order->status_pengiriman }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('styles')
    <style>
        .timeline-container {
            position: relative;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 30px;
            width: 2px;
            height: calc(100% - 10px);
            background: #e0e0e0;
        }

        .timeline-badge {
            position: absolute;
            left: -40px;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 1;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
@endpush
