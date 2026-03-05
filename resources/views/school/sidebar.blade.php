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
                        <h6 class="fw-bold mb-0 text-white">{{ $label['admin_portal'] }}</h6>
                        <small class="text-white opacity-75 tiny">
                                @if($isSport) Sports Academy @else School Management @endif
                        </small>
                </div>
        </div>
</div>

<ul class="nav flex-column sidebar-nav">
        <li class="nav-label tiny text-white opacity-75 mb-2" style="padding-left: 15px;">Main</li>

        <li class="nav-item">
                <a href="{{ route('school.dashboard') }}"
                        class="nav-link {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
                {{ $label['section_academic'] }}
        </li>

        <li class="nav-item">
                <a href="{{ route('school.courses.index') }}"
                        class="nav-link {{ request()->routeIs('school.courses.*') ? 'active' : '' }}">
                        <i class="bi bi-book-half"></i>
                        <span>{{ $label['courses'] }}</span>
                </a>
        </li>

        @if(!$isSport)
                <li class="nav-item">
                        <a href="{{ route('school.classes.index') }}"
                                class="nav-link {{ request()->routeIs('school.classes.*') ? 'active' : '' }}">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                <span>{{ $label['classes'] }}</span>
                        </a>
                </li>
        @endif

        <li class="nav-item">
                <a href="{{ route('school.subjects.index') }}"
                        class="nav-link {{ request()->routeIs('school.subjects.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>{{ $label['subjects'] }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.batches.index') }}"
                        class="nav-link {{ request()->routeIs('school.batches.*') ? 'active' : '' }}">
                        <i class="bi bi-collection-fill"></i>
                        <span>{{ $label['batches'] }}</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
                {{ $label['section_people'] }}
        </li>

        <li class="nav-item">
                <a href="{{ route('school.students.index') }}"
                        class="nav-link {{ request()->routeIs('school.students.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ $label['students'] }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.teachers.index') }}"
                        class="nav-link {{ request()->routeIs('school.teachers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>{{ $label['teachers'] }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.attendance.index') }}"
                        class="nav-link {{ request()->routeIs('school.attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-check-fill"></i>
                        <span>{{ $label['attendance'] }}</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
                {{ $label['section_finance'] }}
        </li>

        <li class="nav-item">
                <a href="{{ route('school.payments.collect') }}"
                        class="nav-link {{ request()->routeIs('school.payments.collect') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack text-white"></i> <span class="fw-bold">Collect Fees</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.fees.index') }}"
                        class="nav-link {{ request()->routeIs('school.fees.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2 text-white"></i> <span>Fee Ledger / History</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.invoices.index') }}"
                        class="nav-link {{ request()->routeIs('school.invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> <span>Invoice Receipts</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.expenses.index') }}"
                        class="nav-link {{ request()->routeIs('school.expenses.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle"></i>
                        <span>{{ $label['expenses'] }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.fee-plans.index') }}"
                        class="nav-link {{ request()->routeIs('school.fee-plans.*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill text-white"></i> <span class="text-white opacity-75">Add fee
                                plans</span>
                </a>
        </li>

        <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">More</li>

        <li class="nav-item">
                <a href="{{ route('school.levels.index') }}"
                        class="nav-link {{ request()->routeIs('school.levels.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-steps"></i> <span>Levels</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.events.index') }}"
                        class="nav-link {{ request()->routeIs('school.events.*') ? 'active' : '' }}">
                        <i class="bi bi-trophy-fill"></i>
                        <span>{{ $label['events'] }}</span>
                </a>
        </li>

        <li class="nav-item">
                <a href="{{ route('school.reports.index') }}"
                        class="nav-link {{ request()->routeIs('school.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> <span>Reports</span>
                </a>
        </li>

        <li class="nav-item mt-4 pb-4">
                <a href="{{ route('logout') }}" class="nav-link text-danger opacity-75"
                        onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </a>
        </li>
</ul>

<div
        class="sidebar-footer mt-auto p-3 mx-2 mb-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
        <div class="d-flex align-items-center">
                <div class="shrink-0 bg-white bg-opacity-20 p-2 rounded-3 me-2">
                        <i class="bi bi-patch-check-fill text-white"></i>
                </div>
                <div>
                        <p class="mb-0 tiny fw-bold text-white">Active Plan</p>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">Enterprise Access</p>
                </div>
        </div>
</div>