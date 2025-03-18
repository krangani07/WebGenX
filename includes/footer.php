<script type="module" src="/qp/WebGenX/assets/js/main.js"></script>
</main>
    
    <!-- Footer -->
    <footer class="bg-white shadow-md mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-gray-600">&copy; 2023 WebGenX. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="https://github.com" class="text-gray-600 hover:text-blue-600 transition duration-300">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://twitter.com" class="text-gray-600 hover:text-blue-600 transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://linkedin.com" class="text-gray-600 hover:text-blue-600 transition duration-300">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
            <div class="mt-4 text-center">
                <nav class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="/qp/WebGenX/index.php" class="text-gray-600 hover:text-blue-600 transition duration-300">Dashboard</a>
                    <a href="/qp/WebGenX/pages/pageEditor.php" class="text-gray-600 hover:text-blue-600 transition duration-300">Page Editor</a>
                    <a href="/qp/WebGenX/pages/pageSectionEditor.php" class="text-gray-600 hover:text-blue-600 transition duration-300">Section Editor</a>
                    <a href="/qp/WebGenX/pages/templateManager.php" class="text-gray-600 hover:text-blue-600 transition duration-300">Templates</a>
                    <a href="/qp/WebGenX/pages/settings.php" class="text-gray-600 hover:text-blue-600 transition duration-300">Settings</a>
                </nav>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>