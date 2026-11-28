
@extends($isAdmin ? 'layouts.admin' : 'layouts.patient')

@section('title', $announcement->title . ' - DentalCare')
@section('page-title', 'Pengumuman')

@section($isAdmin ? 'admin-content' : 'patient-content')

<div class="announcement-reader">

    <div class="d-flex justify-content-between
        align-items-center flex-wrap gap-3 mb-4">

        <a href="{{ route(
            $isAdmin
                ? 'admin.announcements.index'
                : 'patient.announcements.index'
        ) }}"
            class="announcement-text-link">

            <i class="bi bi-arrow-left me-2"></i>
            Kembali ke Pengumuman

        </a>

        @if ($isAdmin)

            <a href="{{ route(
                'admin.announcements.edit',
                $announcement
            ) }}"
                class="btn announcement-edit-button">

                <i class="bi bi-pencil me-1"></i>
                Edit Pengumuman

            </a>

        @endif

    </div>

    <article class="announcement-article">

        @if ($announcement->image)

            <div class="announcement-article-image">

                <img
                    src="{{ asset('storage/' . $announcement->image) }}"
                    alt="{{ $announcement->title }}">

            </div>

        @endif

        <div class="announcement-article-content">

            <span class="announcement-eyebrow">

                DENTALCARE / PENGUMUMAN

            </span>

            @if ($isAdmin)

                <span class="announcement-status
                    announcement-status-{{ $announcement->status }}">

                    {{ $announcement->status === 'published'
                        ? 'Dipublikasikan'
                        : 'Draft' }}

                </span>

            @endif

            <h1>{{ $announcement->title }}</h1>

            <div class="announcement-article-meta">

                <span>

                    <i class="bi bi-calendar3 me-1"></i>

                    {{ ($announcement->published_at
                        ?? $announcement->created_at)
                        ->timezone('Asia/Jakarta')
                        ->locale('id')
                        ->translatedFormat('l, d F Y') }}

                </span>

                <span>

                    <i class="bi bi-person-circle me-1"></i>

                    {{ $isAdmin
                        ? $announcement->author->name
                        : 'Admin DentalCare' }}

                </span>

            </div>

            <hr>

            <div class="announcement-article-text">{{ $announcement->content }}</div>

        </div>

    </article>

</div>

@endsection
