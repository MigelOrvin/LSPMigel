
/* ========================================
   ANNOUNCEMENT DELETE MODAL
======================================== */

const announcementDeleteModal = document.getElementById(
    "announcementDeleteModal"
);

if (announcementDeleteModal) {

    const deleteForm = document.getElementById(
        "announcement-delete-form"
    );

    const deleteTitle = document.getElementById(
        "announcement-delete-title"
    );

    const deleteSubmit = document.getElementById(
        "announcement-delete-submit"
    );

    let deleting = false;

    announcementDeleteModal.addEventListener(
        "show.bs.modal",
        event => {

            const trigger = event.relatedTarget;

            if (!trigger?.dataset.url) {

                event.preventDefault();
                return;
            }

            deleting = false;

            deleteForm.action = trigger.dataset.url;

            deleteTitle.textContent =
                trigger.dataset.title;

            deleteSubmit.disabled = false;

            deleteSubmit.textContent =
                "Hapus Pengumuman";
        }
    );

    announcementDeleteModal.addEventListener(
        "hidden.bs.modal",
        () => {

            deleteForm.removeAttribute("action");

            deleteTitle.textContent = "";

            deleteSubmit.disabled = true;
        }
    );

    deleteForm.addEventListener("submit", event => {

        if (
            deleting ||
            !deleteForm.getAttribute("action")
        ) {

            event.preventDefault();
            return;
        }

        deleting = true;

        deleteSubmit.disabled = true;

        deleteSubmit.textContent = "Menghapus...";
    });
}


/* ========================================
   CREATE & UPDATE FORM
======================================== */

const announcementForm = document.getElementById(
    "announcement-form"
);

if (announcementForm) {

    const content = document.getElementById(
        "announcement-content"
    );

    const characterCount = document.getElementById(
        "announcement-character-count"
    );

    const imageInput = document.getElementById(
        "announcement-image"
    );

    const preview = document.getElementById(
        "announcement-image-preview"
    );

    const previewImage = document.getElementById(
        "announcement-preview-img"
    );

    const removeImageButton = document.getElementById(
        "announcement-remove-image"
    );

    const removeImageInput = document.getElementById(
        "announcement-remove-image-input"
    );

    const imageError = document.getElementById(
        "announcement-image-error"
    );

    const submitButton = document.getElementById(
        "announcement-submit"
    );

    const existingImage = imageInput.dataset.existingImage;

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    const maxFileSize = 2 * 1024 * 1024;

    let previewUrl = null;
    let submitting = false;

    /* Character Counter */

    function updateCharacterCount() {

        characterCount.textContent =
            `${content.value.length.toLocaleString("id-ID")} / 10.000 karakter`;
    }

    content.addEventListener(
        "input",
        updateCharacterCount
    );

    updateCharacterCount();

    /* Image Preview */

    function releasePreviewUrl() {

        if (previewUrl) {

            URL.revokeObjectURL(previewUrl);

            previewUrl = null;
        }
    }

    function restorePreview() {

        releasePreviewUrl();

        if (
            existingImage &&
            removeImageInput.value !== "1"
        ) {

            previewImage.src = existingImage;

            preview.hidden = false;

        } else {

            previewImage.removeAttribute("src");

            preview.hidden = true;
        }
    }

    function showImageError(message) {

        imageInput.value = "";

        imageError.textContent = message;

        imageError.hidden = false;

        restorePreview();
    }

    imageInput.addEventListener("change", () => {

        const file = imageInput.files[0];

        imageError.hidden = true;

        if (!file) {

            restorePreview();
            return;
        }

        if (!allowedTypes.includes(file.type)) {

            showImageError(
                "Format gambar harus JPG, PNG, atau WEBP."
            );

            return;
        }

        if (file.size > maxFileSize) {

            showImageError(
                "Ukuran gambar maksimal 2 MB."
            );

            return;
        }

        releasePreviewUrl();

        previewUrl = URL.createObjectURL(file);

        previewImage.src = previewUrl;

        preview.hidden = false;

        removeImageInput.value = "0";
    });

    removeImageButton.addEventListener("click", () => {

        if (submitting) return;

        releasePreviewUrl();

        imageInput.value = "";

        removeImageInput.value = "1";

        previewImage.removeAttribute("src");

        preview.hidden = true;

        imageError.hidden = true;
    });

    /* Submit */

    announcementForm.addEventListener("submit", event => {

        if (submitting) {

            event.preventDefault();
            return;
        }

        submitting = true;

        submitButton.disabled = true;

        submitButton.textContent = "Menyimpan...";
    });
}
