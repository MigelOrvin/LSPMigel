
@extends('layouts.patient')

@section('title', 'Reservasi Saya - DentalCare')

@section('patient-content')

@php
    $statuses = [
        'pending' => ['Menunggu Verifikasi', 'status-pending'],
        'approved' => ['Menunggu Pembayaran', 'status-approved'],
        'rejected' => ['Ditolak', 'status-rejected'],
        'confirmed' => ['Terkonfirmasi', 'status-approved'],
        'completed' => ['Selesai', 'status-approved'],
        'cancelled' => ['Dibatalkan', 'status-rejected']
    ];
@endphp

<div class="d-flex justify-content-between
    align-items-center flex-wrap gap-3 mb-4">

    <div>

        <h1 class="reservation-title">
            Reservasi Saya
        </h1>

        <p class="reservation-muted mb-0">
            Pantau status dan riwayat pemeriksaan Anda.
        </p>

    </div>

    <a href="{{ route('patient.dashboard') }}#available-doctors"
        class="btn portal-primary-button">

        <i class="bi bi-plus-lg me-1"></i>
        Buat Reservasi

    </a>

</div>

<div class="d-flex flex-column gap-3">

    @forelse ($reservations as $reservation)

        <article class="portal-card reservation-history-card">

            <div class="reservation-history-header">

                <div>

                    <small class="reservation-muted">
                        Kode Reservasi
                    </small>

                    <div class="fw-semibold text-break">

                        {{ $reservation->reservation_code }}

                    </div>

                </div>

                <span class="status-badge
                    {{ $statuses[$reservation->status][1] }}">

                    {{ $statuses[$reservation->status][0] }}

                </span>

            </div>

            <hr>

            <div class="portal-doctor-profile mb-3">

                <div class="portal-doctor-photo">

                    @if ($reservation->schedule->doctor->photo)

                        <img src="{{ asset(
                            $reservation->schedule->doctor->photo
                        ) }}"
                            alt="{{ $reservation->schedule->doctor->name }}">

                    @else

                        <i class="bi bi-person-fill"></i>

                    @endif

                </div>

                <div>

                    <h5 class="fw-bold mb-1">

                        {{ $reservation->schedule->doctor->name }}

                    </h5>

                    <small class="reservation-muted">
                        Pemeriksaan Gigi
                    </small>

                </div>

            </div>

            <div class="row g-3">

                <div class="col-6">

                    <small class="reservation-muted">
                        Tanggal Pemeriksaan
                    </small>

                    <div class="fw-semibold">

                        {{ $reservation->reservation_date
                            ->format('d M Y') }}

                    </div>

                </div>

                <div class="col-6">

                    <small class="reservation-muted">
                        Jam Pemeriksaan
                    </small>

                    <div class="fw-semibold">

                        {{ substr($reservation->start_time, 0, 5) }}
                        -
                        {{ substr($reservation->end_time, 0, 5) }}

                    </div>

                </div>

                <div class="col-12">

                    <small class="reservation-muted">
                        Keluhan
                    </small>

                    <div class="text-break">
                        {{ $reservation->complaint }}
                    </div>

                </div>

            </div>

            <hr>

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

            @if ($reservation->status === 'pending')

                <div class="alert alert-warning mt-3 mb-0">

                    <i class="bi bi-hourglass-split me-1"></i>

                    Reservasi sedang menunggu verifikasi admin.

                </div>

            @endif

            @if ($reservation->admin_note)

                <div class="alert alert-light mt-3 mb-0">

                    <strong>Catatan Admin:</strong>

                    {{ $reservation->admin_note }}

                </div>

            @endif

        </article>

    @empty

        <div class="portal-card portal-empty-state">

            <div class="portal-empty-icon">

                <i class="bi bi-calendar2-week"></i>

            </div>

            <h6>Belum ada reservasi</h6>

            <p>Silakan buat reservasi pemeriksaan terlebih dahulu.</p>

            <a href="{{ route('patient.dashboard') }}#available-doctors"
                class="btn portal-primary-button">

                Lihat Dokter

            </a>

        </div>

    @endforelse

</div>

<div class="mt-4">
    {{ $reservations->links() }}
</div>

@endsection
