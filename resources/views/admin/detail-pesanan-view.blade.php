<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="page-header-title">
                <h5 class="m-b-10">Detail Pesanan</h5>
            </div>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.data.pesanan') }}">Data Pesanan</a></li>
            <li class="breadcrumb-item" aria-current="page">Detail</li>
        </ul>
    </x-slot>

    @livewire('admin.detail-pesanan', ['orderId' => $id])

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            }
        </script>
    @endpush
</x-app-layout>
