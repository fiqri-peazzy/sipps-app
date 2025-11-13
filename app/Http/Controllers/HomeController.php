<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSablon;

class HomeController extends Controller
{
    public function index()
    {
        $jenisSablons = JenisSablon::where('is_active', true)->get();

        return view('home', compact('jenisSablons'));
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

        // Simpan ke database atau kirim email
        // Untuk saat ini kita redirect dengan success message

        return redirect()->route('home')->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
