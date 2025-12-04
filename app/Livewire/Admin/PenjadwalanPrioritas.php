<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderItem;
use App\Models\Order;
use App\Services\PriorityCalculator;
use App\Services\ComplexityCalculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenjadwalanPrioritas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // Filters
    public $filterStatus = 'all';
    public $filterJenisSablon = '';
    public $searchOrder = '';
    public $sortBy = 'priority_score';
    public $sortDirection = 'desc';
    // Modal state
    public $showDetailModal = false;
    public $showComplexityModal = false;
    public $selectedItem = null;
    public $manualComplexityScore = null;
    public $complexityNotes = '';
    // Statistics
    public $stats = [];
    protected $listeners = ['refreshComponent' => '$refresh'];
    protected $layout = 'layouts.app';
    public function mount()
    {
        $this->loadStatistics();
    }
    public function loadStatistics()
    {
        $this->stats = [
            'waiting' => OrderItem::whereHas('order', function ($q) {
                $q->where('status', 'verified');
            })->where('production_status', 'waiting')->count(),
            'in_queue' => OrderItem::whereHas('order', function ($q) {
                $q->where('status', 'verified');
            })->where('production_status', 'in_queue')->count(),
            'in_progress' => OrderItem::whereHas('order', function ($q) {
                $q->where('status', 'in_production');
            })->where('production_status', 'in_progress')->count(),
            'avg_waiting_hours' => OrderItem::whereHas('order', function ($q) {
                $q->where('status', 'verified');
            })->whereIn('production_status', ['waiting', 'in_queue'])
                ->avg('waiting_time_hours') ?? 0,
        ];
    }
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }
    public function updatedFilterJenisSablon()
    {
        $this->resetPage();
    }
    public function updatedSearchOrder()
    {
        $this->resetPage();
    }
    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
    }
    public function showDetail($itemId)
    {
        $this->selectedItem = OrderItem::with(['order.user', 'produk.jenisSablon', 'produk.ukuran'])
            ->findOrFail($itemId);
        $this->showDetailModal = true;
    }
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedItem = null;
    }
    public function openComplexityReview($itemId)
    {
        $this->selectedItem = OrderItem::with(['order', 'produk'])
            ->findOrFail($itemId);
        $this->manualComplexityScore = $this->selectedItem->manual_complexity_score ?? $this->selectedItem->auto_complexity_score;
        $this->complexityNotes = '';
        $this->showComplexityModal = true;
    }
    public function saveComplexityReview()
    {
        Log::info('saveComplexityReview called', [
            'item_id' => $this->selectedItem->id,
            'manual_score' => $this->manualComplexityScore,
            'notes' => $this->complexityNotes
        ]);
        $this->validate([
            'manualComplexityScore' => 'required|numeric|min:0|max:10',
            'complexityNotes' => 'nullable|string|max:500',
        ], [
            'manualComplexityScore.required' => 'Score kompleksitas harus diisi',
            'manualComplexityScore.min' => 'Score minimal 0',
            'manualComplexityScore.max' => 'Score maksimal 10',
        ]);
        try {
            DB::beginTransaction();
            ComplexityCalculator::calculateAndSave(
                $this->selectedItem,
                floatval($this->manualComplexityScore),
                $this->complexityNotes
            );
            PriorityCalculator::calculateAndSave($this->selectedItem, 'manual_recalc');
            DB::commit();
            $this->showComplexityModal = false;
            $this->selectedItem = null;
            $this->loadStatistics();
            // session()->flash('success', 'Penilaian kompleksitas berhasil disimpan dan prioritas telah diperbarui');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Penilaian kompleksitas berhasil disimpan dan prioritas telah diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving complexity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ]);
        }
    }
    public function closeComplexityModal()
    {
        $this->showComplexityModal = false;
        $this->selectedItem = null;
        $this->manualComplexityScore = null;
        $this->complexityNotes = '';
    }
    public function recalculatePriority($itemId)
    {
        try {
            $orderItem = OrderItem::findOrFail($itemId);
            PriorityCalculator::calculateAndSave($orderItem, 'manual_recalc');
            $this->loadStatistics();
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Prioritas berhasil dihitung ulang'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal menghitung ulang prioritas: ' . $e->getMessage()
            ]);
        }
    }
    public function recalculateAll()
    {
        try {
            $count = PriorityCalculator::recalculateAll('manual_recalc');
            $this->loadStatistics();
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Prioritas untuk {$count} item berhasil dihitung ulang"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal menghitung ulang semua prioritas: ' . $e->getMessage()
            ]);
        }
    }
    public function mulaiProduksi($itemId)
    {
        try {
            DB::beginTransaction();
            $orderItem = OrderItem::findOrFail($itemId);
            $order = $orderItem->order;
            // Update order item
            $orderItem->production_status = 'in_progress';
            $orderItem->production_started_at = now();
            $orderItem->save();
            // Update order status if not yet in_production
            if ($order->status !== 'in_production') {
                $order->status = 'in_production';
                $order->save();
            }
            DB::commit();
            $this->loadStatistics();
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Produksi untuk item pesanan telah dimulai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal memulai produksi: ' . $e->getMessage()
            ]);
        }
    }
    public function render()
    {
        $query = OrderItem::with(['order.user', 'produk.jenisSablon', 'produk.ukuran'])
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['verified', 'in_production']);
            });
        if ($this->filterStatus !== 'all') {
            $query->where('production_status', $this->filterStatus);
        }
        if ($this->filterJenisSablon) {
            $query->whereHas(
                'produk.jenisSablon',
                fn($q) =>
                $q->where('id', $this->filterJenisSablon)
            );
        }
        if ($this->searchOrder) {
            $query->whereHas(
                'order',
                fn($q) =>
                $q->where('order_number', 'like', '%' . $this->searchOrder . '%')
            );
        }
        $query->orderBy($this->sortBy, $this->sortDirection);
        return view('livewire.admin.penjadwalan-prioritas', [
            'orderItems' => $query->paginate(20),
            'jenisSablonList' => \App\Models\JenisSablon::all(),
        ]);
    }
}
