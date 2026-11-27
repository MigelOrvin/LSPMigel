
const paymentForm = document.getElementById("payment-form");

if (paymentForm) {

    const proofInput = document.getElementById("payment-proof");
    const uploadArea = document.getElementById("payment-upload-area");
    const preview = document.getElementById("payment-preview");
    const previewImage = document.getElementById("payment-preview-image");
    const fileName = document.getElementById("payment-file-name");
    const fileSize = document.getElementById("payment-file-size");
    const fileError = document.getElementById("payment-file-error");
    const removeButton = document.getElementById("remove-payment-proof");
    const submitButton = document.getElementById("payment-submit");

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    const maxSize = 2 * 1024 * 1024;

    let previewUrl = null;
    let isSubmitting = false;
    let isValidFile = false;

    function clearPreview() {

        if (previewUrl) {

            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        previewImage.removeAttribute("src");
        preview.hidden = true;
        uploadArea.hidden = false;

        fileName.textContent = "";
        fileSize.textContent = "";

        isValidFile = false;
        submitButton.disabled = true;
    }

    function showError(message) {

        clearPreview();

        proofInput.value = "";

        fileError.textContent = message;
        fileError.hidden = false;
    }

    function validateFile(file) {

        if (!file) {

            clearPreview();
            return false;
        }

        if (!allowedTypes.includes(file.type)) {

            showError(
                "Format file harus JPG, PNG, atau WEBP."
            );

            return false;
        }

        if (file.size > maxSize) {

            showError(
                "Ukuran file maksimal 2 MB."
            );

            return false;
        }

        return true;
    }

    function showPreview(file) {

        if (!validateFile(file)) return;

        clearPreview();

        fileError.hidden = true;

        previewUrl = URL.createObjectURL(file);

        previewImage.src = previewUrl;

        fileName.textContent = file.name;

        fileSize.textContent =
            `${(file.size / 1024).toFixed(1)} KB`;

        preview.hidden = false;
        uploadArea.hidden = true;

        isValidFile = true;
        submitButton.disabled = false;
    }

    proofInput.addEventListener("change", () => {

        showPreview(proofInput.files[0]);
    });

    previewImage.addEventListener("error", () => {

        showError("Gambar tidak dapat dibaca.");
    });

    removeButton.addEventListener("click", () => {

        if (isSubmitting) return;

        proofInput.value = "";

        fileError.hidden = true;

        clearPreview();
    });

    /* Drag and Drop */

    ["dragenter", "dragover"].forEach(eventName => {

        uploadArea.addEventListener(eventName, event => {

            event.preventDefault();

            uploadArea.classList.add("dragging");
        });
    });

    ["dragleave", "drop"].forEach(eventName => {

        uploadArea.addEventListener(eventName, event => {

            event.preventDefault();

            uploadArea.classList.remove("dragging");
        });
    });

    uploadArea.addEventListener("drop", event => {

        if (isSubmitting) return;

        const file = event.dataTransfer.files[0];

        if (!file || !validateFile(file)) return;

        const transfer = new DataTransfer();

        transfer.items.add(file);

        proofInput.files = transfer.files;

        showPreview(file);
    });

    /* Submit */

    paymentForm.addEventListener("submit", event => {

        if (
            isSubmitting ||
            !isValidFile ||
            !proofInput.files.length
        ) {

            event.preventDefault();
            return;
        }

        isSubmitting = true;

        submitButton.disabled = true;
        removeButton.disabled = true;

        submitButton.textContent =
            "Mengirim pembayaran...";
    });
}


/* Copy Bank Account */

const copyAccountButton = document.getElementById(
    "copy-account-number"
);

if (copyAccountButton) {

    copyAccountButton.addEventListener("click", async () => {

        const account = document.getElementById(
            "payment-account-number"
        );

        const label = copyAccountButton.querySelector("span");

        try {

            await navigator.clipboard.writeText(
                account.textContent.trim()
            );

            label.textContent = "Tersalin";

            setTimeout(() => {

                label.textContent = "Salin";

            }, 2000);

        } catch (error) {

            label.textContent = "Salin manual";
        }
    });
}
