
<div class="modal fade"
    id="bookingDetail{{ $reservation->id }}"
    tabindex="-1"
    aria-labelledby="bookingDetailTitle{{ $reservation->id }}"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered
        modal-dialog-scrollable">

        <div class="modal-content booking-detail-modal">

            <div class="modal-header">

                <h5 class="modal-title fw-bold"
                    id="bookingDetailTitle{{ $reservation->id }}">

                    Detail Reservasi

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>

            <div class="modal-body">

                <div class="booking-detail-summary mb-4">

                    <small class="reservation-muted">
                        Kode Reservasi
                    </small>

                    <div class="fw-bold text-break">

                        {{ $reservation->reservation_code }}

                    </div>

                </div>

                <h6 class="fw-bold mb-3">
                    Informasi Pemeriksaan
                </h6>

                <div class="row g-3">

                    <div class="col-12">

                        <small class="reservation-muted">
                            Dokter
                        </small>

                        <div class="fw-semibold">

                            {{ $reservation->schedule->doctor->name }}

                        </div>

                    </div>

                    <div class="col-6">

                        <small class="reservation-muted">
                            Tanggal
                        </small>

                        <div class="fw-semibold">

                            {{ $reservation->reservation_date
                                ->locale('id')
                                ->translatedFormat('d M Y') }}

                        </div>

                    </div>

                    <div class="col-6">

                        <small class="reservation-muted">
                            Jam Pemeriksaan
                        </small>

                        <div class="fw-semibold">

                            {{ substr($reservation->start_time, 0, 5) }}
                            -
                            {{ substr($reservation->end_time, 0, 5) }}

                        </div>

                    </div>

                    <div class="col-12">

                        <small class="reservation-muted">
                            Keluhan Anda
                        </small>

                        <div class="booking-complaint mt-2">

                            {{ $reservation->complaint }}

                        </div>

                    </div>

                </div>

                <hr>

                <h6 class="fw-bold mb-3">
                    Informasi Pembayaran
                </h6>

                <small class="reservation-muted">
                    Total Biaya
                </small>

                <div class="reservation-fee mb-3">

                    Rp{{ number_format(
                        $reservation->total_amount,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <small class="reservation-muted">
                    Status Pembayaran
                </small>

                <div class="fw-semibold">

                    @switch($reservation->payment?->status)

                        @case('pending')
                            Menunggu Verifikasi
                            @break

                        @case('approved')
                            Pembayaran Terverifikasi
                            @break

                        @case('rejected')
                            Pembayaran Ditolak
                            @break

                        @default
                            Belum Ada Konfirmasi Pembayaran

                    @endswitch

                </div>

                @if ($reservation->payment?->admin_note)

                    <div class="booking-detail-summary mt-3">

                        <small class="reservation-muted">
                            Catatan Pembayaran
                        </small>

                        <div class="text-break">

                            {{ $reservation->payment->admin_note }}

                        </div>

                    </div>

                @endif

                @if ($reservation->admin_note)

                    <div class="booking-detail-summary mt-3">

                        <small class="reservation-muted">
                            Catatan Reservasi
                        </small>

                        <div class="text-break">

                            {{ $reservation->admin_note }}

                        </div>

                    </div>

                @endif

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>
