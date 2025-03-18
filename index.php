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
            // error_log('RESPONSE'. print_r($response['data'],true));
            // error_log('RESPONSE'. print_r($response['sections'],true));
            $data = $response['sections'];
            
            // Store the section data in session for the page editor
            session_start();
            $_SESSION['page_sections'] = $data;
            $_SESSION['website_name'] = $formData['websiteName'];
            
            // Redirect to the page section editor in the pages directory
            header('Location: pages/pageSectionEditor.php');
            exit;
            
            // Initialize arrays to group files by type
            // $templateFiles   = [];
            // $cssFiles        = [];
            // $jsFiles         = [];
            // $folderstructure = [];

            // Process each page's data
            // foreach ($data as $pageName => $pageData) {
            //     // Decode the JSON structure
            //     $structure       = json_decode($pageData['pagefolderstructure'], true);
            //      $folderstructure[$pageName] = $structure;
            //     // Extract template files
            //     if (isset($pageData['sections'])) {
            //         $templateFiles[$pageName] = $pageData['sections'];
            //     }

            //     // Extract CSS files
            //     if (isset($structure['kaushal']['assets']['css'])) {
            //         $cssFiles[$pageName] = array_keys($structure['kaushal']['assets']['css']);
            //     }

            //     // Extract JS files
            //     if (isset($structure['kaushal']['assets']['js'])) {
            //         $jsFiles[$pageName] = array_keys($structure['kaushal']['assets']['js']);
            //     }
            // }

            // Output results
            // echo "Template files by page:\n";
            // print_r($templateFiles);

            // echo "CSS files by page:\n";
            // print_r($cssFiles);

            // echo "JS files by page:\n";
            // print_r($jsFiles);

            // echo "Folder structure:\n";
            // print_r($folderstructure);
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
