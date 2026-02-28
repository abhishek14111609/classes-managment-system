@extends('layouts.app')

@section('title', $isSport ? 'Academy Dashboard' : 'School Dashboard')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Top Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h2 class="fw-bold mb-1 text-gradient">Dashboard Overview</h2>
                <p class="text-muted small mb-0">Live stats for your {{ $isSport ? 'sports academy' : 'school' }}.</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                @if($stats['days_until_expiry'] !== null && $stats['days_until_expiry'] <= 7)
                    <div class="alert alert-warning py-2 px-3 mb-0 rounded-pill d-flex align-items-center shadow-sm">
                        <i class="bi bi-clock-history me-2 fs-5"></i>
                        <span class="small fw-bold">Subscription Renewal Due: {{ $stats['days_until_expiry'] }} Days</span>
                    </div>
                @endif
                <div class="bg-white p-2 px-3 rounded-pill shadow-sm border small fw-bold text-muted">
                    <i class="bi bi-calendar3 me-1 text-primary"></i> {{ now()->format('D, d M Y') }}
                </div>
            </div>
        </div>

        <!-- Quick Stat Highlighters -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($stats['total_students']) }}</h2>
                                <p class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 1px;">
                                    {{ $label['students'] }}
                                </p>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-primary position-absolute bottom-0 start-0 w-100" style="height: 4px; opacity: 0.2;">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($stats['total_teachers']) }}</h2>
                                <p class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 1px;">
                                    {{ $label['teachers'] }}
                                </p>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-4 text-success">
                                <i class="bi bi-person-badge-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-success position-absolute bottom-0 start-0 w-100" style="height: 4px; opacity: 0.2;">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="fw-bold mb-1">₹{{ number_format($stats['monthly_collection'], 0) }}</h2>
                                <p class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 1px;">Monthly
                                    Revenue</p>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-4 text-info">
                                <i class="bi bi-currency-rupee fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-info position-absolute bottom-0 start-0 w-100" style="height: 4px; opacity: 0.2;"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="fw-bold mb-1 {{ $overdueCount > 0 ? 'text-danger' : '' }}">
                                    ₹{{ number_format($stats['pending_fees'], 0) }}</h2>
                                <p class="text-muted small fw-bold text-uppercase mb-0" style="letter-spacing: 1px;">
                                    Outstanding</p>
                            </div>
                            <div class="bg-danger bg-opacity-10 p-3 rounded-4 text-danger">
                                <i class="bi bi-wallet2 fs-3"></i>
                            </div>
                        </div>
                        @if($overdueCount > 0)
                            <div class="mt-2">
                                <span class="badge bg-danger rounded-pill px-2 py-1 tiny shadow-sm">
                                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $overdueCount }} Overdue
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-danger position-absolute bottom-0 start-0 w-100" style="height: 4px; opacity: 0.2;">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Main Chart Section -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">Financial Performance</h5>
                            <p class="text-muted tiny mb-0">Monthly Fee Collection vs Projections</p>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light border dropdown-toggle"
                                data-bs-toggle="dropdown">
                                {{ now()->year }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div style="position: relative; height: 350px; width: 100%;">
                            <canvas id="feeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Activity/Stats Section -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">High-Risk Accounts</h5>
                        <a href="{{ route('school.reports.pending-fees') }}"
                            class="btn btn-sm btn-link text-decoration-none">Export List</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="defaulters-list">
                            @forelse($topDefaulters as $defaulter)
                                <div class="p-4 border-bottom hover-bg-light transition-all">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px;">
                                                <span class="fw-bold">{{ substr($defaulter->student->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $defaulter->student->user->name }}</div>
                                                <small class="text-muted d-block">{{ $defaulter->fee_count }} pending
                                                    installments</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-danger">₹{{ number_format($defaulter->balance, 0) }}</div>
                                            <a href="{{ route('school.students.show', $defaulter->student_id) }}"
                                                class="btn btn-sm btn-light border rounded-pill px-3 tiny fw-bold mt-1">Settle</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="bi bi-shield-check" style="font-size: 4rem;"></i>
                                    </div>
                                    <h6 class="text-muted">No high-risk accounts detected.</h6>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center rounded-bottom-4">
                        <a href="{{ route('school.reports.pending-fees') }}"
                            class="small fw-bold text-primary text-decoration-none">View Complete Audit Report <i
                                class="bi bi-chevron-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Insights Section -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4 bg-gradient-primary text-white">
                        <h6 class="fw-bold text-white-50 text-uppercase tiny mb-3" style="letter-spacing: 1px;">
                            {{ $label['students'] }} Enrolled
                        </h6>
                        <div class="d-flex align-items-end justify-content-between mb-4">
                            <h3 class="mb-0 fw-bold">{{ $stats['total_students'] }} Active</h3>
                            <span class="badge bg-white text-primary rounded-pill small px-2 py-1 shadow-sm">
                                +{{ array_sum($enrollmentTrend['enrollments'] ?? []) }} This Year
                            </span>
                        </div>
                        <div style="position: relative; height: 120px; width: 100%;">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-muted text-uppercase tiny mb-4" style="letter-spacing: 1px;">Quick Overview
                        </h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Active {{ $label['batches'] }}</span>
                            <span class="small fw-bold">{{ $stats['total_batches'] }}</span>
                        </div>
                        <div class="progress rounded-pill mb-4" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ $stats['total_batches'] > 0 ? '100%' : '0%' }}"></div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">{{ $label['classes'] }}</span>
                            <span class="small fw-bold">{{ $stats['total_classes'] }}</span>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height: 6px;">
                            <div class="progress-bar bg-info"
                                style="width: {{ $stats['total_classes'] > 0 ? '100%' : '0%' }}"></div>
                        </div>
                        <div class="mt-4 pt-2">
                            <a href="{{ route('school.batches.index') }}"
                                class="btn btn-outline-primary w-100 rounded-pill py-2 small fw-bold">Manage
                                {{ $label['batches'] }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-muted text-uppercase tiny mb-4" style="letter-spacing: 1px;">Monthly
                            Expenses</h6>
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-dark mb-1">₹{{ number_format($stats['monthly_expenses'], 0) }}</h4>
                            <p class="text-muted tiny">This Month</p>
                        </div>
                        <div class="p-3 bg-light rounded-4 mb-4">
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-white">
                                <span class="tiny text-muted">Fee Collected:</span>
                                <span
                                    class="tiny fw-bold text-success">₹{{ number_format($stats['monthly_collection'], 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="tiny text-muted">Net Balance:</span>
                                <span
                                    class="tiny fw-bold text-primary">₹{{ number_format(($stats['monthly_collection'] - $stats['monthly_expenses']), 0) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('school.expenses.index') }}"
                            class="btn btn-primary w-100 rounded-pill py-2 small fw-bold shadow-sm">Review Expenses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .text-gradient {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const feeData = @json($feeChart);
            const enrollmentData = @json($enrollmentTrend);

            // Detailed Financial Line Chart
            const feeCtx = document.getElementById('feeChart').getContext('2d');
            new Chart(feeCtx, {
                type: 'line',
                data: {
                    labels: feeData.labels,
                    datasets: [{
                        label: 'Collected Revenue',
                        data: feeData.collected,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: 'Uncollected Dues',
                        data: feeData.pending,
                        borderColor: '#f56565',
                        borderDash: [5, 5],
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6, font: { weight: 'bold', size: 11 } } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: true, drawBorder: false, color: '#f0f0f0' }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });

            // Enrollment Sparkline
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: enrollmentData.labels,
                    datasets: [{
                        label: 'New Registrations',
                        data: enrollmentData.enrollments,
                        backgroundColor: 'rgba(255, 255, 255, 0.4)',
                        borderRadius: 4,
                        hoverBackgroundColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { display: false },
                        x: { display: false }
                    }
                }
            });
        });
    </script>
@endpush