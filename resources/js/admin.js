const adminSidebar = document.getElementById("admin-sidebar");
const sidebarToggle = document.getElementById("sidebar-toggle");
const sidebarOverlay = document.getElementById("sidebar-overlay");

if (adminSidebar && sidebarToggle && sidebarOverlay) {

    function setSidebar(open) {
        adminSidebar.classList.toggle("show", open);
        sidebarOverlay.classList.toggle("show", open);

        sidebarToggle.setAttribute("aria-expanded", String(open));
    }

    sidebarToggle.addEventListener("click", () => {
        setSidebar(!adminSidebar.classList.contains("show"));
    });

    sidebarOverlay.addEventListener("click", () => {
        setSidebar(false);
    });

    document.addEventListener("keydown", event => {
        if (event.key === "Escape") {
            setSidebar(false);
        }
    });

    window.matchMedia("(min-width: 992px)")
        .addEventListener("change", event => {
            if (event.matches) {
                setSidebar(false);
            }
        });
}