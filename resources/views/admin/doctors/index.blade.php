
@extends('layouts.admin')

@section('title', 'Manajemen Dokter - DentalCare')
@section('page-title', 'Dokter')

@section('admin-content')

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

{{-- Page Heading --}}
<div class="doctor-page-header mb-4">

    <div>
        <h1 class="admin-page-title">Dokter</h1>

        <p class="admin-muted mb-0">
            Kelola informasi dokter gigi DentalCare.
        </p>
    </div>

    <button type="button" class="btn btn-admin-primary" disabled>
        <i class="bi bi-plus-lg me-1"></i>
        Tambah Dokter
    </button>

</div>

{{-- Search --}}
<div class="doctor-toolbar mb-4">

    <form action="{{ route('admin.doctors.index') }}"
        method="GET"
        class="doctor-search">

        <div class="input-group">

            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>

            <input type="search"
                name="search"
                class="form-control"
                placeholder="Cari nama dokter..."
                value="{{ $search }}">

            <button class="btn btn-admin-primary" type="submit">
                Cari
            </button>

        </div>

        @if ($search)
            <a href="{{ route('admin.doctors.index') }}"
                class="doctor-reset">
                Reset
            </a>
        @endif

    </form>

    <span class="admin-muted small">
        {{ $doctors->count() }} Dokter
    </span>

</div>


{{-- Doctor Cards --}}
<div class="row g-3 g-xl-4">

    @forelse ($doctors as $doctor)

        <div class="col-12 col-md-6 col-xl-4">

            <div class="doctor-card">

                {{-- Profile Header --}}
                <div class="doctor-profile">

                    <div class="doctor-photo">

                        @if ($doctor->photo)

                            <img
                                src="{{ asset($doctor->photo) }}"
                                alt="{{ $doctor->name }}"
                                loading="lazy">

                        @else

                            <div class="doctor-photo-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>

                        @endif

                    </div>

                    <span class="doctor-status
                        {{ $doctor->is_active
                            ? 'doctor-active'
                            : 'doctor-inactive' }}">

                        <i class="bi bi-circle-fill"></i>

                        {{ $doctor->is_active
                            ? 'Aktif'
                            : 'Nonaktif' }}

                    </span>

                </div>

                {{-- Doctor Information --}}
                <div class="doctor-card-body">

                    <h5 class="doctor-name">
                        {{ $doctor->name }}
                    </h5>

                    <div class="doctor-fee-label">
                        Biaya Konsultasi
                    </div>

                    <div class="doctor-fee">
                        Rp{{ number_format(
                            $doctor->consultation_fee,
                            0,
                            ',',
                            '.'
                        ) }}
                    </div>

                    <hr class="doctor-divider">

                    {{-- Practice Schedule --}}
                    <div class="doctor-schedule-label">

                        <i class="bi bi-calendar-week me-2"></i>
                        Jadwal Praktik

                        <span class="ms-auto">
                            {{ $doctor->schedules->count() }} hari
                        </span>

                    </div>

                    <div class="doctor-schedule-list">

                        @forelse ($doctor->schedules as $schedule)

                            <div class="doctor-schedule-row">

                                <span class="schedule-day">
                                    {{ $days[$schedule->day_of_week] }}
                                </span>

                                <span class="schedule-time">

                                    <i class="bi bi-clock me-1"></i>

                                    {{ substr($schedule->start_time, 0, 5) }}
                                    -
                                    {{ substr($schedule->end_time, 0, 5) }}

                                </span>

                            </div>

                        @empty

                            <div class="text-center py-3">

                                <small class="admin-muted">
                                    Belum ada jadwal praktik.
                                </small>

                            </div>

                        @endforelse

                    </div>

                    {{-- Actions --}}
                    <div class="doctor-actions">

                        <button type="button"
                            class="btn btn-doctor-edit flex-grow-1"
                            disabled>

                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Dokter

                        </button>

                        <button type="button"
                            class="btn btn-doctor-delete"
                            aria-label="Hapus dokter"
                            disabled>

                            <i class="bi bi-trash3"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="admin-card text-center p-5">

                <i class="bi bi-person-vcard fs-1 admin-muted"></i>

                <h5 class="mt-3">
                    Dokter tidak ditemukan
                </h5>

                <p class="admin-muted mb-0">
                    Belum ada data atau hasil pencarian tidak ditemukan.
                </p>

            </div>

        </div>

    @endforelse

</div>

@endsection

