
<div class="modal fade"
    id="financeVerificationModal"
    tabindex="-1"
    aria-labelledby="financeVerificationTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content finance-modal">

            <form method="POST"
                id="finance-verification-form">

                @csrf
                @method('PATCH')

                <input type="hidden"
                    name="status"
                    id="finance-verification-status">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="financeVerificationTitle">

                        Verifikasi Pembayaran

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="text-center mb-4">

                        <div id="finance-verification-icon"
                            class="finance-verification-icon">
                        </div>

                        <h5 class="fw-bold"
                            id="finance-verification-heading">
                        </h5>

                        <p id="finance-verification-message"
                            class="finance-muted small">
                        </p>

                    </div>

                    <div class="finance-modal-summary mb-3">

                        <small class="finance-muted">
                            Pasien
                        </small>

                        <div class="fw-bold text-break"
                            id="finance-verification-patient">
                        </div>

                        <small class="finance-muted"
                            id="finance-verification-code">
                        </small>

                        <div class="finance-amount fs-4 mt-2"
                            id="finance-verification-amount">
                        </div>

                    </div>

                    <label for="finance-admin-note"
                        class="form-label fw-semibold"
                        id="finance-note-label">

                        Catatan Admin

                    </label>

                    <textarea name="admin_note"
                        id="finance-admin-note"
                        class="form-control finance-input"
                        rows="3"
                        maxlength="1000"></textarea>

                    <small class="finance-muted d-block mt-2"
                        id="finance-note-help">
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-light"
                        id="finance-verification-back">

                        Kembali

                    </button>

                    <button type="submit"
                        class="btn"
                        id="finance-verification-submit"
                        disabled>

                        Konfirmasi

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
