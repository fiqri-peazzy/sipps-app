<?php

namespace App\Livewire\Customer;

use App\Models\JenisSablon;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PlaceOrderForm extends Component
{
    // Data
    public $jenisSablons;
    public $ukurans;
    public $selectedJenis;

    // Form Order Items
    public $orderItems = [];
    public $itemIndex = 0;

    // Shipping Address
    public $penerima_nama;
    public $penerima_telepon;
    public $alamat_lengkap;
    public $kelurahan;
    public $kecamatan;
    public $kota = '';
    public $provinsi = '';
    public $kode_pos;
    public $tipe_pengiriman = 'dalam_kota';

    // RajaOngkir Fields
    public $provinsi_id;
    public $kota_id;
    public $district_id;
    public $subdistrict_id;
    public $kurir_code;
    public $kurir_service;
    public $kurir_name;
    public $kurir_etd;
    public $kecamatan_id; // Alias untuk district_id

    // Additional
    public $catatan;

    // Calculation
    public $subtotal = 0;
    public $ongkir = 0;
    public $total = 0;
    public $totalWeight = 0; // Berat total dalam gram

    // Berat 1 kaos katun kombet 24s dalam gram
    const WEIGHT_PER_ITEM = 180;

    protected $rules = [
        'orderItems.*.produk_id' => 'required|exists:produks,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
        'orderItems.*.ukuran_kaos' => 'required|in:S,M,L,XL,XXL,XXXL',
        'orderItems.*.catatan_item' => 'nullable|string',
        'penerima_nama' => 'required|string|max:255',
        'penerima_telepon' => 'required|string|max:20',
        'alamat_lengkap' => 'required|string',
        'provinsi' => 'required|string',
        'kota' => 'required|string',
        'kota_id' => 'required|integer',
        'district_id' => 'required|integer',
        'tipe_pengiriman' => 'required|in:dalam_kota,antar_kota',
    ];

    protected $messages = [
        'kota_id.required' => 'Kota tujuan harus dipilih',
        'provinsi.required' => 'Provinsi harus dipilih',
        'district_id.required' => 'Kecamatan harus dipilih',
    ];

    public function mount($jenisSablons, $ukurans, $selectedJenis = null)
    {
        $this->jenisSablons = $jenisSablons;
        $this->ukurans = $ukurans;
        $this->selectedJenis = $selectedJenis;

        $user = Auth::user();
        $this->penerima_nama = $user->name;
        $this->penerima_telepon = $user->phone ?? '';

        // Add first item
        $this->addItem();

        // Load designs from session if exists
        $sessionKey = 'order_designs_' . Auth::id();
        if (session()->has($sessionKey)) {
            $savedDesigns = session($sessionKey);
            foreach ($savedDesigns as $index => $designConfig) {
                if (isset($this->orderItems[$index])) {
                    $this->orderItems[$index]['design_config'] = $designConfig;
                    if (isset($designConfig['ukuran_kaos'])) {
                        $this->orderItems[$index]['ukuran_kaos'] = $designConfig['ukuran_kaos'];
                    }
                }
            }
        }

        // Calculate initial weight
        $this->calculateTotalWeight();
    }

    public function addItem()
    {
        $this->orderItems[] = [
            'id' => $this->itemIndex++,
            'jenis_sablon_id' => $this->selectedJenis ?? null,
            'produk_id' => null,
            'quantity' => 1,
            'ukuran_kaos' => 'M',
            'design_config' => null,
            'catatan_item' => '',
            'harga_satuan' => 0,
            'subtotal' => 0,
        ];

        $this->calculateTotal();
        $this->calculateTotalWeight();
    }

    public function removeItem($itemId)
    {
        $this->orderItems = array_values(array_filter($this->orderItems, function ($item) use ($itemId) {
            return $item['id'] !== $itemId;
        }));

        $this->calculateTotal();
        $this->calculateTotalWeight();
    }

    public function handleDesignConfigSaved($itemIndex, $designConfig)
    {
        if (!isset($this->orderItems[$itemIndex])) {
            session()->flash('error', 'Item tidak ditemukan!');
            return;
        }

        $this->orderItems[$itemIndex]['design_config'] = $designConfig;

        if (isset($designConfig['ukuran_kaos'])) {
            $this->orderItems[$itemIndex]['ukuran_kaos'] = $designConfig['ukuran_kaos'];
        }

        // Save to session
        $sessionKey = 'order_designs_' . Auth::id();
        $sessionData = session($sessionKey, []);
        $sessionData[$itemIndex] = $designConfig;
        session([$sessionKey => $sessionData]);

        session()->flash('message', 'Desain berhasil disimpan!');
        $this->dispatch('$refresh');
    }

    public function updatedOrderItems($value, $key)
    {
        preg_match('/(\d+)\.(.+)/', $key, $matches);
        $index = $matches[1] ?? null;
        $field = $matches[2] ?? null;

        if ($field === 'produk_id' && $index !== null) {
            $produk = Produk::find($value);
            if ($produk) {
                $this->orderItems[$index]['harga_satuan'] = $produk->harga;
                $this->orderItems[$index]['subtotal'] = $produk->harga * ($this->orderItems[$index]['quantity'] ?? 1);
            }
        }

        if ($field === 'quantity' && $index !== null) {
            $this->orderItems[$index]['subtotal'] = ($this->orderItems[$index]['harga_satuan'] ?? 0) * $value;
            $this->calculateTotalWeight();
        }

        $this->calculateTotal();
    }

    public function calculateTotalWeight()
    {
        $totalQty = collect($this->orderItems)->sum('quantity');
        $this->totalWeight = $totalQty * self::WEIGHT_PER_ITEM;

        return $this->totalWeight;
    }

    public function calculateTotal()
    {
        $this->subtotal = collect($this->orderItems)->sum('subtotal');
        $this->total = $this->subtotal + $this->ongkir;
    }

    public function updatedTipePengiriman()
    {
        // Recalculate if needed
        if ($this->tipe_pengiriman === 'dalam_kota') {
            $this->ongkir = 10000;
            $this->calculateTotal();
        }
    }

    public function submit()
    {
        // Validate with custom rules for antar kota
        if ($this->tipe_pengiriman === 'antar_kota') {
            $this->rules['kurir_code'] = 'required|string';
            $this->rules['kurir_service'] = 'required|string';
            $this->messages['kurir_code.required'] = 'Pilih layanan pengiriman terlebih dahulu';
        }

        $this->validate();

        // Validate ongkir sudah terisi
        if ($this->ongkir <= 0) {
            session()->flash('error', 'Ongkos kirim belum dihitung. Pastikan kota tujuan sudah dipilih.');
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'pending_payment',
                'subtotal' => $this->subtotal,
                'ongkir' => $this->ongkir,
                'total_harga' => $this->total,
                'total_item' => count($this->orderItems),
                'berat_total' => $this->totalWeight,
                'catatan' => $this->catatan,
                'tipe_pengiriman' => $this->tipe_pengiriman,
                'kurir' => $this->kurir_name ?? $this->kurir_code,
                'service_kurir' => $this->kurir_service,
                'estimasi_pengiriman' => $this->kurir_etd,
                'province_id' => $this->provinsi_id,
                'city_id' => $this->kota_id,
                'district_id' => $this->district_id,
                'subdistrict_id' => $this->subdistrict_id,
                'penerima_nama' => $this->penerima_nama,
                'penerima_telepon' => $this->penerima_telepon,
                'alamat_lengkap' => $this->alamat_lengkap,
                'kelurahan' => $this->kelurahan,
                'kecamatan' => $this->kecamatan,
                'kota' => $this->kota,
                'kota_id' => $this->kota_id,
                'provinsi' => $this->provinsi,
                'provinsi_id' => $this->provinsi_id,
                'kode_pos' => $this->kode_pos,
            ]);

            foreach ($this->orderItems as $item) {
                $produk = Produk::find($item['produk_id']);
                $designConfig = $item['design_config'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'ukuran_kaos' => $item['ukuran_kaos'],
                    'warna_kaos' => $designConfig['warna_kaos'] ?? 'putih',
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $produk->harga * $item['quantity'],
                    'design_config' => $designConfig,
                    'catatan_item' => $item['catatan_item'],
                    'deadline' => now()->addDays($produk->estimasi_hari ?? 3),
                ]);
            }

            DB::commit();

            // Clear session
            $sessionKey = 'order_designs_' . Auth::id();
            session()->forget($sessionKey);

            session()->flash('success', 'Pesanan berhasil dibuat!');
            return redirect()->route('customer.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Creation Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function clearDesign($itemIndex)
    {
        if (isset($this->orderItems[$itemIndex])) {
            $this->orderItems[$itemIndex]['design_config'] = null;

            // Clear from session
            $sessionKey = 'order_designs_' . Auth::id();
            $sessionData = session($sessionKey, []);
            unset($sessionData[$itemIndex]);
            session([$sessionKey => $sessionData]);

            session()->flash('message', 'Desain berhasil dihapus!');
        }
    }

    public function render()
    {
        return view('livewire.customer.place-order-form');
    }
}
