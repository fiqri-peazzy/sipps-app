<x-app-layout>
    <x-slot name="header">
        <div class="page-header-title">
            <h2 class="mb-0">Data Pesanan</h2>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Data Pesanan</li>
        </ul>
    </x-slot>

    @livewire('admin.data-pesanan')

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
