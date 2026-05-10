<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Financial Dashboard') }}
        </h2>
    </x-slot>

    <div class="row">
        <!-- Revenue This Month -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar bg-light-success text-success rounded-circle">
                            <i class="ti ti-cash" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pemasukan Bulan Ini</h6>
                            <h4 class="mb-0">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Verification -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar bg-light-warning text-warning rounded-circle">
                            <i class="ti ti-clock" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending Verifikasi</h6>
                            <h4 class="mb-0">{{ $pendingVerification }} Pesanan</h4>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-sm btn-light-warning">Cek &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Receivables -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar bg-light-danger text-danger rounded-circle">
                            <i class="ti ti-report-money" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Piutang (Pending)</h6>
                            <h4 class="mb-0">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="ti ti-receipt-2 d-block mb-1" style="font-size: 1.5rem;"></i>
                                Verifikasi Pembayaran
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('keuangan.laporan.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="ti ti-file-invoice d-block mb-1" style="font-size: 1.5rem;"></i>
                                Laporan Keuangan
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('keuangan.laporan.export') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="ti ti-file-spreadsheet d-block mb-1" style="font-size: 1.5rem;"></i>
                                Export Excel (Rekap)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
