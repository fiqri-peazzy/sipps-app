@extends('layouts.frontend')

@section('title', 'Buat Pesanan Baru')

@section('content')
    <style>
        .order-form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px 0;
        }

        .section-header {
            border-bottom: 3px solid #6366F1;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-header h4 {
            color: #1e293b;
            font-weight: 700;
            margin: 0;
        }

        .order-item-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
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
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
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
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            position: sticky;
            top: 100px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .price-row.total {
            border-bottom: none;
            font-size: 24px;
            font-weight: 700;
            margin-top: 10px;
        }

        .btn-submit-order {
            background: white;
            color: #6366F1;
            border: none;
            padding: 15px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 18px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .btn-submit-order:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .file-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-area:hover {
            border-color: #6366F1;
            background: #f8fafc;
        }

        .file-upload-area i {
            font-size: 48px;
            color: #94a3b8;
            margin-bottom: 10px;
        }
    </style>

    <div class="container">
        <div class="order-form-container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 style="color: #1e293b; font-weight: 800;">
                        <i class="lni lni-cart"></i> Buat Pesanan Baru
                    </h2>
                    <p style="color: #64748b;">Lengkapi form di bawah untuk membuat pesanan sablon Anda</p>
                </div>
            </div>

            @livewire('customer.place-order-form', [
                'jenisSablons' => $jenisSablons,
                'ukurans' => $ukurans,
                'selectedJenis' => $selectedJenis,
            ])
        </div>
    </div>
@endsection
