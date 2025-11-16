@extends('layouts.frontend')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
    <style>
        .order-detail-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px 0;
        }

        .detail-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .detail-card h5 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .status-timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -30px;
            width: 12px;
            height: 12px;
            background: #cbd5e1;
            border-radius: 50%;
        }

        .timeline-dot.active {
            background: #6366F1;
        }

        .timeline-line {
            position: absolute;
            left: -24px;
            top: 12px;
            bottom: -8px;
            width: 2px;
            background: #e2e8f0;
        }

        .btn-action {
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s;
            display: inline-block;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>

    <div class="container">
        <div class="order-detail-container">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 style="color: #1e293b; font-weight: 800;">
                        Detail Pesanan
                    </h2>
                    <p style="color: #64748b; margin: 0;">{{ $order->order_number }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="detail-card">
                        <h5><i class="lni lni-package"></i> Item Pesanan</h5>
                        @foreach ($order->items as $item)
                            <div class="order-item">
                                <div class="item-info">
                                    <div class="item-name">{{ $item->produk->jenisSablon->nama }}</div>
                                    <div class="item-detail">
                                        Ukuran: {{ $item->produk->ukuran->nama }} |
                                        Layanan: {{ $item->produk->tipe_layanan_label }} |
                                        Qty: {{ $item->quantity }}
                                    </div>
                                    @if ($item->file_desain)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($item->file_desain) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="lni lni-download"></i> Lihat Desain
                                            </a>
                                        </div>
                                    @endif
                                    @if ($item->catatan_item)
                                        <div class="mt-2">
                                            <small class="text-muted">Catatan: {{ $item->catatan_item }}</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="item-price">
                                    {{ $item->formatted_subtotal }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping Address -->
                    <div class="detail-card">
                        <h5><i class="lni lni-map-marker"></i> Alamat Pengiriman</h5>
                        <p class="mb-1"><strong>{{ $order->penerima_nama }}</strong></p>
                        <p class="mb-1">{{ $order->penerima_telepon }}</p>
                        <p class="mb-1">{{ $order->alamat_lengkap }}</p>
                        <p class="mb-0">
                            {{ $order->kelurahan }}, {{ $order->kecamatan }}<br>
                            {{ $order->kota }}, {{ $order->provinsi }} {{ $order->kode_pos }}
                        </p>
                        @if ($order->kurir)
                            <div class="mt-3 p-3" style="background: white; border-radius: 10px;">
                                <strong>Kurir:</strong> {{ $order->kurir }} - {{ $order->service_kurir }}<br>
                                @if ($order->resi)
                                    <strong>No. Resi:</strong> {{ $order->resi }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Tracking -->
                    @if ($order->shippingTrackings->count() > 0)
                        <div class="detail-card">
                            <h5><i class="lni lni-package"></i> Status Pengiriman</h5>
                            <div class="status-timeline">
                                @foreach ($order->shippingTrackings as $tracking)
                                    <div class="timeline-item">
                                        <div class="timeline-dot active"></div>
                                        @if (!$loop->last)
                                            <div class="timeline-line"></div>
                                        @endif
                                        <div>
                                            <strong>{{ $tracking->status }}</strong><br>
                                            <small class="text-muted">{{ $tracking->description }}</small><br>
                                            @if ($tracking->location)
                                                <small class="text-muted"><i class="lni lni-map-marker"></i>
                                                    {{ $tracking->location }}</small><br>
                                            @endif
                                            <small
                                                class="text-muted">{{ $tracking->tracked_at->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Price Summary -->
                    <div class="detail-card">
                        <h5><i class="lni lni-calculator"></i> Ringkasan</h5>
                        <div class="price-row">
                            <span>Subtotal</span>
                            <strong>{{ $order->formatted_subtotal }}</strong>
                        </div>
                        <div class="price-row">
                            <span>Ongkir</span>
                            <strong>{{ $order->formatted_ongkir }}</strong>
                        </div>
                        <div class="price-row total">
                            <span>Total</span>
                            <strong>{{ $order->formatted_total_harga }}</strong>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="detail-card">
                        <h5><i class="lni lni-information"></i> Status Order</h5>
                        <div class="status-timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $order->status == 'pending_payment' ? 'active' : '' }}"></div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Menunggu Pembayaran</strong><br>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ in_array($order->status, ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Dibayar</strong><br>
                                    @if ($order->paid_at)
                                        <small class="text-muted">{{ $order->paid_at->format('d M Y, H:i') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ in_array($order->status, ['verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Diverifikasi</strong><br>
                                    @if ($order->verified_at)
                                        <small class="text-muted">{{ $order->verified_at->format('d M Y, H:i') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ in_array($order->status, ['in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Sedang Produksi</strong>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ in_array($order->status, ['shipped', 'completed']) ? 'active' : '' }}">
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Sedang Dikirim</strong><br>
                                    @if ($order->shipped_at)
                                        <small class="text-muted">{{ $order->shipped_at->format('d M Y, H:i') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot {{ $order->status == 'completed' ? 'active' : '' }}"></div>
                                <div>
                                    <strong>Selesai</strong><br>
                                    @if ($order->completed_at)
                                        <small class="text-muted">{{ $order->completed_at->format('d M Y, H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="detail-card">
                        <h5><i class="lni lni-cog"></i> Aksi</h5>

                        @if ($order->status == 'pending_payment')
                            <a href="#" class="btn-action w-100 mb-2">
                                <i class="lni lni-credit-cards"></i> Bayar Sekarang
                            </a>
                        @endif

                        @if (in_array($order->status, ['paid', 'verified', 'in_production']))
                            <button class="btn btn-outline-danger w-100 mb-2">
                                <i class="lni lni-close"></i> Batalkan Pesanan
                            </button>
                        @endif

                        @if ($order->status == 'completed')
                            <button class="btn btn-outline-warning w-100 mb-2">
                                <i class="lni lni-reload"></i> Ajukan Return
                            </button>
                        @endif

                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="lni lni-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>

                    @if ($order->catatan)
                        <div class="detail-card">
                            <h5><i class="lni lni-text-format"></i> Catatan</h5>
                            <p class="mb-0">{{ $order->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
