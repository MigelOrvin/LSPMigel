
const bookingCountdowns = document.querySelectorAll(
    "[data-booking-countdown]"
);

if (bookingCountdowns.length > 0) {

    let reloadScheduled = false;

    bookingCountdowns.forEach(countdown => {

        const expiresAt = Number(
            countdown.dataset.expiresAt
        );

        const serverTime = Number(
            countdown.dataset.serverTime
        );

        const duration = Number(
            countdown.dataset.duration
        );

        const value = countdown.querySelector(
            "[data-countdown-value]"
        );

        const bar = countdown.querySelector(
            "[data-countdown-bar]"
        );

        const message = countdown.querySelector(
            "[data-countdown-message]"
        );

        const card = countdown.closest(
            ".patient-booking-card"
        );

        const payButton = card?.querySelector(
            "[data-booking-pay-button]"
        );

        const startedAt = performance.now();

        let interval = null;
        let expired = false;

        function getRemainingTime() {

            const elapsed =
                performance.now() - startedAt;

            return Math.max(
                0,
                expiresAt - (serverTime + elapsed)
            );
        }

        function expireReservation() {

            if (expired) return;

            expired = true;

            clearInterval(interval);

            countdown.classList.add("expired");

            value.textContent = "00:00";

            bar.style.width = "0%";

            message.textContent =
                "Waktu pembayaran telah habis.";

            if (payButton) {

                payButton.removeAttribute("href");

                payButton.classList.add("disabled");

                payButton.setAttribute(
                    "aria-disabled",
                    "true"
                );

                payButton.setAttribute(
                    "tabindex",
                    "-1"
                );

                payButton.textContent =
                    "Waktu Habis";
            }

            // Refresh agar reservasi masuk ke riwayat
            if (!reloadScheduled) {

                reloadScheduled = true;

                setTimeout(() => {

                    window.location.reload();

                }, 2000);
            }
        }

        function updateCountdown() {

            if (expired) return;

            const remainingMs = getRemainingTime();

            const remaining = Math.ceil(
                remainingMs / 1000
            );

            const minutes = Math.floor(
                remaining / 60
            );

            const seconds = remaining % 60;

            value.textContent =
                `${String(minutes).padStart(2, "0")}:` +
                `${String(seconds).padStart(2, "0")}`;

            const percentage = Math.max(
                0,
                Math.min(
                    100,
                    remainingMs / (duration * 1000) * 100
                )
            );

            bar.style.width = `${percentage}%`;

            countdown.classList.toggle(
                "urgent",
                remaining <= 60
            );

            if (remainingMs <= 0) {

                expireReservation();
            }
        }

        if (payButton) {

            payButton.addEventListener(
                "click",
                event => {

                    if (
                        expired ||
                        getRemainingTime() <= 0
                    ) {

                        event.preventDefault();

                        expireReservation();
                    }
                }
            );
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
    });
}
