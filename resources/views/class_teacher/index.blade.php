@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">

                <h3 class="card-title fw-bold mb-0"><i class="bi bi-person-badge me-2"></i> Class - Teacher Assignments
                </h3>
                <div class="card-tools">
                    @can('class-teacher.create')
                    <a href="{{ route('class-teacher.create') }}" class="btn btn-light btn-sm me-2"><i
                            class="bi bi-plus-circle me-1"></i> Assign Teacher</a>
                    @endcan

                    @if(isset($showAll) && $showAll)
                    <a href="{{ route('class-teacher.index') }}" class="btn btn-outline-light btn-sm"><i
                            class="bi bi-arrow-left-circle me-1"></i> Back to Paginated</a>
                    @else
                    <a href="{{ route('class-teacher.all') }}" class="btn btn-outline-light btn-sm"><i
                            class="bi bi-list-ul me-1"></i> View All Records</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Status</th>
                            <th>Description</th>
                            @canany(['class-teacher.update', 'class-teacher.delete', 'class-teacher.index'])
                            <th width="140" class="text-center">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td class="text-center fw-bold">
                                {{ $loop->iteration + (isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($data->currentPage()-1) * $data->perPage() : 0) }}
                            </td>

                            <td>{{ $row->classSubject->class_name ?? '-' }}</td>
                            <td>{{ $row->classSubject->subject->book_name ?? '-' }}</td>
                            <td>{{ $row->teacher->name ?? '-' }}</td>

                            <td class="text-center">
                                <span
                                    class="badge {{ $row->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($row->status) }}</span>
                            </td>

                            <td><small class="text-muted">{{ Str::limit($row->desc ?? '-', 80) }}</small></td>


                            @canany(['class-teacher.update', 'class-teacher.delete', 'class-teacher.index'])
                            <td class="text-center">
                                <div class="btn-group" role="group">

                                    {{-- View Button --}}
                                    @can('class-teacher.index')
                                    <a href="{{ route('class-teacher.show', $row->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @endcan

                                    @can('class-teacher.update')
                                    <a href="{{ route('class-teacher.edit', $row->id) }}"
                                        class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    @endcan

                                    @can('class-teacher.delete')
                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                        data-id="{{ $row->id }}"
                                        data-name="{{ $row->classSubject->class_name ?? 'Assignment' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endcan
                                </div>

                                <form id="delete-form-{{ $row->id }}"
                                    action="{{ route('class-teacher.destroy', $row->id) }}" method="POST"
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
                                <i class="bi bi-people-x display-1 d-block mb-3"></i>
                                <h4>No assignments found</h4>
                                <p>Assign teachers to classes & subjects here.</p>
                                <a href="{{ route('class-teacher.create') }}" class="btn btn-primary mt-3"><i
                                        class="bi bi-plus-lg me-2"></i> Assign Teacher</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(!isset($showAll))
        <div class="card-footer bg-light border-top">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 Delete -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name || 'record';

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete assignment "${name}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endsection