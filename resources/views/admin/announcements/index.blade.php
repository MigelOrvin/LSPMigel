
@extends('layouts.admin')

@section('title', 'Announcement Center - DentalCare')
@section('page-title', 'Pengumuman')

@section('admin-content')

@php
    $statistics = [
        [
            'label' => 'Total Pengumuman',
            'value' => $totalAnnouncements,
            'icon' => 'bi-megaphone',
            'color' => 'teal'
        ],
        [
            'label' => 'Dipublikasikan',
            'value' => $publishedAnnouncements,
            'icon' => 'bi-check-circle',
            'color' => 'green'
        ],
        [
            'label' => 'Draft',
            'value' => $draftAnnouncements,
            'icon' => 'bi-file-earmark-text',
            'color' => 'yellow'
        ]
    ];
@endphp

<div class="announcement-center">

    {{-- Heading --}}
    <div class="announcement-heading mb-4">

        <div>

            <span class="announcement-eyebrow">
                DENTALCARE / INFORMATION
            </span>

            <h1>Announcement Center</h1>

            <p>
                Kelola informasi dan pengumuman klinik.
            </p>

        </div>

        <a href="{{ route('admin.announcements.create') }}"
            class="btn announcement-primary-button">

            <i class="bi bi-plus-lg me-1"></i>
            Buat Pengumuman

        </a>

    </div>

    {{-- Statistics --}}
    <div class="row g-2 g-md-3 mb-4">

        @foreach ($statistics as $stat)

            <div class="col-4">

                <div class="announcement-stat">

                    <div class="announcement-stat-top">

                        <div class="announcement-stat-icon
                            announcement-icon-{{ $stat['color'] }}">

                            <i class="bi {{ $stat['icon'] }}"></i>

                        </div>

                        <strong>{{ $stat['value'] }}</strong>

                    </div>

                    <small>{{ $stat['label'] }}</small>

                </div>

            </div>

        @endforeach

    </div>

    {{-- Search and Filter --}}
    <section class="announcement-toolbar mb-4">

        <div class="d-flex justify-content-between
            align-items-center flex-wrap gap-2 mb-3">

            <div>

                <h5 class="fw-bold mb-1">
                    Daftar Pengumuman
                </h5>

                <small class="announcement-muted">

                    {{ $announcements->total() }}
                    pengumuman ditemukan

                </small>

            </div>

            @if ($search !== '' || $status !== 'all')

                <a href="{{ route('admin.announcements.index') }}"
                    class="announcement-text-link">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Filter

                </a>

            @endif

        </div>

        <form method="GET"
            action="{{ route('admin.announcements.index') }}">

            <div class="row g-2">

                <div class="col-12 col-md-7">

                    <input type="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control announcement-input"
                        placeholder="Cari judul atau isi pengumuman..."
                        aria-label="Cari pengumuman">

                </div>

                <div class="col-8 col-md-3">

                    <select name="status"
                        class="form-select announcement-input"
                        aria-label="Filter status">

                        <option value="all"
                            @selected($status === 'all')>

                            Semua Status

                        </option>

                        <option value="published"
                            @selected($status === 'published')>

                            Dipublikasikan

                        </option>

                        <option value="draft"
                            @selected($status === 'draft')>

                            Draft

                        </option>

                    </select>

                </div>

                <div class="col-4 col-md-2">

                    <button type="submit"
                        class="btn announcement-primary-button w-100">

                        <i class="bi bi-funnel me-1"></i>
                        Filter

                    </button>

                </div>

            </div>

        </form>

    </section>

    {{-- Announcement List --}}
    <div class="row g-3">

        @forelse ($announcements as $announcement)

            <div class="col-12 col-xl-6">

                <article class="announcement-admin-card">

                    <div class="announcement-admin-body">

                        <div class="announcement-thumbnail">

                            @if ($announcement->image)

                                <img
                                    src="{{ asset('storage/' . $announcement->image) }}"
                                    alt="{{ $announcement->title }}"
                                    loading="lazy">

                            @else

                                <i class="bi bi-megaphone"></i>

                            @endif

                        </div>

                        <div class="announcement-admin-info">

                            <div class="d-flex align-items-center
                                flex-wrap gap-2 mb-2">

                                <span class="announcement-status
                                    announcement-status-{{ $announcement->status }}">

                                    {{ $announcement->status === 'published'
                                        ? 'Dipublikasikan'
                                        : 'Draft' }}

                                </span>

                                <small class="announcement-muted">

                                    {{ $announcement->created_at
                                        ->timezone('Asia/Jakarta')
                                        ->locale('id')
                                        ->translatedFormat('d M Y') }}

                                </small>

                            </div>

                            <h5>{{ $announcement->title }}</h5>

                            <p class="announcement-excerpt">

                                {{ \Illuminate\Support\Str::limit(
                                    $announcement->content,
                                    150
                                ) }}

                            </p>

                            <small class="announcement-muted">

                                <i class="bi bi-person me-1"></i>

                                {{ $announcement->author->name }}

                            </small>

                        </div>

                    </div>

                    <div class="announcement-admin-footer">

                        <a href="{{ route(
                            'admin.announcements.show',
                            $announcement
                        ) }}"
                            class="btn announcement-detail-button">

                            <i class="bi bi-eye me-1"></i>
                            Detail

                        </a>

                        <div class="d-flex gap-2">

                            <a href="{{ route(
                                'admin.announcements.edit',
                                $announcement
                            ) }}"
                                class="btn announcement-edit-button">

                                <i class="bi bi-pencil me-1"></i>
                                Edit

                            </a>

                            <button type="button"
                                class="btn announcement-delete-button"
                                data-bs-toggle="modal"
                                data-bs-target="#announcementDeleteModal"
                                data-title="{{ $announcement->title }}"
                                data-url="{{ route(
                                    'admin.announcements.destroy',
                                    $announcement
                                ) }}">

                                <i class="bi bi-trash me-1"></i>
                                Hapus

                            </button>

                        </div>

                    </div>

                </article>

            </div>

        @empty

            <div class="col-12">

                <div class="announcement-empty">

                    <i class="bi bi-megaphone"></i>

                    <h6>Belum ada pengumuman</h6>

                    <p>

                        Tidak ada pengumuman yang sesuai
                        dengan pencarian atau filter.

                    </p>

                    <a href="{{ route('admin.announcements.create') }}"
                        class="btn announcement-primary-button">

                        Buat Pengumuman

                    </a>

                </div>

            </div>

        @endforelse

    </div>

    @if ($announcements->hasPages())

        <div class="mt-4">

            {{ $announcements->links() }}

        </div>

    @endif

</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade"
    id="announcementDeleteModal"
    tabindex="-1"
    aria-labelledby="announcementDeleteTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content announcement-modal">

            <form method="POST"
                id="announcement-delete-form">

                @csrf
                @method('DELETE')

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="announcementDeleteTitle">

                        Hapus Pengumuman

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body text-center">

                    <div class="announcement-delete-icon">

                        <i class="bi bi-trash"></i>

                    </div>

                    <h5>Hapus pengumuman ini?</h5>

                    <p class="fw-semibold text-break"
                        id="announcement-delete-title">
                    </p>

                    <p class="announcement-muted small">

                        Pengumuman dan gambar yang terkait
                        akan dihapus secara permanen.

                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                        class="btn btn-danger"
                        id="announcement-delete-submit"
                        disabled>

                        Hapus Pengumuman

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
