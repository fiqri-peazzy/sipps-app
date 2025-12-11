<?php

namespace App\Livewire\Admin;

use App\Models\CustomerReturn;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ManajemenCustomerReturn extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $statusFilter = '';
    public $reasonFilter = '';
    public $search       = '';

    public $showDetailModal = false;
    public $selectedReturn;

    public $showApprovalModal   = false;
    public $approvalAction;
    public $adminNotes          = '';

    public function render()
    {
        $query = CustomerReturn::with([
            'order',
            'orderItem.produk.jenisSablon',
            'orderItem.produk.ukuran',
            'user',
            'reviewedBy'
        ]);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->reasonFilter) {
            $query->where('reason', $this->reasonFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('order', function ($subQ) {
                    $subQ->where('order_number', 'like', "%$this->seacrh%")
                        ->orWhere('penerima_nama', 'like', "%$this->search%");
                })
                    ->orWhereHas('user', function ($subQ) {
                        $subQ->where('name', 'like', "%$this->search%");
                    });
            });
        }

        $returns = $query->latest()->paginate(20);

        // Initiate Stats
        $stats = [
            'pending'   => CustomerReturn::where('status', 'pending')->count(),
            'approved'   => CustomerReturn::where('status', 'approved')->count(),
            'rejected'   => CustomerReturn::where('status', 'rejected')->count(),
            'completed'   => CustomerReturn::where('status', 'completed')->count(),
        ];

        return view('livewire.admin.manajemen-customer-return', compact('returns', 'stats'));
    }

    public function showDetail($returnId)
    {
        $this->selectedReturn = CustomerReturn::with([
            'order.items.produk',
            'orderItem.produk.jenisSablon',
            'orderItem.produk.ukuran',
            'user',
            'reviewedBy',
            'replacementItem.order'
        ])->findOrFail($returnId);

        $this->showDetailModal = true;
    }

    public function openApprovalModal($returnId, $action)
    {
        $this->selectedReturn      = CustomerReturn::findOrFail($returnId);
        $this->approvalAction      = $action;
        $this->adminNotes          = '';
        $this->showApprovalModal   = true;
    }

    public function processApproval()
    {
        $this->validate([
            'adminNotes' => 'nullable|string|max:500',
        ]);

        if (!$this->selectedReturn) {
            $this->dispatch('alert', [
                'type'      => 'error',
                'message'   => 'Return request tidak ditemukan'
            ]);
            return;
        }

        // Cek apakah masih pending
        if ($this->selectedReturn->status !== 'pending') {
            $this->dispatch('alert', [
                'type'      => 'error',
                'message'   => 'Return request sudah diproses sebelumnya'
            ]);
            $this->showApprovalModal = false;
            return;
        }

        DB::beginTransaction();
        try {
            $return = CustomerReturn::findOrFail($this->selectedReturn->id);

            if ($this->approvalAction === 'approve') {
                $this->approveReturn($return);
                $message = 'Return request disetujui. Item pengganti berhasil dibuat dan masuk ke queue produksi.';
            } else {
                $this->rejectReturn($return);
                $message = 'Return request ditolak.';
            }

            DB::commit();

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => $message
            ]);

            $this->showApprovalModal = false;
            $this->showDetailModal = false;
            $this->reset(['selectedReturn', 'approvalAction', 'adminNotes']);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    private function approveReturn($return)
    {
        $originalItem = $return->orderItem;

        // Create replacement item (duplicate dari item original)
        $replacementItem = OrderItem::create([
            'order_id' => $originalItem->order_id,
            'produk_id' => $originalItem->produk_id,
            'quantity' => $originalItem->quantity,
            'ukuran_kaos' => $originalItem->ukuran_kaos,
            'warna_kaos' => $originalItem->warna_kaos,
            'harga_satuan' => $originalItem->harga_satuan,
            'subtotal' => $originalItem->subtotal,
            'design_config' => $originalItem->design_config,
            'catatan_item' => $originalItem->catatan_item,

            // Return item settings
            'is_return_item' => true,
            'parent_item_id' => $originalItem->id,
            'return_reason' => $return->reason_detail,

            // Production settings
            'deadline' => now()->addDays(7), // Deadline 7 hari
            'production_status' => 'in_queue',
            'priority_score' => 0, // Akan di-calculate ulang oleh DPS

            // Complexity (copy dari parent)
            'complexity_score' => $originalItem->complexity_score,
            'auto_complexity_score' => $originalItem->auto_complexity_score,
            'manual_complexity_score' => $originalItem->manual_complexity_score,
        ]);

        // Update return request
        $return->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $this->adminNotes,
            'replacement_order_item_id' => $replacementItem->id,
        ]);

        // Update original item
        $originalItem->update([
            'returned_count' => $originalItem->returned_count + 1,
        ]);

        // Update order status
        $return->order->update([
            'status' => 'returned',
        ]);
    }

    private function rejectReturn($return)
    {
        // Update return request
        $return->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $this->adminNotes,
        ]);

        // Update order status kembali ke completed
        $return->order->update([
            'status' => 'completed',
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['statusFilter', 'reasonFilter', 'search']);
    }
}
