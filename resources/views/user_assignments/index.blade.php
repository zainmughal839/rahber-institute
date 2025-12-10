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
                            <i class="bi bi-link-45deg me-2"></i>
                            User Assignments
                            <span class="badge bg-light text-dark ms-2">
                                {{ $assignments->total() }} Total
                            </span>
                        </h3>

                        @can('user-assignment.create')
                        <a href="{{ route('user-assignments.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> New Assignment
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-3 border-bottom bg-light">
                    <form method="GET" class="row g-2">
                        <div class="col-md-3">
                            <select name="panel_type" class="form-control">
                                <option value="">-- All Panels --</option>
                                <option value="student" {{ request('panel_type')=='student' ? 'selected' : '' }}>Student
                                </option>
                                <option value="teacher" {{ request('panel_type')=='teacher' ? 'selected' : '' }}>Teacher
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <input name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search by user or email">
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th>User</th>
                                    <th>Panel</th>
                                    <th>Assigned To</th>
                                    <th>Email</th>
                                     @canany(['user-assignment.update', 'user-assignment.delete'])
                                    <th width="130" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($assignments as $a)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration + ($assignments->currentPage() - 1) * $assignments->perPage() }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 16px;">
                                                {{ Str::upper(substr($a->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $a->user->name ?? '-' }}</strong>
                                                <br>
                                                <span class="text-muted small">
                                                    ID: {{ $a->user_id }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($a->panel_type == 'student')
                                        <span class="badge bg-success">Student</span>
                                        @else
                                        <span class="badge bg-info">Teacher</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $a->assignable->name ?? '-' }}
                                    </td>

                                    <td>
                                        <a href="mailto:{{ $a->email }}" class="text-decoration-none">
                                            {{ $a->email }}
                                        </a>
                                    </td>

                                    @canany(['user-assignment.update', 'user-assignment.delete'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                         @can('user-assignment.update')
                                            <a href="{{ route('user-assignments.edit', $a->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                             @can('user-assignment.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $a->id }}" data-name="{{ $a->assignable->name ?? 'User' }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $a->id }}"
                                            action="{{ route('user-assignments.destroy', $a->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>
                                    @endcan
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-x-circle display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No Assignments Found</h4>
                                        <p>Create your first assignment to get started.</p>
                                        <a href="{{ route('user-assignments.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-circle"></i> Create Assignment
                                        </a>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($assignments->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $assignments->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

<!-- SweetAlert Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete assignment for "${name}"? This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>

@endsection