<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h3 style="text-align:center">Expense Voucher</h3>

<p><strong>Voucher No:</strong> {{ $voucherNo }}</p>
<p><strong>Date:</strong> {{ \Carbon\Carbon::parse($entries->first()->voucher_date)->format('d-m-Y') }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Expense Head</th>
            <th>Description</th>
            <th align="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($entries as $i => $row)
            @php $total += $row->amount; @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->title }}</td>
                <td>{{ $row->description }}</td>
                <td align="right">{{ number_format($row->amount,0) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" align="right">Total</th>
            <th align="right">{{ number_format($total,0) }}</th>
        </tr>
    </tbody>
</table>

</body>
</html>
