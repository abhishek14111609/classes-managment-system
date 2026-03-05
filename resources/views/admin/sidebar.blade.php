<div class="sidebar-brand-wrapper mb-4">
    <div class="sidebar-brand d-flex align-items-center">
        <div class="brand-logo bg-white p-2 rounded-3 me-2 shadow-sm">
            <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-white">System Admin</h6>
            <small class="text-white opacity-75 tiny">Main Configuration</small>
        </div>
    </div>
</div>

<ul class="nav flex-column sidebar-nav">
    <li class="nav-label tiny text-white opacity-75 mb-2" style="padding-left: 15px;">Executive Console</li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> <span>Insights Hub</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Institutional Management</li>

    <li class="nav-item">
        <a href="{{ route('admin.schools.index') }}"
            class="nav-link {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}">
            <i class="bi bi-buildings-fill"></i> <span>School Registry</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.plans.index') }}"
            class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> <span>Subscription Plans</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.subscriptions.index') }}"
            class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card-2-back-fill"></i> <span>Renewal Tracking</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Operations & Security</li>

    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}"
            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> <span>User Control</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.activity-logs.index') }}"
            class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
            <i class="bi bi-activity"></i> <span>Security Audit Logs</span>
        </a>
    </li>

    <li class="nav-item mt-4 pb-4">
        <a href="{{ route('logout') }}" class="nav-link text-danger opacity-75"
            onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> <span>Master Logout</span>
        </a>
    </li>
</ul>

<div
    class="sidebar-footer mt-auto p-3 mx-2 mb-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
    <div class="d-flex align-items-center">
        <div class="shrink-0 bg-white bg-opacity-20 p-2 rounded-3 me-2">
            <i class="bi bi-gear-fill text-white"></i>
        </div>
        <div>
            <p class="mb-0 tiny fw-bold text-white">Global Version</p>
            <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">v2.4.0 High-Fidelity</p>
        </div>
    </div>
</div>