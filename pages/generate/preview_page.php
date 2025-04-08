<?php
session_start();
include '../../includes/header.php';
require_once '../../config/config.php';

// Get the page to generate from URL parameter or session storage
$pageToGenerate = isset($_GET['generate']) ? $_GET['generate'] : null;
$generateAll = isset($_GET['generate_all']) && $_GET['generate_all'] === 'true';

// Get website name from session
$websiteName = $_SESSION['website_name'] ?? 'website';

// Get all available pages for the dropdown
$availablePages = [];
if (isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])) {
    $availablePages = array_keys($_SESSION['page_files']);
}

// Determine which page to preview
$pageToPreview = $pageToGenerate;
if (!$pageToPreview && isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])) {
    $pageToPreview = key($_SESSION['page_files']);
}
if ($generateAll && isset($_SESSION['page_files']) && !empty($_SESSION['page_files'])) {
    $pageToPreview = key($_SESSION['page_files']);
}

// Construct the URL to the generated page
$previewUrl = "/qp/WebGenX/tests/{$websiteName}/pages/{$pageToPreview}.php";
?>

<!-- Define JavaScript functions before they're used -->
<script>
// Function to change the preview page
function changePage(pageName) {
    window.location.href = 'preview_page.php?generate=' + encodeURIComponent(pageName);
}

// Function to load sections for the selected page
function loadSectionsForPage(pageName) {
    if (!pageName) return;
    
    // Show a loading indicator
    const sectionSelector = document.getElementById('sectionSelector');
    sectionSelector.innerHTML = '<option value="all" selected>All Sections</option>';
    
    // Get sections from session data
    const pageSections = <?php echo json_encode($_SESSION['page_files'] ?? []); ?>;
    
    if (pageSections && pageSections[pageName] && pageSections[pageName].section_files) {
        const sectionFiles = pageSections[pageName].section_files;
        
        // Add sections to the dropdown
        sectionFiles.forEach(section => {
            if (section.name && section.path) {
                const option = document.createElement('option');
                option.value = section.path;
                
                // Extract just the section name without the .php extension
                let displayName = section.name.replace('.php', '');
                // If the name has an underscore, take the part before it (section type)
                if (displayName.includes('_')) {
                    displayName = displayName.split('_')[0];
                }
                // Capitalize the first letter
                displayName = displayName.charAt(0).toUpperCase() + displayName.slice(1);
                
                option.textContent = displayName;
                sectionSelector.appendChild(option);
            }
        });
    }
    
    // Update the preview to show the newly selected page
    const iframe = document.getElementById('previewFrame');
    const websiteName = '<?php echo $websiteName; ?>';
    iframe.src = `/qp/WebGenX/tests/${websiteName}/pages/${pageName}.php`;
    
    // Update the page title
    document.querySelector('h1').textContent = `Preview: ${pageName.charAt(0).toUpperCase() + pageName.slice(1)}`;
}

// Function to regenerate the entire page
function regenerateEntirePage() {
    const pageSelector = document.getElementById('pageSelector');
    const selectedPage = pageSelector.value;
    
    // If no page is selected, show an error
    if (!selectedPage) {
        alert('Please select a page to regenerate');
        return;
    }
    
    // Show loader for page regeneration
    document.getElementById('pageRegenerateLoader').classList.remove('hidden');
    document.getElementById('fullPageLoader').classList.remove('hidden');
    
    // Update regeneration text
    document.getElementById('regenerationText').textContent = 
        `Please wait while we regenerate the entire "${selectedPage}" page...`;
    
    // Make AJAX request to generate the page
    regeneratePage(selectedPage, 'all', 'pageRegenerateLoader');
}


// Function to regenerate just the selected section
function regenerateSelectedSection() {
    const pageSelector = document.getElementById('pageSelector');
    const sectionSelector = document.getElementById('sectionSelector');
    const selectedPage = pageSelector.value;
    const selectedSection = sectionSelector.value;
    
    // If no page is selected, show an error
    if (!selectedPage) {
        return;
    }
    
    // If "All Sections" is selected, prompt the user to use the Regenerate Page button instead
    if (selectedSection === 'all') {
        return;
    }
    
    // Show loader for section regeneration
    document.getElementById('sectionRegenerateLoader').classList.remove('hidden');
    document.getElementById('fullPageLoader').classList.remove('hidden');
    
    // Get section display name for the message
    const sectionDisplayName = sectionSelector.options[sectionSelector.selectedIndex].text;
    
    // Update regeneration text
    document.getElementById('regenerationText').textContent = 
        `Please wait while we regenerate the "${sectionDisplayName}" section of the "${selectedPage}" page...`;
    
    // Extract section name from the path for API request
    const sectionPathParts = selectedSection.split('/');
    const sectionFileName = sectionPathParts[sectionPathParts.length - 1];
    const sectionName = sectionFileName.replace('.php', '');
    
    // Get session data for reference
    const sessionData = <?php echo json_encode([
        'websiteName' => $_SESSION['website_name'] ?? 'Default Website',
        'websiteType' => $_SESSION['website_type'] ?? 'Default Type',
        'description' => $_SESSION['description'] ?? 'Default Description',
        'colorScheme' => $_SESSION['color_scheme'] ?? 'Default Color Scheme',
        'typography' => $_SESSION['typography'] ?? 'Default Typography',
        'pageFiles' => $_SESSION['page_files'] ?? []
    ]); ?>;
    
    // Create an object with all the required variables for subPrompt
    const subPromptData = {
        // Basic Component Information
        componentType: 'section',
        componentPurpose: `${sectionDisplayName} section for ${selectedPage} page`,
        designStyle: sessionData.websiteType || 'Modern, responsive, with subtle animations',
        
        // Output Information
        outputPath: selectedSection,
        website_name: sessionData.websiteName,
        
        // Optional Additional Information
        description: `Regenerate the ${sectionDisplayName} section for the ${selectedPage} page. 
                     Website description: ${sessionData.description}`,
        websiteType: sessionData.websiteType,
        colorScheme: sessionData.colorScheme,
        typography: sessionData.typography,
        saveCode: true,
        
        // Additional technical details
        pageContext: selectedPage,
        sectionName: sectionName,
        sectionDisplayName: sectionDisplayName,
        
        // Page structure information if available
        pageStructure: sessionData.pageFiles[selectedPage] || {},
        
        // Reference to other sections in the page
        otherSections: sessionData.pageFiles[selectedPage]?.section_files || []
    };
    
    // Log the subPrompt data to console
    console.log('SubPrompt Required Variables:', subPromptData);
    
    // Make AJAX request to generate the section using subPrompt
    fetch('../generate/generate_section.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subPromptData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the iframe to show the updated section
            const iframe = document.getElementById('previewFrame');
            iframe.src = iframe.src;
            
            // Hide loader after a short delay to ensure the iframe has time to reload
            setTimeout(() => {
                document.getElementById('sectionRegenerateLoader').classList.add('hidden');
                document.getElementById('fullPageLoader').classList.add('hidden');
            }, 1000);
        } else {
            document.getElementById('sectionRegenerateLoader').classList.add('hidden');
            document.getElementById('fullPageLoader').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('sectionRegenerateLoader').classList.add('hidden');
        document.getElementById('fullPageLoader').classList.add('hidden');
    });
}

