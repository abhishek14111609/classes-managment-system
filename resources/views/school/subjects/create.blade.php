@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Add Activity / Exercise' : 'Add Subject / Syllabus')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ auth()->user()->school->institute_type === 'sport' ? 'Add Activity / Exercise' : 'Add Subject / Syllabus' }}
            </h2>
            <a href="{{ route('school.subjects.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('school.subjects.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        @if($isSport)
                            <div class="col-md-4 mb-3">
                                <label for="course_id" class="form-label fw-semibold">Sport (Program) <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id"
                                    name="course_id" required>
                                    <option value="">— Select Sport —</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="level_id" class="form-label fw-semibold">Sports Level <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('level_id') is-invalid @enderror" id="level_id"
                                    name="level_id" required>
                                    <option value="">— Select Level —</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('level_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label fw-semibold">Activity Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}"
                                    placeholder="e.g. Endurance Training, Batting Practice" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @else
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Subject Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="e.g. Mathematics, Science" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="class_id" class="form-label fw-semibold">Class / Grade <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" id="class_id"
                                    name="class_id" required>
                                    <option value="">— Select Class —</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif
                    </div>

                    <input type="hidden" name="type"
                        value="{{ auth()->user()->school->institute_type === 'sport' ? 'sports' : 'academic' }}">

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3"
                            placeholder="Optional notes about syllabus or curriculum...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            {{ auth()->user()->school->institute_type === 'sport' ? 'Add Activity' : 'Add Subject' }}
                        </button>
                        <a href="{{ route('school.subjects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection