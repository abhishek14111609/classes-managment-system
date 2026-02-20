@extends('layouts.app')

@section('title', 'Expense Detail — ' . $expense->title)

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Expense Detail</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('school.expenses.edit', $expense) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('school.expenses.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Expense Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="text-muted" width="40%">Title</th>
                                <td class="fw-semibold">{{ $expense->title }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Category</th>
                                <td><span class="badge bg-secondary">{{ ucfirst($expense->category) }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Amount</th>
                                <td class="text-danger fw-bold fs-5">₹{{ number_format($expense->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Expense Date</th>
                                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Recorded By</th>
                                <td>{{ optional($expense->createdBy)->name ?? 'System' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Created At</th>
                                <td>{{ $expense->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @if($expense->description)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-card-text me-2"></i>Description</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 text-muted">{{ $expense->description }}</p>
                        </div>
                    </div>
                @endif

                @if($expense->receipt)
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-paperclip me-2"></i>Receipt</h6>
                        </div>
                        <div class="card-body text-center">
                            @php $ext = pathinfo($expense->receipt, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <img src="{{ Storage::url($expense->receipt) }}" class="img-fluid rounded" alt="Receipt">
                            @else
                                <a href="{{ Storage::url($expense->receipt) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Download Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-4 text-muted">
                            <i class="bi bi-paperclip fs-2 opacity-25 d-block mb-2"></i>
                            No receipt attached to this expense.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('school.expenses.destroy', $expense) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this expense?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Delete Expense
                </button>
            </form>
        </div>
    </div>
@endsection