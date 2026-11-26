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

    {{-- Admin Profile Dropdown --}}
    <div class="dropdown admin-profile-dropdown">

        <button type="button"
            class="btn admin-profile-button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="Menu akun admin">

            <div class="text-end d-none d-sm-block">

                <div class="fw-semibold">
                    {{ auth()->user()->name }}
                </div>

                <small class="text-secondary">
                    Administrator
                </small>

            </div>

            <div class="admin-avatar">
                {{ mb_strtoupper(
                    mb_substr(auth()->user()->name, 0, 1)
                ) }}
            </div>

            <i class="bi bi-chevron-down admin-profile-chevron"></i>

        </button>

        <ul class="dropdown-menu dropdown-menu-end admin-profile-menu">

            <li>
                <h6 class="dropdown-header">
                    Akun Saya
                </h6>
            </li>

            <li>
                <span class="dropdown-item-text small text-secondary">
                    {{ auth()->user()->email }}
                </span>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>

                <form action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button type="submit"
                        class="dropdown-item admin-logout">

                        <i class="bi bi-box-arrow-right me-2"></i>
                        Keluar

                    </button>

                </form>

            </li>

        </ul>

    </div>

</header>

<div class="sidebar-overlay" id="sidebar-overlay"></div>