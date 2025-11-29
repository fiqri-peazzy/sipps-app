<div>
    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-clock-pause" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-white">Menunggu</h6>
                            <h3 class="mb-0 text-white">{{ $stats['waiting'] }}</h3>
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
                            <i class="ti ti-list-check" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-white">Dalam Antrian</h6>
                            <h3 class="mb-0 text-white">{{ $stats['in_queue'] }}</h3>
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
                            <i class="ti ti-progress" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-white">Dalam Produksi</h6>
                            <h3 class="mb-0 text-white">{{ $stats['in_progress'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-hourglass" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-white">Rata-rata Tunggu</h6>
                            <h3 class="mb-0 text-white">{{ number_format($stats['avg_waiting_hours'], 1) }}h</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status Produksi</label>
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="waiting">Menunggu</option>
                        <option value="in_queue">Dalam Antrian</option>
                        <option value="in_progress">Dalam Produksi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Sablon</label>
                    <select wire:model.live="filterJenisSablon" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach ($jenisSablonList as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari Nomor Order</label>
                    <input type="text" wire:model.live="searchOrder" class="form-control"
                        placeholder="Contoh: ORD-20251119-001">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button wire:click="recalculateAll" class="btn btn-primary w-100">
                        <i class="ti ti-refresh"></i> Hitung Ulang Semua Prioritas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Queue Table -->
    <div class="card">
        <div class="card-header">
            <h5>Antrian Prioritas Produksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th wire:click="sortByColumn('priority_score')" style="cursor: pointer;">
                                Priority Score
                                @if ($sortBy === 'priority_score')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortByColumn('deadline')" style="cursor: pointer;">
                                Deadline
                                @if ($sortBy === 'deadline')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Order Number</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Kompleksitas</th>
                            <th>Waktu Tunggu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderItems as $index => $item)
                            @php
                                $priorityColor = \App\Services\PriorityCalculator::getPriorityColor(
                                    $item->priority_score,
                                );
                                $priorityRank = \App\Services\PriorityCalculator::getPriorityRank(
                                    $item->priority_score,
                                );
                                $deadlineClass = $item->deadline->isPast()
                                    ? 'text-danger fw-bold'
                                    : ($item->deadline->diffInHours() < 24
                                        ? 'text-warning'
                                        : '');
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">
                                        #{{ $orderItems->firstItem() + $index }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="width: 80px; height: 20px;">
                                            <div class="progress-bar bg-{{ $priorityColor }}" role="progressbar"
                                                style="width: {{ $item->priority_score }}%">
                                                {{ $item->priority_score }}
                                            </div>
                                        </div>
                                        <span class="ms-2 badge bg-{{ $priorityColor }}">{{ $priorityRank }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $deadlineClass }}">
                                        {{ $item->deadline->format('d M Y H:i') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        @if ($item->deadline->isPast())
                                            <i class="ti ti-alert-circle"></i> Terlambat
                                            {{ $item->deadline->diffForHumans() }}
                                        @else
                                            {{ $item->deadline->diffForHumans() }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.detail.pesanan', $item->order_id) }}" target="_blank">
                                        {{ $item->order->order_number }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $item->order->user->name }}</small>
                                </td>
                                <td>
                                    <strong>{{ $item->produk->jenisSablon->nama }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $item->produk->ukuran->nama }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->quantity }} pcs</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="badge bg-secondary">{{ number_format($item->complexity_score, 2) }}/10</span>
                                        @if ($item->hasComplexityReview())
                                            <i class="ti ti-check-circle text-success ms-1" title="Sudah direview"></i>
                                        @else
                                            <i class="ti ti-alert-circle text-warning ms-1"
                                                title="Belum direview"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ number_format($item->waiting_time_hours) }} jam
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusBadge = match ($item->production_status) {
                                            'waiting' => 'bg-secondary',
                                            'in_queue' => 'bg-info',
                                            'in_progress' => 'bg-warning',
                                            'completed' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                        $statusLabel = match ($item->production_status) {
                                            'waiting' => 'Menunggu',
                                            'in_queue' => 'Antrian',
                                            'in_progress' => 'Produksi',
                                            'completed' => 'Selesai',
                                            default => $item->production_status,
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="showDetail({{ $item->id }})" class="btn btn-info"
                                            title="Lihat Detail Perhitungan">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button wire:click="openComplexityReview({{ $item->id }})"
                                            class="btn btn-warning" title="Review Kompleksitas">
                                            <i class="ti ti-star"></i>
                                        </button>
                                        @if ($item->production_status !== 'in_progress')
                                            <button wire:click="mulaiProduksi({{ $item->id }})"
                                                class="btn btn-success" title="Mulai Produksi">
                                                <i class="ti ti-player-play"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="ti ti-inbox" style="font-size: 3rem;"></i>
                                    <p class="text-muted">Tidak ada data order dalam antrian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $orderItems->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail Perhitungan (untuk skripsi) -->
    @if ($showDetailModal && $selectedItem)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Perhitungan Dynamic Priority Scheduling</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $breakdown = \App\Services\PriorityCalculator::getFactorsBreakdown($selectedItem);
                            $complexityBreakdown = \App\Services\ComplexityCalculator::getCalculationBreakdown(
                                $selectedItem,
                            );
                        @endphp

                        <!-- Order Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informasi Order</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Order Number:</strong> {{ $selectedItem->order->order_number }}</p>
                                        <p><strong>Customer:</strong> {{ $selectedItem->order->user->name }}</p>
                                        <p><strong>Produk:</strong> {{ $selectedItem->produk->jenisSablon->nama }} -
                                            {{ $selectedItem->produk->ukuran->nama }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Quantity:</strong> {{ $selectedItem->quantity }} pcs</p>
                                        <p><strong>Deadline:</strong>
                                            {{ $selectedItem->deadline->format('d M Y H:i') }}</p>
                                        <p><strong>Status:</strong>
                                            <span class="badge bg-info">{{ $selectedItem->production_status }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Priority Score Calculation -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Perhitungan Priority Score</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Formula:</strong><br>
                                    Priority Score = (Urgency × {{ $breakdown['urgency']['weight'] }}) +
                                    (Complexity × {{ $breakdown['complexity']['weight'] }}) +
                                    (Waiting Time × {{ $breakdown['waiting_time']['weight'] }}) +
                                    (Quantity × {{ $breakdown['quantity']['weight'] }})
                                </div>

                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Faktor</th>
                                            <th>Raw Score</th>
                                            <th>Bobot</th>
                                            <th>Weighted Score</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Urgency (Deadline)</strong></td>
                                            <td>{{ $breakdown['urgency']['raw_score'] }}/100</td>
                                            <td>{{ $breakdown['urgency']['weight'] }}</td>
                                            <td><span
                                                    class="badge bg-primary">{{ $breakdown['urgency']['weighted_score'] }}</span>
                                            </td>
                                            <td>
                                                Deadline: {{ $breakdown['urgency']['deadline'] }}<br>
                                                Sisa waktu:
                                                {{ number_format($breakdown['urgency']['remaining_hours'], 1) }} jam
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Complexity (Desain)</strong></td>
                                            <td>{{ $breakdown['complexity']['raw_score'] }}/100</td>
                                            <td>{{ $breakdown['complexity']['weight'] }}</td>
                                            <td><span
                                                    class="badge bg-primary">{{ $breakdown['complexity']['weighted_score'] }}</span>
                                            </td>
                                            <td>
                                                Complexity Score:
                                                {{ $breakdown['complexity']['complexity_score_original'] }}/10
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Waiting Time</strong></td>
                                            <td>{{ $breakdown['waiting_time']['raw_score'] }}/100</td>
                                            <td>{{ $breakdown['waiting_time']['weight'] }}</td>
                                            <td><span
                                                    class="badge bg-primary">{{ $breakdown['waiting_time']['weighted_score'] }}</span>
                                            </td>
                                            <td>
                                                Menunggu:
                                                {{ number_format($breakdown['waiting_time']['waiting_hours']) }}
                                                jam
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quantity</strong></td>
                                            <td>{{ $breakdown['quantity']['raw_score'] }}/100</td>
                                            <td>{{ $breakdown['quantity']['weight'] }}</td>
                                            <td><span
                                                    class="badge bg-primary">{{ $breakdown['quantity']['weighted_score'] }}</span>
                                            </td>
                                            <td>
                                                {{ $breakdown['quantity']['quantity_value'] }} pcs
                                            </td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="3"><strong>TOTAL PRIORITY SCORE</strong></td>
                                            <td colspan="2">
                                                <h4 class="mb-0">
                                                    <span
                                                        class="badge bg-success">{{ $breakdown['final_score'] }}/100</span>
                                                    <span class="badge bg-secondary ms-2">
                                                        {{ \App\Services\PriorityCalculator::getPriorityRank($breakdown['final_score']) }}
                                                    </span>
                                                </h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Complexity Score Breakdown -->
                        <div class="card">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">Detail Perhitungan Complexity Score</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <strong>Formula Hybrid:</strong><br>
                                    Complexity Score = (Auto Score × 0.6) + (Manual Score × 0.4)
                                </div>

                                <table class="table table-bordered mb-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Score</th>
                                            <th>Bobot</th>
                                            <th>Weighted Score</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($complexityBreakdown as $key => $param)
                                            <tr>
                                                <td><strong>{{ $param['label'] }}</strong></td>
                                                <td>{{ number_format($param['value'], 2) }}/10</td>
                                                <td>{{ $param['weight'] }}</td>
                                                <td><span
                                                        class="badge bg-info">{{ number_format($param['value'] * $param['weight'], 2) }}</span>
                                                </td>
                                                <td>{{ $param['detail'] }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-warning">
                                            <td colspan="3"><strong>AUTO COMPLEXITY SCORE</strong></td>
                                            <td colspan="2">
                                                <span
                                                    class="badge bg-warning">{{ number_format($selectedItem->auto_complexity_score, 2) }}/10</span>
                                            </td>
                                        </tr>
                                        @if ($selectedItem->manual_complexity_score)
                                            <tr>
                                                <td colspan="3"><strong>MANUAL COMPLEXITY SCORE (Admin
                                                        Review)</strong></td>
                                                <td colspan="2">
                                                    <span
                                                        class="badge bg-primary">{{ number_format($selectedItem->manual_complexity_score, 2) }}/10</span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Direview oleh:
                                                        {{ $selectedItem->complexityReviewedBy->name ?? 'N/A' }}
                                                        pada
                                                        {{ $selectedItem->complexity_reviewed_at?->format('d M Y H:i') }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr class="table-success">
                                            <td colspan="3"><strong>FINAL COMPLEXITY SCORE (Hybrid)</strong></td>
                                            <td colspan="2">
                                                <h5 class="mb-0">
                                                    <span
                                                        class="badge bg-success">{{ number_format($selectedItem->complexity_score, 2) }}/10</span>
                                                </h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="ti ti-printer"></i> Print Perhitungan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Review Kompleksitas -->
    @if ($showComplexityModal && $selectedItem)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Review Kompleksitas Desain</h5>
                        <button type="button" class="btn-close" wire:click="closeComplexityModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Order Info -->
                        <div class="alert alert-info">
                            <strong>Order:</strong> {{ $selectedItem->order->order_number }}<br>
                            <strong>Produk:</strong> {{ $selectedItem->produk->jenisSablon->nama }} -
                            {{ $selectedItem->produk->ukuran->nama }}
                        </div>

                        <!-- Auto Score -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Score Otomatis dari Sistem</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $selectedItem->auto_complexity_score * 10 }}%">
                                                {{ number_format($selectedItem->auto_complexity_score, 2) }}/10
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $complexityBreakdown = \App\Services\ComplexityCalculator::getCalculationBreakdown(
                                        $selectedItem,
                                    );
                                @endphp

                                <ul class="list-group list-group-flush">
                                    @foreach ($complexityBreakdown as $param)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $param['label'] }}
                                            <span class="badge bg-secondary">{{ number_format($param['value'], 1) }} ×
                                                {{ $param['weight'] }} =
                                                {{ number_format($param['value'] * $param['weight'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Manual Score Input -->
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0 text-white">Penilaian Manual Admin</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Score Kompleksitas Manual (0-10)</label>
                                    <input type="number" wire:model="manualComplexityScore" class="form-control"
                                        min="0" max="10" step="0.1">
                                    @error('manualComplexityScore')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">
                                        Panduan: 0-3 (Sederhana), 4-6 (Menengah), 7-10 (Kompleks)
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Catatan (Opsional)</label>
                                    <textarea wire:model="complexityNotes" class="form-control" rows="3"
                                        placeholder="Contoh: Desain full color dengan gradient, memerlukan perhatian khusus..."></textarea>
                                    @error('complexityNotes')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if ($manualComplexityScore)
                                    <div class="alert alert-success">
                                        <strong>Preview Hybrid Score:</strong><br>
                                        ({{ number_format($selectedItem->auto_complexity_score, 2) }} × 0.6) +
                                        ({{ number_format($manualComplexityScore, 2) }} × 0.4) =
                                        <strong>{{ number_format($selectedItem->auto_complexity_score * 0.6 + $manualComplexityScore * 0.4, 2) }}/10</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeComplexityModal">Batal</button>
                        <button type="button" class="btn btn-warning" wire:click="saveComplexityReview">
                            <i class="ti ti-device-floppy"></i> Simpan Penilaian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
