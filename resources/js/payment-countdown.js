
const countdown = document.getElementById("payment-countdown");

if (countdown) {

    const expiresAt = Number(countdown.dataset.expiresAt);
    const serverTime = Number(countdown.dataset.serverTime);
    const duration = Number(countdown.dataset.duration);

    const redirectUrl = countdown.dataset.redirectUrl;

    const countdownValue = document.getElementById(
        "payment-countdown-value"
    );

    const countdownBar = document.getElementById(
        "payment-countdown-bar"
    );

    const countdownTitle = document.getElementById(
        "payment-countdown-title"
    );

    const countdownMessage = document.getElementById(
        "payment-countdown-message"
    );

    const form = document.getElementById("payment-form");

    const proofInput = document.getElementById("payment-proof");

    const uploadArea = document.getElementById(
        "payment-upload-area"
    );

    const submitButton = document.getElementById(
        "payment-submit"
    );

    const removeButton = document.getElementById(
        "remove-payment-proof"
    );

    const startedAt = performance.now();

    let interval = null;
    let expired = false;
    let submitting = false;

    function getRemainingTime() {

        const elapsed = performance.now() - startedAt;

        const estimatedServerTime = serverTime + elapsed;

        return Math.max(
            0,
            expiresAt - estimatedServerTime
        );
    }

    function expirePayment() {

        if (expired) return;

        expired = true;

        clearInterval(interval);

        countdown.classList.add("expired");

        countdownValue.textContent = "00:00";

        countdownBar.style.width = "0%";

        countdownTitle.textContent =
            "Waktu pembayaran habis";

        countdownMessage.textContent =
            "Reservasi telah kedaluwarsa. Silakan buat reservasi baru.";

        if (submitting) {

            countdownTitle.textContent =
                "Memeriksa pengiriman pembayaran";

            countdownMessage.textContent =
                "Silakan tunggu hasil konfirmasi dari server.";

            return;
        }

        if (proofInput) {
            proofInput.disabled = true;
        }

        if (submitButton) {

            submitButton.disabled = true;

            submitButton.textContent =
                "Waktu Pembayaran Habis";
        }

        if (removeButton) {
            removeButton.disabled = true;
        }

        if (uploadArea) {

            uploadArea.classList.add(
                "payment-upload-disabled"
            );
        }

        setTimeout(() => {

            window.location.replace(redirectUrl);

        }, 2500);
    }

    function updateCountdown() {

        const remainingMs = getRemainingTime();

        const remaining = Math.ceil(
            remainingMs / 1000
        );

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        countdownValue.textContent =
            `${String(minutes).padStart(2, "0")}:` +
            `${String(seconds).padStart(2, "0")}`;

        const percentage = Math.min(
            100,
            Math.max(
                0,
                remainingMs / (duration * 1000) * 100
            )
        );

        countdownBar.style.width =
            `${percentage}%`;

        countdown.classList.toggle(
            "urgent",
            remaining <= 60
        );

        if (remainingMs <= 0) {
            expirePayment();
        }
    }

    // Cegah upload setelah deadline
    if (form) {

        form.addEventListener("submit", event => {

            if (expired || getRemainingTime() <= 0) {

                event.preventDefault();
                event.stopImmediatePropagation();

                expirePayment();

                return;
            }

            // Tunggu validasi dari payment.js
            queueMicrotask(() => {

                if (!event.defaultPrevented) {
                    submitting = true;
                }

            });

        }, true);
    }

    if (uploadArea) {

        uploadArea.addEventListener("drop", event => {

            if (expired || getRemainingTime() <= 0) {

                event.preventDefault();
                event.stopImmediatePropagation();

                expirePayment();
            }

        }, true);
    }

    interval = setInterval(
        updateCountdown,
        250
    );

    updateCountdown();

    document.addEventListener(
        "visibilitychange",
        updateCountdown
    );
}
