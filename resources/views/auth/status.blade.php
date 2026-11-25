
@extends('auth.auth')

@section('title', 'Status Pendaftaran | DentalCare')

@section('auth-content')

@php
    $isPending = $user->status === 'pending';
@endphp

<div class="mb-4">
    <h1 class="auth-title fw-bold mb-2">
        Status pendaftaran
    </h1>

    <p class="text-secondary mb-0">
        Informasi verifikasi akun Anda.
    </p>
</div>

{{-- Status Alert --}}
<div class="alert {{ $isPending ? 'alert-warning' : 'alert-danger' }} mb-4">

    <div class="d-flex align-items-center gap-2 mb-2">

        <i class="bi {{ $isPending ? 'bi-clock-history' : 'bi-x-circle' }} fs-5"></i>

        <span class="fw-semibold">
            {{ $isPending ? 'Menunggu persetujuan' : 'Pendaftaran ditolak' }}
        </span>

    </div>

    <p class="small mb-0">
        @if ($isPending)
            Akun Anda sedang dalam proses verifikasi oleh admin.
        @else
            Pendaftaran akun Anda telah ditolak oleh admin.
        @endif
    </p>

</div>

{{-- Informasi Akun --}}
<div class="card rounded-3 mb-4">

    <div class="card-body p-4">

        <div class="mb-3">
            <label class="text-secondary small mb-1">
                Nama lengkap
            </label>

            <div class="fw-medium text-break">
                {{ $user->name }}
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <label class="text-secondary small mb-1">
                Email
            </label>

            <div class="fw-medium text-break">
                {{ $user->email }}
            </div>
        </div>

        <hr>

        <div>
            <label class="text-secondary small mb-2">
                Status verifikasi
            </label>

            <div>
                <span class="badge rounded-2 px-3 py-2
                    {{ $isPending
                        ? 'bg-warning-subtle text-warning-emphasis'
                        : 'bg-danger-subtle text-danger' }}">

                    {{ $isPending ? 'Menunggu persetujuan' : 'Ditolak' }}

                </span>
            </div>
        </div>

    </div>

</div>

{{-- Refresh Status --}}
@if ($isPending)

    <a href="{{ route('registration.status') }}"
        class="btn btn-primary auth-submit w-100
               d-flex align-items-center justify-content-center gap-2 mb-3">

        <i class="bi bi-arrow-clockwise"></i>

        Perbarui status

    </a>

@endif

{{-- Back to Login --}}
<div class="text-center mt-3">

    <a href="{{ route('login') }}"
        class="text-decoration-none small fw-medium">

        <i class="bi bi-arrow-left me-1"></i>

        Kembali ke halaman masuk

    </a>

</div>

@endsection
