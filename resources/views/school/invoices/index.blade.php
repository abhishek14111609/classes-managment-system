@extends('layouts.app')

@section('title', 'Official Institutional Invoices')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Official Invoices</h3>
                <p class="text-muted small mb-0">Total of {{ number_format($invoices->total()) }} financial documents
                    generated for settled fees.</p>
            </div>
            <div class="bg-white p-2 px-3 rounded-pill shadow-sm border small fw-bold text-muted d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2 text-info"></i> Invoices are auto-generated upon full settlement.
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
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Invoice Reference</th>
                                <th class="py-3 border-0">Student Context</th>
                                <th class="py-3 border-0">Issue Chronology</th>
                                <th class="py-3 border-0">Financial Capital</th>
                                <th class="py-3 border-0">Settlement Type</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success me-3">
                                                <i class="bi bi-receipt fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $invoice->invoice_number }}</div>
                                                <small class="text-muted tiny">AUTH: Institutional Ledger</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $invoice->student->user->name }}</div>
                                        <small class="text-muted tiny">ROLL: {{ $invoice->student->roll_number }}</small>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $invoice->invoice_date->format('d M, Y') }}
                                        </div>
                                        <small class="text-muted tiny">Recorded at midnight</small>
                                    </td>
                                    <td class="border-0 text-dark fw-bold">₹{{ number_format($invoice->amount, 0) }}</td>
                                    <td class="border-0">
                                        <span class="badge bg-soft-info px-3 py-2 rounded-pill small">
                                            {{ ucfirst($invoice->fee->fee_type) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                                            <a href="{{ route('school.invoices.stream', $invoice) }}" target="_blank"
                                                class="btn btn-sm btn-white border-0" title="Digital Audit">
                                                <i class="bi bi-eye text-primary"></i>
                                            </a>
                                            <a href="{{ route('school.invoices.download', $invoice) }}"
                                                class="btn btn-sm btn-white border-0" title="PDF Archival">
                                                <i class="bi bi-download text-success font-bold"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-receipt-cutoff"
                                                style="font-size: 5rem;"></i></div>
                                        <h5 class="text-muted">Institutional Invoice archives are currently empty.</h5>
                                        <p class="text-muted small">Invoices appear here once fees are marked as fully paid.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $invoices->links() }}
        </div>
    </div>

    <style>
        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
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
            font-size: 0.75rem;
        }
    </style>
@endsection