<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Data Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" wire:model.live="search" class="form-control"
                                placeholder="Cari nomor order atau nama customer...">
                        </div>
                        <div class="col-md-3">
                            <select wire:model="filterStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending_payment">Menunggu Pembayaran</option>
                                <option value="paid">Sudah Dibayar</option>
                                <option value="verified">Diverifikasi</option>
                                <option value="in_production">Sedang Produksi</option>
                                <option value="ready_to_ship">Siap Dikirim</option>
                                <option value="shipped">Sedang Dikirim</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="perPage" class="form-select">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                                <option value="100">100 per halaman</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No Order</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong><br>
                                            <small class="text-muted">{{ $order->total_item }} item</small>
                                        </td>
                                        <td>
                                            {{ $order->user->name }}<br>
                                            <small class="text-muted">{{ $order->penerima_telepon }}</small>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $order->formatted_total_harga }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->payment_status == 'settlement')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.detail.pesanan', $order->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ti ti-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-muted">Tidak ada data pesanan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
