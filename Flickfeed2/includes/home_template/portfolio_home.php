<!-- /Flickfeed2/includes/home_template/portfolio_home.php -->
<?php
/**
 * Portfolio Section - Home Page
 *
 * This file contains the portfolio section for the home page.
 */
?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-dark mb-4">
                Our Portfolio
            </h2>
            <p class="text-lg text-gray-700">
                Showcasing some of our best work and projects.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Portfolio Item 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <img src="https://placehold.co/600x400/000000/FFF " alt="Project 1" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-dark mb-2">Project Title 1</h3>
                    <p class="text-gray-600">
                        Short description of project 1. Highlight key features and technologies used.
                    </p>
                    <a href="../pages/gallery.php" class="inline-block mt-4 py-2 px-4 bg-primary text-white font-semibold rounded-md hover:bg-sky-600 transition-colors duration-200">
                        View Project
                    </a>
                </div>
            </div>

            <!-- Portfolio Item 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <img src="https://placehold.co/600x400/000000/FFF " alt="Project 2" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-dark mb-2">Project Title 2</h3>
                    <p class="text-gray-600">
                        Short description of project 2. Focus on the problem solved and results achieved.
                    </p>
                    <a href="../pages/gallery.php" class="inline-block mt-4 py-2 px-4 bg-primary text-white font-semibold rounded-md hover:bg-sky-600 transition-colors duration-200">
                        View Project
                    </a>
                </div>
            </div>

            <!-- Portfolio Item 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <img src="https://placehold.co/600x400/000000/FFF " alt="Project 3" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-dark mb-2">Project Title 3</h3>
                    <p class="text-gray-600">
                        Short description of project 3. Emphasize creativity and unique aspects of the project.
                    </p>
                    <a href="../pages/gallery.php" class="inline-block mt-4 py-2 px-4 bg-primary text-white font-semibold rounded-md hover:bg-sky-600 transition-colors duration-200">
                        View Project
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>