const verificationModal = document.getElementById(
    "verificationModal"
);

if (verificationModal) {

    const form = document.getElementById("verification-form");
    const status = document.getElementById("verification-status");
    const heading = document.getElementById("verification-heading");
    const message = document.getElementById("verification-message");
    const icon = document.getElementById("verification-icon");
    const submit = document.getElementById("verification-submit");

    verificationModal.addEventListener("show.bs.modal", event => {

        const button = event.relatedTarget;

        if (!button) return;

        const patientName = button.dataset.patient;
        const patientStatus = button.dataset.status;
        const currentStatus = button.dataset.currentStatus;
        const url = button.dataset.url;

        const isApproved = patientStatus === "approved";

        form.action = url;
        status.value = patientStatus;

        if (currentStatus === "rejected" && isApproved) {

            heading.textContent = "Pulihkan Akses Pasien?";

            message.textContent =
                `${patientName} akan kembali mendapatkan akses ke aplikasi.`;

            submit.textContent = "Pulihkan Akses";

        } else if (currentStatus === "approved" && !isApproved) {

            heading.textContent = "Cabut Akses Pasien?";

            message.textContent =
                `${patientName} tidak akan dapat mengakses aplikasi sampai disetujui kembali.`;

            submit.textContent = "Cabut Akses";

        } else {

            heading.textContent = isApproved
                ? "Setujui Pendaftaran?"
                : "Tolak Pendaftaran?";

            message.textContent = isApproved
                ? `${patientName} akan mendapatkan akses ke aplikasi.`
                : `${patientName} tidak akan mendapatkan akses ke aplikasi.`;

            submit.textContent = isApproved
                ? "Setujui"
                : "Tolak";
        }

        icon.className = isApproved
            ? "verification-icon verification-approved"
            : "verification-icon verification-rejected";

        icon.innerHTML = isApproved
            ? '<i class="bi bi-person-check"></i>'
            : '<i class="bi bi-person-x"></i>';

        submit.className = isApproved
            ? "btn btn-success"
            : "btn btn-danger";

        submit.disabled = false;
    });

    form.addEventListener("submit", () => {
        submit.disabled = true;
        submit.textContent = "Memproses...";
    });
}