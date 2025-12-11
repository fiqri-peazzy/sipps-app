<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kinerja DPS</title>
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #28a745;
            color: white;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

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

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .small {
            font-size: 8pt;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge-small {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7pt;
            font-weight: bold;
            border-radius: 2px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-primary {
            background: #007bff;
            color: white;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }

        .metrics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .metric-item {
            display: table-cell;
            width: 25%;
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .metric-item .label {
            font-size: 7pt;
            color: #666;
            text-transform: uppercase;
        }

        .metric-item .value {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN KINERJA DPS</h1>
            <span class="badge">SKRIPSI</span>
            <div class="subtitle">Dynamic Priority Scheduling Performance Report</div>
            <div class="period">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
            @if ($productionStatus)
                <div class="period">
                    Filter Status: <strong>{{ ucfirst(str_replace('_', ' ', $productionStatus)) }}</strong>
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
                    <td>{{ $items->count() }} item produksi</td>
                </tr>
                <tr>
                    <td>Metode:</td>
                    <td>Dynamic Priority Scheduling (DPS)</td>
                </tr>
            </table>
        </div>

        <!-- Key Metrics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="label">Total Item</div>
                <div class="value">{{ $stats['total_items'] }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Avg Waiting Time</div>
                <div class="value">{{ $stats['avg_waiting_time'] }} jam</div>
            </div>
            <div class="stat-card">
                <div class="label">On-Time Rate</div>
                <div class="value">{{ $stats['on_time_rate'] }}%</div>
            </div>
            <div class="stat-card">
                <div class="label">Avg Priority</div>
                <div class="value">{{ $stats['avg_priority_score'] }}</div>
            </div>
        </div>

        <!-- Additional Metrics -->
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="label">Avg Complexity</div>
                <div class="value">{{ $stats['avg_complexity_score'] }}</div>
            </div>
            <div class="metric-item">
                <div class="label">Recalculations</div>
                <div class="value">{{ $stats['total_recalculations'] }}</div>
            </div>
            <div class="metric-item">
                <div class="label">Completed</div>
                <div class="value">{{ $stats['completed_items'] }}</div>
            </div>
            <div class="metric-item">
                <div class="label">On-Time Items</div>
                <div class="value">{{ $stats['on_time_items'] }}</div>
            </div>
        </div>

        <!-- Priority Distribution & Status Breakdown -->
        <div class="breakdown-container">
            <div class="breakdown-card">
                <h3>Distribusi Prioritas</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Range</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $priorityLabels = [
                                'very_low' => ['label' => 'Sangat Rendah', 'range' => '0-20'],
                                'low' => ['label' => 'Rendah', 'range' => '21-40'],
                                'medium' => ['label' => 'Menengah', 'range' => '41-60'],
                                'high' => ['label' => 'Tinggi', 'range' => '61-80'],
                                'very_high' => ['label' => 'Sangat Tinggi', 'range' => '81-100'],
                            ];
                        @endphp
                        @foreach ($stats['priority_distribution'] as $key => $count)
                            <tr>
                                <td>{{ $priorityLabels[$key]['label'] }}</td>
                                <td>{{ $priorityLabels[$key]['range'] }}</td>
                                <td class="text-right font-weight-bold">{{ $count }}</td>
                                <td class="text-right">
                                    {{ $stats['total_items'] > 0 ? round(($count / $stats['total_items']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="breakdown-card">
                <h3>Status Produksi</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusLabels = [
                                'waiting' => 'Menunggu',
                                'in_queue' => 'Antrian',
                                'in_progress' => 'Dikerjakan',
                                'completed' => 'Selesai',
                            ];
                        @endphp
                        @foreach ($stats['status_breakdown'] as $status => $count)
                            <tr>
                                <td>{{ $statusLabels[$status] ?? ucfirst($status) }}</td>
                                <td class="text-right font-weight-bold">{{ $count }}</td>
                                <td class="text-right">
                                    {{ $stats['total_items'] > 0 ? round(($count / $stats['total_items']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Detail Items Table -->
        <h3
            style="font-size: 12pt; color: #333; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 2px solid #667eea;">
            Detail Item Produksi
        </h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Order</th>
                    <th style="width: 20%;">Produk</th>
                    <th style="width: 7%;">Qty</th>
                    <th style="width: 10%;">Priority</th>
                    <th style="width: 10%;">Complexity</th>
                    <th style="width: 10%;">Wait Time</th>
                    <th style="width: 13%;">Status</th>
                    <th style="width: 10%;">Deadline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->order->order_number }}</strong><br>
                            <span class="text-muted small">{{ $item->order->created_at->format('d M Y') }}</span>
                        </td>
                        <td>{{ $item->produk->nama ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">
                            @php
                                $priorityColor = 'secondary';
                                if ($item->priority_score >= 80) {
                                    $priorityColor = 'danger';
                                } elseif ($item->priority_score >= 60) {
                                    $priorityColor = 'warning';
                                } elseif ($item->priority_score >= 40) {
                                    $priorityColor = 'info';
                                }
                            @endphp
                            <span class="badge-small badge-{{ $priorityColor }}">{{ $item->priority_score }}</span>
                        </td>
                        <td class="text-center">{{ $item->complexity_score }}</td>
                        <td class="text-center">{{ $item->waiting_time_hours }} jam</td>
                        <td>
                            @php
                                $statusColors = [
                                    'waiting' => 'secondary',
                                    'in_queue' => 'info',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                ];
                                $statusLabels = [
                                    'waiting' => 'Menunggu',
                                    'in_queue' => 'Antrian',
                                    'in_progress' => 'Dikerjakan',
                                    'completed' => 'Selesai',
                                ];
                            @endphp
                            <span
                                class="badge-small badge-{{ $statusColors[$item->production_status] ?? 'secondary' }}">
                                {{ $statusLabels[$item->production_status] ?? ucfirst($item->production_status) }}
                            </span>
                        </td>
                        <td class="text-center small">
                            @if ($item->deadline)
                                {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div>Laporan Kinerja DPS - Dokumen untuk Keperluan Skripsi</div>
            <div>Dicetak pada: {{ now()->format('d M Y, H:i:s') }} WIB</div>
            <div style="margin-top: 5px;">Halaman 1 dari 1</div>
        </div>
    </div>
</body>

</html>
