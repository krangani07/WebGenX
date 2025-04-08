<?php
session_start();
require_once '../../includes/api_handler.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the JSON data from the request
$jsonData = file_get_contents('php://input');
$formData = json_decode($jsonData, true);

// Validate the required fields
if (!isset($formData['componentType']) || !isset($formData['componentPurpose']) || 
    !isset($formData['outputPath']) || !isset($formData['website_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Create an instance of ApiHandler with the form data and 'subPrompt' type
    $handler = new ApiHandler($formData, 'subPrompt');
    
    // Handle the request
    $response = $handler->handleRequest();
    
    // Check if the API call was successful
    if ($response['code'] >= 200 && $response['code'] < 300) {
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Section regenerated successfully',
            'data' => $response
        ]);
    } else {
        // Return error response
        echo json_encode([
            'success' => false,
            'message' => 'Error from API: ' . $response['code'],
            'data' => $response
        ]);
    }
} catch (Exception $e) {
    // Return exception response
    echo json_encode([
        'success' => false,
        'message' => 'Exception: ' . $e->getMessage()
    ]);
}