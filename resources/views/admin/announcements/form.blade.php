
@extends('layouts.admin')

@php
    $isEdit = $announcement->exists;

    $pageTitle = $isEdit
        ? 'Edit Pengumuman'
        : 'Buat Pengumuman';

    $selectedStatus = old(
        'status',
        $announcement->status ?? 'draft'
    );
@endphp

@section('title', $pageTitle . ' - DentalCare')
@section('page-title', 'Pengumuman')

@section('admin-content')

<a href="{{ route('admin.announcements.index') }}"
    class="announcement-text-link">

    <i class="bi bi-arrow-left me-2"></i>
    Kembali ke Pengumuman

</a>

<div class="announcement-heading mt-4 mb-4">

    <div>

        <h1>{{ $pageTitle }}</h1>

        <p>

            {{ $isEdit
                ? 'Perbarui informasi pengumuman klinik.'
                : 'Sampaikan informasi terbaru kepada pasien.' }}

        </p>

    </div>

</div>

<form method="POST"
    action="{{ $isEdit
        ? route('admin.announcements.update', $announcement)
        : route('admin.announcements.store') }}"
    enctype="multipart/form-data"
    id="announcement-form">

    @csrf

    @if ($isEdit)

        @method('PUT')

    @endif

    <div class="row g-4 align-items-start">

        {{-- Main Content --}}
        <div class="col-lg-8">

            <div class="announcement-panel">

                <h5 class="fw-bold mb-4">
                    Informasi Pengumuman
                </h5>

                <div class="mb-4">

                    <label for="announcement-title"
                        class="form-label fw-semibold">

                        Judul Pengumuman

                    </label>

                    <input type="text"
                        name="title"
                        id="announcement-title"
                        class="form-control announcement-input
                            @error('title') is-invalid @enderror"
                        placeholder="Masukkan judul pengumuman..."
                        maxlength="150"
                        value="{{ old('title', $announcement->title) }}"
                        required>

                    @error('title')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                <div>

                    <label for="announcement-content"
                        class="form-label fw-semibold">

                        Isi Pengumuman

                    </label>

                    <textarea name="content"
                        id="announcement-content"
                        class="form-control announcement-input
                            @error('content') is-invalid @enderror"
                        rows="13"
                        maxlength="10000"
                        placeholder="Tuliskan informasi yang ingin disampaikan kepada pasien..."
                        required>{{ old('content', $announcement->content) }}</textarea>

                    @error('content')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <div class="d-flex justify-content-end mt-2">

                        <small class="announcement-muted"
                            id="announcement-character-count">

                            0 / 10.000 karakter

                        </small>

                    </div>

                </div>

            </div>

        </div>

        {{-- Image and Publication --}}
        <div class="col-lg-4">

            <div class="announcement-panel mb-3">

                <h6 class="fw-bold mb-2">

                    Gambar Pengumuman

                </h6>

                <p class="announcement-muted small">

                    Tambahkan gambar agar informasi
                    lebih menarik.

                </p>

                <input type="file"
                    name="image"
                    id="announcement-image"
                    class="form-control announcement-input"
                    accept="image/jpeg,image/png,image/webp"
                    data-existing-image="{{ $announcement->image
                        ? asset('storage/' . $announcement->image)
                        : '' }}">

                <small class="announcement-muted d-block mt-2">

                    JPG, PNG, WEBP. Maksimal 2 MB.

                </small>

                @error('image')

                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>

                @enderror

                <div id="announcement-image-error"
                    class="text-danger small mt-2"
                    hidden>
                </div>

                <div class="announcement-image-preview mt-3"
                    id="announcement-image-preview"
                    @if (!$announcement->image) hidden @endif>

                    <img id="announcement-preview-img"
                        @if ($announcement->image)
                            src="{{ asset('storage/' . $announcement->image) }}"
                        @endif
                        alt="Preview gambar pengumuman">

                    <button type="button"
                        class="btn announcement-delete-button w-100 mt-2"
                        id="announcement-remove-image">

                        <i class="bi bi-trash me-1"></i>
                        Hapus Gambar

                    </button>

                </div>

                <input type="hidden"
                    name="remove_image"
                    id="announcement-remove-image-input"
                    value="0">

            </div>

            <div class="announcement-panel">

                <h6 class="fw-bold mb-3">

                    <i class="bi bi-globe me-1"></i>
                    Publikasi

                </h6>

                <label class="announcement-radio-option">

                    <input type="radio"
                        name="status"
                        value="draft"
                        @checked($selectedStatus === 'draft')
                        required>

                    <div>

                        <strong>Simpan sebagai Draft</strong>

                        <small>
                            Belum ditampilkan kepada pasien.
                        </small>

                    </div>

                </label>

                <label class="announcement-radio-option mt-2">

                    <input type="radio"
                        name="status"
                        value="published"
                        @checked($selectedStatus === 'published')
                        required>

                    <div>

                        <strong>Publikasikan</strong>

                        <small>
                            Dapat langsung dibaca pasien.
                        </small>

                    </div>

                </label>

                @error('status')

                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>

                @enderror

                <hr>

                <button type="submit"
                    class="btn announcement-primary-button w-100"
                    id="announcement-submit">

                    <i class="bi bi-check-circle me-1"></i>

                    {{ $isEdit
                        ? 'Simpan Perubahan'
                        : 'Simpan Pengumuman' }}

                </button>

                <a href="{{ route('admin.announcements.index') }}"
                    class="btn btn-light w-100 mt-2">

                    Batal

                </a>

            </div>

        </div>

    </div>

</form>

@endsection
