@extends('layouts.app')

@section('title', 'Institutional Receivables Analysis')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Header Area -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-cash-stack text-danger fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Receivables Analytics Deck</h4>
                                <p class="text-white-50 mb-0 small">Real-time tracking of outstanding institutional dues and
                                    billing cycles</p>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <div class="tiny text-white-50 fw-bold uppercase mb-1" style="letter-spacing: 1px;">TOTAL
                                OUTSTANDING LIQUIDITY</div>
                            <h3 class="fw-bold mb-0 text-white">₹{{ number_format($totalOutstanding, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receivables Ledger -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header border-0 bg-white pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0">Outstanding Collections Registry</h5>
                    <p class="text-muted small">Sequential breakdown of verified pending settlements</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light border rounded-pill px-4 btn-sm fw-bold dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> Export Data
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2">
                        <li><a class="dropdown-item rounded-3 py-2 small fw-bold" href="#"><i
                                    class="bi bi-file-earmark-pdf me-2 text-danger"></i> Export as PDF</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 small fw-bold" href="#"><i
                                    class="bi bi-file-earmark-excel me-2 text-success"></i> Export as CSV</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="ps-4 py-3 border-0 tiny text-muted fw-bold uppercase"
                                    style="letter-spacing: 1px;">Member Profile</th>
                                <th class="py-3 border-0 tiny text-muted fw-bold uppercase" style="letter-spacing: 1px;">
                                    Operational Batch</th>
                                <th class="py-3 border-0 tiny text-muted fw-bold uppercase" style="letter-spacing: 1px;">
                                    Ledger Category</th>
                                <th class="py-3 border-0 tiny text-muted fw-bold uppercase" style="letter-spacing: 1px;">
                                    Financial Metrics</th>
                                <th class="py-3 border-0 tiny text-muted fw-bold uppercase" style="letter-spacing: 1px;">
                                    Phase Status</th>
                                <th class="py-3 border-0 tiny text-muted fw-bold uppercase" style="letter-spacing: 1px;">
                                    Maturity Date</th>
                                <th class="pe-4 py-3 border-0 text-end tiny text-muted fw-bold uppercase"
                                    style="letter-spacing: 1px;">Operational Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingFees as $fee)
                                <tr class="transition-all hover-lift border-bottom border-light">
                                    <td class="ps-4 py-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-container position-relative me-3">
                                                @if($fee->student->photo)
                                                    <img src="{{ Storage::url($fee->student->photo) }}"
                                                        class="rounded-circle shadow-sm border-2 border-white" width="45"
                                                        height="45" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center shadow-sm border-2 border-white"
                                                        style="width: 45px; height: 45px;">
                                                        <span
                                                            class="fw-bold">{{ strtoupper(substr($fee->student->user->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $fee->student->user->name }}</div>
                                                <small class="text-muted tiny fw-bold uppercase">ID:
                                                    {{ $fee->student->roll_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <span
                                            class="badge bg-light text-dark border rounded-pill px-3 py-1 tiny fw-bold shadow-none">
                                            {{ $fee->student->batch->name ?? 'OFF-CYCLE' }}
                                        </span>
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-tag-fill text-success small"></i>
                                            </div>
                                            <span class="fw-bold text-dark small">{{ ucfirst($fee->fee_type) }}</span>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex flex-column">
                                            <span class="small text-muted fw-bold">TOTAL:
                                                ₹{{ number_format($fee->total_amount, 2) }}</span>
                                            <span class="small text-success fw-bold">PAID:
                                                ₹{{ number_format($fee->paid_amount, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div
                                            class="bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-4 d-inline-block border border-danger border-opacity-10">
                                            <span class="font-monospace fw-bold small">DUE:
                                                ₹{{ number_format($fee->remaining_amount, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div
                                            class="d-flex align-items-center {{ $fee->due_date < now() ? 'text-danger' : 'text-dark' }}">
                                            <i
                                                class="bi {{ $fee->due_date < now() ? 'bi-exclamation-octagon-fill animate-pulse' : 'bi-calendar-date' }} me-2"></i>
                                            <span class="small fw-bold">{{ $fee->due_date->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <a href="{{ route('school.payments.create', $fee) }}"
                                            class="btn btn-primary bg-gradient-brand border-0 rounded-pill px-4 py-2 small fw-bold shadow-sm transition-all hover-lift">
                                            <i class="bi bi-wallet2 me-1"></i> COLLECT
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-5 opacity-25">
                                            <i class="bi bi-check2-all display-1 d-block mb-3"></i>
                                            <h4 class="fw-bold text-dark">Institutional Dues Synchronized</h4>
                                            <p class="text-muted small">Excellent! No outstanding receivables found in the
                                                current operational frame.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    {{ $pendingFees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(0.9);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush