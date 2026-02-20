@extends('layouts.app')

@section('title', 'Study Materials')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">Class Materials & Resources</h3>
                <p class="text-muted small mb-0">Share documents, assignments, and study guides with your students.</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload me-1"></i> Upload Material
            </button>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bi bi-folder-fill text-warning me-2"></i> Quick Folders
                        </h6>
                        <div class="list-group list-group-flush">
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-light rounded-3 mb-2 transition-all">
                                <span><i class="bi bi-journal-text me-2"></i> Lecture Notes</span>
                                <span class="badge bg-white border text-muted rounded-pill">12</span>
                            </a>
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 rounded-3 mb-2 transition-all">
                                <span><i class="bi bi-file-earmark-pdf me-2"></i> Assignments</span>
                                <span class="badge bg-light text-muted rounded-pill">5</span>
                            </a>
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 rounded-3 mb-2 transition-all">
                                <span><i class="bi bi-play-circle me-2"></i> Video Guides</span>
                                <span class="badge bg-light text-muted rounded-pill">8</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Recent File Uploads</h5>
                            <div class="input-group input-group-sm w-auto">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-light border-0" placeholder="Search files...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="border-0">NAME</th>
                                        <th class="border-0">BATCH</th>
                                        <th class="border-0">SIZE</th>
                                        <th class="border-0 text-end">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover-lift transition-all">
                                        <td class="border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger me-3"><i
                                                        class="bi bi-file-pdf fs-5"></i></div>
                                                <div>
                                                    <div class="fw-bold small">Project_Final_Guidelines.pdf</div>
                                                    <small class="text-muted tiny">Uploaded 2 hours ago</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0"><span class="badge bg-light text-dark rounded-pill">Morning
                                                Batch</span></td>
                                        <td class="border-0 small text-muted">2.4 MB</td>
                                        <td class="border-0 text-end">
                                            <button class="btn btn-sm btn-light border rounded-circle"><i
                                                    class="bi bi-download"></i></button>
                                            <button class="btn btn-sm btn-light border rounded-circle text-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr class="hover-lift transition-all">
                                        <td class="border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3"><i
                                                        class="bi bi-file-earmark-word fs-5"></i></div>
                                                <div>
                                                    <div class="fw-bold small">Lesson_3_Notes.docx</div>
                                                    <small class="text-muted tiny">Uploaded Yesterday</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0"><span class="badge bg-light text-dark rounded-pill">All
                                                Batches</span></td>
                                        <td class="border-0 small text-muted">1.1 MB</td>
                                        <td class="border-0 text-end">
                                            <button class="btn btn-sm btn-light border rounded-circle"><i
                                                    class="bi bi-download"></i></button>
                                            <button class="btn btn-sm btn-light border rounded-circle text-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Upload New Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FILE TITLE</label>
                            <input type="text" class="form-control rounded-3" placeholder="Enter resource name...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">TARGET BATCH</label>
                            <select class="form-select rounded-3" name="batch_id">
                                <option value="">All Assigned Batches</option>
                                @php $teacher = auth()->user()->teacher; @endphp
                                @foreach($teacher->batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">SELECT FILE</label>
                            <div class="upload-drop-zone border-2 border-dashed rounded-4 p-5 text-center bg-light"
                                style="border-style: dashed !important; border-color: #dee2e6 !important;">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 mb-3 d-block"></i>
                                <p class="text-muted small mb-0">Drag & drop files here or click to browse</p>
                                <input type="file" class="d-none" id="fileInput shadow-none">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Start Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection