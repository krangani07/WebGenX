<?php
/**
 * JsonExtractor Class
 * 
 * Extracts section names and locations from a JSON structure based on a given page name
 */
class JsonExtractor {
    /**
     * Extract sections and create a filtered JSON structure for a specific page
     * 
     * @param string $jsonString The JSON string containing the website structure
     * @param string $pageName The name of the page to extract sections for
     * @return array An array containing the filtered JSON and sections list
     */
    public function extractSections($jsonString, $pageName) {
        // Decode the JSON string
        $jsonData = json_decode($jsonString, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'Invalid JSON: ' . json_last_error_msg(),
                'filteredJson' => null,
                'sections' => []
            ];
        }
        
        // Get the first key of the JSON object (e.g., "p1")
        $websiteKey = array_key_first($jsonData);
        
        if (!$websiteKey) {
            return [
                'error' => 'Invalid JSON structure: No root key found',
                'filteredJson' => null,
                'sections' => []
            ];
        }
        
        // Create a template for the filtered JSON
        $filteredJson = [
            $websiteKey => [
                'assets' => [
                    'css' => [],
                    'js' => [],
                    'images' => [],
                    'fonts' => []
                ],
                'includes' => [
                    'header.php' => '',
                    'footer.php' => '',
                ],
                'pages' => []
            ]
        ];
        
        // Extract sections for the specified page
        $sections = [];
        $templateKey = "{$pageName}_template";
        
        // Check if the template exists for the specified page
        if (isset($jsonData[$websiteKey]['includes'][$templateKey])) {
            $sectionTemplates = $jsonData[$websiteKey]['includes'][$templateKey];
            
            // Add the template to the filtered JSON
            $filteredJson[$websiteKey]['includes'][$templateKey] = [];
            
            // Extract section names and add them to the sections array
            foreach ($sectionTemplates as $sectionFile => $content) {
                $sections[] = $sectionFile;
                $filteredJson[$websiteKey]['includes'][$templateKey][$sectionFile] = '';
            }
        }
        
        // Add the page to the filtered JSON
        $pageFile = $pageName === 'home' ? 'index.php' : "{$pageName}.php";
        $filteredJson[$websiteKey]['pages'][$pageFile] = '';
        
        // Check if there's a JS file for this page
        if (isset($jsonData[$websiteKey]['assets']['js']["{$pageName}.js"])) {
            $filteredJson[$websiteKey]['assets']['js']["{$pageName}.js"] = '';
        }
        
        return [
            'error' => null,
            'filteredJson' => $filteredJson,
            'sections' => $sections
        ];
    }
    
    /**
     * Get the full path to a section file
     * 
     * @param string $websiteKey The website key (e.g., "p1")
     * @param string $pageName The name of the page
     * @param string $sectionFile The section file name
     * @return string The full path to the section file
     */
    public function getSectionPath($websiteKey, $pageName, $sectionFile) {
        return "includes/{$pageName}_template/{$sectionFile}";
    }
    
    /**
     * Get the full path to a page file
     * 
     * @param string $websiteKey The website key (e.g., "p1")
     * @param string $pageName The name of the page
     * @return string The full path to the page file
     */
    public function getPagePath($websiteKey, $pageName) {
        $pageFile = $pageName === 'home' ? 'index.php' : "{$pageName}.php";
        return "pages/{$pageFile}";
    }
}

// Example usage:
/*
$jsonString = '{"p1":{"assets":{"css":{"global.css":""},"js":{"global.js":"","home.js":"","about.js":"","services.js":"","blog.js":"","contact.js":""},"images":{},"fonts":{}},"includes":{"header.php":"","footer.php":"","home_template":{"hero_home.php":"","features_home.php":"","testimonial_home.php":""},"about_template":{"hero_about.php":"","content_about.php":""},"services_template":{"hero_services.php":"","list_services.php":""},"blog_template":{"hero_blog.php":"","list_blog.php":""},"contact_template":{"hero_contact.php":"","form_contact.php":""}},"pages":{"index.php":"","about.php":"","services.php":"","blog.php":"","contact.php":""}}}';
$pageName = 'home';

$extractor = new JsonExtractor();
$result = $extractor->extractSections($jsonString, $pageName);

echo "Filtered JSON:\n";
echo json_encode($result['filteredJson'], JSON_PRETTY_PRINT);

echo "\n\nSections:\n";
print_r($result['sections']);
*/
?>