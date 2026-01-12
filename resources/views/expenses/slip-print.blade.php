<!DOCTYPE html>
<html>
<head>
    <title>Expense Voucher {{ $voucherNo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container-fluid mt-4">
    <h3 class="text-center">Expense Voucher</h3>
    <p><strong>Voucher No:</strong> {{ $voucherNo }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($entries->first()->voucher_date)->format('d-m-Y') }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Expense Head</th>
                <th>Description</th>
                <th class="text-end">Amount</th>
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
                    <td class="text-end">{{ number_format($row->amount,0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th class="text-end">{{ number_format($total,0) }}</th>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
