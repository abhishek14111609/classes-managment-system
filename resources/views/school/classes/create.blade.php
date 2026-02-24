@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Create Team' : 'Create Class')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ auth()->user()->school->institute_type === 'sport' ? 'Create New Team' : 'Create New Class' }}</h2>
        <a href="{{ route('school.classes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('school.classes.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="course_id" class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Program/Discipline' : 'Course' }} <span class="text-muted">(Optional)</span></label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id">
                            <option value="">{{ auth()->user()->school->institute_type === 'sport' ? 'Select Discipline' : 'Select Course' }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} {{ $course->code ? "($course->code)" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Team Name' : 'Class Name' }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" placeholder="{{ auth()->user()->school->institute_type === 'sport' ? 'e.g. U-16 Boys' : 'e.g. Grade 10' }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <input type="hidden" name="type" value="{{ auth()->user()->school->institute_type === 'sport' ? 'sports' : 'academic' }}">

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> {{ auth()->user()->school->institute_type === 'sport' ? 'Create Team' : 'Create Class' }}
                    </button>
                    <a href="{{ route('school.classes.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
