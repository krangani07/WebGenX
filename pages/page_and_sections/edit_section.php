<?php
session_start();
include '../../includes/header.php';

// Check if the required parameters are present
if (!isset($_GET['page']) || !isset($_GET['section'])) {
    header('Location: page_editor.php');
    exit;
}

$page = $_GET['page'];
$section_path = $_GET['section'];

// Find the section in the page_files
$section_data = null;
$section_index = -1;

if (isset($_SESSION['page_files'][$page]['section_files'])) {
    foreach ($_SESSION['page_files'][$page]['section_files'] as $index => $section) {
        if ($section['path'] === $section_path) {
            $section_data = $section;
            $section_index = $index;
            break;
        }
    }
}

// If section not found, redirect back to page editor
if ($section_data === null) {
    header('Location: page_editor.php?page=' . urlencode($page));
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_section'])) {
    // Update section content
    $_SESSION['page_files'][$page]['section_files'][$section_index]['content'] = $_POST['section_content'];
    
    // Redirect back to page editor
    header('Location: page_editor.php?page=' . urlencode($page) . '&updated=true');
    exit;
}

// Get section content (or default if not set)
$section_content = isset($section_data['content']) ? $section_data['content'] : '<!-- Section content goes here -->';
?>

<!-- Main Content Area -->
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Section: <?php echo htmlspecialchars($section_data['name']); ?></h1>
        <a href="page_editor.php?page=<?php echo urlencode($page); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Page Editor
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="post" action="">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="section_path">
                    Section Path:
                </label>
                <input type="text" id="section_path" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" value="<?php echo htmlspecialchars($section_data['path']); ?>" readonly>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2" for="section_content">
                    Section Content:
                </label>
                <textarea id="section_content" name="section_content" rows="20" class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm"><?php echo htmlspecialchars($section_content); ?></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" name="save_section" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include the external JavaScript file -->
<script src="../assets/js/pages/edit_section.js"></script>
// At the end of the file
include '../../includes/footer.php';
?>