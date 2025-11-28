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
                            <i class="bi bi-journal-text me-2"></i>
                            All Programs

                        </h3>
                        <div class="card-tools">
                            @can('program.create')
                            <a href="{{ route('programs.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Program
                            </a>
                            @endcan
                            @if(isset($showAll))
                            <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('programs.all') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul"></i> View All Records
                            </a>
                            @endif
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
                                    <th>Program Name</th>
                                    <th width="120">Short Name</th>
                                    <th width="130">Code</th>
                                    <th>Description</th>

                                    @canany(['program.update', 'program.delete'])
                                    <th width="120" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programs as $p)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $p->name }}
                                    </td>
                                    <td>
                                        <span class="  text-dark">{{ $p->shortname ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <code class=" text-dark ">{{ $p->program_code ?? '-' }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($p->description, 80) ?: '-' }}
                                        </small>
                                    </td>

                                    @canany(['program.update', 'program.delete'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('program.update')
                                            <a href="{{ route('programs.edit', $p->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan
                                            @can('program.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $p->id }}" data-name="{{ $p->name }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $p->id }}"
                                            action="{{ route('programs.destroy', $p->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-1 d-block mb-3"></i>
                                        <h4>No programs found</h4>
                                        <a href="{{ route('programs.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus"></i> Add First Program
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if (!isset($showAll) && $programs->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $programs->links('pagination::bootstrap-5') }}
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
            text: `Delete "${name}"? This action cannot be undone!`,
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