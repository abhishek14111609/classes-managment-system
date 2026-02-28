@extends('layouts.app')

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
@endphp

@section('title', 'Batch Details')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $batch->name }}</h2>
            <div>
                <a href="{{ route('school.batches.edit', $batch) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('school.batches.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Batch Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Class:</th>
                                <td>{{ $batch->class->name }}</td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>
                                    <span class="badge bg-{{ $batch->class->type === 'academic' ? 'info' : 'success' }}">
                                        {{ ucfirst($batch->class->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Time:</th>
                                <td>{{ date('h:i A', strtotime($batch->start_time)) }} -
                                    {{ date('h:i A', strtotime($batch->end_time)) }}</td>
                            </tr>
                            <tr>
                                <th>Capacity:</th>
                                <td>{{ $batch->capacity }}</td>
                            </tr>
                            <tr>
                                <th>Enrolled:</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $batch->students->count() >= $batch->capacity ? 'danger' : 'success' }}">
                                        {{ $batch->students->count() }} / {{ $batch->capacity }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                                @if($isSport)
                                        Assigned Coaches ({{ $batch->teachers->count() }})
                                @else
                                        Assigned Teachers ({{ $batch->teachers->count() }})
                                @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($batch->teachers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Employee ID</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($batch->teachers as $teacher)
                                            <tr>
                                                <td>{{ $teacher->user->name }}</td>
                                                <td>{{ $teacher->employee_id }}</td>
                                                <td>{{ $teacher->user->email }}</td>
                                                <td>{{ $teacher->user->phone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                @if($isSport) No coaches assigned to this batch. @else No teachers assigned to this batch. @endif
                            </p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                                @if($isSport)
                                        Enrolled Athletes ({{ $batch->students->count() }})
                                @else
                                        Enrolled Students ({{ $batch->students->count() }})
                                @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($batch->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Roll No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Admission Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($batch->students as $student)
                                            <tr>
                                                <td>{{ $student->roll_number }}</td>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->user->email }}</td>
                                                <td>{{ $student->admission_date->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('school.students.show', $student) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                @if($isSport) No athletes enrolled in this batch yet. @else No students enrolled in this batch yet. @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection