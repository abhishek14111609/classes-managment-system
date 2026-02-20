@extends('layouts.app')

@section('title', 'My Fees')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Fees & Payments</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Due Date</th>
                                <th>Fee Type</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->due_date->format('M d, Y') }}</td>
                                    <td>
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
                                            $typeLabel = ucwords(str_replace('_', ' ', $fee->fee_type));
                                            $durationLabel = $durationMap[$fee->duration] ?? null;
                                        @endphp
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge {{ $typeBadge }} w-fit">{{ $typeLabel }}</span>
                                            @if($durationLabel)
                                                <small class="text-muted"><i
                                                        class="bi bi-clock me-1"></i>{{ $durationLabel }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                                    <td>₹{{ number_format($fee->paid_amount, 2) }}</td>
                                    <td>₹{{ number_format($fee->total_amount - $fee->paid_amount, 2) }}</td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="{{ route('student.fees.show', $fee->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No fee records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $fees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection