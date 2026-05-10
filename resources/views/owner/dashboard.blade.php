<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Executive Dashboard') }}
        </h2>
    </x-slot>

    <div class="row">
        <!-- Total Revenue -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-currency-dollar" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white text-opacity-75 mb-1">Total Revenue</h6>
                            <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-shopping-cart" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white text-opacity-75 mb-1">Total Orders</h6>
                            <h3 class="mb-0">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Order Value -->
        <div class="col-md-4 col-sm-12">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-chart-line" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-white text-opacity-75 mb-1">Avg Order Value</h6>
                            <h3 class="mb-0">Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Tren Revenue 12 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Top 5 Customers</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($topCustomers as $customer)
                                <tr>
                                    <td class="ps-3">
                                        <h6 class="mb-0">{{ $customer->name }}</h6>
                                        <small class="text-muted">{{ $customer->email }}</small>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="fw-bold">Rp {{ number_format($customer->orders_sum_total_harga ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">Belum ada data customer.</td>
                                </tr>
                                @endforelse
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
                series: [{
                    name: 'Revenue',
                    data: @json($monthlyRevenue->pluck('revenue'))
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: { enabled: false },
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#6366f1'],
                xaxis: {
                    categories: @json($monthlyRevenue->pluck('month')),
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
