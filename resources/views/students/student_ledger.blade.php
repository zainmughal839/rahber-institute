{{-- resources/views/students/student_ledger.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Student Info -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>
                Ledger for Student: {{ $student->name }}
            </h4>
        </div>
        <div class="card-body">
            <p>
                <strong>Father Name:</strong> {{ $student->father_name }} <br>
                <strong>Roll No:</strong> {{ $student->rollnum ?? 'Not assigned' }} <br>
                <strong>Phone:</strong> {{ $student->phone ?? '-' }} <br>
                <strong>Session - Program:</strong>
                {{ $student->sessionProgram->session->start_date ?? '' }} -
                {{ $student->sessionProgram->session->end_date ?? '' }} /
                {{ $student->sessionProgram->program->name ?? '' }}
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
                            <th>Debit (Rs.)</th>
                            <th>Credit (Rs.)</th>
                            <th>Balance (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $balance = 0;
                        @endphp
                        @forelse($student->ledgers()->orderBy('created_at')->get() as $ledger)
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
                                No ledger entries found for this student.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('students.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to Students
        </a>
    </div>
</div>
@endsection