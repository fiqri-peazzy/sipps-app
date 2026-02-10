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
use App\Services\WhatsAppService;

class CustomerReturnForm extends Component
{
    use WithFileUploads;

    public Order $order;
    public $selectedItemIds = [];
    public $reason = '';
    public $reasonDetail = '';
    public $evidencePhotos = [];
    public $uploadedPhotos = [];

    protected $rules = [
        'selectedItemIds' => 'required|array|min:1',
        'selectedItemIds.*' => 'exists:order_items,id',
        'reason' => 'required|in:wrong_size,wrong_color,print_quality,damage,not_as_described,other',
        'reasonDetail' => 'required|string|max:1000',
        'evidencePhotos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    protected $messages = [
        'selectedItemIds.required'   => 'Pilih minimal satu item yang ingin di-return',
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
        
        $orderItems = OrderItem::whereIn('id', $this->selectedItemIds)
            ->where('order_id', $this->order->id)
            ->get();

        if ($orderItems->count() !== count($this->selectedItemIds)) {
            $this->dispatch('show-alert', [
                'type'      => 'error',
                'message'   => 'Terdapat item yang tidak valid'
            ]);
            return;
        }

        foreach ($orderItems as $item) {
            if ($item->customerReturns()->exists()) {
                $this->dispatch('show-alert', [
                    'type'      => 'error',
                    'message'   => 'Item ' . $item->produk->jenisSablon->nama . ' sudah pernah diajukan'
                ]);
                return;
            }
        }

        DB::beginTransaction();
        try {
            $photoPaths = [];
            foreach ($this->evidencePhotos as $photo) {
                $path = $photo->store('customer-evidence', 'public');
                $photoPaths[] = $path;
            }

            foreach ($orderItems as $orderItem) {
                CustomerReturn::create([
                    'order_id'          => $this->order->id,
                    'order_item_id'     => $orderItem->id,
                    'user_id'           => Auth::id(),
                    'reason'            => $this->reason,
                    'reason_detail'     => $this->reasonDetail,
                    'evidence_photos'   => $photoPaths,
                    'status'            => 'pending'
                ]);
            }

            $this->order->update([
                'status' => 'return_requested',
            ]);

            DB::commit();

            // Kirim Notifikasi WA
            try {
                $whatsapp = app(WhatsAppService::class);
                $itemsLabel = $orderItems->map(fn($item) => $item->produk->jenisSablon->nama)->implode(', ');
                $whatsapp->sendReturnRequestNotification(
                    $this->order->penerima_telepon,
                    $this->order->order_number,
                    $itemsLabel
                );
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA return request: ' . $e->getMessage());
            }

            $this->dispatch('show-alert', [
                'type'      => 'success',
                'message'   => 'Permintaan return berhasil diajukan untuk ' . $orderItems->count() . ' item. Mohon tunggu review dari admin.'
            ]);

            return redirect()->route('customer.orders.show', $this->order->id);
        } catch (\Exception $e) {
            DB::rollBack();

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
