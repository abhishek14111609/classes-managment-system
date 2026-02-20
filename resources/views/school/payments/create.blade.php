@extends('layouts.app')

@section('title', 'Record Payment')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Record Payment</h2>
            <a href="{{ route('school.fees.show', $fee) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Fee
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Fee Summary Card --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Fee Summary</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0 small">
                            <tr>
                                <th class="text-muted" width="50%">Student</th>
                                <td class="fw-semibold">{{ $fee->student->user->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Fee Type</th>
                                <td>{{ ucfirst(str_replace('_', '-', $fee->fee_type)) }}
                                    @if($fee->sport_level)
                                        <span class="badge bg-primary ms-1">{{ ucfirst($fee->sport_level) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Total Amount</th>
                                <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                            </tr>
                            @if($fee->discount > 0)
                                <tr>
                                    <th class="text-muted">Discount</th>
                                    <td class="text-success">− ₹{{ number_format($fee->discount, 2) }}</td>
                                </tr>
                            @endif
                            @if($fee->late_fee > 0)
                                <tr>
                                    <th class="text-muted">Late Fee</th>
                                    <td class="text-danger">+ ₹{{ number_format($fee->late_fee, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-muted">Already Paid</th>
                                <td class="text-success fw-semibold">₹{{ number_format($fee->paid_amount, 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <th>Remaining Balance</th>
                                <td class="fw-bold text-primary fs-6">₹{{ number_format($fee->getRemainingAmount(), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Due Date</th>
                                <td @if($fee->due_date->isPast() && $fee->status !== 'paid') class="text-danger" @endif>
                                    {{ $fee->due_date->format('d M Y') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Payment Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('school.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="fee_id" value="{{ $fee->id }}">

                            <div class="mb-3">
                                <label for="amount" class="form-label fw-semibold">
                                    Payment Amount (₹) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                        id="amount" name="amount" value="{{ old('amount', $fee->getRemainingAmount()) }}"
                                        step="0.01" min="0.01" max="{{ $fee->getRemainingAmount() }}" required>
                                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text text-muted">
                                    Maximum payable: ₹{{ number_format($fee->getRemainingAmount(), 2) }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label fw-semibold">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('payment_method') is-invalid @enderror"
                                    id="payment_method" name="payment_method" required>
                                    <option value="">— Select Method —</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>💵 Cash
                                    </option>
                                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                    <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>💳 Card
                                    </option>
                                    <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>📝
                                        Cheque</option>
                                    <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>📱 UPI
                                    </option>
                                </select>
                                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="paid_at" class="form-label fw-semibold">Payment Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('paid_at') is-invalid @enderror" id="paid_at"
                                    name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" required>
                                @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="transaction_id" class="form-label fw-semibold">Transaction / Reference
                                    ID</label>
                                <input type="text" class="form-control @error('transaction_id') is-invalid @enderror"
                                    id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}"
                                    placeholder="e.g. UPI Ref No., Cheque No...">
                                @error('transaction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                    rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check2-circle me-2"></i> Record Payment
                                </button>
                                <a href="{{ route('school.fees.show', $fee) }}" class="btn btn-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection