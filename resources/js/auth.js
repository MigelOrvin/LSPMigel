document.querySelectorAll(".password-toggle").forEach((button) => {
    button.addEventListener("click", () => {
        const input = document.getElementById(button.dataset.target);
        const icon = button.querySelector("i");

        if (!input || !icon) return;

        const isHidden = input.type === "password";

        input.type = isHidden ? "text" : "password";

        icon.classList.toggle("bi-eye", !isHidden);
        icon.classList.toggle("bi-eye-slash", isHidden);

        button.setAttribute(
            "aria-label",
            isHidden ? "Sembunyikan password" : "Tampilkan password",
        );
    });
});
// BIKIN MERAH KONFIRMASI DAN PASS KALO BEDA
const password = document.getElementById("password");
const confirmation = document.getElementById("password_confirmation");
const buttonRegist = document.getElementById("register-btn")
function validatePassword() {
    if (password.value !== confirmation.value && confirmation.value != "") {
        password.classList.add("is-invalid");
        confirmation.classList.add("is-invalid");
        buttonRegist.disabled = true
    } else {
        password.classList.remove("is-invalid");
        confirmation.classList.remove("is-invalid");
        buttonRegist.disabled = false
    }
}

if (password && confirmation) {
    password.addEventListener("input", validatePassword);
    confirmation.addEventListener("input", validatePassword);
}
