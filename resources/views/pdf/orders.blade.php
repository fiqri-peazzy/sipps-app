<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 20pt;
            color: #667eea;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 11pt;
            color: #666;
            margin-bottom: 3px;
        }

        .header .period {
            font-size: 10pt;
            color: #999;
            font-style: italic;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .info-section table {
            width: 100%;
        }

        .info-section td {
            padding: 3px 5px;
            font-size: 9pt;
        }

        .info-section td:first-child {
            font-weight: bold;
            width: 150px;
            color: #555;
        }

        /* Statistics Cards */
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .stat-card .label {
            font-size: 8pt;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 14pt;
            font-weight: bold;
            color: #667eea;
        }

        /* Breakdown Tables */
        .breakdown-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .breakdown-card {
            display: table-cell;
            width: 50%;
            padding: 0 5px;
        }

        .breakdown-card h3 {
            font-size: 11pt;
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
        }

        /* Tables */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table thead {
            background: #667eea;
            color: white;
        }

        table.data-table thead th {
            padding: 8px 5px;
            font-size: 9pt;
            text-align: left;
            font-weight: bold;
        }

        table.data-table tbody td {
            padding: 6px 5px;
            font-size: 8pt;
            border-bottom: 1px solid #dee2e6;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table.data-table tbody tr:hover {
            background: #e9ecef;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 7pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-primary {
            background: #007bff;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .badge-dark {
            background: #343a40;
            color: white;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Text Utilities */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6c757d;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .small {
            font-size: 8pt;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN PESANAN</h1>
            <div class="subtitle">Sistem Penjadwalan Pemesanan Sablon</div>
            <div class="period">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
            @if ($status)
                <div class="period">
                    Filter Status: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <table>
                <tr>
                    <td>Tanggal Cetak:</td>
                    <td>{{ now()->format('d M Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Total Data:</td>
                    <td>{{ $orders->count() }} pesanan</td>
                </tr>
            </table>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="label">Total Pesanan</div>
                <div class="value">{{ $stats['total_orders'] }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Item</div>
                <div class="value">{{ $stats['total_items'] }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Revenue</div>
                <div class="value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Rata-rata Order</div>
                <div class="value">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Breakdown Tables -->
        <div class="breakdown-container">
            <div class="breakdown-card">
                <h3>Breakdown Status Order</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusLabels = [
                                'pending_payment' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'verified' => 'Diverifikasi',
                                'in_production' => 'Sedang Produksi',
                                'ready_to_ship' => 'Siap Kirim',
                                'shipped' => 'Sedang Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'return_requested' => 'Ajuan Return',
                                'returned' => 'Dikembalikan',
                            ];
                        @endphp
                        @foreach ($stats['status_breakdown'] as $statusKey => $count)
                            <tr>
                                <td>{{ $statusLabels[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey)) }}</td>
                                <td class="text-right font-weight-bold">{{ $count }}</td>
                                <td class="text-right">
                                    {{ $stats['total_orders'] > 0 ? round(($count / $stats['total_orders']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="breakdown-card">
                <h3>Breakdown Status Pembayaran</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stats['payment_breakdown'] as $paymentStatus => $count)
                            <tr>
                                <td>{{ ucfirst($paymentStatus) }}</td>
                                <td class="text-right font-weight-bold">{{ $count }}</td>
                                <td class="text-right">
                                    {{ $stats['total_orders'] > 0 ? round(($count / $stats['total_orders']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Detail Orders Table -->
        <h3
            style="font-size: 12pt; color: #333; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 2px solid #667eea;">
            Detail Pesanan
        </h3>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">No. Order</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 20%;">Customer</th>
                    <th style="width: 8%;">Item</th>
                    <th style="width: 15%;">Total Harga</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 13%;">Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $order)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            {{ $order->penerima_nama }}<br>
                            <span class="text-muted small">{{ $order->user->email }}</span>
                        </td>
                        <td class="text-center">{{ $order->total_item }}</td>
                        <td class="text-right font-weight-bold">Rp
                            {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending_payment' => 'warning',
                                    'paid' => 'info',
                                    'verified' => 'primary',
                                    'in_production' => 'secondary',
                                    'ready_to_ship' => 'info',
                                    'shipped' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    'return_requested' => 'warning',
                                    'returned' => 'dark',
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            @php
                                $paymentColors = [
                                    'pending' => 'secondary',
                                    'settlement' => 'success',
                                    'capture' => 'success',
                                    'deny' => 'danger',
                                    'cancel' => 'danger',
                                    'expire' => 'warning',
                                    'failure' => 'danger',
                                ];
                            @endphp
                            <span class="badge badge-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div>Dokumen ini digenerate secara otomatis oleh sistem</div>
            <div>Dicetak pada: {{ now()->format('d M Y, H:i:s') }} WIB</div>
            <div style="margin-top: 5px;">Halaman 1 dari 1</div>
        </div>
    </div>
</body>

</html>
