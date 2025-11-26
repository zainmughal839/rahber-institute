@extends('layout.master')

@section('content')

<div class="col-12">
    <div class="m-4">

        @if ($errors->any())
        <div class="alert alert-danger">
            {{ implode(', ', $errors->all()) }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-primary card-outline shadow-sm col-8">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Edit Program</h3>
                <a href="{{ route('programs.index') }}" class="btn btn-primary">All Programs</a>
            </div>

            <form method="POST" action="{{ route('programs.update', $program->id) }}">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <!-- Program Name -->
                    <div class="mb-3">
                        <label class="form-label">Program Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}">
                    </div>

                    <!-- Shortname -->
                    <div class="mb-3">
                        <label class="form-label">Shortname</label>
                        <input type="text" name="shortname" class="form-control"
                            value="{{ old('shortname', $program->shortname) }}">
                    </div>

                    <!-- Program Code -->
                    <div class="mb-3">
                        <label class="form-label">Program Code</label>
                        <input type="text" name="program_code" class="form-control"
                            value="{{ old('program_code', $program->program_code) }}">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description', $program->description) }}</textarea>
                    </div>

                </div>

                <div class="card-footer text-start">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection