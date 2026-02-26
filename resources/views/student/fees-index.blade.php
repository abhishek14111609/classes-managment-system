@extends('layouts.app')

@section('title', 'Financial Wallet')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Modern Wallet Header -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2 h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="text-white-50 fw-bold mb-1 small text-uppercase" style="letter-spacing: 1px;">
                                    Wallet Balance</h6>
                                <h2 class="fw-bold mb-0 display-5">₹{{ number_format($stats['total_remaining']) }}</h2>
                            </div>
                            <div
                                class="bg-primary bg-opacity-10 p-3 rounded-circle border border-primary border-opacity-25">
                                <i class="bi bi-wallet2 text-primary fs-2"></i>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-top border-white border-opacity-10">
                            <div class="row g-4">
                                <div class="col-6">
                                    <small class="text-white-50 fw-bold d-block mb-1">Total Allocated</small>
                                    <h5 class="fw-bold mb-0">₹{{ number_format($stats['total_due']) }}</h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-white-50 fw-bold d-block mb-1">Total Cleared</small>
                                    <h5 class="fw-bold text-success mb-0">₹{{ number_format($stats['total_paid']) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-2 overflow-hidden bg-white">
                    <div class="card-body p-4 d-flex flex-column">
                        <h6 class="text-muted fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Repayment
                            Progress</h6>
                        <div class="d-flex align-items-center justify-content-center grow position-relative">
                            <!-- Circular Progress Simulation -->
                            <div class="position-relative" style="width: 130px; height: 130px;">
                                <svg class="w-100 h-100" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="16" fill="none" stroke="#f1f5f9" stroke-width="3"></circle>
                                    <circle cx="18" cy="18" r="16" fill="none" stroke="#6366f1" stroke-width="3"
                                        stroke-dasharray="{{ $stats['payment_progress'] }}, 100" stroke-linecap="round">
                                    </circle>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h4 class="fw-bold mb-0">{{ round($stats['payment_progress']) }}%</h4>
                                    <small class="tiny text-muted fw-bold">Paid</small>
                                </div>
                            </div>
                        </div>
                        <p class="text-center text-muted small mt-3 mb-0">Maintain timely payments to avoid <span
                                class="text-danger fw-bold">Late Charges</span>.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Plans & Breakdown -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Fee Structural Breakdown</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-none">
                                <i class="bi bi-funnel me-1"></i> All Time
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 small">DUE DATE</th>
                                        <th class="small">PLAN TYPE</th>
                                        <th class="small">COST BREAKDOWN</th>
                                        <th class="small text-center">STATUS</th>
                                        <th class="small pe-4 text-end">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fees as $fee)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="fw-bold text-dark">{{ $fee->due_date->format('d M, Y') }}</div>
                                                            <div class="tiny text-muted fw-semibold">Expires:
                                                                {{ $fee->due_date->diffForHumans() }}
                                                            </div>
                                                        </td>
                                                        <td>
                                        @php
                                            $typeClasses = [
                                                'tuition' => 'bg-primary-subtle text-primary',
                                                'sports' => 'bg-success-subtle text-success',
                                                'transport' => 'bg-warning-subtle text-warning',
                                                'exam' => 'bg-danger-subtle text-danger',
                                                'library' => 'bg-info-subtle text-info',
                                                'other' => 'bg-secondary-subtle text-secondary',
                                            ];
                                            $typeLabel = ucwords(str_replace('_', ' ', $fee->fee_type));
                                            $btnClass = $typeClasses[$fee->fee_type] ?? 'bg-light text-muted';
                                        @endphp
                                                            <span
                                                                class="badge {{ $btnClass }} border-0 rounded-pill px-3">{{ $typeLabel }}</span>
                                                            <div class="tiny text-muted mt-1 fw-medium"><i class="bi bi-repeat me-1"></i>
                                                                {{ ucfirst($fee->duration ?? 'One-Time') }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="mb-1">
                                                                <span
                                                                    class="small fw-bold text-dark">₹{{ number_format($fee->total_amount) }}</span>
                                                                <span class="tiny text-muted">Total</span>
                                                            </div>
                                                            <div class="progress bg-light"
                                                                style="height: 4px; width: 120px; border-radius: 2px;">
                                                                <div class="progress-bar bg-success"
                                                                    style="width: {{ $fee->total_amount > 0 ? ($fee->paid_amount / $fee->total_amount) * 100 : 0 }}%">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $statusTheme = [
                                                                    'paid' => 'success',
                                                                    'partial' => 'warning',
                                                                    'pending' => 'info',
                                                                    'overdue' => 'danger'
                                                                ][$fee->status] ?? 'secondary';
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $statusTheme }}-subtle text-{{ $statusTheme }} rounded-pill px-3 border-0">{{ ucfirst($fee->status) }}</span>
                                                        </td>
                                                        <td class="pe-4 text-end">
                                                            <a href="{{ route('student.fees.show', $fee->id) }}"
                                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold tiny">
                                                                Manage & Pay <i class="bi bi-chevron-right ms-1"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="opacity-25 mb-3">
                                                    <i class="bi bi-cash-stack display-1"></i>
                                                </div>
                                                <p class="text-muted small">No fee plans assigned to your account yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct Support & Quick Info -->
        <div class="row g-4 mb-5 pb-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="bg-primary bg-opacity-20 p-3 rounded-4 me-3 text-primary">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark">Secure Transactions</h6>
                            <p class="text-muted small mb-0">All payments are encrypted. Download your official digital
                                receipt immediately after successful clearance.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-info bg-opacity-10">
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="bg-info bg-opacity-20 p-3 rounded-4 me-3 text-info">
                            <i class="bi bi-info-circle-fill fs-2"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark">Need Assistance?</h6>
                            <p class="text-muted small mb-0">If you find any discrepancy in your fee ledger, please contact
                                the finance desk with your transaction reference.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection