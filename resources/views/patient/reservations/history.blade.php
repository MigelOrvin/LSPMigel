
<section class="reservation-history-section"
    id="reservation-history">

    <div class="reservation-section-heading mb-4">

        <h5>

            Riwayat Reservasi

            <span class="reservation-section-count">
                {{ $historyReservations->total() }}
            </span>

        </h5>

        <p>
            Informasi reservasi yang telah berakhir.
        </p>

    </div>

    <div class="reservation-history-list">

        @forelse ($historyReservations as $reservation)

            @php
                $doctor = $reservation->schedule->doctor;

                $displayStatus = $reservation->display_status;

                $paymentStatus = $reservation->payment?->status;

                $isPastDate = $reservation->reservation_date
                    ->format('Y-m-d')
                    < now('Asia/Jakarta')->toDateString();

                if (
                    $isPastDate &&
                    in_array($reservation->status, [
                        'approved',
                        'confirmed'
                    ], true)
                ) {
                    $displayStatus = 'schedule_ended';
                }

                $historyStatus = match ($displayStatus) {

                    'payment_rejected' => [
                        'label' => 'Pembayaran Ditolak',
                        'color' => 'rejected',
                        'icon' => 'bi-x-circle',
                        'message' =>
                            $reservation->payment?->admin_note
                            ?? 'Pembayaran ditolak oleh admin.'
                    ],

                    'expired' => [
                        'label' => 'Kedaluwarsa',
                        'color' => 'waiting',
                        'icon' => 'bi-clock-history',
                        'message' =>
                            'Bukti pembayaran tidak dikirim '
                            . 'dalam batas waktu.'
                    ],

                    'rejected' => [
                        'label' => 'Reservasi Ditolak',
                        'color' => 'rejected',
                        'icon' => 'bi-x-circle',
                        'message' =>
                            $reservation->admin_note
                            ?? 'Reservasi ditolak oleh admin.'
                    ],

                    'cancelled' => [
                        'label' => 'Dibatalkan',
                        'color' => 'rejected',
                        'icon' => 'bi-x-circle',
                        'message' =>
                            'Reservasi telah dibatalkan.'
                    ],

                    'completed' => [
                        'label' => 'Selesai',
                        'color' => 'approved',
                        'icon' => 'bi-check-circle',
                        'message' =>
                            'Pemeriksaan telah selesai.'
                    ],

                    'schedule_ended' => [
                        'label' => 'Jadwal Berakhir',
                        'color' => 'approved',
                        'icon' => 'bi-calendar-check',
                        'message' =>
                            'Tanggal pemeriksaan telah berlalu.'
                    ],

                    'payment_pending' => [
                        'label' => 'Verifikasi Pembayaran',
                        'color' => 'processing',
                        'icon' => 'bi-hourglass-split',
                        'message' =>
                            'Jadwal telah berlalu, tetapi pembayaran '
                            . 'masih menunggu keputusan admin.'
                    ],

                    'reservation_pending' => [
                        'label' => 'Verifikasi Reservasi',
                        'color' => 'processing',
                        'icon' => 'bi-hourglass-split',
                        'message' =>
                            'Jadwal telah berlalu, tetapi reservasi '
                            . 'masih menunggu keputusan admin.'
                    ],

                    default => [
                        'label' => 'Jadwal Berlalu',
                        'color' => 'waiting',
                        'icon' => 'bi-info-circle',
                        'message' =>
                            'Jadwal reservasi telah berlalu.'
                    ]
                };
            @endphp

            <article class="reservation-history-item">

                <div class="history-item-main">

                    {{-- Doctor Profile --}}
                    <div class="history-doctor">

                        <div class="history-doctor-photo">

                            @if ($doctor->photo)

                                <img
                                    src="{{ asset($doctor->photo) }}"
                                    alt="{{ $doctor->name }}">

                            @else

                                <i class="bi bi-person-fill"></i>

                            @endif

                        </div>

                        <div class="history-doctor-info">

                            <small class="history-reservation-code">

                                {{ $reservation->reservation_code }}

                            </small>

                            <h6>{{ $doctor->name }}</h6>

                            <small class="reservation-muted">

                                Pemeriksaan Gigi

                            </small>

                        </div>

                    </div>

                    {{-- Schedule --}}
                    <div class="history-schedule">

                        <small>Tanggal Pemeriksaan</small>

                        <strong>

                            {{ $reservation->reservation_date
                                ->locale('id')
                                ->translatedFormat('d M Y') }}

                        </strong>

                        <span>

                            {{ substr($reservation->start_time, 0, 5) }}
                            -
                            {{ substr($reservation->end_time, 0, 5) }}
                            WIB

                        </span>

                    </div>

                    {{-- Amount --}}
                    <div class="history-amount">

                        <small>Total Biaya</small>

                        <strong>

                            Rp{{ number_format(
                                $reservation->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </strong>

                    </div>

                    {{-- Final Status --}}
                    <div class="history-item-status">

                        <span class="patient-booking-status
                            booking-status-{{ $historyStatus['color'] }}">

                            {{ $historyStatus['label'] }}

                        </span>

                    </div>

                    {{-- Detail Button --}}
                    <div class="history-item-actions">

                        <button type="button"
                            class="btn booking-detail-button"
                            data-bs-toggle="modal"
                            data-bs-target="#bookingDetail{{ $reservation->id }}">

                            <i class="bi bi-eye me-1"></i>
                            Detail

                        </button>

                    </div>

                </div>

                {{-- Final Information --}}
                <div class="history-item-message
                    history-message-{{ $historyStatus['color'] }}">

                    <i class="bi {{ $historyStatus['icon'] }}"></i>

                    <div>

                        @if (in_array($displayStatus, [
                            'payment_rejected',
                            'rejected'
                        ], true))

                            <strong>Alasan:</strong>

                        @endif

                        {{ $historyStatus['message'] }}

                        @if (
                            $displayStatus === 'rejected' &&
                            $paymentStatus === 'approved'
                        )

                            <div class="mt-1">

                                Silakan hubungi klinik untuk
                                proses pengembalian dana.

                            </div>

                        @endif

                    </div>

                </div>

            </article>

            @include('patient.reservations.detail-modal')

        @empty

            <div class="reservation-history-empty">

                <i class="bi bi-clock-history"></i>

                <div>

                    <h6>Belum ada riwayat reservasi</h6>

                    <p>
                        Reservasi yang telah berakhir
                        akan ditampilkan di sini.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

    @if ($historyReservations->hasPages())

        <div class="mt-4">

            {{ $historyReservations
                ->fragment('reservation-history')
                ->links() }}

        </div>

    @endif

</section>
