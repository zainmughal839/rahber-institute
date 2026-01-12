{{-- resources/views/students/all_student_ledger.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-file-bar-graph me-2"></i>
                            All Students Ledger Summary
                        </h3>

                        <div class="card-tools">
                            <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Students
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th width="200">Student</th>
                                    <th width="200">Roll num</th>
                                    <th width="150">Phone</th>
                                    <th width="220">Session - Program</th>
                                    <th width="150" class="text-center">Now Balance</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $s)

                                @php
                                $total = $s->ledgers->where('ledger_category','total_fee')->sum('amount');
                                $advance = $s->ledgers->where('ledger_category','advance')->sum('amount');
                                $balance = $total - $advance;
                                @endphp

                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>
                                        <a href="{{ route('students.ledger', $s->id) }}"
                                        class="text-success fw-bold text-decoration-none">
                                            {{ $s->name }}
                                        </a>
                                    </td>

                                    <td>{{ $s->rollnum }}</td>

                                    <td>
                                        <small class="text-muted">{{ $s->phone ?? '-' }}</small>
                                    </td>

                                    <td>
                                        @php $sp = $s->sessionProgram; @endphp
                                        <small class="text-muted">
                                            {{ $sp->session->start_date ?? '' }} -
                                            {{ $sp->session->end_date ?? '' }}
                                            /
                                            {{ $sp->program->name ?? '' }}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        @if($balance > 0)
                                        <span class="badge bg-danger px-3">
                                            Rs. {{ number_format($balance) }}
                                        </span>
                                        @else
                                        <span class="badge bg-success px-3">
                                            Rs. {{ number_format($balance) }}
                                        </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('students.ledger', $s->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-journal-text"></i> Ledger
                                        </a>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal display-1 d-block mb-3"></i>
                                        <h4>No ledger records found</h4>
                                        <p>Ledger summary will appear after students are added.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination (if you use it) -->
                @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer bg-light border-top">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection