@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-folder2-open me-2"></i> MCQ Books Head
                        </h3>

                        @can('mcq-category.create') 
                        <a href="{{ route('mcq.categories.create') }}"
                           class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Books Head
                        </a>
                         @endcan 
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th width="120" class="text-center">Status</th>

                                    @canany(['mcq-category.update','mcq-category.delete']) 
                                    <th width="140" class="text-center">Actions</th>
                                     @endcanany 
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                      {{ $cat->name }}
                                    </td>

                                    <td>
                                        <small class="text-muted">
                                            {{ $cat->description ?? '-' }}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge {{ $cat->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $cat->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                     @canany(['mcq-category.update','mcq-category.delete']) 
                                    <td class="text-center">
                                        <div class="btn-group">

                                        @can('mcq-category.update') 
                                            <a href="{{ route('mcq.categories.edit',$cat->id) }}"
                                               class="btn btn-warning btn-sm"
                                               title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan
                                            

                                            @can('mcq-category.delete') 
                                            
                                            <button type="button"
                                                    class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $cat->id }}"
                                                    data-name="{{ $cat->name }}"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                            
                                        </div>

                                        {{-- DELETE FORM --}}
                                        
                                        <form id="delete-form-{{ $cat->id }}"
                                              action="{{ route('mcq.categories.destroy',$cat->id) }}"
                                              method="POST"
                                              style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                     @endcanany 
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x display-1 d-block mb-3"></i>
                                        <h4>No MCQ Categories Found</h4>
                                        <p>Create categories to build MCQ Bank.</p>

                                        @can('mcq-category.create')
                                        <a href="{{ route('mcq.categories.create') }}"
                                           class="btn btn-primary mt-3">
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

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete category "${name}"? This cannot be undone!`,
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
