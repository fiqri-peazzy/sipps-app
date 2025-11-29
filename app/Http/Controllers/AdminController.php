<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function manageProduk()
    {
        return view('admin.manajemen-produk');
    }
    public function dataPesanan()
    {
        return view('admin.data-pesanan');
    }

    public function detailPesanan($id)
    {
        return view('admin.detail-pesanan-view', compact('id'));
    }

    public function penjadwalan()
    {
        return view('admin.penjadwalan-prioritas-view');
    }
}
