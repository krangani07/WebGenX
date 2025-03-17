// < !-- /Flickfeed2/assets / js / global.js-- >
    /**
     * Global JavaScript
     *
     * This file contains global JavaScript functions and event listeners.
     */

    document.addEventListener('DOMContentLoaded', function () {
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Add any other global scripts here (e.g., for animations, etc.)
    });