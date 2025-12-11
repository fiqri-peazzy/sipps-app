<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perbandingan FCFS vs DPS</title>
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

        .comparison-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .method-card {
            display: table-cell;
            width: 50%;
            padding: 0 5px;
        }

        .method-card .card-header {
            padding: 10px;
            font-size: 11pt;
            font-weight: bold;
            color: white;
            text-align: center;
            margin-bottom: 10px;
        }

        .method-card.fcfs .card-header {
            background: #007bff;
        }

        .method-card.dps .card-header {
            background: #28a745;
        }

        .metrics-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .metric-item {
            display: table-cell;
            width: 50%;
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        .metric-item .label {
            font-size: 7pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .metric-item .value {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .summary-item .label {
            font-size: 7pt;
            color: #666;
        }

        .summary-item .value {
            font-size: 10pt;
            font-weight: bold;
        }

        .improvements-section {
            margin: 20px 0;
            padding: 15px;
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
        }

        .improvements-section h3 {
            font-size: 12pt;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .improvements-grid {
            display: table;
            width: 100%;
        }

        .improvement-item {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
        }

        .improvement-item .value {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }

        .improvement-item .value.positive {
            color: #28a745;
        }

        .improvement-item .value.negative {
            color: #dc3545;
        }

        .improvement-item .label {
            font-size: 8pt;
            color: #666;
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN PERBANDINGAN</h1>
            <div class="subtitle">FCFS vs DPS</div>
            <span class="badge">SKRIPSI</span>
            <div class="period">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <table>
                <tr>
                    <td>Tanggal Cetak:</td>
                    <td>{{ now()->format('d M Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Total Data Simulasi:</td>
                    <td>{{ $comparison['total_items'] }} item produksi</td>
                </tr>
                <tr>
                    <td>Metode Perbandingan:</td>
                    <td>FCFS (First Come First Serve) vs DPS (Dynamic Priority Scheduling)</td>
                </tr>
            </table>
        </div>

        <!-- Comparison Section -->
        <div class="comparison-container">
            <!-- FCFS Method -->
            <div class="method-card fcfs">
                <div class="card-header">FCFS (First Come First Serve)</div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">On-Time Rate</div>
                        <div class="value">{{ $comparison['fcfs']['on_time_rate'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Avg Completion</div>
                        <div class="value">{{ $comparison['fcfs']['avg_completion_time'] }} jam</div>
                    </div>
                </div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">Efficiency</div>
                        <div class="value">{{ $comparison['fcfs']['efficiency'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Avg Waiting</div>
                        <div class="value">{{ $comparison['fcfs']['avg_waiting_time'] }} jam</div>
                    </div>
                </div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">Late Rate</div>
                        <div class="value" style="color: #dc3545;">{{ $comparison['fcfs']['late_rate'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Throughput</div>
                        <div class="value">{{ $comparison['fcfs']['throughput'] }} item/hari</div>
                    </div>
                </div>

                <div class="summary-row">
                    <div class="summary-item">
                        <div class="label">Completed</div>
                        <div class="value">{{ $comparison['fcfs']['completed_items'] }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">On-Time</div>
                        <div class="value" style="color: #28a745;">{{ $comparison['fcfs']['on_time_items'] }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Late</div>
                        <div class="value" style="color: #dc3545;">{{ $comparison['fcfs']['late_items'] }}</div>
                    </div>
                </div>
            </div>

            <!-- DPS Method -->
            <div class="method-card dps">
                <div class="card-header">DPS (Dynamic Priority Scheduling)</div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">On-Time Rate</div>
                        <div class="value">{{ $comparison['dps']['on_time_rate'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Avg Completion</div>
                        <div class="value">{{ $comparison['dps']['avg_completion_time'] }} jam</div>
                    </div>
                </div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">Efficiency</div>
                        <div class="value">{{ $comparison['dps']['efficiency'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Avg Waiting</div>
                        <div class="value">{{ $comparison['dps']['avg_waiting_time'] }} jam</div>
                    </div>
                </div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="label">Late Rate</div>
                        <div class="value" style="color: #dc3545;">{{ $comparison['dps']['late_rate'] }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="label">Throughput</div>
                        <div class="value">{{ $comparison['dps']['throughput'] }} item/hari</div>
                    </div>
                </div>

                <div class="summary-row">
                    <div class="summary-item">
                        <div class="label">Completed</div>
                        <div class="value">{{ $comparison['dps']['completed_items'] }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">On-Time</div>
                        <div class="value" style="color: #28a745;">{{ $comparison['dps']['on_time_items'] }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Late</div>
                        <div class="value" style="color: #dc3545;">{{ $comparison['dps']['late_items'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improvements Section -->
        <div class="improvements-section">
            <h3>Peningkatan Performa (DPS vs FCFS)</h3>
            <div class="improvements-grid">
                <div class="improvement-item">
                    <div
                        class="value {{ $comparison['improvements']['on_time_rate'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $comparison['improvements']['on_time_rate'] >= 0 ? '+' : '' }}{{ $comparison['improvements']['on_time_rate'] }}%
                    </div>
                    <div class="label">On-Time Delivery Rate</div>
                </div>
                <div class="improvement-item">
                    <div
                        class="value {{ $comparison['improvements']['avg_completion_time'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $comparison['improvements']['avg_completion_time'] >= 0 ? '-' : '+' }}{{ abs($comparison['improvements']['avg_completion_time']) }}
                        jam
                    </div>
                    <div class="label">Completion Time (lebih cepat)</div>
                </div>
                <div class="improvement-item">
                    <div class="value {{ $comparison['improvements']['efficiency'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $comparison['improvements']['efficiency'] >= 0 ? '+' : '' }}{{ $comparison['improvements']['efficiency'] }}%
                    </div>
                    <div class="label">Efficiency Score</div>
                </div>
            </div>
        </div>

        <!-- Comparison Table -->
        <h3
            style="font-size: 12pt; color: #333; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 2px solid #667eea;">
            Perbandingan Detail Metrik
        </h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Metrik</th>
                    <th style="width: 30%;" class="text-center">FCFS</th>
                    <th style="width: 30%;" class="text-center">DPS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-weight-bold">On-Time Delivery Rate</td>
                    <td class="text-center">{{ $comparison['fcfs']['on_time_rate'] }}%</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['on_time_rate'] }}%
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Average Completion Time</td>
                    <td class="text-center">{{ $comparison['fcfs']['avg_completion_time'] }} jam</td>
                    <td class="text-center" style="background: #d4edda;">
                        {{ $comparison['dps']['avg_completion_time'] }} jam</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Efficiency Score</td>
                    <td class="text-center">{{ $comparison['fcfs']['efficiency'] }}%</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['efficiency'] }}%</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Average Waiting Time</td>
                    <td class="text-center">{{ $comparison['fcfs']['avg_waiting_time'] }} jam</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['avg_waiting_time'] }}
                        jam</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Late Delivery Rate</td>
                    <td class="text-center" style="color: #dc3545;">{{ $comparison['fcfs']['late_rate'] }}%</td>
                    <td class="text-center" style="background: #d4edda; color: #dc3545;">
                        {{ $comparison['dps']['late_rate'] }}%</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Throughput (item/hari)</td>
                    <td class="text-center">{{ $comparison['fcfs']['throughput'] }}</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['throughput'] }}</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Completed Items</td>
                    <td class="text-center">{{ $comparison['fcfs']['completed_items'] }}</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['completed_items'] }}
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">On-Time Items</td>
                    <td class="text-center">{{ $comparison['fcfs']['on_time_items'] }}</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['on_time_items'] }}
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Late Items</td>
                    <td class="text-center">{{ $comparison['fcfs']['late_items'] }}</td>
                    <td class="text-center" style="background: #d4edda;">{{ $comparison['dps']['late_items'] }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div>Laporan Perbandingan FCFS vs DPS - Dokumen untuk Keperluan Skripsi</div>
            <div>Dicetak pada: {{ now()->format('d M Y, H:i:s') }} WIB</div>
            <div style="margin-top: 5px;">Halaman 1 dari 1</div>
        </div>
    </div>
</body>

</html>
