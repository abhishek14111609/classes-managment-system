<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webvibe - Next Gen Classes Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Modern UI -->
    <link rel="stylesheet" href="{{ asset('css/modern-ui.css') }}">

    <style>
        /* Specific adjustments for landing page only */
        .landing-gradient-text {
            background: linear-gradient(135deg, #111827 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-body text-main">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top landing-nav">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-main font-heading" href="/">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                Webvibe
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2 text-main"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item"><a class="nav-link fw-medium text-muted" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium text-muted" href="#roles">Roles</a></li>
                    <li class="nav-item">
                        <a class="btn btn-primary rounded-pill px-4" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-2"></i> Portal Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="landing-hero d-flex align-items-center min-vh-100 position-relative overflow-hidden">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div
                        class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary bg-opacity-10 text-primary fw-bold small mb-4">
                        <span class="badge bg-primary rounded-pill">NEW</span>
                        <span>Version 2.0 Released</span>
                    </div>
                    <h1 class="display-3 font-heading fw-bold mb-4 lh-sm">
                        Manage your classes with <span class="landing-gradient-text">elegance & power.</span>
                    </h1>
                    <p class="lead text-muted mb-5 lh-lg" style="max-width: 500px;">
                        The all-in-one platform for schools, tuitions, and academies. Automate attendance, fees, and
                        results tailored for modern education.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('login') }}"
                            class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg">
                            Get Started Now <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#features"
                            class="btn btn-outline-secondary btn-lg rounded-pill px-5 py-3 fw-bold bg-white ml-sm-3">
                            Explore Features
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-5 pt-4 border-top border-secondary border-opacity-10">
                        <div>
                            <h4 class="fw-bold m-0 font-heading">500+</h4>
                            <small class="text-muted text-uppercase fw-bold"
                                style="font-size: 0.7rem;">Institutions</small>
                        </div>
                        <div>
                            <h4 class="fw-bold m-0 font-heading">10k+</h4>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Students</small>
                        </div>
                        <div>
                            <h4 class="fw-bold m-0 font-heading">99.9%</h4>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Uptime</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <!-- Abstract Decoration -->
                        <div class="position-absolute top-50 start-50 translate-middle bg-primary rounded-circle blur-3xl opacity-20"
                            style="width: 400px; height: 400px; filter: blur(100px); z-index: -1;"></div>

                        <!-- Main Dashboard Preview Card -->
                        <div class="card border-0 shadow-lg p-3 rotate-card mb-4 transform-style-3d"
                            style="transform: perspective(1000px) rotateY(-10deg) rotateX(5deg);">
                            <div
                                class="card-header border-bottom bg-white d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark">Dashboard Overview</span>
                                <div class="d-flex gap-1">
                                    <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                                    <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                                    <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                                </div>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="bg-white p-3 rounded-3 shadow-sm">
                                            <small class="text-muted d-block mb-1">Total Students</small>
                                            <h4 class="fw-bold mb-0">1,245</h4>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white p-3 rounded-3 shadow-sm">
                                            <small class="text-muted d-block mb-1">Monthly Revenue</small>
                                            <h4 class="fw-bold mb-0 text-success">₹45,200</h4>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="bg-white p-3 rounded-3 shadow-sm" style="height: 100px;">
                                            <small class="text-muted d-block mb-2">Attendance Trend</small>
                                            <div class="w-100 bg-light rounded-pill overflow-hidden"
                                                style="height: 6px;">
                                                <div class="bg-primary h-100" style="width: 75%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <small class="text-muted" style="font-size: 0.7rem;">Mon</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">Tue</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">Wed</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">Thu</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">Fri</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5 mw-md mx-auto" style="max-width: 700px;">
                <span class="text-primary fw-bold small text-uppercase tracking-wider">Features</span>
                <h2 class="display-5 font-heading fw-bold mb-3 mt-2">Everything run smoothly</h2>
                <p class="text-muted fs-5">Designed with attention to detail to help you manage your educational
                    institution without the headache.</p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-primary bg-white shadow-sm">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Role-Based Access</h4>
                        <p class="text-muted mb-0">Separate, secure portals for Administrators, Teachers, and
                            Students/Parents ensures privacy and focus.</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-success bg-white shadow-sm">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Smart Fee Management</h4>
                        <p class="text-muted mb-0">Automated invoices, pending fee alerts, and detailed financial
                            reports keep your revenue flowing.</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-info bg-white shadow-sm">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Instant Attendance</h4>
                        <p class="text-muted mb-0">Mark attendance in seconds. Students visualize their records
                            instantly on their dashboard.</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-warning bg-white shadow-sm">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Analytics & Reports</h4>
                        <p class="text-muted mb-0">Make data-driven decisions with real-time graphs on student
                            performance and financial health.</p>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-danger bg-white shadow-sm">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sports & Events</h4>
                        <p class="text-muted mb-0">Manage extracurricular activities, tournaments, and events alongside
                            academics seamlessly.</p>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4 rounded-4 border bg-light bg-opacity-50">
                        <div class="feature-icon-box text-primary bg-white shadow-sm">
                            <i class="bi bi-cloud-check"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Secure Cloud</h4>
                        <p class="text-muted mb-0">Your data is encrypted and backed up daily. Access your institute
                            from anywhere, anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Dark Section -->
    <section id="roles" class="py-5 bg-dark text-white position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 bg-primary opacity-25 rounded-circle blur-3xl"
            style="width: 600px; height: 600px; filter: blur(150px); transform: translate(30%, -30%);"></div>

        <div class="container py-5 position-relative z-2">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2 class="display-5 font-heading fw-bold mb-4" style="color: white;">Unified Platform.<br><span
                            class="text-gradient">Three Perspectives.</span></h2>
                    <p class="lead text-white opacity-75 mb-5">
                        Experience a system that adapts to who you are. Whether you run the school, teach a class, or
                        learn a subject.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-4 py-3 fw-bold">
                        Access Portal
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div
                                class="p-4 rounded-4 bg-blue bg-opacity-5 border border-white border-opacity-60 h-100 ">
                                <h4 class="fw-bold text-white mb-2"><i class="bi bi-star-fill text-warning me-2"></i>
                                    Admin</h4>
                                <p class="text-white opacity-75 small mb-0">Total control over finances, staff,
                                    students, and
                                    system configurations.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 bg-blue bg-opacity-5 border border-white border-opacity-50 h-100">
                                <h4 class="fw-bold text-white mb-2"><i class="bi bi-briefcase-fill text-info me-2"></i>
                                    Teacher</h4>
                                <p class="text-white opacity-75 small mb-0">Streamlined tools for grading, attendance,
                                    and
                                    student communication.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 bg-blue bg-opacity-5 border border-white border-opacity-25 h-100">
                                <h4 class="fw-bold text-white mb-2"><i
                                        class="bi bi-mortarboard-fill text-success me-2"></i> Student</h4>
                                <p class="text-white opacity-75 small mb-0">Personalized dashboard for results,
                                    schedule, and
                                    fee status.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-white border-top">
        <div class="container text-center">
            <a class="d-inline-flex align-items-center gap-2 fw-bold fs-4 text-main font-heading text-decoration-none mb-4"
                href="/">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                Webvibe
            </a>
            <div class="mb-4">
                <a href="javascript:void(0)" class="text-muted text-decoration-none mx-3">Privacy</a>
                <a href="javascript:void(0)" class="text-muted text-decoration-none mx-3">Terms</a>
                <a href="javascript:void(0)" class="text-muted text-decoration-none mx-3">Support</a>
                <a href="javascript:void(0)" class="text-muted text-decoration-none mx-3">Contact</a>
            </div>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Classes Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>