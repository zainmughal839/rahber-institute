{{-- resources/views/teachers/teacher_ledger.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Teacher Info -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>
                Ledger for Teacher: {{ $teacher->name }}
            </h4>
        </div>
        <div class="card-body">
            <p>
                <strong>Email:</strong> {{ $teacher->email ?? '-' }} <br>
                <strong>Phone:</strong> {{ $teacher->phone ?? '-' }} <br>
                <strong>Address:</strong> {{ $teacher->address ?? '-' }} <br>
                <strong>Salary:</strong>
                <strong> {{ number_format($teacher->salary, 2) }}</strong>
            </p>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Ledger Entries</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Ledger Category</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $balance = 0; @endphp
                        @forelse($ledgers as $ledger)
                            @php
                                if ($ledger->type === 'debit') {
                                    $balance += $ledger->amount;
                                } else {
                                    $balance -= $ledger->amount;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $ledger->created_at->format('d-M-Y') }}</td>
                                <td>{{ $ledger->description ?? '-' }}</td>
                                <td>{{ ucfirst($ledger->ledger_category ?? '-') }}</td>
                                <td class="text-end">
                                    {{ $ledger->type === 'debit' ? number_format($ledger->amount, 2) : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ $ledger->type === 'credit' ? number_format($ledger->amount, 2) : '-' }}
                                </td>
                                <td class="text-end">{{ number_format($balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    No ledger entries found for this teacher.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-3">
        <a href="{{ route('teachers.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to Teachers
        </a>
    </div>

</div>
@endsection
