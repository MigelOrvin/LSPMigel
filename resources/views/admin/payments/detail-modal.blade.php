
<div class="modal fade"
    id="financeDetailModal"
    tabindex="-1"
    aria-labelledby="financeDetailTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-xl
        modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content finance-modal">

            <div class="modal-header">

                <h5 class="modal-title fw-bold"
                    id="financeDetailTitle">

                    Detail Pembayaran

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>

            <div class="modal-body">

                <div class="row g-4">

                    {{-- Proof Image --}}
                    <div class="col-lg-7">

                        <h6 class="fw-bold mb-3">

                            <i class="bi bi-image me-1"></i>
                            Bukti Transfer

                        </h6>

                        <div class="finance-proof-container">

                            <img id="finance-proof-image"
                                alt="Bukti pembayaran pasien"
                                hidden>

                            <div id="finance-proof-message"
                                class="finance-proof-message">

                                Memuat bukti pembayaran...

                            </div>

                        </div>

                        <a id="finance-proof-link"
                            class="btn finance-detail-button mt-3"
                            target="_blank"
                            rel="noopener noreferrer"
                            hidden>

                            <i class="bi bi-arrows-fullscreen me-1"></i>

                            Buka Ukuran Penuh

                        </a>

                    </div>

                    {{-- Payment Information --}}
                    <div class="col-lg-5">

                        <h6 class="fw-bold mb-3">
                            Informasi Transaksi
                        </h6>

                        <div class="finance-modal-summary mb-4">

                            <small class="finance-muted">
                                Nominal Tagihan
                            </small>

                            <h3 class="finance-amount mt-1"
                                id="finance-detail-amount">
                            </h3>

                            <span id="finance-detail-status"
                                class="finance-status">
                            </span>

                        </div>

                        <div class="finance-detail-list">

                            @foreach ([
                                'code' => 'Kode Pembayaran',
                                'reservation' => 'Kode Reservasi',
                                'patient' => 'Nama Pasien',
                                'email' => 'Email Pasien',
                                'doctor' => 'Dokter',
                                'date' => 'Tanggal Pemeriksaan',
                                'time' => 'Jam Pemeriksaan',
                                'verifier' => 'Diverifikasi Oleh'
                            ] as $field => $label)

                                <div>

                                    <small>{{ $label }}</small>

                                    <strong
                                        id="finance-detail-{{ $field }}">
                                    </strong>

                                </div>

                            @endforeach

                        </div>

                        <div class="finance-note-box mt-3">

                            <small class="finance-muted">
                                Catatan Admin
                            </small>

                            <div id="finance-detail-note"
                                class="text-break mt-1">
                            </div>

                        </div>

                        <div class="finance-review-notice mt-3"
                            id="finance-review-notice"
                            hidden>

                            <i class="bi bi-info-circle"></i>

                            <span>

                                Pastikan nominal, rekening tujuan,
                                tanggal, dan bukti transfer sesuai
                                sebelum menyetujui pembayaran.

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

                <div id="finance-review-actions"
                    class="finance-review-actions"
                    hidden>

                    <button type="button"
                        class="btn finance-reject-button"
                        id="finance-reject-trigger">

                        <i class="bi bi-x-lg me-1"></i>

                        Tolak Pembayaran

                    </button>

                    <button type="button"
                        class="btn finance-approve-button"
                        id="finance-approve-trigger"
                        disabled>

                        <i class="bi bi-check-lg me-1"></i>

                        Setujui Pembayaran

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
