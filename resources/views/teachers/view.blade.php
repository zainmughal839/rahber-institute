{{-- resources/views/teachers/view.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">
        <!-- Header -->

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                 <h3 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> Teacher Details</h3>
                <div class="card-tools">
                    @can('teacher.index')
            <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to List
            </a>
            @endcan
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="row">

                <!-- Profile -->
                <div class="col-md-4 text-center mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            @if($teacher->picture)
                                <img src="{{ asset('storage/'.$teacher->picture) }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; font-size: 50px;">
                                    {{ Str::upper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @endif
                            <h4 class="fw-bold">{{ $teacher->name }}</h4>
                            <p class="text-muted mb-1"><i class="bi bi-envelope me-1"></i> {{ $teacher->email ?? '-' }}</p>
                            <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $teacher->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                 
                <div class="col-md-8">
                    <!-- Personal Info -->
                     
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                         
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted fw-semibold">CNIC Front:</div>
                                <div class="col-sm-8">
                                    @if($teacher->cnic_front_image)
                                        <img src="{{ asset('storage/'.$teacher->cnic_front_image) }}" class="img-fluid rounded mb-2" style="height: 100px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Not Uploaded</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted fw-semibold">CNIC Back:</div>
                                <div class="col-sm-8">
                                    @if($teacher->cnic_back_image)
                                        <img src="{{ asset('storage/'.$teacher->cnic_back_image) }}" class="img-fluid rounded mb-2" style="height: 100px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Not Uploaded</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted fw-semibold">Salary:</div>
                                <div class="col-sm-8">
                                    <strong>₹ {{ number_format($teacher->salary, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Info -->
                      <h5 class="card-title fw-bold p-2">Academic Qualifications</h5>
                    <div class=" shadow-sm border-0">
                        <div class="card-body">
                            <br>
                            @if(!empty($teacher->academic_details))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Degree</th>
                                                <th>Institute</th>
                                                <th>Passing Year</th>
                                                <th>Certificate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teacher->academic_details as $i => $a)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $a['degree'] }}</td>
                                                <td>{{ $a['institute'] }}</td>
                                                <td>{{ $a['passing_year'] }}</td>
                                                <td>
                                                    @if(!empty($a['image']))
                                                        <a href="{{ asset('storage/'.$a['image']) }}" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf-fill text-primary"></i> View
                                                        </a>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No academic records available.</p>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
