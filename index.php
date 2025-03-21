<?php
require_once 'config/config.php';
require_once 'includes/api_handler.php';

if (isset($_POST['submit'])) {
    // Start the session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Unset all existing session variables
    $_SESSION = array();
    
    $formData = [
        'websiteName' => $_POST['websiteName'],
        'websiteType' => $_POST['websiteType'],
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
            // Store the response data in session
            $_SESSION['website_name'] = $formData['websiteName'];
            $_SESSION['website_type'] = $formData['websiteType'];
            $_SESSION['description'] = $formData['description'];
            $_SESSION['color_scheme'] = $formData['colorScheme'];
            $_SESSION['typography'] = $formData['typography'];
            $_SESSION['pages'] = $formData['pages'];
            $_SESSION['custom_pages'] = $formData['customPages'];
            $_SESSION['all_pages'] = $formData['allPages'];
            
            // Store page files data if available
            if (isset($response['page_files'])) {
                $_SESSION['page_files'] = $response['page_files'];
                $_SESSION['response_data'] = json_decode($response['data'], true);
                // error_log("page_files:".print_r($response['page_files'],true));
                // error_log("data:".print_r($response['data'],true));
                
                // Redirect to the page editor with correct path
                header('Location: /qp/WebGenX/pages/page_and_sections/page_editor.php');
                exit;
            }
        } else {
            error_log('Error: HTTP Status Code ' . print_r($response['code'], true) . "\n" . print_r($response['data'], true));
        }
    } else {
        echo '';
    }
}

include 'includes/header.php';
include 'includes/form.php';
include 'includes/footer.php';

?>
<script type="module" src="/qp/WebGenX/assets/js/formValidation.js"></script>
<script type="module" src="/qp/WebGenX/assets/js/formHandling.js"></script>
