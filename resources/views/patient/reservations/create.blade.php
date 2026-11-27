
@extends('layouts.patient')

@section('title', 'Buat Reservasi - DentalCare')

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
@endphp

<a href="{{ route('patient.dashboard') }}"
    class="reservation-back">

    <i class="bi bi-arrow-left me-2"></i>
    Kembali ke Beranda

</a>

<div class="portal-section-heading mt-4 mb-4">

    <h1 class="reservation-title">
        Buat Reservasi
    </h1>

    <p>Pilih tanggal dan jam pemeriksaan Anda.</p>

</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $errors->first() }}
    </div>
@endif

<div class="row g-4 align-items-start">

    {{-- Doctor Information --}}
    <div class="col-lg-4">

        <div class="portal-card reservation-card">

            <div class="reservation-doctor-profile">

                <div class="reservation-doctor-photo">

                    @if ($doctor->photo)

                        <img src="{{ asset($doctor->photo) }}"
                            alt="{{ $doctor->name }}">

                    @else

                        <i class="bi bi-person-fill"></i>

                    @endif

                </div>

                <h5>{{ $doctor->name }}</h5>

                <p class="reservation-muted">
                    Dokter Gigi
                </p>

                <div class="reservation-fee">

                    Rp{{ number_format(
                        $doctor->consultation_fee,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <small class="reservation-muted">
                    Per pemeriksaan / 60 menit
                </small>

            </div>

            <hr>

            <h6 class="fw-bold mb-3">

                <i class="bi bi-calendar-week me-2"></i>
                Jadwal Praktik

            </h6>

            <div class="portal-schedule-list mb-0">

                @forelse ($doctor->schedules as $schedule)

                    <div class="portal-schedule-row">

                        <span>
                            {{ $days[$schedule->day_of_week] }}
                        </span>

                        <strong>

                            {{ substr($schedule->start_time, 0, 5) }}
                            -
                            {{ substr($schedule->end_time, 0, 5) }}

                        </strong>

                    </div>

                @empty

                    <p class="small text-secondary mb-0">
                        Belum ada jadwal praktik.
                    </p>

                @endforelse

            </div>

        </div>

    </div>

    {{-- Reservation Form --}}
    <div class="col-lg-8">

        <div class="portal-card reservation-card">

            <h5 class="fw-bold mb-1">
                Detail Reservasi
            </h5>

            <p class="reservation-muted small mb-4">
                Lengkapi informasi pemeriksaan berikut.
            </p>

            <form
                action="{{ route('patient.reservations.store', $doctor) }}"
                method="POST"
                id="reservation-form"
                data-slots-url="{{ route(
                    'patient.reservations.slots',
                    $doctor
                ) }}">

                @csrf

                
                {{-- Available Dates --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Pilih Tanggal Pemeriksaan
                    </label>

                    <p class="reservation-muted small">
                        Pilih tanggal sesuai jadwal praktik dokter.
                    </p>

                    <input type="hidden"
                        id="reservation-date"
                        name="reservation_date"
                        value=""
                        data-old-value="{{ old('reservation_date') }}">

                    <div class="reservation-dates">

                        @forelse ($availableDates as $date)

                            <button type="button"
                                class="date-button
                                    {{ !$date['available'] ? 'unavailable' : '' }}"
                                data-date="{{ $date['date'] }}"
                                aria-pressed="false"
                                @disabled(!$date['available'])>

                                <span class="date-day">
                                    {{ $date['day'] }}
                                </span>

                                <strong class="date-number">
                                    {{ $date['day_number'] }}
                                </strong>

                                <span class="date-month">
                                    {{ $date['month'] }}
                                </span>

                                <span class="date-availability">

                                    @if ($date['available'])

                                        {{ $date['available_count'] }} slot

                                    @else

                                        Tidak tersedia

                                    @endif

                                </span>

                            </button>

                        @empty

                            <div class="slot-message w-100">
                                Belum ada jadwal pemeriksaan tersedia.
                            </div>

                        @endforelse

                    </div>

                    <small class="reservation-muted"
                        id="reservation-day">
                    </small>

                </div>


                {{-- Time Slot --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Jam Pemeriksaan
                    </label>

                    <input type="hidden"
                        name="start_time"
                        id="reservation-start-time"
                        value=""
                        data-old-value="{{ old('start_time') }}">

                    <div id="slot-message"
                        class="slot-message"
                        role="status">

                        Pilih tanggal pemeriksaan terlebih dahulu.

                    </div>

                    <div id="slot-container"
                        class="reservation-slots">
                    </div>

                </div>

                {{-- Complaint --}}
                <div class="mb-4">

                    <label for="complaint"
                        class="form-label fw-semibold">

                        Keluhan Anda

                    </label>

                    <textarea
                        name="complaint"
                        id="complaint"
                        class="form-control reservation-input"
                        rows="4"
                        maxlength="1000"
                        placeholder="Ceritakan keluhan gigi Anda..."
                        required>{{ old('complaint') }}</textarea>

                </div>

                {{-- Summary --}}
                <div class="reservation-summary">

                    <div>

                        <small class="reservation-muted">
                            Total Biaya Pemeriksaan
                        </small>

                        <div class="reservation-fee">

                            Rp{{ number_format(
                                $doctor->consultation_fee,
                                0,
                                ',',
                                '.'
                            ) }}

                        </div>

                    </div>

                    <div class="reservation-muted small">

                        <i class="bi bi-clock me-1"></i>
                        60 menit

                    </div>

                </div>

                <button type="submit"
                    class="btn portal-primary-button w-100 mt-4"
                    id="reservation-submit"
                    disabled>

                    <i class="bi bi-calendar-check me-2"></i>
                    Ajukan Reservasi

                </button>

                <p class="reservation-muted small
                    text-center mt-3 mb-0">

                    Reservasi akan diverifikasi oleh admin
                    sebelum Anda melakukan pembayaran.

                </p>

            </form>

        </div>

    </div>

</div>

@endsection
