@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline shadow-lg border-0">

                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-list-task me-2"></i> Task Categories
                        </h3>

                        @can('task-cat.create')
                        <a href="{{ route('task-cat.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
<tr>
    <th class="text-center" width="80">#</th>
    <th>Name</th>
    <th>Description</th>

    @canany(['task-cat.update','task-cat.delete'])
    <th class="text-center" width="120">Actions</th>
    @endcanany
</tr>
</thead>


                           <tbody>
@forelse ($records as $cat)
<tr>
    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

    <td>{{ $cat->name }}</td>

    <td>
        <small class="text-muted">{{ $cat->desc ?? '-' }}</small>
    </td>

    @canany(['task-cat.update','task-cat.delete'])
    <td class="text-center">
        <div class="btn-group">

            @can('task-cat.update')
            <a href="{{ route('task-cat.edit', $cat->id) }}"
               class="btn btn-warning btn-sm" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>
            @endcan

            @can('task-cat.delete')
            <button type="button"
                    class="btn btn-danger btn-sm delete-btn"
                    data-id="{{ $cat->id }}"
                    data-name="{{ $cat->name }}">
                <i class="bi bi-trash"></i>
            </button>
            @endcan

        </div>

        @can('task-cat.delete')
        <form id="delete-form-{{ $cat->id }}"
              action="{{ route('task-cat.destroy', $cat->id) }}"
              method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        @endcan
    </td>
    @endcanany
</tr>
@empty
<tr>
    <td colspan="4" class="text-center py-5 text-muted">
        <i class="bi bi-list-task display-1 d-block mb-3"></i>
        <h4>No Categories Found</h4>

        @can('task-cat.create')
        <a href="{{ route('task-cat.create') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-lg me-2"></i> Add First Category
        </a>
        @endcan
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

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete category "${name}"? This cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});
</script>
@endsection
