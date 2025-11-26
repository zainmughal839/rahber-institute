{{-- resources/views/users/index.blade.php --}}
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
                            <i class="bi bi-people me-2"></i>
                            All Users
                            <span class="badge bg-light text-dark ms-2">
                                {{ $users->total() }} Total
                            </span>
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-person-plus me-1"></i> Add New User
                            </a>
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined On</th>
                                    <th width="130" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 16px;">
                                                {{ Str::upper(substr($u->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $u->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $u->email }}" class="text-decoration-none">
                                            {{ $u->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($u->is_admin)
                                        <span class="badge bg-danger">Admin</span>
                                        @else
                                        <span class="badge bg-success">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $u->created_at?->format('d M, Y') ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if(auth()->id() != $u->id)
                                            <!-- Apne aap ko delete na kar sake -->
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $u->id }}" data-name="{{ $u->name }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $u->id }}"
                                            action="{{ route('users.destroy', $u->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No users found</h4>
                                        <p>Add your first user to get started.</p>
                                        <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-person-plus"></i> Add First User
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $users->links('pagination::bootstrap-5') }}
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
            text: `Delete user "${name}"? This action cannot be undone!`,
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