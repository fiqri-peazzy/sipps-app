<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <form action="{{ route('keuangan.pembayaran.index') }}" method="GET" class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" 
                                    placeholder="Cari No. Pesanan atau Nama..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 rounded-0 mb-0" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-0 mb-0" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">No. Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Tanggal Bayar</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold text-primary">{{ $o->order_number }}</span>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $o->user->name ?? '-' }}</h6>
                                    </td>
                                    <td>Rp {{ number_format($o->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ $o->paid_at ? $o->paid_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('keuangan.detail.pesanan', $o->id) }}" class="btn btn-sm btn-light-secondary" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <form action="{{ route('keuangan.verifikasi', $o->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Konfirmasi verifikasi pembayaran untuk pesanan ini?')" title="Verifikasi">
                                                    <i class="ti ti-check"></i> Verifikasi
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <p class="text-muted mb-0">Tidak ada pesanan menunggu verifikasi.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
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
