@extends('layouts.app')

@section('title', 'Institutional Revenue Control')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Collection Desk</h3>
                <p class="text-muted small mb-0">Monitor student financial standing, record revenue, and manage
                    institutional receivables.</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('school.fee-plans.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm hover-lift d-flex align-items-center fw-bold small">
                    <i class="bi bi-shield-check me-2 text-primary"></i> Billing Framework
                </a>
                <a href="{{ route('school.fees.create') }}"
                    class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center fw-bold small">
                    <i class="bi bi-plus-lg me-2"></i> Issue New Ledger
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div class="small fw-bold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Financial Summary Dashboard -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <i class="bi bi-receipt fs-3 opacity-50"></i>
                            <span class="badge bg-white bg-opacity-20 rounded-pill tiny fw-bold">TOTAL EXPECTED</span>
                        </div>
                        <h2 class="fw-bold mb-1">₹{{ number_format($stats['total_expected'], 0) }}</h2>
                        <p class="mb-0 opacity-75 small fw-semibold text-uppercase">Projected Revenue Pool</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-success bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <i class="bi bi-cash-coin fs-3 opacity-50"></i>
                            <span class="badge bg-white bg-opacity-20 rounded-pill tiny fw-bold">TOTAL COLLECTED</span>
                        </div>
                        <h2 class="fw-bold mb-1">₹{{ number_format($stats['total_paid'], 0) }}</h2>
                        <p class="mb-0 opacity-75 small fw-semibold text-uppercase">Secured Institutional Asset</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-danger bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <i class="bi bi-exclamation-octagon fs-3 opacity-50"></i>
                            <span class="badge bg-white bg-opacity-20 rounded-pill tiny fw-bold">OUTSTANDING DUE</span>
                        </div>
                        <h2 class="fw-bold mb-1">₹{{ number_format($stats['total_outstanding'], 0) }}</h2>
                        <p class="mb-0 opacity-75 small fw-semibold text-uppercase">Accounts Receivable Balance</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search & Filter Area -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4 bg-light bg-opacity-50">
                <form action="{{ route('school.fees.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Ledger Status</label>
                        <select name="status" class="form-select rounded-pill px-3 shadow-none border small fw-bold"
                            onchange="this.form.submit()">
                            <option value="">All Institutional Dues</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Settlement
                            </option>
                            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partially Settled
                            </option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Fully Settled</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue Accounts
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Student Identification</label>
                        <div class="input-group bg-white rounded-pill px-3 py-1 border shadow-sm">
                            <span class="input-group-text bg-transparent border-0"><i
                                    class="bi bi-search text-muted small"></i></span>
                            <input type="text" name="search"
                                class="form-control bg-transparent border-0 shadow-none tiny fw-bold"
                                placeholder="Locate student by name or unique enrollment ID..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Apply
                            Analysis</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Collection Ledger Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="tiny text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Student Context</th>
                                <th class="py-3 border-0">Artifact / Cycle</th>
                                <th class="py-3 border-0">Financial Breakdown</th>
                                <th class="py-3 border-0">Institutional Dues</th>
                                <th class="py-3 border-0 text-center">Lifecycle</th>
                                <th class="pe-4 py-3 border-0 text-end">Administration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fees as $fee)
                                <tr class="hover-lift transition-all border-bottom border-light">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center me-3"
                                                style="width: 45px; height: 45px;">
                                                <span class="fw-bold">{{ substr($fee->student->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $fee->student->user->name }}</div>
                                                <small class="text-muted tiny fw-bold">ENROLL:
                                                    #{{ $fee->student->roll_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @php
                                            $typeMap = [
                                                'tuition' => ['label' => 'Tuition', 'color' => 'soft-primary'],
                                                'sports' => ['label' => 'Athletics', 'color' => 'soft-success'],
                                                'transport' => ['label' => 'Transit', 'color' => 'soft-warning'],
                                                'exam' => ['label' => 'Academic Exam', 'color' => 'soft-danger'],
                                                'library' => ['label' => 'Resource', 'color' => 'soft-info'],
                                                'other' => ['label' => 'General', 'color' => 'soft-secondary'],
                                            ];
                                            $durationMap = ['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'half_yearly' => 'Semi-Annual', 'annual' => 'Term Annual', 'one_time' => 'Lump Sum'];
                                            $style = $typeMap[$fee->fee_type] ?? ['label' => 'Misc', 'color' => 'soft-secondary'];
                                        @endphp
                                        <div class="d-flex flex-column gap-1">
                                            <span
                                                class="badge bg-{{ $style['color'] }} px-2 py-1 tiny fw-bold w-fit rounded-pill">{{ $style['label'] }}</span>
                                            @if($fee->batch)
                                                <small class="text-primary tiny fw-bold"><i class="bi bi-tag-fill me-1"></i>
                                                    {{ $fee->batch->name }}</small>
                                            @endif
                                            @if($fee->duration)
                                                <small class="text-muted tiny fw-bold"><i class="bi bi-calendar2-range me-1"></i>
                                                    {{ $durationMap[$fee->duration] ?? 'Standard' }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">Total: ₹{{ number_format($fee->total_amount, 0) }}
                                        </div>
                                        @if($fee->paid_amount > 0)
                                            <div class="tiny text-success fw-bold">Cleared:
                                                ₹{{ number_format($fee->paid_amount, 0) }}</div>
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        @php $remaining = $fee->getRemainingAmount(); @endphp
                                        <div class="h6 fw-bold mb-0 {{ $remaining > 0 ? 'text-danger' : 'text-muted' }}">
                                            ₹{{ number_format($remaining, 0) }}
                                        </div>
                                        <small class="text-muted tiny fw-bold">DUE:
                                            {{ $fee->due_date->format('d M, Y') }}</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        @php
                                            $statusClass = [
                                                'paid' => ['label' => 'SETTLED', 'color' => 'success'],
                                                'partial' => ['label' => 'PARTIAL', 'color' => 'warning'],
                                                'overdue' => ['label' => 'CRITICAL', 'color' => 'danger'],
                                                'pending' => ['label' => 'ACTIVE', 'color' => 'secondary']
                                            ][$fee->status] ?? ['label' => 'UNKNOWN', 'color' => 'dark'];
                                        @endphp
                                        <span
                                            class="badge bg-{{ $statusClass['color'] }} rounded-pill px-3 py-1 tiny fw-bold shadow-none">
                                            {{ $statusClass['label'] }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden bg-white border">
                                            <a href="{{ route('school.fees.show', $fee) }}"
                                                class="btn btn-sm btn-white border-0 px-3" title="Audit Trail">
                                                <i class="bi bi-layout-text-sidebar-reverse text-info"></i>
                                            </a>
                                            @if($fee->status !== 'paid')
                                                <button type="button" class="btn btn-sm btn-white border-0 px-3 pay-btn"
                                                    data-fee-id="{{ $fee->id }}" data-student="{{ $fee->student->user->name }}"
                                                    data-remaining="{{ $remaining }}" title="Record Settlement">
                                                    <i class="bi bi-currency-rupee text-success fw-bold"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-white border-0 px-3"
                                                onclick="if(confirm('Revoke this ledger entry?')) document.getElementById('delete-form-{{ $fee->id }}').submit();"
                                                title="Revoke Fee">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $fee->id }}" action="{{ route('school.fees.destroy', $fee) }}"
                                            method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-receipt-cutoff"
                                                style="font-size: 5rem;"></i></div>
                                        <h5 class="text-muted fw-bold">Institutional Ledger is currently empty.</h5>
                                        <a href="{{ route('school.fees.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Initialize First
                                            Settlement</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $fees->links() }}
        </div>
    </div>

    {{-- Payment Gateway Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-gradient-primary text-white border-0 px-4 py-4">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-wallet2 me-2"></i> Record Asset Settlement
                    </h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('school.payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="modal-body p-4">
                        <input type="hidden" name="fee_id" id="modal_fee_id">

                        <div class="bg-light rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Student
                                    Obligation</small>
                                <h6 class="fw-bold text-dark mb-0" id="modal_student_name">—</h6>
                            </div>
                            <div class="text-end">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Remaining Asset</small>
                                <h6 class="fw-bold text-danger mb-0" id="modal_remaining_display">₹0.00</h6>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Settlement Capital <span
                                    class="text-danger">*</span></label>
                            <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                <span class="input-group-text bg-transparent border-0 fw-bold text-muted">₹</span>
                                <input type="number" class="form-control bg-transparent border-0 shadow-none fw-bold"
                                    name="amount" id="modal_amount" step="0.01" min="0.01" required placeholder="0.00">
                                <button type="button" class="btn btn-primary rounded-pill px-3 py-1 small fw-bold"
                                    id="payFullBtn">Full Settlement</button>
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
                                    <option value="upi">UPI Gateway</option>
                                    <option value="cheque">Physical Cheque</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Track
                                    Reference</label>
                                <input type="text" class="form-control rounded-pill shadow-none border small fw-bold"
                                    name="transaction_id" placeholder="REF-#000000">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Financial
                                annotations</label>
                            <textarea class="form-control rounded-4 shadow-none border small" name="notes" rows="3"
                                placeholder="Additional ledger details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small"
                            data-bs-dismiss="modal">Abort</button>
                        <button type="submit" class="btn btn-primary grow rounded-pill px-5 py-2 fw-bold shadow-sm">
                            Confirm Asset Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        .btn-white {
            background-color: #fff;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }

        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .grow {
            flex-grow: 1;
        }

        .w-fit {
            width: fit-content;
        }
    </style>

    @push('scripts')
        <script>
            let modalRemainingVal = 0;
            document.querySelectorAll('.pay-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const feeId = this.dataset.feeId;
                    const student = this.dataset.student;
                    const remaining = parseFloat(this.dataset.remaining) || 0;
                    modalRemainingVal = remaining;

                    document.getElementById('modal_fee_id').value = feeId;
                    document.getElementById('modal_student_name').textContent = student;
                    document.getElementById('modal_remaining_display').textContent = '₹' + remaining.toLocaleString();
                    document.getElementById('modal_amount').value = '';
                    document.getElementById('modal_amount').max = remaining;

                    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                    modal.show();
                });
            });

            document.getElementById('payFullBtn').addEventListener('click', function () {
                document.getElementById('modal_amount').value = modalRemainingVal.toFixed(2);
            });
        </script>
    @endpush
@endsection