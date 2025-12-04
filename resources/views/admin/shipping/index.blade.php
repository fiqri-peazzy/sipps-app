<x-app-layout>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Proses Pengiriman</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Proses Pengiriman</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Pesanan Siap Kirim</h5>
                </div>
                <div class="card-body">
                    <!-- Filter & Search -->
                    <form method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status Order</option>
                                    <option value="ready_to_ship"
                                        {{ request('status') == 'ready_to_ship' ? 'selected' : '' }}>Siap Kirim</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dalam
                                        Pengiriman</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status_pengiriman" class="form-select">
                                    <option value="">Semua Status Pengiriman</option>
                                    <option value="pending"
                                        {{ request('status_pengiriman') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="picked_up"
                                        {{ request('status_pengiriman') == 'picked_up' ? 'selected' : '' }}>Diambil
                                        Kurir
                                    </option>
                                    <option value="in_transit"
                                        {{ request('status_pengiriman') == 'in_transit' ? 'selected' : '' }}>Dalam
                                        Perjalanan</option>
                                    <option value="delivered"
                                        {{ request('status_pengiriman') == 'delivered' ? 'selected' : '' }}>Terkirim
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="kurir" class="form-select">
                                    <option value="">Semua Kurir</option>
                                    @foreach ($kurirs as $kurir)
                                        <option value="{{ $kurir }}"
                                            {{ request('kurir') == $kurir ? 'selected' : '' }}>
                                            {{ $kurir }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari order, resi, nama..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Order</th>
                                    <th>Customer</th>
                                    <th>Tujuan</th>
                                    <th>Kurir</th>
                                    <th>No. Resi</th>
                                    <th>Status Order</th>
                                    <th>Status Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            {{ $order->penerima_nama }}
                                            <br>
                                            <small class="text-muted">{{ $order->penerima_telepon }}</small>
                                        </td>
                                        <td>
                                            {{ $order->kota }}
                                            <br>
                                            <small class="text-muted">{{ $order->kecamatan }}</small>
                                        </td>
                                        <td>
                                            @if ($order->kurir)
                                                <span class="badge bg-info">{{ $order->kurir }}</span>
                                                @if ($order->service_kurir)
                                                    <br><small>{{ $order->service_kurir }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->resi)
                                                <code>{{ $order->resi }}</code>
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
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
                                            <span
                                                class="badge bg-{{ $statusColors[$order->status_pengiriman] ?? 'secondary' }}">
                                                {{ $statusLabels[$order->status_pengiriman] ?? $order->status_pengiriman }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.shipping.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ti ti-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2">Tidak ada data pengiriman</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
