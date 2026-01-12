@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                {{-- Header --}}
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-wallet2 me-2"></i> Teacher Salaries
                        </h3>
                        @can('teacher-salary.create')
                        <a href="{{ route('teacher-salaries.create') }}"
                           class="btn btn-outline-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Pay Salary
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Filters --}}
                <div class="card-body border-bottom py-3">
                    <form method="GET" action="{{ route('teacher-salaries.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <select name="teacher_id" class="form-select form-select-sm">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} - {{ $teacher->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="date" name="from_date"
                                   class="form-control form-control-sm"
                                   value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-3">
                            <input type="date" name="to_date"
                                   class="form-control form-control-sm"
                                   value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('teacher-salaries.index') }}"
                               class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Teacher</th>
                                    <th width="">Period</th>
                                    <th width="140">Amount</th>
                                    <th width="150">Date</th>
                                    <th width="200" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $index => $salary)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $salaries->firstItem() + $index }}
                                    </td>

                                    <td>
                                        <strong>{{ $salary->teacher->name }}</strong><br>
                                        <small class="text-muted">
                                             {{ $salary->teacher->email }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="badge bg-info-subtle text-dark px-3 py-2">
                                            {{ $salary->title }}
                                        </span>
                                    </td>

                                    <td class="fw-bold text-success">
                                        Rs. {{ number_format($salary->amount, 2) }}
                                    </td>

                                    <td>
                                        {{ $salary->created_at->format('d-m-Y') }}
                                    </td>

                                    

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('teacher-salaries.print', $salary->id) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            @can('teacher-salary.delete')
                                            <button class="btn btn-danger btn-sm delete-salary"
                                                    data-id="{{ $salary->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-wallet display-1 d-block mb-3"></i>
                                        <h4>No Salary Records Found</h4>
                                        <p>Try adjusting filters or pay a new salary.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="card-footer bg-light border-top">
                    {{ $salaries->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-salary').forEach(btn => {
    btn.addEventListener('click', function () {
        const salaryId = this.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "This salary voucher will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('teacher-salaries') }}/${salaryId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Salary voucher deleted successfully.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });
});
</script>




@endsection
