<?php
session_start();

// Check if section data exists in session
if (!isset($_SESSION['page_sections']) || empty($_SESSION['page_sections'])) {
    header('Location: ../index.php');
    exit;
}

// Use session data instead of static data
$data = $_SESSION['page_sections'];
$websiteName = $_SESSION['website_name'] ?? 'Website';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_changes'])) {
        // Create a copy of the original data structure
        $updatedData = $data;
        
        foreach ($data as $pageName => $pageData) {
            if (isset($_POST[$pageName])) {
                // Update section names and add new sections
                foreach ($_POST[$pageName] as $index => $sectionName) {
                    if (!empty($sectionName)) {
                        // Add new section or update existing one
                        $updatedData[$pageName]['sections'][$index] = $sectionName;
                    }
                }
                
                // Update section order if provided
                if (isset($_POST[$pageName . '_order'])) {
                    $newOrder = explode(',', $_POST[$pageName . '_order']);
                    if (!empty($newOrder)) {
                        $newSections = [];
                        foreach ($newOrder as $index) {
                            if (isset($updatedData[$pageName]['sections'][$index])) {
                                $newSections[$index] = $updatedData[$pageName]['sections'][$index];
                            }
                        }
                        if (!empty($newSections)) {
                            $updatedData[$pageName]['sections'] = $newSections;
                        }
                    }
                }
            }
        }
        
        // Update the session with modified data
        $_SESSION['page_sections'] = $updatedData;
        $data = $updatedData;
        
        $successMessage = "Changes saved successfully!";
    }
}

// Determine which page to display
$pages = array_keys($data);
$currentPage = isset($_GET['page']) ? $_GET['page'] : $pages[0];
if (!isset($data[$currentPage])) {
    $currentPage = $pages[0];
}

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// If it's an AJAX request, return only the page content
if ($isAjax && isset($_GET['page'])) {
    $pageData = $data[$currentPage];
    ob_start();
    include_once('../includes/page_content_template.php');
    $content = ob_get_clean();
    echo json_encode(['content' => $content, 'page' => $currentPage]);
    exit;
}

// Include header
include_once('../includes/header.php');
?>

