<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan ada Admin untuk reviewer
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Reviewer',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // 2. Buat Customer (Dalam & Luar Kota)
        $customers = [];

        // Customer Gorontalo
        for ($i = 1; $i <= 10; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer_gto{$i}@example.com"],
                [
                    'name' => "Customer Gorontalo {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Customer Luar Kota
        for ($i = 1; $i <= 10; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer_luar{$i}@example.com"],
                [
                    'name' => "Customer Luar Kota {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '0821' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
        }

        $produks = Produk::all();
        if ($produks->isEmpty()) {
            $this->command->info('Please run ProdukSeeder first!');
            return;
        }

        $statuses = ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'];
        $locations = [
            // Dalam Kota (Gorontalo)
            [
                'tipe' => 'dalam_kota',
                'provinsi' => 'Gorontalo',
                'province_id' => 7,
                'kota' => 'Gorontalo',
                'city_id' => 128,
                'kecamatan' => 'Kota Selatan',
                'district_id' => 1709, // Mock ID
                'kelurahan' => 'Limba U1',
                'subdistrict_id' => 23412, // Mock ID
                'alamat' => 'Jl. Agus Salim No. ' . rand(1, 100),
                'kode_pos' => '96111',
                'kurir' => 'Kurir Lokal',
                'service' => 'Sameday',
                'ongkir' => 10000
            ],
            [
                'tipe' => 'dalam_kota',
                'provinsi' => 'Gorontalo',
                'province_id' => 7,
                'kota' => 'Gorontalo',
                'city_id' => 128,
                'kecamatan' => 'Sipatana',
                'district_id' => 1713, // Mock ID
                'kelurahan' => 'Tapa',
                'subdistrict_id' => 23450, // Mock ID
                'alamat' => 'Jl. Pangeran Hidayat No. ' . rand(1, 100),
                'kode_pos' => '96123',
                'kurir' => 'Kurir Lokal',
                'service' => 'Sameday',
                'ongkir' => 15000
            ],
            // Luar Kota
            [
                'tipe' => 'antar_kota',
                'provinsi' => 'Sulawesi Selatan',
                'province_id' => 33,
                'kota' => 'Makassar',
                'city_id' => 254,
                'kecamatan' => 'Tamalanrea',
                'district_id' => 3456,
                'kelurahan' => 'Tamalanrea Indah',
                'subdistrict_id' => 45678,
                'alamat' => 'Jl. Perintis Kemerdekaan KM 10',
                'kode_pos' => '90245',
                'kurir' => 'jne',
                'service' => 'REG',
                'ongkir' => 45000
            ],
            [
                'tipe' => 'antar_kota',
                'provinsi' => 'Jawa Timur',
                'province_id' => 11,
                'kota' => 'Surabaya',
                'city_id' => 444,
                'kecamatan' => 'Gubeng',
                'district_id' => 6789,
                'kelurahan' => 'Airlangga',
                'subdistrict_id' => 78901,
                'alamat' => 'Jl. Dharmawangsa No. ' . rand(1, 100),
                'kode_pos' => '60286',
                'kurir' => 'jnt',
                'service' => 'EZ',
                'ongkir' => 60000
            ],
            [
                'tipe' => 'antar_kota',
                'provinsi' => 'DKI Jakarta',
                'province_id' => 6,
                'kota' => 'Jakarta Barat',
                'city_id' => 151,
                'kecamatan' => 'Kebon Jeruk',
                'district_id' => 1234,
                'kelurahan' => 'sukabumi Utara',
                'subdistrict_id' => 56789,
                'alamat' => 'Perumahan Greenville Blok B No. ' . rand(1, 50),
                'kode_pos' => '11540',
                'kurir' => 'sicepat',
                'service' => 'REG',
                'ongkir' => 55000
            ]
        ];

        $orderCountsByDate = [];

        foreach ($customers as $customer) {
            // Setiap customer punya 1-3 order
            $orderCount = rand(1, 3);

            for ($k = 0; $k < $orderCount; $k++) {
                $location = $locations[array_rand($locations)];
                $status = $statuses[array_rand($statuses)];

                $orderDate = Carbon::now()->subDays(rand(1, 30));
                $dateKey = $orderDate->format('Ymd');

                // Track order count per date for unique order number
                if (!isset($orderCountsByDate[$dateKey])) {
                    // Check database if there are already orders for this date
                    $lastOrder = Order::whereDate('created_at', $orderDate)->latest()->first();
                    $orderCountsByDate[$dateKey] = $lastOrder ? (int) substr($lastOrder->order_number, -4) : 0;
                }
                $orderCountsByDate[$dateKey]++;
                $orderNumber = 'ORD-' . $dateKey . '-' . str_pad($orderCountsByDate[$dateKey], 4, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $customer->id,
                    'status' => $status,
                    'subtotal' => 0, // Akan diupdate
                    'ongkir' => $location['ongkir'],
                    'total_harga' => 0, // Akan diupdate
                    'total_item' => 0, // Akan diupdate
                    'catatan' => 'Pesanan sampel untuk testing DPS',
                    'metode_pembayaran' => rand(0, 1) ? 'bank_transfer' : 'qris',
                    'payment_status' => ($status == 'pending_payment') ? 'pending' : 'settlement',
                    'paid_at' => ($status != 'pending_payment') ? $orderDate->copy()->addHours(rand(1, 4)) : null,
                    'tipe_pengiriman' => $location['tipe'],
                    'kurir' => $location['kurir'],
                    'service_kurir' => $location['service'],
                    'estimasi_pengiriman' => rand(2, 5),
                    'status_pengiriman' => ($status == 'shipped') ? 'in_transit' : (($status == 'completed') ? 'delivered' : 'pending'),
                    'penerima_nama' => $customer->name,
                    'penerima_telepon' => $customer->phone,
                    'alamat_lengkap' => $location['alamat'],
                    'kelurahan' => $location['kelurahan'],
                    'kecamatan' => $location['kecamatan'],
                    'kota' => $location['kota'],
                    'provinsi' => $location['provinsi'],
                    'province_id' => $location['province_id'],
                    'city_id' => $location['city_id'],
                    'district_id' => $location['district_id'] ?? null,
                    'subdistrict_id' => $location['subdistrict_id'] ?? null,
                    'kota_id' => $location['city_id'],
                    'provinsi_id' => $location['province_id'],
                    'kode_pos' => $location['kode_pos'],
                    'berat_total' => 0, // Akan diupdate
                    'verified_at' => in_array($status, ['verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']) ? $orderDate->copy()->addHours(rand(5, 10)) : null,
                    'shipped_at' => in_array($status, ['shipped', 'completed']) ? $orderDate->copy()->addDays(rand(2, 4)) : null,
                    'completed_at' => ($status == 'completed') ? $orderDate->copy()->addDays(rand(5, 7)) : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                $itemCount = rand(1, 4);
                $totalSubtotal = 0;
                $totalItems = 0;
                $totalBerat = 0;

                for ($i = 0; $i < $itemCount; $i++) {
                    $produk = $produks->random();
                    $qty = rand(1, 12);
                    $subtotalItem = $produk->harga * $qty;

                    $totalSubtotal += $subtotalItem;
                    $totalItems += $qty;
                    $totalBerat += ($qty * 200); // Asumsi 200gr per kaos

                    // Mock Canvas Design Config
                    $designConfig = [
                        'front' => [
                            'active' => true,
                            'elements' => [
                                [
                                    'type' => 'image',
                                    'src' => 'https://ui-avatars.com/api/?name=Design+Front&background=random',
                                    'left' => rand(50, 150),
                                    'top' => rand(50, 150),
                                    'scaleX' => 0.5,
                                    'scaleY' => 0.5,
                                    'angle' => 0
                                ],
                                [
                                    'type' => 'text',
                                    'text' => 'Sample Logo ' . rand(1, 100),
                                    'left' => rand(100, 200),
                                    'top' => rand(200, 300),
                                    'fontSize' => 24,
                                    'fill' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
                                ]
                            ]
                        ],
                        'back' => rand(0, 1) ? [
                            'active' => true,
                            'elements' => [
                                [
                                    'type' => 'text',
                                    'text' => 'BACK DESIGN ' . rand(10, 99),
                                    'left' => 100,
                                    'top' => 100,
                                    'fontSize' => 40,
                                    'fontWeight' => 'bold',
                                    'fill' => '#000000'
                                ]
                            ]
                        ] : ['active' => false]
                    ];

                    $productionStatus = 'waiting';
                    if ($status == 'in_production') $productionStatus = 'in_progress';
                    if (in_array($status, ['ready_to_ship', 'shipped', 'completed'])) $productionStatus = 'completed';

                    OrderItem::create([
                        'order_id' => $order->id,
                        'produk_id' => $produk->id,
                        'quantity' => $qty,
                        'ukuran_kaos' => ['S', 'M', 'L', 'XL', 'XXL'][rand(0, 4)],
                        'warna_kaos' => ['Hitam', 'Putih', 'Merah', 'Biru Navy', 'Hijau Army'][rand(0, 4)],
                        'harga_satuan' => $produk->harga,
                        'subtotal' => $subtotalItem,
                        'design_config' => $designConfig,
                        'catatan_item' => 'Mock design for testing',
                        'production_status' => $productionStatus,
                        'deadline' => $orderDate->copy()->addHours($produk->estimasi_waktu + rand(0, 24)),
                        'priority_score' => rand(0, 100),
                    ]);
                }

                $order->update([
                    'subtotal' => $totalSubtotal,
                    'total_harga' => $totalSubtotal + $location['ongkir'],
                    'total_item' => $totalItems,
                    'berat_total' => $totalBerat
                ]);
            }
        }
    }
}
