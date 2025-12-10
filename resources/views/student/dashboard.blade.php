@extends('layout.master')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">Student Dashboard</h2>

    <div class="card shadow">
        <div class="card-body">
            <h4>{{ $student->name }}</h4>

            <p><strong>Father Name:</strong> {{ $student->father_name }}</p>
            <p><strong>Roll Number:</strong> {{ $student->rollnum }}</p>
            <p><strong>Phone:</strong> {{ $student->phone }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>

            <hr>

            <p>Welcome to your student panel.</p>
        </div>
    </div>

</div>

@endsection