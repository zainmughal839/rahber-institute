@extends('layout.master')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
            <h4>Expense Voucher</h4>
            <strong>Voucher No:</strong> {{ $voucherNo }}
        </div>

        <div class="card-body">
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($entries->first()->voucher_date)->format('d-m-Y') }}</p>

            <table class="table table-bordered">
                <thead class="table-light">
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
                            <td class="text-end">Rs. {{ number_format($row->amount,0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">Rs. {{ number_format($total,0) }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center mt-4">
                <a href="{{ route('expenses.print', $voucherNo) }}" class="btn btn-primary">
                    Print
                </a>
                <a href="{{ route('expenses.pdf', $voucherNo) }}" class="btn btn-danger">
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
