@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

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

                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-book-half me-2"></i> Class Subjects
                        </h3>

                        <div class="card-tools">
                            @can('class-subject.create')
                            <a href="{{ route('class-subjects.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Class
                            </a>
                            @endcan

                            @if(isset($showAll) && $showAll)
                            <a href="{{ route('class-subjects.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('class-subjects.all') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> View All Records
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="10" class="text-center">#</th>
                                    <th width="150">Class Name</th>
                                    <th  width="250">Subjects</th>
                                    <th width="300">Session & Program</th>
                                    <th width="120" class="text-center">Status</th>
                                    @canany(['class-subject.update', 'class-subject.delete'])
                                    <th width="120" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($data as $row)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>{{ $row->class_name }}</td>

                                    <!-- MULTIPLE SUBJECTS FIX HERE -->
                                    <td>
                                        @foreach($row->subjects as $subject)
                                        <span class="badge bg-info text-dark me-1">
                                            {{ $subject->book_name }} ({{ $subject->book_short_name }})
                                        </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        @if($row->sessionProgram && $row->sessionProgram->session)

                                        <div>
                                            <strong>Session:</strong>
                                            {{ $row->sessionProgram->session->sessions_name }}

                                        </div>

                                        <div class="mt-2">
                                            <strong>Programs:</strong><br>
                                            @forelse($row->programs as $program)
                                            <span class="badge bg-primary me-1 mb-1">
                                                {{ $program->name }}
                                            </span>
                                            @empty
                                            <span class="text-muted">No Program</span>
                                            @endforelse
                                        </div>

                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <span
                                            class="badge {{ $row->status=='active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>

                                    @canany(['class-subject.update', 'class-subject.delete'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            @can('class-subject.update')
                                            <a href="{{ route('class-subjects.edit',$row->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            @can('class-subject.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $row->id }}" data-name="{{ $row->class_name }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan

                                        </div>

                                        <form id="delete-form-{{ $row->id }}"
                                            action="{{ route('class-subjects.destroy',$row->id) }}" method="POST"
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
                                        <i class="bi bi-book-x display-1 d-block mb-3"></i>
                                        <h4>No Class Subjects found</h4>
                                        <p>Add class subjects to manage sessions and subjects.</p>
                                        <a href="{{ route('class-subjects.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Class
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                @if(!isset($showAll) || !$showAll)
                <div class="card-footer bg-light border-top">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
                @endif

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
                text: `Delete class subject "${name}"? This cannot be undone!`,
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