// Function to regenerate the current page or section
function regeneratePage(pageName, sectionName = 'all', loaderElementId = 'pageRegenerateLoader') {
    // Make AJAX request to generate the page
    fetch('../generate/generate_page.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            page: pageName,
            section: sectionName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the iframe to show the updated page
            const iframe = document.getElementById('previewFrame');
            iframe.src = iframe.src;
            
            // Hide loader after a short delay to ensure the iframe has time to reload
            setTimeout(() => {
                document.getElementById(loaderElementId).classList.add('hidden');
                document.getElementById('fullPageLoader').classList.add('hidden');
            }, 1000);
        } else {
            document.getElementById(loaderElementId).classList.add('hidden');
            document.getElementById('fullPageLoader').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById(loaderElementId).classList.add('hidden');
        document.getElementById('fullPageLoader').classList.add('hidden');
    });
}

// Load sections for the initially selected page
document.addEventListener('DOMContentLoaded', function() {
    const pageSelector = document.getElementById('pageSelector');
    if (pageSelector.value) {
        loadSectionsForPage(pageSelector.value);
    }
    
    // If this page was loaded with generate=true, trigger page generation
    <?php if ($pageToGenerate): ?>
    regeneratePage('<?php echo $pageToGenerate; ?>', 'all', 'pageRegenerateLoader');
    <?php endif; ?>
    
    <?php if ($generateAll): ?>
    // No alert message here
    <?php endif; ?>
});
</script>

<!-- Main Content Area -->
<div class="container-fluid p-0 flex flex-row h-screen">
    <!-- Side Panel for Controls -->
    <div class="bg-gray-100 p-4 border-r border-gray-300 w-64 flex flex-col">
        <h1 class="text-xl font-bold mb-6">Preview: <?php echo ucfirst($pageToPreview); ?></h1>
        
        <div class="flex flex-col gap-3">
            <button onclick="window.location.href='../page_and_sections/page_editor.php?page=<?php echo $pageToPreview; ?>'" 
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-300 w-full text-left">
                <i class="fas fa-arrow-left mr-2"></i> Back to Editor
            </button>
            
            <button onclick="window.open('<?php echo $previewUrl; ?>', '_blank')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300 w-full text-left">
                <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
            </button>
            
            <div class="mt-4">
                <label for="pageSelector" class="block text-sm font-medium text-gray-700 mb-2">Select Page:</label>
                <select id="pageSelector" onchange="loadSectionsForPage(this.value)"
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled>Select a page</option>
                    <?php foreach ($availablePages as $page): ?>
                        <option value="<?php echo $page; ?>" <?php echo $page === $pageToPreview ? 'selected' : ''; ?>>
                            <?php echo ucfirst($page); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button onclick="regenerateEntirePage()" 
                        class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300 w-full text-left">
                    <i class="fas fa-sync-alt mr-2"></i> Regenerate Page
                    <span id="pageRegenerateLoader" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
            
            <div class="mt-4">
                <label for="sectionSelector" class="block text-sm font-medium text-gray-700 mb-2">Select Section:</label>
                <select id="sectionSelector" 
                        class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all" selected>All Sections</option>
                    <!-- Sections will be loaded dynamically -->
                </select>
                
                <button onclick="regenerateSelectedSection()" 
                        class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-300 w-full text-left">
                    <i class="fas fa-sync-alt mr-2"></i> Regenerate Section
                    <span id="sectionRegenerateLoader" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        </div>
        
        <div class="mt-auto text-xs text-gray-500">
            <p>Website: <?php echo htmlspecialchars($websiteName); ?></p>
        </div>
    </div>
    
    <!-- Preview Frame - Full Height -->
    <div class="flex-grow overflow-hidden">
        <iframe id="previewFrame" src="<?php echo $previewUrl; ?>" class="w-full h-full border-0"></iframe>
    </div>
</div>

<!-- Full-page Loader for Regeneration Process -->
<div id="fullPageLoader" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
        <i class="fas fa-sync-alt text-4xl text-blue-600 mb-4 animate-spin"></i>
        <h3 class="text-xl font-bold mb-4">Regenerating Page</h3>
        <p id="regenerationText" class="text-gray-700">Please wait while we regenerate the page...</p>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>