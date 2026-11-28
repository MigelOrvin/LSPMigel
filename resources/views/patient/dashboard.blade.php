
@extends('layouts.patient')

@section('title', 'Beranda - DentalCare')

@section('patient-content')

@php
    $days = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu'
    ];

    $reservationStatuses = [
        'awaiting_payment' => 'Menunggu Pembayaran',
        'payment_pending' => 'Verifikasi Pembayaran',
        'reservation_pending' => 'Verifikasi Reservasi',
        'pending' => 'Menunggu Verifikasi',
        'approved' => 'Reservasi Disetujui',
        'confirmed' => 'Terkonfirmasi'
    ];
@endphp

{{-- ========================================
     WELCOME HERO
======================================== --}}

<section class="portal-hero discovery-hero mb-5">

    <div class="portal-hero-content">

        <span class="portal-eyebrow">
            DENTALCARE PATIENT PORTAL
        </span>

        <div class="discovery-greeting">

            Halo, {{ auth()->user()->name }}! 👋

        </div>

        <h1>
            Temukan dokter untuk<br class="d-none d-md-block">
            senyum sehatmu.
        </h1>

        <p>
            Pilih dokter gigi dan jadwal pemeriksaan
            yang sesuai dengan kebutuhan Anda.
        </p>

        <div class="d-flex flex-wrap gap-2">

            <a href="#available-doctors"
                class="btn portal-hero-button">

                <i class="bi bi-calendar-plus me-2"></i>
                Buat Reservasi

            </a>

            <a href="{{ route('patient.reservations.index') }}"
                class="btn discovery-secondary-button">

                <i class="bi bi-ticket-perforated me-2"></i>
                Reservasi Saya

            </a>

        </div>

    </div>

    <div class="portal-hero-decoration">

        <i class="bi bi-heart-pulse"></i>

    </div>

</section>


{{-- ========================================
     DOCTOR CALENDAR
======================================== --}}

<section id="doctor-calendar"
    class="discovery-section mb-5">

    <div class="discovery-section-heading">

        <div>

            <span class="discovery-eyebrow">
                PRACTICE SCHEDULE
            </span>

            <h4>Jadwal Praktik Dokter</h4>

            <p>
                Lihat ketersediaan jadwal pemeriksaan
                dalam tujuh hari ke depan.
            </p>

        </div>

        <div class="discovery-period">

            <i class="bi bi-calendar-week"></i>

            {{ $calendarDates[0]['label'] }}
            -
            {{ $calendarDates[6]['label'] }}

        </div>

    </div>

    <div class="discovery-calendar">

        @if ($doctors->isNotEmpty())

            <div class="discovery-calendar-scroll">

                <table class="discovery-calendar-table">

                    <thead>

                        <tr>

                            <th class="calendar-doctor-column">

                                Dokter Gigi

                            </th>

                            @foreach ($calendarDates as $date)

                                <th class="{{ $date['is_today']
                                    ? 'calendar-today'
                                    : '' }}">

                                    <span class="calendar-day">

                                        {{ $date['day'] }}

                                    </span>

                                    <strong class="calendar-date">

                                        {{ $date['label'] }}

                                    </strong>

                                    @if ($date['is_today'])

                                        <small class="calendar-today-label">
                                            Hari ini
                                        </small>

                                    @endif

                                </th>

                            @endforeach

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($doctors as $doctor)

                            <tr>

                                <td class="calendar-doctor-column">

                                    <div class="calendar-doctor">

                                        <div class="calendar-doctor-photo">

                                            @if ($doctor->photo)

                                                <img
                                                    src="{{ asset($doctor->photo) }}"
                                                    alt="{{ $doctor->name }}"
                                                    loading="lazy">

                                            @else

                                                <i class="bi bi-person-fill"></i>

                                            @endif

                                        </div>

                                        <div>

                                            <strong>
                                                {{ $doctor->name }}
                                            </strong>

                                            <small>

                                                Rp{{ number_format(
                                                    $doctor->consultation_fee,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                @foreach ($calendarDates as $date)

                                    @php
                                        $availability =
                                            $doctorAvailability[$doctor->id][$date['date']];
                                    @endphp

                                    <td class="{{ $date['is_today']
                                        ? 'calendar-today-cell'
                                        : '' }}">

                                        @if ($availability['status'] === 'available')

                                            <a href="{{ route(
                                                'patient.reservations.create',
                                                [
                                                    'doctor' => $doctor,
                                                    'date' => $date['date']
                                                ]
                                            ) }}"
                                                class="calendar-slot calendar-slot-available">

                                                <strong>

                                                    {{ $availability['start_time'] }}
                                                    -
                                                    {{ $availability['end_time'] }}

                                                </strong>

                                                <small>

                                                    {{ $availability['available_count'] }}
                                                    slot tersedia

                                                </small>

                                            </a>

                                        @elseif ($availability['status'] === 'unavailable')

                                            <div class="calendar-slot calendar-slot-full">

                                                <strong>

                                                    {{ $availability['start_time'] }}
                                                    -
                                                    {{ $availability['end_time'] }}

                                                </strong>

                                                <small>
                                                    Tidak tersedia
                                                </small>

                                            </div>

                                        @else

                                            <div class="calendar-slot calendar-slot-closed">

                                                <span>—</span>

                                            </div>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="calendar-legend">

                <span>

                    <i class="calendar-legend-dot legend-available"></i>
                    Slot tersedia

                </span>

                <span>

                    <i class="calendar-legend-dot legend-full"></i>
                    Tidak ada slot tersisa

                </span>

                <span>

                    <i class="calendar-legend-dot legend-closed"></i>
                    Tidak praktik

                </span>

                <small class="ms-lg-auto">

                    <i class="bi bi-info-circle me-1"></i>

                    Klik jadwal untuk melakukan reservasi.

                </small>

            </div>

        @else

            <div class="portal-empty-state">

                <i class="bi bi-calendar2-x fs-2"></i>

                <h6 class="mt-3">
                    Belum ada jadwal praktik tersedia
                </h6>

            </div>

        @endif

    </div>

    <p class="calendar-mobile-hint">

        <i class="bi bi-arrow-left-right me-1"></i>
        Geser kalender untuk melihat jadwal lainnya.

    </p>

</section>


{{-- ========================================
     DOCTOR DISCOVERY
======================================== --}}

<section id="available-doctors"
    class="discovery-section mb-5">

    <div class="discovery-section-heading">

        <div>

            <span class="discovery-eyebrow">
                OUR DENTISTS
            </span>

            <h4>Pilih Dokter Gigi Anda</h4>

            <p>
                Temukan dokter dan jadwal praktik
                yang sesuai dengan kebutuhan Anda.
            </p>

        </div>

        <div class="discovery-doctor-count">

            <i class="bi bi-person-vcard me-1"></i>

            {{ $doctors->count() }} Dokter

        </div>

    </div>

    <div class="row g-3 g-xl-4">

        @forelse ($doctors as $doctor)

            @php
                $weeklySlots = collect(
                    $doctorAvailability[$doctor->id] ?? []
                )->sum('available_count');

                $practiceDays = $doctor->schedules
                    ->map(fn ($schedule) =>
                        $days[$schedule->day_of_week] ?? '-'
                    )
                    ->implode(', ');
            @endphp

            <div class="col-12 col-md-6 col-xl-4">

                <article class="discovery-doctor-card">

                    {{-- Doctor Image --}}
                    <div class="discovery-doctor-cover">

                        <i class="bi bi-heart-pulse discovery-cover-decoration"></i>

                        <span class="discovery-availability
                            {{ $weeklySlots > 0
                                ? 'availability-open'
                                : 'availability-closed' }}">

                            <span></span>

                            {{ $weeklySlots > 0
                                ? 'Slot tersedia'
                                : 'Tidak ada slot minggu ini' }}

                        </span>

                        <div class="discovery-doctor-photo">

                            @if ($doctor->photo)

                                <img
                                    src="{{ asset($doctor->photo) }}"
                                    alt="{{ $doctor->name }}"
                                    loading="lazy">

                            @else

                                <i class="bi bi-person-fill"></i>

                            @endif

                        </div>

                    </div>

                    {{-- Doctor Information --}}
                    <div class="discovery-doctor-body">

                        <h5>{{ $doctor->name }}</h5>

                        <p class="discovery-speciality">

                            Dokter Gigi

                        </p>

                        <div class="discovery-doctor-information">

                            <div class="discovery-info-row">

                                <div class="discovery-info-icon">

                                    <i class="bi bi-calendar-week"></i>

                                </div>

                                <div>

                                    <small>Hari Praktik</small>

                                    <strong>

                                        {{ $practiceDays ?: 'Belum tersedia' }}

                                    </strong>

                                </div>

                            </div>

                            <div class="discovery-info-row">

                                <div class="discovery-info-icon">

                                    <i class="bi bi-calendar-check"></i>

                                </div>

                                <div>

                                    <small>
                                        Ketersediaan 7 Hari
                                    </small>

                                    <strong>

                                        {{ $weeklySlots }}
                                        slot pemeriksaan

                                    </strong>

                                </div>

                            </div>

                            <div class="discovery-info-row">

                                <div class="discovery-info-icon">

                                    <i class="bi bi-clock"></i>

                                </div>

                                <div>

                                    <small>Durasi Pemeriksaan</small>

                                    <strong>60 Menit</strong>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Consultation Fee --}}
                    <div class="discovery-doctor-footer">

                        <div>

                            <small>Biaya Konsultasi</small>

                            <strong class="discovery-doctor-fee">

                                Rp{{ number_format(
                                    $doctor->consultation_fee,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </strong>

                        </div>

                        @if ($doctor->schedules->isNotEmpty())

                            <a href="{{ route(
                                'patient.reservations.create',
                                $doctor
                            ) }}"
                                class="btn portal-primary-button">

                                <i class="bi bi-calendar-plus me-1"></i>

                                Buat Reservasi

                            </a>

                        @else

                            <button type="button"
                                class="btn portal-primary-button"
                                disabled>

                                Belum Tersedia

                            </button>

                        @endif

                    </div>

                </article>

            </div>

        @empty

            <div class="col-12">

                <div class="portal-card portal-empty-state">

                    <div class="portal-empty-icon">

                        <i class="bi bi-person-vcard"></i>

                    </div>

                    <h6>Belum ada dokter tersedia</h6>

                    <p class="mb-0">
                        Silakan periksa kembali nanti.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</section>


{{-- ========================================
     UPCOMING RESERVATIONS
======================================== --}}

<section class="discovery-section discovery-upcoming mb-4">

    <div class="discovery-section-heading">

        <div>

            <span class="discovery-eyebrow">
                YOUR APPOINTMENTS
            </span>

            <h4>Reservasi Mendatang</h4>

            <p>
                Pantau jadwal pemeriksaan Anda.
            </p>

        </div>

        <a href="{{ route('patient.reservations.index') }}"
            class="discovery-view-all">

            Lihat Semua

            <i class="bi bi-arrow-right ms-1"></i>

        </a>

    </div>

    <div class="discovery-upcoming-list">

        @forelse ($upcomingReservations as $reservation)

            @php
                $doctor = $reservation->schedule->doctor;
                $displayStatus = $reservation->display_status;

                $statusColor = match ($displayStatus) {
                    'approved', 'confirmed' => 'approved',
                    'awaiting_payment' => 'waiting',
                    default => 'processing'
                };
            @endphp

            <article class="discovery-appointment">

                <div class="discovery-appointment-date">

                    <span>

                        {{ $reservation->reservation_date
                            ->locale('id')
                            ->translatedFormat('M') }}

                    </span>

                    <strong>

                        {{ $reservation->reservation_date
                            ->format('d') }}

                    </strong>

                </div>

                <div class="discovery-appointment-info">

                    <h6>{{ $doctor->name }}</h6>

                    <small>

                        <i class="bi bi-clock me-1"></i>

                        {{ substr($reservation->start_time, 0, 5) }}
                        -
                        {{ substr($reservation->end_time, 0, 5) }}

                        WIB

                    </small>

                </div>

                <div class="discovery-appointment-status">

                    <span class="patient-booking-status
                        booking-status-{{ $statusColor }}">

                        {{ $reservationStatuses[$displayStatus]
                            ?? $displayStatus }}

                    </span>

                </div>

                <a href="{{ $reservation->can_pay
                    ? route('patient.payments.create', $reservation)
                    : route('patient.reservations.index') }}"
                    class="btn booking-detail-button">

                    {{ $reservation->can_pay
                        ? 'Bayar Sekarang'
                        : 'Lihat Reservasi' }}

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </article>

        @empty

            <div class="discovery-appointment-empty">

                <div class="discovery-info-icon">

                    <i class="bi bi-calendar2-week"></i>

                </div>

                <div>

                    <h6>Belum ada reservasi aktif</h6>

                    <p>

                        Pilih dokter dan jadwalkan pemeriksaan
                        pertama Anda.

                    </p>

                </div>

                <a href="#available-doctors"
                    class="discovery-view-all ms-md-auto">

                    Lihat Dokter
                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        @endforelse

    </div>

</section>

<section class="discovery-section mt-5 mb-4">

    <div class="discovery-section-heading">

        <div>

            <span class="discovery-eyebrow">
                CLINIC INFORMATION
            </span>

            <h4>Pengumuman Terbaru</h4>

            <p>
                Informasi terbaru dari DentalCare.
            </p>

        </div>

        <a href="{{ route('patient.announcements.index') }}"
            class="discovery-view-all">

            Lihat Semua

            <i class="bi bi-arrow-right ms-1"></i>

        </a>

    </div>

    <div class="row g-3">

        @forelse ($latestAnnouncements as $announcement)

            <div class="col-12 col-md-6 col-xl-4">

                <article class="announcement-patient-card">

                    <a href="{{ route(
                        'patient.announcements.show',
                        $announcement
                    ) }}"
                        class="announcement-cover">

                        @if ($announcement->image)

                            <img
                                src="{{ asset('storage/' . $announcement->image) }}"
                                alt="{{ $announcement->title }}"
                                loading="lazy">

                        @else

                            <i class="bi bi-megaphone"></i>

                        @endif

                    </a>

                    <div class="announcement-patient-body">

                        <div class="announcement-article-date">

                            <i class="bi bi-calendar3 me-1"></i>

                            {{ $announcement->published_at
                                ->timezone('Asia/Jakarta')
                                ->locale('id')
                                ->translatedFormat('d M Y') }}

                        </div>

                        <h5>{{ $announcement->title }}</h5>

                        <p class="announcement-excerpt">

                            {{ \Illuminate\Support\Str::limit(
                                $announcement->content,
                                100
                            ) }}

                        </p>

                        <a href="{{ route(
                            'patient.announcements.show',
                            $announcement
                        ) }}"
                            class="announcement-text-link mt-auto">

                            Baca Selengkapnya

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </article>

            </div>

        @empty

            <div class="col-12">

                <div class="announcement-empty">

                    <i class="bi bi-megaphone"></i>

                    <h6>Belum ada pengumuman terbaru</h6>

                    <p class="mb-0">
                        Informasi klinik akan ditampilkan di sini.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</section>
@endsection
