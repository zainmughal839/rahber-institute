@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h3 class="mb-0">
                <i class="bi bi-file-earmark-text"></i> MCQ Papers
            </h3>
            <a href="{{ route('mcq.papers.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> New Paper
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($papers as $paper)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paper->title }}</td>
                            <td>
                                <span class="badge bg-{{ $paper->status=='published'?'success':'secondary' }}">
                                    {{ ucfirst($paper->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('mcq.papers.edit',$paper->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="{{ route('mcq.papers.index',$paper->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-question-circle"></i>
                                </a>

                                <form action="{{ route('mcq.papers.destroy',$paper->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete paper?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No papers found</td></tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
