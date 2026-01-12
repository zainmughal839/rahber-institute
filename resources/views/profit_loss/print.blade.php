<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit & Loss Report</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            margin: 0; 
            font-size: 12px;
            color: #212529;
        }

        .container {
            width: 100%;
            padding: 10px 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header img {
            max-height: 70px;
            margin-bottom: 5px;
        }

        .header h1, .header h3 {
            margin: 2px 0;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-muted { color: #6c757d; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table th, table td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
        }

        table td {
            vertical-align: middle;
        }

        .summary-row {
            margin-bottom: 20px;
        }

        .summary-card {
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 4px;
        }

        .summary-card h4 {
            font-size: 13px;
            margin-bottom: 4px;
        }

        .summary-card h3 {
            font-size: 16px;
            margin-bottom: 0;
        }

        small {
            font-size: 10px;
            color: #6c757d;
        }

        @media print {
            body { margin: 0; }
            .container { padding: 0; }
            .no-print { display: none; }
            table th, table td { border: 1px solid #000; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">

        <!-- Company Header -->
       <!-- Company Header -->
@php
    $settings = \App\Models\Setting::first();
    $logoPath = $settings?->invoice_logo ? asset('storage/' . $settings->invoice_logo) : asset('assets/img/logo.png');
@endphp

<div class="header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    <!-- Logo Left -->
    <div style="flex: 0 0 auto;">
        <img src="{{ $logoPath }}" alt="Logo" style="max-height: 70px;">
    </div>

    <!-- Company Details Right -->
    <div style="flex: 1; text-align: right; padding-left: 15px;">
        @if($settings)
            <h1 style="margin: 0; font-size: 20px;">{{ $settings->company_name }}</h1>
            <h3 style="margin: 2px 0; font-size: 14px; font-weight: normal; color: #6c757d;">{{ $settings->address }}</h3>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">
                Phone: {{ $settings->phone }} | Email: {{ $settings->email }}
            </p>
        @else
            <h1 style="margin: 0; font-size: 20px;">Company Name</h1>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">Company Address, Phone, Email</p>
        @endif
    </div>
</div>

<hr style="border: 1px solid #dee2e6; margin: 10px 0;">

        <!-- Report Title -->
        <div class="text-center mb-3">
            <h2>Profit & Loss Report</h2>
            @if($fromDate || $toDate)
            <p class="text-muted">
                Period: {{ $fromDate ?? 'Beginning' }} to {{ $toDate ?? 'Now' }}
            </p>
            @endif
        </div>

        <!-- Summary -->
        <div class="row summary-row" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <div class="summary-card text-center" style="flex: 1; margin-right: 5px;">
                <h4>Total Income</h4>
                <h3 class="text-success">Rs. {{ number_format($income, 0) }}</h3>
            </div>
            <div class="summary-card text-center" style="flex: 1; margin: 0 5px;">
                <h4>Total Expenses</h4>
                <h3 class="text-danger">Rs. {{ number_format($expenses, 0) }}</h3>
            </div>
            <div class="summary-card text-center" style="flex: 1; margin-left: 5px;">
                <h4>Net {{ $profitLoss >= 0 ? 'Profit' : 'Loss' }}</h4>
                <h3 class="{{ $profitLoss >= 0 ? 'text-success' : 'text-danger' }}">
                    Rs. {{ number_format(abs($profitLoss), 0) }}
                </h3>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Voucher / Challan / Student</th>
                    <th>Title / Description</th>
                    <th width="90">Date</th>
                    <th width="90" class="text-end">Amount</th>
                    <th width="70">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $i => $entry)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>
                        @if($entry->voucher_no ?? $entry->challan_no)
                            {{ $entry->voucher_no ?? $entry->challan_no }}
                        @elseif($entry->student)
                            {{ $entry->student->name }}<br>
                            <small>Roll #: {{ $entry->student->rollnum }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        {{ $entry->title }}
                        @if($entry->description)<br><small>{{ $entry->description }}</small>@endif
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($entry->voucher_date ?? $entry->created_at)->format('d-m-Y') }}</td>
                    <td class="text-end {{ $entry->type === 'credit' ? 'text-success' : 'text-danger' }}">
                        Rs. {{ number_format($entry->amount, 0) }}
                    </td>
                    <td class="text-center">
                        {{ $entry->type === 'credit' ? 'Income' : 'Expense' }}
                    </td>
                </tr>
                @endforeach
                @if($entries->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">No entries found for this period.</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Footer -->
        <div class="text-center mt-4 text-muted">
            Printed on: {{ now()->format('d-m-Y h:i A') }}
        </div>
    </div>
</body>
</html>
