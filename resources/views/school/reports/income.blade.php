@extends('layouts.app')

@section('title', 'Income Report')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Income Report</h2>
                <p class="text-muted mb-0">Fee collections and payment summary</p>
            </div>
            <a href="{{ route('school.reports.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('school.reports.income') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Year</label>
                        <select name="year" class="form-select">
                            @for($y = now()->year; $y >= now()->year - 4; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Month (optional)</label>
                        <select name="month" class="form-select">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Fees Generated</div>
                        <h4 class="text-dark fw-bold">₹{{ number_format($report['total_generated'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Collected</div>
                        <h4 class="text-success fw-bold">₹{{ number_format($report['total_collected'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Still Pending</div>
                        <h4 class="text-warning fw-bold">₹{{ number_format($report['total_pending'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Discounts Given</div>
                        <h4 class="text-info fw-bold">₹{{ number_format($report['total_discount'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Collection Rate Bar --}}
        @php
            $rate = $report['total_generated'] > 0
                ? round(($report['total_collected'] / $report['total_generated']) * 100, 1)
                : 0;
        @endphp
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Collection Rate</strong>
                    <strong>{{ $rate }}%</strong>
                </div>
                <div class="progress" style="height:20px;">
                    <div class="progress-bar bg-success" style="width:{{ $rate }}%">{{ $rate }}%</div>
                </div>
            </div>
        </div>
    </div>
@endsection