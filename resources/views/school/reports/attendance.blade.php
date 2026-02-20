@extends('layouts.app')

@section('title', 'Attendance Statistics')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Attendance Statistics</h2>
                <p class="text-muted mb-0">School-wide attendance overview</p>
            </div>
            <a href="{{ route('school.reports.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
        </div>

        {{-- Date Range Filter --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('school.reports.attendance') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">From Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ is_string($startDate) ? $startDate : $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">To Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ is_string($endDate) ? $endDate : $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check"></i> View Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Records</div>
                        <h3 class="fw-bold">{{ number_format($stats['total_records']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-start border-success border-4">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Present</div>
                        <h3 class="text-success fw-bold">{{ number_format($stats['present']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-start border-danger border-4">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Absent</div>
                        <h3 class="text-danger fw-bold">{{ number_format($stats['absent']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-start border-warning border-4">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Late</div>
                        <h3 class="text-warning fw-bold">{{ number_format($stats['late']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Rate Bar --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Overall Attendance Rate</strong>
                    <strong class="{{ $stats['present_percentage'] >= 75 ? 'text-success' : 'text-danger' }}">
                        {{ $stats['present_percentage'] }}%
                    </strong>
                </div>
                <div class="progress" style="height:24px;">
                    <div class="progress-bar {{ $stats['present_percentage'] >= 75 ? 'bg-success' : 'bg-danger' }}"
                        style="width:{{ $stats['present_percentage'] }}%">
                        {{ $stats['present_percentage'] }}%
                    </div>
                </div>
                @if($stats['present_percentage'] < 75)
                    <p class="text-danger small mt-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Attendance is below the 75% threshold.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection