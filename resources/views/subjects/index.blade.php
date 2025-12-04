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
                            <i class="bi bi-book-half me-2"></i> All Subjects
                        </h3>

                        @can('subject.create')
                        <a href="{{ route('subjects.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Subject
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
                                    <th>Book Name</th>
                                    <th>Short Name</th>

                                    @canany(['subject.update', 'subject.delete'])
                                    <th class="text-center" width="120">Actions</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($subjects as $sub)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>{{ $sub->book_name }}</td>
                                    <td>
                                        <small class="text-muted">{{ $sub->book_short_name ?? '-' }}</small>
                                    </td>

                                    @canany(['subject.update', 'subject.delete'])
                                    <td class="text-center">
                                        <div class="btn-group">

                                            @can('subject.update')
                                            <a href="{{ route('subjects.edit', $sub->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            @can('subject.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $sub->id }}" data-name="{{ $sub->book_name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan

                                        </div>

                                        <form id="delete-form-{{ $sub->id }}"
                                            action="{{ route('subjects.destroy', $sub->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-book display-1 d-block mb-3"></i>
                                        <h4>No Subjects Found</h4>
                                        <p>Add subjects used in academic classes.</p>
                                        <a href="{{ route('subjects.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Subject
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete subject "${name}"? This cannot be undone!`,
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