<?php
session_start();
include '../../includes/header.php';  // Updated path to correctly point to the includes directory
require_once '../../config/config.php';
$current_page = isset($_GET['page']) ? $_GET['page'] : (isset($_SESSION['page_files']) && !empty($_SESSION['page_files']) ? key($_SESSION['page_files']) : 'home');
?>
<!-- Meta tag for current page (for JavaScript) -->
<meta name="current-page" content="<?php echo htmlspecialchars($current_page); ?>">

<!-- Main Content Area -->
<div class="container mx-auto px-4 py-6">
    <!-- Navigation Bar with Progress Bar -->
    <!-- Navigation Bar with Progress Bar -->
    <div class="w-full bg-white rounded-lg shadow-sm mb-6 p-4">
        <div class="text-center font-mono text-gray-700 mb-2">Website Generation Progress</div>
        
        <!-- Progress Bar Container -->
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <?php
            // Calculate progress based on generated pages
            $totalPages = isset($_SESSION['page_files']) ? count($_SESSION['page_files']) : 0;
            $generatedPages = 0;
            
            if (isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])) {
                $websiteName = $_SESSION['website_name'] ?? 'website';
                
                foreach ($_SESSION['page_files'] as $page_key => $page_data) {
                    // Check if the page file exists and has content
                    $pagePath = "c:/xampp/htdocs/qp/WebGenX/tests/{$websiteName}/pages/{$page_key}.php";
                    
                    if (file_exists($pagePath) && filesize($pagePath) > 0) {
                        $generatedPages++;
                        // Update the session to mark this page as generated
                        $_SESSION['page_files'][$page_key]['generated'] = true;
                    } else {
                        // If file doesn't exist or is empty, mark as not generated
                        $_SESSION['page_files'][$page_key]['generated'] = false;
                    }
                }
            }
            
            $progressPercentage = $totalPages > 0 ? ($generatedPages / $totalPages) * 100 : 0;
            ?>
            <div id="progressBar" class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                 style="width: <?php echo $progressPercentage; ?>%"></div>
        </div>
        
        <!-- Progress Status Text -->
        <div id="progressStatus" class="text-center text-sm text-gray-600">
            <?php echo $generatedPages; ?> of <?php echo $totalPages; ?> pages generated
        </div>
        
        <!-- Generate All Pages Button -->
        <div class="mt-4 text-center">
            <button id="generateAllPagesBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300 inline-flex items-center justify-center">
                <i class="fas fa-cogs mr-2"></i> Generate All Pages
                <div id="generateAllLoader" class="hidden ml-2">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </button>
        </div>
        
        <!-- Full-page Loader for Generation Process -->
        <div id="fullPageLoader" class="fixed inset-0 bg-black bg-opacity-70 flex flex-col items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
                <i class="fas fa-cogs text-4xl text-blue-600 mb-4 animate-spin"></i>
                <h3 class="text-xl font-bold mb-4">Generating Website</h3>
                <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                    <div id="generationProgressBar" class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="generationProgressText" class="text-gray-700">Preparing to generate pages...</p>
                <p id="currentPageGenerating" class="text-sm text-gray-500 mt-2"></p>
            </div>
        </div>
    </div>

    <!-- Two-Column Layout -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Right Column - Content Area (Displayed first on mobile) -->
        <div class="w-full md:w-3/4 bg-white rounded-lg shadow-sm p-4 order-1 md:order-2">
            <!-- Tabs Navigation - Desktop -->
            <div class="hidden md:flex flex-wrap border-b border-gray-300 mb-6">
                <?php
                if (isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])):
                    foreach ($_SESSION['page_files'] as $page_key => $page_data):
                        ?>
                        <div class="font-mono text-gray-700 border border-gray-300 px-4 py-2 rounded-t mr-1 cursor-pointer hover:bg-gray-100 <?php echo ($page_key === $current_page) ? 'bg-blue-50 border-b-0' : ''; ?>"
                            data-page="<?php echo $page_key; ?>"
                            onclick="window.location.href='?page=<?php echo $page_key; ?>'">
                            <?php echo ucfirst($page_key); ?>
                        </div>
                        <?php
                    endforeach;
                else:
                    ?>
                    <div class="font-mono text-gray-700 border border-gray-300 px-4 py-2 rounded-t mr-1 cursor-pointer hover:bg-gray-100 bg-blue-50 border-b-0"
                        data-page="home" onclick="window.location.href='?page=home'">
                        Home
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tabs Navigation - Mobile Dropdown -->
            <div class="md:hidden mb-6">
                <select
                    class="w-full border border-gray-300 rounded p-2 font-mono text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="window.location.href='?page='+this.value">
                    <?php foreach ($_SESSION['page_files'] as $page_key => $page_data): ?>
                        <option value="<?php echo $page_key; ?>" <?php echo $page_key === $current_page ? 'selected' : ''; ?>>
                            <?php echo ucfirst($page_key); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Section List -->
            <div class="space-y-4" id="sortable-sections">
                <?php
                $current_page = isset($_GET['page']) ? $_GET['page'] : (isset($_SESSION['page_files']) && !empty($_SESSION['page_files']) ? key($_SESSION['page_files']) : 'home');

                if (isset($_SESSION['page_files'][$current_page]['section_files']) && !empty($_SESSION['page_files'][$current_page]['section_files'])):
                    foreach ($_SESSION['page_files'][$current_page]['section_files'] as $section_index => $section):
                        ?>
                        <div class="border border-gray-300 rounded p-4 cursor-move hover:bg-gray-50"
                            data-section-index="<?php echo $section_index; ?>">
                            <div class="font-mono text-gray-700 text-center"><?php echo $section['name']; ?></div>
                            <div class="text-xs text-gray-500 text-center mt-2"><?php echo $section['path']; ?></div>
                            <div class="flex justify-center mt-3 space-x-2">
                                <button class="text-blue-600 hover:text-blue-800"
                                    onclick="editSection('<?php echo $section['path']; ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="text-red-600 hover:text-red-800"
                                    onclick="confirmDeleteSection('<?php echo $section_index; ?>', '<?php echo $section['name']; ?>')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else:
                    ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle mb-2 text-2xl"></i>
                        <p>No sections found for this page.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add Section Button and Generate Page Button -->
            <div class="mt-6 flex flex-col md:flex-row gap-4">
                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300 flex items-center justify-center flex-1"
                    onclick="openAddSectionModal('<?php echo $current_page; ?>')">
                    <i class="fas fa-plus mr-2"></i> Add New Section
                </button>
                <button id="generatePageBtn"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300 flex items-center justify-center flex-1"
                    onclick="generateAndPreview('<?php echo $current_page; ?>')">
                    <div id="generateLoader" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <span>Generate <?php echo ucfirst($current_page); ?></span>
                </button>
            </div>
        </div>

        <!-- Left Column - Folder Structure (Displayed last on mobile) -->
        <div class="w-full md:w-1/4 bg-white rounded-lg shadow-sm p-4 order-2 md:order-1 mt-6 md:mt-0">
            <div class="border border-gray-300 rounded p-4">
                <div class="font-mono text-gray-700 mb-3">Project Structure</div>
                <div id="folder-structure" class="text-sm">
                    <?php

                    function displayFolderStructure($data, $indent = 0)
                    {
                        $html = '<ul class="list-none" style="margin-left: ' . ($indent * 16) . 'px">';

                        if (is_array($data)) {
                            foreach ($data as $key => $value) {
                                if (is_array($value)) {
                                    // This is a directory
                                    $html .= '<li class="my-1">';
                                    $html .= '<div class="flex items-center cursor-pointer hover:bg-gray-100 py-1 px-2 rounded folder-toggle">';
                                    $html .= '<i class="fas fa-folder text-yellow-400 mr-2"></i>';
                                    $html .= '<span class="text-gray-700">' . htmlspecialchars($key) . '</span>';
                                    $html .= '</div>';
                                    $html .= displayFolderStructure($value, $indent + 1);
                                    $html .= '</li>';
                                } else {
                                    // This is a file
                                    $html .= '<li class="my-1">';
                                    $html .= '<div class="flex items-center py-1 px-2 rounded">';

                                    // Determine file icon based on extension
                                    $extension = pathinfo($key, PATHINFO_EXTENSION);
                                    $iconClass = 'fa-file';

                                    if ($extension === 'php') {
                                        $iconClass = 'fa-file-code';
                                    } else if ($extension === 'js') {
                                        $iconClass = 'fa-file-code';
                                    } else if ($extension === 'css') {
                                        $iconClass = 'fa-file-code';
                                    } else if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        $iconClass = 'fa-file-image';
                                    }

                                    $html .= '<i class="fas ' . $iconClass . ' text-gray-400 mr-2"></i>';
                                    $html .= '<span class="text-gray-700">' . htmlspecialchars($key) . '</span>';
                                    $html .= '</div>';
                                    $html .= '</li>';
                                }
                            }
                        }

                        $html .= '</ul>';
                        return $html;
                    }

                    // Convert the session data to a hierarchical structure
                    $websiteName = isset($_SESSION['website_name']) ? $_SESSION['website_name'] : 'website';
                    $folderStructure = [
                        $websiteName => [
                            'assets' => [
                                'css' => [
                                    'global.css' => ''
                                ],
                                'js' => [],
                                'images' => [],
                                'fonts' => []
                            ],
                            'includes' => [
                                'header.php' => '',
                                'footer.php' => ''
                            ],
                            'pages' => []
                        ]
                    ];

                    // Add pages and their sections
                    if (isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])) {
                        foreach ($_SESSION['page_files'] as $page_key => $page_data) {
                            // Add page file
                            $folderStructure[$websiteName]['pages'][$page_data['main_file']] = '';

                            // Add JS files
                            if (isset($page_data['js_files'])) {
                                foreach ($page_data['js_files'] as $js_file) {
                                    $folderStructure[$websiteName]['assets']['js'][$js_file] = '';
                                }
                            }

                            // Add template directories and section files
                            if (isset($page_data['section_files'])) {
                                foreach ($page_data['section_files'] as $section) {
                                    if (isset($section['path'])) {
                                        $parts = explode('/', $section['path']);
                                        if (count($parts) > 1) {
                                            $template_dir = $parts[0];
                                            $section_file = $parts[1];

                                            if (!isset($folderStructure[$websiteName]['includes'][$template_dir])) {
                                                $folderStructure[$websiteName]['includes'][$template_dir] = [];
                                            }

                                            $folderStructure[$websiteName]['includes'][$template_dir][$section_file] = '';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // If we have response data from the API, use that for a more complete structure
                    if (isset($_SESSION['response_data']) && !empty($_SESSION['response_data'])) {
                        $apiData = $_SESSION['response_data'];
                        if (is_array($apiData) && !empty($apiData)) {
                            $apiWebsiteName = array_key_first($apiData);
                            if (isset($apiData[$apiWebsiteName])) {
                                $folderStructure = $apiData;
                            }
                        }
                    }

                    echo displayFolderStructure($folderStructure);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Section Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h3 class="text-lg font-bold mb-4">Confirm Delete</h3>
        <p class="mb-6">Are you sure you want to delete the section "<span id="deleteSectonName"></span>"?</p>
        <div class="flex justify-end space-x-4">
            <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                Cancel
            </button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div id="addSectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h3 class="text-lg font-bold mb-4">Add New Section</h3>
        <form id="addSectionForm" action="../../includes/section_handler.php" method="post">
            <input type="hidden" id="pageName" name="page_name" value="">
            <input type="hidden" id="sectionType" name="section_type" value="custom">
            <div class="mb-4">
                <label for="sectionName" class="block text-gray-700 mb-2">Section Name</label>
                <div class="flex items-center">
                    <input type="text" id="sectionName" name="section_name"
                        class="flex-grow border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., hero" required pattern="[a-zA-Z_]+" title="Only letters and underscores allowed (no numbers)">
                    <span id="sectionSuffix" class="bg-gray-100 border border-gray-300 border-l-0 rounded-r px-3 py-2 text-gray-500">_index.php</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Only letters and underscores allowed (no numbers).</p>
                <p id="sectionNameError" class="text-red-500 text-sm mt-1 hidden">Section name can only contain letters and underscores (no numbers).</p>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelAddSection"
                    class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add Section
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include the external JavaScript file -->
<script src="../../assets/js/pages/page_editor.js"></script>
<?php include '../../includes/footer.php'; ?>

<script>
function generateAndPreview(pageName) {
    const generateBtn = document.getElementById('generatePageBtn');
    const loader = document.getElementById('generateLoader');
    
    generateBtn.disabled = true;
    loader.classList.remove('hidden');
    
    // Store the current page in session storage for the preview page to use
    sessionStorage.setItem('pageToGenerate', pageName);
    
    // Show full page loader
    document.getElementById('fullPageLoader').classList.remove('hidden');
    document.getElementById('generationProgressText').textContent = `Generating ${pageName} page...`;
    document.getElementById('generationProgressBar').style.width = '50%';
    
    // Redirect immediately to preview page with a parameter indicating to generate
    window.location.href = '../generate/preview_page.php?generate=' + encodeURIComponent(pageName);
}

// Generate All Pages functionality
document.getElementById('generateAllPagesBtn').addEventListener('click', function() {
    const generateAllBtn = document.getElementById('generateAllPagesBtn');
    const loader = document.getElementById('generateAllLoader');
    
    generateAllBtn.disabled = true;
    loader.classList.remove('hidden');
    
    // Get all page names from the tabs
    const pageElements = document.querySelectorAll('[data-page]');
    const pages = Array.from(pageElements).map(el => el.dataset.page);
    
    // Store pages to generate in session storage
    sessionStorage.setItem('pagesToGenerate', JSON.stringify(pages));
    
    // Show full page loader
    document.getElementById('fullPageLoader').classList.remove('hidden');
    document.getElementById('generationProgressText').textContent = 'Preparing to generate all pages...';
    document.getElementById('generationProgressBar').style.width = '10%';
    
    // Start the generation process
    generateAllPages(pages);
});

// Function to generate all pages sequentially
function generateAllPages(pages) {
    if (!pages || pages.length === 0) {
        // All pages generated, redirect to preview
        window.location.href = '../generate/preview_page.php?generate_all=true';
        return;
    }
    
    const totalPages = pages.length;
    const pagesLeft = [...pages]; // Create a copy to work with
    const totalPagesCount = pagesLeft.length;
    let completedPages = 0;
    
    function generateNextPage() {
        if (pagesLeft.length === 0) {
            // All pages generated, redirect to preview
            window.location.href = '../generate/preview_page.php?generate_all=true';
            return;
        }
        
        const currentPage = pagesLeft.shift();
        completedPages++;
        
        // Update progress UI
        const progressPercentage = (completedPages / totalPagesCount) * 100;
        document.getElementById('generationProgressBar').style.width = `${progressPercentage}%`;
        document.getElementById('generationProgressText').textContent = 
            `Generating page ${completedPages} of ${totalPagesCount}`;
        document.getElementById('currentPageGenerating').textContent = 
            `Current page: ${currentPage}`;
        
        // Make AJAX request to generate the page
        fetch('../generate/generate_page.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ page: currentPage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Continue with next page
                setTimeout(generateNextPage, 500); // Small delay between requests
            } else {
                alert(`Error generating page ${currentPage}: ${data.message}`);
                // Continue with next page despite error
                setTimeout(generateNextPage, 500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error generating page ${currentPage}: ${error.message}`);
            // Continue with next page despite error
            setTimeout(generateNextPage, 500);
        });
    }
    
    // Start the generation process
    generateNextPage();
}
</script>