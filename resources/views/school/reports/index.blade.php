@extends('layouts.app')

@section('title', 'Institutional Insight Center')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Institutional Intelligence</h3>
                <p class="text-muted small mb-0">Leverage data-driven insights to optimize institutional health and academic
                    performance.</p>
            </div>
            <div class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-bold">
                <i class="bi bi-cpu-fill me-2"></i> Real-time Predictive Analytics Active
            </div>
        </div>

        <!-- Core Analytical Modules -->
        <div class="row g-4 mb-5">
            <!-- Financial Intelligence -->
            <div class="col-md-4">
                <div
                    class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all overflow-hidden position-relative">
                    <div class="card-body p-4 pt-5">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 d-inline-block mb-4 shadow-sm">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Revenue Audit</h5>
                        <p class="text-muted small mb-4 lh-lg">Comprehensive dissection of institutional fee collections,
                            scholarship disbursements, and net revenue streams for fiscal years.</p>
                        <a href="{{ route('school.reports.income') }}"
                            class="btn btn-soft-success w-100 rounded-pill fw-bold small">
                            Execute Financial Audit
                        </a>
                    </div>
                    <div class="bg-success position-absolute bottom-0 start-0 w-100 opacity-25" style="height: 3px;"></div>
                </div>
            </div>

            <!-- Expense Analysis -->
            <div class="col-md-4">
                <div
                    class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all overflow-hidden position-relative">
                    <div class="card-body p-4 pt-5">
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 d-inline-block mb-4 shadow-sm">
                            <i class="bi bi-pie-chart-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Cost Analysis</h5>
                        <p class="text-muted small mb-4 lh-lg">Granular tracking of institutional overheads, faculty
                            payroll, and facility maintenance costs with trend benchmarking.</p>
                        <a href="{{ route('school.reports.expenses') }}"
                            class="btn btn-soft-danger w-100 rounded-pill fw-bold small">
                            Review Expenditure
                        </a>
                    </div>
                    <div class="bg-danger position-absolute bottom-0 start-0 w-100 opacity-25" style="height: 3px;"></div>
                </div>
            </div>

            <!-- Operational Presence -->
            <div class="col-md-4">
                <div
                    class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all overflow-hidden position-relative">
                    <div class="card-body p-4 pt-5">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 d-inline-block mb-4 shadow-sm">
                            <i class="bi bi-person-check-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Presence Intelligence</h5>
                        <p class="text-muted small mb-4 lh-lg">Advanced monitoring of personnel engagement levels, chronic
                            absenteeism detection, and faculty consistency metrics.</p>
                        <a href="{{ route('school.reports.attendance') }}"
                            class="btn btn-soft-primary w-100 rounded-pill fw-bold small">
                            View Engagement Stats
                        </a>
                    </div>
                    <div class="bg-primary position-absolute bottom-0 start-0 w-100 opacity-25" style="height: 3px;"></div>
                </div>
            </div>
        </div>

        <!-- Specific Ledger Insights -->
        <div class="row g-4">
            <div class="col-md-4">
                <div
                    class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all border-start border-warning-subtle">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 me-3">
                                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0">High-Risk Receivables</h6>
                        </div>
                        <p class="text-muted tiny mb-4">Identify critical fee defaulters and aging receivables to initiate
                            institutional recovery protocols.</p>
                        <a href="{{ route('school.reports.pending-fees') }}"
                            class="btn btn-warning grow-on-hover rounded-pill px-4 fw-bold small w-100 shadow-sm text-dark">
                            Access Recovery Desk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .bg-soft-success:hover {
            background-color: #198754;
            color: #fff;
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .bg-soft-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .bg-soft-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .tiny {
            font-size: 0.7rem;
            letter-spacing: 0.3px;
        }

        .grow-on-hover:hover {
            transform: scale(1.02);
            transition: all 0.2s;
        }
    </style>
@endsection