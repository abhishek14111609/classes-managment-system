@extends('layouts.app')

@section('title', 'Kit Sale History')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Sale History</h3>
                <p class="text-muted small mb-0">Track all kit sales and print invoices.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.inventory.items.index') }}"
                    class="btn btn-white rounded-pill px-4 shadow-sm border d-flex align-items-center">
                    <i class="bi bi-box-seam me-2"></i> Stock Management
                </a>
                <a href="{{ route('school.inventory.sales.create') }}"
                    class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i> Direct Sale
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Date</th>
                                <th class="py-3 border-0">Student</th>
                                <th class="py-3 border-0">Item Purchased</th>
                                <th class="py-3 border-0 text-center">Qty</th>
                                <th class="py-3 border-0">Amount</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="pe-4 py-3 border-0 text-end">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="fw-bold text-dark">{{ $sale->created_at->format('d M, Y') }}</div>
                                        <small class="text-muted tiny">{{ $sale->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="border-0">
                                        <div class="fw-bold text-dark">{{ $sale->student->user->name }}</div>
                                        <small class="text-muted tiny">ID: {{ $sale->student->student_id }}</small>
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-2">
                                                <i class="bi bi-bag-check-fill"></i>
                                            </div>
                                            <div class="fw-bold text-dark">{{ $sale->item->name }}</div>
                                        </div>
                                    </td>
                                    <td class="border-0 text-center fw-bold">{{ $sale->quantity }}</td>
                                    <td class="border-0">
                                        <div class="fw-bold text-primary">₹{{ number_format($sale->total_amount, 2) }}</div>
                                        <small class="text-muted tiny">₹{{ number_format($sale->unit_price, 2) }} / unit</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        @php
                                            $statusColors = ['paid' => 'success', 'pending' => 'warning', 'partial' => 'info'];
                                            $color = $statusColors[$sale->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }} rounded-pill text-capitalize px-3">
                                            {{ $sale->payment_status }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <a href="{{ route('school.inventory.sales.invoice', $sale) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-cart-x" style="font-size: 4rem;"></i></div>
                                        <h5 class="text-muted">No sales recordings found.</h5>
                                        <a href="{{ route('school.inventory.sales.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Make First Sale</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-white {
            background-color: #fff;
        }

        .tiny {
            font-size: 0.75rem;
        }
    </style>
@endsection