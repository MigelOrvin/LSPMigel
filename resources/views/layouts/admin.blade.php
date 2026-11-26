@extends('layouts.app')

@section('content')

<div class="admin-layout"
    style="--admin-waves: url('{{ asset('images/waves.svg') }}');">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Area --}}
    <div class="admin-main">

        {{-- Navbar --}}
        @include('admin.partials.navbar')

        {{-- Page Content --}}
        <main class="admin-content">

            @if (session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    id="success-alert">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    {{ session('success') }}

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup">
                    </button>

                </div>

            @endif

            @if ($errors->any())

                <div class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-exclamation-circle-fill me-2"></i>

                    {{ $errors->first() }}

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup">
                    </button>

                </div>

            @endif

            @yield('admin-content')

        </main>

    </div>

</div>

@endsection