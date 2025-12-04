{{-- FILE: resources/views/customer/customer-return-form.blade.php --}}
@extends('layouts.customer')

@push('styles')
    <style>
        .form-check:has(input:checked) {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
        }
    </style>
@endpush

@section('customer-content')
    <div class="content-header">
        <h1 class="content-title">
            <i class="lni lni-reload"></i>
            Ajukan Return Barang
        </h1>
        <p class="content-subtitle">Order: {{ $order->order_number }}</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="lni lni-checkmark-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="lni lni-cross-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card">
        @livewire('customer.customer-return-form', ['order' => $order])
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-alert', (data) => {
                const alertType = data[0].type || 'info';
                const alertMessage = data[0].message || 'Notification';
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${alertType} alert-dismissible fade show`;
                alertDiv.role = 'alert';
                alertDiv.innerHTML =
                    `<i class="lni lni-${alertType === 'success' ? 'checkmark-circle' : 'information'}"></i> ${alertMessage}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                document.querySelector('.customer-content').insertBefore(alertDiv, document.querySelector(
                    '.customer-content').firstChild);
                setTimeout(() => alertDiv.remove(), 5000);
            });
        });
    </script>
@endpush
