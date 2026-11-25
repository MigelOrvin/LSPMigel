
@extends('auth.auth')

@section('title', 'Daftar | DentalCare')

@section('auth-content')

<div class="mb-4">

    <h1 class="auth-title fw-bold mb-2">
        Buat akun baru
    </h1>

    <p class="text-secondary mb-0">
        Daftar untuk mulai reservasi.
    </p>

</div>

<form action="{{ route('register.process') }}" method="POST">

    @csrf

    {{-- Nama --}}
    <div class="mb-3">

        <label for="name" class="form-label fw-medium">
            Nama lengkap
        </label>

        <input type="text"
            id="name"
            name="name"
            class="form-control"
            placeholder="Nama lengkap Anda"
            value="{{ old('name') }}"
            autocomplete="name"
            required>

    </div>

    {{-- Email --}}
    <div class="mb-3">

        <label for="email" class="form-label fw-medium">
            Email
        </label>

        <input type="email"
            id="email"
            name="email"
            class="form-control"
            placeholder="nama@email.com"
            value="{{ old('email') }}"
            autocomplete="email"
            required>

    </div>

    {{-- Password --}}
    <div class="mb-3">

        <label for="password" class="form-label fw-medium">
            Password
        </label>

        <div class="position-relative">

            <input type="password"
                id="password"
                name="password"
                class="form-control password-input"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                minlength="8"
                required>

            <button type="button"
                class="password-toggle"
                data-target="password"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>

            </button>

        </div>

    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-4">

        <label for="password_confirmation" class="form-label fw-medium">
            Konfirmasi password
        </label>

        <div class="position-relative">

            <input type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control password-input"
                placeholder="Ulangi password"
                autocomplete="new-password"
                required>

            <button type="button"
                class="password-toggle"
                data-target="password_confirmation"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>

            </button>

        </div>

    </div>

    {{-- Submit --}}
    <button type="submit"
        id="register-btn"
        class="btn btn-primary auth-submit w-100 position-relative">

        Daftar

        <i class="bi bi-arrow-right position-absolute end-0 me-3"></i>

    </button>

</form>

@endsection
