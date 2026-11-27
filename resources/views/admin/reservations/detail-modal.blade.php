
<div class="modal fade"
    id="appointmentDetailModal"
    tabindex="-1"
    aria-labelledby="appointmentDetailTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered
        modal-dialog-scrollable">

        <div class="modal-content appointment-modal">

            <div class="modal-header">

                <h5 class="modal-title fw-bold"
                    id="appointmentDetailTitle">

                    Detail Reservasi

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>

            <div class="modal-body">

                <div class="appointment-modal-summary mb-4">

                    <small>Kode Reservasi</small>

                    <h6 id="detail-reservation"
                        class="text-break fw-bold mt-1">
                    </h6>

                    <span id="detail-status"
                        class="appointment-modal-status">
                    </span>

                </div>

                <h6 class="fw-bold mb-3">
                    Informasi Pasien
                </h6>

                <div class="row g-3 mb-4">

                    <div class="col-12">

                        <small class="appointment-muted">
                            Nama Pasien
                        </small>

                        <div class="fw-semibold text-break"
                            id="detail-patient">
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="appointment-muted">
                            Email
                        </small>

                        <div class="text-break"
                            id="detail-email">
                        </div>

                    </div>

                </div>

                <hr>

                <h6 class="fw-bold mb-3">
                    Informasi Pemeriksaan
                </h6>

                <div class="row g-3 mb-4">

                    <div class="col-12">

                        <small class="appointment-muted">
                            Dokter
                        </small>

                        <div class="fw-semibold"
                            id="detail-doctor">
                        </div>

                    </div>

                    <div class="col-12 col-sm-6">

                        <small class="appointment-muted">
                            Tanggal Pemeriksaan
                        </small>

                        <div class="fw-semibold"
                            id="detail-date">
                        </div>

                    </div>

                    <div class="col-12 col-sm-6">

                        <small class="appointment-muted">
                            Jam Pemeriksaan
                        </small>

                        <div class="fw-semibold"
                            id="detail-time">
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="appointment-muted">
                            Keluhan Pasien
                        </small>

                        <div class="appointment-complaint mt-2"
                            id="detail-complaint">
                        </div>

                    </div>

                </div>

                <hr>

                <small class="appointment-muted">
                    Total Biaya Pemeriksaan
                </small>

                <h4 class="appointment-fee mt-1"
                    id="detail-fee">
                </h4>

                <div class="appointment-note-box mt-3">

                    <small class="appointment-muted">
                        Diverifikasi oleh
                    </small>

                    <div class="fw-semibold mb-3"
                        id="detail-verifier">
                    </div>

                    <small class="appointment-muted">
                        Catatan Admin
                    </small>

                    <div class="text-break"
                        id="detail-note">
                    </div>

                </div>

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
