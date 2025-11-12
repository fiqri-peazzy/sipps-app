<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produk;
use App\Models\JenisSablon;
use App\Models\Ukuran;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManajemenProduk extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $produk_id;
    public $jenis_sablon_id;
    public $ukuran_id;
    public $tipe_layanan = 'regular';
    public $harga;
    public $estimasi_waktu;
    public $deskripsi;
    public $is_active = true;

    public $isEdit = false;
    public $search = '';
    public $filterJenis = '';
    public $filterTipe = '';

    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];

    protected $rules = [
        'jenis_sablon_id' => 'required|exists:jenis_sablons,id',
        'ukuran_id' => 'required|exists:ukurans,id',
        'tipe_layanan' => 'required|in:regular,express',
        'harga' => 'required|numeric|min:0',
        'estimasi_waktu' => 'required|integer|min:1',
        'deskripsi' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'jenis_sablon_id.required' => 'Jenis sablon harus dipilih',
        'ukuran_id.required' => 'Ukuran harus dipilih',
        'tipe_layanan.required' => 'Tipe layanan harus dipilih',
        'harga.required' => 'Harga harus diisi',
        'harga.numeric' => 'Harga harus berupa angka',
        'estimasi_waktu.required' => 'Estimasi waktu harus diisi',
        'estimasi_waktu.integer' => 'Estimasi waktu harus berupa angka',
    ];

    public function render()
    {
        $produks = Produk::with(['jenisSablon', 'ukuran'])
            ->when($this->search, function ($query) {
                $query->whereHas('jenisSablon', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })->orWhereHas('ukuran', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis_sablon_id', $this->filterJenis);
            })
            ->when($this->filterTipe, function ($query) {
                $query->where('tipe_layanan', $this->filterTipe);
            })
            ->orderBy('jenis_sablon_id')
            ->orderBy('ukuran_id')
            ->orderBy('tipe_layanan')
            ->paginate(10);

        $jenisSablons = JenisSablon::where('is_active', true)->get();
        $ukurans = Ukuran::where('is_active', true)->get();

        return view('livewire.admin.manajemen-produk', compact('produks', 'jenisSablons', 'ukurans'));
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

        $exists = Produk::where('jenis_sablon_id', $this->jenis_sablon_id)
            ->where('ukuran_id', $this->ukuran_id)
            ->where('tipe_layanan', $this->tipe_layanan)
            ->exists();

        if ($exists) {
            $this->addError('jenis_sablon_id', 'Kombinasi jenis sablon, ukuran, dan tipe layanan sudah ada');
            return;
        }

        Produk::create([
            'jenis_sablon_id' => $this->jenis_sablon_id,
            'ukuran_id' => $this->ukuran_id,
            'tipe_layanan' => $this->tipe_layanan,
            'harga' => $this->harga,
            'estimasi_waktu' => $this->estimasi_waktu,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('hide-form-modal');
        $this->dispatch('show-toast', ['message' => 'Produk berhasil ditambahkan']);
        $this->resetForm();
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        $this->produk_id = $produk->id;
        $this->jenis_sablon_id = $produk->jenis_sablon_id;
        $this->ukuran_id = $produk->ukuran_id;
        $this->tipe_layanan = $produk->tipe_layanan;
        $this->harga = $produk->harga;
        $this->estimasi_waktu = $produk->estimasi_waktu;
        $this->deskripsi = $produk->deskripsi;
        $this->is_active = $produk->is_active;

        $this->isEdit = true;
        $this->dispatch('show-form-modal');
    }

    public function update()
    {
        $this->validate();

        $exists = Produk::where('jenis_sablon_id', $this->jenis_sablon_id)
            ->where('ukuran_id', $this->ukuran_id)
            ->where('tipe_layanan', $this->tipe_layanan)
            ->where('id', '!=', $this->produk_id)
            ->exists();

        if ($exists) {
            $this->addError('jenis_sablon_id', 'Kombinasi jenis sablon, ukuran, dan tipe layanan sudah ada');
            return;
        }

        $produk = Produk::findOrFail($this->produk_id);
        $produk->update([
            'jenis_sablon_id' => $this->jenis_sablon_id,
            'ukuran_id' => $this->ukuran_id,
            'tipe_layanan' => $this->tipe_layanan,
            'harga' => $this->harga,
            'estimasi_waktu' => $this->estimasi_waktu,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('hide-form-modal');
        $this->dispatch('show-toast', ['message' => 'Produk berhasil diperbarui']);
        $this->resetForm();
    }

    public function delete($id)
    {

        $produk = Produk::find($id);
        if ($produk) {
            $produk->delete();
            $this->dispatch('show-toast', ['message' => 'Produk berhasil dihapus']);
            $this->resetPage();
        }
    }

    public function toggleStatus($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update(['is_active' => !$produk->is_active]);
        $this->dispatch('show-toast', ['message' => 'Status produk berhasil diubah']);
    }

    private function resetForm()
    {
        $this->produk_id = null;
        $this->jenis_sablon_id = null;
        $this->ukuran_id = null;
        $this->tipe_layanan = 'regular';
        $this->harga = null;
        $this->estimasi_waktu = null;
        $this->deskripsi = null;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function updatingFilterTipe()
    {
        $this->resetPage();
    }
}
