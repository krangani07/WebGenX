<?php
session_start();
require_once '../../includes/api_handler.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the page data from the request
$pageData = json_decode(file_get_contents('php://input'), true);

if (!isset($pageData['page']) || empty($pageData['page'])) {
    echo json_encode(['success' => false, 'message' => 'Page name is required']);
    exit;
}

$pageName = $pageData['page'];

// Get the page information from the session
if (!isset($_SESSION['page_files'][$pageName])) {
    echo json_encode(['success' => false, 'message' => 'Page information not found in session']);
    exit;
}

// Prepare the data for the API handler
$formData = [
    'websiteName' => $_SESSION['website_name'] ?? 'Default Website',
    'websiteType' => $_SESSION['website_type'] ?? 'Default Type',
    'description' => $_SESSION['description'] ?? 'Default Description',
    'colorScheme' => $_SESSION['color_scheme'] ?? 'Default Color Scheme',
    'typography' => $_SESSION['typography'] ?? 'Default Typography',
    'pageName' => $pageName,
    'sections' => $_SESSION['page_files'][$pageName]['section_files'] ?? []
];

// Log the data being sent to the API handler
error_log('Data sent to API handler: ' . print_r($formData, true));

// Call the API handler with the singlePage prompt type
$response = handleApiRequest($formData, 'singlePage');

// Log the response from the API handler
error_log('Response from API handler: ' . print_r($response, true));

// Store the response in the session
$_SESSION['generated_page'] = [
    'name' => $pageName,
    'response' => $response,
    'formData' => $formData // Store the form data for debugging
];

// Return success response with redirect URL (updated path)
echo json_encode([
    'success' => true, 
    'message' => 'Page generated successfully',
    'redirect' => '/qp/WebGenX/pages/generate/view_generated_page.php',
    'debug_data' => $formData // Include the form data in the response for debugging
]);