@extends('layout.master')

@section('content')
<div class="col-8 m-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="card-title">Edit User</h4>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                        required>
                </div>

                <hr>
                <p class="text-muted small">Leave password fields empty if you don't want to change password.</p>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
                <button class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection