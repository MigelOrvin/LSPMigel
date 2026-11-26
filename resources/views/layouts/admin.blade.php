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
            @yield('admin-content')
        </main>

    </div>

</div>

@endsection