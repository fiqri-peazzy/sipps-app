<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        $revenueThisMonth = Order::whereIn('status', ['paid', 'verified', 'in_production', 'ready_to_ship', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        $pendingVerification = Order::where('status', 'paid')->count();

        $totalReceivables = Order::where('status', 'pending')->sum('total_harga');

        return view('keuangan.dashboard', compact('revenueThisMonth', 'pendingVerification', 'totalReceivables'));
    }

    public function pembayaran(Request $request)
    {
        $query = Order::with('user')->where('status', 'paid');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest('paid_at')->paginate(20);
        return view('keuangan.pembayaran', compact('orders'));
    }

    public function detailPesanan($id)
    {
        // View detail pesanan (read-only for finance)
        return view('keuangan.detail-pesanan', compact('id'));
    }

    public function verifikasi(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Status pesanan tidak valid untuk verifikasi.');
        }

        $order->update(['status' => 'verified']);

        return back()->with('success', "Pesanan {$order->order_number} berhasil diverifikasi.");
    }

    public function laporan(Request $request)
    {
        $query = Order::with('user')->whereIn('status', ['verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']);

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
        }

        $orders = $query->latest()->paginate(50);
        return view('keuangan.laporan', compact('orders'));
    }

    public function exportExcel(Request $request)
    {
        $query = Order::with('user')->whereIn('status', ['verified', 'in_production', 'ready_to_ship', 'shipped', 'completed']);

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
        }

        $orders = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Pesanan');
        $sheet->setCellValue('C1', 'Pelanggan');
        $sheet->setCellValue('D1', 'Total Harga');
        $sheet->setCellValue('E1', 'Tanggal Bayar');
        $sheet->setCellValue('F1', 'Status');

        $row = 2;
        foreach ($orders as $index => $o) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $o->order_number);
            $sheet->setCellValue('C' . $row, $o->user->name ?? '-');
            $sheet->setCellValue('D' . $row, $o->total_harga);
            $sheet->setCellValue('E' . $row, $o->paid_at ? $o->paid_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('F' . $row, $o->status);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $filename = 'Laporan_Keuangan_' . now()->format('YmdHis') . '.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
