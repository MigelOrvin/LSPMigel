
@extends('layouts.admin')

@section('title', 'Admin Dashboard - DentalCare')

@section('page-title', 'Dashboard')

@section('admin-content')

{{-- Page Heading --}}
<div class="mb-4">

    <h1 class="admin-page-title">
        Dashboard
    </h1>

    <p class="admin-muted mb-0">
        Pantau aktivitas dan kelola operasional klinik.
    </p>

</div>

{{-- Welcome Banner --}}
<div class="admin-welcome mb-4">

    <div class="welcome-content">

        <div class="welcome-label">
            DENTALCARE ADMIN PORTAL
        </div>

        <h2>
            Selamat datang, {{ auth()->user()->name }}!
        </h2>

        <p class="mb-0">
            Kelola reservasi dan pelayanan pasien
            dengan mudah dalam satu tempat.
        </p>

    </div>

    <i class="bi bi-heart-pulse welcome-icon"></i>

</div>

{{-- Statistics --}}
<div class="row g-2 g-md-3 mb-4">

    {{-- Total Pasien --}}
    <div class="col-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon stat-teal">
                <i class="bi bi-people"></i>
            </div>

            <div class="stat-label">
                Total Pasien
            </div>

            <div class="stat-value">
                {{ $totalPatients }}
            </div>

            <small class="admin-muted">
                Seluruh akun pasien
            </small>

        </div>

    </div>

    {{-- Pending --}}
<div class="col-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon stat-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="stat-label">
                Menunggu Verifikasi
            </div>

            <div class="stat-value">
                {{ $pendingPatients }}
            </div>

            <small class="admin-muted">
                Membutuhkan persetujuan
            </small>

        </div>

    </div>

    {{-- Approved --}}
<div class="col-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon stat-success">
                <i class="bi bi-person-check"></i>
            </div>

            <div class="stat-label">
                Akun Disetujui
            </div>

            <div class="stat-value">
                {{ $approvedPatients }}
            </div>

            <small class="admin-muted">
                Pasien terverifikasi
            </small>

        </div>

    </div>

    {{-- Rejected --}}
<div class="col-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon stat-danger">
                <i class="bi bi-person-x"></i>
            </div>

            <div class="stat-label">
                Akun Ditolak
            </div>

            <div class="stat-value">
                {{ $rejectedPatients }}
            </div>

            <small class="admin-muted">
                Pendaftaran tidak disetujui
            </small>

        </div>

    </div>

</div>

{{-- Recent Patients --}}
<div class="admin-card">

    <div class="admin-card-header">

        <div>

            <h5 class="fw-bold mb-1">
                Pendaftaran Pasien Terbaru
            </h5>

            <p class="admin-muted small mb-0">
                Daftar lima pasien yang terakhir mendaftar.
            </p>

        </div>

    </div>

    <div class="table-responsive">

        <table class="table admin-table align-middle mb-0">

            <thead>
                <tr>
                    <th>Nama Pasien</th>
                    <th>Email</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($recentPatients as $patient)

                    <tr>

                        <td class="fw-semibold">
                            {{ $patient->name }}
                        </td>

                        <td>
                            {{ $patient->email }}
                        </td>

                        <td>
                            {{ $patient->created_at->format('d M Y') }}
                        </td>

                        <td>

                            @if ($patient->status === 'pending')

                                <span class="status-badge status-pending">
                                    Menunggu
                                </span>

                            @elseif ($patient->status === 'approved')

                                <span class="status-badge status-approved">
                                    Disetujui
                                </span>

                            @elseif ($patient->status === 'rejected')

                                <span class="status-badge status-rejected">
                                    Ditolak
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center py-5">

                            <i class="bi bi-inbox fs-2 admin-muted"></i>

                            <p class="admin-muted mt-2 mb-0">
                                Belum ada pasien yang terdaftar.
                            </p>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
