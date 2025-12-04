<?php

namespace App\Livewire\Customer;

use App\Models\CustomerReturn;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerReturnForm extends Component
{
    use WithFileUploads;

    public Order $order;
    public $selectedItemId;
    public $reason = '';
    public $reasonDetail = '';
    public $evidencePhotos = [];
    public $uploadedPhotos = [];

    protected $rules = [
        'selectedItemId' => 'required|exists:order_items,id',
        'reason' => 'required|in:wrong_size,wrong_color,print_quality,damage,not_as_described,other',
        'reasonDetail' => 'required|string|max:1000',
        'evidencePhotos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    protected $message = [
        'selectedItemId.required'   => 'Pilih Item yang ingin di return',
        'reason.required'           => 'Pilih Alasan Return',
        'reasonDetail.required'     => 'Jelaskan detail alasan return',
        'evidencePhotos.*.required' => 'Upload Minimal 1 bukti foto',
        'evidencePhotos.*.image'    => 'File Harus Berupa gambar',
        'evidencePhotos.*.max'      => 'Maksimal Ukuran File 2MB'
    ];

    public function mount(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canRequestReturn()) {
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Order ini tidak dapat dilakukan proses return'
            ]);

            return redirect()->route('customer.orders.show', $order->id);
        }

        $this->order = $order;
    }

    public function updatedEvidencePhotos()
    {
        $this->validate([
            'evidencePhotos.*'  => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);
    }

    public function removePhoto($index)
    {
        array_splice($this->evidencePhotos, $index, 1);
    }


    public function submit()
    {
        $this->validate();
        $orderItem = OrderItem::where('id', $this->selectedItemId)
            ->where('order_id', $this->order->id)
            ->first();
        if (!$orderItem) {
            $this->dispatch('show-alert', [
                'type'      => 'error',
                'message'   => 'Order Item tidak valid'
            ]);
            return;
        }

        if ($orderItem->customerReturns()->exists()) {
            $this->dispatch('show-alert', [
                'type'      => 'success',
                'message'   => 'Item Ini sudah pernah di ajukan'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            $photoPaths = [];
            foreach ($this->evidencePhotos as $photo) {
                $path = $photo->store('customer-evidence', 'public');
                $photoPaths[] = $path;
            }

            CustomerReturn::create([
                'order_id'          => $this->order->id,
                'order_item_id'     => $orderItem->id,
                'user_id'           => Auth::id(),
                'reason'            => $this->reason,
                'reason_detail'     => $this->reasonDetail,
                'evidence_photos'   => $photoPaths,
                'status'            => 'pending'
            ]);
            $this->order->update([
                'status' => 'return_requested',
            ]);

            DB::commit();

            Log::info('Customer Return Request Created', [
                'order_id' => $this->order->id,
                'order_item_id' => $orderItem->id,
                'user_id' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type'      => 'success',
                'message'   => 'Permintaan return berhasil diajukan. Mohon tunggu review dari admin.'
            ]);

            return redirect()->route('customer.orders.show', $this->order->id);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Customer Return Request Error', [
                'order_id'  => $this->order->id,
                'error'     => $e->getMessage(),
            ]);

            $this->dispatch('show-alert', [
                'type'      => 'error',
                'message'   => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.customer.customer-return-form');
    }
}