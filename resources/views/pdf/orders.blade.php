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
                    <td>Total Produk:</td>
                    <td>{{ $items->count() }} item sablon</td>
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
            Daftar Parameter Prioritas Pesanan (Metode DPS)
        </h3>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 12%;">Kode Pesanan</th>
                    <th style="width: 10%;">Tgl Pesan</th>
                    <th style="width: 10%;">Deadline</th>
                    <th style="width: 5%; text-align: center;">T<sub>i</sub> (Hari)</th>
                    <th style="width: 8%; text-align: center;">U<sub>i</sub> (Jam)</th>
                    <th style="width: 8%; text-align: center;">C<sub>i</sub> (0-10)</th>
                    <th style="width: 8%; text-align: center;">W<sub>i</sub> (Jam)</th>
                    <th style="width: 8%; text-align: center;">Q<sub>i</sub> (Qty)</th>
                    <th style="width: 13%;">Item</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    @php
                        $factors = \App\Services\PriorityCalculator::getFactorsBreakdown($item);
                        $remainingDays = $item->deadline ? round(now()->diffInDays($item->deadline, false)) : 0;

                        // Map scores to 1-5 scale (Original scores are 0-100)
                        $uiScale = max(1, round(($factors['urgency']['raw_score'] ?? 0) / 20, 1));
                        $ciScale = max(1, round(($factors['complexity']['raw_score'] ?? 0) / 20, 1));
                        $wiScale = max(1, round(($factors['waiting_time']['raw_score'] ?? 0) / 20, 1));
                        $qiScale = max(1, round(($factors['quantity']['raw_score'] ?? 0) / 20, 1));
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $item->order->order_number }}</strong></td>
                        <td>{{ $item->order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->deadline ? $item->deadline->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            {{ (float) $factors['urgency']['remaining_days'] }}
                        </td>
                        <td class="text-center font-weight-bold">{{ (float) $factors['urgency']['remaining_hours'] }}
                            Jam</td>
                        <td class="text-center font-weight-bold">
                            {{ (float) $factors['complexity']['complexity_score_original'] }}</td>
                        <td class="text-center font-weight-bold">{{ (float) $factors['waiting_time']['waiting_hours'] }}
                            Jam</td>
                        <td class="text-center font-weight-bold">{{ $item->quantity }} Pcs</td>
                        <td>
                            <div style="font-weight: bold;">{{ $item->produk->jenisSablon->nama }}
                            </div>
                        </td>
                        <td>
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
                            {{ $statusLabels[$item->order->status] ?? $item->order->status }}
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
