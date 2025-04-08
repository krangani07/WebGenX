<?php
session_start();
require_once '../../includes/api_handler.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Set initial generation status
$_SESSION['generation_status'] = [
    'status' => 'in_progress',
    'message' => 'Generating page...'
];

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

// Get the website name from session
$websiteName = $_SESSION['website_name'] ?? 'Default Website';

// Get header and footer content for reference
$headerPath = USER_WEBSITES . "/{$websiteName}/includes/header.php";
$footerPath = USER_WEBSITES . "/{$websiteName}/includes/footer.php";

$headerContent = file_exists($headerPath) ? file_get_contents($headerPath) : '';
$footerContent = file_exists($footerPath) ? file_get_contents($footerPath) : '';

// Create header and footer reference
$headerFooterReference = "```php\n// header.php\n{$headerContent}\n```\n\n```php\n// footer.php\n{$footerContent}\n```";

// Prepare the data for the API handler
$formData = [
    'websiteName' => $_SESSION['website_name'] ?? 'Default Website',
    'websiteType' => $_SESSION['website_type'] ?? 'Default Type',
    'description' => $_SESSION['description'] ?? 'Default Description',
    'colorScheme' => $_SESSION['color_scheme'] ?? 'Default Color Scheme',
    'typography' => $_SESSION['typography'] ?? 'Default Typography',
    'pageName' => $pageName,
    // Include all page_files data for this page, not just section_files
    'pageData' => $_SESSION['page_files'][$pageName] ?? [],
    // Keep the sections for backward compatibility
    'sections' => $_SESSION['page_files'][$pageName]['section_files'] ?? [],
    // Add header and footer reference
    'headerFooterReference' => $headerFooterReference
];

// Call the API handler with the singlePage prompt type
$response = handleApiRequest($formData, 'singlePage');

// Store the response in the session
$_SESSION['generated_page'] = [
    'name' => $pageName,
    'response' => $response,
    'formData' => $formData // Store the form data for debugging
];

// When generation is complete, update the status
$_SESSION['generation_status'] = [
    'status' => 'completed',
    'message' => 'Page generation complete'
];

// After successfully generating the page, mark it as generated in the session
if (isset($_SESSION['page_files'][$pageName])) {
    $_SESSION['page_files'][$pageName]['generated'] = true;
}

// Return success response with redirect URL
echo json_encode([
    'success' => true, 
    'message' => 'Page generated successfully',
    'redirect' => '/qp/WebGenX/pages/generate/preview_page.php?page=' . urlencode($pageName),
    'pageName' => $pageName
]);