
@extends('layouts.admin')

@section('title', 'Manajemen Pasien - DentalCare')
@section('page-title', 'Pasien')

@section('admin-content')

@php
    $statistics = [
        [
            'label' => 'Total Pasien',
            'value' => $totalPatients,
            'icon' => 'bi-people',
            'color' => 'stat-teal'
        ],
        [
            'label' => 'Menunggu Verifikasi',
            'value' => $pendingPatients,
            'icon' => 'bi-hourglass-split',
            'color' => 'stat-warning'
        ],
        [
            'label' => 'Akun Disetujui',
            'value' => $approvedPatients,
            'icon' => 'bi-person-check',
            'color' => 'stat-success'
        ],
        [
            'label' => 'Akun Ditolak',
            'value' => $rejectedPatients,
            'icon' => 'bi-person-x',
            'color' => 'stat-danger'
        ]
    ];

    $filters = [
        'all' => 'Semua',
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak'
    ];
@endphp

{{-- Page Heading --}}
<div class="mb-4">

    <h1 class="admin-page-title">Pasien</h1>

    <p class="admin-muted mb-0">
        Kelola dan verifikasi pendaftaran akun pasien.
    </p>

</div>

{{-- Statistics --}}
<div class="row g-2 g-md-3 mb-4">

    @foreach ($statistics as $stat)

        <div class="col-6 col-xl-3">

            <div class="stat-card patient-stat-card">

                <div class="stat-icon {{ $stat['color'] }}">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </div>

                <div class="stat-label">
                    {{ $stat['label'] }}
                </div>

                <div class="stat-value">
                    {{ $stat['value'] }}
                </div>

            </div>

        </div>

    @endforeach

</div>

{{-- Patient List --}}
<div class="admin-card">

    <div class="admin-card-header">

        <h5 class="fw-bold mb-1">Daftar Pasien</h5>

        <p class="admin-muted small mb-0">
            Tinjau dan kelola status pendaftaran pasien.
        </p>

    </div>

    {{-- Search & Filter --}}
    <div class="patient-toolbar">

        <form action="{{ route('admin.patients.index') }}"
            method="GET"
            class="patient-search">

            <input type="hidden"
                name="status"
                value="{{ $status }}">

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>

                <input type="search"
                    class="form-control"
                    name="search"
                    aria-label="Cari pasien"
                    placeholder="Cari nama atau email..."
                    value="{{ $search }}">

                <button class="btn btn-admin-primary"
                    type="submit">
                    Cari
                </button>

            </div>

        </form>

        <div class="patient-filters">

            @foreach ($filters as $value => $label)

                <a href="{{ route('admin.patients.index', [
                    'status' => $value,
                    'search' => $search
                ]) }}"
                    class="patient-filter
                    {{ $status === $value ? 'active' : '' }}">

                    {{ $label }}

                </a>

            @endforeach

        </div>

    </div>

    {{-- Patient Table --}}
    <div class="table-responsive">

        <table class="table admin-table patient-table align-middle mb-0">

            <thead>
                <tr>
                    <th>Pasien</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($patients as $patient)

                    <tr>

                        {{-- Patient Identity --}}
                        <td>

                            <div class="patient-identity">

                                <div class="patient-avatar">

                                    {{ mb_strtoupper(
                                        mb_substr($patient->name, 0, 1)
                                    ) }}

                                </div>

                                <div class="patient-info">

                                    <div class="patient-name">
                                        {{ $patient->name }}
                                    </div>

                                    <small class="admin-muted">
                                        {{ $patient->email }}
                                    </small>

                                </div>

                            </div>

                        </td>

                        {{-- Registration Date --}}
                        <td class="patient-date">

                            <span class="d-md-none me-1">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            {{ $patient->created_at->format('d M Y') }}

                        </td>

                        {{-- Status --}}
                        <td>

                            <span class="status-badge
                                status-{{ $patient->status }}">

                                {{ $filters[$patient->status] }}

                            </span>

                        </td>

                        {{-- Actions --}}
                        <td>

                            @if ($patient->status === 'pending')

                                <div class="patient-actions">

                                    <button type="button"
                                        class="btn btn-patient-approve"
                                        data-bs-toggle="modal"
                                        data-bs-target="#verificationModal"
                                        data-patient="{{ $patient->name }}"
                                        data-status="approved"
                                        data-url="{{ route(
                                            'admin.patients.status',
                                            $patient
                                        ) }}">

                                        <i class="bi bi-check-lg me-1"></i>
                                        Setujui

                                    </button>

                                    <button type="button"
                                        class="btn btn-patient-reject"
                                        data-bs-toggle="modal"
                                        data-bs-target="#verificationModal"
                                        data-patient="{{ $patient->name }}"
                                        data-status="rejected"
                                        data-url="{{ route(
                                            'admin.patients.status',
                                            $patient
                                        ) }}">

                                        <i class="bi bi-x-lg me-1"></i>
                                        Tolak

                                    </button>

                                </div>

                            @else

                                <span class="admin-muted small">

                                    <i class="bi bi-check2-circle me-1"></i>
                                    Sudah ditinjau

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center py-5">

                            <i class="bi bi-people fs-1 admin-muted"></i>

                            <h6 class="mt-3">
                                Pasien tidak ditemukan
                            </h6>

                            <p class="admin-muted small mb-0">
                                Belum ada data sesuai pencarian atau filter.
                            </p>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if ($patients->hasPages())

        <div class="patient-pagination">
            {{ $patients->links() }}
        </div>

    @endif

</div>

{{-- Verification Modal --}}
@include('admin.patients.verification-modal')

@endsection
