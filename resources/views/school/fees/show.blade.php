@extends('layouts.app')

@section('title', 'Institutional Ledger Breakdown')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Institutional Ledger Card</h3>
                <p class="text-muted small mb-0">Detailed breakdown of student financial obligations and transactional
                    history.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.fees.edit', $fee) }}"
                    class="btn btn-warning rounded-pill px-4 shadow-sm border-0 fw-bold small">
                    <i class="bi bi-pencil-square me-2"></i> Revise Ledger
                </a>
                @if($fee->paid_amount == 0)
                    <form action="{{ route('school.fees.destroy', $fee) }}" method="POST"
                        onsubmit="return confirm('Nullify this ledger entry?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="btn btn-light text-danger border rounded-pill px-4 shadow-sm fw-bold small">
                            <i class="bi bi-trash3 me-2"></i> Revoke
                        </button>
                    </form>
                @endif
                <a href="{{ route('school.fees.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-arrow-left me-2"></i> Collection Desk
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div class="small fw-bold">{{ session('success') }}</div>

                @if(session('open_invoice_id'))
                    <a href="{{ route('school.invoices.stream', session('open_invoice_id')) }}" target="_blank"
                        class="btn btn-sm btn-dark rounded-pill fw-bold ms-3 shadow-sm px-3" id="autoOpenInvoiceBtn">
                        <i class="bi bi-printer me-1"></i> Print Receipt
                    </a>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            window.open(document.getElementById('autoOpenInvoiceBtn').href, '_blank');
                        });
                    </script>
                @endif

                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Sidebar Summary -->
            <div class="col-md-4">
                <!-- Student Context -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative z-index-10">
                        <h6 class="tiny fw-bold text-muted text-uppercase mb-4" style="letter-spacing: 1px;">Stakeholder
                            Context</h6>
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 800;">
                                {{ substr($fee->student->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ $fee->student->user->name }}</h6>
                                <p class="text-muted tiny fw-bold mb-0">ENROLL: #{{ $fee->student->roll_number }}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <div class="p-2 rounded-3 bg-light border border-white">
                                <small class="text-muted tiny d-block">Primary Batch</small>
                                <span
                                    class="small fw-bold text-dark">{{ optional($fee->student->batch)->name ?? 'No Primary Batch' }}</span>
                            </div>
                            @if($fee->batch)
                                <div class="p-2 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                                    <small class="text-primary tiny d-block">Linked Session (Fee Item)</small>
                                    <span class="small fw-bold text-primary"><i class="bi bi-tag-fill me-1"></i>
                                        {{ $fee->batch->name }}</span>
                                </div>
                            @endif
                            <div class="p-2 rounded-3 bg-light border border-white">
                                <small class="text-muted tiny d-block">Communication Channel</small>
                                <span class="small fw-bold text-dark">{{ $fee->student->user->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-primary position-absolute top-0 end-0 w-25 h-100 opacity-5"
                        style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
                </div>

                <!-- Financial Analysis -->
                <div class="card border-0 shadow-sm rounded-4 mb-4"
                    style="border-bottom: 4px solid var(--{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }}) !important;">
                    <div class="card-body p-4">
                        <h6 class="tiny fw-bold text-muted text-uppercase mb-4" style="letter-spacing: 1px;">Asset Breakdown
                        </h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted">Total Obligation:</span>
                                <span class="small fw-bold text-dark">₹{{ number_format($fee->total_amount, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted">Settled Capital:</span>
                                <span class="small fw-bold text-success">₹{{ number_format($fee->paid_amount, 0) }}</span>
                            </div>
                            @if($fee->late_fee > 0)
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Late Penalty:</span>
                                    <span class="small fw-bold text-danger">₹{{ number_format($fee->late_fee, 0) }}</span>
                                </div>
                            @endif
                            <hr class="my-1 opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Institutional Dues:</span>
                                <span
                                    class="h5 mb-0 fw-bold text-primary">₹{{ number_format($fee->getRemainingAmount(), 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="small text-muted">Dossier Status:</span>
                                @php
                                    $statusMap = [
                                        'paid' => ['label' => 'SETTLED', 'color' => 'success'],
                                        'partial' => ['label' => 'PARTIAL', 'color' => 'warning'],
                                        'overdue' => ['label' => 'CRITICAL', 'color' => 'danger'],
                                        'pending' => ['label' => 'ACTIVE', 'color' => 'secondary']
                                    ][$fee->status] ?? ['label' => 'UNKNOWN', 'color' => 'dark'];
                                @endphp
                                <span
                                    class="badge bg-{{ $statusMap['color'] }} rounded-pill px-3 py-1 tiny fw-bold">{{ $statusMap['label'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Transaction Log -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i> Asset
                            Settlement History</h6>
                        @if($fee->status !== 'paid')
                            <button type="button" class="btn btn-soft-success btn-sm rounded-pill px-4 fw-bold"
                                data-bs-toggle="modal" data-bs-target="#paymentModal">
                                <i class="bi bi-plus-lg me-1"></i> Record Settlement
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if($fee->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="tiny text-muted text-uppercase fw-bold">
                                            <th class="ps-4">Chronology</th>
                                            <th>Disbursed Capital</th>
                                            <th>Settlement Mode</th>
                                            <th>Auth ID</th>
                                            <th class="pe-4 text-end">Administration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fee->payments as $payment)
                                            <tr class="transition-all hover-lift">
                                                <td class="ps-4">
                                                    <div class="small fw-bold text-dark">{{ $payment->paid_at->format('d M, Y') }}
                                                    </div>
                                                    <small class="text-muted tiny">{{ $payment->paid_at->format('h:i A') }}</small>
                                                </td>
                                                <td><span class="small fw-bold text-success">+
                                                        ₹{{ number_format($payment->amount, 0) }}</span></td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info rounded-pill px-3 py-1 tiny fw-bold">
                                                        {{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}
                                                    </span>
                                                </td>
                                                <td><small
                                                        class="text-muted tiny fw-bold">#{{ $payment->transaction_id ?? 'N/A' }}</small>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <form action="{{ route('school.payments.destroy', $payment) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Reverse this settlement? Linked invoice will also be revoked.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-white border-0 text-danger px-3"
                                                            title="Reverse Transaction">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="opacity-10 mb-3"><i class="bi bi-wallet2" style="font-size: 4rem;"></i></div>
                                <h6 class="text-muted fw-bold">Zero asset settlements identified in this lifecycle.</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inline Payment Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0 px-4 py-4">
                    <h5 class="modal-title fw-bold">Record Asset Settlement</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('school.payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="fee_id" value="{{ $fee->id }}">

                        <div class="bg-light rounded-4 p-3 mb-4 d-flex justify-content-between">
                            <span class="small fw-bold text-muted">Outstanding Obligation:</span>
                            <span class="fw-bold text-danger">₹{{ number_format($fee->getRemainingAmount(), 0) }}</span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Settlement Capital <span
                                    class="text-danger">*</span></label>
                            <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                <span class="input-group-text bg-transparent border-0 fw-bold">₹</span>
                                <input type="number" class="form-control bg-transparent border-0 shadow-none fw-bold"
                                    name="amount" step="0.01" max="{{ $fee->getRemainingAmount() }}" required
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Transit Mode <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded-pill shadow-none border small fw-bold"
                                    name="payment_method" required>
                                    <option value="cash">Institutional Cash</option>
                                    <option value="bank_transfer">Digital Transfer</option>
                                    <option value="card">Card Terminal</option>
                                    <option value="cheque">Physical Cheque</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Track
                                    Reference</label>
                                <input type="text" class="form-control rounded-pill shadow-none border small fw-bold"
                                    name="transaction_id" placeholder="REF-0000">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Ledger Annotations</label>
                            <textarea class="form-control rounded-4 shadow-none border small" name="notes" rows="2"
                                placeholder="Notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 text-center">
                        <button type="submit"
                            class="btn btn-primary rounded-pill px-5 py-2 fw-bold w-100 shadow-sm grow">Confirm Asset
                            Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .btn-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .btn-soft-success:hover {
            background-color: #198754;
            color: #fff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-white {
            background-color: #fff;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .grow:hover {
            transform: scale(1.01);
        }
    </style>
@endsection