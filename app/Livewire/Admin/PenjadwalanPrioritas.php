<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderItem;
use App\Models\Order;
use App\Services\PriorityCalculator;
use App\Services\ComplexityCalculator;
use Illuminate\Support\Facades\DB;

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

            // Calculate and save complexity
            ComplexityCalculator::calculateAndSave(
                $this->selectedItem,
                $this->manualComplexityScore,
                $this->complexityNotes
            );

            // Recalculate priority based on new complexity
            PriorityCalculator::calculateAndSave($this->selectedItem, 'complexity_updated');

            DB::commit();

            $this->showComplexityModal = false;
            $this->selectedItem = null;
            $this->loadStatistics();

            session()->flash('success', 'Penilaian kompleksitas berhasil disimpan dan prioritas telah diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
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
            session()->flash('success', 'Prioritas berhasil dihitung ulang');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghitung prioritas: ' . $e->getMessage());
        }
    }

    public function recalculateAll()
    {
        try {
            $count = PriorityCalculator::recalculateAll('manual_recalc');
            $this->loadStatistics();
            session()->flash('success', "Berhasil menghitung ulang {$count} item");
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghitung prioritas: ' . $e->getMessage());
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
            session()->flash('success', 'Produksi berhasil dimulai');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memulai produksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = OrderItem::with(['order.user', 'produk.jenisSablon', 'produk.ukuran'])
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['verified', 'in_production']);
            });

        // Apply filters
        if ($this->filterStatus !== 'all') {
            $query->where('production_status', $this->filterStatus);
        }

        if ($this->filterJenisSablon) {
            $query->whereHas('produk.jenisSablon', function ($q) {
                $q->where('id', $this->filterJenisSablon);
            });
        }

        if ($this->searchOrder) {
            $query->whereHas('order', function ($q) {
                $q->where('order_number', 'like', '%' . $this->searchOrder . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $orderItems = $query->paginate(20);

        // Get jenis sablon for filter
        $jenisSablonList = \App\Models\JenisSablon::all();

        return view('livewire.admin.penjadwalan-prioritas', [
            'orderItems' => $orderItems,
            'jenisSablonList' => $jenisSablonList,
        ])->layout('layouts.app');
    }
}
