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

        $portfolios = \App\Models\Portfolio::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();
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

    public function layanan()
    {
        $jenisSablons = JenisSablon::where('is_active', true)
            ->withCount('produks')
            ->get();
        return view('frontend.layanan', compact('jenisSablons'));
    }

    public function portfolio()
    {
        // Use the same sample data for now
        $portfolios = \App\Models\Portfolio::where('is_active', true)
            ->latest()
            ->paginate(12);
        return view('frontend.portfolio', compact('portfolios'));
    }
}
