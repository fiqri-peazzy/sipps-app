@section('title', 'Laporan Pesanan')

<x-app-layout>

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                        <li class="breadcrumb-item active">Laporan Pesanan</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Laporan Pesanan</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Livewire Component (semua logic di sini) --}}
    @livewire('admin.reports.orders-report')


</x-app-layout>
