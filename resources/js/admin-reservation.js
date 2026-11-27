
/* ========================================
   APPOINTMENT DETAIL MODAL
======================================== */

const detailModal = document.getElementById(
    "appointmentDetailModal"
);

if (detailModal) {

    detailModal.addEventListener("show.bs.modal", event => {

        const button = event.relatedTarget;

        if (!button) return;

        const fields = [
            "reservation",
            "patient",
            "email",
            "doctor",
            "date",
            "time",
            "fee",
            "complaint",
            "status",
            "note",
            "verifier"
        ];

        fields.forEach(field => {

            const element = document.getElementById(
                `detail-${field}`
            );

            if (element) {

                element.textContent =
                    button.dataset[field] || "-";
            }
        });
    });
}


/* ========================================
   APPOINTMENT VERIFICATION MODAL
======================================== */

const verificationModal = document.getElementById(
    "appointmentVerificationModal"
);

if (verificationModal) {

    const form = document.getElementById(
        "appointment-verification-form"
    );

    const statusInput = document.getElementById(
        "appointment-verification-status"
    );

    const icon = document.getElementById(
        "appointment-verification-icon"
    );

    const heading = document.getElementById(
        "appointment-verification-heading"
    );

    const message = document.getElementById(
        "appointment-verification-message"
    );

    const patient = document.getElementById(
        "appointment-verification-patient"
    );

    const code = document.getElementById(
        "appointment-verification-code"
    );

    const note = document.getElementById(
        "appointment-admin-note"
    );

    const noteLabel = document.getElementById(
        "appointment-note-label"
    );

    const noteHelp = document.getElementById(
        "appointment-note-help"
    );

    const submitButton = document.getElementById(
        "appointment-verification-submit"
    );

    let isSubmitting = false;

    function updateSubmitButton() {

        submitButton.disabled =
            isSubmitting ||
            !statusInput.value ||
            (
                statusInput.value === "rejected" &&
                !note.value.trim()
            );
    }

    verificationModal.addEventListener(
        "show.bs.modal",
        event => {

            const button = event.relatedTarget;

            if (!button) return;

            const status = button.dataset.status;

            if (!["approved", "rejected"].includes(status)) {
                event.preventDefault();
                return;
            }

            isSubmitting = false;

            form.action = button.dataset.url;

            statusInput.value = status;

            patient.textContent = button.dataset.patient;

            code.textContent = button.dataset.code;

            note.value = "";

            if (status === "approved") {

                icon.className =
                    "appointment-verification-icon approve";

                icon.innerHTML =
                    '<i class="bi bi-calendar2-check"></i>';

                heading.textContent =
                    "Setujui Reservasi?";

                message.textContent =
                    "Pasien dapat melanjutkan proses pembayaran setelah reservasi disetujui.";

                noteLabel.textContent =
                    "Catatan Admin (opsional)";

                noteHelp.textContent =
                    "Tambahkan informasi jika diperlukan.";

                note.placeholder =
                    "Masukkan catatan tambahan...";

                note.required = false;

                submitButton.className =
                    "btn appointment-approve-button";

                submitButton.textContent =
                    "Setujui Reservasi";

            } else {

                icon.className =
                    "appointment-verification-icon reject";

                icon.innerHTML =
                    '<i class="bi bi-calendar2-x"></i>';

                heading.textContent =
                    "Tolak Reservasi?";

                message.textContent =
                    "Slot pemeriksaan akan kembali tersedia jika waktunya belum berlalu.";

                noteLabel.textContent =
                    "Alasan Penolakan (wajib)";

                noteHelp.textContent =
                    "Alasan penolakan akan ditampilkan kepada pasien.";

                note.placeholder =
                    "Masukkan alasan penolakan...";

                note.required = true;

                submitButton.className =
                    "btn appointment-reject-button";

                submitButton.textContent =
                    "Tolak Reservasi";
            }

            updateSubmitButton();
        }
    );

    note.addEventListener(
        "input",
        updateSubmitButton
    );

    form.addEventListener("submit", event => {

        if (
            isSubmitting ||
            !statusInput.value ||
            (
                statusInput.value === "rejected" &&
                !note.value.trim()
            )
        ) {

            event.preventDefault();
            return;
        }

        isSubmitting = true;

        submitButton.disabled = true;

        submitButton.textContent = "Memproses...";
    });
}
