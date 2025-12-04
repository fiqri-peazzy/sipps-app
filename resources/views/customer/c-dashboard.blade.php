{{-- FILE: resources/views/customer/dashboard.blade.php --}}
@extends('layouts.customer')

@section('customer-content')
    <div class="content-header">
        <h1 class="content-title">
            <i class="lni lni-dashboard"></i>
            Dashboard
        </h1>
        <p class="content-subtitle">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>

    <div class="row">
        {{-- Stats Cards --}}
        <div class="col-md-3 mb-4">
            <div class="content-card text-center">
                <div
                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="lni lni-cart" style="font-size: 2rem; color: #fff;"></i>
                </div>
                <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $totalOrders ?? 0 }}</h3>
                <p style="color: #64748b; margin: 0;">Total Pesanan</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="content-card text-center">
                <div
                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="lni lni-timer" style="font-size: 2rem; color: #fff;"></i>
                </div>
                <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $pendingOrders ?? 0 }}</h3>
                <p style="color: #64748b; margin: 0;">Belum Bayar</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="content-card text-center">
                <div
                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="lni lni-package" style="font-size: 2rem; color: #fff;"></i>
                </div>
                <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $processingOrders ?? 0 }}</h3>
                <p style="color: #64748b; margin: 0;">Diproses</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="content-card text-center">
                <div
                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="lni lni-checkmark-circle" style="font-size: 2rem; color: #fff;"></i>
                </div>
                <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $completedOrders ?? 0 }}</h3>
                <p style="color: #64748b; margin: 0;">Selesai</p>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="content-card">
        <div class="card-header-custom">
            <h5>
                <i class="lni lni-package"></i>
                Pesanan Terbaru
            </h5>
        </div>

        @if (isset($recentOrders) && $recentOrders->count() > 0)
            @foreach ($recentOrders as $order)
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
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: #1e293b;">{{ $order->formatted_total_harga }}</strong>
                        </div>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-primary-custom">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-3">
                <a href="{{ route('customer.orders.index') }}" class="btn-primary-custom">
                    Lihat Semua Pesanan
                </a>
            </div>
        @else
            <div class="empty-state">
                <i class="lni lni-package"></i>
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum memiliki pesanan. Mulai pesan sekarang!</p>
                <a href="{{ route('customer.order.create') }}" class="btn-primary-custom">
                    <i class="lni lni-plus"></i> Buat Pesanan Pertama
                </a>
            </div>
        @endif
    </div>
@endsection
