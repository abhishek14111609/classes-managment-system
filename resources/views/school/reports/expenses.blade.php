@extends('layouts.app')

@section('title', 'Expense Analysis')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Expense Analysis</h2>
                <p class="text-muted mb-0">School expenditure broken down by category</p>
            </div>
            <a href="{{ route('school.reports.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('school.reports.expenses') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Year</label>
                        <select name="year" class="form-select">
                            @for($y = now()->year; $y >= now()->year - 4; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Month</label>
                        <select name="month" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-search"></i> View Expenses
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($report->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No expenses recorded for the selected period.
            </div>
        @else
            {{-- Expense Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Total Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report as $row)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary me-2">{{ ucfirst($row->category) }}</span>
                                    </td>
                                    <td class="text-end fw-semibold text-danger">₹{{ number_format($row->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-warning fw-bold">
                            <tr>
                                <td>Total</td>
                                <td class="text-end">₹{{ number_format($report->sum('total'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection