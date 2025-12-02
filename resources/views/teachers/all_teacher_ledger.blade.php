{{-- resources/views/teachers/all_teacher_ledger.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <div class="card card-primary card-outline shadow-lg border-0">

        <!-- Card Header -->
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0"><i class="bi bi-journal-text me-2"></i> All Teachers Ledger</h3>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Teacher</th>
                            <th>CNIC</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Salary (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $t)
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('teachers.ledger', $t->id) }}" class="text-decoration-none">
                                    {{ $t->name }}
                                </a>
                            </td>
                            <td>{{ $t->cnic ?? '-' }}</td>
                            <td>{{ $t->email ?? '-' }}</td>
                            <td>{{ $t->phone ?? '-' }}</td>
                            <td class="text-end">
                                {{ number_format($t->latest_salary, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x display-1 d-block mb-3 opacity-50"></i>
                                <h4>No teachers found</h4>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
