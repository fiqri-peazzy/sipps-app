{{-- FILE: resources/views/customer/orders/create.blade.php --}}
@extends('layouts.customer')

@push('styles')
    <style>
        .order-item-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .remove-item-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .remove-item-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .btn-add-item {
            background: linear-gradient(135deg, #6366F1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s;
            width: 100%;
        }

        .btn-add-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.3);
        }

        .price-summary {
            background: linear-gradient(135deg, #6366F1 0%, #4f46e5 100%);
            border-radius: 15px;
            padding: 2rem;
            color: white;
            position: sticky;
            top: 140px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .price-row.total {
            border-bottom: none;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1rem;
        }

        .btn-submit-order {
            background: white;
            color: #6366F1;
            border: none;
            padding: 15px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s;
        }

        .btn-submit-order:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .file-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-area:hover {
            border-color: #6366F1;
            background: #f8fafc;
        }

        .file-upload-area i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('customer-content')
    <div class="content-header">
        <h1 class="content-title">
            <i class="lni lni-cart"></i>
            Buat Pesanan Baru
        </h1>
        <p class="content-subtitle">Lengkapi form di bawah untuk membuat pesanan sablon Anda</p>
    </div>

    @livewire('customer.place-order-form', [
        'jenisSablons' => $jenisSablons,
        'ukurans' => $ukurans,
        'selectedJenis' => $selectedJenis,
    ])
@endsection
