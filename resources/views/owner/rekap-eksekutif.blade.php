<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Eksekutif (KPI)') }}
        </h2>
    </x-slot>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar bg-light-primary text-primary mx-auto mb-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="ti ti-cash" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Penjualan</h6>
                    <h4 class="mb-0">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar bg-light-success text-success mx-auto mb-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="ti ti-shopping-cart" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Order</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_orders']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar bg-light-info text-info mx-auto mb-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="ti ti-shirt" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Item Terjual</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_items_sold']) }} pcs</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar bg-light-warning text-warning mx-auto mb-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="ti ti-users" style="font-size: 1.5rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Customer</h6>
                    <h4 class="mb-0">{{ number_format($stats['total_customers']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Products Chart -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Top 5 Produk Terlaris (Qty)</h5>
                </div>
                <div class="card-body">
                    <div id="top-products-chart"></div>
                </div>
            </div>
        </div>

        <!-- Business Health Summary -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Business Health Check</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Average Order Value (AOV)</h6>
                                <small class="text-muted">Rata-rata belanja per order</small>
                            </div>
                            <span class="badge bg-light-primary text-primary fs-6">
                                Rp {{ $stats['total_orders'] > 0 ? number_format($stats['total_sales'] / $stats['total_orders'], 0, ',', '.') : 0 }}
                            </span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Items per Order</h6>
                                <small class="text-muted">Rata-rata item per pesanan</small>
                            </div>
                            <span class="badge bg-light-info text-info fs-6">
                                {{ $stats['total_orders'] > 0 ? number_format($stats['total_items_sold'] / $stats['total_orders'], 1) : 0 }} pcs
                            </span>
                        </li>
                    </ul>

                    <div class="mt-4 p-4 rounded-3 bg-light-secondary border border-secondary border-opacity-10 text-center">
                        <h6 class="mb-2">Rekomendasi Strategi:</h6>
                        @if($stats['total_orders'] > 0 && ($stats['total_sales'] / $stats['total_orders']) < 200000)
                            <p class="small text-muted mb-0">AOV Anda masih di bawah Rp 200rb. Pertimbangkan strategi **Bundling** atau **Upselling** desain eksklusif.</p>
                        @else
                            <p class="small text-muted mb-0">Pertahankan kualitas layanan untuk meningkatkan **Customer Retention Rate**.</p>
                        @endif
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
                    name: 'Quantity',
                    data: @json($topSellingProducts->pluck('total_qty'))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        distributed: true,
                    }
                },
                colors: ['#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f59e0b'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: @json($topSellingProducts->pluck('nama_sablon')),
                },
                legend: { show: false }
            };

            var chart = new ApexCharts(document.querySelector("#top-products-chart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
