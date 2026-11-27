
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

    $services = [
        [
            'title' => 'Reservasi Saya',
            'description' => 'Pantau status reservasi.',
            'icon' => 'bi-calendar-check',
            'color' => 'portal-icon-teal'
        ],
        [
            'title' => 'Pembayaran',
            'description' => 'Konfirmasi pembayaran.',
            'icon' => 'bi-wallet2',
            'color' => 'portal-icon-green'
        ],
        [
            'title' => 'Dokter Gigi',
            'description' => 'Temukan dokter pilihanmu.',
            'icon' => 'bi-person-vcard',
            'color' => 'portal-icon-blue',
            'href' => '#available-doctors'
        ],
        [
            'title' => 'Pengumuman',
            'description' => 'Informasi terbaru klinik.',
            'icon' => 'bi-megaphone',
            'color' => 'portal-icon-orange'
        ]
    ];
@endphp

{{-- Welcome Banner --}}
<section class="portal-hero mb-4">

    <div class="portal-hero-content">

        <span class="portal-eyebrow">
            DENTALCARE PATIENT PORTAL
        </span>

        <h1>
            Halo, {{ auth()->user()->name }}!
            <span class="portal-wave">👋</span>
        </h1>

        <p>
            Senyum sehat dimulai dari perawatan yang tepat.
            Jadwalkan pemeriksaan gigimu dengan mudah bersama
            dokter pilihanmu.
        </p>

        <a href="#available-doctors"
            class="btn portal-hero-button">

            <i class="bi bi-calendar-plus me-2"></i>
            Lihat Jadwal Dokter

        </a>

    </div>

    <div class="portal-hero-decoration">

        <i class="bi bi-heart-pulse"></i>

    </div>

</section>

{{-- Quick Access --}}
<section class="mb-5">

    <div class="portal-section-heading">

        <h5>Akses Cepat</h5>

        <p>Akses layanan DentalCare dengan mudah.</p>

    </div>

    <div class="row g-2 g-md-3">

        @foreach ($services as $service)

            <div class="col-6 col-lg-3">

                @if (isset($service['href']))

                    <a href="{{ $service['href'] }}"
                        class="portal-service-card">

                @else

                    <div class="portal-service-card">

                @endif

                    <div class="portal-service-icon
                        {{ $service['color'] }}">

                        <i class="bi {{ $service['icon'] }}"></i>

                    </div>

                    <h6>{{ $service['title'] }}</h6>

                    <p>{{ $service['description'] }}</p>

                @if (isset($service['href']))
                    </a>
                @else
                    </div>
                @endif

            </div>

        @endforeach

    </div>

</section>

{{-- Upcoming Reservation --}}
<section class="mb-5">

    <div class="portal-section-heading">

        <h5>Reservasi Mendatang</h5>

        <p>Jadwal pemeriksaan gigi Anda.</p>

    </div>

    <div class="portal-card portal-empty-state">

        <div class="portal-empty-icon">

            <i class="bi bi-calendar2-week"></i>

        </div>

        <h6>Reservasi belum tersedia</h6>

        <p>
            Fitur pemesanan jadwal pemeriksaan
            sedang disiapkan.
        </p>

        <a href="#available-doctors"
            class="btn portal-primary-button">

            Lihat Dokter

        </a>

    </div>

</section>

{{-- Available Doctors --}}
<section id="available-doctors" class="mb-4">

    <div class="portal-section-heading">

        <h5>Dokter Gigi Kami</h5>

        <p>
            Temukan dokter dan jadwal praktik yang sesuai
            dengan kebutuhan Anda.
        </p>

    </div>

    <div class="row g-3 g-xl-4">

        @forelse ($doctors as $doctor)

            <div class="col-12 col-md-6 col-xl-4">

                <article class="portal-doctor-card">

                    {{-- Doctor Profile --}}
                    <div class="portal-doctor-profile">

                        <div class="portal-doctor-photo">

                            @if ($doctor->photo)

                                <img src="{{ asset($doctor->photo) }}"
                                    alt="{{ $doctor->name }}"
                                    loading="lazy">

                            @else

                                <i class="bi bi-person-fill"></i>

                            @endif

                        </div>

                        <div class="portal-doctor-info">

                            <h6>{{ $doctor->name }}</h6>

                            <small>Biaya Konsultasi</small>

                            <div class="portal-doctor-fee">

                                Rp{{ number_format(
                                    $doctor->consultation_fee,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- Doctor Schedule --}}
                    <div class="portal-schedule-heading">

                        <i class="bi bi-calendar-week"></i>
                        Jadwal Praktik

                    </div>

                    <div class="portal-schedule-list">

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

                            <small class="text-secondary">
                                Belum ada jadwal praktik.
                            </small>

                        @endforelse

                    </div>

                    <a href="{{ route('patient.reservations.create', $doctor) }}"
                        class="btn portal-primary-button w-100 mt-auto">

                        <i class="bi bi-calendar-plus me-1"></i>
                        Buat Reservasi

                    </a>

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

@endsection
