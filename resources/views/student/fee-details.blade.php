@extends('layouts.app')

@section('title', 'Statement of Account')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Header Area -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-receipt-cutoff text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Fee Structural Detail</h4>
                                <p class="text-white-50 mb-0 small">Reference ID: {{ strtoupper(str_replace('_', '-', $fee->fee_type)) }}-{{ $fee->id }}</p>
                            </div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('student.fees.index') }}" class="btn btn-light bg-white border-0 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="bi bi-arrow-left me-1"></i> Return to Wallet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Master Breakdown -->
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Term Summary</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ ucfirst($fee->duration) }} Cycle</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4 py-3 bg-light rounded-4 border border-dashed">
                            @php
                                $statusClass = [
                                    'paid' => 'success',
                                    'partial' => 'warning',
                                    'pending' => 'info',
                                    'overdue' => 'danger',
                                ][$fee->status] ?? 'secondary';
                            @endphp
                            <h6 class="text-muted tiny fw-bold text-uppercase mb-2" style="letter-spacing: 2px;">Verification Status</h6>
                            <h3 class="fw-bold text-{{ $statusClass }} mb-0">{{ strtoupper($fee->status) }}</h3>
                        </div>

                        <div class="d-grid gap-3">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light bg-opacity-50">
                                <span class="text-muted small fw-bold">Base Assessment</span>
                                <span class="fw-bold text-dark">₹{{ number_format($fee->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light bg-opacity-50">
                                <div>
                                    <span class="text-muted small fw-bold">Adjustment Benefit</span>
                                    <small class="d-block text-success tiny">Scholarship / Voucher</small>
                                </div>
                                <span class="fw-bold text-success">- ₹{{ number_format($fee->discount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light bg-opacity-50">
                                <div>
                                    <span class="text-muted small fw-bold">Surcharge (Late)</span>
                                    <small class="d-block text-danger tiny">Temporal Delay Penalty</small>
                                </div>
                                <span class="fw-bold text-danger">+ ₹{{ number_format($fee->late_fee, 2) }}</span>
                            </div>
                            <hr class="my-2 opacity-10">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                                <span class="text-primary fw-bold">Net Total Liability</span>
                                <span class="h4 fw-bold text-primary mb-0">₹{{ number_format($fee->total_amount + $fee->late_fee - $fee->discount, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center p-3 rounded-4 bg-success bg-opacity-10">
                                        <small class="text-success tiny fw-bold d-block text-uppercase mb-1">Total Cleared</small>
                                        <h5 class="fw-bold text-success mb-0">₹{{ number_format($fee->paid_amount, 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 rounded-4 bg-danger bg-opacity-10">
                                        <small class="text-danger tiny fw-bold d-block text-uppercase mb-1">Outstanding</small>
                                        <h5 class="fw-bold text-danger mb-0">₹{{ number_format($fee->getRemainingAmount(), 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Context -->
                @if($fee->remarks)
                    <div class="card border-0 shadow-sm rounded-4 bg-light">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white p-2 rounded-3 me-3">
                                    <i class="bi bi-sticky-fill text-warning"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Official Remarks</h6>
                            </div>
                            <p class="text-muted small mb-0 fs-italic">"{{ $fee->remarks }}"</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Transaction Registry -->
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Payment Verification History</h5>
                        <i class="bi bi-shield-check text-success fs-4"></i>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 small">DATE</th>
                                        <th class="small">RECEIPT NO</th>
                                        <th class="small">METHOD</th>
                                        <th class="pe-4 text-end small">AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fee->payments as $payment)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark small">{{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : 'N/A' }}</td>
                                            <td><code class="text-primary fw-bold">{{ $payment->receipt_number }}</code></td>
                                            <td>
                                                <span class="badge bg-light text-dark border rounded-pill px-3">{{ ucfirst($payment->payment_method) }}</span>
                                            </td>
                                            <td class="pe-4 text-end fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="opacity-50 mb-2"><i class="bi bi-inbox display-4"></i></div>
                                                <p class="text-muted small mb-0">No biometric or manual payments recorded for this term.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Invoice Vault -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Generated Invoices Vault</h5>
                        <i class="bi bi-safe2 text-primary opacity-50 fs-4"></i>
                    </div>
                    <div class="card-body p-4">
                        @forelse($fee->invoices as $invoice)
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-light border border-dashed mb-3 transition-all hover-lift">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white p-2 rounded-3 shadow-sm me-3 text-primary">
                                        <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $invoice->invoice_number }}</h6>
                                        <small class="text-muted tiny text-uppercase">TIMESTAMP: {{ $invoice->created_at->format('d M, Y • H:i') }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('school.invoices.download', $invoice->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-download me-1"></i> PDF
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small border border-dashed rounded-4">
                                <i class="bi bi-journal-x d-block fs-3 opacity-25 mb-2"></i>
                                Digital invoice records are currently unavailable.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
