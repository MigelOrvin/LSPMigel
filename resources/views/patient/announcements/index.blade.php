
@extends('layouts.patient')

@section('title', 'Pengumuman - DentalCare')

@section('patient-content')

{{-- Hero --}}
<section class="portal-hero announcement-hero mb-4">

    <div class="portal-hero-content">

        <span class="portal-eyebrow">
            DENTALCARE INFORMATION
        </span>

        <h1 class="mt-3">

            Pengumuman Klinik

        </h1>

        <p class="mb-0">

            Temukan informasi terbaru mengenai jadwal,
            layanan, dan pengumuman resmi DentalCare.

        </p>

    </div>

    <div class="portal-hero-decoration">

        <i class="bi bi-megaphone"></i>

    </div>

</section>

{{-- Section Heading --}}
<div class="announcement-heading mb-3">

    <div>

        <h4 class="fw-bold">

            Informasi Terbaru

        </h4>

        <p>

            {{ $announcements->total() }}
            pengumuman tersedia.

        </p>

    </div>

</div>

{{-- Search --}}
<form method="GET"
    action="{{ route('patient.announcements.index') }}"
    class="announcement-patient-search mb-4">

    <input type="search"
        name="search"
        value="{{ $search }}"
        class="form-control announcement-input"
        placeholder="Cari pengumuman klinik..."
        aria-label="Cari pengumuman">

    <button type="submit"
        class="btn announcement-primary-button">

        <i class="bi bi-search me-1"></i>
        Cari

    </button>

    @if ($search !== '')

        <a href="{{ route('patient.announcements.index') }}"
            class="btn announcement-detail-button">

            Reset

        </a>

    @endif

</form>

{{-- Announcements --}}
<div class="row g-3 g-xl-4">

    @forelse ($announcements as $announcement)

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

                        <span>DentalCare Information</span>

                    @endif

                </a>

                <div class="announcement-patient-body">

                    <div class="announcement-article-date">

                        <i class="bi bi-calendar3 me-1"></i>

                        {{ $announcement->published_at
                            ->timezone('Asia/Jakarta')
                            ->locale('id')
                            ->translatedFormat('d F Y') }}

                    </div>

                    <h5>

                        {{ $announcement->title }}

                    </h5>

                    <p class="announcement-excerpt">

                        {{ \Illuminate\Support\Str::limit(
                            $announcement->content,
                            160
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

                <h6>Belum ada pengumuman</h6>

                <p class="mb-0">

                    Informasi terbaru klinik akan
                    ditampilkan di sini.

                </p>

            </div>

        </div>

    @endforelse

</div>

@if ($announcements->hasPages())

    <div class="mt-4">

        {{ $announcements->links() }}

    </div>

@endif

@endsection
