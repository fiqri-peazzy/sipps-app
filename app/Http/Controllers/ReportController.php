<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    /**
     * Render view untuk Laporan Pesanan
     * Logic ada di Livewire component
     */
    public function orders()
    {
        return view('admin.reports.orders');
    }

    public function dpsPerformance()
    {
        return view('admin.reports.dps-performance');
    }

    public function comparison()
    {
        return view('admin.reports.comparison');
    }

    public function dashboard()
    {
        return view('admin.reports.dashboard');
    }
}
