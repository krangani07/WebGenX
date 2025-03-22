<?php
// Start output buffering to catch any unwanted output
ob_start();
session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header to JSON
    header('Content-Type: application/json');
    
    try {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        
        switch ($action) {
            case 'add':
                addSection();
                break;
            case 'delete':
                deleteSection();
                break;
            case 'check_duplicate':
                checkDuplicate();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } catch (Exception $e) {
        // Clear any output that might have been generated
        ob_clean();
        // Return error as JSON
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    // Set header to JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
// End and flush the output buffer
ob_end_flush();

/**
 * Check if a section with the given name already exists
 */
/**
 * Check if a section with the given name already exists
 */
function checkDuplicate() {
    $page_name = isset($_POST['page_name']) ? $_POST['page_name'] : '';
    $section_name = isset($_POST['section_name']) ? $_POST['section_name'] : '';
    
    // Validate input
    if (empty($page_name) || empty($section_name)) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        return;
    }
    
    // Sanitize section name
    $section_name = preg_replace('/[^a-zA-Z_]/', '', $section_name);
    
    // Determine the suffix based on the page
    $suffix = ($page_name === 'home') ? '_index' : '_' . $page_name;
    
    // Create the new file name with the proper suffix and .php extension
    $new_file_name = $section_name . $suffix . '.php';
    
    // Create the template directory name based on the page
    $template_dir = ($page_name === 'home') ? 'index_template' : $page_name . '_template';
    
    // Check if section exists (case-insensitive)
    $duplicate = false;
    if (isset($_SESSION['page_files'][$page_name]['section_files'])) {
        foreach ($_SESSION['page_files'][$page_name]['section_files'] as $section) {
            // Compare the base section name (without suffix) case-insensitively
            $existing_name = $section['name'];
            $existing_base_name = preg_replace('/' . preg_quote($suffix, '/') . '\.php$/i', '', $existing_name);
            
            if (strtolower($existing_base_name) === strtolower($section_name)) {
                $duplicate = true;
                break;
            }
        }
    }
    
    echo json_encode(['success' => true, 'duplicate' => $duplicate]);
}

/**
 * Add a new section to a page
 */
function addSection() {
    try {
        // Get the required parameters
        $page_name = isset($_POST['page_name']) ? $_POST['page_name'] : '';
        $section_name = isset($_POST['section_name']) ? $_POST['section_name'] : '';
        $section_type = isset($_POST['section_type']) ? $_POST['section_type'] : 'custom';
        
        // Validate input
        if (empty($page_name) || empty($section_name)) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Sanitize section name (remove spaces, special chars)
        $section_name = preg_replace('/[^a-zA-Z_]/', '', $section_name);
        
        // Determine the suffix based on the page
        $suffix = ($page_name === 'home') ? '_index' : '_' . $page_name;
        
        // Create the new file name with the proper suffix and .php extension
        $new_file_name = $section_name . $suffix . '.php';
        
        // Check for duplicates (case-insensitive)
        $duplicate = false;
        if (isset($_SESSION['page_files'][$page_name]['section_files'])) {
            foreach ($_SESSION['page_files'][$page_name]['section_files'] as $section) {
                // Compare the base section name (without suffix) case-insensitively
                $existing_name = $section['name'];
                $existing_base_name = preg_replace('/' . preg_quote($suffix, '/') . '\.php$/i', '', $existing_name);
                
                if (strtolower($existing_base_name) === strtolower($section_name)) {
                    $duplicate = true;
                    break;
                }
            }
        }
        
        // If duplicate found, return error
        if ($duplicate) {
            echo json_encode(['success' => false, 'message' => 'A section with this name already exists']);
            return;
        }
        
        // Create the template directory name based on the page
        $template_dir = ($page_name === 'home') ? 'index_template' : $page_name . '_template';
        
        // Create the new path
        $new_section_path = $template_dir . '/' . $new_file_name;
        
        // Get website name from session
        $website_name = isset($_SESSION['website_name']) ? $_SESSION['website_name'] : 'default_website';
        
        // Include config file safely
        if (!defined('USER_WEBSITES')) {
            require_once __DIR__. '/../config/config.php';
        }
        
        // Create the physical file in the includes directory under the page's template folder
        $user_website_path = USER_WEBSITES . '/' . $website_name . '/includes/' . $new_section_path;
        
        // Make sure the directory exists
        $template_dir_path = dirname($user_website_path);
        if (!is_dir($template_dir_path)) {
            if (!mkdir($template_dir_path, 0755, true)) {
                throw new Exception("Failed to create directory: $template_dir_path");
            }
        }
        
        // Create default content for the section
        $default_content = "<!-- Section: {$section_name} for {$page_name} page -->\n";
        $default_content .= "<div class=\"section {$section_name}-section\">\n";
        $default_content .= "    <div class=\"container\">\n";
        $default_content .= "        <h2>{$section_name}</h2>\n";
        $default_content .= "        <p>This is the {$section_name} section content for the {$page_name} page.</p>\n";
        $default_content .= "    </div>\n";
        $default_content .= "</div>";
        
        // Write the content to the file
        if (file_put_contents($user_website_path, $default_content) === false) {
            throw new Exception("Failed to write to file: $user_website_path");
        }
        
        // Add the section to the session
        if (!isset($_SESSION['page_files'][$page_name]['section_files'])) {
            $_SESSION['page_files'][$page_name]['section_files'] = [];
        }
        
        // Create a default description
        $section_description = 'Custom section for ' . $page_name . ' page';
        
        // Add the section to the page
        $_SESSION['page_files'][$page_name]['section_files'][] = [
            'name' => $new_file_name,
            'path' => $new_section_path,
            'type' => $section_type,
            'description' => $section_description,
            'content' => $default_content
        ];
        
        // Add section to dependencies if not already there
        if (!isset($_SESSION['page_files'][$page_name]['dependencies'])) {
            $_SESSION['page_files'][$page_name]['dependencies'] = [];
        }
        
        if (!in_array($new_section_path, $_SESSION['page_files'][$page_name]['dependencies'])) {
            $_SESSION['page_files'][$page_name]['dependencies'][] = $new_section_path;
        }
        
        // Update the folder structure in response_data
        updateFolderStructure('add', $new_section_path, $new_file_name);
        
        echo json_encode(['success' => true, 'message' => 'Section added successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

/**
 * Delete a section from a page
 */
function deleteSection() {
    $sectionIndex = isset($_POST['section_index']) ? intval($_POST['section_index']) : -1;
    $pageName = isset($_POST['page']) ? $_POST['page'] : '';
    
    // Validate input
    if ($sectionIndex < 0 || empty($pageName)) {
        echo json_encode(['success' => false, 'message' => 'Invalid section index or page name']);
        return;
    }
    
    // Check if section exists
    if (!isset($_SESSION['page_files'][$pageName]['section_files'][$sectionIndex])) {
        echo json_encode(['success' => false, 'message' => 'Section not found']);
        return;
    }
    
    // Get section path to remove from dependencies
    $sectionPath = $_SESSION['page_files'][$pageName]['section_files'][$sectionIndex]['path'];
    $sectionName = $_SESSION['page_files'][$pageName]['section_files'][$sectionIndex]['name'];
    
    // Get website name from session
    $website_name = isset($_SESSION['website_name']) ? $_SESSION['website_name'] : 'default_website';
    
    // Include config file if USER_WEBSITES is not defined
    if (!defined('USER_WEBSITES')) {
        require_once __DIR__. '/../config/config.php';
    }
    
    // Create the physical file path
    $file_path = USER_WEBSITES . '/' . $website_name . '/includes/' . $sectionPath;
    
    // Delete the physical file if it exists
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    // Remove section from session
    array_splice($_SESSION['page_files'][$pageName]['section_files'], $sectionIndex, 1);
    
    // Remove section from dependencies
    if (($key = array_search($sectionPath, $_SESSION['page_files'][$pageName]['dependencies'])) !== false) {
        array_splice($_SESSION['page_files'][$pageName]['dependencies'], $key, 1);
    }
    
    // Update the folder structure in response_data
    updateFolderStructure('delete', $sectionPath, $sectionName);
    
    echo json_encode(['success' => true, 'message' => 'Section deleted successfully']);
}

/**
 * Update the folder structure in response_data
 * 
 * @param string $action The action being performed (add, edit, delete)
 * @param string $sectionPath The path of the section
 * @param string $sectionName The name of the section file
 * @param string $oldSectionPath The old path of the section (for edit action)
 * @param string $oldSectionName The old name of the section file (for edit action)
 */
function updateFolderStructure($action, $sectionPath, $sectionName, $oldSectionPath = '', $oldSectionName = '') {
    // Check if response_data exists
    if (!isset($_SESSION['response_data']) || empty($_SESSION['response_data'])) {
        return; // Nothing to update
    }
    
    $websiteName = isset($_SESSION['website_name']) ? $_SESSION['website_name'] : 'website';
    if (!isset($_SESSION['response_data'][$websiteName])) {
        return; // Website not found in response_data
    }
    
    // Parse the section path
    $parts = explode('/', $sectionPath);
    if (count($parts) < 2) {
        return; // Invalid path format
    }
    
    $template_dir = $parts[0];
    $section_file = $parts[1];
    
    // Handle different actions
    switch ($action) {
        case 'add':
            // Create template directory if it doesn't exist
            if (!isset($_SESSION['response_data'][$websiteName]['includes'][$template_dir])) {
                $_SESSION['response_data'][$websiteName]['includes'][$template_dir] = [];
            }
            
            // Add section file to the template directory
            $_SESSION['response_data'][$websiteName]['includes'][$template_dir][$section_file] = '';
            break;
            
        case 'edit':
            // If old path is provided, remove the old section first
            if (!empty($oldSectionPath) && !empty($oldSectionName)) {
                $old_parts = explode('/', $oldSectionPath);
                if (count($old_parts) >= 2) {
                    $old_template_dir = $old_parts[0];
                    $old_section_file = $old_parts[1];
                    
                    // Remove old section
                    if (isset($_SESSION['response_data'][$websiteName]['includes'][$old_template_dir][$old_section_file])) {
                        unset($_SESSION['response_data'][$websiteName]['includes'][$old_template_dir][$old_section_file]);
                    }
                }
            }
            
            // Create template directory if it doesn't exist
            if (!isset($_SESSION['response_data'][$websiteName]['includes'][$template_dir])) {
                $_SESSION['response_data'][$websiteName]['includes'][$template_dir] = [];
            }
            
            // Add new section file
            $_SESSION['response_data'][$websiteName]['includes'][$template_dir][$section_file] = '';
            break;
            
        case 'delete':
            // Remove section file from the template directory
            if (isset($_SESSION['response_data'][$websiteName]['includes'][$template_dir][$section_file])) {
                unset($_SESSION['response_data'][$websiteName]['includes'][$template_dir][$section_file]);
            }
            break;
    }
}