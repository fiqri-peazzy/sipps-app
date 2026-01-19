@extends('layouts.customer')

@section('customer-content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="content-title">
                    Pesanan Saya
                </h1>
                <p class="content-subtitle">Daftar semua pesanan yang telah Anda buat</p>
            </div>
            <a href="{{ route('customer.order.create') }}" class="btn-primary-custom">
                <i class="lni lni-plus"></i> Buat Pesanan Baru
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-0">
            @if ($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0" id="order-table">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">No. Order</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark">{{ $order->order_number }}</span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $order->created_at->format('d M Y') }}<br>
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @foreach ($order->items as $item)
                                                <div class="text-truncate" style="max-width: 250px;">
                                                    <i class="fa-solid fa-circle-dot me-1 text-primary"
                                                        style="font-size: 6px;"></i>
                                                    {{ $item->produk->jenisSablon->nama }}
                                                    <span class="text-muted">({{ $item->quantity }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill badge-soft-{{ $order->status_color ?? 'primary' }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-indigo">{{ $order->formatted_total_harga }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('customer.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-icon-only btn-primary" title="Lihat Detail">
                                            <i class="lni lni-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-box-open text-muted opacity-25" style="font-size: 64px;"></i>
                    </div>
                    <h4>Belum Ada Pesanan</h4>
                    <p class="text-muted mb-4">Anda belum membuat pesanan apapun.</p>
                    <a href="{{ route('customer.order.create') }}" class="btn btn-primary px-4">
                        <i class="fa-solid fa-plus me-2"></i>Buat Pesanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if ($orders->count() > 0)
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

    @push('styles')
        <style>
            #order-table thead th {
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding-top: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #f1f5f9;
            }

            #order-table tbody td {
                padding-top: 18px;
                padding-bottom: 18px;
                border-bottom: 1px solid #f8fafc;
            }

            #order-table tbody tr:last-child td {
                border-bottom: none;
            }

            .text-indigo {
                color: #6366f1;
            }

            .badge-soft-primary {
                background-color: #eef2ff;
                color: #6366f1;
            }

            .badge-soft-success {
                background-color: #f0fdf4;
                color: #22c55e;
            }

            .badge-soft-warning {
                background-color: #fffbeb;
                color: #f59e0b;
            }

            .badge-soft-danger {
                background-color: #fef2f2;
                color: #ef4444;
            }

            .badge-soft-info {
                background-color: #f0f9ff;
                color: #0ea5e9;
            }

            .badge-soft-secondary {
                background-color: #f8fafc;
                color: #64748b;
            }

            /*
                                                                    .btn-soft-primary {
                                                                        background-color: #eef2ff;
                                                                        color: #6366f1;
                                                                        border: none;
                                                                    }

                                                                    .btn-soft-primary:hover {
                                                                        background-color: #6366f1;
                                                                        color: white;
                                                                    }

                                                                    .btn-icon-only {
                                                                        width: 32px;
                                                                        height: 32px;
                                                                        padding: 0;
                                                                        display: inline-flex;
                                                                        align-items: center;
                                                                        justify-content: center;
                                                                        border-radius: 8px;
                                                                        transition: all 0.2s;
                                                                    } */
        </style>
    @endpush
@endsection
