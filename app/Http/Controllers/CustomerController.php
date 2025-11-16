<?php

namespace App\Http\Controllers;

use App\Models\JenisSablon;
use App\Models\Ukuran;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.produk.jenisSablon'])
            ->latest()
            ->take(5)
            ->get();

        $orderStats = [
            'pending_payment' => Order::where('user_id', $user->id)->where('status', 'pending_payment')->count(),
            'in_production' => Order::where('user_id', $user->id)->where('status', 'in_production')->count(),
            'shipped' => Order::where('user_id', $user->id)->where('status', 'shipped')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('customer.c-dashboard', compact('recentOrders', 'orderStats'));
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.produk.jenisSablon'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function createOrder(Request $request)
    {
        $jenisSablons = JenisSablon::where('is_active', true)
            ->with(['produks' => function ($query) {
                $query->where('is_active', true)
                    ->with('ukuran')
                    ->orderBy('ukuran_id')
                    ->orderBy('tipe_layanan');
            }])
            ->get();

        $ukurans = Ukuran::where('is_active', true)->get();

        // Pre-select jenis sablon jika ada di query string
        $selectedJenis = $request->get('jenis');

        return view('customer.orders.create', compact('jenisSablons', 'ukurans', 'selectedJenis'));
    }

    public function showOrder(Order $order)
    {
        // Pastikan user hanya bisa melihat order mereka sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.produk.jenisSablon', 'paymentHistories', 'shippingTracking']);

        return view('customer.orders.show', compact('order'));
    }
}
