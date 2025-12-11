@section('title', 'Perbandingan FCFS vs DPS')

<x-app-layout>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                        <li class="breadcrumb-item active">FCFS vs DPS</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Perbandingan FCFS vs DPS</h2>
                        <span class="badge bg-success ms-2">Skripsi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('admin.reports.comparison-report')
</x-app-layout>
