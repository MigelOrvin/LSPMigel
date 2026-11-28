
@extends('layouts.admin')

@section('title', 'Clinic Overview - DentalCare')
@section('page-title', 'Dashboard')

@section('admin-content')

@php
    $statistics = [
        [
            'label' => 'Pasien Terverifikasi',
            'value' => $approvedPatients,
            'icon' => 'bi-people',
            'color' => 'teal',
            'href' => route('admin.patients.index', [
                'status' => 'approved'
            ])
        ],
        [
            'label' => 'Pemeriksaan Hari Ini',
            'value' => $todayAppointments,
            'icon' => 'bi-calendar-check',
            'color' => 'blue',
            'href' => route('admin.reservations.index', [
                'date' => $now->toDateString(),
                'sort' => 'schedule'
            ])
        ],
        [
            'label' => 'Pembayaran Terverifikasi',
            'value' => 'Rp' . number_format(
                $totalVerifiedPayments, 0, ',', '.'
            ),
            'icon' => 'bi-wallet2',
            'color' => 'green',
            'href' => route('admin.payments.index', [
                'status' => 'approved'
            ])
        ],
        [
            'label' => 'Dokter Aktif',
            'value' => $activeDoctors,
            'icon' => 'bi-person-vcard',
            'color' => 'orange',
            'href' => route('admin.doctors.index')
        ]
    ];

    $actions = [
        [
            'title' => 'Verifikasi Akun Pasien',
            'description' => 'Pendaftaran pasien baru',
            'count' => $pendingPatients,
            'icon' => 'bi-person-plus',
            'color' => 'teal',
            'href' => route('admin.patients.index', [
                'status' => 'pending'
            ])
        ],
        [
            'title' => 'Verifikasi Pembayaran',
            'description' => 'Bukti transfer menunggu pemeriksaan',
            'count' => $pendingPayments,
            'icon' => 'bi-wallet2',
            'color' => 'orange',
            'href' => route('admin.payments.index', [
                'status' => 'pending'
            ])
        ],
        [
            'title' => 'Persetujuan Reservasi',
            'description' => 'Reservasi dengan pembayaran terverifikasi',
            'count' => $pendingReservations,
            'icon' => 'bi-calendar-check',
            'color' => 'green',
            'href' => route('admin.reservations.index', [
                'status' => 'pending'
            ])
        ]
    ];
@endphp

