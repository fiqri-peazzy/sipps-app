<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSablon;
use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        $jenisSablons = JenisSablon::where('is_active', true)
            ->withCount('produks')
            ->get();

        $produks = Produk::with(['jenisSablon', 'ukuran'])
            ->where('is_active', true)
            ->where('tipe_layanan', 'regular')
            ->take(8)
            ->get();

        $portfolios = [
            [
                'image' => 'https://images.unsplash.com/photo-1618354691714-7d92150909db?q=80&w=1167&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Sablon DTF Jersey Tim Futsal',
                'category' => 'DTF',
                'description' => 'Sablon jersey tim futsal dengan teknik DTF hasil tajam dan detail'
            ],
            [
                'image' => 'https://plus.unsplash.com/premium_photo-1747643596473-36555168062f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Sablon Manual Kaos Event',
                'category' => 'Manual',
                'description' => 'Sablon manual untuk kaos event dengan hasil awet dan tidak mudah luntur'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1704138161405-b4164c58f213?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Polyflex Logo Perusahaan',
                'category' => 'Polyflex',
                'description' => 'Sablon polyflex untuk seragam kantor dengan logo perusahaan'
            ],
            [
                'image' => 'https://media.istockphoto.com/id/1140961205/id/vektor/templat-desain-kaos-e-sport-tata-letak-biru-putih-dan-merah.jpg?s=2048x2048&w=is&k=20&c=nV95E6nQ1b1qvEOqAWg8pNnZSGqzI5XZ6lbCJaw3G8I=',
                'title' => 'Sublim Kaos Olahraga',
                'category' => 'Sublim',
                'description' => 'Sablon sublim full print untuk kaos olahraga dengan warna vibrant'
            ],
        ];
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'avatar' => 'avatar-1.jpg',
                'rating' => 5,
                'text' => 'Pelayanan cepat dan hasil sablon sangat memuaskan! Recommended untuk yang butuh sablon berkualitas.',
                'date' => '2 minggu lalu'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'avatar' => 'avatar-2.jpg',
                'rating' => 5,
                'text' => 'Sudah langganan di NNClothing, hasilnya selalu bagus dan harga terjangkau. Tim juga responsif!',
                'date' => '1 bulan lalu'
            ],
            [
                'name' => 'Ahmad Rizki',
                'avatar' => 'avatar-3.jpg',
                'rating' => 5,
                'text' => 'Puas banget! Pesan sablon express, hasilnya cepat dan sesuai ekspektasi. Terima kasih NNClothing!',
                'date' => '3 minggu lalu'
            ],
        ];

        return view('home', compact('jenisSablons', 'produks', 'portfolios', 'testimonials'));
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }

    public function show($id)
    {
        $jenisSablon = JenisSablon::with(['produks.ukuran'])
            ->findOrFail($id);

        // Group products by ukuran
        $groupedProducts = $jenisSablon->produks->groupBy('ukuran_id');

        $priceTable = [];
        foreach ($groupedProducts as $ukuranId => $products) {
            $ukuran = $products->first()->ukuran;
            $regular = $products->where('tipe_layanan', 'regular')->first();
            $express = $products->where('tipe_layanan', 'express')->first();

            $priceTable[] = [
                'ukuran' => $ukuran->nama,
                'regular' => $regular ? 'Rp ' . number_format($regular->harga, 0, ',', '.') : '-',
                'express' => $express ? 'Rp ' . number_format($express->harga, 0, ',', '.') : '-',
                'regular_id' => $regular?->id,
                'express_id' => $express?->id,
            ];
        }

        return response()->json([
            'id' => $jenisSablon->id,
            'nama' => $jenisSablon->nama,
            'deskripsi' => $jenisSablon->deskripsi,
            'priceTable' => $priceTable,
        ]);
    }
}
