@extends('layouts.app')

@section('title', auth()->user()->school && auth()->user()->school->institute_type === 'sport' ? 'Assign Fee to Athlete' : 'Assign Fee to Student')

@section('sidebar')
    @include('school.sidebar')
@endsection

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
    $feeTypeMap = [
        'monthly' => ['label' => 'Monthly', 'color' => 'bg-primary'],
        'quarterly' => ['label' => 'Quarterly', 'color' => 'bg-indigo'],
        'half_yearly' => ['label' => 'Half Yearly', 'color' => 'bg-purple'],
        'annual' => ['label' => 'Annual', 'color' => 'bg-dark'],
        'tuition' => ['label' => 'Tuition', 'color' => 'bg-primary'],
        'sports' => ['label' => 'Sports', 'color' => 'bg-success'],
        'transport' => ['label' => 'Transport', 'color' => 'bg-warning text-dark'],
        'exam' => ['label' => 'Exam', 'color' => 'bg-danger'],
        'library' => ['label' => 'Library', 'color' => 'bg-info text-dark'],
        'other' => ['label' => 'Other', 'color' => 'bg-secondary'],
    ];
@endphp

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    @if($isSport) Assign Fee to Athlete @else Assign Fee to Student @endif
                </h2>
                <p class="text-muted mb-0">
                    @if($isSport)
                        Select a plan → pick an athlete → set the due date.
                    @else
                        Select a plan → pick a student → set the due date.
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.fee-plans.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-journal-plus me-1"></i> Manage Plans
                </a>
                <a href="{{ route('school.fees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        @if($feePlans->isEmpty())
            <div class="alert alert-warning d-flex gap-3 align-items-start border-0 rounded-3">
                <i class="bi bi-exclamation-triangle-fill fs-4 mt-1"></i>
                <div>
                    <strong>No active fee plans found.</strong><br>
                    You must create at least one fee plan before assigning fees to students.
                    <a href="{{ route('school.fee-plans.create') }}" class="alert-link ms-1">Create a plan →</a>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('school.fees.store') }}" method="POST" id="feeForm">
            @csrf

            <div class="row g-4">

                {{-- STEP 1: Select Plan --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white rounded-top py-3">
                            <h6 class="mb-0 fw-bold">
                                <span class="badge bg-white text-primary me-2">1</span>
                                Select Fee Plan
                            </h6>
                        </div>
                        <div class="card-body p-0" style="max-height:520px; overflow-y:auto;">
                            @forelse($feePlans as $plan)
                                @php $typeInfo = $feeTypeMap[$plan->fee_type] ?? ['label' => ucfirst($plan->fee_type), 'color' => 'bg-secondary']; @endphp
                                <label class="d-flex align-items-start gap-3 p-3 border-bottom plan-option"
                                    for="plan_{{ $plan->id }}" style="cursor:pointer; transition:background .15s;"
                                    data-plan-id="{{ $plan->id }}">
                                    <input type="radio" name="fee_plan_id" id="plan_{{ $plan->id }}" value="{{ $plan->id }}"
                                        class="plan-radio mt-1" data-amount="{{ $plan->amount }}"
                                        data-type="{{ $plan->fee_type }}" data-type-label="{{ $typeInfo['label'] }}"
                                        data-duration="{{ $plan->duration ?? 'one_time' }}"
                                        data-sport="{{ $plan->sport_level ?? '' }}" data-desc="{{ $plan->description ?? '' }}"
                                        data-name="{{ $plan->name }}" {{ ($selectedPlan && $selectedPlan->id === $plan->id) || old('fee_plan_id') == $plan->id ? 'checked' : '' }}>
                                    <div class="grow min-w-0">
                                        <div class="fw-semibold text-truncate">{{ $plan->name }}</div>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            <span class="badge {{ $typeInfo['color'] }} small">
                                                {{ $typeInfo['label'] }}
                                            </span>
                                            @if($plan->duration)
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary border border-secondary small">
                                                    {{ ucwords(str_replace('_', ' ', $plan->duration)) }}
                                                </span>
                                            @endif
                                            @if($plan->sport_level)
                                                <span class="badge small" style="background:#7c3aed;">
                                                    {{ ucfirst($plan->sport_level) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end shrink-0">
                                        <span class="fw-bold text-dark">₹{{ number_format($plan->amount, 0) }}</span>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center text-muted py-4 small">No active plans available.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="col-md-8">

                    {{-- Plan preview --}}
                    <div class="card border-0 shadow-sm mb-4" id="planPreview" style="display:none;">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small mb-1">Selected Plan</div>
                                    <h5 class="fw-bold mb-1" id="previewName">—</h5>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-secondary" id="previewType"></span>
                                        <span class="badge bg-info-subtle text-info border border-info"
                                            id="previewDuration"></span>
                                        <span class="badge ms-1" id="previewSport"
                                            style="background:#7c3aed; display:none;"></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small mb-1">Plan Amount</div>
                                    <h3 class="fw-bold text-primary mb-0">₹<span id="previewAmount">0</span></h3>
                                </div>
                            </div>
                            <p class="text-muted small mt-2 mb-0" id="previewDesc"></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="fw-bold mb-0">
                                <span class="badge bg-primary me-2">2</span>
                                Student & Fee Details
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            {{-- Hidden fields filled by JS from selected plan --}}
                            <input type="hidden" name="fee_type" id="fee_type_hidden" value="{{ old('fee_type') }}">
                            <input type="hidden" name="duration" id="duration_hidden" value="{{ old('duration') }}">
                            <input type="hidden" name="sport_level" id="sport_level_hidden"
                                value="{{ old('sport_level') }}">
                            <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">

                            <div class="mb-3">
                                <label for="student_id" class="form-label fw-semibold">
                                    @if($isSport) Athlete @else Student @endif <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                                    name="student_id" required onchange="updateStudentBatches(this)">
                                    <option value="">
                                        @if($isSport) — Select Athlete — @else — Select Student — @endif
                                    </option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}"
                                            data-batches="{{ json_encode($student->batches->map(fn($b) => ['id' => $b->id, 'name' => $b->name])) }}"
                                            {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->user->name }}
                                            @if($student->roll_number) (Roll: {{ $student->roll_number }})@endif
                                            @if($student->batches->isNotEmpty()) — Enrolled in {{ $student->batches->count() }}
                                            Sessions @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3" id="batch_selection_container" style="display:none;">
                                <label for="batch_id" class="form-label fw-semibold">
                                    Link to Session <small class="text-muted">(Optional)</small>
                                </label>
                                <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id"
                                    name="batch_id">
                                    <option value="">— Select Relevant Session —</option>
                                </select>
                                <div class="form-text">Choose the specific sport/training session this fee is for.</div>
                                @error('batch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="total_amount" class="form-label fw-semibold">
                                        Amount (₹) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number"
                                            class="form-control @error('total_amount') is-invalid @enderror"
                                            id="total_amount" name="total_amount" value="{{ old('total_amount') }}"
                                            step="0.01" min="1" required placeholder="Auto-filled from plan">
                                    </div>
                                    <div class="form-text">You can override the amount if needed.</div>
                                    @error('total_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="due_date" class="form-label fw-semibold">
                                        Due Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                        id="due_date" name="due_date"
                                        value="{{ old('due_date', now()->addMonth()->format('Y-m-d')) }}" required>
                                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="discount" class="form-label fw-semibold">Discount (₹)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control" id="discount" name="discount"
                                            value="{{ old('discount', 0) }}" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="late_fee" class="form-label fw-semibold">Late Fee (₹)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control" id="late_fee" name="late_fee"
                                            value="{{ old('late_fee', 0) }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remarks" class="form-label fw-semibold">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                    placeholder="Any additional notes…">{{ old('remarks') }}</textarea>
                            </div>

                            {{-- NEW: Initial Payment Section --}}
                            <div class="card bg-light border-0 rounded-3 mb-4">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="record_payment_toggle"
                                            onchange="togglePaymentSection(this.checked)">
                                        <label class="form-check-label fw-bold text-success" for="record_payment_toggle">
                                            <i class="bi bi-cash-stack me-1"></i> Record Initial Payment & Generate Invoice
                                            Now?
                                        </label>
                                    </div>

                                    <div id="payment_fields" style="display:none;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="initial_paid_amount" class="form-label fw-semibold">Amount Being
                                                    Paid (₹)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" class="form-control" name="initial_paid_amount"
                                                        id="initial_paid_amount" step="0.01" min="0" placeholder="0.00"
                                                        oninput="updateNetPayable()">
                                                </div>
                                                <div class="form-text">Enter portion being paid now (e.g. half or full).
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="payment_method" class="form-label fw-semibold">Payment
                                                    Method</label>
                                                <select class="form-select" name="payment_method" id="payment_method">
                                                    <option value="cash">Cash</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="card">Card</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="upi">UPI</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="transaction_id" class="form-label fw-semibold">Transaction ID /
                                                    Reference (Optional)</label>
                                                <input type="text" class="form-control" name="transaction_id"
                                                    id="transaction_id" placeholder="e.g. UTR Number, Receipt ID">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Live net payable --}}
                            <div class="alert alert-info border-0 rounded-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold" id="netLabel">Total Estimated Fee</span>
                                        <div class="small text-muted" id="netSubtext">Amount + Late Fee − Discount</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fs-3 fw-bold text-primary">
                                            ₹<span id="netPayable">0.00</span>
                                        </span>
                                        <div class="small text-danger fw-semibold" id="remainingText" style="display:none;">
                                            Pending: ₹<span id="remainingAmount">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-body p-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold" id="submitBtn">
                                        <i class="bi bi-check-all me-2"></i>
                                        @if($isSport) Assign Fee to Athlete @else Assign Fee to Student @endif
                                    </button>
                                    <p class="text-center text-muted small mt-3 mb-0">
                                        <i class="bi bi-shield-lock me-1"></i> Securely processed by School Management
                                        System
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function applyPlan(radio) {
            if (!radio) return;
            const data = radio.dataset;

            // Fill hidden inputs + amount
            document.getElementById('fee_type_hidden').value = data.type;
            document.getElementById('duration_hidden').value = data.duration || 'one_time';
            document.getElementById('sport_level_hidden').value = data.sport || '';
            document.getElementById('total_amount').value = parseFloat(data.amount).toFixed(2);
            document.getElementById('initial_paid_amount').value = ''; // Reset on plan change
            document.getElementById('initial_paid_amount').max = data.amount;

            // Set default due dates based on duration
            const today = new Date();
            let dueDate = new Date();

            switch (data.duration) {
                case 'monthly':
                    dueDate.setMonth(today.getMonth() + 1);
                    break;
                case 'quarterly':
                    dueDate.setMonth(today.getMonth() + 3);
                    break;
                case 'half_yearly':
                    dueDate.setMonth(today.getMonth() + 6);
                    break;
                case 'annual':
                    dueDate.setFullYear(today.getFullYear() + 1);
                    break;
                default: // One-time or other
                    dueDate.setDate(today.getDate() + 15);
            }

            document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];

            // Update preview card
            document.getElementById('planPreview').style.display = 'block';
            document.getElementById('previewName').textContent = data.name;
            document.getElementById('previewType').textContent = data.typeLabel;
            document.getElementById('previewDuration').textContent = data.duration.replace('_', ' ').toUpperCase();
            document.getElementById('previewAmount').textContent = parseFloat(data.amount).toLocaleString('en-IN');
            document.getElementById('previewDesc').textContent = data.desc;

            const sportBadge = document.getElementById('previewSport');
            if (data.sport) {
                sportBadge.style.display = 'inline-block';
                sportBadge.textContent = data.sport.charAt(0).toUpperCase() + data.sport.slice(1) + ' Level';
            } else {
                sportBadge.style.display = 'none';
            }

            // Highlight selected option row
            document.querySelectorAll('.plan-option').forEach(el => el.classList.remove('bg-primary-subtle'));
            radio.closest('.plan-option').classList.add('bg-primary-subtle');

            calcNet();
        }

        // Plan radio click
        document.querySelectorAll('.plan-radio').forEach(radio => {
            radio.addEventListener('change', () => applyPlan(radio));
        });

        // Net payable calc
        function updateNetPayable() {
            const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const lateFee = parseFloat(document.getElementById('late_fee').value) || 0;
            const initialPaid = parseFloat(document.getElementById('initial_paid_amount').value) || 0;

            const netTotal = Math.max(0, totalAmount + lateFee - discount);
            document.getElementById('netPayable').textContent = netTotal.toFixed(2);
            document.getElementById('initial_paid_amount').max = netTotal; // Allow paying total amount

            const recordPayment = document.getElementById('record_payment_toggle').checked;
            if (recordPayment && initialPaid > 0) {
                const remaining = Math.max(0, netTotal - initialPaid);
                document.getElementById('remainingAmount').textContent = remaining.toFixed(2);
                document.getElementById('remainingText').style.display = 'block';
                document.getElementById('netLabel').textContent = 'Current Payment';
                document.getElementById('netPayable').textContent = initialPaid.toFixed(2);
            } else {
                document.getElementById('remainingText').style.display = 'none';
                document.getElementById('netLabel').textContent = 'Total Estimated Fee';
            }
        }

        function togglePaymentSection(show) {
            const fields = document.getElementById('payment_fields');
            fields.style.display = show ? 'block' : 'none';

            const initialInput = document.getElementById('initial_paid_amount');
            if (show) {
                initialInput.setAttribute('required', 'required');
                // Default to full amount if shown
                const total = parseFloat(document.getElementById('total_amount').value) || 0;
                const disc = parseFloat(document.getElementById('discount').value) || 0;
                const late = parseFloat(document.getElementById('late_fee').value) || 0;
                initialInput.value = Math.max(0, total + late - disc).toFixed(2);
            } else {
                initialInput.removeAttribute('required');
                initialInput.value = '';
            }
            updateNetPayable();
        }

        ['total_amount', 'discount', 'late_fee'].forEach(id =>
            document.getElementById(id).addEventListener('input', updateNetPayable)
        );

        function updateStudentBatches(select) {
            const container = document.getElementById('batch_selection_container');
            const batchSelect = document.getElementById('batch_id');
            const option = select.options[select.selectedIndex];

            if (!option || !option.value || !option.dataset.batches) {
                container.style.display = 'none';
                batchSelect.innerHTML = '<option value="">— Select Relevant Session —</option>';
                return;
            }

            const batches = JSON.parse(option.dataset.batches);
            if (batches.length > 0) {
                container.style.display = 'block';
                let html = '<option value="">— Select Relevant Session —</option>';
                batches.forEach(b => {
                    html += `<option value="${b.id}">${b.name}</option>`;
                });
                batchSelect.innerHTML = html;
            } else {
                container.style.display = 'none';
            }
        }

        // Alias for the existing call in applyPlan
        function calcNet() { updateNetPayable(); }

        // Auto-apply on load (URL ?plan=X or old() repopulation)
        const checkedRadio = document.querySelector('.plan-radio:checked');
        if (checkedRadio) applyPlan(checkedRadio);

        const studentSelect = document.getElementById('student_id');
        if (studentSelect.value) updateStudentBatches(studentSelect);

        calcNet();
    </script>
@endsection