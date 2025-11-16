@extends('layouts.frontend')

@section('title', 'Pesanan Saya')

@section('content')
    <style>
        .orders-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px 0;
        }

        .order-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .order-card:hover {
            border-color: #6366F1;
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.15);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 15px;
        }

        .order-number {
            font-weight: 700;
            color: #1e293b;
            font-size: 18px;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-pending_payment {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-verified {
            background: #ddd6fe;
            color: #5b21b6;
        }

        .status-in_production {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-shipped {
            background: #bfdbfe;
            color: #1e40af;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .order-items {
            margin-top: 15px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .item-detail {
            font-size: 13px;
            color: #64748b;
        }

        .item-price {
            text-align: right;
            color: #6366F1;
            font-weight: 700;
        }

        .order-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }

        .btn-view-order {
            background: linear-gradient(135deg, #6366F1 0%, #F97316 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .btn-view-order:hover {
            transform: translateY(-2px);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 100px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #64748b;
            margin-bottom: 15px;
        }
    </style>

    <div class="container">
        <div class="orders-container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 style="color: #1e293b; font-weight: 800;">
                        <i class="lni lni-package"></i> Pesanan Saya
                    </h2>
                    <p style="color: #64748b;">Daftar semua pesanan yang telah Anda buat</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('customer.order.create') }}" class="btn-view-order">
                        <i class="lni lni-plus"></i> Buat Pesanan Baru
                    </a>
                </div>
            </div>

            @if ($orders->count() > 0)
                @foreach ($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-number">{{ $order->order_number }}</div>
                                <small style="color: #64748b;">{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div class="order-items">
                            @foreach ($order->items as $item)
                                <div class="order-item">
                                    <div class="item-info">
                                        <div class="item-name">{{ $item->produk->jenisSablon->nama }}</div>
                                        <div class="item-detail">
                                            {{ $item->produk->ukuran->nama }} - {{ $item->produk->tipe_layanan_label }} ×
                                            {{ $item->quantity }}
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        {{ $item->formatted_subtotal }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-footer">
                            <div class="order-total">
                                Total: {{ $order->formatted_total_harga }}
                            </div>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-view-order">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="lni lni-package"></i>
                    <h4>Belum Ada Pesanan</h4>
                    <p style="color: #94a3b8; margin-bottom: 30px;">Anda belum membuat pesanan apapun</p>
                    <a href="{{ route('customer.order.create') }}" class="btn-view-order">
                        <i class="lni lni-plus"></i> Buat Pesanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
