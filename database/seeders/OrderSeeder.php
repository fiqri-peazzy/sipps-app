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
        $produks = Produk::all();
        if ($produks->isEmpty()) {
            $this->command->info('Please run ProdukSeeder first!');
            return;
        }

        // 1. Buat 5 Customer
        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'name' => "Customer {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
        }

        // 2. Data lokasi delivery
        $locations = [
            [
                'tipe' => 'dalam_kota',
                'provinsi' => 'Gorontalo',
                'province_id' => 7,
                'kota' => 'Gorontalo',
                'city_id' => 128,
                'kecamatan' => 'Kota Selatan',
                'kelurahan' => 'Limbah U1',
                'alamat' => 'Jl. Agus Salim No. 45',
                'kode_pos' => '96111',
                'kurir' => 'Kurir Lokal',
                'service' => 'Sameday',
                'ongkir' => 10000
            ],
            [
                'tipe' => 'antar_kota',
                'provinsi' => 'Sulawesi Selatan',
                'province_id' => 33,
                'kota' => 'Makassar',
                'city_id' => 254,
                'kecamatan' => 'Tamalanrea',
                'kelurahan' => 'Tamalanrea Indah',
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
                'kelurahan' => 'Airlangga',
                'alamat' => 'Jl. Dharmawangsa No. 123',
                'kode_pos' => '60286',
                'kurir' => 'jnt',
                'service' => 'EZ',
                'ongkir' => 60000
            ],
        ];

        // 3. Buat 10 Pesanan (PAID, BELUM DIVERIFIKASI)
        $orderNumbers = [];
        
        for ($o = 1; $o <= 10; $o++) {
            $customer = $customers[($o - 1) % count($customers)];
            $location = $locations[($o - 1) % count($locations)];
            $orderDate = Carbon::now()->subDays(rand(1, 5));
            $dateKey = $orderDate->format('Ymd');
            
            // Generate unique order number
            if (!isset($orderNumbers[$dateKey])) {
                $orderNumbers[$dateKey] = 0;
            }
            $orderNumbers[$dateKey]++;
            $orderNumber = 'ORD-' . $dateKey . '-' . str_pad($orderNumbers[$dateKey], 4, '0', STR_PAD_LEFT);

            // Buat Order (Status: PAID, tapi belum verified)
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $customer->id,
                'status' => 'paid',  // Sudah dibayar
                'subtotal' => 0, // Will update
                'ongkir' => $location['ongkir'],
                'total_harga' => 0, // Will update
                'total_item' => 0, // Will update
                'catatan' => "Pesanan ke-{$o} - Siap diverifikasi",
                'metode_pembayaran' => ['bank_transfer', 'qris', 'gopay'][rand(0, 2)],
                'snap_token' => 'snap_' . Str::random(20),
                'transaction_id' => 'txn_' . Str::random(20),
                'payment_status' => 'settlement',  // Pembayaran SETTLED
                'paid_at' => $orderDate->copy()->addHours(rand(1, 3)),
                'tipe_pengiriman' => $location['tipe'],
                'kurir' => $location['kurir'],
                'service_kurir' => $location['service'],
                'estimasi_pengiriman' => rand(2, 5),
                'penerima_nama' => $customer->name,
                'penerima_telepon' => $customer->phone,
                'alamat_lengkap' => $location['alamat'],
                'kelurahan' => $location['kelurahan'],
                'kecamatan' => $location['kecamatan'],
                'kota' => $location['kota'],
                'provinsi' => $location['provinsi'],
                'province_id' => $location['province_id'],
                'city_id' => $location['city_id'],
                'kota_id' => $location['city_id'],
                'provinsi_id' => $location['province_id'],
                'kode_pos' => $location['kode_pos'],
                'berat_total' => 0, // Will update
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // 4. Buat Order Items
            $itemCount = rand(1, 3);
            $totalSubtotal = 0;
            $totalItems = 0;
            $totalBerat = 0;

            for ($i = 0; $i < $itemCount; $i++) {
                $produk = $produks->random();
                $qty = rand(1, 6);
                $subtotalItem = $produk->harga * $qty;

                $totalSubtotal += $subtotalItem;
                $totalItems += $qty;
                $totalBerat += ($qty * 200);

                // Mock Design Config
                $designConfig = [
                    'front' => [
                        'active' => true,
                        'elements' => [
                            [
                                'type' => 'text',
                                'text' => 'ORDER ' . $orderNumber,
                                'left' => 100,
                                'top' => 150,
                                'fontSize' => 32,
                                'fontWeight' => 'bold',
                                'fill' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
                            ]
                        ]
                    ],
                    'back' => ['active' => false]
                ];

                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $produk->id,
                    'quantity' => $qty,
                    'ukuran_kaos' => ['S', 'M', 'L', 'XL', 'XXL'][rand(0, 4)],
                    'warna_kaos' => ['Hitam', 'Putih', 'Merah', 'Biru Navy', 'Hijau Army'][rand(0, 4)],
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotalItem,
                    'design_config' => $designConfig,
                    'catatan_item' => 'Pesanan siap produksi',
                    'production_status' => 'waiting',  // Menunggu verifikasi admin
                    'deadline' => $orderDate->copy()->addDays(rand(3, 7)),
                    'priority_score' => 0, // Akan dihitung saat diverifikasi
                ]);
            }

            // 5. Update total order
            $order->update([
                'subtotal' => $totalSubtotal,
                'total_harga' => $totalSubtotal + $location['ongkir'],
                'total_item' => $totalItems,
                'berat_total' => $totalBerat
            ]);

            $this->command->line("✅ Order {$orderNumber} created - {$totalItems} items");
        }

        $this->command->info("\n✅ 10 pesanan (PAID - BELUM DIVERIFIKASI) berhasil dibuat!");
    }
}
