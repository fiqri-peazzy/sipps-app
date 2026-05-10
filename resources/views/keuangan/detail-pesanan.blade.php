<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }} #{{ \App\Models\Order::find($id)->order_number }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-sm-12">
            @livewire('admin.detail-pesanan', ['orderId' => $id])
        </div>
    </div>

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
