@extends('layouts.app')

@section('content')

<div class="patient-portal"
    style="--portal-waves: url('{{ asset('images/waves.svg') }}');">

    @include('patient.partials.navbar')

    <main class="portal-main">

        @if (session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                id="success-alert"
                role="alert">

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

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ $errors->first() }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup">
                </button>

            </div>

        @endif

        @yield('patient-content')

    </main>

    @include('patient.partials.footer')

</div>

@endsection