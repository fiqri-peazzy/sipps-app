<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Helpers\DesignFileHelper;
use Illuminate\Support\Facades\DB;

class DetailPesanan extends Component
{
    public $orderId;
    public $order;
    public $selectedItem = null;
    public $selectedArea = null;
    public $showDesignModal = false;

    protected $listeners = ['refreshDetail' => '$refresh'];

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['user', 'items.produk'])
            ->findOrFail($this->orderId);
    }

    public function showDesignPreview($itemId, $area)
    {
        $this->selectedItem = $this->order->items->firstWhere('id', $itemId);
        $this->selectedArea = $area;
        $this->showDesignModal = true;

        // Ambil canvas data
        $canvasData = $this->selectedItem->design_config['canvas_data'][$area] ?? null;
        $warnaKaos = $this->selectedItem->warna_kaos ?? 'putih';

        // Dispatch event dengan data canvas JSON
        $this->dispatch('designModalOpened', [
            'area' => $area,
            'warna' => $warnaKaos,
            'canvasJson' => $canvasData, // Kirim JSON string
            'hasCanvas' => !empty($canvasData)
        ]);
    }

    public function closeModal()
    {
        $this->showDesignModal = false;
        $this->selectedItem = null;
        $this->selectedArea = null;
    }

    public function getDesignFiles($itemId)
    {
        $item = $this->order->items->firstWhere('id', $itemId);
        if (!$item || !$item->design_config) {
            return [];
        }

        return DesignFileHelper::extractFilesFromDesignConfig($item->design_config);
    }

    public function hasDesignInArea($itemId, $area)
    {
        $item = $this->order->items->firstWhere('id', $itemId);
        if (!$item || !$item->design_config) {
            return false;
        }

        $hasDesign = $item->design_config['has_design'] ?? [];
        return $hasDesign[$area] ?? false;
    }

    public function verifikasiPesanan()
    {
        try {
            DB::beginTransaction();

            $this->order->update([
                'status' => 'verified',
                'verified_at' => now()
            ]);

            DB::commit();

            $this->loadOrder();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Pesanan berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Gagal memverifikasi pesanan: ' . $e->getMessage()
            ]);
        }
    }

    public function tolakPesanan($reason = null)
    {
        try {
            DB::beginTransaction();

            $this->order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason' => $reason ?? 'Pesanan ditolak oleh admin'
            ]);

            DB::commit();

            $this->loadOrder();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Pesanan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Gagal menolak pesanan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.detail-pesanan');
    }
}
