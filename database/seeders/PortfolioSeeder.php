<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Sablon Kaos Komunitas Motor Gorontalo',
                'client_name' => 'Bikers Brotherhood GTLO',
                'description' => 'Proyek sablon kaos sebanyak 100 pcs untuk gathering tahunan komunitas motor. Menggunakan teknik plastisol dengan detail garis halus.',
                'material' => 'Cotton Combed 24s Black',
                'size' => 'A3 Front, A4 Back',
                'method' => 'Plastisol Premium',
                'is_featured' => true,
            ],
            [
                'title' => 'Kaos Event Lari "Gorontalo Run"',
                'client_name' => 'Pemerintah Kota Gorontalo',
                'description' => 'Produksi kaos olahraga jersey dryfit dengan sablon sublimasi full print untuk 500 peserta.',
                'material' => 'Jersey Dryfit Milano',
                'size' => 'Full Print',
                'method' => 'Sublimasi / FullPrint',
                'is_featured' => true,
            ],
            [
                'title' => 'Uniform Staff Kafe @KopiKita',
                'client_name' => 'Kafe Kopi Kita',
                'description' => 'Sablon kaos seragam staff kafe dengan logo minimalis di dada kirim dan tulisan besar di belakang.',
                'material' => 'Cotton Combed 30s Navy',
                'size' => 'Logo Front, A3 Back',
                'method' => 'Digital Transfer Film (DTF)',
                'is_featured' => false,
            ],
            [
                'title' => 'Kaos Reuni Akbar SMAN 1 Gorontalo',
                'client_name' => 'Alumni SMAN 1',
                'description' => 'Pemesanan kaos reuni lintas angkatan dengan variasi ukuran dari S hingga XXXL. Menggunakan tinta rubber yang elastis.',
                'material' => 'Cotton Combed 30s White',
                'size' => 'A3 Front',
                'method' => 'Rubber / Manual',
                'is_featured' => false,
            ],
            [
                'title' => 'Merchandise Limited Edition "Explore GTLO"',
                'client_name' => 'Dinas Pariwisata',
                'description' => 'Edisi terbatas kaos oleh-oleh khas Gorontalo dengan desain ilustrasi hiu paus.',
                'material' => 'Cotton Combed 30s Sand',
                'size' => 'A3 Front',
                'method' => 'Discharge (Cabut Warna)',
                'is_featured' => true,
            ],
        ];

        foreach ($data as $item) {
            Portfolio::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'client_name' => $item['client_name'],
                'description' => $item['description'],
                'material' => $item['material'],
                'size' => $item['size'],
                'method' => $item['method'],
                'is_featured' => $item['is_featured'],
                'is_active' => true,
                // image is empty for now
            ]);
        }
    }
}
