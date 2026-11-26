<header class="admin-navbar">

    <div class="d-flex align-items-center gap-3">

        <button type="button"
            class="btn sidebar-toggle d-lg-none"
            id="sidebar-toggle"
            aria-label="Buka navigasi"
            aria-controls="admin-sidebar"
            aria-expanded="false">

            <i class="bi bi-list fs-4"></i>

        </button>

        <div class="admin-breadcrumb">

            Admin

            <i class="bi bi-chevron-right mx-2"></i>

            <span>@yield('page-title', 'Dashboard')</span>

        </div>

    </div>

    <div class="d-flex align-items-center gap-3">

        <div class="text-end d-none d-sm-block">

            <div class="fw-semibold">
                {{ auth()->user()->name }}
            </div>

            <small class="text-secondary">
                Administrator
            </small>

        </div>

        <div class="admin-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

    </div>

</header>

<div class="sidebar-overlay" id="sidebar-overlay"></div>