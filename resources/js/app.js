// resources/js/app.js
import "./bootstrap"; // Biarkan ini jika ada (biasanya untuk Echo, dll)
import { createApp } from "vue";

// Import komponen Vue utama untuk landing page customer
import CustomerLandingPage from "./components/CustomerLandingPage.vue";

// Buat aplikasi Vue dan mount ke elemen dengan id #app-customer
// Kita akan buat elemen ini nanti di file Blade
const app = createApp({});
app.component("customer-landing-page", CustomerLandingPage);
app.mount("#app-customer"); // Pastikan ID ini unik dan akan kita buat di Blade

// Import komponen kasir kita
import PosPage from "./components/PosPage.vue";

// Cari div #pos-app, jika ada, maka jalankan komponen PosPage
const posAppElement = document.getElementById("pos-app");
if (posAppElement) {
    createApp(PosPage).mount("#pos-app");
}

// Ini untuk mengatur sidebar dan top header
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const topHeader = document.getElementById("top-header");
    const sidebarToggleDesktop = document.getElementById(
        "sidebar-toggle-desktop"
    );
    const sidebarToggleMobile = document.getElementById(
        "sidebar-toggle-mobile"
    );

    // --- PENGATURAN ---
    const sidebarWidthExpanded = "190px";
    const sidebarWidthMinimized = "55px"; // Corresponds to w-20 icon wrapper
    const breakpointMd = 768;

    let isSidebarPermanentlyExpanded =
        localStorage.getItem("sidebarExpanded") !== "false";

    // --- FUNGSI UTAMA ---

    const setDesktopSidebarState = (expand) => {
        isSidebarPermanentlyExpanded = expand;
        localStorage.setItem(
            "sidebarExpanded",
            String(isSidebarPermanentlyExpanded)
        );

        if (expand) {
            sidebar.classList.remove("sidebar-minimized");
            sidebar.style.width = sidebarWidthExpanded;
            mainContent.style.marginLeft = sidebarWidthExpanded;
            topHeader.style.left = sidebarWidthExpanded;
        } else {
            sidebar.classList.add("sidebar-minimized");
            sidebar.style.width = sidebarWidthMinimized;
            mainContent.style.marginLeft = sidebarWidthMinimized;
            topHeader.style.left = sidebarWidthMinimized;
        }
    };

    const openSidebarMobile = () => {
        sidebar.classList.add("translate-x-0");
        sidebar.classList.remove("-translate-x-full");
        const overlay = document.createElement("div");
        overlay.id = "sidebar-overlay";
        // FIX: Use the dedicated CSS class for the overlay
        overlay.className = "sidebar-overlay md:hidden";
        document.body.appendChild(overlay);
        overlay.addEventListener("click", closeSidebarMobile);
    };

    const closeSidebarMobile = () => {
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0");
        const overlay = document.getElementById("sidebar-overlay");
        if (overlay) {
            overlay.remove();
        }
    };

    const handleResizeAndInit = () => {
        if (window.innerWidth >= breakpointMd) {
            closeSidebarMobile();
            sidebar.classList.remove("-translate-x-full");
            setDesktopSidebarState(isSidebarPermanentlyExpanded);
        } else {
            closeSidebarMobile();
            mainContent.style.marginLeft = "0";
            topHeader.style.left = "0";
            sidebar.style.width = sidebarWidthExpanded;
            sidebar.classList.remove("sidebar-minimized");
        }
    };

    // --- EVENT LISTENERS ---

    if (sidebarToggleDesktop) {
        sidebarToggleDesktop.addEventListener("click", () => {
            setDesktopSidebarState(!isSidebarPermanentlyExpanded);
        });
    }

    if (sidebar) {
        sidebar.addEventListener("mouseenter", () => {
            if (
                window.innerWidth >= breakpointMd &&
                !isSidebarPermanentlyExpanded
            ) {
                sidebar.style.width = sidebarWidthExpanded;
            }
        });
        sidebar.addEventListener("mouseleave", () => {
            if (
                window.innerWidth >= breakpointMd &&
                !isSidebarPermanentlyExpanded
            ) {
                sidebar.style.width = sidebarWidthMinimized;
            }
        });
    }

    if (sidebarToggleMobile) {
        sidebarToggleMobile.addEventListener("click", openSidebarMobile);
    }

    // --- INITIALIZATION ---
    handleResizeAndInit();
    window.addEventListener("resize", handleResizeAndInit);
});
