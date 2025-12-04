<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Services\TrackingMoreService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\ShippingTracking;

class ShippingController extends Controller
{
    protected $trackingService;
    protected $whatsappService;
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir, TrackingMoreService $trackingMoreService, WhatsAppService $whatsAppService)
    {
        $this->rajaOngkir = $rajaOngkir;
        $this->trackingService = $trackingMoreService;
        $this->whatsappService = $whatsAppService;
    }

    /**
     * Halaman daftar pengiriman
     */
    // public function index(Request $request)
    // {
    //     $query = Order::with(['user', 'items'])
    //         ->whereIn('status', ['ready_to_ship', 'shipped', 'completed']);

    //     // Filter status order
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // Filter status pengiriman
    //     if ($request->filled('status_pengiriman')) {
    //         $query->where('status_pengiriman', $request->status_pengiriman);
    //     }

    //     // Filter kurir
    //     if ($request->filled('kurir')) {
    //         $query->where('kurir', $request->kurir);
    //     }

    //     // Search
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('order_number', 'like', "%{$search}%")
    //                 ->orWhere('resi', 'like', "%{$search}%")
    //                 ->orWhere('penerima_nama', 'like', "%{$search}%");
    //         });
    //     }

    //     $orders = $query->latest()->paginate(20);

    //     // Get distinct kurir untuk filter
    //     $kurirs = Order::whereNotNull('kurir')
    //         ->distinct()
    //         ->pluck('kurir');

    //     return view('admin.shipping.index', compact('orders', 'kurirs'));
    // }

    // /**
    //  * Detail pengiriman order
    //  */
    // public function show(Order $order)
    // {
    //     $order->load(['user', 'items.produk', 'shippingTrackings' => function ($q) {
    //         $q->orderBy('tracked_at', 'desc');
    //     }]);

    //     return view('admin.shipping.show', compact('order'));
    // }

    // /**
    //  * Input nomor resi dan kirim paket
    //  */
    // public function inputResi(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'resi' => 'required|string|max:255',
    //     ]);

    //     // Validasi: order harus ready_to_ship
    //     if ($order->status !== 'ready_to_ship') {
    //         return back()->with('error', 'Order tidak dalam status siap kirim');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $resi = $request->resi;

    //         // Step 1: Detect courier dari nomor resi
    //         $detectResult = $this->trackingService->detectCourier($resi);

    //         if (!$detectResult['success'] || empty($detectResult['couriers'])) {
    //             return back()->with('error', 'Tidak dapat mendeteksi kurir dari nomor resi. Pastikan nomor resi benar.');
    //         }

    //         // Ambil courier pertama yang terdeteksi
    //         $courier = $detectResult['couriers'][0];
    //         $courierCode = $courier['courier_code'];
    //         $courierName = $courier['courier_name'];

    //         Log::info('Courier Detected', [
    //             'resi' => $resi,
    //             'courier_code' => $courierCode,
    //             'courier_name' => $courierName,
    //         ]);

    //         // Step 2: Create tracking di TrackingMore
    //         $createResult = $this->trackingService->createTracking($resi, $courierCode);

    //         if (!$createResult['success']) {
    //             DB::rollBack();
    //             return back()->with('error', 'Gagal membuat tracking: ' . $createResult['message']);
    //         }

    //         // Step 3: Update order
    //         // $order->update([
    //         //     'resi' => $resi,
    //         //     'status' => 'shipped',
    //         //     'status_pengiriman' => 'picked_up',
    //         //     'shipped_at' => now(),
    //         // ]);

    //         // // Step 4: Create initial shipping tracking record
    //         // ShippingTracking::create([
    //         //     'order_id' => $order->id,
    //         //     'status' => 'picked_up',
    //         //     'description' => 'Paket telah diambil oleh kurir ' . $courierName,
    //         //     'location' => null,
    //         //     'tracked_at' => now(),
    //         // ]);

    //         // Step 5: Kirim notifikasi WA ke customer (placeholder)
    //         $this->whatsappService->sendShippingNotification(
    //             $order->penerima_telepon,
    //             $order->order_number,
    //             $resi,
    //             $courierName
    //         );

    //         DB::commit();

    //         Log::info('Shipping Created Successfully', [
    //             'order_id' => $order->id,
    //             'order_number' => $order->order_number,
    //             'resi' => $resi,
    //             'courier' => $courierName,
    //         ]);

    //         return redirect()->route('admin.shipping.show', $order->id)
    //             ->with('success', 'Nomor resi berhasil disimpan dan tracking diaktifkan');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Input Resi Error', [
    //             'order_id' => $order->id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Refresh tracking dari TrackingMore API
    //  */
    // public function refreshTracking(Order $order)
    // {
    //     if (!$order->resi) {
    //         return back()->with('error', 'Order belum memiliki nomor resi');
    //     }

    //     try {
    //         // Detect courier lagi (atau ambil dari field jika sudah disimpan)
    //         $detectResult = $this->trackingService->detectCourier($order->resi);

    //         if (!$detectResult['success'] || empty($detectResult['couriers'])) {
    //             return back()->with('error', 'Tidak dapat mendeteksi kurir');
    //         }

    //         $courierCode = $detectResult['couriers'][0]['courier_code'];

    //         // Get tracking info dari TrackingMore
    //         $trackingResult = $this->trackingService->getTracking($order->resi, $courierCode);

    //         if (!$trackingResult['success']) {
    //             return back()->with('error', 'Gagal mendapatkan tracking info: ' . $trackingResult['message']);
    //         }

    //         $trackingData = $trackingResult['data'];

    //         // Parse events dan simpan ke database
    //         $events = $this->trackingService->parseTrackingEvents($trackingData);

    //         DB::beginTransaction();
    //         foreach ($events as $event) {
    //             // Cek apakah event sudah ada (berdasarkan tracked_at dan description)
    //             $exists = ShippingTracking::where('order_id', $order->id)
    //                 ->where('tracked_at', $event['tracked_at'])
    //                 ->where('description', $event['description'])
    //                 ->exists();

    //             if (!$exists) {
    //                 ShippingTracking::create([
    //                     'order_id' => $order->id,
    //                     'status' => $event['status'],
    //                     'description' => $event['description'],
    //                     'location' => $event['location'],
    //                     'tracked_at' => $event['tracked_at'],
    //                 ]);

    //                 // Update status pengiriman order
    //                 $order->update([
    //                     'status_pengiriman' => $event['status'],
    //                 ]);

    //                 // Kirim notifikasi WA (placeholder)
    //                 $this->whatsappService->sendTrackingUpdate(
    //                     $order->penerima_telepon,
    //                     $order->order_number,
    //                     $event['status'],
    //                     $event['description'],
    //                     $event['location']
    //                 );
    //             }
    //         }

    //         // Update status order jika sudah delivered
    //         if ($order->status_pengiriman === 'delivered' && $order->status === 'shipped') {
    //             $order->update([
    //                 'status' => 'completed',
    //                 'completed_at' => now(),
    //             ]);
    //         }

    //         DB::commit();

    //         return back()->with('success', 'Tracking berhasil diperbarui');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Refresh Tracking Error', [
    //             'order_id' => $order->id,
    //             'error' => $e->getMessage(),
    //         ]);

    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Webhook listener dari TrackingMore
    //  */
    // public function webhook(Request $request)
    // {
    //     Log::info('TrackingMore Webhook Received', $request->all());

    //     try {
    //         $data = $request->all();

    //         // Validate webhook data
    //         if (!isset($data['tracking_number'])) {
    //             return response()->json(['message' => 'Invalid webhook data'], 400);
    //         }

    //         $trackingNumber = $data['tracking_number'];
    //         $status = $data['status'] ?? 'unknown';
    //         $description = $data['status_info'] ?? 'Update status';
    //         $location = $data['location'] ?? null;

    //         // Find order by resi
    //         $order = Order::where('resi', $trackingNumber)->first();

    //         if (!$order) {
    //             Log::warning('Order not found for webhook', ['tracking_number' => $trackingNumber]);
    //             return response()->json(['message' => 'Order not found'], 404);
    //         }

    //         // Create shipping tracking record
    //         ShippingTracking::create([
    //             'order_id' => $order->id,
    //             'status' => $this->mapWebhookStatus($status),
    //             'description' => $description,
    //             'location' => $location,
    //             'tracked_at' => now(),
    //         ]);

    //         // Update order status
    //         $mappedStatus = $this->mapWebhookStatus($status);
    //         $order->update([
    //             'status_pengiriman' => $mappedStatus,
    //         ]);

    //         // Jika delivered, update status order ke completed
    //         if ($mappedStatus === 'delivered' && $order->status === 'shipped') {
    //             $order->update([
    //                 'status' => 'completed',
    //                 'completed_at' => now(),
    //             ]);
    //         }

    //         // Kirim notifikasi WA
    //         $this->whatsappService->sendTrackingUpdate(
    //             $order->penerima_telepon,
    //             $order->order_number,
    //             $mappedStatus,
    //             $description,
    //             $location
    //         );

    //         return response()->json(['message' => 'Webhook processed successfully'], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Webhook Processing Error', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json(['message' => 'Webhook processing failed'], 500);
    //     }
    // }

    // /**
    //  * Map webhook status ke internal status
    //  */
    // private function mapWebhookStatus($status)
    // {
    //     $statusMap = [
    //         'InfoReceived' => 'pending',
    //         'InTransit' => 'in_transit',
    //         'OutForDelivery' => 'in_transit',
    //         'Delivered' => 'delivered',
    //         'AvailableForPickup' => 'picked_up',
    //         'Exception' => 'returned',
    //     ];

    //     return $statusMap[$status] ?? 'pending';
    // }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->whereIn('status', ['ready_to_ship', 'shipped', 'completed']);

        // Filter status order
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter status pengiriman
        if ($request->filled('status_pengiriman')) {
            $query->where('status_pengiriman', $request->status_pengiriman);
        }

        // Filter kurir
        if ($request->filled('kurir')) {
            $query->where('kurir', $request->kurir);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('resi', 'like', "%{$search}%")
                    ->orWhere('penerima_nama', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20);

        // Get distinct kurir untuk filter
        $kurirs = Order::whereNotNull('kurir')
            ->distinct()
            ->pluck('kurir');

        return view('admin.shipping.index', compact('orders', 'kurirs'));
    }

    /**
     * Detail pengiriman order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.produk', 'shippingTrackings' => function ($q) {
            $q->orderBy('tracked_at', 'desc');
        }]);

        return view('admin.shipping.show', compact('order'));
    }

    /**
     * Input nomor resi dan kirim paket (MANUAL VERSION)
     */
    public function inputResi(Request $request, Order $order)
    {
        $request->validate([
            'resi' => 'required|string|max:255',
        ]);

        // Validasi: order harus ready_to_ship
        if ($order->status !== 'ready_to_ship') {
            return back()->with('error', 'Order tidak dalam status siap kirim');
        }

        DB::beginTransaction();
        try {
            $resi = $request->resi;

            // Update order - SIMPLE VERSION
            $order->update([
                'resi' => $resi,
                'status' => 'shipped',
                'status_pengiriman' => 'picked_up',
                'shipped_at' => now(),
            ]);

            // Create initial shipping tracking record
            ShippingTracking::create([
                'order_id' => $order->id,
                'status' => 'picked_up',
                'description' => 'Paket telah diserahkan ke kurir ' . $order->kurir,
                'location' => 'Origin',
                'tracked_at' => now(),
            ]);

            DB::commit();

            Log::info('Manual Shipping Created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'resi' => $resi,
            ]);

            return redirect()->route('admin.shipping.show', $order->id)
                ->with('success', 'Nomor resi berhasil disimpan dan status pengiriman diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Input Resi Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update tracking manual oleh admin
     */
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered,returned',
            'description' => 'required|string|max:500',
            'location' => 'nullable|string|max:255',
        ]);

        if (!$order->resi) {
            return back()->with('error', 'Order belum memiliki nomor resi');
        }

        DB::beginTransaction();
        try {
            // Create tracking record
            ShippingTracking::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'description' => $request->description,
                'location' => $request->location,
                'tracked_at' => now(),
            ]);

            // Update order status pengiriman
            $order->update([
                'status_pengiriman' => $request->status,
            ]);

            // Jika delivered, update status order ke completed
            if ($request->status === 'delivered' && $order->status === 'shipped') {
                $order->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            DB::commit();

            Log::info('Manual Tracking Updated', [
                'order_id' => $order->id,
                'status' => $request->status,
            ]);

            return back()->with('success', 'Status tracking berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Update Tracking Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete tracking record (jika salah input)
     */
    public function deleteTracking(ShippingTracking $tracking)
    {
        try {
            $orderId = $tracking->order_id;
            $tracking->delete();

            Log::info('Tracking Deleted', [
                'tracking_id' => $tracking->id,
                'order_id' => $orderId,
            ]);

            return back()->with('success', 'Tracking berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Delete Tracking Error', [
                'tracking_id' => $tracking->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus tracking');
        }
    }

    /**
     * Get list provinces
     */
    public function getProvinces()
    {
        $provinces = $this->rajaOngkir->getProvinces();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->get('province_id');
        $cities = $this->rajaOngkir->getCities($provinceId);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Get districts by city
     */
    public function getDistricts(Request $request)
    {
        $cityId = $request->get('city_id');

        if (!$cityId) {
            return response()->json([
                'success' => false,
                'message' => 'City ID is required',
                'data' => []
            ], 400);
        }

        $districts = $this->rajaOngkir->getDistricts($cityId);

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Get subdistricts by district
     */
    public function getSubDistricts(Request $request)
    {
        $districtId = $request->get('district_id');

        if (!$districtId) {
            return response()->json([
                'success' => false,
                'message' => 'District ID is required',
                'data' => []
            ], 400);
        }

        $subdistricts = $this->rajaOngkir->getSubDistricts($districtId);

        return response()->json([
            'success' => true,
            'data' => $subdistricts
        ]);
    }

    /**
     * Search city by keyword
     */
    public function searchCity(Request $request)
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 3 karakter untuk pencarian',
                'data' => []
            ]);
        }

        $cities = $this->rajaOngkir->searchCity($keyword);

        return response()->json([
            'success' => true,
            'data' => array_values($cities)
        ]);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            // 'destination_subdistrict_id' => 'required|integer',
            'destination_city_id' => 'required|integer',
            'destination_district_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);

        $originCity = $this->rajaOngkir->getOriginCityId();
        $destinationCity = $request->destination_city_id;
        $weight = $request->weight;

        // Check if same city (dalam kota)
        if ($this->rajaOngkir->isSameCity($originCity, $destinationCity)) {
            return response()->json([
                'success' => true,
                'is_same_city' => true,
                'data' => [
                    [
                        'name' => 'Pengiriman Dalam Kota',
                        'code' => 'local',
                        'service' => 'FLAT',
                        'description' => 'Pengiriman Dalam Kota/Kabupaten Gorontalo',
                        'cost' => 6000,
                        'etd' => '1 hari',
                    ]
                ]
            ]);
        }

        $originDistrictId = $this->rajaOngkir->getGorontaloOriginDistrictId();
        $destinationDistrictId = $request->destination_district_id;

        // Calculate using RajaOngkir for antar kota
        $result = $this->rajaOngkir->calculateCost(
            $originDistrictId,
            $destinationDistrictId,
            $weight
        );

        return response()->json($result);
    }
}
