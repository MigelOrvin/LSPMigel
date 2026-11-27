
import { Modal } from "bootstrap";

const financeDetailModal = document.getElementById(
    "financeDetailModal"
);

const financeVerificationModal = document.getElementById(
    "financeVerificationModal"
);

if (financeDetailModal && financeVerificationModal) {

    const detailInstance = Modal.getOrCreateInstance(
        financeDetailModal
    );

    const verificationInstance = Modal.getOrCreateInstance(
        financeVerificationModal
    );

    const proofImage = document.getElementById(
        "finance-proof-image"
    );

    const proofMessage = document.getElementById(
        "finance-proof-message"
    );

    const proofLink = document.getElementById(
        "finance-proof-link"
    );

    const reviewActions = document.getElementById(
        "finance-review-actions"
    );

    const reviewNotice = document.getElementById(
        "finance-review-notice"
    );

    const approveTrigger = document.getElementById(
        "finance-approve-trigger"
    );

    const rejectTrigger = document.getElementById(
        "finance-reject-trigger"
    );

    const form = document.getElementById(
        "finance-verification-form"
    );

    const statusInput = document.getElementById(
        "finance-verification-status"
    );

    const note = document.getElementById(
        "finance-admin-note"
    );

    const noteLabel = document.getElementById(
        "finance-note-label"
    );

    const noteHelp = document.getElementById(
        "finance-note-help"
    );

    const submitButton = document.getElementById(
        "finance-verification-submit"
    );

    let currentPayment = null;
    let isSubmitting = false;
    let proofLoaded = false;

    /* ========================================
       DETAIL MODAL
    ======================================== */

    function renderPayment() {

        if (!currentPayment) return;

        const fields = [
            "code",
            "reservation",
            "patient",
            "email",
            "doctor",
            "date",
            "time",
            "amount",
            "note",
            "verifier"
        ];

        fields.forEach(field => {

            const element = document.getElementById(
                `finance-detail-${field}`
            );

            if (element) {

                element.textContent =
                    currentPayment[field] || "-";
            }
        });

        const statusLabel = document.getElementById(
            "finance-detail-status"
        );

        statusLabel.textContent =
            currentPayment.statusLabel;

        statusLabel.className =
            `finance-status finance-status-${currentPayment.status}`;

        const isPending =
            currentPayment.status === "pending";

        reviewActions.hidden = !isPending;
        reviewNotice.hidden = !isPending;

        approveTrigger.disabled = true;

        // Preview payment proof
        proofLoaded = false;

        proofImage.hidden = true;
        proofLink.hidden = true;

        proofMessage.hidden = false;

        proofMessage.textContent =
            "Memuat bukti pembayaran...";

        proofImage.onload = () => {

            proofLoaded = true;

            proofImage.hidden = false;
            proofMessage.hidden = true;
            proofLink.hidden = false;

            approveTrigger.disabled = !isPending;
        };

        proofImage.onerror = () => {

            proofLoaded = false;

            proofImage.hidden = true;
            proofLink.hidden = true;

            approveTrigger.disabled = true;

            proofMessage.hidden = false;

            proofMessage.textContent =
                "Bukti pembayaran tidak dapat dimuat.";
        };

        proofLink.href = currentPayment.proof;

        proofImage.src = currentPayment.proof;
    }

    financeDetailModal.addEventListener(
        "show.bs.modal",
        event => {

            if (event.relatedTarget) {

                currentPayment = {
                    ...event.relatedTarget.dataset
                };
            }

            if (!currentPayment) {

                event.preventDefault();
                return;
            }

            renderPayment();
        }
    );

    financeDetailModal.addEventListener(
        "hidden.bs.modal",
        () => {

            proofImage.onload = null;
            proofImage.onerror = null;

            proofImage.removeAttribute("src");
            proofLink.removeAttribute("href");

            proofLoaded = false;
        }
    );

    /* ========================================
       VERIFICATION MODAL
    ======================================== */

    function updateSubmitButton() {

        submitButton.disabled =
            isSubmitting ||
            !currentPayment ||
            !statusInput.value ||
            (
                statusInput.value === "rejected" &&
                !note.value.trim()
            );
    }

    function openVerification(status) {

        if (
            !currentPayment ||
            currentPayment.status !== "pending" ||
            !["approved", "rejected"].includes(status)
        ) return;

        if (status === "approved" && !proofLoaded) {
            return;
        }

        const isApproved = status === "approved";

        isSubmitting = false;

        form.action = currentPayment.url;

        statusInput.value = status;

        note.value = "";

        document.getElementById(
            "finance-verification-patient"
        ).textContent = currentPayment.patient;

        document.getElementById(
            "finance-verification-code"
        ).textContent = currentPayment.code;

        document.getElementById(
            "finance-verification-amount"
        ).textContent = currentPayment.amount;

        const icon = document.getElementById(
            "finance-verification-icon"
        );

        icon.className =
            `finance-verification-icon ${
                isApproved ? "approve" : "reject"
            }`;

        icon.innerHTML = isApproved
            ? '<i class="bi bi-check-circle"></i>'
            : '<i class="bi bi-x-circle"></i>';

        document.getElementById(
            "finance-verification-heading"
        ).textContent = isApproved
            ? "Setujui Pembayaran?"
            : "Tolak Pembayaran?";

        document.getElementById(
            "finance-verification-message"
        ).textContent = isApproved
            ? "Reservasi akan masuk ke tahap verifikasi jadwal."
            : "Reservasi akan dihentikan dan slot pemeriksaan kembali tersedia jika waktunya belum berlalu.";

        noteLabel.textContent = isApproved
            ? "Catatan Admin (opsional)"
            : "Alasan Penolakan (wajib)";

        noteHelp.textContent = isApproved
        ? "Tambahkan catatan jika diperlukan."
        : "Alasan penolakan akan disimpan pada riwayat reservasi pasien.";

        note.placeholder = isApproved
            ? "Masukkan catatan tambahan..."
            : "Masukkan alasan penolakan...";

        note.required = !isApproved;

        submitButton.className = isApproved
            ? "btn finance-approve-button"
            : "btn finance-reject-button";

        submitButton.textContent = isApproved
            ? "Setujui Pembayaran"
            : "Tolak Pembayaran";

        updateSubmitButton();

        // Tunggu modal pertama benar-benar tertutup
        financeDetailModal.addEventListener(
            "hidden.bs.modal",
            () => verificationInstance.show(),
            { once: true }
        );

        detailInstance.hide();
    }

    approveTrigger.addEventListener("click", () => {

        openVerification("approved");
    });

    rejectTrigger.addEventListener("click", () => {

        openVerification("rejected");
    });

    document.getElementById(
        "finance-verification-back"
    ).addEventListener("click", () => {

        if (isSubmitting) return;

        financeVerificationModal.addEventListener(
            "hidden.bs.modal",
            () => detailInstance.show(),
            { once: true }
        );

        verificationInstance.hide();
    });

    note.addEventListener(
        "input",
        updateSubmitButton
    );

    form.addEventListener("submit", event => {

        if (
            isSubmitting ||
            !currentPayment ||
            !["approved", "rejected"].includes(statusInput.value) ||
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
