
@extends('layouts.admin')

@section('title', 'Payment Center - DentalCare')
@section('page-title', 'Pembayaran')

@section('admin-content')

@php
    $statuses = [
        'pending' => 'Menunggu Verifikasi',
        'approved' => 'Diterima',
        'rejected' => 'Ditolak'
    ];

    $statistics = [
        [
            'label' => 'Menunggu',
            'value' => $pendingPayments,
            'icon' => 'bi-hourglass-split',
            'color' => 'yellow',
            'status' => 'pending'
        ],
        [
            'label' => 'Diterima',
            'value' => $approvedPayments,
            'icon' => 'bi-check-circle',
            'color' => 'green',
            'status' => 'approved'
        ],
        [
            'label' => 'Ditolak',
            'value' => $rejectedPayments,
            'icon' => 'bi-x-circle',
            'color' => 'red',
            'status' => 'rejected'
        ]
    ];
@endphp

<div class="finance-center">

    {{-- Heading --}}
    <div class="finance-heading mb-4">

        <div>

            <span class="finance-eyebrow">
                DENTALCARE / FINANCE
            </span>

            <h1>Payment Center</h1>

            <p>Kelola dan verifikasi pembayaran pasien.</p>

        </div>

        <div class="finance-heading-icon">
            <i class="bi bi-wallet2"></i>
        </div>

    </div>

    {{-- Revenue Overview --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-6">

            <div class="finance-revenue-card">

                <div class="finance-revenue-content">

                    <span class="finance-revenue-label">
                        TOTAL PEMBAYARAN TERVERIFIKASI
                    </span>

                    <h2>

                        Rp{{ number_format(
                            $totalRevenue,
                            0,
                            ',',
                            '.'
                        ) }}

                    </h2>

                    <p>

                        <i class="bi bi-check-circle me-1"></i>

                        {{ $approvedPayments }}
                        transaksi berhasil diverifikasi

                    </p>

                    <div class="finance-revenue-footer">

                        <small>
                            Transfer Bank
                        </small>

                        <i class="bi bi-bank"></i>

                    </div>

                </div>

                <i class="bi bi-credit-card-2-front
                    finance-revenue-decoration">
                </i>

            </div>

        </div>

        {{-- Statistics --}}
        <div class="col-12 col-xl-6">

            <div class="finance-statistics">

                @foreach ($statistics as $stat)

                    <a href="{{ route('admin.payments.index', [
                        'status' => $stat['status']
                    ]) }}#finance-transactions"
                        class="finance-stat
                            {{ $status === $stat['status']
                                ? 'selected'
                                : '' }}">

                        <div class="finance-stat-icon
                            finance-stat-{{ $stat['color'] }}">

                            <i class="bi {{ $stat['icon'] }}"></i>

                        </div>

                        <div class="flex-grow-1">

                            <small>{{ $stat['label'] }}</small>

                            <h4>{{ $stat['value'] }}</h4>

                        </div>

                        <i class="bi bi-chevron-right
                            finance-stat-arrow">
                        </i>

                    </a>

                @endforeach

            </div>

        </div>

    </div>

    {{-- Transaction List --}}
    <section class="finance-transactions"
        id="finance-transactions">

        <div class="finance-toolbar">

            <div class="d-flex justify-content-between
                align-items-center flex-wrap gap-2 mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        Daftar Transaksi
                    </h5>

                    <small class="finance-muted">

                        {{ $payments->total() }} dari
                        {{ $totalPayments }} transaksi

                    </small>

                </div>

                @if ($search !== '' || $status !== 'all')

                    <a href="{{ route('admin.payments.index') }}#finance-transactions"
                        class="finance-reset">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Filter

                    </a>

                @endif

            </div>

            {{-- Search --}}
            <form method="GET"
                action="{{ route('admin.payments.index') }}#finance-transactions"
                class="finance-search-form">

                <input type="hidden"
                    name="status"
                    value="{{ $status }}">

                <div class="finance-search">

                    <i class="bi bi-search"></i>

                    <input type="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        aria-label="Cari transaksi"
                        placeholder="Cari pasien, dokter, atau kode reservasi...">

                </div>

                <button type="submit"
                    class="btn finance-primary-button">

                    Cari

                </button>

            </form>

            {{-- Status Filter --}}
            <div class="finance-tabs mt-3">

                @php
                    $filters = [
                        'all' => 'Semua',
                        'pending' => 'Menunggu',
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak'
                    ];
                @endphp

                @foreach ($filters as $key => $label)

                    <a href="{{ route('admin.payments.index', [
                        'search' => $search,
                        'status' => $key
                    ]) }}#finance-transactions"
                        class="finance-tab
                            {{ $status === $key ? 'active' : '' }}">

                        {{ $label }}

                    </a>

                @endforeach

            </div>

        </div>

        {{-- Table --}}
        <div class="table-responsive finance-table-wrapper">

            <table class="table finance-table mb-0">

                <thead>

                    <tr>

                        <th>Transaksi</th>
                        <th>Pasien</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th class="text-end">Tindakan</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($payments as $payment)

                        @php
                            $reservation = $payment->reservation;
                            $patient = $reservation->user;
                            $doctor = $reservation->schedule->doctor;

                            $paymentCode = 'PAY-' . str_pad(
                                $payment->id,
                                5,
                                '0',
                                STR_PAD_LEFT
                            );
                        @endphp

                        <tr>

                            {{-- Transaction --}}
                            <td data-label="Transaksi">

                                <div class="finance-transaction">

                                    <div class="finance-transaction-icon">

                                        <i class="bi bi-receipt"></i>

                                    </div>

                                    <div>

                                        <strong>

                                            {{ $paymentCode }}

                                        </strong>

                                        <small>

                                            {{ $payment->updated_at
                                                ->timezone('Asia/Jakarta')
                                                ->format('d M Y, H:i') }}

                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- Patient --}}
                            <td data-label="Pasien">

                                <strong>

                                    {{ $patient->name }}

                                </strong>

                                <small class="finance-table-muted">

                                    {{ $doctor->name }}

                                </small>

                            </td>

                            {{-- Amount --}}
                            <td data-label="Nominal">

                                <strong class="finance-amount">

                                    Rp{{ number_format(
                                        $payment->amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </strong>

                                <small class="finance-table-muted">

                                    Transfer Bank

                                </small>

                            </td>

                            {{-- Status --}}
                            <td data-label="Status">

                                <span class="finance-status
                                    finance-status-{{ $payment->status }}">

                                    {{ $statuses[$payment->status] }}

                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="finance-actions"
                                data-label="Tindakan">

                                <button type="button"
                                    class="btn finance-detail-button
                                        {{ $payment->status === 'pending'
                                            ? 'finance-review-button'
                                            : '' }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#financeDetailModal"
                                    data-code="{{ $paymentCode }}"
                                    data-reservation="{{ $reservation->reservation_code }}"
                                    data-patient="{{ $patient->name }}"
                                    data-email="{{ $patient->email }}"
                                    data-doctor="{{ $doctor->name }}"
                                    data-date="{{ $reservation->reservation_date->locale('id')->translatedFormat('l, d F Y') }}"
                                    data-time="{{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} WIB"
                                    data-amount="Rp{{ number_format($payment->amount, 0, ',', '.') }}"
                                    data-status="{{ $payment->status }}"
                                    data-status-label="{{ $statuses[$payment->status] }}"
                                    data-note="{{ $payment->admin_note ?? '-' }}"
                                    data-verifier="{{ $payment->verifier?->name ?? '-' }}"
                                    data-proof="{{ route('admin.payments.proof', $payment) }}"
                                    data-url="{{ route('admin.payments.status', $payment) }}">

                                    <i class="bi
                                        {{ $payment->status === 'pending'
                                            ? 'bi-shield-check'
                                            : 'bi-eye' }} me-1">
                                    </i>

                                    {{ $payment->status === 'pending'
                                        ? 'Verifikasi'
                                        : 'Detail' }}

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="finance-empty">

                                    <i class="bi bi-receipt"></i>

                                    <h6>Belum ada transaksi</h6>

                                    <p>
                                        Tidak ada pembayaran yang sesuai
                                        dengan pencarian atau filter.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($payments->hasPages())

            <div class="finance-pagination">

                {{ $payments->links() }}

            </div>

        @endif

    </section>

</div>

@include('admin.payments.detail-modal')
@include('admin.payments.verification-modal')

@endsection
