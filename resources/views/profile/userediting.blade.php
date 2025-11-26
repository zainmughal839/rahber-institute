@extends('layout.master')

@section('content')
<div class="col-8">
    <div class="m-4">

        {{-- Success Message --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> Please fix the following:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif


        <div class="card card-primary card-outline shadow-sm col-12">
            <div class="card-header">
                <h3 class="card-title">Update Profile</h3>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="card-body">

                    <!-- CENTER THE FORM FIELDS -->
                    <div class="row ">
                        <div class="col-md-10">

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    required>
                            </div>

                            <hr>

                            <h5 class="mb-3">Change Password (Optional)</h5>

                            <div class="mb-3">
                                <label class="form-label">Old Password</label>
                                <input type="password" name="oldpassword" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="newpassword" class="form-control">
                            </div>

                        </div>
                    </div>

                </div>

                <div class="card-footer text-strat">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection