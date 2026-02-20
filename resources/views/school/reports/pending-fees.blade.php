@extends('layouts.app')

@section('title', 'Pending Fees List')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Fees Pending List</h2>
            <div class="text-end">
                <p class="text-muted mb-0">Total Outstanding:
                    <strong>₹{{ number_format($pendingFees->sum('remaining_amount'), 2) }}</strong>
                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Student</th>
                                <th>Batch</th>
                                <th>Fee Type</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Pending</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingFees as $fee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($fee->student->photo)
                                                <img src="{{ Storage::url($fee->student->photo) }}" class="rounded-circle me-2"
                                                    width="35" height="35" alt="">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px;">
                                                    {{ strtoupper(substr($fee->student->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $fee->student->user->name }}</div>
                                                <small class="text-muted">ID: {{ $fee->student->roll_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $fee->student->batch->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($fee->fee_type) }}</td>
                                    <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                                    <td class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td class="text-danger font-monospace fw-bold">
                                        ₹{{ number_format($fee->total_amount - $fee->paid_amount, 2) }}</td>
                                    <td>
                                        <span class="text-{{ $fee->due_date < now() ? 'danger' : 'muted' }}">
                                            {{ $fee->due_date->format('M d, Y') }}
                                            @if($fee->due_date < now())
                                                <i class="bi bi-exclamation-triangle-fill" title="Overdue"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('school.payments.create', $fee) }}"
                                            class="btn btn-sm btn-outline-success">
                                            Collect Payment
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Excellent! No pending fees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $pendingFees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection