
@extends('layouts.admin')

@section('title', 'Appointment Center - DentalCare')
@section('page-title', 'Reservasi')

@section('admin-content')

@php
    $statuses = [
        'pending' => 'Siap Diverifikasi',
        'approved' => 'Reservasi Disetujui',
        'rejected' => 'Ditolak',
        'confirmed' => 'Terkonfirmasi',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'expired' => 'Waktu Pembayaran Habis'
    ];

    $statistics = [
        [
            'label' => 'Total Reservasi',
            'value' => $totalReservations,
            'icon' => 'bi-calendar2-week',
            'color' => 'teal'
        ],
        [
            'label' => 'Disetujui',
            'value' => $approvedReservations,
            'icon' => 'bi-check-circle',
            'color' => 'green'
        ],
        [
            'label' => 'Ditolak',
            'value' => $rejectedReservations,
            'icon' => 'bi-x-circle',
            'color' => 'red'
        ],
        [
            'label' => 'Kedaluwarsa',
            'value' => $expiredReservations,
            'icon' => 'bi-clock-history',
            'color' => 'yellow'
        ]
    ];
@endphp

<div class="appointment-center">

    {{-- Page Heading --}}
    <div class="appointment-heading mb-4">

        <div>
            <span class="appointment-eyebrow">
                DENTALCARE / ADMINISTRATION
            </span>

            <h1>Appointment Center</h1>

            <p>
                Kelola pengajuan dan jadwal pemeriksaan pasien.
            </p>
        </div>

        <div class="appointment-heading-icon">
            <i class="bi bi-calendar2-week"></i>
        </div>

    </div>

    {{-- Priority Banner --}}
    <section class="appointment-banner mb-3">

        <div class="appointment-banner-content">

            <span class="appointment-banner-label">
                <i class="bi bi-hourglass-split me-1"></i>
                MEMERLUKAN TINDAKAN
            </span>

            <h2>
                {{ $pendingReservations }}
                <span>Reservasi</span>
            </h2>

            <p>
                @if ($pendingReservations > 0)

                    Terdapat reservasi dengan pembayaran
                    terverifikasi yang menunggu persetujuan Anda.

                @else

                    Tidak ada reservasi yang siap diverifikasi.

                @endif
            </p>

            <a href="{{ route('admin.reservations.index', [
                'status' => 'pending'
            ]) }}#appointment-list"
                class="btn appointment-banner-button">

                Tinjau Pengajuan
                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

        <i class="bi bi-calendar-check appointment-banner-decoration">
        </i>

    </section>

    {{-- Statistics --}}
    <div class="row g-2 g-md-3 mb-4">

        @foreach ($statistics as $stat)

        <div class="col-6 col-lg-3">

                <div class="appointment-stat">

                    <div class="appointment-stat-icon
                        appointment-stat-{{ $stat['color'] }}">

                        <i class="bi {{ $stat['icon'] }}"></i>

                    </div>

                    <div class="appointment-stat-value">
                        {{ $stat['value'] }}
                    </div>

                    <span class="appointment-stat-label">
                        {{ $stat['label'] }}
                    </span>

                </div>

            </div>

        @endforeach

    </div>

    {{-- Search and Filters --}}
    <section class="appointment-toolbar mb-4"
        id="appointment-list">

        <div class="d-flex justify-content-between
            align-items-center flex-wrap gap-2 mb-3">

            <div>
                <h5 class="fw-bold mb-1">
                    Daftar Pemeriksaan
                </h5>

                <small class="text-secondary">
                    {{ $reservations->total() }} reservasi ditemukan
                </small>
            </div>

            @if (
                $search !== '' ||
                $status !== 'all' ||
                $doctorId ||
                $date ||
                $sort !== 'latest'
            )

                <a href="{{ route('admin.reservations.index') }}#appointment-list"
                    class="appointment-reset">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Filter

                </a>

            @endif

        </div>

        <form method="GET"
            action="{{ route('admin.reservations.index') }}#appointment-list">

            <div class="appointment-search mb-3">

                <i class="bi bi-search"></i>

                <input type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari kode, pasien, atau dokter..."
                    aria-label="Cari reservasi"
                    class="form-control">

            </div>

            
            <div class="row g-2 align-items-end">

                {{-- Status --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label class="appointment-filter-label">
                        Status
                    </label>

                    <select name="status"
                        class="form-select appointment-input"
                        aria-label="Filter status">

                        <option value="all"
                            @selected($status === 'all')>
                            Semua Status
                        </option>

                        @foreach ($statuses as $key => $label)

                            <option value="{{ $key }}"
                                @selected($status === $key)>

                                {{ $label }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Doctor --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label class="appointment-filter-label">
                        Dokter
                    </label>

                    <select name="doctor_id"
                        class="form-select appointment-input"
                        aria-label="Filter dokter">

                        <option value="">
                            Semua Dokter
                        </option>

                        @foreach ($doctors as $doctor)

                            <option value="{{ $doctor->id }}"
                                @selected((string) $doctorId === (string) $doctor->id)>

                                {{ $doctor->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Date --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label class="appointment-filter-label">
                        Tanggal Pemeriksaan
                    </label>

                    <input type="date"
                        name="date"
                        value="{{ $date }}"
                        aria-label="Filter tanggal pemeriksaan"
                        class="form-control appointment-input">

                </div>

                {{-- Sorting --}}
                <div class="col-12 col-md-6 col-xl-3">

                    <label class="appointment-filter-label">
                        Urutkan
                    </label>

                    <select name="sort"
                        class="form-select appointment-input"
                        aria-label="Urutkan reservasi">

                        <option value="latest"
                            @selected($sort === 'latest')>
                            Terbaru
                        </option>

                        <option value="oldest"
                            @selected($sort === 'oldest')>
                            Terlama
                        </option>

                        <option value="schedule"
                            @selected($sort === 'schedule')>
                            Jadwal Pemeriksaan
                        </option>

                        <option value="priority"
                            @selected($sort === 'priority')>
                            Prioritas Verifikasi
                        </option>

                    </select>

                </div>

                {{-- Filter Button --}}
                <div class="col-12 mt-3">

                    <button type="submit"
                        class="btn btn-admin-primary
                            appointment-filter-button w-100">

                        <i class="bi bi-funnel me-1"></i>
                        Terapkan Filter

                    </button>

                </div>

            </div>


        </form>

    </section>

    {{-- Appointment Cards --}}
    <div class="row g-3">

        @forelse ($reservations as $reservation)

            @php
            $displayStatus = $reservation->display_status;

            $paymentStatus = $reservation->payment?->status;

            $isPaid = $paymentStatus === 'approved';

            $canVerify = $displayStatus === 'reservation_pending'
                && $isPaid;

            $paymentLabel = match ($paymentStatus) {
                'pending' => 'Verifikasi Pembayaran',
                'approved' => 'Pembayaran Terverifikasi',
                'rejected' => 'Pembayaran Ditolak',
                default => $displayStatus === 'expired'
                    ? 'Waktu Pembayaran Habis'
                    : 'Belum Membayar'
            };

            $reservationLabel = match ($displayStatus) {
                'awaiting_payment' => 'Menunggu Pembayaran',
                'payment_pending' => 'Verifikasi Pembayaran',
                'payment_rejected' => 'Pembayaran Ditolak',
                'reservation_pending' => 'Siap Diverifikasi',
                'expired' => 'Waktu Pembayaran Habis',
                default => $statuses[$reservation->status]
            };

            $statusClass = match ($displayStatus) {
                'payment_rejected' => 'rejected',
                'expired' => 'expired',
                default => $reservation->status
            };

            $adminNote = $displayStatus === 'payment_rejected'
                ? $reservation->payment?->admin_note
                : $reservation->admin_note;

            $verifier = $displayStatus === 'payment_rejected'
                ? $reservation->payment?->verifier?->name
                : $reservation->verifier?->name;

            $doctor = $reservation->schedule->doctor;

            $isPast = \Carbon\CarbonImmutable::parse(
                $reservation->reservation_date->format('Y-m-d')
                    . ' '
                    . $reservation->start_time,
                'Asia/Jakarta'
            )->lte(
                \Carbon\CarbonImmutable::now('Asia/Jakarta')
            );
        @endphp
            <div class="col-12 col-xl-6">

                <article class="appointment-card">

                    {{-- Card Header --}}
                    <div class="appointment-card-header">

                        <span class="appointment-code">
                            <i class="bi bi-ticket-perforated me-1"></i>

                            {{ $reservation->reservation_code }}
                        </span>

                        <span class="appointment-status
                            appointment-status-{{ $statusClass }}">

                            {{ $reservationLabel }}

                        </span>

                    </div>

                    {{-- Card Body --}}
                    <div class="appointment-card-body">

                        {{-- Date Block --}}
                        <div class="appointment-date-block">

                            <span class="appointment-month">

                                {{ $reservation->reservation_date
                                    ->locale('id')
                                    ->translatedFormat('M') }}

                            </span>

                            <strong class="appointment-day-number">

                                {{ $reservation->reservation_date
                                    ->format('d') }}

                            </strong>

                            <span class="appointment-weekday">

                                {{ $reservation->reservation_date
                                    ->locale('id')
                                    ->translatedFormat('D') }}

                            </span>

                            <div class="appointment-date-divider">
                            </div>

                            <strong class="appointment-time">

                                {{ substr($reservation->start_time, 0, 5) }}

                            </strong>

                        </div>

                        {{-- Patient and Doctor --}}
                        <div class="appointment-information">

                            <small class="appointment-muted">
                                Pasien
                            </small>

                            <h5>{{ $reservation->user->name }}</h5>

                            <p class="appointment-email">
                                {{ $reservation->user->email }}
                            </p>

                            <div class="appointment-doctor">

                                <i class="bi bi-person-vcard"></i>

                                <span>{{ $doctor->name }}</span>

                            </div>

                            <div class="appointment-duration">

                                <i class="bi bi-clock"></i>

                                <span>

                                    {{ substr($reservation->start_time, 0, 5) }}
                                    -
                                    {{ substr($reservation->end_time, 0, 5) }}
                                    WIB

                                </span>

                            </div>

                            <div class="appointment-fee">

                                Rp{{ number_format(
                                    $reservation->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                            <div class="appointment-payment-indicator
                                {{ $isPaid
                                    ? 'payment-verified'
                                    : (in_array($displayStatus, [
                                        'payment_rejected',
                                        'expired'
                                    ], true)
                                        ? 'payment-failed'
                                        : 'payment-waiting') }}">

                                <i class="bi
                                    {{ $isPaid
                                        ? 'bi-shield-check'
                                        : (in_array($displayStatus, [
                                            'payment_rejected',
                                            'expired'
                                        ], true)
                                            ? 'bi-x-circle'
                                            : 'bi-hourglass-split') }}">
                                </i>

                                <span>{{ $paymentLabel }}</span>

                            </div>

                        </div>

                    </div>

                    @if ($isPast && $reservation->status === 'pending')

                        <div class="appointment-expired-notice">

                            <i class="bi bi-exclamation-circle me-1"></i>

                            Waktu pemeriksaan telah berlalu.

                        </div>

                    @endif

                                            
                        {{-- Card Actions --}}
                        <div class="appointment-card-footer">

                            <button type="button"
                                class="btn appointment-detail-button"
                                data-bs-toggle="modal"
                                data-bs-target="#appointmentDetailModal"
                                data-reservation="{{ $reservation->reservation_code }}"
                                data-patient="{{ $reservation->user->name }}"
                                data-email="{{ $reservation->user->email }}"
                                data-doctor="{{ $doctor->name }}"
                                data-date="{{ $reservation->reservation_date->locale('id')->translatedFormat('l, d F Y') }}"
                                data-time="{{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} WIB"
                                data-fee="Rp{{ number_format($reservation->total_amount, 0, ',', '.') }}"
                                data-complaint="{{ $reservation->complaint }}"
                                data-status="{{ $reservationLabel }}"
                                data-note="{{ $adminNote ?? '-' }}"
                                data-verifier="{{ $verifier ?? '-' }}"

                                <i class="bi bi-eye me-1"></i>
                                Detail

                            </button>

                            @if ($canVerify)

                                <div class="appointment-verification-actions">

                                    <button type="button"
                                        class="btn appointment-reject-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#appointmentVerificationModal"
                                        data-status="rejected"
                                        data-patient="{{ $reservation->user->name }}"
                                        data-code="{{ $reservation->reservation_code }}"
                                        data-url="{{ route('admin.reservations.status', $reservation) }}">

                                        <i class="bi bi-x-lg me-1"></i>
                                        Tolak

                                    </button>

                                    <button type="button"
                                        class="btn appointment-approve-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#appointmentVerificationModal"
                                        data-status="approved"
                                        data-patient="{{ $reservation->user->name }}"
                                        data-code="{{ $reservation->reservation_code }}"
                                        data-url="{{ route('admin.reservations.status', $reservation) }}"
                                        @disabled($isPast)>

                                        <i class="bi bi-check-lg me-1"></i>
                                        Setujui

                                    </button>

                                </div>

                            @elseif ($displayStatus === 'payment_rejected')

                                <small class="appointment-muted text-danger">

                                    <i class="bi bi-x-circle me-1"></i>
                                    Pembayaran ditolak

                                </small>

                            @elseif ($displayStatus === 'expired')

                                <small class="appointment-muted">

                                    <i class="bi bi-clock-history me-1"></i>
                                    Waktu pembayaran habis

                                </small>

                            @elseif ($reservation->status === 'pending')

                                <small class="appointment-muted">

                                    <i class="bi bi-lock me-1"></i>
                                    Menunggu pembayaran terverifikasi

                                </small>

                            @else

                                <small class="appointment-muted">

                                    <i class="bi bi-check2-all me-1"></i>
                                    Tidak ada tindakan

                                </small>

                            @endif

                        </div>


                </article>

            </div>

        @empty

            <div class="col-12">

                <div class="appointment-empty">

                    <div class="appointment-empty-icon">

                        <i class="bi bi-calendar2-x"></i>

                    </div>

                    <h5>Belum ada reservasi</h5>

                    <p>
                        Tidak ada reservasi yang sesuai
                        dengan pencarian atau filter Anda.
                    </p>

                    <a href="{{ route('admin.reservations.index') }}"
                        class="btn btn-admin-primary">

                        Lihat Semua Reservasi

                    </a>

                </div>

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    @if ($reservations->hasPages())

        <div class="mt-4">

            {{ $reservations->links() }}

        </div>

    @endif

</div>

@include('admin.reservations.detail-modal')
@include('admin.reservations.verification-modal')

@endsection
