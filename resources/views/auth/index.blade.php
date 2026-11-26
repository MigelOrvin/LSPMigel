
@extends('auth.auth')

@section('title', 'DentalCare')

@section('auth-content')

<div id="auth-slider"
    class="auth-slider {{ $mode === 'register' ? 'active' : '' }}"
    style="--auth-bg: url('{{ asset('images/background.gif') }}');">
    

    {{-- Sliding Glass --}}
    <div class="auth-glass"></div>
    

    {{-- Login Form --}}
    <div class="auth-form-panel login-panel">
        {{-- Header --}}
    <header class="border-bottom">
        <div class="container d-flex justify-content-between align-items-center py-3">

            <a href="{{ route('login') }}"
                class="auth-brand text-decoration-none fw-bold fs-4">

                <i class="bi bi-heart-pulse text-primary me-1"></i>
                DentalCare<span class="text-primary">.</span>

            </a>


        </div>
    </header>

        @include('auth.partials.login')

        <div class="d-md-none text-center mt-4">
            <span class="text-secondary small">
                Belum punya akun?
            </span>

            <button type="button"
                class="btn btn-link p-0 text-decoration-none"
                data-auth-mode="register">
                Daftar
            </button>
        </div>

    </div>

    {{-- Register Form --}}
    <div class="auth-form-panel register-panel">
        {{-- Header --}}
    <header class="border-bottom">
        <div class="container d-flex justify-content-between align-items-center py-3">

            <a href="{{ route('login') }}"
                class="auth-brand text-decoration-none fw-bold fs-4">

                <i class="bi bi-heart-pulse text-primary me-1"></i>
                DentalCare<span class="text-primary">.</span>

            </a>


        </div>
    </header>

        @include('auth.partials.register')

        <div class="d-md-none text-center mt-4">
            <span class="text-secondary small">
                Sudah punya akun?
            </span>

            <button type="button"
                class="btn btn-link p-0 text-decoration-none"
                data-auth-mode="login">
                Masuk
            </button>
        </div>

    </div>

    {{-- Right Panel --}}
    <div class="auth-switch-panel switch-right">

        <h2 class="fw-bold mb-4">
            Belum punya akun?
        </h2>

        <button type="button"
            class="btn btn-outline-light px-5 py-2"
            data-auth-mode="register">
            Daftar
        </button>

    </div>

    {{-- Left Panel --}}
    <div class="auth-switch-panel switch-left">

        <h2 class="fw-bold mb-4">
            Sudah punya akun?
        </h2>

        <button type="button"
            class="btn btn-outline-light px-5 py-2"
            data-auth-mode="login">
            Masuk
        </button>

    </div>

</div>

@endsection
