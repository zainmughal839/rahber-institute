@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-dark text-white">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi bi-gear-wide-connected me-2"></i>
                        System Settings
                    </h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Company / Institute Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control" required
                                       value="{{ old('company_name', $settings->company_name) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $settings->email) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $settings->phone) }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="address" rows="3" class="form-control">{{ old('address', $settings->address) }}</textarea>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-semibold">Main Logo </label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                @if($settings->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->logo) }}" width="150" class="border rounded">
                                    <small class="text-muted d-block">Current Logo</small>
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-semibold">Invoice Logo (for Challan)</label>
                                <input type="file" name="invoice_logo" class="form-control" accept="image/*">
                                @if($settings->invoice_logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->invoice_logo) }}" width="150" class="border rounded">
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-semibold">Favicon (.ico or .png)</label>
                                <input type="file" name="favicon" class="form-control" accept=".ico,.png">
                                @if($settings->favicon)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->favicon) }}" width="32" class="border rounded">
                                    <small class="text-muted d-block">Current Favicon</small>
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-semibold">Paid Stamp Image (for Paid Invoices/Challan) </label>
                                <small class="text-muted d-block">Best: Transparent PNG with red "PAID" text</small>
                                <input type="file" name="paid_stamp" class="form-control" accept="image/*">
                                @if($settings->paid_stamp)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->paid_stamp) }}" width="200" class="border rounded bg-light">
                                    <small class="text-muted d-block">Current Paid Stamp</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-check2-all me-2"></i>
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection