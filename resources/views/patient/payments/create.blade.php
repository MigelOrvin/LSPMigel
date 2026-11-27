
@extends('layouts.patient')

@section('title', 'Pembayaran - DentalCare')

@section('patient-content')

@php
    $doctor = $reservation->schedule->doctor;

    $isResubmission =
        $reservation->payment?->status === 'rejected';
@endphp

<a href="{{ route('patient.reservations.index') }}"
    class="reservation-back">

    <i class="bi bi-arrow-left me-2"></i>
    Kembali ke Reservasi

</a>

<div class="payment-heading mt-4 mb-4">

    <h1 class="reservation-title">

        {{ $isResubmission
            ? 'Upload Ulang Pembayaran'
            : 'Pembayaran' }}

    </h1>

    <p class="reservation-muted mb-0">

        Selesaikan pembayaran untuk melanjutkan
        proses reservasi pemeriksaan Anda.

    </p>

</div>

@if ($errors->any())

    <div class="alert alert-danger">

        <i class="bi bi-exclamation-circle me-1"></i>

        {{ $errors->first() }}

    </div>

@endif

<div class="row g-4 align-items-start">

    {{-- Reservation Summary --}}
    <div class="col-lg-4">

        <div class="portal-card payment-card">

            <h6 class="fw-bold mb-4">

                <i class="bi bi-receipt me-2"></i>
                Ringkasan Reservasi

            </h6>

            <div class="portal-doctor-profile mb-4">

                <div class="portal-doctor-photo">

                    @if ($doctor->photo)

                        <img src="{{ asset($doctor->photo) }}"
                            alt="{{ $doctor->name }}">

                    @else

                        <i class="bi bi-person-fill"></i>

                    @endif

                </div>

                <div>

                    <h6 class="fw-bold mb-1">

                        {{ $doctor->name }}

                    </h6>

                    <small class="reservation-muted">

                        Pemeriksaan Gigi

                    </small>

                </div>

            </div>

            <div class="payment-summary-list">

                <div>

                    <small>Kode Reservasi</small>

                    <strong class="text-break">

                        {{ $reservation->reservation_code }}

                    </strong>

                </div>

                <div>

                    <small>Tanggal Pemeriksaan</small>

                    <strong>

                        {{ $reservation->reservation_date
                            ->locale('id')
                            ->translatedFormat('l, d F Y') }}

                    </strong>

                </div>

                <div>

                    <small>Jam Pemeriksaan</small>

                    <strong>

                        {{ substr($reservation->start_time, 0, 5) }}
                        -
                        {{ substr($reservation->end_time, 0, 5) }}

                        WIB

                    </strong>

                </div>

                <div>

                    <small>Durasi</small>

                    <strong>60 Menit</strong>

                </div>

            </div>

            <hr>

            <small class="reservation-muted">
                Total Pembayaran
            </small>

            <div class="payment-total">

                Rp{{ number_format(
                    $reservation->total_amount,
                    0,
                    ',',
                    '.'
                ) }}

            </div>

        </div>

    </div>

    {{-- Payment Form --}}
    <div class="col-lg-8">

        <div class="portal-card payment-card">

            <h5 class="fw-bold mb-1">
                Metode Pembayaran
            </h5>

            <p class="reservation-muted small mb-4">

                Lakukan transfer sesuai nominal tagihan.

            </p>


            {{-- Bank Method --}}
            <div class="payment-method">

                <div class="payment-method-icon">

                    <i class="bi bi-bank"></i>

                </div>

                <div class="flex-grow-1">

                    <strong>Transfer Bank</strong>

                    <small class="d-block reservation-muted">

                        Pembayaran dengan verifikasi manual

                    </small>

                </div>

                <i class="bi bi-check-circle-fill
                    payment-method-check">
                </i>

            </div>

            {{-- Bank Information --}}
            <div class="payment-bank-information mt-3">

                <span class="payment-bank-label">
                    Nama Bank
                </span>

                <h6 class="fw-bold mb-3">

                    {{ config('payment.bank_name') }}

                </h6>

                <span class="payment-bank-label">
                    Nomor Rekening
                </span>

                <div class="d-flex align-items-center
                    justify-content-between gap-2">

                    <strong class="payment-account-number"
                        id="payment-account-number">

                        {{ config('payment.account_number') }}

                    </strong>

                    <button type="button"
                        class="btn payment-copy-button"
                        id="copy-account-number">

                        <i class="bi bi-copy"></i>

                        <span>Salin</span>

                    </button>

                </div>

                <span class="payment-bank-label mt-3">

                    Nama Pemilik Rekening

                </span>

                <strong>

                    {{ config('payment.account_holder') }}

                </strong>

            </div>

            {{-- Instructions --}}
            <div class="payment-instructions mt-4">

                <h6 class="fw-bold">
                    Petunjuk Pembayaran
                </h6>

                <ol class="mb-0">

                    <li>
                        Salin nomor rekening tujuan.
                    </li>

                    <li>

                        Transfer sesuai total pembayaran,

                        <strong>

                            Rp{{ number_format(
                                $reservation->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </strong>.

                    </li>

                    <li>
                        Simpan bukti transfer.
                    </li>

                    <li>
                        Unggah bukti pembayaran di bawah.
                    </li>

                </ol>

            </div>

            <hr>

            @if ($isResubmission)

                <div class="alert alert-danger small">

                    <strong>
                        Pembayaran sebelumnya ditolak.
                    </strong>

                    <p class="mb-0 mt-1">

                        {{ $reservation->payment->admin_note
                            ?? 'Silakan periksa kembali bukti pembayaran.' }}

                    </p>

                </div>

            @endif

            {{-- Upload Form --}}
            <form
                action="{{ route(
                    'patient.payments.store',
                    $reservation
                ) }}"
                method="POST"
                enctype="multipart/form-data"
                id="payment-form">

                @csrf

                <h6 class="fw-bold mb-2">

                    Upload Bukti Pembayaran

                </h6>

                <p class="reservation-muted small">

                    Pastikan nominal, tanggal, dan informasi
                    transfer terlihat dengan jelas.

                </p>

                <input type="file"
                    name="payment_proof"
                    id="payment-proof"
                    accept="image/jpeg,image/png,image/webp"
                    class="visually-hidden"
                    required>

                <label for="payment-proof"
                    class="payment-upload-area"
                    id="payment-upload-area">

                    <i class="bi bi-cloud-arrow-up"></i>

                    <strong>
                        Klik untuk memilih gambar
                    </strong>

                    <span>
                        atau drag and drop di sini
                    </span>

                    <small>
                        JPG, PNG, WEBP — Maksimal 2 MB
                    </small>

                </label>

                <div id="payment-file-error"
                    class="text-danger small mt-2"
                    role="alert"
                    hidden>
                </div>

                {{-- Preview --}}
                <div class="payment-preview mt-3"
                    id="payment-preview"
                    hidden>

                    <img id="payment-preview-image"
                        alt="Preview bukti pembayaran">

                    <div class="payment-preview-footer">

                        <div class="payment-file-information">

                            <strong id="payment-file-name">
                            </strong>

                            <small id="payment-file-size">
                            </small>

                        </div>

                        <button type="button"
                            class="btn payment-remove-button"
                            id="remove-payment-proof"
                            aria-label="Hapus bukti pembayaran">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>

                <div class="payment-notice mt-4">

                    <i class="bi bi-info-circle"></i>

                    <div>

                        <strong>
                            Verifikasi Pembayaran
                        </strong>

                        <p>

                            Pembayaran akan diperiksa admin.
                            Setelah pembayaran disetujui,
                            reservasi Anda akan masuk ke
                            tahap verifikasi jadwal.

                        </p>

                    </div>

                </div>

                <button type="submit"
                    class="btn portal-primary-button
                        w-100 mt-4"
                    id="payment-submit"
                    disabled>

                    <i class="bi bi-check-circle me-2"></i>

                    Konfirmasi Pembayaran

                </button>

            </form>

        </div>

    </div>

</div>

@endsection
