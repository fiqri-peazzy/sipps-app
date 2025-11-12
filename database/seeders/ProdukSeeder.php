<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSablon;
use App\Models\Ukuran;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $jenisSablons = [
            ['nama' => 'DTF (Direct to Film)', 'deskripsi' => 'Sablon dengan teknologi digital printing langsung ke film transfer. Hasil tajam, detail, dan tahan lama. Cocok untuk desain full color.'],
            ['nama' => 'Manual (Screen Printing)', 'deskripsi' => 'Sablon manual menggunakan screen/layar. Cocok untuk produksi massal dengan warna solid. Hasil awet dan tidak mudah luntur.'],
            ['nama' => 'Polyflex', 'deskripsi' => 'Menggunakan bahan polyflex yang dipotong sesuai desain lalu ditempel dengan heat press. Cocok untuk desain simpel seperti text dan logo.'],
            ['nama' => 'Sublim', 'deskripsi' => 'Teknik sablon untuk bahan polyester dengan tinta yang menyatu dengan serat kain. Hasil maksimal untuk desain full print pada kaos olahraga.'],
        ];

        foreach ($jenisSablons as $jenis) {
            JenisSablon::create($jenis);
        }

        $ukurans = [
            ['nama' => 'S'],
            ['nama' => 'M'],
            ['nama' => 'L'],
            ['nama' => 'XL'],
            ['nama' => 'XXL'],
            ['nama' => 'Custom'],
        ];

        foreach ($ukurans as $ukuran) {
            Ukuran::create($ukuran);
        }

        $jenisSablonData = JenisSablon::all();
        $ukuranData = Ukuran::all();

        foreach ($jenisSablonData as $jenis) {
            foreach ($ukuranData as $ukuran) {
                $baseHarga = match ($jenis->id) {
                    1 => 45000,
                    2 => 35000,
                    3 => 40000,
                    4 => 50000,
                    default => 40000,
                };

                $tambahanUkuran = match ($ukuran->nama) {
                    'S' => 0,
                    'M' => 5000,
                    'L' => 10000,
                    'XL' => 15000,
                    'XXL' => 20000,
                    'Custom' => 25000,
                    default => 0,
                };

                Produk::create([
                    'jenis_sablon_id' => $jenis->id,
                    'ukuran_id' => $ukuran->id,
                    'tipe_layanan' => 'regular',
                    'harga' => $baseHarga + $tambahanUkuran,
                    'estimasi_waktu' => 48,
                    'deskripsi' => 'Layanan regular dengan estimasi pengerjaan 2 hari kerja. Cocok untuk pesanan yang tidak terlalu mendesak dengan harga terjangkau.',
                ]);

                Produk::create([
                    'jenis_sablon_id' => $jenis->id,
                    'ukuran_id' => $ukuran->id,
                    'tipe_layanan' => 'express',
                    'harga' => ($baseHarga + $tambahanUkuran) * 1.5,
                    'estimasi_waktu' => 24,
                    'deskripsi' => 'Layanan express dengan estimasi pengerjaan 1 hari kerja. Prioritas lebih tinggi dalam antrian produksi untuk pesanan yang mendesak.',
                ]);
            }
        }
    }
}
