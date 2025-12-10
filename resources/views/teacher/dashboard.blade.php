@extends('layout.master')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">Teacher Dashboard</h2>

    <div class="card shadow">
        <div class="card-body">
            <h4>{{ $teacher->name }}</h4>

            <p><strong>Phone:</strong> {{ $teacher->phone }}</p>
            <p><strong>Email:</strong> {{ $teacher->email }}</p>

            <hr>

            <p>Welcome to your teacher panel.</p>
        </div>
    </div>

</div>

@endsection