<?php
session_start();
header('Content-Type: application/json');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the JSON data from the request
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Check if the required data is present
if (!isset($data['page']) || !isset($data['section_index'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$page = $data['page'];
$section_index = $data['section_index'];

// Check if the page exists in the session
if (!isset($_SESSION['page_files'][$page])) {
    echo json_encode(['success' => false, 'message' => 'Page not found']);
    exit;
}

// Check if the section exists
if (!isset($_SESSION['page_files'][$page]['section_files'][$section_index])) {
    echo json_encode(['success' => false, 'message' => 'Section not found']);
    exit;
}

// Remove the section
unset($_SESSION['page_files'][$page]['section_files'][$section_index]);

// Reindex the array to ensure sequential keys
$_SESSION['page_files'][$page]['section_files'] = array_values($_SESSION['page_files'][$page]['section_files']);

echo json_encode(['success' => true, 'message' => 'Section deleted successfully']);
?>