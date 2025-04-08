<?php
session_start();
header('Content-Type: application/json');

// Check if there's a generation status in the session
if (isset($_SESSION['generation_status'])) {
    echo json_encode($_SESSION['generation_status']);
    
    // If generation is complete, clear the status
    if ($_SESSION['generation_status']['status'] === 'completed') {
        unset($_SESSION['generation_status']);
    }
} else {
    // Default to completed if no status is found
    echo json_encode([
        'status' => 'completed',
        'message' => 'No generation in progress'
    ]);
}