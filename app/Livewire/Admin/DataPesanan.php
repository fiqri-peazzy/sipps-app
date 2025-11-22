<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class DataPesanan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterStatus = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::with(['user', 'items'])
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.data-pesanan', compact('orders'));
    }
}
