<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGenX - Website Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="/qp/WebGenX/index.php" class="text-2xl font-bold text-blue-600">WebGenX</a>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="/qp/WebGenX/index.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Dashboard</a>
                    <a href="/qp/WebGenX/pages/pageEditor.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Page Editor</a>
                    <a href="/qp/WebGenX/pages/pageSectionEditor.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Section Editor</a>
                    <a href="/qp/WebGenX/pages/templateManager.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Templates</a>
                    <a href="/qp/WebGenX/pages/settings.php" class="text-gray-700 hover:text-blue-600 transition duration-300">Settings</a>
                </nav>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-2">
                <a href="/qp/WebGenX/index.php" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">Dashboard</a>
                <a href="/qp/WebGenX/pages/pageEditor.php" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">Page Editor</a>
                <a href="/qp/WebGenX/pages/pageSectionEditor.php" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">Section Editor</a>
                <a href="/qp/WebGenX/pages/templateManager.php" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">Templates</a>
                <a href="/qp/WebGenX/pages/settings.php" class="block py-2 text-gray-700 hover:text-blue-600 transition duration-300">Settings</a>
            </div>
        </div>
    </header>
    
    <main class="flex-grow">