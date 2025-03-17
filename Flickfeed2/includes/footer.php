<!-- /Flickfeed2/includes/footer.php -->
<?php
/**
 * Footer Template
 *
 * This file contains the footer for the website.
 */
?>
<footer class="bg-dark text-gray-300 py-12">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Footer Section 1 -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-white">About Flickfeed2</h4>
                <p class="text-sm">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>

            <!-- Footer Section 2 -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                <ul class="text-sm">
                    <li><a href="../pages/index.php" class="hover:text-primary">Home</a></li>
                    <li><a href="../pages/about.php" class="hover:text-primary">About</a></li>
                    <li><a href="../pages/services.php" class="hover:text-primary">Services</a></li>
                    <li><a href="../pages/blog.php" class="hover:text-primary">Blog</a></li>
                    <li><a href="../pages/gallery.php" class="hover:text-primary">Gallery</a></li>
                    <li><a href="../pages/contact.php" class="hover:text-primary">Contact</a></li>
                </ul>
            </div>

            <!-- Footer Section 3 -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-white">Contact Us</h4>
                <p class="text-sm">Email: info@flickfeed2.com</p>
                <p class="text-sm">Phone: +1 123-456-7890</p>
                <p class="text-sm">Address: 123 Main Street, City, Country</p>
            </div>

            <!-- Footer Section 4 -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-white">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-primary"><i class="fab fa-facebook-square text-xl"></i>Facebook</a>
                    <a href="#" class="text-gray-300 hover:text-primary"><i class="fab fa-twitter-square text-xl"></i>Twitter</a>
                    <a href="#" class="text-gray-300 hover:text-primary"><i class="fab fa-linkedin text-xl"></i>LinkedIn</a>
                </div>
            </div>
        </div>

        <!-- Copyright Section -->
        <div class="mt-10 py-4 border-t border-gray-700">
            <p class="text-center text-sm">
                © <?php echo date('Y'); ?> Flickfeed2. All rights reserved.
            </p>
        </div>
    </div>
</footer>