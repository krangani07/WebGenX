<?php
require_once 'config/config.php';
require_once 'includes/api_handler.php';

if (isset($_POST['submit'])) {
    $formData = [
        'websiteName' => $_POST['websiteName'],
        'websiteType' => $_POST['websiteType'],
        'aiProvider'  => 'Gemini AI' ?? $_POST['aiProvider'],
        'description' => $_POST['description'],
        'colorScheme' => $_POST['colorScheme'],
        'typography'  => $_POST['typography'] ?? 'inter',
        'pages'       => isset($_POST['pages']) ? $_POST['pages'] : [],
        'customPages' => isset($_POST['customPages']) ? array_filter($_POST['customPages']) : [],
        'allPages'    => array_merge(
            isset($_POST['pages']) ? $_POST['pages'] : [],
            isset($_POST['customPages']) ? array_filter($_POST['customPages']) : []
        )
    ];

    $response = handleApiRequest($formData);
    if (isset($response['code'])) {
        if ($response['code'] == 200) {
            $response = $response['data'];
            error_log('RESPONSE'. print_r($response,true));
            // error_log('RESPONSE' . $response['data']);
        } else {
            error_log('Error: HTTP Status Code ' . print_r($response['code'],true) . "\n" . print_r($response['data'], true));
        }
    } else {
        echo '';
    }
}

include 'includes/header.php';
include 'includes/form.php';
include 'includes/footer.php';
