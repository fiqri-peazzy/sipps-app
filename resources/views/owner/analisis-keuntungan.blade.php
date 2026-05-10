<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analisis Keuntungan (Estimasi)') }}
        </h2>
    </x-slot>

    <div class="row">
        <!-- Monthly Comparison Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Perbandingan Revenue Bulanan</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h6 class="text-muted mb-1">Bulan Lalu</h6>
                            <h4 class="mb-0">Rp {{ number_format($lastMonthRevenue, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Bulan Ini</h6>
                            <h4 class="mb-0 text-primary">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    @php
                        $growth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                    @endphp
                    <div class="mt-4 p-3 rounded {{ $growth >= 0 ? 'bg-light-success' : 'bg-light-danger' }} text-center">
                        <span class="fw-bold">
                            <i class="ti ti-trending-{{ $growth >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($growth), 1) }}% {{ $growth >= 0 ? 'Kenaikan' : 'Penurunan' }} dibanding bulan lalu
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Distribution by Category -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Kontribusi Revenue per Jenis Sablon</h5>
                </div>
                <div class="card-body">
                    <div id="category-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Detail Kontribusi Produk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Jenis Sablon</th>
                                    <th>Total Revenue</th>
                                    <th>Estimasi Profit (30%)</th>
                                    <th class="text-end pe-4">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $maxTotal = $revenueBySablon->max('total') ?: 1; @endphp
                                @foreach($revenueBySablon as $item)
                                <tr>
                                    <td class="ps-4 font-bold">{{ $item->nama_sablon }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td class="text-success">Rp {{ number_format($item->total * 0.3, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4" style="width: 200px;">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: {{ ($item->total / $maxTotal) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: @json($revenueBySablon->pluck('total')),
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: @json($revenueBySablon->pluck('nama_sablon')),
                colors: ['#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f59e0b'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Revenue',
                                    formatter: function (w) {
                                        let total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return "Rp " + total.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#category-chart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
