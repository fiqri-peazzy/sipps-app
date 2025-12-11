<div>
    <!-- Filter Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-filter"></i> Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" wire:model.live="endDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Order</label>
                            <select wire:model.live="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending_payment">Menunggu Pembayaran</option>
                                <option value="paid">Sudah Dibayar</option>
                                <option value="verified">Diverifikasi</option>
                                <option value="in_production">Sedang Produksi</option>
                                <option value="ready_to_ship">Siap Kirim</option>
                                <option value="shipped">Sedang Dikirim</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                                <option value="return_requested">Ajuan Return</option>
                                <option value="returned">Dikembalikan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" wire:click="resetFilters" class="btn btn-secondary">
                            <i class="ti ti-refresh"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.flex class="col-sm-12">
            <div class="alert alert-info w-100">
                <i class="ti ti-loader spinning"></i> Memuat data...
            </div>
        </div>

        <!-- Summary Statistics -->
        <div wire:loading.remove class="col-sm-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-shopping-cart" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Total Pesanan</h6>
                                    <h3 class="text-white mb-0">{{ $stats['total_orders'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-package" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Total Item</h6>
                                    <h3 class="text-white mb-0">{{ $stats['total_items'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-cash" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Total Revenue</h6>
                                    <h3 class="text-white mb-0">Rp
                                        {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-trending-up" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Rata-rata Nilai Order</h6>
                                    <h3 class="text-white mb-0">Rp
                                        {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Cards -->
        <div wire:loading.remove class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Breakdown Status Order</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $statusColors = [
                                        'pending_payment' => 'warning',
                                        'paid' => 'info',
                                        'verified' => 'primary',
                                        'in_production' => 'secondary',
                                        'ready_to_ship' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'return_requested' => 'warning',
                                        'returned' => 'dark',
                                    ];

                                    $statusLabels = [
                                        'pending_payment' => 'Menunggu Pembayaran',
                                        'paid' => 'Sudah Dibayar',
                                        'verified' => 'Diverifikasi',
                                        'in_production' => 'Sedang Produksi',
                                        'ready_to_ship' => 'Siap Kirim',
                                        'shipped' => 'Sedang Dikirim',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        'return_requested' => 'Ajuan Return',
                                        'returned' => 'Dikembalikan',
                                    ];
                                @endphp

                                @forelse($stats['status_breakdown'] as $statusKey => $count)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $statusColors[$statusKey] ?? 'secondary' }}">
                                                {{ $statusLabels[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey)) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td class="text-end">
                                            {{ $stats['total_orders'] > 0 ? round(($count / $stats['total_orders']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading.remove class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Breakdown Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $paymentColors = [
                                        'pending' => 'secondary',
                                        'settlement' => 'success',
                                        'capture' => 'success',
                                        'deny' => 'danger',
                                        'cancel' => 'danger',
                                        'expire' => 'warning',
                                        'failure' => 'danger',
                                    ];
                                @endphp

                                @forelse($stats['payment_breakdown'] as $paymentStatus => $count)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $paymentColors[$paymentStatus] ?? 'secondary' }}">
                                                {{ ucfirst($paymentStatus) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td class="text-end">
                                            {{ $stats['total_orders'] > 0 ? round(($count / $stats['total_orders']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Detail Pesanan ({{ $orders->total() }} data)</h5>
                    <button type="button" wire:click="exportPdf" class="btn btn-danger"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="exportPdf">
                            <i class="ti ti-file-type-pdf"></i> Export PDF
                        </span>
                        <span wire:loading wire:target="exportPdf">
                            <i class="ti ti-loader spinning"></i> Generating...
                        </span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Order</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Total Item</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $index => $order)
                                    <tr>
                                        <td>{{ $orders->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            {{ $order->penerima_nama }}<br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </td>
                                        <td>{{ $order->total_item }} item</td>
                                        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentColors = [
                                                    'pending' => 'secondary',
                                                    'settlement' => 'success',
                                                    'capture' => 'success',
                                                    'deny' => 'danger',
                                                    'cancel' => 'danger',
                                                    'expire' => 'warning',
                                                    'failure' => 'danger',
                                                ];
                                            @endphp
                                            <span
                                                class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="ti ti-inbox" style="font-size: 64px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-3 mb-0">Tidak ada data untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .spinning {
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
        </style>
    @endpush
</div>
