@extends('layout.master')
@section('title','Assigned MCQ Papers')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            Assigned  Papers
                        </h3>

                        @can('assign-paper.create')
                        <div class="card-tools">
                            <a href="{{ route('mcq.assign.create') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> New Assignment
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Paper Title</th>
                                    @unless($isStudentPanel ?? false)
                                        <th width="120" class="text-center">Students</th>
                                        <th width="120" class="text-center">Questions</th>
                                    @endunless
                                    @canany(['assign-paper.update','assign-paper.delete','assign-paper.index'])
                                        <th width="140" class="text-center">Actions</th>
                                    @endcanany
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($papers as $paper)
                                @php
                                    $paperDate = $paper->task?->paper_date;
                                    $paperDateTimestamp = $paperDate ? $paperDate->timestamp : null;
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $paper->title }}</strong><br>
                                        <small class="text-muted">
                                            Task: {{ $paper->task?->title ?? '-' }}<br>
                                            @if($paperDate)
                                                <span class="text-info">
                                                    Paper Date: {{ $paperDate->format('d M Y - h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-danger">No Paper Date Set</span>
                                            @endif
                                        </small>
                                    </td>

                                    @unless($isStudentPanel ?? false)
                                        <td class="text-center">
                                            <span class="badge bg-info px-3">{{ $paper->students_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success px-3">{{ $paper->questions_count }}</span>
                                        </td>
                                    @endunless

                                    @canany(['assign-paper.update','assign-paper.delete','assign-paper.index'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group" id="action-group-{{ $paper->id }}">

                                            {{-- Dynamic View Button - Updated via AJAX --}}
                                            <span id="view-btn-{{ $paper->id }}"
                                                  data-paper-id="{{ $paper->id }}"
                                                  data-paper-date="{{ $paperDateTimestamp }}">
                                                @if($paperDateTimestamp && now()->addSeconds(20)->greaterThanOrEqualTo($paperDate))
                                                    <a href="{{ route('mcq.assign.view', $paper->id) }}"
   class="btn btn-info btn-sm" title="View Paper">
    <i class="bi bi-eye"></i>
</a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled title="Available 20 sec before paper date">
                                                        <i class="bi bi-clock-history"></i> Soon
                                                    </button>
                                                @endif
                                            </span>

                                            @can('assign-paper.update')
                                                <a href="{{ route('mcq.assign.edit', $paper->id) }}"
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('assign-paper.delete')
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $paper->id }}" data-title="{{ $paper->title }}"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </div>

                                        <form id="delete-form-{{ $paper->id }}"
                                            action="{{ route('mcq.assign.destroy', $paper->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcanany
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x display-1 d-block mb-3"></i>
                                        <h4>No MCQ Assignments Found</h4>
                                        <p>Create MCQ paper assignments for students.</p>
                                        @can('assign-paper.create')
                                        <a href="{{ route('mcq.assign.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Create Assignment
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Delete Confirmation
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let title = this.dataset.title;
        Swal.fire({
            title: 'Are you sure?',
            text: `Delete MCQ Paper "${title}"? This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});

// AJAX: Check paper availability every 10 seconds
function checkPaperAvailability() {
    document.querySelectorAll('[id^="view-btn-"]').forEach(element => {
        const paperId = element.dataset.paperId;
        const paperDate = parseInt(element.dataset.paperDate);

        if (!paperDate) return;

        const now = Math.floor(Date.now() / 1000); 
        const unlockTime = paperDate; 

        if (now >= unlockTime) {
          element.innerHTML = `
            <a href="/mcq/assign/${paperId}/view" class="btn btn-info btn-sm" title="View Paper">
                <i class="bi bi-eye"></i>
            </a>
        `;
        }
    });
}

// Run every 10 seconds
setInterval(checkPaperAvailability, 10000);

// Also check immediately on load
checkPaperAvailability();
</script>

@endsection