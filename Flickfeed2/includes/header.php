<!-- /Flickfeed2/includes/header.php -->
<?php
/**
 * Header Template
 *
 * This file contains the header for the website.
 */
?>
<header class="sticky top-0 bg-white shadow-md z-50">
    <nav class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="../pages/index.php" class="text-2xl font-bold text-dark">
                    Flickfeed3
                </a>
            </div>

            <!-- Mobile Menu Button (Hidden on larger screens) -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-gray-900 focus:outline-none focus:text-gray-900" aria-label="Open navigation">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2z"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links (Hidden on mobile) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="../pages/index.php" class="text-base text-gray-800 hover:text-primary">Home</a>
                <a href="../pages/about.php" class="text-base text-gray-800 hover:text-primary">About</a>
                <a href="../pages/services.php" class="text-base text-gray-800 hover:text-primary">Services</a>
                <a href="../pages/blog.php" class="text-base text-gray-800 hover:text-primary">Blog</a>
                <a href="../pages/gallery.php" class="text-base text-gray-800 hover:text-primary">Gallery</a>
                <a href="../pages/contact.php" class="text-base text-gray-800 hover:text-primary">Contact</a>
            </div>
        </div>

        <!-- Mobile Menu (Hidden initially) -->
        <div class="md:hidden hidden mt-4" id="mobile-menu">
            <a href="../pages/index.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">Home</a>
            <a href="../pages/about.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">About</a>
            <a href="../pages/services.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">Services</a>
            <a href="../pages/blog.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">Blog</a>
            <a href="../pages/gallery.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">Gallery</a>
            <a href="../pages/contact.php" class="block py-2 px-4 text-sm text-gray-800 hover:bg-gray-100">Contact</a>
        </div>
    </nav>
</header>