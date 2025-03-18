<?php
/**
 * JsonExtractor Class
 * 
 * Extracts section names and locations from a JSON structure based on a given page name
 */
class JsonExtractor {
    /**
     * Extract sections and create a filtered JSON structure for a specific page or all pages
     * 
     * @param string $jsonString The JSON string containing the website structure
     * @param string|null $pageName The name of the page to extract sections for (null for all pages)
     * @return array An array containing the formatted output with sections and page folder structure
     */
    public function extractSections($jsonString, $pageName = null) {
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
        
        // If pageName is null, process all pages
        if ($pageName === null) {
            // Get all sections using extractAllSections
            $allPageSections = $this->extractAllSections($jsonString);
            
            // If there was an error, return it
            if (isset($allPageSections['error'])) {
                return [
                    'error' => $allPageSections['error'],
                    'filteredJson' => null,
                    'sections' => []
                ];
            }
            
            // Create the new format for the response
            $formattedResponse = [];
            
            foreach ($allPageSections as $page => $sections) {
                // Create a filtered JSON structure for this page
                $pageFilteredJson = [
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
                
                // Add the template to the filtered JSON
                $templateKey = "{$page}_template";
                $pageFilteredJson[$websiteKey]['includes'][$templateKey] = [];
                
                // Add sections to the filtered JSON
                foreach ($sections as $sectionFile) {
                    $pageFilteredJson[$websiteKey]['includes'][$templateKey][$sectionFile] = '';
                }
                
                // Add the page to the filtered JSON
                $pageFile = $page === 'home' ? 'index.php' : "{$page}.php";
                $pageFilteredJson[$websiteKey]['pages'][$pageFile] = '';
                
                // Check if there's a JS file for this page
                if (isset($jsonData[$websiteKey]['assets']['js']["{$page}.js"])) {
                    $pageFilteredJson[$websiteKey]['assets']['js']["{$page}.js"] = '';
                }
                
                // Check if there's a global JS file
                if (isset($jsonData[$websiteKey]['assets']['js']["global.js"])) {
                    $pageFilteredJson[$websiteKey]['assets']['js']["global.js"] = '';
                }
                
                // Check if there's a CSS file for this page
                if (isset($jsonData[$websiteKey]['assets']['css']["{$page}.css"])) {
                    $pageFilteredJson[$websiteKey]['assets']['css']["{$page}.css"] = '';
                }
                
                // Check if there's a global CSS file
                if (isset($jsonData[$websiteKey]['assets']['css']["global.css"])) {
                    $pageFilteredJson[$websiteKey]['assets']['css']["global.css"] = '';
                }
                
                // Add this page to the formatted response
                $formattedResponse[$page] = array(
                    'sections' => $sections,
                    'pagefolderstructure' => json_encode($pageFilteredJson)
                );
            }
            
            return $formattedResponse;
        } else {
            // Process just the specified page
            $sections = [];
            $templateKey = "{$pageName}_template";
            
            // Create a filtered JSON structure for this page
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
            
            // Check if there's a global JS file
            if (isset($jsonData[$websiteKey]['assets']['js']["global.js"])) {
                $filteredJson[$websiteKey]['assets']['js']["global.js"] = '';
            }
            
            // Check if there's a CSS file for this page
            if (isset($jsonData[$websiteKey]['assets']['css']["{$pageName}.css"])) {
                $filteredJson[$websiteKey]['assets']['css']["{$pageName}.css"] = '';
            }
            
            // Check if there's a global CSS file
            if (isset($jsonData[$websiteKey]['assets']['css']["global.css"])) {
                $filteredJson[$websiteKey]['assets']['css']["global.css"] = '';
            }
            
            // Create the formatted response for a single page
            $formattedResponse = [
                $pageName => [
                    'sections' => $sections,
                    'pagefolderstructure' => json_encode($filteredJson, JSON_PRETTY_PRINT)
                ]
            ];
            
            return $formattedResponse;
        }
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
    
    /**
     * Log errors for specific page sections
     * 
     * @param string $pageName The name of the page
     * @param array $sectionErrors Array of section names with errors
     * @return bool Whether the log was successfully created
     */
    public function logSectionErrors($pageName, $sectionErrors) {
        $logDir = 'logs';
        
        // Create logs directory if it doesn't exist
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/section_errors.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $logEntry = "[{$timestamp}] Page: {$pageName} - Section Errors: " . 
                    json_encode($sectionErrors) . PHP_EOL;
        
        return file_put_contents($logFile, $logEntry, FILE_APPEND) !== false;
    }
    
    /**
     * Get all logged errors for a specific page
     * 
     * @param string $pageName The name of the page (optional)
     * @return array Array of errors by page and section
     */
    public function getPageErrors($pageName = null) {
        $logFile = 'logs/section_errors.log';
        $errors = [];
         
        if (!file_exists($logFile)) {
            return $errors;
        }
        
        $logContent = file_get_contents($logFile);
        $logLines = explode(PHP_EOL, trim($logContent));
        
        foreach ($logLines as $line) {
            if (preg_match('/\[(.*?)\] Page: (.*?) - Section Errors: (.*)/', $line, $matches)) {
                $timestamp = $matches[1];
                $page = $matches[2];
                $sectionErrors = json_decode($matches[3], true);
                
                if ($pageName === null || $pageName === $page) {
                    if (!isset($errors[$page])) {
                        $errors[$page] = [];
                    }
                    
                    foreach ($sectionErrors as $section) {
                        if (!in_array($section, $errors[$page])) {
                            $errors[$page][] = $section;
                        }
                    }
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Extract all sections organized by page name
     * 
     * @param string $jsonString The JSON string containing the website structure
     * @return array An array with page names as keys and arrays of section files as values
     */
    public function extractAllSections($jsonString) {
        // Decode the JSON string
        $jsonData = json_decode($jsonString, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Invalid JSON: ' . json_last_error_msg()];
        }
        
        // Get the first key of the JSON object (e.g., "p1", "kaushal", etc.)
        $websiteKey = array_key_first($jsonData);
        
        if (!$websiteKey) {
            return ['error' => 'Invalid JSON structure: No root key found'];
        }
        
        $allSections = array();
        
        // Check if includes exist
        if (isset($jsonData[$websiteKey]['includes'])) {
            // Loop through all keys in includes
            foreach ($jsonData[$websiteKey]['includes'] as $key => $value) {
                // Check if this is a template key (ends with _template)
                if (strpos($key, '_template') !== false) {
                    // Extract the page name from the template key
                    $pageName = str_replace('_template', '', $key);
                    
                    // Initialize the array for this page if it doesn't exist
                    if (!isset($allSections[$pageName])) {
                        $allSections[$pageName] = [];
                    }
                    
                    // Add all section files for this page
                    foreach ($value as $sectionFile => $content) {
                        $allSections[$pageName][] = $sectionFile;
                    }
                }
            }
        }
        
        return $allSections;
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