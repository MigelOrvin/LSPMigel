
@extends('layouts.patient')

@section('title', 'Reservasi Saya - DentalCare')

@section('patient-content')

@php
    $statuses = [
        'awaiting_payment' => [
            'Menunggu Pembayaran',
            'waiting'
        ],
        'payment_pending' => [
            'Verifikasi Pembayaran',
            'processing'
        ],
        'payment_rejected' => [
            'Pembayaran Ditolak',
            'rejected'
        ],
        'reservation_pending' => [
            'Verifikasi Reservasi',
            'processing'
        ],
        'pending' => [
            'Menunggu Verifikasi',
            'waiting'
        ],
        'approved' => [
            'Reservasi Disetujui',
            'approved'
        ],
        'rejected' => [
            'Reservasi Ditolak',
            'rejected'
        ],
        'confirmed' => [
            'Terkonfirmasi',
            'approved'
        ],
        'completed' => [
            'Selesai',
            'approved'
        ],
        'cancelled' => [
            'Dibatalkan',
            'rejected'
        ]
    ];
@endphp

{{-- Page Heading --}}
<div class="d-flex justify-content-between
    align-items-center flex-wrap gap-3 mb-4">

    <div>

        <h1 class="reservation-title">
            Reservasi Saya
        </h1>

        <p class="reservation-muted mb-0">
            Kelola dan pantau pemeriksaan Anda.
        </p>

    </div>

    <a href="{{ route('patient.dashboard') }}#available-doctors"
        class="btn portal-primary-button">

        <i class="bi bi-plus-lg me-1"></i>
        Reservasi Baru

    </a>

</div>

