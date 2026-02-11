<?php

namespace App\Livewire\Customer;

use App\Models\JenisSablon;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaOngkirService; // TAMBAHKAN
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

    // TAMBAHKAN: Data untuk populate select options
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subdistricts = [];
    public $courierOptions = [];

    // TAMBAHKAN: Loading states
    public $loadingCities = false;
    public $loadingDistricts = false;
    public $loadingSubdistricts = false;
    public $loadingShippingCost = false;

    // Additional
    public $catatan;

    // Calculation
    public $subtotal = 0;
    public $ongkir = 0;
    public $total = 0;
    public $totalWeight = 0;

    const WEIGHT_PER_ITEM = 180;
    const LONG_SLEEVE_EXTRA_PRICE = 10000;

    protected $rules = [
        'orderItems.*.produk_id' => 'required|exists:produks,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
        'orderItems.*.ukuran_kaos' => 'required|in:S,M,L,XL,XXL,XXXL',
        'orderItems.*.tipe_lengan' => 'required|in:pendek,panjang',
        'orderItems.*.catatan_item' => 'nullable|string',
        'penerima_nama' => 'required|string|max:255',
        'penerima_telepon' => 'required|string|max:20',
        'alamat_lengkap' => 'required|string',
        'provinsi_id' => 'required|integer',
        'kota_id' => 'required|integer',
        'district_id' => 'required|integer',
        'tipe_pengiriman' => 'required|in:dalam_kota,antar_kota',
        'provinsi' => 'nullable|string',
        'kota' => 'nullable|string',
    ];

    protected $messages = [
        'orderItems.*.produk_id.required' => 'Wajib pilih produk untuk setiap item',
        'penerima_nama.required' => 'Nama penerima wajib diisi',
        'penerima_telepon.required' => 'Nomor telepon/WA wajib diisi',
        'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
        'provinsi_id.required' => 'Pilih provinsi terlebih dahulu',
        'kota_id.required' => 'Kota tujuan harus dipilih',
        'district_id.required' => 'Kecamatan harus dipilih',
    ];

    public function mount($jenisSablons, $ukurans, $selectedJenis = null)
    {
        $this->jenisSablons = $jenisSablons;
        $this->ukurans = $ukurans;
        $this->selectedJenis = $selectedJenis;

        // Load provinces must be done first OR inside restoration
        $this->loadProvinces();

        // Restore from session if exists
        if (session()->has('place_order_form_state')) {
            $state = session('place_order_form_state');
            foreach ($state as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }

            // Re-fetch options to populate selects
            try {
                $service = app(RajaOngkirService::class);
                if ($this->provinsi_id) {
                    $this->cities = $service->getCities($this->provinsi_id);
                }
                if ($this->kota_id) {
                    $this->districts = $service->getDistricts($this->kota_id);
                }
                if ($this->district_id) {
                    $this->subdistricts = $service->getSubDistricts($this->district_id);

                    // Re-populate courier options if it's antar_kota
                    if ($this->tipe_pengiriman === 'antar_kota') {
                        $this->calculateShippingCost();
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Failed to restore RajaOngkir options: " . $e->getMessage());
            }

            $this->calculateTotal();
            $this->calculateTotalWeight();

            // FALLBACK: Sync designs from alternative session key if missing
            $altSessionKey = 'order_designs_' . Auth::id();
            if (session()->has($altSessionKey)) {
                $altDesigns = session($altSessionKey);
                foreach ($this->orderItems as $idx => &$item) {
                    if (empty($item['design_config']) && isset($altDesigns[$idx])) {
                        $item['design_config'] = $altDesigns[$idx];
                        if (isset($altDesigns[$idx]['warna_kaos'])) {
                            $item['warna_kaos'] = $altDesigns[$idx]['warna_kaos'];
                        }
                    }
                }
            }
        } else {
            $user = Auth::user();
            $this->penerima_nama = $user->name;
            $this->penerima_telepon = $user->phone ?? '';

            if (empty($this->orderItems)) {
                $this->addItem();
            }
        }
    }

    // ==================== RAJAONGKIR METHODS ====================

    /**
     * Load provinces
     */
    public function loadProvinces()
    {
        $rajaOngkir = app(RajaOngkirService::class);
        $this->provinces = $rajaOngkir->getProvinces();
    }

    /**
     * Updated hook untuk provinsi_id
     */
    public function updatedProvinsiId($value)
    {
        if (!$value)
            return;

        // Reset dependent fields
        $this->kota_id = null;
        $this->kota = '';
        $this->district_id = null;
        $this->kecamatan = '';
        $this->subdistrict_id = null;
        $this->kelurahan = '';
        $this->cities = [];
        $this->districts = [];
        $this->subdistricts = [];
        $this->courierOptions = [];
        $this->ongkir = 0;
        $this->tipe_pengiriman = '';

        // Get province name
        $province = collect($this->provinces)->firstWhere('id', $value);
        if ($province) {
            $this->provinsi = $province['name'];
        }

        // Load cities
        $this->loadCities($value);

        $this->calculateTotal();
    }

    /**
     * Load cities by province
     */
    public function loadCities($provinceId)
    {
        $this->loadingCities = true;

        $rajaOngkir = app(RajaOngkirService::class);
        $this->cities = $rajaOngkir->getCities($provinceId);

        $this->loadingCities = false;
    }

    /**
     * Updated hook untuk kota_id
     */
    public function updatedKotaId($value)
    {
        if (!$value)
            return;

        // Reset dependent fields
        $this->district_id = null;
        $this->kecamatan = '';
        $this->subdistrict_id = null;
        $this->kelurahan = '';
        $this->districts = [];
        $this->subdistricts = [];
        $this->courierOptions = [];
        $this->ongkir = 0;

        // Get city name
        $city = collect($this->cities)->firstWhere('id', $value);
        if ($city) {
            $this->kota = $city['name'];
        }

        // Load districts
        $this->loadDistricts($value);

        $this->calculateTotal();
    }

    /**
     * Load districts by city
     */
    public function loadDistricts($cityId)
    {
        $this->loadingDistricts = true;

        $rajaOngkir = app(RajaOngkirService::class);
        $this->districts = $rajaOngkir->getDistricts($cityId);

        $this->loadingDistricts = false;
    }

    /**
     * Updated hook untuk district_id
     */
    public function updatedDistrictId($value)
    {
        if (!$value)
            return;

        // Reset dependent fields
        $this->subdistrict_id = null;
        $this->kelurahan = '';
        $this->subdistricts = [];
        $this->courierOptions = [];
        $this->ongkir = 0;

        // Get district name
        $district = collect($this->districts)->firstWhere('id', $value);
        if ($district) {
            $this->kecamatan = $district['name'];
        }

        // Load subdistricts (optional, karena bisa langsung calculate)
        $this->loadSubDistricts($value);

        // Calculate shipping cost
        $this->calculateShippingCost();
    }

    /**
     * Load subdistricts by district (optional)
     */
    public function loadSubDistricts($districtId)
    {
        $this->loadingSubdistricts = true;

        $rajaOngkir = app(RajaOngkirService::class);
        $this->subdistricts = $rajaOngkir->getSubDistricts($districtId);

        $this->loadingSubdistricts = false;
    }

    /**
     * Updated hook untuk subdistrict_id (optional)
     */
    public function updatedSubdistrictId($value)
    {
        if (!$value)
            return;

        // Get subdistrict name
        $subdistrict = collect($this->subdistricts)->firstWhere('id', $value);
        if ($subdistrict) {
            $this->kelurahan = $subdistrict['name'];
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost()
    {
        if (!$this->kota_id || !$this->district_id) {
            return;
        }

        $this->loadingShippingCost = true;
        $this->courierOptions = [];

        $rajaOngkir = app(RajaOngkirService::class);
        $originCityId = $rajaOngkir->getOriginCityId();

        // Check if same city
        if ($rajaOngkir->isSameCity($originCityId, $this->kota_id)) {
            // Dalam kota Gorontalo
            $this->tipe_pengiriman = 'dalam_kota';
            $this->ongkir = 6000;
            $this->kurir_code = 'local';
            $this->kurir_name = 'Pengiriman Dalam Kota';
            $this->kurir_service = 'FLAT';
            $this->kurir_etd = '1 hari';
            $this->courierOptions = [];
        } else {
            // Antar kota - calculate via RajaOngkir
            $this->tipe_pengiriman = 'antar_kota';

            $originDistrictId = $rajaOngkir->getGorontaloOriginDistrictId();
            $result = $rajaOngkir->calculateCost(
                $originDistrictId,
                $this->district_id,
                $this->totalWeight
            );

            if ($result['success'] && !empty($result['data'])) {
                $this->courierOptions = $result['data'];

                // Set default: pilih yang termurah (index 0)
                $cheapest = $this->courierOptions[0];
                $this->selectCourier(0);
            } else {
                session()->flash('error', 'Tidak ada layanan pengiriman tersedia untuk tujuan ini');
                $this->ongkir = 0;
            }
        }

        $this->calculateTotal();
        $this->loadingShippingCost = false;
    }

    /**
     * Select courier option
     */
    public function selectCourier($index)
    {
        if (!isset($this->courierOptions[$index])) {
            return;
        }

        $courier = $this->courierOptions[$index];
        $this->kurir_code = $courier['code'];
        $this->kurir_name = $courier['name'];
        $this->kurir_service = $courier['service'];
        $this->kurir_etd = $courier['etd'];
        $this->ongkir = $courier['cost'];

        $this->calculateTotal();
    }

    // ==================== EXISTING METHODS ====================

    public function addItem()
    {
        $this->orderItems[] = [
            'id' => $this->itemIndex++,
            'jenis_sablon_id' => $this->selectedJenis ?? null,
            'produk_id' => null,
            'quantity' => 1,
            'ukuran_kaos' => 'M',
            'tipe_lengan' => 'pendek',
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

        // Recalculate shipping jika ada perubahan berat
        if ($this->district_id) {
            $this->calculateShippingCost();
        }
    }

    public function goToDesignEditor($index)
    {
        // Pastikan index ada
        if (!isset($this->orderItems[$index]))
            return;

        // Simpan state form saat ini ke session sebelum pindah halaman
        session([
            'place_order_form_state' => [
                'orderItems' => $this->orderItems,
                'itemIndex' => $this->itemIndex,
                'selectedJenis' => $this->selectedJenis,
                'penerima_nama' => $this->penerima_nama,
                'penerima_telepon' => $this->penerima_telepon,
                'alamat_lengkap' => $this->alamat_lengkap,
                'provinsi_id' => $this->provinsi_id,
                'kota_id' => $this->kota_id,
                'district_id' => $this->district_id,
                'tipe_pengiriman' => $this->tipe_pengiriman,
                'provinsi' => $this->provinsi,
                'kota' => $this->kota,
                'kecamatan' => $this->kecamatan,
                'kelurahan' => $this->kelurahan,
                'kode_pos' => $this->kode_pos,
                'kurir_code' => $this->kurir_code,
                'kurir_service' => $this->kurir_service,
                'kurir_name' => $this->kurir_name,
                'kurir_etd' => $this->kurir_etd,
                'ongkir' => $this->ongkir,
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'totalWeight' => $this->totalWeight,
            ]
        ]);

        return redirect()->route('customer.design-editor.page', ['index' => $index]);
    }

    public function handleDesignConfigSaved($itemIndex, $designConfig)
    {
        if (!isset($this->orderItems[$itemIndex])) {
            session()->flash('error', 'Item tidak ditemukan!');
            return;
        }

        $this->orderItems[$itemIndex]['design_config'] = $designConfig;
        
        // Sync warna_kaos if present
        if (isset($designConfig['warna_kaos'])) {
            $this->orderItems[$itemIndex]['warna_kaos'] = $designConfig['warna_kaos'];
        }

        // Sync with place_order_form_state for consistency
        if (session()->has('place_order_form_state')) {
            $state = session('place_order_form_state');
            $state['orderItems'][$itemIndex]['design_config'] = $designConfig;
            if (isset($designConfig['warna_kaos'])) {
                $state['orderItems'][$itemIndex]['warna_kaos'] = $designConfig['warna_kaos'];
            }
            session(['place_order_form_state' => $state]);
        }

        // Simpan ke session juga agar persisten jika user refresh
        $sessionKey = 'order_designs_' . Auth::id();
        $sessionData = session($sessionKey, []);
        $sessionData[$itemIndex] = $designConfig;
        session([$sessionKey => $sessionData]);

        session()->flash('success', 'Desain berhasil disimpan!');
        $this->dispatch('$refresh');
    }

    public function updatedOrderItems($value, $key)
    {
        preg_match('/(\d+)\.(.+)/', $key, $matches);
        $index = $matches[1] ?? null;
        $field = $matches[2] ?? null;

        if ($field === 'produk_id' || $field === 'tipe_lengan' || $field === 'quantity') {
            if ($index !== null && isset($this->orderItems[$index]['produk_id'])) {
                $produk = Produk::find($this->orderItems[$index]['produk_id']);
                if ($produk) {
                    $baseHarga = (int) $produk->harga;
                    $extraHarga = ($this->orderItems[$index]['tipe_lengan'] === 'panjang') ? self::LONG_SLEEVE_EXTRA_PRICE : 0;

                    $this->orderItems[$index]['harga_satuan'] = $baseHarga + $extraHarga;
                    $this->orderItems[$index]['subtotal'] = ($baseHarga + $extraHarga) * (int) ($this->orderItems[$index]['quantity'] ?? 1);

                    $this->calculateTotalWeight();

                    // Recalculate shipping jika ada perubahan quantity atau berat
                    if ($this->district_id) {
                        $this->calculateShippingCost();
                    }
                }
            }
        }

        $this->calculateTotal();
    }

    public function calculateTotalWeight()
    {
        $totalQty = collect($this->orderItems)->sum(fn($item) => (int) ($item['quantity'] ?? 0));
        $this->totalWeight = $totalQty * self::WEIGHT_PER_ITEM;
        return $this->totalWeight;
    }

    public function calculateTotal()
    {
        $this->subtotal = collect($this->orderItems)->sum(fn($item) => (int) ($item['subtotal'] ?? 0));
        $this->total = $this->subtotal + $this->ongkir;
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

                // Pindahkan file dari temp ke permanent
                if ($designConfig && isset($designConfig['file_metadata'])) {
                    $designConfig = $this->moveTempFilesToPermanent($designConfig, $order->order_number);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'ukuran_kaos' => $item['ukuran_kaos'],
                    'tipe_lengan' => $item['tipe_lengan'] ?? 'pendek',
                    'warna_kaos' => $designConfig['warna_kaos'] ?? 'putih',
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                    'design_config' => $designConfig,
                    'catatan_item' => $item['catatan_item'],
                    'deadline' => now()->addDays($produk->estimasi_hari ?? 3),
                ]);
            }

            DB::commit();

            // Clear session
            $sessionKey = 'order_designs_' . Auth::id();
            session()->forget($sessionKey);
            session()->forget('place_order_form_state');

            session()->flash('success', 'Pesanan berhasil dibuat!');
            return redirect()->route('customer.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Pindahkan file dari folder temp ke permanent saat order di-submit
     */
    private function moveTempFilesToPermanent($designConfig, $orderNumber)
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];

        foreach ($fileMetadata as $area => &$files) {
            foreach ($files as &$file) {
                if ($file['type'] === 'image' && isset($file['original_path'])) {
                    $tempPath = $file['original_path'];

                    // Cek apakah file dari folder temp
                    if (strpos($tempPath, 'designs/temp/') !== false) {
                        // Generate permanent path
                        $fileName = basename($tempPath);
                        $permanentPath = "designs/originals/{$orderNumber}/{$fileName}";

                        // Move file dari temp ke permanent
                        if (Storage::disk('public')->exists($tempPath)) {
                            // Buat folder jika belum ada
                            $permanentDir = dirname(Storage::disk('public')->path($permanentPath));
                            if (!file_exists($permanentDir)) {
                                mkdir($permanentDir, 0755, true);
                            }

                            // Copy file (bukan move, untuk jaga-jaga)
                            Storage::disk('public')->copy($tempPath, $permanentPath);

                            // Update path di metadata
                            $file['original_path'] = $permanentPath;

                            // Update canvas JSON jika ada
                            if (isset($designConfig['canvas_data'][$area])) {
                                $canvasJson = json_decode($designConfig['canvas_data'][$area], true);

                                foreach ($canvasJson['objects'] as &$obj) {
                                    if ($obj['type'] === 'image' && isset($obj['originalFilePath']) && $obj['originalFilePath'] === $tempPath) {
                                        $obj['originalFilePath'] = $permanentPath;
                                        $obj['src'] = Storage::url($permanentPath);
                                    }
                                }

                                $designConfig['canvas_data'][$area] = json_encode($canvasJson);
                            }
                        }
                    }
                }
            }
        }

        $designConfig['file_metadata'] = $fileMetadata;
        return $designConfig;
    }

    public function clearDesign($itemIndex)
    {
        if (isset($this->orderItems[$itemIndex])) {
            $designConfig = $this->orderItems[$itemIndex]['design_config'];

            // Hapus file temporary saat clear design
            if ($designConfig && isset($designConfig['file_metadata'])) {
                $this->deleteTempFiles($designConfig);
            }

            $this->orderItems[$itemIndex]['design_config'] = null;

            // Sync dengan session state utama
            if (session()->has('place_order_form_state')) {
                $state = session('place_order_form_state');
                if (isset($state['orderItems'][$itemIndex])) {
                    $state['orderItems'][$itemIndex]['design_config'] = null;
                    session(['place_order_form_state' => $state]);
                }
            }

            session()->flash('message', 'Desain berhasil dihapus!');
        }
    }

    /**
     * Hapus file temporary dari storage
     */
    private function deleteTempFiles($designConfig)
    {
        $fileMetadata = $designConfig['file_metadata'] ?? [];

        foreach ($fileMetadata as $area => $files) {
            foreach ($files as $file) {
                if ($file['type'] === 'image' && isset($file['original_path'])) {
                    $tempPath = $file['original_path'];

                    // Hanya hapus jika dari folder temp
                    if (strpos($tempPath, 'designs/temp/') !== false) {
                        if (Storage::disk('public')->exists($tempPath)) {
                            Storage::disk('public')->delete($tempPath);
                        }
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.place-order-form');
    }
}
