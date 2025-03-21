<div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Generate</h1>
            <p class="text-xl text-gray-600">Create beautiful websites using multiple AI providers</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Generate Your Website</h2>

            <form action="" method="post" id="generatorForm">

                <div class="mb-4">
                    <label for="websiteName" class="block text-gray-700 font-medium mb-2">Color Scheme</label>
                    <input type="text" id="websiteName" name="websiteName" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Website Name" value="kaushal">
                    <span id="websiteNameError" class="text-red-500 text-sm hidden">Please enter Name for Website.</span>
                </div>

                <div class="mb-4">
                    <label for="websiteType" class="block text-gray-700 font-medium mb-2">Website Type</label>
                    <select id="websiteType" name="websiteType" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Website Type</option>
                        <option value="business">Business</option>
                        <option value="portfolio" selected>Portfolio</option>
                        <option value="blog">Blog</option>
                        <option value="ecommerce">E-Commerce</option>
                        <option value="personal">Personal</option>
                    </select>
                    <span id="websiteTypeError" class="text-red-500 text-sm hidden">Please select a website type.</span>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Website Description</label>
                    <textarea id="description" name="description" rows="4" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Describe your website in detail...">A sleek and professional personal portfolio website showcasing my skills, projects, and experience in web development, AI integration, design. The site features an engaging homepage, an about section, a portfolio of my best work, testimonials, and a contact page for collaboration opportunities. Designed for a clean, modern, and user-friendly experience</textarea>
                    <span id="descriptionError" class="text-red-500 text-sm hidden">Please enter a description.</span>
                </div>

                <div class="mb-4">
                    <label for="colorScheme" class="block text-gray-700 font-medium mb-2">Color Scheme</label>
                    <input type="text" id="colorScheme" name="colorScheme" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., Blue and white, Earth tones, etc." value="blue,black,purple">
                    <span id="colorSchemeError" class="text-red-500 text-sm hidden">Please enter a color scheme.</span>
                </div>

                <div class="mb-4">
                    <label for="typography" class="block text-gray-700 font-medium mb-2">Typography</label>
                    <select id="typography" name="typography" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Typography</option>
                        <option value="inter" selected>Inter</option>
                        <option value="roboto">Roboto</option>
                        <option value="opensans">Open Sans</option>
                        <option value="montserrat">Montserrat</option>
                        <option value="poppins">Poppins</option>
                        <option value="lato">Lato</option>
                    </select>
                    <span id="typographyError" class="text-red-500 text-sm hidden">Please select a typography.</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Pages to Include</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="pageHome" name="pages[]" value="Home" class="mr-2" checked>
                            <label for="pageHome">Home</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="pageAbout" name="pages[]" value="About" class="mr-2" checked>
                            <label for="pageAbout">About</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="pageServices" name="pages[]" value="Services" class="mr-2" checked>
                            <label for="pageServices">Services</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="pagePortfolio" name="pages[]" value="Portfolio" class="mr-2">
                            <label for="pagePortfolio">Portfolio</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="pageBlog" name="pages[]" value="Blog" class="mr-2">
                            <label for="pageBlog">Blog</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="pageContact" name="pages[]" value="Contact" class="mr-2">
                            <label for="pageContact">Contact</label>
                        </div>
                    </div>
                    <span id="pagesError" class="text-red-500 text-sm hidden">Please select at least one page.</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Custom Pages</label>
                    <div id="customPagesContainer">
                        <div class="flex mb-2">
                            <input type="text" name="customPages[]"
                                class="flex-grow px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter custom page name" value="works">
                            <button type="button"
                                class="ml-2 bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 remove-page hidden">Remove</button>
                        </div>
                    </div>
                    <button type="button" id="addCustomPage"
                        class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-plus mr-2"></i>Add Custom Page
                    </button>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit" value="1"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Generate
                        Website</button>
                </div>
            </form>
        </div>
    </div>