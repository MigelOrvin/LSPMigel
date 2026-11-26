
@extends('layouts.app')

@section('content')
<div class="auth-page d-flex flex-column min-vh-100"
    style="--page-vector: url('{{ asset('images/waves.svg') }}');">

    

    {{-- Main Content --}}
    <main class="flex-grow-1 d-flex justify-content-center align-items-center px-4 py-5">

        <div class="w-100 {{ isset($mode) ? 'auth-wide' : 'auth-content' }}">

            


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


</div>

@endsection
