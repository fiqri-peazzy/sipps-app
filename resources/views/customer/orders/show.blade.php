{{-- FILE: resources/views/customer/orders/show.blade.php --}}
@extends('layouts.customer')

@push('styles')
    <style>
        .detail-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-card h5 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .item-detail {
            font-size: 0.9rem;
            color: #64748b;
        }

        .item-price {
            text-align: right;
            color: #6366f1;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .status-timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
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
            background: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        .timeline-line {
            position: absolute;
            left: -24px;
            top: 12px;
            bottom: -8px;
            width: 2px;
            background: #e2e8f0;
        }

        .payment-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .payment-loading.active {
            display: flex;
        }

        .payment-loading-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #6366f1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .tracking-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .timeline-container {
            position: relative;
            padding-left: 40px;
            margin-top: 20px;
        }

        .timeline-badge {
            position: absolute;
            left: -36px;
            top: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var orderId = {{ $order->id }};
            var uniqueOrderId = '';

            $('#btn-pay-now').on('click', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<i class="lni lni-spinner spinning"></i> Memproses...');
                showPaymentLoading('Menyiapkan pembayaran...');

                $.ajax({
                    url: '/customer/payment/initiate/' + orderId,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        hidePaymentLoading();
                        if (response.success) {
                            uniqueOrderId = response.unique_order_id;
                            snap.pay(response.snap_token, {
                                onSuccess: function(result) {
                                    showPaymentLoading(
                                        'Pembayaran berhasil! Mengalihkan...');
                                    setTimeout(function() {
                                        window.location.href =
                                            '/customer/payment/finish?order_id=' +
                                            uniqueOrderId;
                                    }, 1500);
                                },
                                onPending: function(result) {
                                    showPaymentLoading('Menunggu pembayaran...');
                                    setTimeout(function() {
                                        window.location.href =
                                            '/customer/payment/finish?order_id=' +
                                            uniqueOrderId;
                                    }, 1500);
                                },
                                onError: function(result) {
                                    hidePaymentLoading();
                                    alert('Terjadi kesalahan pada pembayaran');
                                    $btn.prop('disabled', false).html(originalHtml);
                                },
                                onClose: function() {
                                    hidePaymentLoading();
                                    $btn.prop('disabled', false).html(originalHtml);
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        hidePaymentLoading();
                        alert('Gagal memproses pembayaran');
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            @if ($order->status == 'pending_payment' && $order->payment_status == 'pending' && $order->transaction_id)
                setInterval(function() {
                    $.ajax({
                        url: '/customer/payment/check-status/' + orderId,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                var status = response.data.transaction_status;
                                if (status === 'settlement' || status === 'capture') {
                                    location.reload();
                                }
                            }
                        }
                    });
                }, 10000);
            @endif

            function showPaymentLoading(message) {
                var html = '<div class="payment-loading-content"><div class="spinner"></div><h5 class="mt-3">' +
                    message + '</h5></div>';
                if ($('.payment-loading').length === 0) {
                    $('body').append('<div class="payment-loading"></div>');
                }
                $('.payment-loading').html(html).addClass('active');
            }

            function hidePaymentLoading() {
                $('.payment-loading').removeClass('active');
            }
        });
    </script>
@endpush

@section('customer-content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="content-title">Detail Pesanan</h1>
                <p class="content-subtitle">{{ $order->order_number }}</p>
            </div>
            <span class="status-badge status-{{ $order->status }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Order Items --}}
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
                                <small class="text-muted d-block mt-2">Catatan: {{ $item->catatan_item }}</small>
                            @endif
                        </div>
                        <div class="item-price">{{ $item->formatted_subtotal }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Shipping Address --}}
            <div class="detail-card">
                <h5><i class="lni lni-map-marker"></i> Alamat Pengiriman</h5>
                <p class="mb-1"><strong>{{ $order->penerima_nama }}</strong></p>
                <p class="mb-1">{{ $order->penerima_telepon }}</p>
                <p class="mb-1">{{ $order->alamat_lengkap }}</p>
                <p class="mb-0">{{ $order->kelurahan }}, {{ $order->kecamatan }}<br>{{ $order->kota }},
                    {{ $order->provinsi }} {{ $order->kode_pos }}</p>
                @if ($order->kurir)
                    <div class="mt-3 p-3 bg-white rounded">
                        <strong>Kurir:</strong> {{ $order->kurir }} - {{ $order->service_kurir }}<br>
                        @if ($order->resi)
                            <strong>No. Resi:</strong> {{ $order->resi }}
                        @endif
                    </div>
                @endif
            </div>

            {{-- Shipping Tracking --}}
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
                                    <small class="text-muted">{{ $tracking->tracked_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Price Summary --}}
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

            {{-- Order Status Timeline --}}
            <div class="detail-card">
                <h5><i class="lni lni-information"></i> Status Order</h5>
                <div class="status-timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->status == 'pending_payment' ? 'active' : '' }}"></div>
                        <div class="timeline-line"></div>
                        <div><strong>Menunggu Pembayaran</strong><br><small
                                class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small></div>
                    </div>
                    <div class="timeline-item">
                        <div
                            class="timeline-dot {{ in_array($order->status, ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                        </div>
                        <div class="timeline-line"></div>
                        <div><strong>Dibayar</strong>
                            @if ($order->paid_at)
                                <br><small class="text-muted">{{ $order->paid_at->format('d M Y, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div
                            class="timeline-dot {{ in_array($order->status, ['verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                        </div>
                        <div class="timeline-line"></div>
                        <div><strong>Diverifikasi</strong>
                            @if ($order->verified_at)
                                <br><small class="text-muted">{{ $order->verified_at->format('d M Y, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div
                            class="timeline-dot {{ in_array($order->status, ['in_production', 'ready_to_ship', 'shipped', 'completed']) ? 'active' : '' }}">
                        </div>
                        <div class="timeline-line"></div>
                        <div><strong>Sedang Produksi</strong></div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($order->status, ['shipped', 'completed']) ? 'active' : '' }}">
                        </div>
                        <div class="timeline-line"></div>
                        <div><strong>Sedang Dikirim</strong>
                            @if ($order->shipped_at)
                                <br><small class="text-muted">{{ $order->shipped_at->format('d M Y, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->status == 'completed' ? 'active' : '' }}"></div>
                        <div><strong>Selesai</strong>
                            @if ($order->completed_at)
                                <br><small class="text-muted">{{ $order->completed_at->format('d M Y, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="detail-card">
                <h5><i class="lni lni-cog"></i> Aksi</h5>
                @if ($order->status == 'pending_payment')
                    @if ($order->payment_expired_at && now()->greaterThan($order->payment_expired_at))
                        <div class="alert alert-danger"><i class="lni lni-warning"></i> Waktu pembayaran telah habis</div>
                    @else
                        <button type="button" id="btn-pay-now" class="btn-primary-custom w-100 mb-2">
                            <i class="lni lni-credit-cards"></i> Bayar Sekarang
                        </button>
                        @if ($order->payment_expired_at)
                            <div class="alert alert-warning mt-2">
                                <small><i class="lni lni-timer"></i> Bayar sebelum:
                                    <strong>{{ $order->payment_expired_at->format('d M Y, H:i') }}</strong></small>
                            </div>
                        @endif
                    @endif
                @endif
                @if ($order->canRequestReturn())
                    <a href="{{ route('customer.orders.return', $order->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="lni lni-reload"></i> Ajukan Return
                    </a>
                @endif

                @if (in_array($order->status, ['paid', 'verified', 'in_production']))
                    <button class="btn btn-outline-danger w-100 mb-2">
                        <i class="lni lni-close"></i> Batalkan Pesanan
                    </button>
                @endif
                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="lni lni-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
