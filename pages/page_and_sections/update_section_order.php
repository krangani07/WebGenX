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
if (!isset($data['page']) || !isset($data['sections'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$page = $data['page'];
$section_order = $data['sections'];

// Check if the page exists in the session
if (!isset($_SESSION['page_files'][$page])) {
    echo json_encode(['success' => false, 'message' => 'Page not found']);
    exit;
}

// Reorder the sections based on the new order
$sections = $_SESSION['page_files'][$page]['section_files'];
$new_sections = [];

foreach ($section_order as $index) {
    if (isset($sections[$index])) {
        $new_sections[] = $sections[$index];
    }
}

// Update the session with the new order
$_SESSION['page_files'][$page]['section_files'] = $new_sections;

echo json_encode(['success' => true, 'message' => 'Section order updated successfully']);
?>