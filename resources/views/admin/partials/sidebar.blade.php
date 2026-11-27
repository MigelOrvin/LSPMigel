<aside class="admin-sidebar" id="admin-sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">

        <a href="{{ route('admin.dashboard') }}"
            class="text-decoration-none">

            <i class="bi bi-heart-pulse"></i>
            DentalCare<span>.</span>

        </a>

        <small>ADMIN PORTAL</small>

    </div>

    {{-- Navigation --}}
    <nav class="sidebar-navigation">

        <div class="sidebar-label">
            MENU UTAMA
        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2"></i>
            Dashboard

        </a>

        <div class="sidebar-label">
            MANAJEMEN
        </div>

        <a href="{{ route('admin.patients.index') }}"
            class="sidebar-link
            {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">

            <i class="bi bi-people"></i>
            Pasien

        </a>

        <a href="{{ route('admin.doctors.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">

            <i class="bi bi-person-vcard"></i>
            Dokter

        </a>

        <button class="sidebar-link" disabled>
            <i class="bi bi-calendar-week"></i>
            Jadwal Praktik
        </button>

        <a href="{{ route('admin.reservations.index') }}"
            class="sidebar-link
                {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">

            <i class="bi bi-calendar-check"></i>

            <span>Reservasi</span>

        </a>

        <a href="{{ route('admin.payments.index') }}"
            class="sidebar-link
                {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">

            <i class="bi bi-wallet2"></i>

            <span>Pembayaran</span>

        </a>

        <button class="sidebar-link" disabled>
            <i class="bi bi-megaphone"></i>
            Pengumuman
        </button>

    </nav>

    {{-- Admin Profile --}}
    <div class="sidebar-profile">

        <div class="admin-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div>
            <div class="fw-semibold">
                {{ auth()->user()->name }}
            </div>

            <small class="text-secondary">
                Administrator
            </small>
        </div>

    </div>

</aside>