<div class="clinic-dashboard">

    {{-- ========================================
         PAGE HEADING
    ======================================== --}}

    <div class="clinic-heading mb-4">

        <div>

            <span class="clinic-eyebrow">
                DENTALCARE / OVERVIEW
            </span>

            <h1>Clinic Overview</h1>

            <p>
                Selamat datang, {{ auth()->user()->name }}.
                Berikut ringkasan operasional klinik Anda.
            </p>

        </div>

        <div class="clinic-current-date">

            <i class="bi bi-calendar3"></i>

            {{ $now->locale('id')->translatedFormat('l, d F Y') }}

        </div>

    </div>


    {{-- ========================================
         OVERVIEW STATISTICS
    ======================================== --}}

    <div class="row g-2 g-md-3 mb-4">

        @foreach ($statistics as $stat)

            <div class="col-6 col-xl-3">

                <a href="{{ $stat['href'] }}"
                    class="clinic-stat-card">

                    <div class="clinic-stat-top">

                        <div class="clinic-icon
                            clinic-icon-{{ $stat['color'] }}">

                            <i class="bi {{ $stat['icon'] }}"></i>

                        </div>

                        <i class="bi bi-arrow-up-right
                            clinic-stat-arrow">
                        </i>

                    </div>

                    <span class="clinic-stat-label">

                        {{ $stat['label'] }}

                    </span>

                    <strong class="clinic-stat-value">

                        {{ $stat['value'] }}

                    </strong>

                </a>

            </div>

        @endforeach

    </div>


    {{-- ========================================
         ANALYTICS & ACTION CENTER
    ======================================== --}}

    <div class="row g-3 mb-4">

        {{-- Reservation Chart --}}
        <div class="col-12 col-xl-7">

            <section class="clinic-panel h-100">

                <div class="clinic-panel-heading">

                    <div>

                        <h5>Aktivitas Reservasi</h5>

                        <p>Pengajuan dalam 7 hari terakhir.</p>

                    </div>

                    <span class="clinic-period">

                        7 Hari Terakhir

                    </span>

                </div>

                <div class="clinic-chart-summary">

                    <strong>{{ $weeklyReservations }}</strong>

                    <span>Total pengajuan reservasi</span>

                </div>

                <div class="clinic-chart">

                    {{-- Y Axis --}}
                    <div class="clinic-chart-axis">

                        @for ($i = 4; $i >= 0; $i--)

                            <span>
                                {{ $chartMax * $i / 4 }}
                            </span>

                        @endfor

                    </div>

                    {{-- Chart Plot --}}
                    <div class="clinic-chart-content">

                        <div class="clinic-chart-plot">

                            <div class="clinic-chart-grid">

                                @for ($i = 0; $i < 5; $i++)

                                    <div></div>

                                @endfor

                            </div>

                            <div class="clinic-chart-columns">

                                @foreach ($reservationChart as $item)

                                    <div class="clinic-chart-column">

                                        <div class="clinic-chart-bar
                                            {{ $item['is_today']
                                                ? 'is-today'
                                                : '' }}"
                                            style="--bar-height: {{ $item['total'] / $chartMax * 100 }}%;"
                                            title="{{ $item['label'] }}: {{ $item['total'] }} reservasi">

                                            <span class="clinic-chart-value">

                                                {{ $item['total'] }}

                                            </span>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        <div class="clinic-chart-labels">

                            @foreach ($reservationChart as $item)

                                <div>

                                    <span>{{ $item['day'] }}</span>

                                    <small>{{ $item['label'] }}</small>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                <div class="clinic-chart-footer">

                    <span class="clinic-chart-legend"></span>

                    Jumlah reservasi yang dibuat

                </div>

            </section>

        </div>

        {{-- Action Center --}}
        <div class="col-12 col-xl-5">

            <section class="clinic-panel h-100">

                <div class="clinic-panel-heading">

                    <div>

                        <h5>Memerlukan Tindakan</h5>

                        <p>Pengajuan yang perlu ditinjau.</p>

                    </div>

                    <span class="clinic-action-count">

                        {{ $totalPendingActions }}

                    </span>

                </div>

                <div class="clinic-action-list">

                    @foreach ($actions as $action)

                        <a href="{{ $action['href'] }}"
                            class="clinic-action-item">

                            <div class="clinic-icon
                                clinic-icon-{{ $action['color'] }}">

                                <i class="bi {{ $action['icon'] }}"></i>

                            </div>

                            <div class="clinic-action-info">

                                <h6>{{ $action['title'] }}</h6>

                                <small>
                                    {{ $action['description'] }}
                                </small>

                            </div>

                            <div class="clinic-action-right">

                                <strong>{{ $action['count'] }}</strong>

                                <i class="bi bi-chevron-right"></i>

                            </div>

                        </a>

                    @endforeach

                </div>

                @if ($totalPendingActions === 0)

                    <div class="clinic-action-notice">

                        <i class="bi bi-check-circle"></i>

                        Semua pengajuan telah ditangani.

                    </div>

                @else

                    <div class="clinic-action-notice has-pending">

                        <i class="bi bi-info-circle"></i>

                        Selesaikan pengajuan untuk mempercepat
                        pelayanan pasien.

                    </div>

                @endif

            </section>

        </div>

    </div>


    {{-- ========================================
         APPOINTMENTS & PRACTICING DOCTORS
    ======================================== --}}

    <div class="row g-3 mb-4">

        {{-- Today's Appointments --}}
        <div class="col-12 col-xl-7">

            <section class="clinic-panel h-100">

                <div class="clinic-panel-heading">

                    <div>

                        <h5>Pemeriksaan Hari Ini</h5>

                        <p>Jadwal pasien yang telah disetujui.</p>

                    </div>

                    <a href="{{ route(
                        'admin.reservations.index',
                        [
                            'date' => $now->toDateString(),
                            'sort' => 'schedule'
                        ]
                    ) }}"
                        class="clinic-text-link">

                        Lihat Semua
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

                <div class="clinic-timeline">

                    @forelse ($appointments as $reservation)

                        @php
                            $appointmentStart =
                                \Carbon\CarbonImmutable::parse(
                                    $reservation->reservation_date
                                        ->format('Y-m-d')
                                    . ' '
                                    . $reservation->start_time,
                                    'Asia/Jakarta'
                                );

                            $appointmentEnd =
                                \Carbon\CarbonImmutable::parse(
                                    $reservation->reservation_date
                                        ->format('Y-m-d')
                                    . ' '
                                    . $reservation->end_time,
                                    'Asia/Jakarta'
                                );

                            $appointmentStatus = match (true) {
                                $reservation->status === 'completed',
                                $appointmentEnd->lte($now)
                                    => ['Selesai', 'completed'],

                                $appointmentStart->lte($now)
                                    => ['Sedang Berlangsung', 'ongoing'],

                                default
                                    => ['Terjadwal', 'upcoming']
                            };
                        @endphp

                        <div class="clinic-timeline-item">

                            <div class="clinic-timeline-time">

                                <strong>
                                    {{ substr(
                                        $reservation->start_time,
                                        0, 5
                                    ) }}
                                </strong>

                                <small>
                                    {{ substr(
                                        $reservation->end_time,
                                        0, 5
                                    ) }}
                                </small>

                            </div>

                            <div class="clinic-timeline-marker
                                marker-{{ $appointmentStatus[1] }}">

                                <span></span>

                            </div>

                            <div class="clinic-timeline-info">

                                <h6>
                                    {{ $reservation->user->name }}
                                </h6>

                                <small>
                                    {{ $reservation
                                        ->schedule
                                        ->doctor
                                        ->name }}
                                </small>

                            </div>

                            <span class="clinic-appointment-status
                                clinic-status-{{ $appointmentStatus[1] }}">

                                {{ $appointmentStatus[0] }}

                            </span>

                        </div>

                    @empty

                        <div class="clinic-empty">

                            <i class="bi bi-calendar2-check"></i>

                            <h6>Belum ada pemeriksaan hari ini</h6>

                            <p>
                                Jadwal pemeriksaan yang disetujui
                                akan muncul di sini.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>

        </div>

        {{-- Doctor Schedule --}}
        <div class="col-12 col-xl-5">

            <section class="clinic-panel h-100">

                <div class="clinic-panel-heading">

                    <div>

                        <h5>Dokter Praktik Hari Ini</h5>

                        <p>
                            {{ $todayDoctors->count() }}
                            dokter memiliki jadwal praktik.
                        </p>

                    </div>

                    <a href="{{ route('admin.doctors.index') }}"
                        class="clinic-text-link">

                        Lihat Semua
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>

                <div class="clinic-doctor-list">

                    @forelse ($todayDoctors as $doctor)

                        <div class="clinic-doctor-item">

                            <div class="clinic-doctor-photo">

                                @if ($doctor->photo)

                                    <img
                                        src="{{ asset($doctor->photo) }}"
                                        alt="{{ $doctor->name }}">

                                @else

                                    <i class="bi bi-person-fill"></i>

                                @endif

                            </div>

                            <div class="clinic-doctor-info">

                                <h6>{{ $doctor->name }}</h6>

                                @foreach ($doctor->schedules as $schedule)

                                    <small>

                                        <i class="bi bi-clock me-1"></i>

                                        {{ substr(
                                            $schedule->start_time,
                                            0, 5
                                        ) }}
                                        -
                                        {{ substr(
                                            $schedule->end_time,
                                            0, 5
                                        ) }}

                                        WIB

                                    </small>

                                @endforeach

                            </div>

                            <div class="clinic-doctor-indicator">

                                <span></span>

                                Terjadwal

                            </div>

                        </div>

                    @empty

                        <div class="clinic-empty">

                            <i class="bi bi-person-vcard"></i>

                            <h6>Tidak ada jadwal praktik</h6>

                            <p>
                                Tidak ada dokter aktif yang
                                memiliki jadwal hari ini.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>

        </div>

    </div>


    {{-- ========================================
         RECENT ANNOUNCEMENTS
    ======================================== --}}

    <section class="clinic-panel mb-4">

        <div class="clinic-panel-heading">

            <div>

                <h5>Pengumuman Terbaru</h5>

                <p>Informasi yang dipublikasikan kepada pasien.</p>

            </div>

            <a href="{{ route('admin.announcements.index') }}"
                class="clinic-text-link">

                Kelola Pengumuman
                <i class="bi bi-arrow-right ms-1"></i>

            </a>

        </div>

        <div class="row g-3">

            @forelse ($latestAnnouncements as $announcement)

                <div class="col-12 col-lg-4">

                    <a href="{{ route(
                        'admin.announcements.show',
                        $announcement
                    ) }}"
                        class="clinic-announcement">

                        <div class="clinic-announcement-photo">

                            @if ($announcement->image)

                                <img
                                    src="{{ asset(
                                        'storage/' . $announcement->image
                                    ) }}"
                                    alt="{{ $announcement->title }}">

                            @else

                                <i class="bi bi-megaphone"></i>

                            @endif

                        </div>

                        <div class="clinic-announcement-info">

                            <small>

                                {{ $announcement->published_at
                                    ->timezone('Asia/Jakarta')
                                    ->locale('id')
                                    ->translatedFormat('d M Y') }}

                            </small>

                            <h6>{{ $announcement->title }}</h6>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                </div>

            @empty

                <div class="col-12">

                    <div class="clinic-empty">

                        <i class="bi bi-megaphone"></i>

                        <h6>Belum ada pengumuman</h6>

                        <a href="{{ route(
                            'admin.announcements.create'
                        ) }}"
                            class="clinic-text-link">

                            Buat Pengumuman Baru

                        </a>

                    </div>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection
