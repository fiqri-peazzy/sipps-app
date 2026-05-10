<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <form action="{{ route('keuangan.laporan.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">DARI TANGGAL</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">SAMPAI TANGGAL</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('keuangan.laporan.export', request()->all()) }}" class="btn btn-success w-100">
                                <i class="ti ti-file-spreadsheet"></i> Export Excel
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">No. Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalRevenue = 0; @endphp
                                @forelse($orders as $o)
                                @php $totalRevenue += $o->total_harga; @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold">{{ $o->order_number }}</span>
                                    </td>
                                    <td>{{ $o->user->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($o->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ $o->paid_at ? $o->paid_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($o->status) {
                                                'verified' => 'bg-light-success text-success',
                                                'completed' => 'bg-success text-white',
                                                'shipped' => 'bg-light-info text-info',
                                                default => 'bg-light-secondary text-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ strtoupper($o->status) }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('keuangan.detail.pesanan', $o->id) }}" class="btn btn-sm btn-icon btn-light-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($orders->count() > 0)
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="2" class="ps-4">TOTAL (Halaman Ini)</td>
                                    <td colspan="4">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
