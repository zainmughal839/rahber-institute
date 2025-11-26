@extends('layout.master')

@section('content')
<div class="col-8 m-4">
    <div class="card">
        <div class="card-header">
            <h4>{{ $user->name }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Joined:</strong> {{ optional($user->created_at)->format('d M, Y') }}</p>
        </div>
    </div>
</div>
@endsection