<div class="container mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Page Section Editor - <?php echo htmlspecialchars($websiteName); ?></h1>
    
    <?php if (isset($successMessage)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <p><?php echo $successMessage; ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Page Navigation -->
    <div class="flex justify-center mb-8">
        <div class="inline-flex rounded-md shadow-sm" role="group">
            <?php foreach ($pages as $pageName): ?>
                <a href="?page=<?php echo $pageName; ?>" 
                   class="page-nav-link px-4 py-2 text-sm font-medium <?php echo $currentPage === $pageName 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-white text-gray-700 hover:bg-gray-100'; ?> 
                        <?php echo $pageName === $pages[0] ? 'rounded-l-lg' : ''; ?>
                        <?php echo $pageName === $pages[count($pages)-1] ? 'rounded-r-lg' : ''; ?>
                        border border-gray-200"
                   data-page="<?php echo $pageName; ?>">
                    <?php echo ucfirst($pageName); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <form method="post" action="" class="space-y-8" id="page-form">
        <div class="max-w-2xl mx-auto" id="page-content">
            <?php 
            $pageData = $data[$currentPage];
            include_once('../includes/page_content_template.php');
            ?>
            
            <!-- Hidden inputs for other pages to maintain their data -->
            <?php foreach ($data as $pageName => $pageData): ?>
                <?php if ($pageName !== $currentPage): ?>
                    <?php foreach ($pageData['sections'] as $index => $section): ?>
                        <input type="hidden" name="<?php echo $pageName; ?>[<?php echo $index; ?>]" value="<?php echo $section; ?>">
                    <?php endforeach; ?>
                    <input type="hidden" name="<?php echo $pageName; ?>_order" value="<?php echo implode(',', array_keys($pageData['sections'])); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <div class="flex justify-center space-x-4">
            <button type="submit" name="save_changes" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300" id="save-changes-btn">
                Save Changes
            </button>
            <a href="generatePage.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300" id="generate-page-btn">
                Generate Pages
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Sortable for the current page's section list
    initSortable('<?php echo $currentPage; ?>');
    
    // Add AJAX navigation for page links
    document.querySelectorAll('.page-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Save current page changes to local storage before navigating
            saveToLocalStorage();
            const page = this.getAttribute('data-page');
            loadPageContent(page);
        });
    });
    
    // Save to local storage when form is submitted
    document.getElementById('save-changes-btn').addEventListener('click', function() {
        // Form will be submitted normally, but also save to local storage
        saveToLocalStorage();
    });
    
    // Save data to local storage before redirecting to generate page
    document.getElementById('generate-page-btn').addEventListener('click', function(e) {
        e.preventDefault();
        saveToLocalStorage();
        window.location.href = this.getAttribute('href');
    });
    
    // Add event listener for the "Add Section" button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-section-btn')) {
            const btn = e.target.closest('.add-section-btn');
            const pageName = btn.getAttribute('data-page');
            addNewSection(pageName);
        }
    });
    
    function addNewSection(pageName) {
        const sectionsList = document.getElementById('sections-' + pageName);
        if (!sectionsList) return;
        
        // Find the highest index currently in use
        const items = sectionsList.querySelectorAll('li');
        let maxIndex = -1;
        items.forEach(item => {
            const index = parseInt(item.dataset.index);
            if (!isNaN(index) && index > maxIndex) {
                maxIndex = index;
            }
        });
        
        // Create a new index
        const newIndex = maxIndex + 1;
        
        // Create a new section element
        const newSection = document.createElement('li');
        newSection.className = 'flex items-center border border-gray-300 rounded-md bg-gray-50 p-3';
        newSection.dataset.index = newIndex;
        
        newSection.innerHTML = `
            <div class="cursor-move mr-3 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                    <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
            </div>
            <input type="text" name="${pageName}[${newIndex}]" value="new_section_${pageName}" class="flex-grow px-3 py-2 border border-gray-300 rounded-md">
        `;
        
        // Add the new section to the list
        sectionsList.appendChild(newSection);
        
        // Update the order input
        updateSectionOrder(pageName);
        
        // Save to local storage
        saveToLocalStorage();
    }
    
    function saveToLocalStorage() {
        const formData = new FormData(document.getElementById('page-form'));
        const data = {};
        
        // Process all pages
        <?php foreach ($pages as $pageName): ?>
        data['<?php echo $pageName; ?>'] = {
            sections: {},
            order: formData.get('<?php echo $pageName; ?>_order') || ''
        };
        
        // Get all section inputs for this page - use a unique variable name for each page
        const sectionInputs_<?php echo $pageName; ?> = document.querySelectorAll('input[name^="<?php echo $pageName; ?>["]');
        sectionInputs_<?php echo $pageName; ?>.forEach(input => {
            const match = input.name.match(/\[(\d+)\]/);
            if (match) {
                const index = match[1];
                data['<?php echo $pageName; ?>'].sections[index] = input.value;
            }
        });
        <?php endforeach; ?>
        
        // Save to local storage
        localStorage.setItem('webgenx_page_sections', JSON.stringify(data));
        console.log('Saved page section data to local storage');
    }
    
    function loadPageContent(page) {
        // Show loading indicator
        document.getElementById('page-content').innerHTML = '<div class="text-center py-10"><div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div><p class="mt-2">Loading...</p></div>';
        
        // Make AJAX request
        fetch('?page=' + page, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update page content
            document.getElementById('page-content').innerHTML = data.content;
            
            // Update active navigation link
            document.querySelectorAll('.page-nav-link').forEach(link => {
                if (link.getAttribute('data-page') === data.page) {
                    link.classList.add('bg-blue-600', 'text-white');
                    link.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100');
                } else {
                    link.classList.remove('bg-blue-600', 'text-white');
                    link.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-100');
                }
            });
            
            // Re-initialize sortable
            initSortable(data.page);
            
            // Update URL without reloading the page
            history.pushState({page: data.page}, '', '?page=' + data.page);
        })
        .catch(error => {
            console.error('Error loading page content:', error);
            document.getElementById('page-content').innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert"><p>Error loading page content. Please try again.</p></div>';
        });
    }
    
    function initSortable(pageName) {
        const sectionsList = document.getElementById('sections-' + pageName);
        if (sectionsList) {
            new Sortable(sectionsList, {
                animation: 150,
                ghostClass: 'bg-blue-100',
                handle: '.cursor-move',
                onEnd: function() {
                    updateSectionOrder(pageName);
                }
            });
            
            // Initialize the order input
            updateSectionOrder(pageName);
        }
    }
    
    function updateSectionOrder(pageName) {
        const list = document.getElementById('sections-' + pageName);
        if (!list) return;
        
        const items = list.querySelectorAll('li');
        const orderInput = document.getElementById(pageName + '-order');
        
        if (items.length > 0 && orderInput) {
            const order = Array.from(items).map(item => item.dataset.index);
            orderInput.value = order.join(',');
        }
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.page) {
            loadPageContent(event.state.page);
        }
    });
    
    // Initialize history state
    history.replaceState({page: '<?php echo $currentPage; ?>'}, '', '?page=<?php echo $currentPage; ?>');
});
</script>

<?php
// Include footer
include_once('../includes/footer.php');
?>