@extends('layouts.app')

@section('title', 'Institutional Expenditure Registry')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Expenditure Tracking</h3>
                <p class="text-muted small mb-0">Monitor institutional overheads, salaries, and operational costs.</p>
            </div>
            <a href="{{ route('school.expenses.create') }}"
                class="btn btn-danger rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-dash-circle me-2"></i> Log New Expenditure
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div class="small fw-bold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search & Analysis Bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 bg-light bg-opacity-50">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Category Filter</label>
                        <form action="{{ route('school.expenses.index') }}" method="GET">
                            <select name="category" class="form-select rounded-pill px-3 shadow-none border small fw-bold"
                                onchange="this.form.submit()">
                                <option value="">All Institutional Overheads</option>
                                <option value="salary" {{ request('category') === 'salary' ? 'selected' : '' }}>Faculty &
                                    Staff Salaries</option>
                                <option value="maintenance" {{ request('category') === 'maintenance' ? 'selected' : '' }}>
                                    Facility Maintenance</option>
                                <option value="utilities" {{ request('category') === 'utilities' ? 'selected' : '' }}>Public
                                    Utilities (Power/Water)</option>
                                <option value="supplies" {{ request('category') === 'supplies' ? 'selected' : '' }}>
                                    Educational Supplies</option>
                                <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Miscellaneous
                                    Costs</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Cycle Analysis</label>
                        <form action="{{ route('school.expenses.index') }}" method="GET">
                            <input type="month" name="month"
                                class="form-control rounded-pill px-3 shadow-none border small fw-bold"
                                value="{{ request('month') }}" onchange="this.form.submit()">
                        </form>
                    </div>
                    <div class="col-md-5 d-flex align-items-end justify-content-end">
                        <div class="bg-white border rounded-pill px-4 py-2 shadow-sm d-flex align-items-center">
                            <span class="tiny fw-bold text-muted text-uppercase me-3">Period Aggregate:</span>
                            <span
                                class="h5 mb-0 fw-bold text-danger">₹{{ number_format($expenses->sum('amount'), 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="tiny text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Expenditure Title</th>
                                <th class="py-3 border-0">Classification</th>
                                <th class="py-3 border-0">Disbursed Capital</th>
                                <th class="py-3 border-0">Transaction Date</th>
                                <th class="py-3 border-0">Annotation</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger me-3">
                                                <i class="bi bi-wallet2 fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $expense->title }}</div>
                                                <small class="text-muted tiny">AUTH: Institutional Petty Cash</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @php
                                            $colorMap = [
                                                'salary' => 'soft-primary',
                                                'maintenance' => 'soft-warning',
                                                'utilities' => 'soft-info',
                                                'supplies' => 'soft-success',
                                                'other' => 'soft-secondary'
                                            ];
                                            $catStyle = $colorMap[$expense->category] ?? 'soft-secondary';
                                        @endphp
                                        <span class="badge bg-{{ $catStyle }} px-3 py-2 rounded-pill tiny fw-bold">
                                            {{ ucfirst($expense->category) }}
                                        </span>
                                    </td>
                                    <td class="border-0">
                                        <span class="text-danger fw-bold">₹{{ number_format($expense->amount, 0) }}</span>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $expense->expense_date->format('d M, Y') }}
                                        </div>
                                        <small class="text-muted tiny">Settlement reference</small>
                                    </td>
                                    <td class="border-0">
                                        <div class="text-muted small text-truncate" style="max-width: 200px;"
                                            title="{{ $expense->description }}">
                                            {{ $expense->description ?? 'No granular annotation provided.' }}
                                        </div>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden border bg-white">
                                            <a href="{{ route('school.expenses.edit', $expense) }}"
                                                class="btn btn-sm btn-white border-0 px-3" title="Revise Transaction">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0 px-3"
                                                onclick="if(confirm('Nullify this expenditure record?')) document.getElementById('delete-form-{{ $expense->id }}').submit();"
                                                title="Void Transaction">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $expense->id }}"
                                            action="{{ route('school.expenses.destroy', $expense) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-10 mb-3"><i class="bi bi-box-seam" style="font-size: 5rem;"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No institutional expenditures identified in this cycle.
                                        </h5>
                                        <a href="{{ route('school.expenses.create') }}"
                                            class="btn btn-sm btn-danger rounded-pill px-4 mt-2">Log Initial Expense</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $expenses->links() }}
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

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .text-gradient {
            background: linear-gradient(135deg, #f85032 0%, #e73827 100%);
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
    </style>
@endsection