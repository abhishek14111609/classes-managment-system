@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Add New Athlete' : 'Add New Student')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ auth()->user()->school->institute_type === 'sport' ? 'Add New Athlete' : 'Add New Student' }}
        </h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('school.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-3">Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Athlete Name' : 'Student Name' }}
                                *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">
                        {{ auth()->user()->school->institute_type === 'sport' ? 'Registration Information' : 'Academic Information' }}
                    </h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Registration ID / Jersey Number' : 'Roll Number' }}</label>
                            <input type="text" name="roll_number"
                                class="form-control @error('roll_number') is-invalid @enderror"
                                value="{{ old('roll_number') }}" placeholder="Auto-generated">
                            @error('roll_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Course / Program</label>
                            <select name="course_id" id="course_id_select" class="form-select @error('course_id') is-invalid @enderror">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ auth()->user()->school->institute_type === 'sport' ? 'Assigned Sessions (Sports)' : 'Batch' }}</label>
                            @if(auth()->user()->school->institute_type === 'sport')
                                <div class="card bg-light border-0 shadow-none rounded-3">
                                    <div class="card-body p-2" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($batches as $batch)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input batch-checkbox" type="checkbox" 
                                                       name="batch_ids[]" value="{{ $batch->id }}" 
                                                       id="batch_{{ $batch->id }}"
                                                       data-course="{{ $batch->class->course_id ?? '' }}"
                                                       {{ in_array($batch->id, (array)old('batch_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="batch_{{ $batch->id }}">
                                                    {{ $batch->name }} <span class="text-muted">({{ $batch->subject->name ?? 'N/A' }})</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-text small">Select one or more active training sessions for the athlete.</div>
                            @else
                                <select name="batch_id" id="batch_id" class="form-select @error('batch_id') is-invalid @enderror">
                                    <option value="">Select Batch</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" 
                                            data-course="{{ $batch->class->course_id ?? '' }}"
                                            {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                            {{ $batch->name }} ({{ $batch->subject->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('batch_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('batch_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label text-primary fw-bold">Assign Fee Plan</label>
                            <select name="fee_plan_id"
                                class="form-select bg-primary bg-opacity-10 border-primary @error('fee_plan_id') is-invalid @enderror">
                                <option value="">No Initial Fee</option>
                                @foreach($feePlans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('fee_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (Rs. {{ number_format($plan->amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Automatically generates an invoice.</div>
                            @error('fee_plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Joining Date' : 'Admission Date' }}
                                *</label>
                            <input type="date" name="admission_date"
                                class="form-control @error('admission_date') is-invalid @enderror"
                                value="{{ old('admission_date', date('Y-m-d')) }}" required>
                            @error('admission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="birth_date"
                                class="form-control @error('birth_date') is-invalid @enderror"
                                value="{{ old('birth_date') }}">
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="previous_school"
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Current School/Institute' : 'Previous School' }}</label>
                            <input type="text" name="previous_school"
                                class="form-control @error('previous_school') is-invalid @enderror"
                                value="{{ old('previous_school') }}">
                            @error('previous_school')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Parent Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Name</label>
                            <input type="text" name="parent_name"
                                class="form-control @error('parent_name') is-invalid @enderror"
                                value="{{ old('parent_name') }}">
                            @error('parent_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Phone</label>
                            <input type="text" name="parent_phone"
                                class="form-control @error('parent_phone') is-invalid @enderror"
                                value="{{ old('parent_phone') }}">
                            @error('parent_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                            rows="3">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label
                            class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Athlete Photo' : 'Student Photo' }}</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                            accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                            class="btn btn-primary">{{ auth()->user()->school->institute_type === 'sport' ? 'Register Athlete' : 'Create Student' }}</button>
                        <a href="{{ route('school.students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(auth()->user()->school->institute_type === 'sport')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id_select');
        const checkboxes = document.querySelectorAll('.batch-checkbox');

        function filterBatches() {
            const selectedCourseId = courseSelect.value;
            
            checkboxes.forEach(cb => {
                const parent = cb.closest('.form-check');
                if (selectedCourseId === "" || cb.getAttribute('data-course') === selectedCourseId) {
                    parent.style.display = 'block';
                } else {
                    parent.style.display = 'none';
                    cb.checked = false; // Uncheck if hidden
                }
            });
        }

        if (courseSelect) {
            courseSelect.addEventListener('change', filterBatches);
            if (courseSelect.value) filterBatches();
        }
    });
    </script>
    @endif
@endsection