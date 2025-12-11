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
                            <label class="form-label">Status Produksi</label>
                            <select wire:model.live="productionStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="waiting">Menunggu</option>
                                <option value="in_queue">Antrian</option>
                                <option value="in_progress">Sedang Dikerjakan</option>
                                <option value="completed">Selesai</option>
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

        <!-- Key Metrics -->
        <div wire:loading.remove class="col-sm-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
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
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-clock" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Rata-rata Waiting Time</h6>
                                    <h3 class="text-white mb-0">{{ $stats['avg_waiting_time'] }} jam</h3>
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
                                    <i class="ti ti-check" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">On-Time Delivery</h6>
                                    <h3 class="text-white mb-0">{{ $stats['on_time_rate'] }}%</h3>
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
                                    <i class="ti ti-star" style="font-size: 48px; opacity: 0.3;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-white mb-1">Avg Priority Score</h6>
                                    <h3 class="text-white mb-0">{{ $stats['avg_priority_score'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Priority Distribution & Status Breakdown -->
        <div wire:loading.remove class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Distribusi Prioritas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori Prioritas</th>
                                    <th>Range Score</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $priorityLabels = [
                                        'very_low' => [
                                            'label' => 'Sangat Rendah',
                                            'range' => '0-20',
                                            'color' => 'secondary',
                                        ],
                                        'low' => ['label' => 'Rendah', 'range' => '21-40', 'color' => 'info'],
                                        'medium' => ['label' => 'Menengah', 'range' => '41-60', 'color' => 'primary'],
                                        'high' => ['label' => 'Tinggi', 'range' => '61-80', 'color' => 'warning'],
                                        'very_high' => [
                                            'label' => 'Sangat Tinggi',
                                            'range' => '81-100',
                                            'color' => 'danger',
                                        ],
                                    ];
                                @endphp
                                @foreach ($stats['priority_distribution'] as $key => $count)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $priorityLabels[$key]['color'] }}">
                                                {{ $priorityLabels[$key]['label'] }}
                                            </span>
                                        </td>
                                        <td>{{ $priorityLabels[$key]['range'] }}</td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td class="text-end">
                                            {{ $stats['total_items'] > 0 ? round(($count / $stats['total_items']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading.remove class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Status Produksi</h5>
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
                                    $statusLabels = [
                                        'waiting' => ['label' => 'Menunggu', 'color' => 'secondary'],
                                        'in_queue' => ['label' => 'Antrian', 'color' => 'info'],
                                        'in_progress' => ['label' => 'Sedang Dikerjakan', 'color' => 'warning'],
                                        'completed' => ['label' => 'Selesai', 'color' => 'success'],
                                    ];
                                @endphp
                                @forelse($stats['status_breakdown'] as $status => $count)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $statusLabels[$status]['color'] ?? 'secondary' }}">
                                                {{ $statusLabels[$status]['label'] ?? ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td class="text-end">
                                            {{ $stats['total_items'] > 0 ? round(($count / $stats['total_items']) * 100, 1) : 0 }}%
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

        <!-- Additional Metrics -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Metrik Tambahan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small">Avg Complexity Score</label>
                                <h4 class="mb-0">{{ $stats['avg_complexity_score'] }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small">Total Recalculations</label>
                                <h4 class="mb-0">{{ $stats['total_recalculations'] }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small">Completed Items</label>
                                <h4 class="mb-0">{{ $stats['completed_items'] }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small">On-Time Items</label>
                                <h4 class="mb-0">{{ $stats['on_time_items'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Detail Item Produksi ({{ $items->total() }} data)</h5>
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
                                    <th>Order</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Priority</th>
                                    <th>Complexity</th>
                                    <th>Waiting Time</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $item->order->order_number }}</strong><br>
                                            <small
                                                class="text-muted">{{ $item->order->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $item->produk->nama ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            @php
                                                $priorityColor = 'secondary';
                                                if ($item->priority_score >= 80) {
                                                    $priorityColor = 'danger';
                                                } elseif ($item->priority_score >= 60) {
                                                    $priorityColor = 'warning';
                                                } elseif ($item->priority_score >= 40) {
                                                    $priorityColor = 'info';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $priorityColor }}">
                                                {{ $item->priority_score }}
                                            </span>
                                        </td>
                                        <td>{{ $item->complexity_score }}</td>
                                        <td>{{ $item->waiting_time_hours }} jam</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'waiting' => 'secondary',
                                                    'in_queue' => 'info',
                                                    'in_progress' => 'warning',
                                                    'completed' => 'success',
                                                ];
                                                $statusLabels = [
                                                    'waiting' => 'Menunggu',
                                                    'in_queue' => 'Antrian',
                                                    'in_progress' => 'Dikerjakan',
                                                    'completed' => 'Selesai',
                                                ];
                                            @endphp
                                            <span
                                                class="badge bg-{{ $statusColors[$item->production_status] ?? 'secondary' }}">
                                                {{ $statusLabels[$item->production_status] ?? ucfirst($item->production_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->deadline)
                                                {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="ti ti-inbox" style="font-size: 64px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-3 mb-0">Tidak ada data untuk periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $items->links() }}
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
