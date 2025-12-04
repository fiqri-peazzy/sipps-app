<x-app-layout>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produksi</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Daftar Order Dalam Produksi</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @forelse($orders as $order)
                @php
                    $totalItems = $order->items->count();
                    $completedItems = $order->items->where('production_status', 'completed')->count();
                    $progressPercent = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                @endphp

                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="ti ti-package"></i> {{ $order->order_number }}
                                </h5>
                                <small class="text-muted">
                                    {{ $order->penerima_nama }} | {{ $order->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="mb-2">
                                    <span class="badge bg-{{ $progressPercent == 100 ? 'success' : 'warning' }}">
                                        Progress: {{ $progressPercent }}%
                                    </span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $progressPercent == 100 ? 'success' : 'primary' }}"
                                        role="progressbar" style="width: {{ $progressPercent }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Priority</th>
                                        <th>Deadline</th>
                                        <th>Status Produksi</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->produk->jenisSablon->nama }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->produk->ukuran->nama }}
                                                    @if ($item->ukuran_kaos)
                                                        | {{ $item->ukuran_kaos }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $item->quantity }} pcs</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    Score: {{ $item->priority_score }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->deadline)
                                                    <small>{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
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
                                                        'in_queue' => 'Dalam Antrian',
                                                        'in_progress' => 'Sedang Dikerjakan',
                                                        'completed' => 'Selesai',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusColors[$item->production_status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$item->production_status] ?? $item->production_status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->production_status == 'waiting' || $item->production_status == 'in_queue')
                                                    <form
                                                        action="{{ route('admin.production.start-item', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary"
                                                            title="Mulai Produksi">
                                                            <i class="ti ti-player-play"></i>
                                                        </button>
                                                    </form>
                                                @elseif($item->production_status == 'in_progress')
                                                    <form
                                                        action="{{ route('admin.production.complete-item', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Tandai Selesai">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="ti ti-check"></i> Done
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        @if ($progressPercent == 100)
                            <form action="{{ route('admin.production.complete', $order->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Tandai produksi selesai dan siap kirim?')">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-check"></i> Selesai Produksi & Siap Kirim
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="ti ti-clock"></i> Menunggu Semua Item Selesai
                                ({{ $completedItems }}/{{ $totalItems }})
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-package" style="font-size: 64px; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">Tidak ada order dalam produksi</h5>
                        <p class="text-muted">Order yang sudah diverifikasi akan muncul di sini</p>
                    </div>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
