<div>
    <!-- Period Filter -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">Dashboard Analitik</h5>
                            <small class="text-muted">Visualisasi data operasional dan finansial</small>
                        </div>
                        <div class="col-md-4">
                            <select wire:model.live="periodDays" class="form-select">
                                <option value="7">7 Hari Terakhir</option>
                                <option value="30">30 Hari Terakhir</option>
                                <option value="60">60 Hari Terakhir</option>
                                <option value="90">90 Hari Terakhir</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Metrics -->
        <div class="col-sm-12">
            <h6 class="mb-3">Metrik Operasional</h6>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-shopping-cart" style="font-size: 48px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">Total Orders</h6>
                            <h3 class="text-white mb-0">{{ $operational['total_orders'] }}</h3>
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
                            <i class="ti ti-truck-delivery" style="font-size: 48px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">Completed</h6>
                            <h3 class="text-white mb-0">{{ $operational['completed_orders'] }}</h3>
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
                            <i class="ti ti-percentage" style="font-size: 48px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">On-Time Rate</h6>
                            <h3 class="text-white mb-0">{{ $operational['on_time_rate'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle" style="font-size: 48px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">Returns</h6>
                            <h3 class="text-white mb-0">{{ $operational['total_returns'] }}</h3>
                            <small class="text-white">{{ $operational['pending_returns'] }} pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pesanan Harian (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyOrdersChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Status Produksi</h5>
                </div>
                <div class="card-body">
                    <canvas id="productionChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Financial Metrics -->
        <div class="col-sm-12 mt-4">
            <h6 class="mb-3">Metrik Finansial</h6>
        </div>

        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-cash" style="font-size: 64px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">Total Revenue</h6>
                            <h3 class="text-white mb-0">Rp {{ number_format($financial['total_revenue'], 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-trending-up" style="font-size: 64px; opacity: 0.3;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white mb-1">Avg Order Value</h6>
                            <h3 class="text-white mb-0">Rp
                                {{ number_format($financial['avg_order_value'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Revenue Harian (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Top 5 Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($financial['top_products'] as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->produk->jenisSablon->nama ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">
                                                {{ $item->produk->ukuran->nama ?? '-' }} |
                                                <span
                                                    class="badge badge-sm bg-{{ $item->produk->tipe_layanan == 'express' ? 'danger' : 'secondary' }}">
                                                    {{ ucfirst($item->produk->tipe_layanan ?? '-') }}
                                                </span>
                                            </small>
                                        </td>
                                        <td class="text-center">{{ $item->count }}</td>
                                        <td class="text-center">{{ $item->total_qty }}</td>
                                        <td class="text-end">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Breakdown -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-center">Count</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($financial['payment_stats'] as $status => $data)
                                    <tr>
                                        <td>
                                            @php
                                                $colors = [
                                                    'settlement' => 'success',
                                                    'capture' => 'success',
                                                    'pending' => 'warning',
                                                    'expire' => 'danger',
                                                    'cancel' => 'danger',
                                                    'deny' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$status] ?? 'secondary' }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $data['count'] }}</td>
                                        <td class="text-end">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Status Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operational['shipping_stats'] as $status => $count)
                                    <tr>
                                        <td>
                                            @php
                                                $colors = [
                                                    'delivered' => 'success',
                                                    'in_transit' => 'info',
                                                    'picked_up' => 'warning',
                                                    'pending' => 'secondary',
                                                    'returned' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Daily Orders Chart
            const dailyOrdersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
            new Chart(dailyOrdersCtx, {
                type: 'line',
                data: {
                    labels: @json($charts['daily_orders']['labels']),
                    datasets: [{
                        label: 'Pesanan',
                        data: @json($charts['daily_orders']['data']),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Production Status Chart
            const productionCtx = document.getElementById('productionChart').getContext('2d');
            const productionData = @json($charts['production_distribution']);
            new Chart(productionCtx, {
                type: 'doughnut',
                data: {
                    labels: productionData.map(item => {
                        const labels = {
                            'waiting': 'Menunggu',
                            'in_queue': 'Antrian',
                            'in_progress': 'Dikerjakan',
                            'completed': 'Selesai'
                        };
                        return labels[item.production_status] || item.production_status;
                    }),
                    datasets: [{
                        data: productionData.map(item => item.count),
                        backgroundColor: [
                            '#6c757d',
                            '#17a2b8',
                            '#ffc107',
                            '#28a745'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Daily Revenue Chart
            const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            new Chart(dailyRevenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($charts['daily_revenue']['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($charts['daily_revenue']['data']),
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</div>
