{{-- FILE: resources/views/customer/orders/index.blade.php --}}
@extends('layouts.customer')

@section('customer-content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="content-title">
                    <i class="lni lni-package"></i>
                    Pesanan Saya
                </h1>
                <p class="content-subtitle">Daftar semua pesanan yang telah Anda buat</p>
            </div>
            <a href="{{ route('customer.order.create') }}" class="btn-primary-custom">
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

                <div style="margin: 1rem 0;">
                    @foreach ($order->items as $item)
                        <div
                            style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <strong style="color: #1e293b;">{{ $item->produk->jenisSablon->nama }}</strong><br>
                                <small style="color: #64748b;">
                                    {{ $item->produk->ukuran->nama }} - {{ $item->produk->tipe_layanan_label }} ×
                                    {{ $item->quantity }}
                                </small>
                            </div>
                            <div style="text-align: right;">
                                <strong style="color: #6366f1;">{{ $item->formatted_subtotal }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div
                    style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid #e5e7eb;">
                    <div>
                        <strong style="font-size: 1.2rem; color: #1e293b;">{{ $order->formatted_total_harga }}</strong>
                    </div>
                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-primary-custom">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="content-card">
            <div class="empty-state">
                <i class="lni lni-package"></i>
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum membuat pesanan apapun</p>
                <a href="{{ route('customer.order.create') }}" class="btn-primary-custom">
                    <i class="lni lni-plus"></i> Buat Pesanan Pertama
                </a>
            </div>
        </div>
    @endif
@endsection
