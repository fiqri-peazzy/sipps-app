@extends('layouts.customer')

@section('customer-content')
    <div class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Riwayat <span
                class="text-primary italic">Pesanan</span></h1>
        <p class="text-slate-500 mt-2 text-lg">Daftar riwayat dan status pesanan sablon Anda.</p>
    </div>

    <div class="card-modern p-0! overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between flex-wrap gap-4">
            <h4 class="text-xl font-black text-slate-900">Riwayat Pesanan</h4>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total: {{ $orders->total() }}
                    Pesanan</span>
            </div>
        </div>

        @if ($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">
                                No. Order</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">
                                Tanggal</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">
                                Produk</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">
                                Status</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right whitespace-nowrap">
                                Total</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-center whitespace-nowrap">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($orders as $order)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="font-black text-slate-900">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-700">{{ $order->created_at->format('d M Y') }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase">{{ $order->created_at->format('H:i') }}
                                            WIB</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="max-w-[200px] space-y-1">
                                        @foreach ($order->items->take(2) as $item)
                                            <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                                <span class="h-1 w-1 rounded-full bg-primary shrink-0"></span>
                                                <span class="truncate">{{ $item->produk->jenisSablon->nama }}</span>
                                                <span class="text-slate-400">({{ $item->quantity }})</span>
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 2)
                                            <span
                                                class="text-[10px] font-black text-primary uppercase">+{{ $order->items->count() - 2 }}
                                                lainnya</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                        @if ($order->status == 'completed') bg-green-100 text-green-700
                                        @elseif($order->status == 'pending_payment')
                                        @elseif($order->status == 'cancelled')
                                        @else @endif">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right text-sm">
                                    <span class="font-black text-primary">{{ $order->formatted_total_harga }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('customer.orders.show', $order->id) }}"
                                            class="h-9 w-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                            <i class="lni lni-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="p-8 border-t border-slate-50">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="py-24 flex flex-col items-center text-center px-8">
                <div
                    class="h-24 w-24 rounded-[2.5rem] bg-slate-50 flex items-center justify-center text-slate-200 mb-8 border border-white rotate-3">
                    <i class="lni lni-package text-5xl"></i>
                </div>
                <h4 class="text-2xl font-black text-slate-900 mb-2">Riwayat Kosong</h4>
                <p class="text-slate-500 max-w-sm mb-10">Sepertinya Anda belum memiliki pesanan. Mulai berkreasi dengan
                    desain kaos unik Anda!</p>
                <a href="{{ route('customer.order.create') }}" class="btn-premium">
                    <i class="lni lni-plus mr-2"></i> Buat Pesanan Baru
                </a>
            </div>
        @endif
    </div>
@endsection
