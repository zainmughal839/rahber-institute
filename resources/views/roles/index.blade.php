{{-- resources/views/roles/index.blade.php --}}
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
                <!-- Card Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-shield-lock me-2"></i>
                            All Roles
                            <span class="badge bg-light text-dark ms-2">
                                {{ $roles->total() }} Total
                            </span>
                        </h3>
                        <div class="card-tools">
                            @can('role.create')
                            <a href="{{ route('roles.create') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Create New Role
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th>Role Key</th>
                                    <th>Permissions</th>
                                    <th width="130" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $r)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}
                                    </td>
                                    <td>
                                        <code>{{ $r->name }}</code>
                                    </td>

                                    <td>
                                        @foreach($r->permissions as $p)
                                        <span
                                            class="badge bg-primary text-white me-1 mb-1">{{ $p->display_name ?? $p->name }}</span>
                                        @endforeach
                                        @if($r->permissions->isEmpty())
                                        <span class="text-muted">No permissions assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('role.update')
                                            <a href="{{ route('roles.edit', $r->id) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan
                                            @can('role.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $r->id }}" data-name="{{ $r->display_name ?? $r->name }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $r->id }}"
                                            action="{{ route('roles.destroy', $r->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-shield-x display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No roles found</h4>
                                        <p>Create your first role to get started.</p>
                                        <a href="{{ route('roles.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-circle"></i> Create First Role
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($roles->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $roles->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
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
            text: `Delete role "${name}"? This action cannot be undone!`,
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