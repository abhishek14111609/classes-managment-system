<div class="sidebar-brand-wrapper mb-4">
        <div class="sidebar-brand d-flex align-items-center">
                <div class="brand-logo bg-white p-2 rounded-3 me-2 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; overflow: hidden;">
                        @if(auth()->user()->school && auth()->user()->school->logo)
                                <img src="{{ asset('storage/' . auth()->user()->school->logo) }}" alt="Logo" class="img-fluid"
                                        style="max-height: 100%; object-fit: contain;">
                        @else
                                <i class="bi bi-buildings-fill text-primary fs-4"></i>
                        @endif
                </div>
                <div>
                        <h6 class="fw-bold mb-0 text-white">
                                {{ auth()->user()->school->institute_type === 'sport' ? 'Sports Academy Admin' : 'School Admin' }}
                        </h6>
                        <small
                                class="text-white opacity-75 tiny">{{ auth()->user()->school->institute_type === 'sport' ? 'Command Center' : 'Institutional Control' }}</small>
                </div>
        </div>
</div>

<ul class="nav flex-column sidebar-nav">
        <li class="nav-label tiny text-white opacity-75 mb-2" style="padding-left: 15px;">Main Console</li>

        <li class="nav-item">
                <a href="{{ route('school.dashboard') }}"
                        class="nav-link {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
                {{ auth()->user()->school->institute_type === 'sport' ? 'Training & Sports Core' : 'Academic Core' }}
        </li>

        <li class="nav-item">
                <a href="{{ route('school.courses.index') }}"
                        class="nav-link {{ request()->routeIs('school.courses.*') ? 'active' : '' }}">
                        <i class="bi bi-book-half"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Programs & Disciplines' : 'Programs & Courses' }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.classes.index') }}"
                        class="nav-link {{ request()->routeIs('school.classes.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Levels & Teams' : 'Levels & Classes' }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.subjects.index') }}"
                        class="nav-link {{ request()->routeIs('school.subjects.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Activities & Exercises' : 'Syllabus & Subjects' }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.batches.index') }}"
                        class="nav-link {{ request()->routeIs('school.batches.*') ? 'active' : '' }}">
                        <i class="bi bi-collection-fill"></i> <span>Time Batches</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Human Resources</li>

        <li class="nav-item">
                <a href="{{ route('school.students.index') }}"
                        class="nav-link {{ request()->routeIs('school.students.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Athletes Registry' : 'Students Registry' }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.teachers.index') }}"
                        class="nav-link {{ request()->routeIs('school.teachers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Coaches & Staff' : 'Faculty & Staff' }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.attendance.index') }}"
                        class="nav-link {{ request()->routeIs('school.attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-check-fill"></i> <span>Attendance Logs</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Financials</li>

        <li class="nav-item">
                <a href="{{ route('school.fee-plans.index') }}"
                        class="nav-link {{ request()->routeIs('school.fee-plans.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> <span>Fee Templates</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.fees.index') }}"
                        class="nav-link {{ request()->routeIs('school.fees.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i> <span>Collection Desk</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.invoices.index') }}"
                        class="nav-link {{ request()->routeIs('school.invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff"></i> <span>Official Invoices</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.reports.pending-fees') }}"
                        class="nav-link {{ request()->routeIs('school.reports.pending-fees') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> <span>Pending Fees</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.expenses.index') }}"
                        class="nav-link {{ request()->routeIs('school.expenses.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle-fill"></i>
                        <span>{{ auth()->user()->school->institute_type === 'sport' ? 'Academy Expenses' : 'School Expenses' }}</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Operations</li>

        <li class="nav-item">
                <a href="{{ route('school.levels.index') }}"
                        class="nav-link {{ request()->routeIs('school.levels.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-steps"></i> <span>Levels & Categories</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.events.index') }}"
                        class="nav-link {{ request()->routeIs('school.events.*') ? 'active' : '' }}">
                        <i class="bi bi-trophy-fill"></i> <span>Sports & Events</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.reports.index') }}"
                        class="nav-link {{ request()->routeIs('school.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> <span>Insight Reports</span>
                </a>
        </li>

        <li class="nav-item mt-4 pb-4">
                <a href="#" class="nav-link text-danger opacity-75"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="bi bi-box-arrow-right"></i> <span>Secure Logout</span>
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                </form>
        </li>
</ul>

<div
        class="sidebar-footer mt-auto p-3 mx-2 mb-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
        <div class="d-flex align-items-center">
                <div class="shrink-0 bg-white bg-opacity-20 p-2 rounded-3 me-2">
                        <i class="bi bi-patch-check-fill text-white"></i>
                </div>
                <div>
                        <p class="mb-0 tiny fw-bold text-white">Managed Plan</p>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">Enterprise Access</p>
                </div>
        </div>
</div>