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
    // Get the new section name
    $new_section_name = isset($_POST['section_name']) ? $_POST['section_name'] : '';
    
    // Validate section name
    $name_error = '';
    if (empty($new_section_name)) {
        $name_error = 'Section name cannot be empty';
    } elseif (!preg_match('/^[a-zA-Z_]+$/', $new_section_name)) {
        $name_error = 'Section name can only contain letters and underscores (no numbers)';
    } else {
        // Check for duplicate section names
        $duplicate = false;
        
        // Determine the suffix based on the page
        $suffix = ($page === 'home') ? '_index' : '_' . $page;
        
        // Create the new file name with the proper suffix and .php extension
        $new_file_name = $new_section_name . $suffix . '.php';
        
        // Skip checking the current section
        if (isset($_SESSION['page_files'][$page]['section_files'])) {
            foreach ($_SESSION['page_files'][$page]['section_files'] as $index => $section) {
                if ($index !== $section_index && isset($section['name'])) {
                    // Compare the base section name (without suffix) case-insensitively
                    $existing_name = $section['name'];
                    $existing_base_name = preg_replace('/' . preg_quote($suffix, '/') . '\.php$/i', '', $existing_name);
                    
                    if (strtolower($existing_base_name) === strtolower($new_section_name)) {
                        $duplicate = true;
                        break;
                    }
                }
            }
        }
        
        if ($duplicate) {
            $name_error = 'A section with this name already exists';
        }
    }
    
    // If no validation errors, proceed with saving
    if (empty($name_error)) {
        // Sanitize section name (remove spaces, special chars)
        $new_section_name = preg_replace('/[^a-zA-Z_]/', '', $new_section_name);
        
        // Determine the suffix based on the page
        $suffix = ($page === 'home') ? '_index' : '_' . $page;
        
        // Create the new file name with the proper suffix and .php extension
        $new_file_name = $new_section_name . $suffix . '.php';
        
        // Get the template directory from the current path
        $parts = explode('/', $section_data['path']);
        $template_dir = $parts[0];
        
        // Create the new path
        $new_section_path = $template_dir . '/' . $new_file_name;
        
        // Store old values for updating folder structure
        $old_section_path = $section_data['path'];
        $old_section_name = $section_data['name'];
        
        // Update the section data
        $_SESSION['page_files'][$page]['section_files'][$section_index]['name'] = $new_file_name;
        $_SESSION['page_files'][$page]['section_files'][$section_index]['path'] = $new_section_path;
        
        // Update section content
        $_SESSION['page_files'][$page]['section_files'][$section_index]['content'] = $_POST['section_content'];
        
        // Update the folder structure in response_data
        include_once '../../includes/section_handler.php';
        updateFolderStructure('edit', $new_section_path, $new_file_name, $old_section_path, $old_section_name);
        
        // Redirect back to page editor
        header('Location: page_editor.php?page=' . urlencode($page) . '&updated=true');
        exit;
    }
    // If there are validation errors, we'll continue to the form with the error message
} else {
    $name_error = '';
}

// Get section content (or default if not set)
$section_content = isset($section_data['content']) ? $section_data['content'] : '<!-- Section content goes here -->';

// Extract the base section name (without suffix and extension)
$filename = pathinfo($section_data['name'], PATHINFO_FILENAME);

// Determine the suffix based on the page
$suffix = ($page === 'home') ? '_index' : '_' . $page;

// Remove the suffix from the end of the filename
$base_section_name = preg_replace('/' . preg_quote($suffix, '/') . '$/', '', $filename);
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
                <label class="block text-gray-700 font-bold mb-2" for="section_name">
                    Section Name:
                </label>
                <div class="flex items-center">
                    <input type="text" id="section_name" name="section_name" 
                           class="flex-grow px-3 py-2 border <?php echo !empty($name_error) ? 'border-red-500' : 'border-gray-300'; ?> rounded-md" 
                           value="<?php echo htmlspecialchars($new_section_name ?? $base_section_name); ?>" 
                           placeholder="Enter section name">
                    <span class="ml-2 text-gray-500">
                        <?php echo ($page === 'home') ? '_index.php' : '_' . htmlspecialchars($page) . '.php'; ?>
                    </span>
                </div>
                <?php if (!empty($name_error)): ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo htmlspecialchars($name_error); ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 mt-1">Only letters and underscores allowed (no numbers). Extension will be added automatically.</p>
                <?php endif; ?>
            </div>
            
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
<script src="../../assets/js/pages/edit_section.js"></script>
<?php include '../../includes/footer.php'; ?>