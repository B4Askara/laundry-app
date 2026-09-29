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
    // SIDEBAR TOGGLE
    // ========================================

    const sidebarToggle = document.getElementById('sidebarToggle');
    const dashboardWrapper = document.querySelector('.dashboard-wrapper');

    if (sidebarToggle && dashboardWrapper) {

        sidebarToggle.addEventListener('click', function () {

            dashboardWrapper.classList.toggle('sidebar-collapsed');

        });

    }

});