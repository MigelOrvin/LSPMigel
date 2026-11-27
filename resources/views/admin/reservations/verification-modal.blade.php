
<div class="modal fade"
    id="appointmentVerificationModal"
    tabindex="-1"
    aria-labelledby="appointmentVerificationTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content appointment-modal">

            <form method="POST"
                id="appointment-verification-form">

                @csrf
                @method('PATCH')

                <input type="hidden"
                    name="status"
                    id="appointment-verification-status">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="appointmentVerificationTitle">

                        Verifikasi Reservasi

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="text-center mb-4">

                        <div id="appointment-verification-icon"
                            class="appointment-verification-icon">
                        </div>

                        <h5 class="fw-bold"
                            id="appointment-verification-heading">
                        </h5>

                        <p class="appointment-muted small mb-0"
                            id="appointment-verification-message">
                        </p>

                    </div>

                    <div class="appointment-modal-summary mb-3">

                        <small>Pasien</small>

                        <div id="appointment-verification-patient"
                            class="fw-bold text-break">
                        </div>

                        <small
                            id="appointment-verification-code"
                            class="text-break">
                        </small>

                    </div>

                    <label for="appointment-admin-note"
                        class="form-label fw-semibold"
                        id="appointment-note-label">

                        Catatan Admin

                    </label>

                    <textarea name="admin_note"
                        id="appointment-admin-note"
                        class="form-control appointment-input"
                        rows="3"
                        maxlength="1000">
                    </textarea>

                    <small class="appointment-muted d-block mt-2"
                        id="appointment-note-help">
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                        class="btn"
                        id="appointment-verification-submit"
                        disabled>

                        Konfirmasi

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
