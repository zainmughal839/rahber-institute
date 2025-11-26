@extends('layout.master')

@section('content')

<div class="col-12">
    <div class="m-4">


        <div class="card card-primary card-outline shadow-sm col-8">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Edit Session</h3>
                <a href="{{ route('sessions.create') }}" class="btn btn-primary">+ Add New Session</a>
            </div>

            <form action="{{ route('sessions.update', $session->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <!-- FORM FIELDS -->
                    <div class="row">
                        <div class="col-md-8">

                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $session->start_date }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $session->end_date }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ $session->description }}</textarea>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-12">
                    <div class="card-footer text-strat">
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Update</button>
                        </div>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection