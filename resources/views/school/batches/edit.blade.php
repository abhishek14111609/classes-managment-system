@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Edit Training Session' : 'Edit Batch')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $isSport ? 'Edit Training Session' : 'Edit Batch' }}</h2>
            <a href="{{ route('school.batches.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('school.batches.update', $batch) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        @if($isSport)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">Sport (Program) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                                        <option value="">Select Sport</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                {{ old('course_id', $batch->class->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="subject_id" class="form-label">Batch Type (Activity & Level) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                        <option value="">Select Batch Type</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                data-course="{{ $subject->schoolClass->course_id ?? '' }}"
                                                {{ old('subject_id', $batch->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }} ({{ $subject->level->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Session Name <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $batch->name) }}" placeholder="Auto-generated if empty">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Batch Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $batch->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $batch->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time <span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time" value="{{ old('start_time', $batch->start_time->format('H:i')) }}"
                                    required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" value="{{ old('end_time', $batch->end_time->format('H:i')) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                    id="capacity" name="capacity" value="{{ old('capacity', $batch->capacity) }}" min="1"
                                    required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if(!$isSport)
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sport_level" class="form-label">Sport Level</label>
                                    <select class="form-select @error('sport_level') is-invalid @enderror" id="sport_level"
                                        name="sport_level">
                                        <option value="">Select Level (Optional)</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->name }}" {{ old('sport_level', $batch->sport_level) == $level->name ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sport_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label
                            class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Assign Coaches' : 'Assign Teachers' }}</label>
                        <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                            @foreach($teachers as $teacher)
                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('teacher_ids') is-invalid @enderror" type="checkbox"
                                        name="teacher_ids[]" value="{{ $teacher->id }}" id="teacher_{{ $teacher->id }}" {{ in_array($teacher->id, old('teacher_ids', $batch->teachers->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="teacher_{{ $teacher->id }}">
                                        {{ $teacher->user->name }} <span
                                            class="text-muted small">({{ $teacher->employee_id }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select one or more
                            {{ auth()->user()->school->institute_type === 'sport' ? 'coaches' : 'teachers' }} by checking
                            the boxes</small>
                        @error('teacher_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            {{ auth()->user()->school->institute_type === 'sport' ? 'Update Session' : 'Update Batch' }}
                        </button>
                        <a href="{{ route('school.batches.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($isSport)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id');
        const subjectSelect = document.getElementById('subject_id');
        const allSubjects = Array.from(subjectSelect.options);

        function filterSubjects() {
            const selectedCourseId = courseSelect.value;
            const currentSubjectId = subjectSelect.value;
            
            // Reset subjects
            subjectSelect.innerHTML = '';
            
            // Add back everything that matches
            allSubjects.forEach(option => {
                if (option.value === "" || option.getAttribute('data-course') === selectedCourseId) {
                    subjectSelect.appendChild(option.cloneNode(true));
                }
            });
            
            // Restore selection if possible
            subjectSelect.value = currentSubjectId;
        }

        courseSelect.addEventListener('change', filterSubjects);
        
        // Initial run if course is already selected
        if (courseSelect.value) filterSubjects();
    });
    </script>
    @endif
@endsection