
@extends('auth.auth')

@section('title', 'Masuk | DentalCare')

@section('auth-content')

<div class="mb-4">
    <h1 class="auth-title fw-bold mb-2">
        Selamat datang!
    </h1>

    <p class="text-secondary mb-0">
        Masuk untuk melanjutkan reservasi Anda.
    </p>
</div>

<form action="{{ route('login.process') }}" method="POST">

    @csrf

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
    <div class="mb-4">

        <label for="password" class="form-label fw-medium">
            Password
        </label>

        <div class="position-relative">

            <input type="password"
                id="password"
                name="password"
                class="form-control password-input"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required>

            <button type="button"
                class="password-toggle"
                data-target="password"
                aria-label="Tampilkan password">

                <i class="bi bi-eye"></i>

            </button>

        </div>

    </div>

    {{-- Submit --}}
    <button type="submit"
        class="btn btn-primary auth-submit w-100 position-relative">

        Masuk

        <i class="bi bi-arrow-right position-absolute end-0 me-3"></i>

    </button>

</form>

@endsection
