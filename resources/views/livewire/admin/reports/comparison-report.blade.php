<div>
    <!-- Filter Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-filter"></i> Filter Periode Perbandingan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model.live="startDate" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" wire:model.live="endDate" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" wire:click="resetFilters" class="btn btn-secondary w-100">
                                <i class="ti ti-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.flex class="col-sm-12">
            <div class="alert alert-info w-100">
                <i class="ti ti-loader spinning"></i> Memuat simulasi perbandingan...
            </div>
        </div>

        <!-- Summary Info -->
        <div wire:loading.remove class="col-sm-12">
            <div class="alert alert-primary">
                <div class="d-flex align-items-center">
                    <i class="ti ti-info-circle me-2" style="font-size: 24px;"></i>
                    <div>
                        <strong>Simulasi Perbandingan FCFS vs DPS</strong><br>
                        <small>Total {{ $comparison['total_items'] }} item produksi dalam periode
                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Cards -->
        <div wire:loading.remove class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="text-white mb-0">
                        <i class="ti ti-sort-ascending"></i> FCFS (First Come First Serve)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">On-Time Rate</small>
                                <h3 class="mb-0">{{ $comparison['fcfs']['on_time_rate'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Avg Completion Time</small>
                                <h3 class="mb-0">{{ $comparison['fcfs']['avg_completion_time'] }} jam</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Efficiency Score</small>
                                <h3 class="mb-0">{{ $comparison['fcfs']['efficiency'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Avg Waiting Time</small>
                                <h3 class="mb-0">{{ $comparison['fcfs']['avg_waiting_time'] }} jam</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Late Rate</small>
                                <h3 class="mb-0 text-danger">{{ $comparison['fcfs']['late_rate'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Throughput</small>
                                <h3 class="mb-0">{{ $comparison['fcfs']['throughput'] }} item/hari</h3>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">Completed</small>
                            <strong>{{ $comparison['fcfs']['completed_items'] }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">On-Time</small>
                            <strong class="text-success">{{ $comparison['fcfs']['on_time_items'] }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Late</small>
                            <strong class="text-danger">{{ $comparison['fcfs']['late_items'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading.remove class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="text-white mb-0">
                        <i class="ti ti-chart-line"></i> DPS (Dynamic Priority Scheduling)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">On-Time Rate</small>
                                <h3 class="mb-0">{{ $comparison['dps']['on_time_rate'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Avg Completion Time</small>
                                <h3 class="mb-0">{{ $comparison['dps']['avg_completion_time'] }} jam</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Efficiency Score</small>
                                <h3 class="mb-0">{{ $comparison['dps']['efficiency'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Avg Waiting Time</small>
                                <h3 class="mb-0">{{ $comparison['dps']['avg_waiting_time'] }} jam</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Late Rate</small>
                                <h3 class="mb-0 text-danger">{{ $comparison['dps']['late_rate'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted d-block mb-1">Throughput</small>
                                <h3 class="mb-0">{{ $comparison['dps']['throughput'] }} item/hari</h3>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">Completed</small>
                            <strong>{{ $comparison['dps']['completed_items'] }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">On-Time</small>
                            <strong class="text-success">{{ $comparison['dps']['on_time_items'] }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Late</small>
                            <strong class="text-danger">{{ $comparison['dps']['late_items'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improvements Summary -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="ti ti-trending-up"></i> Peningkatan Performa (DPS vs FCFS)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="ti ti-check-circle"
                                    style="font-size: 48px; color: {{ $comparison['improvements']['on_time_rate'] >= 0 ? '#28a745' : '#dc3545' }};"></i>
                                <h3 class="mt-2"
                                    style="color: {{ $comparison['improvements']['on_time_rate'] >= 0 ? '#28a745' : '#dc3545' }};">
                                    {{ $comparison['improvements']['on_time_rate'] >= 0 ? '+' : '' }}{{ $comparison['improvements']['on_time_rate'] }}%
                                </h3>
                                <p class="text-muted mb-0">On-Time Delivery Rate</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="ti ti-clock"
                                    style="font-size: 48px; color: {{ $comparison['improvements']['avg_completion_time'] >= 0 ? '#28a745' : '#dc3545' }};"></i>
                                <h3 class="mt-2"
                                    style="color: {{ $comparison['improvements']['avg_completion_time'] >= 0 ? '#28a745' : '#dc3545' }};">
                                    {{ $comparison['improvements']['avg_completion_time'] >= 0 ? '-' : '+' }}{{ abs($comparison['improvements']['avg_completion_time']) }}
                                    jam
                                </h3>
                                <p class="text-muted mb-0">Completion Time (lebih cepat)</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="ti ti-activity"
                                    style="font-size: 48px; color: {{ $comparison['improvements']['efficiency'] >= 0 ? '#28a745' : '#dc3545' }};"></i>
                                <h3 class="mt-2"
                                    style="color: {{ $comparison['improvements']['efficiency'] >= 0 ? '#28a745' : '#dc3545' }};">
                                    {{ $comparison['improvements']['efficiency'] >= 0 ? '+' : '' }}{{ $comparison['improvements']['efficiency'] }}%
                                </h3>
                                <p class="text-muted mb-0">Efficiency Score</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export PDF Button -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card">
                <div class="card-body text-center">
                    <button type="button" wire:click="exportPdf" class="btn btn-danger btn-lg"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="exportPdf">
                            <i class="ti ti-file-type-pdf"></i> Export Laporan PDF
                        </span>
                        <span wire:loading wire:target="exportPdf">
                            <i class="ti ti-loader spinning"></i> Generating PDF...
                        </span>
                    </button>
                    <p class="text-muted mt-2 mb-0">
                        <small>Laporan perbandingan untuk dokumentasi skripsi</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Detail Items Table -->
        <div wire:loading.remove class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Data Sample Item ({{ $items->total() }} data)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Order</th>
                                    <th>Produk</th>
                                    <th>Created At</th>
                                    <th>Priority Score</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                    <th>Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $item->order->order_number }}</strong>
                                        </td>
                                        <td>{{ $item->produk->nama ?? 'N/A' }}</td>
                                        <td>{{ $item->order->created_at->format('d M Y H:i') }}</td>
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
                                            <span
                                                class="badge bg-{{ $priorityColor }}">{{ $item->priority_score }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'waiting' => 'secondary',
                                                    'in_queue' => 'info',
                                                    'in_progress' => 'warning',
                                                    'completed' => 'success',
                                                ];
                                            @endphp
                                            <span
                                                class="badge bg-{{ $statusColors[$item->production_status] ?? 'secondary' }}">
                                                {{ ucfirst($item->production_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->deadline)
                                                {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->order->completed_at)
                                                @php
                                                    $isOnTime =
                                                        $item->deadline &&
                                                        $item->order->completed_at->lte($item->deadline);
                                                @endphp
                                                <span class="badge bg-{{ $isOnTime ? 'success' : 'danger' }}">
                                                    {{ $item->order->completed_at->format('d M Y') }}
                                                    @if ($isOnTime)
                                                        <i class="ti ti-check"></i>
                                                    @else
                                                        <i class="ti ti-x"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
