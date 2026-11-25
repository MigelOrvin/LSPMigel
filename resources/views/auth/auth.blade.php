
@extends('layouts.app')

@section('content')

<div class="auth-page d-flex flex-column min-vh-100">

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

    {{-- Main Content --}}
    <main class="flex-grow-1 d-flex justify-content-center align-items-center px-4 py-5">

        <div class="auth-content w-100">

            
            {{-- Navigation --}}
            @if (request()->routeIs('login', 'register'))

                <div class="auth-navigation d-flex p-1 rounded-3 mb-4">

                    <a href="{{ route('login') }}"
                        class="auth-tab {{ request()->routeIs('login') ? 'active' : '' }}">
                        Masuk
                    </a>

                    <a href="{{ route('register') }}"
                        class="auth-tab {{ request()->routeIs('register') ? 'active' : '' }}">
                        Daftar
                    </a>

                </div>

            @endif


            {{-- Success Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}

                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Tutup">
                    </button>
                </div>
            @endif

            {{-- Warning Alert --}}
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}

                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Tutup">
                    </button>
                </div>
            @endif

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}

                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Tutup">
                    </button>
                </div>
            @endif

            {{-- Login or Register Form --}}
            @yield('auth-content')

        </div>

    </main>

    {{-- Footer --}}
    <footer class="text-center text-secondary small py-4">
        &copy; {{ date('Y') }} DentalCare
    </footer>

</div>

@endsection
