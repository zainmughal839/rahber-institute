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
                            <i class="bi bi-list-ul me-2"></i>
                            Student Categories
                        </h3>
                        @can('stu_category.create')
                        <div class="card-tools">
                            <a href="{{ route('stu-category.create') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add New Category
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>{{ $cat->name }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $cat->desc ? Str::limit($cat->desc, 80) : '-' }}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            {{-- Edit --}}
                                            <a href="{{ route('stu-category.edit', $cat->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $cat->id }}"
                                            action="{{ route('stu-category.destroy', $cat->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-database-x display-1 d-block mb-3"></i>
                                        <h4>No categories found</h4>
                                        <p>Create categories to classify students.</p>

                                        <a href="{{ route('stu-category.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Category
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

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
            text: `Delete category "${name}"? This action cannot be undone!`,
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