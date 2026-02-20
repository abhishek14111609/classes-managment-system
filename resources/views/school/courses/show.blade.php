@extends('layouts.app')

@section('title', 'Course: ' . $course->name)

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Course Details: {{ $course->name }}</span>
                    <a href="{{ route('school.courses.edit', $course) }}" class="btn btn-warning btn-sm">Edit</a>
                </div>

                <div class="card-body">
                    <p><strong>Name:</strong> {{ $course->name }}</p>
                    <p><strong>Code:</strong> {{ $course->code ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $course->description ?? 'No description provided.' }}</p>
                    <p><strong>Status:</strong> <span
                            class="badge bg-{{ $course->is_active ? 'success' : 'secondary' }}">{{ $course->is_active ? 'Active' : 'Inactive' }}</span>
                    </p>

                    <h4 class="mt-4">Classes Linked to this Course</h4>
                    @if($course->classes->count() > 0)
                        <ul class="list-group">
                            @foreach($course->classes as $class)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $class->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $class->batches_count ?? 0 }} Batches</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No classes linked to this course yet.</p>
                    @endif
                </div>

                <div class="card-footer">
                    <a href="{{ route('school.courses.index') }}" class="btn btn-secondary btn-sm">Back to Courses</a>
                </div>
            </div>
        </div>
    </div>
@endsection