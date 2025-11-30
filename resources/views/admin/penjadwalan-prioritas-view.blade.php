<x-app-layout>
    <!-- Header & Statistics Cards -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Penjadwalan dan Prioritas</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Penjadwalan dan Prioritas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    @livewire('admin.penjadwalan-prioritas')
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('show-alert', (data) => {
                    const alertData = data[0];
                    toastr[alertData.type](alertData.message);
                });
            });
        </script>
    @endpush
</x-app-layout>
