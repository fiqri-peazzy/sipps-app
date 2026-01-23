<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManajemenPortfolio extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $portfolio_id;
    public $title;
    public $client_name;
    public $description;
    public $image;
    public $material;
    public $size;
    public $method;
    public $is_featured = false;
    public $is_active = true;
    public $existing_image;

    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'client_name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048', // 2MB Max
        'material' => 'nullable|string|max:255',
        'size' => 'nullable|string|max:255',
        'method' => 'nullable|string|max:255',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $portfolios = Portfolio::when($this->search, function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('client_name', 'like', '%' . $this->search . '%')
                ->orWhere('method', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manajemen-portfolio', compact('portfolios'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->dispatch('show-form-modal');
    }

    public function store()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('portfolios', 'public');
        }

        Portfolio::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'client_name' => $this->client_name,
            'description' => $this->description,
            'image' => $imagePath,
            'material' => $this->material,
            'size' => $this->size,
            'method' => $this->method,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('hide-form-modal');
        $this->dispatch('show-toast', ['message' => 'Portfolio berhasil ditambahkan']);
        $this->resetForm();
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->portfolio_id = $portfolio->id;
        $this->title = $portfolio->title;
        $this->client_name = $portfolio->client_name;
        $this->description = $portfolio->description;
        $this->existing_image = $portfolio->image;
        $this->material = $portfolio->material;
        $this->size = $portfolio->size;
        $this->method = $portfolio->method;
        $this->is_featured = $portfolio->is_featured;
        $this->is_active = $portfolio->is_active;

        $this->isEdit = true;
        $this->dispatch('show-form-modal');
    }

    public function update()
    {
        $this->validate();

        $portfolio = Portfolio::findOrFail($this->portfolio_id);

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'client_name' => $this->client_name,
            'description' => $this->description,
            'material' => $this->material,
            'size' => $this->size,
            'method' => $this->method,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            // Delete old image
            if ($portfolio->image) {
                Storage::disk('public')->delete($portfolio->image);
            }
            $data['image'] = $this->image->store('portfolios', 'public');
        }

        $portfolio->update($data);

        $this->dispatch('hide-form-modal');
        $this->dispatch('show-toast', ['message' => 'Portfolio berhasil diperbarui']);
        $this->resetForm();
    }

    public function delete($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $portfolio->delete();
        $this->dispatch('show-toast', ['message' => 'Portfolio berhasil dihapus']);
    }

    public function toggleStatus($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->update(['is_active' => !$portfolio->is_active]);
        $this->dispatch('show-toast', ['message' => 'Status portfolio berhasil diubah']);
    }

    public function toggleFeatured($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->update(['is_featured' => !$portfolio->is_featured]);
        $this->dispatch('show-toast', ['message' => 'Status unggulan berhasil diubah']);
    }

    private function resetForm()
    {
        $this->portfolio_id = null;
        $this->title = null;
        $this->client_name = null;
        $this->description = null;
        $this->image = null;
        $this->existing_image = null;
        $this->material = null;
        $this->size = null;
        $this->method = null;
        $this->is_featured = false;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
