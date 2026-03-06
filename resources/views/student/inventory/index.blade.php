@extends('layouts.app')

@section('title', 'My Kit Purchases')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 fw-bold mb-1 small text-uppercase" style="letter-spacing: 1px;">Store History</h6>
                            <h2 class="fw-bold mb-0 display-6">Equipment & Kits</h2>
                        </div>
                        <div class="bg-primary bg-opacity-20 p-3 rounded-circle border border-primary border-opacity-25">
                            <i class="bi bi-cart-check text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Table -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Order History</h5>
                        <div class="text-muted small">Showing your recent gear purchases</div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 small">DATE</th>
                                        <th class="small">ITEM DETAILS</th>
                                        <th class="small text-center">QTY</th>
                                        <th class="small">AMOUNT</th>
                                        <th class="small text-center">STATUS</th>
                                        <th class="small pe-4 text-end">INVOICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $sale)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $sale->created_at->format('d M, Y') }}</div>
                                                <div class="tiny text-muted fw-semibold">{{ $sale->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-2">
                                                        <i class="bi bi-bag-check-fill"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $sale->item->name }}</div>
                                                        <div class="tiny text-muted">{{ $sale->item->category ?? 'General Equipment' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold">{{ $sale->quantity }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">₹{{ number_format($sale->total_amount, 2) }}</div>
                                                <div class="tiny text-muted">₹{{ number_format($sale->unit_price, 2) }} / unit</div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusTheme = [
                                                        'paid' => 'success',
                                                        'pending' => 'warning',
                                                        'partial' => 'info'
                                                    ][$sale->payment_status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusTheme }}-subtle text-{{ $statusTheme }} rounded-pill px-3 border-0 text-capitalize">{{ $sale->payment_status }}</span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('student.inventory.invoice', $sale->id) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold tiny">
                                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="opacity-25 mb-3">
                                                    <i class="bi bi-cart-x display-1"></i>
                                                </div>
                                                <p class="text-muted small">You haven't purchased any kits or equipment yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Boxes -->
        <div class="row g-4 mb-5 pb-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10 h-100">
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="bg-primary bg-opacity-20 p-3 rounded-4 me-3 text-primary">
                            <i class="bi bi-patch-check fs-2"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark">Quality Assurance</h6>
                            <p class="text-muted small mb-0">All kits and equipment provided by the institute are certified for institutional standards and safety.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-info bg-opacity-10 h-100">
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="bg-info bg-opacity-20 p-3 rounded-4 me-3 text-info">
                            <i class="bi bi-arrow-repeat fs-2"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark">Exchange Policy</h6>
                            <p class="text-muted small mb-0">For sizing issues or defects, please bring your receipt and the item to the administrator within 48 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
