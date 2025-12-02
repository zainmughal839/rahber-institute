{{-- resources/views/teachers/index.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card card-primary card-outline shadow-lg border-0">

        <!-- Card Header -->
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0"><i class="bi bi-person-lines-fill me-2"></i> Teachers</h3>
                <div class="card-tools">
                    <a href="{{ route('teachers.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Teacher
                    </a>
                </div>
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
                            <th>Address</th>
                             @canany(['teacher.update', 'teacher.delete'])
                            <th width="130" class="text-center">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $t)
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration + ($data->currentPage()-1) * $data->perPage() }}</td>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->cnic ?? '-' }}</td>
                            <td>{{ $t->email ?? '-' }}</td>
                            <td><small class="text-muted">{{ Str::limit($t->address ?? '-', 60) }}</small></td>
                             @canany(['teacher.update', 'teacher.delete'])
                            <td class="text-center">
                                
                                <div class="btn-group" role="group">
                                    @can('teacher.index')
<a href="{{ route('teachers.show', $t->id) }}" class="btn btn-info btn-sm" title="View">
    <i class="bi bi-eye"></i>
</a>
@endcan

                                     @can('teacher.update')
                                    <a href="{{ route('teachers.edit', $t->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan
                                     @can('teacher.delete')
                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                        data-id="{{ $t->id }}" data-name="{{ $t->name }}" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endcan
                                </div>

                                <!-- Hidden Delete Form -->
                                <form id="delete-form-{{ $t->id }}" action="{{ route('teachers.destroy', $t->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x display-1 d-block mb-3 opacity-50"></i>
                                <h4>No teachers found</h4>
                                <p>Add your first teacher to get started.</p>
                                <a href="{{ route('teachers.create') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle"></i> Add Teacher
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-light border-top">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete teacher "${name}"? This action cannot be undone!`,
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
