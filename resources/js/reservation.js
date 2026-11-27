
const reservationForm = document.getElementById("reservation-form");

if (reservationForm) {

    const dateInput = document.getElementById("reservation-date");
    const dayLabel = document.getElementById("reservation-day");
    const startInput = document.getElementById("reservation-start-time");
    const slotContainer = document.getElementById("slot-container");
    const slotMessage = document.getElementById("slot-message");
    const submitButton = document.getElementById("reservation-submit");
    const complaint = document.getElementById("complaint");

    const dateButtons = reservationForm.querySelectorAll(".date-button");

    const slotsUrl = reservationForm.dataset.slotsUrl;

    let requestController = null;
    let isSubmitting = false;

    // Mengatur status tombol submit
    function updateSubmitButton() {

        submitButton.disabled =
            isSubmitting ||
            !dateInput.value ||
            !startInput.value ||
            !complaint.value.trim();
    }

    // Memilih tanggal pemeriksaan
    function selectDate(button) {

        dateButtons.forEach(item => {

            item.classList.remove("selected");
            item.setAttribute("aria-pressed", "false");

        });

        button.classList.add("selected");
        button.setAttribute("aria-pressed", "true");

        dateInput.value = button.dataset.date;
    }

    // Memilih slot pemeriksaan
    function selectSlot(button, startTime) {

        slotContainer
            .querySelectorAll(".slot-button")
            .forEach(item => {

                item.classList.remove("selected");
                item.setAttribute("aria-pressed", "false");

            });

        button.classList.add("selected");
        button.setAttribute("aria-pressed", "true");

        startInput.value = startTime;

        updateSubmitButton();
    }

    // Mengambil slot melalui AJAX
    async function loadSlots(restoreOldValue = false) {

        if (requestController) {
            requestController.abort();
        }

        startInput.value = "";
        slotContainer.innerHTML = "";

        updateSubmitButton();

        const date = dateInput.value;

        if (!date) {

            dayLabel.textContent = "";

            slotMessage.textContent =
                "Pilih tanggal pemeriksaan terlebih dahulu.";

            slotMessage.hidden = false;

            return;
        }

        // Menampilkan tanggal lengkap
        dayLabel.textContent = new Date(
            date + "T12:00:00"
        ).toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        slotMessage.textContent = "Memuat jadwal...";
        slotMessage.hidden = false;

        const controller = new AbortController();

        requestController = controller;

        try {

            const url = new URL(
                slotsUrl,
                window.location.origin
            );

            url.searchParams.set("date", date);

            const response = await fetch(url, {

                signal: controller.signal,

                headers: {
                    Accept: "application/json"
                },

                cache: "no-store"
            });

            if (!response.ok) {
                throw new Error("Gagal mengambil jadwal.");
            }

            const data = await response.json();

            if (controller.signal.aborted) return;

            if (!data.slots.length) {

                slotMessage.textContent =
                    "Dokter tidak memiliki jadwal praktik pada tanggal tersebut.";

                slotMessage.hidden = false;

                updateDateAvailability(date, 0);

                return;
            }

            slotMessage.hidden = true;

            for (const slot of data.slots) {

                const button = document.createElement("button");

                button.type = "button";
                button.className = "slot-button";

                button.setAttribute("aria-pressed", "false");

                const time = document.createElement("strong");

                time.textContent =
                    `${slot.start_time.slice(0, 5)} - ${slot.end_time.slice(0, 5)}`;

                const status = document.createElement("small");

                status.textContent = slot.available
                    ? "Tersedia"
                    : slot.status === "expired"
                        ? "Waktu berlalu"
                        : "Sudah dipesan";

                button.append(time, status);

                if (!slot.available) {

                    button.disabled = true;
                    button.classList.add("unavailable");

                } else {

                    button.addEventListener("click", () => {

                        selectSlot(button, slot.start_time);

                    });
                }

                slotContainer.appendChild(button);

                // Mengembalikan pilihan sebelumnya jika validasi gagal
                if (
                    restoreOldValue &&
                    slot.available &&
                    slot.start_time === startInput.dataset.oldValue
                ) {

                    selectSlot(button, slot.start_time);

                }
            }

            // Menghitung jumlah slot yang masih tersedia
            const availableCount = data.slots.filter(
                slot => slot.available
            ).length;

            updateDateAvailability(date, availableCount);

            if (availableCount === 0) {

                slotMessage.textContent =
                    "Tidak ada slot tersedia. Silakan pilih tanggal lain.";

                slotMessage.hidden = false;
            }

        } catch (error) {

            if (
                error.name === "AbortError" ||
                controller.signal.aborted
            ) return;

            slotMessage.textContent =
                "Gagal memuat jadwal. Silakan coba kembali.";

            slotMessage.hidden = false;
        }
    }

    // Memperbarui jumlah slot pada date card
    function updateDateAvailability(date, availableCount) {

        const button = Array.from(dateButtons).find(
            item => item.dataset.date === date
        );

        if (!button) return;

        const availabilityLabel = button.querySelector(
            ".date-availability"
        );

        if (availabilityLabel) {

            availabilityLabel.textContent = availableCount > 0
                ? `${availableCount} slot`
                : "Tidak tersedia";
        }

        button.disabled = availableCount === 0;

        button.classList.toggle(
            "unavailable",
            availableCount === 0
        );

        if (availableCount === 0) {

            button.classList.remove("selected");
            button.setAttribute("aria-pressed", "false");

            if (dateInput.value === date) {

                dateInput.value = "";
                startInput.value = "";
                updateSubmitButton();

            }
        }
    }

    // Event ketika pasien memilih tanggal
    dateButtons.forEach(button => {

        button.addEventListener("click", () => {

            if (button.disabled || isSubmitting) return;

            selectDate(button);

            loadSlots();
        });
    });

    // Event ketika pasien mengisi keluhan
    complaint.addEventListener("input", updateSubmitButton);

    // Event submit reservasi
    reservationForm.addEventListener("submit", event => {

        if (
            isSubmitting ||
            !dateInput.value ||
            !startInput.value ||
            !complaint.value.trim()
        ) {

            event.preventDefault();

            return;
        }

        isSubmitting = true;

        submitButton.disabled = true;

        submitButton.textContent = "Memproses reservasi...";
    });

    // Mengambil pilihan tanggal sebelumnya
    const oldDate = dateInput.dataset.oldValue;

    const oldDateButton = Array.from(dateButtons).find(
        button =>
            button.dataset.date === oldDate &&
            !button.disabled
    );

    // Mencari tanggal terdekat yang masih tersedia
    const firstAvailableDate = Array.from(dateButtons).find(
        button => !button.disabled
    );

    const initialDate = oldDateButton || firstAvailableDate;

    // Otomatis memilih tanggal ketika halaman dibuka
    if (initialDate) {

        selectDate(initialDate);

        loadSlots(Boolean(oldDateButton));

    } else {

        slotMessage.textContent =
            "Tidak ada jadwal pemeriksaan yang tersedia.";

        slotMessage.hidden = false;
    }

    updateSubmitButton();
}
