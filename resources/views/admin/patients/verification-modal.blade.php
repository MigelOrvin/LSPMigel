
<div class="modal fade"
    id="verificationModal"
    tabindex="-1"
    aria-labelledby="verification-title"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content patient-modal">

            <form method="POST" id="verification-form">

                @csrf
                @method('PATCH')

                <input type="hidden"
                    name="status"
                    id="verification-status">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="verification-title">
                        Konfirmasi Verifikasi
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body text-center py-4">

                    <div class="verification-icon"
                        id="verification-icon">

                        <i class="bi bi-person-check"></i>

                    </div>

                    <h5 class="fw-bold mt-3"
                        id="verification-heading">
                    </h5>

                    <p class="admin-muted mb-0"
                        id="verification-message">
                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn"
                        id="verification-submit">
                        Konfirmasi
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
