@extends('layouts.customer')

@push('styles')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        .timeline-step-active {
            @apply bg-primary text-white scale-110 shadow-lg shadow-primary/30;
        }

        .timeline-step-inactive {
            @apply bg-slate-100 text-slate-400;
        }

        .timeline-line-active {
            @apply bg-primary;
        }

        .timeline-line-inactive {
            @apply bg-slate-100;
        }
    </style>
@endpush

@section('customer-content')
    <div class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Detail <span
                class="text-primary italic">Pesanan</span></h1>
        <p class="text-slate-500 mt-2 text-lg">Informasi lengkap mengenai status dan progres produksi pesanan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Content -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Order Status Banner -->
            <div class="card-modern p-0! overflow-hidden">
                <div class="bg-slate-900 p-8 text-white flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-primary flex items-center justify-center text-2xl">
                            <i class="lni lni-cart"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-black">{{ $order->order_number }}</h4>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Dipesan pada
                                {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    <div
                        class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border-2 border-white/10
                        @if ($order->status == 'completed') bg-green-500/10 text-green-400
                        @elseif($order->status == 'pending_payment') bg-amber-500/10 text-amber-400
                        @else bg-primary/10 text-primary @endif">
                        {{ $order->status_label }}
                    </div>
                </div>

                <!-- Step Timeline -->
                <div class="p-10 bg-white">
                    <div class="relative flex justify-between items-start max-w-4xl mx-auto">
                        <!-- Connecting Lines -->
                        <div class="absolute top-6 left-0 w-full h-1 bg-slate-50 flex">
                            <div class="h-full bg-primary transition-all duration-1000"
                                style="width: 
                                @if ($order->status == 'completed') 100% 
                                 @elseif($order->status == 'shipped') 80%
                                 @elseif($order->status == 'ready_to_ship') 80%
                                 @elseif($order->status == 'in_production') 60%
                                 @elseif($order->status == 'verified') 40%
                                 @elseif($order->status == 'paid') 20%
                                 @else 0% @endif">
                            </div>
                        </div>

                        <!-- Steps -->
                        @php
                            $steps = [
                                ['id' => 'paid', 'icon' => 'lni-credit-cards', 'label' => 'Bayar'],
                                ['id' => 'verified', 'icon' => 'lni-checkmark', 'label' => 'Verifikasi'],
                                ['id' => 'in_production', 'icon' => 'lni-cogs', 'label' => 'Produksi'],
                                ['id' => 'shipped', 'icon' => 'lni-delivery', 'label' => 'Kirim'],
                                ['id' => 'completed', 'icon' => 'lni-star', 'label' => 'Selesai'],
                            ];

                            $currentStatus = $order->status;
                            $reachedSteps = match ($currentStatus) {
                                'pending_payment' => [],
                                'paid' => ['paid'],
                                'verified' => ['paid', 'verified'],
                                'in_production' => ['paid', 'verified', 'in_production'],
                                'ready_to_ship', 'shipped' => ['paid', 'verified', 'in_production', 'shipped'],
                                'completed' => ['paid', 'verified', 'in_production', 'shipped', 'completed'],
                                default => [],
                            };
                        @endphp

                        @foreach ($steps as $step)
                            <div class="relative z-10 flex flex-col items-center gap-3">
                                <div
                                    class="h-12 w-12 rounded-2xl flex items-center justify-center transition-all duration-500
                                    {{ in_array($step['id'], $reachedSteps) ? 'bg-primary text-white shadow-lg shadow-primary/30 rotate-3' : 'bg-slate-50 text-slate-300' }}">
                                    <i class="lni {{ $step['icon'] }} text-lg"></i>
                                </div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest {{ in_array($step['id'], $reachedSteps) ? 'text-primary' : 'text-slate-400' }}">
                                    {{ $step['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Return Info Section -->
            @php
                $returns = $order->items->flatMap->customerReturns;
            @endphp

            @if ($returns->isNotEmpty())
                <div class="card-modern bg-amber-50/50! border-amber-100">
                    <div class="inline-flex items-center gap-3 mb-6">
                        <div
                            class="h-10 w-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-200">
                            <i class="lni lni-reload text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Informasi Pengembalian (Return)</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach ($returns as $ret)
                            <div
                                class="p-5 rounded-2xl bg-white border border-amber-100 shadow-sm transition-all hover:shadow-md">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <h5 class="font-black text-slate-900">
                                            {{ $ret->orderItem->produk->jenisSablon->nama }}</h5>
                                        <p class="text-sm text-slate-500 mt-1">Alasan: <span
                                                class="font-bold text-slate-700">{{ $ret->reason_label }}</span></p>
                                        <p class="text-xs text-slate-400 mt-1 italic">"{{ $ret->reason_detail }}"</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-{{ $ret->status_color }}-100 text-{{ $ret->status_color }}-700 border border-{{ $ret->status_color }}-200">
                                            {{ $ret->status_label }}
                                        </span>
                                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">
                                            {{ $ret->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                @if ($ret->resolution_type)
                                    <div class="mt-3 flex flex-wrap items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resolusi:</span>
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest {{ $ret->resolution_type === 'replacement' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-green-50 text-green-600 border border-green-100' }}">
                                                {{ $ret->resolution_type === 'replacement' ? 'Produksi Ulang' : 'Pengembalian Dana' }}
                                            </span>
                                        </div>
                                        @if ($ret->resolution_type === 'refund')
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total
                                                    Refund:</span>
                                                <span class="text-xs font-black text-green-600">Rp
                                                    {{ number_format($ret->refund_amount, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if ($ret->admin_notes)
                                    <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Catatan Admin:</p>
                                        <p class="text-sm text-slate-600 font-medium">{{ $ret->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Items Section -->
            <div class="card-modern">
                <div class="inline-flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <i class="lni lni-package text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Daftar Item Sablon</h2>
                </div>

                <div class="divide-y divide-slate-50">
                    @foreach ($order->items as $item)
                        <div class="py-6 flex flex-col md:flex-row md:items-center gap-6 group">
                            <div
                                class="h-20 w-20 rounded-3xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100 group-hover:rotate-3 transition-transform">
                                <i
                                    class="lni lni-brush text-3xl text-slate-300 group-hover:text-primary transition-colors"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-black text-slate-900 text-lg">{{ $item->produk->jenisSablon->nama }}</h5>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><i
                                            class="lni lni-ruler mr-1"></i> {{ $item->produk->ukuran->nama }}
                                        ({{ $item->ukuran_kaos }})
                                    </span>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><i
                                            class="lni lni-cog mr-1"></i> {{ $item->produk->tipe_layanan_label }}</span>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><i
                                            class="lni lni-layers mr-1"></i> Qty: {{ $item->quantity }}</span>

                                    @if ($item->is_return_item)
                                        <span
                                            class="px-2 py-0.5 rounded-md bg-info/10 text-info text-[10px] font-black uppercase tracking-widest border border-info/20">
                                            Item Pengganti (Return)
                                        </span>
                                    @elseif($item->customerReturns->where('status', 'approved')->isNotEmpty())
                                        <span
                                            class="px-2 py-0.5 rounded-md bg-danger/10 text-danger text-[10px] font-black uppercase tracking-widest border border-danger/20">
                                            Telah Direturn
                                        </span>
                                    @elseif($item->customerReturns->where('status', 'pending')->isNotEmpty())
                                        <span
                                            class="px-2 py-0.5 rounded-md bg-warning/10 text-warning text-[10px] font-black uppercase tracking-widest border border-warning/20">
                                            Ajuan Return Pending
                                        </span>
                                    @endif
                                </div>
                                @if ($item->catatan_item)
                                    <p
                                        class="mt-3 text-xs p-3 rounded-xl bg-slate-50 text-slate-500 border border-slate-100 font-medium">
                                        <i class="lni lni-write mr-2"></i> {{ $item->catatan_item }}
                                    </p>
                                @endif

                                @if ($item->file_desain)
                                    <div class="mt-4">
                                        <a href="{{ Storage::url($item->file_desain) }}" target="_blank"
                                            class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary hover:underline">
                                            <i class="lni lni-download"></i> Unduh File Desain
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-xl font-black {{ $item->customerReturns->where('status', 'approved')->isNotEmpty() ? 'text-slate-300 line-through' : 'text-primary' }}">
                                    {{ $item->formatted_subtotal }}
                                </p>
                                @if ($item->is_return_item)
                                    <p class="text-[10px] font-black text-info uppercase tracking-widest">Garansi (Rp 0)
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card-modern">
                <div class="inline-flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class="lni lni-map-marker text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Detail Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 relative group">
                            <div
                                class="absolute -top-3 left-6 px-3 py-1 rounded-full bg-white border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-primary transition-colors">
                                Penerima</div>
                            <h6 class="font-black text-slate-900">{{ $order->penerima_nama }}</h6>
                            <p class="text-sm font-bold text-slate-500 mt-1">{{ $order->penerima_telepon }}</p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 relative group">
                            <div
                                class="absolute -top-3 left-6 px-3 py-1 rounded-full bg-white border border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-primary transition-colors">
                                Alamat Lengkap</div>
                            <p class="text-sm font-medium text-slate-700 leading-relaxed">
                                {{ $order->alamat_lengkap }}<br>
                                {{ $order->kelurahan }}, {{ $order->kecamatan }}<br>
                                {{ $order->kota }}, {{ $order->provinsi }} {{ $order->kode_pos }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-slate-900 rounded-4xl p-8 text-white relative overflow-hidden">
                            <i class="lni lni-delivery text-6xl absolute -bottom-4 -right-4 opacity-10"></i>
                            <h6 class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 mb-4">Informasi Kurir
                            </h6>
                            @if ($order->kurir)
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-xl bg-white/10 flex items-center justify-center text-primary">
                                            <i class="lni lni-truck"></i>
                                        </div>
                                        <span class="font-black">{{ $order->kurir }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-xl bg-white/10 flex items-center justify-center text-primary">
                                            <i class="lni lni-tag"></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-slate-400 capitalize">{{ $order->service_kurir }}</span>
                                    </div>
                                    @if ($order->resi)
                                        <div class="mt-6 pt-6 border-t border-white/10">
                                            <p
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">
                                                Nomor Resi</p>
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-lg font-black tracking-widest">{{ $order->resi }}</span>
                                                <button onclick="navigator.clipboard.writeText('{{ $order->resi }}')"
                                                    class="h-10 w-10 flex items-center justify-center rounded-xl bg-primary text-white hover:scale-110 active:scale-95 transition-all">
                                                    <i class="lni lni-files"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex flex-col items-center py-4 text-center opacity-40">
                                    <i class="lni lni-timer text-3xl mb-2"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Menunggu Kurir Ditugaskan
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="lg:col-span-4 space-y-8">

            <!-- Summary Card -->
            <div class="card-modern bg-primary! text-white! p-10! shadow-xl shadow-primary/20 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 space-y-6">
                    <h5 class="text-lg font-black italic tracking-tight">Ringkasan Pembayaran</h5>

                    <div class="space-y-3 border-b border-white/20 pb-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-white/70">Subtotal</span>
                            <span class="font-black">{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-white/70">Ongkir</span>
                            <span class="font-black">{{ $order->formatted_ongkir }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">Total
                                Akhir</span>
                            <div class="text-3xl font-black">{{ $order->formatted_total_harga }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contextual Actions -->
            <div class="card-modern space-y-4">
                <h5 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 px-2">Aksi Pesanan</h5>

                @if ($order->status == 'pending_payment')
                    <button type="button" id="btn-pay-now"
                        class="w-full btn-premium flex items-center justify-center gap-3">
                        <i class="lni lni-credit-cards"></i> Bayar Sekarang
                    </button>

                    @if ($order->payment_expired_at)
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-1">Batas
                                Pembayaran</p>
                            <span
                                class="text-xs font-bold text-amber-900">{{ $order->payment_expired_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                @endif

                @if ($order->canRequestReturn())
                    <a href="{{ route('customer.orders.return', $order->id) }}"
                        class="w-full h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-black text-sm hover:bg-amber-200 transition-all gap-2">
                        <i class="lni lni-reload"></i> Ajukan Pengembalian
                    </a>
                @endif

                <a href="{{ route('customer.orders.index') }}"
                    class="w-full h-14 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm hover:bg-slate-200 transition-all gap-2">
                    <i class="lni lni-arrow-left"></i> Kembali ke List
                </a>
            </div>

            <!-- Production Timeline (Detailed) -->
            <div class="card-modern">
                <h5 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-8 px-2">Progres Produksi</h5>

                <div class="space-y-8 relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-slate-50"></div>

                    @php
                        $progress = [
                            ['label' => 'Pesanan Dibuat', 'time' => $order->created_at, 'active' => true],
                            [
                                'label' => 'Pembayaran Masuk',
                                'time' => $order->paid_at,
                                'active' => (bool) $order->paid_at,
                            ],
                            [
                                'label' => 'Verifikasi Admin',
                                'time' => $order->verified_at,
                                'active' => (bool) $order->verified_at,
                            ],
                            [
                                'label' =>
                                    $order->status === 'returned'
                                        ? 'Pesanan Dikembalikan'
                                        : ($order->items->where('is_return_item', true)->isNotEmpty()
                                            ? 'Produksi Ulang (Return)'
                                            : 'Dalam Produksi'),
                                'time' => null,
                                'active' => in_array($order->status, [
                                    'in_production',
                                    'ready_to_ship',
                                    'shipped',
                                    'completed',
                                    'returned',
                                ]),
                            ],
                            [
                                'label' => 'Selesai & Dikirim',
                                'time' => $order->shipped_at,
                                'active' => (bool) $order->shipped_at,
                            ],
                        ];
                    @endphp

                    @foreach ($progress as $p)
                        <div class="relative pl-14 flex flex-col">
                            <div
                                class="absolute left-4 top-1 h-4 w-4 rounded-full border-4 border-white z-10 
                                {{ $p['active'] ? 'bg-primary' : 'bg-slate-100' }}">
                            </div>
                            <span
                                class="text-xs font-black uppercase tracking-widest 
                                {{ $p['active'] ? 'text-slate-900' : 'text-slate-300' }}">{{ $p['label'] }}</span>
                            @if ($p['time'])
                                <span
                                    class="text-[10px] font-bold text-slate-400 mt-1 uppercase leading-none">{{ $p['time']->format('d M, H:i') }}</span>
                            @elseif($p['active'])
                                <span class="text-[10px] font-black text-blue-500 mt-1 uppercase animate-pulse">Sedang
                                    Berjalan</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var orderId = {{ $order->id }};

            $('#btn-pay-now').on('click', function(e) {
                e.preventDefault();
                var $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="lni lni-spinner-arrow animate-spin mr-2"></i> Menyiapkan...');

                $.ajax({
                    url: '/customer/payment/initiate/' + orderId,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            snap.pay(response.snap_token, {
                                onSuccess: function(result) {
                                    window.location.href =
                                        "{{ route('customer.payment.finish') }}?order_id=" +
                                        result.order_id;
                                },
                                onPending: function(result) {
                                    window.location.href =
                                        "{{ route('customer.payment.unfinish') }}?order_id=" +
                                        result.order_id;
                                },
                                onError: function(result) {
                                    console.error('Payment Error:', result);
                                    $btn.prop('disabled', false).html(
                                        '<i class="lni lni-credit-cards"></i> Bayar Sekarang'
                                    );
                                },
                                onClose: function() {
                                    $btn.prop('disabled', false).html(
                                        '<i class="lni lni-credit-cards"></i> Bayar Sekarang'
                                    );
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
