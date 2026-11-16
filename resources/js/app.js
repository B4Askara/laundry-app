import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {

    // ========================================
    // PROFILE POPUP
    // ========================================

    const profileButton = document.getElementById('profileButton');
    const profilePopup = document.getElementById('profilePopup');

    if (profileButton && profilePopup) {

        profileButton.addEventListener('click', function (event) {
            event.stopPropagation();
            profilePopup.classList.toggle('show');
        });

        document.addEventListener('click', function (event) {
            if (
                !profilePopup.contains(event.target) &&
                !profileButton.contains(event.target)
            ) {
                profilePopup.classList.remove('show');
            }
        });

    }


    // ========================================
    // SIDEBAR DESKTOP TOGGLE
    // ========================================

    const sidebarToggle = document.getElementById('sidebarToggle');
    const dashboardWrapper = document.querySelector('.dashboard-wrapper');

    if (sidebarToggle && dashboardWrapper) {

        sidebarToggle.addEventListener('click', function () {
            dashboardWrapper.classList.toggle('sidebar-collapsed');
        });

    }


    // ========================================
    // SIDEBAR TABLET DAN HP
    // ========================================

    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebarClose = document.getElementById('mobileSidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebar = document.querySelector('.sidebar');

    function openMobileSidebar() {
        if (!dashboardWrapper) return;

        dashboardWrapper.classList.add('mobile-sidebar-open');

        if (mobileSidebarToggle) {
            mobileSidebarToggle.setAttribute('aria-expanded', 'true');
        }
    }

    function closeMobileSidebar() {
        if (!dashboardWrapper) return;

        dashboardWrapper.classList.remove('mobile-sidebar-open');

        if (mobileSidebarToggle) {
            mobileSidebarToggle.setAttribute('aria-expanded', 'false');
        }
    }


    // Tombol garis tiga di topbar untuk membuka sidebar
    if (mobileSidebarToggle && dashboardWrapper) {

        mobileSidebarToggle.addEventListener('click', function () {
            const isOpen = dashboardWrapper.classList.contains('mobile-sidebar-open');

            if (isOpen) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        });

    }


    // Tombol garis tiga di dalam sidebar untuk menutup sidebar
    if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', closeMobileSidebar);
    }


    // Klik area gelap untuk menutup sidebar
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }


    // Tutup sidebar setelah memilih menu
    if (sidebar) {
        sidebar.querySelectorAll('a.sidebar-item').forEach(function (link) {
            link.addEventListener('click', closeMobileSidebar);
        });
    }


    // ========================================
    // KEYBOARD ESCAPE
    // ========================================

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileSidebar();

            if (profilePopup) {
                profilePopup.classList.remove('show');
            }
        }
    });


    // ========================================
    // RESPONSIVE RESIZE
    // ========================================

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            closeMobileSidebar();
        }
    });

});