@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <h3><i class="bi bi-plus-circle"></i> Create MCQ Paper</h3>
        </div>

        <form method="POST" action="{{ route('mcq.papers.store') }}">
            @csrf
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label>Paper Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <div class="col-12">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

            </div>

            <div class="card-footer text-end">
                <button class="btn btn-success">Save Paper</button>
                <a href="{{ route('mcq.papers.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>

    </div>
</div>
@endsection
