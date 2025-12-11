<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;

class AdminController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function manageProduk()
    {
        return view('admin.manajemen-produk');
    }
    public function dataPesanan()
    {
        return view('admin.data-pesanan');
    }
    public function detailPesanan($id)
    {
        return view('admin.detail-pesanan-view', compact('id'));
    }
    public function penjadwalan()
    {
        return view('admin.penjadwalan-prioritas-view');
    }

    public function returns()
    {
        return view('admin.list-returns');
    }
    /**
     * Halaman daftar order dalam produksi
     */
    public function production(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->where('status', 'in_production');
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('penerima_nama', 'like', "%{$search}%");
            });
        }
        $orders = $query->latest('updated_at')->paginate(20);
        return view('admin.list-production', compact('orders'));
    }
    /**
     * Tandai produksi selesai dan siap kirim
     */
    public function completeProduction(Order $order)
    {
        // Validasi: order harus status in_production
        if ($order->status !== 'in_production') {
            return back()->with('error', 'Order tidak dalam status produksi');
        }
        // Validasi: semua items harus production_status = completed
        $incompleteItems = $order->items()->where('production_status', '!=', 'completed')->count();
        if ($incompleteItems > 0) {
            return back()->with('error', "Masih ada {$incompleteItems} item yang belum selesai produksi");
        }
        try {
            DB::beginTransaction();
            // Update order status ke ready_to_ship
            $order->update([
                'status' => 'ready_to_ship',
            ]);
            DB::commit();
            Log::info('Production Completed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
            return redirect()->route('admin.shipping.index')
                ->with('success', 'Produksi selesai! Order siap untuk dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete Production Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Mulai produksi item
     */
    public function startItemProduction(OrderItem $item)
    {
        if (!in_array($item->production_status, ['waiting', 'in_queue'])) {
            return back()->with('error', 'Item tidak dalam status yang valid untuk dimulai');
        }
        try {
            $item->update([
                'production_status' => 'in_progress',
                'production_started_at' => now(),
            ]);
            Log::info('Item Production Started', [
                'item_id' => $item->id,
                'order_number' => $item->order->order_number,
            ]);
            return back()->with('success', 'Item berhasil dimulai produksinya');
        } catch (\Exception $e) {
            Log::error('Start Item Production Error', [
                'item_id' => $item->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Selesaikan produksi item
     */
    public function completeItemProduction(OrderItem $item)
    {
        if ($item->production_status !== 'in_progress') {
            return back()->with('error', 'Item tidak dalam status produksi');
        }
        try {
            $item->update([
                'production_status' => 'completed',
            ]);
            Log::info('Item Production Completed', [
                'item_id' => $item->id,
                'order_number' => $item->order->order_number,
            ]);
            $order = $item->order;
            $allCompleted = $order->items()->where('production_status', '!=', 'completed')->count() === 0;
            if ($allCompleted) {
                return back()->with('success', 'Item selesai! Semua item dalam order ini sudah selesai diproduksi.');
            }
            return back()->with('success', 'Item berhasil diselesaikan');
        } catch (\Exception $e) {
            Log::error('Complete Item Production Error', [
                'item_id' => $item->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