{{-- Reservation Cards --}}
<div class="row g-3 g-xl-4">

    @forelse ($reservations as $reservation)

        @php
            $doctor = $reservation->schedule->doctor;

            $displayStatus = $reservation->display_status;

            $statusInfo = $statuses[$displayStatus];

            $isPaid = $reservation->payment?->status === 'approved';

            $isApproved = in_array(
                $reservation->status,
                ['approved', 'confirmed', 'completed'],
                true
            );

            $isUpcoming = \Carbon\CarbonImmutable::parse(
                $reservation->reservation_date->format('Y-m-d')
                    . ' '
                    . $reservation->start_time,
                'Asia/Jakarta'
            )->gt(now('Asia/Jakarta'));

            $canPay = $reservation->status === 'pending'
                && $isUpcoming
                && (
                    !$reservation->payment ||
                    $reservation->payment->status === 'rejected'
                );

            $steps = [
                [
                    'label' => 'Reservasi',
                    'icon' => 'bi-calendar-check',
                    'done' => true
                ],
                [
                    'label' => 'Pembayaran',
                    'icon' => 'bi-wallet2',
                    'done' => in_array(
                        $reservation->payment?->status,
                        ['pending', 'approved'],
                        true
                    )
                ],
                [
                    'label' => 'Verifikasi',
                    'icon' => 'bi-shield-check',
                    'done' => $isPaid
                ],
                [
                    'label' => 'Disetujui',
                    'icon' => 'bi-check-circle',
                    'done' => $isApproved
                ]
            ];
        @endphp

        <div class="col-12 col-xl-6">

            <article class="patient-booking-card">

                {{-- Header --}}
                <div class="patient-booking-header">

                    <span class="patient-booking-code">

                        <i class="bi bi-ticket-perforated me-1"></i>

                        {{ $reservation->reservation_code }}

                    </span>

                    <span class="patient-booking-status
                        booking-status-{{ $statusInfo[1] }}">

                        {{ $statusInfo[0] }}

                    </span>

                </div>

                {{-- Body --}}
                <div class="patient-booking-body">

                    {{-- Doctor Profile --}}
                    <div class="portal-doctor-profile mb-3">

                        <div class="portal-doctor-photo">

                            @if ($doctor->photo)

                                <img src="{{ asset($doctor->photo) }}"
                                    alt="{{ $doctor->name }}"
                                    loading="lazy">

                            @else

                                <i class="bi bi-person-fill"></i>

                            @endif

                        </div>

                        <div class="patient-booking-doctor">

                            <h5>{{ $doctor->name }}</h5>

                            <small class="reservation-muted">
                                Pemeriksaan Gigi
                            </small>

                        </div>

                    </div>

                    {{-- Appointment Schedule --}}
                    <div class="patient-booking-schedule">

                        <div>

                            <i class="bi bi-calendar3"></i>

                            <div>

                                <small>Tanggal Pemeriksaan</small>

                                <strong>

                                    {{ $reservation->reservation_date
                                        ->locale('id')
                                        ->translatedFormat('d M Y') }}

                                </strong>

                            </div>

                        </div>

                        <div>

                            <i class="bi bi-clock"></i>

                            <div>

                                <small>Jam Pemeriksaan</small>

                                <strong>

                                    {{ substr($reservation->start_time, 0, 5) }}
                                    -
                                    {{ substr($reservation->end_time, 0, 5) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                    {{-- Progress --}}
                    <div class="patient-booking-progress">

                        <h6>Progres Reservasi</h6>

                        <div class="booking-steps">

                            @foreach ($steps as $step)

                                <div class="booking-step
                                    {{ $step['done'] ? 'done' : '' }}">

                                    <div class="booking-step-icon">

                                        <i class="bi {{ $step['icon'] }}"></i>

                                    </div>

                                    <span>{{ $step['label'] }}</span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- Status Messages --}}
                    @if ($displayStatus === 'awaiting_payment')

                        <p class="booking-status-message">

                            <i class="bi bi-info-circle me-1"></i>

                            Silakan lakukan pembayaran agar
                            reservasi dapat diproses.

                        </p>

                    @elseif ($displayStatus === 'payment_pending')

                        <p class="booking-status-message">

                            <i class="bi bi-hourglass-split me-1"></i>

                            Bukti pembayaran sedang diperiksa admin.

                        </p>

                    @elseif ($displayStatus === 'payment_rejected')

                        <div class="booking-warning-message">

                            <strong>Pembayaran ditolak.</strong>

                            <p class="mb-0">

                                {{ $reservation->payment?->admin_note
                                    ?? 'Silakan unggah ulang bukti pembayaran.' }}

                            </p>

                        </div>

                    @elseif ($displayStatus === 'reservation_pending')

                        <p class="booking-status-message">

                            <i class="bi bi-shield-check me-1"></i>

                            Pembayaran berhasil diverifikasi.
                            Menunggu persetujuan jadwal.

                        </p>

                    @elseif ($displayStatus === 'approved')

                        <p class="booking-success-message">

                            <i class="bi bi-check-circle me-1"></i>

                            Reservasi telah disetujui.
                            Datang sesuai jadwal pemeriksaan.

                        </p>

                    @elseif ($displayStatus === 'rejected')

                        <div class="booking-warning-message">

                            <strong>Reservasi ditolak.</strong>

                            <p class="mb-1">

                                {{ $reservation->admin_note
                                    ?? 'Reservasi tidak dapat diproses.' }}

                            </p>

                            @if ($isPaid)

                                <small>

                                    Hubungi klinik untuk proses
                                    pengembalian dana.

                                </small>

                            @endif

                        </div>

                    @endif

                    @if (!$isUpcoming && $reservation->status === 'pending')

                        <div class="booking-warning-message">
                            Waktu pemeriksaan telah berlalu.
                            Silakan hubungi klinik.
                        </div>

                    @endif

                </div>

                {{-- Footer --}}
                <div class="patient-booking-footer">

                    <div>

                        <small class="reservation-muted">
                            Total Biaya
                        </small>

                        <div class="reservation-fee">

                            Rp{{ number_format(
                                $reservation->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </div>

                    </div>

                    @if ($canPay)

                        <a href="{{ route(
                            'patient.payments.create',
                            $reservation
                        ) }}"
                            class="btn portal-primary-button">

                            <i class="bi bi-wallet2 me-1"></i>

                            {{ $displayStatus === 'payment_rejected'
                                ? 'Upload Ulang'
                                : 'Bayar Sekarang' }}

                        </a>

                    @endif

                    <button type="button"
                        class="btn booking-detail-button"
                        data-bs-toggle="modal"
                        data-bs-target="#bookingDetail{{ $reservation->id }}">

                        <i class="bi bi-eye me-1"></i>
                        Detail

                    </button>

                </div>

            </article>

        </div>

        @include('patient.reservations.detail-modal', [
            'reservation' => $reservation
        ])

    @empty

        <div class="col-12">

            <div class="portal-card portal-empty-state">

                <div class="portal-empty-icon">

                    <i class="bi bi-calendar2-week"></i>

                </div>

                <h6>Belum ada reservasi</h6>

                <p>
                    Silakan buat reservasi pemeriksaan
                    terlebih dahulu.
                </p>

                <a href="{{ route('patient.dashboard') }}#available-doctors"
                    class="btn portal-primary-button">

                    Lihat Dokter

                </a>

            </div>

        </div>

    @endforelse

</div>

<div class="mt-4">

    {{ $reservations->links() }}

</div>

@endsection
