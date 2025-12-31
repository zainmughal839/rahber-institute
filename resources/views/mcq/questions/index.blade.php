@extends('layout.master')
@section('content')

<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white ">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi bi-question-circle me-1"></i>
                    MCQ Questions
                </h3>

                <a href="{{ route('mcq.banks.questions.create',$bank->id) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> Add Mcqs
                </a>

            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Correct</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $q)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $q->question }}</td>
                        <td class="fw-bold">{{ strtoupper($q->correct_option) }}</td>
                        <td class="text-center">
                            <div class="btn-group">

                                {{-- EDIT --}}
                                <a href="{{ route('mcq.banks.questions.edit',[$bank->id,$q->id]) }}"
                                    class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- DELETE --}}
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $q->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <form id="delete-form-{{ $q->id }}"
                                    action="{{ route('mcq.banks.questions.destroy',[$bank->id,$q->id]) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No questions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection