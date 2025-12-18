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

    <div class="card card-primary card-outline shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold mb-0">
                <i class="bi bi-kanban me-2"></i> Tasks
            </h3>

            @can('task.create')
            <a href="{{ route('tasks.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Task
            </a>
            @endcan
</div>
        </div>

        

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Audience</th>
                            <th>Task Start</th>
                            <th>Paper Date</th>
                            @canany(['task.update','task.delete', 'task.index'])
<th width="140" class="text-center">Actions</th>
@endcanany

                        </tr>
                    </thead>

                    <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td class="text-center fw-bold">
                                {{ $loop->iteration + (($tasks->currentPage()-1) * $tasks->perPage()) }}
                            </td>

                            {{-- Audience --}}
                            <td>
                                @if(is_array($task->audience))
                                    @foreach($task->audience as $aud)
                                        <span class="badge bg-info me-1">
                                            {{ ucfirst($aud) }}
                                        </span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Task Start --}}
                            <td>
                                {{ $task->task_start
                                    ? $task->task_start->format('d M, Y H:i')
                                    : '-' }}
                            </td>

                            {{-- Paper Date --}}
                            <td>
                                {{ $task->paper_date
                                    ? $task->paper_date->format('d M, Y H:i')
                                    : '-' }}
                            </td>

                            {{-- Actions --}}
@canany(['task.index','task.update','task.delete'])
<td class="text-center">
    <div class="btn-group">

        {{-- VIEW --}}
        @can('task.index')
        <a href="{{ route('tasks.view', $task->id) }}"
           class="btn btn-info btn-sm"
           title="View Task">
            <i class="bi bi-eye"></i>
        </a>
        @endcan

        {{-- EDIT --}}
        @can('task.update')
        <a href="{{ route('tasks.edit', $task->id) }}"
           class="btn btn-warning btn-sm"
           title="Edit Task">
            <i class="bi bi-pencil-square"></i>
        </a>
        @endcan

        {{-- DELETE --}}
        @can('task.delete')
        <button type="button"
                class="btn btn-danger btn-sm delete-btn"
                data-id="{{ $task->id }}"
                data-name="Task #{{ $task->id }}"
                title="Delete Task">
            <i class="bi bi-trash"></i>
        </button>
        @endcan

    </div>

    {{-- DELETE FORM --}}
    @can('task.delete')
    <form id="delete-form-{{ $task->id }}"
          action="{{ route('tasks.destroy', $task->id) }}"
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
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-kanban display-1 d-block mb-3"></i>
                                <h4>No Tasks Found</h4>

                                @can('task.create')
                                <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-lg me-2"></i> Add First Task
                                </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="p-3">
                    {{ $tasks->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        let id = this.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This task will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});
</script>
@endsection
