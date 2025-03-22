<?php
session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

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
    
    // Create the new path
    $template_dir = 'templates';
    $new_section_path = $template_dir . '/' . $new_file_name;
    
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
        'content' => '<!-- ' . $section_name . ' section content -->'
    ];
    
    // Add section to dependencies if not already there
    if (!isset($_SESSION['page_files'][$page_name]['dependencies'])) {
        $_SESSION['page_files'][$page_name]['dependencies'] = [];
    }
    
    if (!in_array($new_section_path, $_SESSION['page_files'][$page_name]['dependencies'])) {
        $_SESSION['page_files'][$page_name]['dependencies'][] = $new_section_path;
    }
    
    echo json_encode(['success' => true, 'message' => 'Section added successfully']);
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
    
    // Remove section from session
    array_splice($_SESSION['page_files'][$pageName]['section_files'], $sectionIndex, 1);
    
    // Remove section from dependencies
    if (($key = array_search($sectionPath, $_SESSION['page_files'][$pageName]['dependencies'])) !== false) {
        array_splice($_SESSION['page_files'][$pageName]['dependencies'], $key, 1);
    }
    
    echo json_encode(['success' => true, 'message' => 'Section deleted successfully']);
}