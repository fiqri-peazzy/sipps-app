<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSablon;
use App\Models\Ukuran;
use App\Models\Produk;

class ProdukReSeeder extends Seeder
{
    public function run(): void
    {
        // Reset tabel
        // Produk::truncate();

        // Ukuran::truncate();
        // JenisSablon::truncate();

        // Jenis sablon
        $jenisSablons = [
            ['nama' => 'DTF', 'deskripsi' => 'Teknologi digital transfer full color.', 'is_active' => 1],
            ['nama' => 'Manual', 'deskripsi' => 'Screen printing warna solid.', 'is_active' => 1],
            ['nama' => 'Polyflex', 'deskripsi' => 'Cutting polyflex untuk teks/logo sederhana.', 'is_active' => 1],
            ['nama' => 'Sublim', 'deskripsi' => 'Sablon sublimasi untuk bahan polyester.', 'is_active' => 1],
        ];

        foreach ($jenisSablons as $jenis) {
            JenisSablon::create($jenis);
        }

        // Ukuran sablon sesuai gambar (nama saja)
        $ukuranSablon = [
            'Small Area (8cm × 8cm)',
            'A6 (14.8cm × 10.5cm)',
            'A5 (21cm × 14.8cm)',
            'A4 (21cm × 29.7cm)',
            'A3 (29.7cm × 42cm)',
            'A3+ (32.9cm × 48.3cm)',
            'Custom Size',
        ];

        foreach ($ukuranSablon as $uk) {
            Ukuran::create([
                'nama' => $uk,
                'is_active' => 1,
            ]);
        }

        $jenisData = JenisSablon::all();
        $ukuranData = Ukuran::all();

        // Harga tambahan berdasarkan ukuran (tanpa ubah struktur tabel!)
        $hargaTambahan = [
            'Small Area' => 0,
            'A6' => 10000,
            'A5' => 20000,
            'A4' => 30000,
            'A3' => 50000,
            'A3+' => 75000,
        ];

        // Harga dasar jenis sablon
        $hargaJenis = [
            'DTF' => 45000,
            'Manual' => 30000,
            'Polyflex' => 35000,
            'Sublim' => 40000,
        ];

        foreach ($jenisData as $jenis) {
            foreach ($ukuranData as $uk) {

                // ambil key ukuran dari nama
                $key = explode(' ', $uk->nama)[0]; // Small, A6, A5, A4, A3

                // fallback untuk nama "Small Area"
                if ($key === 'Small') {
                    $key = 'Small Area';
                }

                $base = $hargaJenis[$jenis->nama] ?? 30000;
                $add  = $hargaTambahan[$key] ?? 0;

                // REGULAR
                Produk::create([
                    'jenis_sablon_id' => $jenis->id,
                    'ukuran_id' => $uk->id,
                    'tipe_layanan' => 'regular',
                    'harga' => $base + $add,
                    'estimasi_waktu' => 48,
                    'deskripsi' => "Sablon ukuran {$uk->nama} untuk tipe regular.",
                    'is_active' => 1,
                ]);

                // EXPRESS
                Produk::create([
                    'jenis_sablon_id' => $jenis->id,
                    'ukuran_id' => $uk->id,
                    'tipe_layanan' => 'express',
                    'harga' => ($base + $add) * 1.5,
                    'estimasi_waktu' => 24,
                    'deskripsi' => "Sablon ukuran {$uk->nama} untuk layanan express.",
                    'is_active' => 1,
                ]);
            }
        }
    }
}
