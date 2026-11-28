
<nav class="navbar navbar-expand-lg portal-navbar">

    <div class="container-xl">

        {{-- Brand --}}
        <a class="portal-brand navbar-brand"
            href="{{ route('patient.dashboard') }}">

            <i class="bi bi-heart-pulse"></i>
            DentalCare<span>.</span>

        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#patientNavbar"
            aria-controls="patientNavbar"
            aria-expanded="false"
            aria-label="Buka navigasi">

            <i class="bi bi-list fs-3"></i>

        </button>

        {{-- Navigation --}}
        <div class="collapse navbar-collapse"
            id="patientNavbar">

            <ul class="navbar-nav portal-nav mx-auto">

                <li class="nav-item">

                    <a href="{{ route('patient.dashboard') }}"
                        class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">

                        <i class="bi bi-house-door me-1"></i>
                        Beranda

                    </a>

                </li>

                <li class="nav-item">

                    <a href="{{ route('patient.reservations.index') }}"
                        class="nav-link
                        {{ request()->routeIs('patient.reservations.*')
                            ? 'active'
                            : '' }}">

                        <i class="bi bi-calendar-check me-1"></i>
                        Reservasi

                    </a>

                </li>

                <li class="nav-item">

                    <a href="{{ route('patient.announcements.index') }}"
                        class="nav-link
                            {{ request()->routeIs('patient.announcements.*')
                                ? 'active'
                                : '' }}">

                        <i class="bi bi-megaphone me-1"></i>

                        Pengumuman

                    </a>

                </li>

            </ul>

            {{-- Profile --}}
            <div class="dropdown portal-profile">

                <button type="button"
                    class="btn portal-profile-button dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <div class="portal-avatar">

                        {{ mb_strtoupper(
                            mb_substr(auth()->user()->name, 0, 1)
                        ) }}

                    </div>

                    <div class="portal-profile-info">

                        <span>{{ auth()->user()->name }}</span>
                        <small>Pasien</small>

                    </div>

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

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
                                class="dropdown-item text-danger">

                                <i class="bi bi-box-arrow-right me-2"></i>
                                Keluar

                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>
