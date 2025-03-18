<?php
session_start();

// Check if section data exists in session
if (!isset($_SESSION['page_sections']) || empty($_SESSION['page_sections'])) {
    header('Location: ../index.php');
    exit;
}

$data = $_SESSION['page_sections'];
$websiteName = $_SESSION['website_name'] ?? 'Website';

// Process page generation if requested
$generatedPages = [];
$selectedPage = isset($_GET['page']) ? $_GET['page'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $selectedPage = $_POST['page'];
    
    if ($selectedPage === 'all') {
        // Generate all pages
        foreach (array_keys($data) as $pageName) {
            $generatedPages[$pageName] = true;
        }
        $successMessage = "Successfully generated all pages!";
    } elseif (isset($data[$selectedPage])) {
        // Generate single page
        $generatedPages[$selectedPage] = true;
        $successMessage = "Successfully generated the {$selectedPage} page!";
    }
}

include_once('../includes/header.php');
?>

<div class="container mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Generate Pages - <?php echo htmlspecialchars($websiteName); ?></h1>
    
    <?php if (isset($successMessage)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <p><?php echo $successMessage; ?></p>
        </div>
    <?php endif; ?>
    
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Select a Page to Generate</h2>
        
        <form method="post" action="" class="space-y-6">
            <div class="mb-4">
                <label for="page-select" class="block text-gray-700 text-sm font-bold mb-2">Page:</label>
                <select id="page-select" name="page" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">-- Select a page --</option>
                    <?php foreach (array_keys($data) as $pageName): ?>
                        <option value="<?php echo $pageName; ?>" <?php echo $selectedPage === $pageName ? 'selected' : ''; ?>>
                            <?php echo ucfirst($pageName); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="all" <?php echo $selectedPage === 'all' ? 'selected' : ''; ?>>All Pages</option>
                </select>
            </div>
            
            <div id="page-preview" class="mb-6">
                <?php if ($selectedPage && $selectedPage !== 'all' && isset($data[$selectedPage])): ?>
                    <h3 class="text-lg font-medium mb-2 text-gray-700">Page Preview: <?php echo ucfirst($selectedPage); ?></h3>
                    <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
                        <h4 class="font-medium mb-2">Sections:</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <?php foreach ($data[$selectedPage]['sections'] as $section): ?>
                                <li><?php echo htmlspecialchars($section); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif ($selectedPage === 'all'): ?>
                    <h3 class="text-lg font-medium mb-2 text-gray-700">All Pages Preview</h3>
                    <div class="border border-gray-300 rounded-md p-4 bg-gray-50">
                        <p>You've selected to generate all pages. This will create all pages with their current section configurations.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="flex justify-between">
                <a href="pageSectionEditor.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Back to Editor
                </a>
                <button type="submit" name="generate" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Generate Page
                </button>
            </div>
        </form>
    </div>
    
    <?php if (!empty($generatedPages)): ?>
        <div class="max-w-2xl mx-auto mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Generated Pages</h2>
            <ul class="list-disc pl-5 space-y-2">
                <?php foreach (array_keys($generatedPages) as $pageName): ?>
                    <li>
                        <span class="font-medium"><?php echo ucfirst($pageName); ?></span>
                        <span class="text-green-600 ml-2">✓</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-blue-800">
                    <strong>Note:</strong> Your page section changes have been saved. You can download the generated files or continue editing.
                </p>
            </div>
            
            <div class="mt-6 flex justify-center">
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 mr-4">
                    Download Files
                </a>
                <a href="../index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Start New Project
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load stored section data from local storage
    const storedData = localStorage.getItem('webgenx_page_sections');
    if (storedData) {
        const sectionData = JSON.parse(storedData);
        console.log('Loaded section data from local storage:', sectionData);
    }
    
    // Update preview when page selection changes
    const pageSelect = document.getElementById('page-select');
    if (pageSelect) {
        pageSelect.addEventListener('change', function() {
            const selectedPage = this.value;
            if (selectedPage) {
                // Submit the form to update the preview
                this.form.submit();
            }
        });
    }
});
</script>

<?php include_once('../includes/footer.php'); ?>