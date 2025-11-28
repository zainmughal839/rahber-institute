{{-- resources/views/students/index.blade.php --}}
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
                            <i class="bi bi-people-fill me-2"></i>
                            All Students
                        </h3>

                        <div class="card-tools">
                            @can('student.create')
                            <a href="{{ route('students.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Student
                            </a>
                            @endcan

                            @if(isset($showAll))
                            <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('students.index', ['all' => '1']) }}"
                                class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> View All Records
                            </a>
                            @endif
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
                                    <!-- <th width="200">Father Name</th> -->
                                    <th width="200">Roll num</th>
                                    <th width="150">Phone</th>
                                    <th width="150">Fee</th>
                                    <th width="220">Session - Program</th>
                                    @canany(['student.update', 'student.delete'])
                                    <th width="120" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $s)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>{{ $s->name }}</td>
                                    <!-- <td>{{ $s->father_name }}</td> -->
                                    <td>{{ $s->rollnum }}</td>

                                    <td>
                                        <small class="text-muted">{{ $s->phone ?? '-' }}</small>
                                    </td>

                                    <td>
                                        <span class="badge bg-success px-3">
                                            Rs. {{ number_format($s->fees) }}
                                        </span>
                                    </td>

                                    <td>
                                        @php
                                        $sp = $s->sessionProgram;
                                        @endphp

                                        <small class="text-muted">
                                            {{ $sp->session->start_date ?? '' }} -
                                            {{ $sp->session->end_date ?? '' }}
                                            /
                                            {{ $sp->program->name ?? '' }}
                                        </small>
                                    </td>

                                    @canany(['student.update', 'student.delete'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('student.update')
                                            <a href="{{ route('students.edit', $s->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan
                                            @can('student.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $s->id }}" data-name="{{ $s->name }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $s->id }}"
                                            action="{{ route('students.destroy', $s->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-1 d-block mb-3"></i>
                                        <h4>No students found</h4>
                                        <p>Add student records to manage enrollment.</p>
                                        <a href="{{ route('students.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Student
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if (!isset($showAll) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer bg-light border-top">
                    {{ $data->links('pagination::bootstrap-5') }}
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
            text: `Delete student "${name}"? This cannot be undone!`,
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