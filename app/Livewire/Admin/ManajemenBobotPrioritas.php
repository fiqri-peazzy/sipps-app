<?php

namespace App\Livewire\Admin;

use App\Models\PriorityWeight;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManajemenBobotPrioritas extends Component
{
    public $weights;
    public $selectedId;
    public $name;
    public $weight_urgency = 0;
    public $weight_complexity = 0;
    public $weight_waiting_time = 0;
    public $weight_quantity = 0;
    public $description;
    public $is_active = false;

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'weight_urgency' => 'required|numeric|min:0|max:1',
        'weight_complexity' => 'required|numeric|min:0|max:1',
        'weight_waiting_time' => 'required|numeric|min:0|max:1',
        'weight_quantity' => 'required|numeric|min:0|max:1',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadWeights();
    }

    public function loadWeights()
    {
        $this->weights = PriorityWeight::orderBy('created_at', 'desc')->get();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->weight_urgency = 0;
        $this->weight_complexity = 0;
        $this->weight_waiting_time = 0;
        $this->weight_quantity = 0;
        $this->description = '';
        $this->is_active = false;
        $this->selectedId = null;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function openCreateModal()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetErrorBag();
        $weight = PriorityWeight::findOrFail($id);
        $this->selectedId = $id;
        $this->name = $weight->name;
        $this->weight_urgency = $weight->weight_urgency;
        $this->weight_complexity = $weight->weight_complexity;
        $this->weight_waiting_time = $weight->weight_waiting_time;
        $this->weight_quantity = $weight->weight_quantity;
        $this->description = $weight->description;
        $this->is_active = $weight->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Validate sum must be 1.0
        $sum = (float)$this->weight_urgency + (float)$this->weight_complexity + (float)$this->weight_waiting_time + (float)$this->weight_quantity;

        if (abs($sum - 1.0) > 0.001) {
            $this->addError('weight_sum', 'Total bobot harus tepat 1.00 (Sekarang: ' . number_format($sum, 2) . ')');
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->isEditing) {
                $weight = PriorityWeight::findOrFail($this->selectedId);
                $weight->update([
                    'name' => $this->name,
                    'weight_urgency' => $this->weight_urgency,
                    'weight_complexity' => $this->weight_complexity,
                    'weight_waiting_time' => $this->weight_waiting_time,
                    'weight_quantity' => $this->weight_quantity,
                    'description' => $this->description,
                ]);
            } else {
                PriorityWeight::create([
                    'name' => $this->name,
                    'weight_urgency' => $this->weight_urgency,
                    'weight_complexity' => $this->weight_complexity,
                    'weight_waiting_time' => $this->weight_waiting_time,
                    'weight_quantity' => $this->weight_quantity,
                    'description' => $this->description,
                    'is_active' => false, // Default inactive if new
                ]);
            }

            DB::commit();
            $this->showModal = false;
            $this->loadWeights();
            session()->flash('success', 'Konfigurasi bobot berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        DB::beginTransaction();
        try {
            // Deactivate all
            PriorityWeight::where('id', '>', 0)->update(['is_active' => false]);

            // Activate selected
            $weight = PriorityWeight::findOrFail($id);
            $weight->update(['is_active' => true]);

            DB::commit();
            $this->loadWeights();
            session()->flash('success', "Konfigurasi '{$weight->name}' sekarang aktif.");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal mengaktifkan konfigurasi.');
        }
    }

    public function delete($id)
    {
        $weight = PriorityWeight::findOrFail($id);
        if ($weight->is_active) {
            session()->flash('error', 'Tidak dapat menghapus konfigurasi yang sedang aktif.');
            return;
        }

        $weight->delete();
        $this->loadWeights();
        session()->flash('success', 'Konfigurasi bobot berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.manajemen-bobot-prioritas');
    }
}
