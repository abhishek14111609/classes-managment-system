@extends('layouts.app')

@section('title', 'Fee Details')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Fee Details</h2>
            <a href="{{ route('student.fees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Fee Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Fee Category:</span>
                            @php
                                $typeMap = [
                                    'tuition' => 'bg-primary',
                                    'sports' => 'bg-success',
                                    'transport' => 'bg-warning text-dark',
                                    'exam' => 'bg-danger',
                                    'library' => 'bg-info text-dark',
                                    'other' => 'bg-secondary',
                                ];
                                $durationMap = [
                                    'monthly' => 'Monthly',
                                    'quarterly' => 'Quarterly',
                                    'half_yearly' => 'Half Yearly',
                                    'annual' => 'Annual',
                                    'one_time' => 'One-Time',
                                ];
                                $typeBadge = $typeMap[$fee->fee_type] ?? 'bg-secondary';
                                $durationLabel = $durationMap[$fee->duration] ?? null;
                            @endphp
                            <strong><span
                                    class="badge {{ $typeBadge }}">{{ ucwords(str_replace('_', ' ', $fee->fee_type)) }}</span></strong>
                        </div>
                        @if($durationLabel)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Duration:</span>
                                <strong>{{ $durationLabel }}</strong>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Due Date:</span>
                            <strong>{{ $fee->due_date->format('M d, Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status:</span>
                            @php
                                $statusClass = [
                                    'paid' => 'success',
                                    'partial' => 'warning',
                                    'pending' => 'info',
                                    'overdue' => 'danger'
                                ][$fee->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                {{ ucfirst($fee->status) }}
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Amount:</span>
                            <strong>₹{{ number_format($fee->total_amount, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Late Fee:</span>
                            <strong class="text-danger">+ ₹{{ number_format($fee->late_fee, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount:</span>
                            <strong class="text-success">- ₹{{ number_format($fee->discount, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fs-5">Net Payable:</span>
                            <span
                                class="fs-5 fw-bold">₹{{ number_format($fee->total_amount + $fee->late_fee - $fee->discount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Paid:</span>
                            <strong class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <span class="fs-5 text-primary">Remaining:</span>
                            <span
                                class="fs-5 fw-bold text-primary">₹{{ number_format($fee->getRemainingAmount(), 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($fee->remarks)
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Admin Remarks</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 text-muted italic small">{{ $fee->remarks }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Receipt #</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fee->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td><code>{{ $payment->receipt_number }}</code></td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No payments recorded for this
                                                fee.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Invoices</h5>
                    </div>
                    <div class="card-body">
                        @forelse($fee->invoices as $invoice)
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $invoice->invoice_number }}</h6>
                                    <small class="text-muted">Issued on: {{ $invoice->created_at->format('M d, Y') }}</small>
                                </div>
                                <a href="{{ route('school.invoices.download', $invoice->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3 mb-0">No invoices generated yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection