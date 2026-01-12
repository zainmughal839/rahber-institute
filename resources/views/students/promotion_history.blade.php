@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between">
            <div>
                <h4 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Promotion History
                </h4>
                <small class="text-white-75">Student: {{ $student->name }} 
                    {{ $student->rollnum ? '('. $student->rollnum .')' : '' }}
                </small>
            </div>
            <div>
                <a href="{{ route('students.promote.select') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Students
                </a>
            </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="22%">Promoted At</th>
                            <th width="25%">From Class</th>
                            <th width="25%">To Class</th>
                            <th>Description / Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                        <tr>
                            <td>
                                <div class="fw-medium">
                                    {{ $history->promoted_at ? $history->promoted_at->format('d M Y') : 'N/A' }}
                                </div>
                                <small class="text-muted">
                                    {{ $history->promoted_at ? $history->promoted_at->format('h:i A') : '' }}
                                </small>
                            </td>
                            <td>
                                {{ $history->oldClassSubject?->class_name ?? '<span class="text-muted">Not Set</span>' }}
                            </td>
                            <td class="fw-medium text-primary">
                                {{ $history->newClassSubject?->class_name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $history->description ?? '<span class="text-muted fst-italic">No description provided</span>' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                                    <p class="mb-1">No promotion history found for this student yet.</p>
                                    <small>Student hasn't been promoted to any new class.</small>
                                </div>
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