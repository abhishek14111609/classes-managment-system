@extends('layouts.app')

@section('title', 'Unified Fee Collection Hub')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="collection-hub-wrapper">
        <!-- Premium Hero Header -->
        <div class="hub-hero py-5 px-4 mb-4">
            <div class="container-fluid position-relative z-index-1">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-2 mb-3 backdrop-blur border border-white border-opacity-20 animate-fade-in-down small tracking-widest">
                            <i class="bi bi-lightning-charge-fill me-1"></i> FINANCE CENTER
                        </div>
                        <h1 class="display-5 fw-800 text-white mb-2 tracking-tight animate-fade-in-up">Unified Collection Hub</h1>
                        <p class="text-white opacity-70 lead mb-0 animate-fade-in-up delay-100">Scan, retrieve, and process multiple athletic fees in a single high-speed transaction.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0 animate-fade-in-right delay-200">
                        <div class="btn-group glass-group p-1 rounded-pill border border-white border-opacity-10 shadow-lg">
                            <a href="{{ route('school.fees.index') }}" class="btn btn-glass-light rounded-pill px-4 border-0 fw-bold">
                                <i class="bi bi-list-columns-reverse me-2"></i> All Fees
                            </a>
                            <a href="{{ route('school.students.index') }}" class="btn btn-glass-light rounded-pill px-4 border-0 fw-bold">
                                <i class="bi bi-people me-2"></i> Athletes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dynamic Background Shapes -->
            <div class="abstract-shape shape-1"></div>
            <div class="abstract-shape shape-2"></div>
        </div>

        <div class="container-fluid py-2 pb-5">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    
                    <!-- Enhanced Selection Section -->
                    <div class="card selection-card border-0 shadow-soft rounded-5 mb-5 overflow-hidden animate-zoom-in">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
                                    <h4 class="fw-800 text-dark mb-2">Member Ledger Retrieval</h4>
                                    <p class="text-muted mb-0">Select an athlete profile below to view active memberships and outstanding dues.</p>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-select-wrapper position-relative">
                                        <div class="search-icon-float">
                                            <i class="bi bi-search text-primary"></i>
                                        </div>
                                        <select id="student_select"
                                            class="form-select form-select-xl hub-select rounded-pill ps-5 pe-4 shadow-sm fw-bold border-2"
                                            onchange="window.location.href='/school/payments/collect/' + this.value">
                                            <option value="">— Search Athlete Profile / Registration —</option>
                                            @foreach($students as $s)
                                                <option value="{{ $s->id }}" {{ $student && $student->id == $s->id ? 'selected' : '' }}>
                                                    {{ $s->user->name }} (Roll: {{ $s->roll_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($student)
                        <form action="{{ route('school.payments.bulk-store') }}" method="POST" id="bulk_payment_form" class="animate-fade-in-up">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $student->id }}">

                            <div class="row g-4">
                                <!-- Modern Ledger Items -->
                                <div class="col-lg-8">
                                    <div class="card border-0 shadow-soft rounded-5 overflow-hidden h-100">
                                        <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-gradient rounded-circle me-3 d-flex align-items-center justify-content-center text-white">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-800 text-dark mb-0">Active Ledger Items</h6>
                                                    <small class="text-muted">Breakdown of pending session & kit fees</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-soft-rose text-rose rounded-pill px-3 py-2 fw-bold border border-rose border-opacity-10">
                                                {{ $pendingFees->count() }} PENDING ITEMS
                                            </span>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0 hub-table">
                                                <thead class="bg-light-indigo text-indigo opacity-80">
                                                    <tr class="small fw-800 text-uppercase tracking-wider">
                                                        <th class="ps-4 py-3">Fee Specification</th>
                                                        <th>Member Group / Session</th>
                                                        <th>Balance Due</th>
                                                        <th class="pe-4" style="width: 200px;">Settling Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($pendingFees as $index => $fee)
                                                        @php $remaining = $fee->getRemainingAmount(); @endphp
                                                        <tr class="ledger-row transition-base">
                                                            <td class="ps-4 py-4">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fee-icon me-3 bg-light rounded-3 d-flex align-items-center justify-content-center text-primary border border-white border-opacity-10 shadow-sm" style="width: 45px; height: 45px;">
                                                                        <i class="bi bi-{{ $fee->fee_type == 'monthly' ? 'calendar-event' : 'check-square-fill' }}"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-800 text-dark">{{ ucfirst(str_replace('_', ' ', $fee->fee_type)) }}</div>
                                                                        <small class="text-muted opacity-75">DUE BY: {{ $fee->due_date->format('d M, Y') }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2 border border-primary border-opacity-10 fw-bold">
                                                                    <i class="bi bi-tag-fill me-1"></i> {{ $fee->batch->name ?? 'General' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="fw-800 text-dark fs-5">₹{{ number_format($remaining, 2) }}</span>
                                                            </td>
                                                            <td class="pe-4">
                                                                <input type="hidden" name="payments[{{ $index }}][fee_id]" value="{{ $fee->id }}">
                                                                <div class="input-group input-group-settle rounded-pill overflow-hidden border shadow-sm transition-focus">
                                                                    <span class="input-group-text border-0 bg-white text-muted tiny fw-bold">₹</span>
                                                                    <input type="number" name="payments[{{ $index }}][amount]"
                                                                        class="form-control border-0 shadow-none payment-input fw-bold text-primary"
                                                                        step="0.01" min="0" max="{{ $remaining }}"
                                                                        data-max="{{ $remaining }}" value="0"
                                                                        oninput="calculateTotal()">
                                                                    <button class="btn btn-soft-primary border-0 px-2" type="button"
                                                                        onclick="this.previousElementSibling.value = this.previousElementSibling.dataset.max; calculateTotal();">
                                                                        <i class="bi bi-plus-circle-fill"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-5">
                                                                <div class="empty-state-icon mb-4 animate-bounce">
                                                                    <div class="bg-soft-success rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                                                        <i class="bi bi-shield-check text-success display-5"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="fw-800 text-dark mb-1">Clear Balance!</h5>
                                                                <p class="text-muted px-5">This athlete has settled all active institutional dues. No current financial action required.</p>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Premium Control Panel -->
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-strong rounded-5 sticky-top overflow-hidden" style="top: 20px;">
                                        <div class="card-body p-0">
                                            <!-- Panel Header -->
                                            <div class="bg-dark p-4 text-white">
                                                <h6 class="fw-800 text-uppercase tiny tracking-widest opacity-60 mb-3 text-center">Checkout Summary</h6>
                                                <div class="text-center">
                                                    <span class="display-5 fw-800 text-primary-light">₹<span id="grand_total">0.00</span></span>
                                                    <div class="tiny opacity-50 mt-1">TOTAL CONSOLIDATED AMOUNT</div>
                                                </div>
                                            </div>

                                            <div class="p-4 bg-white">
                                                <!-- Auto Allocation Tool -->
                                                <div class="tool-box p-3 rounded-4 bg-light-indigo mb-4 border border-indigo border-opacity-10 shadow-sm animate-fade-in-up delay-100">
                                                    <label class="form-label tiny fw-800 text-indigo text-uppercase mb-2">Smart Auto-Allocate</label>
                                                    <div class="input-group rounded-pill overflow-hidden border border-white shadow-sm">
                                                        <span class="input-group-text border-0 bg-white text-muted small">₹</span>
                                                        <input type="number" id="lump_sum" class="form-control border-0 shadow-none fw-bold" placeholder="Lump sum amount...">
                                                        <button type="button" class="btn btn-indigo border-0 px-3 fw-bold" onclick="autoAllocate()">
                                                            <i class="bi bi-magic"></i>
                                                        </button>
                                                    </div>
                                                    <div class="form-text tiny opacity-75 mt-2 ms-2"><i class="bi bi-info-circle me-1"></i>Distributes across oldest dues.</div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label tiny fw-800 text-muted text-uppercase mb-2 ms-2">Transaction Medium</label>
                                                    <div class="payment-grid">
                                                        <div class="pay-option">
                                                            <input type="radio" name="payment_method" value="cash" id="m_cash" checked>
                                                            <label for="m_cash" class="pay-card">
                                                                <i class="bi bi-cash-stack"></i>
                                                                <span>CASH</span>
                                                            </label>
                                                        </div>
                                                        <div class="pay-option">
                                                            <input type="radio" name="payment_method" value="upi" id="m_upi">
                                                            <label for="m_upi" class="pay-card">
                                                                <i class="bi bi-qr-code-scan"></i>
                                                                <span>UPI</span>
                                                            </label>
                                                        </div>
                                                        <div class="pay-option">
                                                            <input type="radio" name="payment_method" value="bank_transfer" id="m_bank">
                                                            <label for="m_bank" class="pay-card">
                                                                <i class="bi bi-bank"></i>
                                                                <span>BANK</span>
                                                            </label>
                                                        </div>
                                                        <div class="pay-option">
                                                            <input type="radio" name="payment_method" value="card" id="m_card">
                                                            <label for="m_card" class="pay-card">
                                                                <i class="bi bi-credit-card"></i>
                                                                <span>CARD</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-4">
                                                    <div class="col-12">
                                                        <label class="form-label tiny fw-800 text-muted text-uppercase mb-2 ms-2">Collection Date</label>
                                                        <input type="date" name="paid_at"
                                                            class="form-control rounded-pill px-4 py-2 border-light bg-light fw-bold"
                                                            value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label tiny fw-800 text-muted text-uppercase mb-2 ms-2">Reference / Ref No.</label>
                                                        <input type="text" name="transaction_id"
                                                            class="form-control rounded-pill px-4 py-2 border-light bg-light fw-bold"
                                                            placeholder="Cheque # or UPI Ref">
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label tiny fw-800 text-muted text-uppercase mb-2 ms-2">Internal Remarks</label>
                                                    <textarea name="notes"
                                                        class="form-control rounded-4 px-3 py-2 border-light bg-light small" rows="2"
                                                        placeholder="Administrative notes..."></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-xl w-100 rounded-pill py-3 fw-bold shadow-strong animate-pulse transition-scale"
                                                    id="submit_btn" disabled>
                                                    <i class="bi bi-check2-all me-2"></i> CONFIRM COLLECTION
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal() {
            let inputs = document.querySelectorAll('.payment-input');
            let total = 0;
            inputs.forEach(input => {
                let val = parseFloat(input.value) || 0;
                total += val;
            });

            const formattedTotal = total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
            document.getElementById('grand_total').innerText = formattedTotal;
            
            const submitBtn = document.getElementById('submit_btn');
            submitBtn.disabled = total <= 0;
            if (total > 0) {
                submitBtn.innerHTML = `<i class="bi bi-wallet2 me-2"></i> COLLECT ₹${formattedTotal}`;
            } else {
                submitBtn.innerHTML = `<i class="bi bi-check2-all me-2"></i> CONFIRM COLLECTION`;
            }
        }

        function autoAllocate() {
            let lumpSum = parseFloat(document.getElementById('lump_sum').value) || 0;
            if (lumpSum <= 0) return;
            
            let inputs = document.querySelectorAll('.payment-input');
            inputs.forEach(input => input.value = 0);
            
            inputs.forEach(input => {
                if (lumpSum <= 0) return;
                let max = parseFloat(input.dataset.max) || 0;
                let allocate = Math.min(lumpSum, max);
                input.value = allocate.toFixed(2);
                lumpSum -= allocate;
                
                // Add a little highlight effect
                input.closest('.input-group-settle').classList.add('allocate-flash');
                setTimeout(() => input.closest('.input-group-settle').classList.remove('allocate-flash'), 500);
            });
            
            calculateTotal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            calculateTotal();
        });
    </script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --hero-gradient: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            --indigo: #4338ca;
            --rose: #e11d48;
            --text-rose: #be123c;
            --bg-rose: rgba(225, 29, 72, 0.08);
            --bg-indigo: rgba(67, 56, 202, 0.08);
        }

        .fw-800 { font-weight: 800; }
        .tracking-tight { letter-spacing: -1.5px; }
        .tracking-widest { letter-spacing: 2px; }
        .backdrop-blur { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
        .shadow-soft { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .shadow-strong { box-shadow: 0 30px 60px rgba(0,0,0,0.15); }
        .transition-base { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .transition-scale:active { transform: scale(0.98); }
        .transition-focus:focus-within { border-color: #4f46e5 !important; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important; }

        /* Hero Styling */
        .hub-hero {
            background: var(--hero-gradient);
            border-radius: 0 0 3rem 3rem;
            position: relative;
            overflow: hidden;
            margin-top: -1.5rem;
        }

        .abstract-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            opacity: 0.15;
        }
        .shape-1 { width: 300px; height: 300px; background: #c026d3; top: -100px; right: -50px; }
        .shape-2 { width: 200px; height: 200px; background: #6366f1; bottom: -50px; left: 10%; }

        .glass-group { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); }
        .btn-glass-light { color: white; background: transparent; }
        .btn-glass-light:hover { background: rgba(255, 255, 255, 0.15); color: white; }

        /* Select Enhancement */
        .hub-select {
            border: 2px solid #e2e8f0;
            background-color: #f8fafc;
            height: 60px;
            transition: all 0.3s;
        }
        .hub-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.1);
            background-color: #fff;
        }
        .search-icon-float {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            font-size: 1.2rem;
            pointer-events: none;
        }

        /* Table & Ledger Styling */
        .bg-light-indigo { background: var(--bg-indigo); }
        .text-indigo { color: var(--indigo); }
        .bg-soft-rose { background: var(--bg-rose); }
        .text-rose { color: var(--text-rose); }
        .ledger-row:hover { background-color: rgba(79, 70, 229, 0.02); }
        .bg-primary-gradient { background: var(--primary-gradient); }

        /* Payment Grid */
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .pay-option input { display: none; }
        .pay-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border: 2px solid #f1f5f9;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.25s;
            background: #f8fafc;
        }
        .pay-card i { font-size: 1.5rem; margin-bottom: 5px; color: #64748b; }
        .pay-card span { font-size: 0.6rem; font-weight: 800; letter-spacing: 1px; color: #64748b; }
        .pay-option input:checked + .pay-card {
            border-color: #4f46e5;
            background: rgba(79, 70, 229, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.15);
        }
        .pay-option input:checked + .pay-card i { color: #4f46e5; }
        .pay-option input:checked + .pay-card span { color: #4f46e5; }
        .pay-card:hover { border-color: #cbd5e1; }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        @keyframes pulseSlight { 0% { transform: scale(1); } 50% { transform: scale(1.02); } 100% { transform: scale(1); } }
        @keyframes bounceSlight { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes flashBorder { 0% { border-color: transparent; } 50% { border-color: #4f46e5; background-color: rgba(79, 70, 229, 0.05); } 100% { border-color: transparent; } }

        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-fade-in-down { animation: fadeInDown 0.6s ease-out forwards; }
        .animate-fade-in-right { animation: fadeInRight 0.6s ease-out forwards; }
        .animate-zoom-in { animation: zoomIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .animate-pulse { animation: pulseSlight 2s infinite ease-in-out; }
        .animate-bounce { animation: bounceSlight 3s infinite ease-in-out; }
        .allocate-flash { animation: flashBorder 0.6s ease-out; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }

        .text-primary-light { color: #818cf8; }
        .btn-primary { background: var(--primary-gradient); border: none; }
        .btn-indigo { background: #4338ca; color: white; }
        .btn-xl { font-size: 1.1rem; }
    </style>
@endsection