@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <!-- Header with Buttons -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-chart-line me-2"></i> Profit & Loss Report
                        </h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('profit_loss.print') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                               target="_blank" class="btn btn-info btn-sm">
                                <i class="bi bi-printer me-1"></i> Print Report
                            </a>
                            <a href="{{ route('profit_loss.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                            </a>

                           @if($showAll ?? false)
<a href="{{ route('profit_loss.index') }}{{ request()->except('show_all') ? '?' . http_build_query(request()->except('show_all')) : '' }}"
   class="btn btn-outline-light btn-sm">
    <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
</a>
@else
<a href="{{ route('profit_loss.index', ['show_all' => 1] + request()->query()) }}"
   class="btn btn-outline-light btn-sm">
    <i class="bi bi-list-ul me-1"></i> View All Records
</a>
@endif

                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body border-bottom py-3">
                    <form method="GET" action="{{ route('profit_loss.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate ?? '' }}">
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-funnel me-1"></i> Generate Report
                            </button>
                            <a href="{{ route('profit_loss.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="card-body py-4">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <div class="card border-success shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Total Income</h6>
                                    <h3 class="text-success fw-bold mb-0">Rs. {{ number_format($income, 0) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-danger shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Total Expenses</h6>
                                    <h3 class="text-danger fw-bold mb-0">Rs. {{ number_format($expenses, 0) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $profitLoss >= 0 ? 'border-success' : 'border-danger' }} shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Net Profit/Loss</h6>
                                    <h3 class="{{ $profitLoss >= 0 ? 'text-success' : 'text-danger' }} fw-bold mb-0">
                                        Rs. {{ number_format(abs($profitLoss), 0) }}
                                        <span class="badge {{ $profitLoss >= 0 ? 'bg-success' : 'bg-danger' }} ms-2">
                                            {{ $profitLoss >= 0 ? 'Profit' : 'Loss' }}
                                        </span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body p-0 border-top">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Voucher/Challan No</th>
                                    <th>Title/Description</th>
                                    <th width="110">Date</th>
                                    <th width="130" class="text-end">Amount</th>
                                    <th width="100" class="text-center">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $showAll ?? false ? $loop->iteration : ($entries->firstItem() + $loop->index) }}
                                    </td>
                                      <td class="fw-semibold">
                                        @if($entry->voucher_no ?? $entry->challan_no)
                                            {{ $entry->voucher_no ?? $entry->challan_no ?? '' }}
                                        @elseif($entry->student)
                                            {{ $entry->student->name }}
                                            <br>
                                            <small class="text-muted">Roll #: {{ $entry->student->rollnum }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $entry->title ?? '-' }}</strong>
                                        @if($entry->description)
                                        <br><small class="text-muted">{{ Str::limit($entry->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($entry->voucher_date ?? $entry->created_at)->format('d-m-Y') }}</td>
                                    <td class="text-end fw-bold {{ $entry->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                        Rs. {{ number_format($entry->amount, 0) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $entry->type === 'credit' ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                            {{ $entry->type === 'credit' ? 'Income' : 'Expense' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-chart-line display-1 d-block mb-3"></i>
                                        <h4>No Entries Found</h4>
                                        <p>Please select a date range to view the report.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(!($showAll ?? false))
                <div class="card-footer bg-light border-top text-center">
                    {{ $entries->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection