<?php
require_once __DIR__ . '/../config/config.php';

/**
 * ApiHandler Class
 * 
 * Handles API requests to generate website content using AI.
 * Supports different types of prompts for various generation scenarios.
 */
class ApiHandler {
    /** @var array $formData Form data containing website generation parameters */
    private $formData;
    
    /** @var string $promptType Type of prompt to use (master, singlePage, subPrompt) */
    private $promptType;
    
    /**
     * Constructor for ApiHandler
     * 
     * @param array $formData Form data containing website generation parameters
     * @param string $promptType Type of prompt to use (default: 'master')
     */
    public function __construct($formData, $promptType = 'master') {
        $this->formData = $formData;
        $this->promptType = $promptType;
    }
    
    /**
     * Handles the API request process
     * 
     * @return array Response data from the API call
     */
    public function handleRequest() {
        $promptText = $this->generatePrompt();
        $data = $this->buildRequestData($promptText);
        return $this->makeApiCall($data);
    }
    
    /**
     * Generates the appropriate prompt based on promptType
     * 
     * @return string Generated prompt text
     */
    private function generatePrompt() {
        switch ($this->promptType) {
            case 'master':
                return $this->masterPrompt();
            case 'singlePage':
                return $this->singlePagePrompt();
            case 'subPrompt':
                return $this->subPrompt();
            default:
                return $this->masterPrompt();
        }
    }
    
    private function masterPrompt() {
        $promptText = "You are an AI-powered website generator. Your task is to create a fully functional multi-page website by generating structured HTML, PHP, JavaScript, and Tailwind.\n\n";

        // Basic website information
        $promptText .= "---\n\n";
        $promptText .= "General Guidelines\n";
        $promptText .= "- Follow best practices for design, accessibility, and performance.\n";
        $promptText .= "- Ensure consistency across all pages (branding, layout, typography, color scheme).\n";
        $promptText .= "- Generate the website step by step, focusing on one page at a time.\n";
        $promptText .= "- Use semantic HTML, clean Tailwind, and optimized JavaScript & PHP.\n\n";

        $promptText .= "---\n\n";
        $promptText .= "Website Foundation & Planning\n\n";

        // 1. General Information
        $promptText .= "1. General Information\n";
        $promptText .= "- Website Name: {$this->formData['websiteName']}\n";
        $promptText .= "- Purpose: {$this->formData['websiteType']}\n";
        $promptText .= "- Target Audience: General users interested in {$this->formData['websiteType']} content\n";
        $promptText .= "- Primary Call-to-Action (CTA): Contact Us\n\n";

        // 2. Website Pages & Structure
        $promptText .= "2. Website Pages & Structure\n";
        $promptText .= "The site should have the following pages:\n";

        if (!empty($this->formData['allPages'])) {
            foreach ($this->formData['allPages'] as $page) {
                $promptText .= "- {$page}: Content related to {$page}\n";
            }
        }

        $promptText .= "\n---\n\n";

        // 3. Design & Aesthetics
        $promptText .= "3. Design & Aesthetics\n";
        $promptText .= "- Color Scheme: {$this->formData['colorScheme']}\n";
        $promptText .= "- Typography: {$this->formData['typography']}\n";
        $promptText .= "- Layout Style: Modern, clean design\n";
        $promptText .= "- Branding Elements: Simple logo and icons\n";
        $promptText .= "- Responsive Design: Ensure compatibility across mobile, tablet, and desktop.\n\n";
        $promptText .= "- For Navigation use the following way to write the links '../pages/pagename.php'.\n\n";

        $promptText .= "---\n\n";

        // 4. Functionality & Features
        $promptText .= "4. Functionality & Features\n";
        $promptText .= "- Navigation: Sticky header\n";
        $promptText .= "- Forms: Contact form\n";
        $promptText .= "- Animations & Effects: Subtle animations\n\n";

        $promptText .= "---\n\n";

        // 5. Performance Optimization
        $promptText .= "5. Performance Optimization\n";
        $promptText .= "- Page Speed Optimization: Lazy loading, Minified assets\n";
        $promptText .= "- Image Optimization: WebP format preferred\n\n";
        $promptText .= "- use this api for img place holder 'https://placehold.co/600x400/000000/FFF ' ,WebP format preferred\n\n";

        $promptText .= "---\n\n";

        // 6. Content Style & Tone
        $promptText .= "6. Content Style & Tone\n";
        $promptText .= "- Writing Style: Professional yet friendly\n";
        $promptText .= "- Content-Length Preference: Concise and informative\n\n";

        $promptText .= "---\n\n";

        // 7. Technologies
        $promptText .= "7. Technologies\n";
        $promptText .= "- PHP, ES6+, Vanilla JS, Tailwind CSS\n\n";

        $promptText .= "---\n\n";

        // 8. Folder Structure
        $promptText .= "8. Folder Structure\n";
        $promptText .= "- Use the following folder structure:\n";
        $promptText .= "- For including anythings use this fomrat ../includes/abc.php  and ../assets/js/xyz.js \n";
        $promptText .= "- Create dedicated sections for each page and store them in the includes/pagename_template folder\n";
        $promptText .= "- Each page should be modular with separate section files (e.g., hero_home.php, features_home.php, etc.)\n";
        $promptText .= "- Use include statements to bring these sections together in the main page files\n";
        $promptText .= "```\n";
        $promptText .= "/{$this->formData['websiteName']}(root)\n";
        $promptText .= "├── /assets\n";
        $promptText .= "│   ├── /css  # if required\n";
        $promptText .= "│   │   ├── global.css   # Global styles (header, footer, layout)\n";
        $promptText .= "│   ├── /js\n";
        $promptText .= "│   │   ├── global.js   # Global JavaScript (menu toggle, animations)\n";
        $promptText .= "│   │   ├── [pagename].js   # Page-specific JavaScript\n";
        $promptText .= "│   ├── /images   # Store images/icons here\n";
        $promptText .= "│   ├── /fonts   # Store custom fonts here\n";
        $promptText .= "├── /includes\n";
        $promptText .= "│   ├── header.php   # Reusable header template\n";
        $promptText .= "│   ├── footer.php   # Reusable footer template\n";
        $promptText .= "│   ├── /[pagename]_template  # Page-specific template sections\n";
        $promptText .= "│   │   ├── [sectionname]_[pagename].php   # section for the page\n";
        $promptText .= "├── /pages\n";
        $promptText .= "│   ├── index.php  # Home Page\n";
        $promptText .= "│   ├── [page-name].php   # Additional pages\n";
        $promptText .= "```\n\n";

        // Additional description from the user
        $promptText .= "---\n\n";
        $promptText .= "Additional Description:\n";
        $promptText .= "{$this->formData['description']}\n\n";

        // Response Format instructions
        $promptText .= "---\n\n";
        $promptText .= "9. Response Format\n";
        $promptText .= "- First, provide the file and folder structure in a JSON format code block\n";

        $promptText .= "- Do not include any explanations or text outside of the code blocks\n";
        $promptText .= "- Example format:\n\n";

        $promptText .= "```json\n";
        $promptText .= "{\n";
        $promptText .= "  \"WebsiteName\": {\n";
        $promptText .= "    \"assets\": {\n";
        $promptText .= "      \"css\": {\n";
        $promptText .= "        \"global.css\": ''\n";
        $promptText .= "      },\n";
        $promptText .= "      \"js\": {\n";
        $promptText .= "        \"global.js\": '',\n";
        $promptText .= "        \"home.js\": '',\n";
        $promptText .= "        \"about.js\": ''\n";
        $promptText .= "      },\n";
        $promptText .= "      \"images\": {},\n";
        $promptText .= "      \"fonts\": {}\n";
        $promptText .= "    },\n";
        $promptText .= "    \"includes\": {\n";
        $promptText .= "      \"header.php\": '',\n";
        $promptText .= "      \"footer.php\": '',\n";
        $promptText .= "      \"home_template\": {\n";
        $promptText .= "        \"hero_home.php\": '',\n";
        $promptText .= "        \"features_home.php\": '',\n";
        $promptText .= "        \"testimonial_home.php\": ''\n";
        $promptText .= "      },\n";
        $promptText .= "      \"about_template\": {\n";
        $promptText .= "        \"hero_about.php\": '',\n";
        $promptText .= "        \"team_about.php\": ''\n";
        $promptText .= "      }\n";
        $promptText .= "    },\n";
        $promptText .= "    \"pages\": {\n";
        $promptText .= "      \"index.php\": '',\n";
        $promptText .= "      \"pagename.php\": '',\n";
        $promptText .= "    }\n";
        $promptText .= "  }\n";
        $promptText .= "}\n";
        $promptText .= "```\n";

        // error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        // error_log("prompt::==".$promptText);
        // error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }
    
    private function singlePagePrompt() {
        $promptText = "You are an AI-powered website generator. Your task is to create a single page for a website using HTML, PHP, JavaScript, and Tailwind.\n\n";
        
        // Add single page specific instructions
        $promptText .= "---\n\n";
        $promptText .= "Page Details\n";
        $promptText .= "- Page Name: {$this->formData['pageName']}\n";
        $promptText .= "- Page Purpose: {$this->formData['pagePurpose']}\n";
        $promptText .= "- Content Sections: {$this->formData['contentSections']}\n\n";
        
        // Add more single page specific instructions as needed
        
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log("single page prompt::==".$promptText);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }
    
    private function subPrompt() {
        $promptText = "You are an AI-powered component generator. Your task is to create a specific component or section for a website using HTML, PHP, JavaScript, and Tailwind.\n\n";
        
        // Add sub-prompt specific instructions
        $promptText .= "---\n\n";
        $promptText .= "Component Details\n";
        $promptText .= "- Component Type: {$this->formData['componentType']}\n";
        $promptText .= "- Component Purpose: {$this->formData['componentPurpose']}\n";
        $promptText .= "- Design Style: {$this->formData['designStyle']}\n\n";
        
        // Add more component specific instructions as needed
        
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log("sub prompt::==".$promptText);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }
    
    /**
     * Builds the request data structure for the API call
     * 
     * @param string $promptText The prompt text to send to the API
     * @return array Structured data for the API request
     */
    private function buildRequestData($promptText) {
        return [
            'contents'         => [
                [
                    'role'  => 'user',
                    'parts' => [
                        ['text' => $promptText]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'      => 2,
                'topK'             => 64,
                'topP'             => 0.95,
                'maxOutputTokens'  => 65536,
                'responseMimeType' => 'text/plain'
            ]
        ];
    }
    
    /**
     * Makes the API call to the AI service
     * 
     * @param array $data Request data to send to the API
     * @return array Processed API response
     */
    private function makeApiCall($data) {
        $url = API_URL . '?key=' . API_KEY;
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = json_decode($response, true);
        $responseText = $response['candidates'][0]['content']['parts']['0']['text'];
        
        // Process response based on prompt type
        return $this->processResponse($responseText, $httpCode);
    }
    
    /**
     * Processes the API response based on prompt type
     * 
     * @param string $responseText The text response from the API
     * @param int $httpCode HTTP status code from the API call
     * @return array Processed response data
     */
    private function processResponse($responseText, $httpCode) {
        switch ($this->promptType) {
            case 'master':
                return $this->processMasterResponse($responseText, $httpCode);
            case 'singlePage':
                return $this->processSinglePageResponse($responseText, $httpCode);
            case 'subPrompt':
                return $this->processSubPromptResponse($responseText, $httpCode);
            default:
                return $this->processMasterResponse($responseText, $httpCode);
        }
    }
    
    /**
     * Processes the response for master prompt type
     * Creates website structure from JSON response
     * 
     * @param string $responseText The text response from the API
     * @param int $httpCode HTTP status code from the API call
     * @return array Processed response data
     */
    private function processMasterResponse($responseText, $httpCode) {
        // Use the structure generator to create files and folders
        require_once __DIR__ . '/structure_generator.php';
        $generator = new StructureGenerator(USER_WEBSITES);
        // $generator->createFromJson($responseText);

        //clean resonce to json before further process
        $responseText = $generator->cleanJson($responseText);

        // Extract sections for the home page using JsonExtractor
        require_once __DIR__ . '/json_extractor.php';
        $extractor = new JsonExtractor();
        $pageName = 'home'; // Default to home page
        $result = $extractor->extractSections($responseText, $pageName);
        
        // Log the extracted sections and filtered JSON
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log("Extracted sections for page '{$pageName}':");
        error_log(print_r($result['sections'], true));
        error_log("\nFiltered JSON structure:");
        error_log(json_encode($result['filteredJson'], JSON_PRETTY_PRINT));
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        
        return [
            'code' => $httpCode,
            'data' => $generator->cleanJson($responseText)
        ];
    }
    
    /**
     * Processes the response for single page prompt type
     * 
     * @param string $responseText The text response from the API
     * @param int $httpCode HTTP status code from the API call
     * @return array Processed response data
     */
    private function processSinglePageResponse($responseText, $httpCode) {
        // Process single page response
        // You can create a specific handler for single page responses
        return [
            'code' => $httpCode,
            'data' => $responseText,
            'type' => 'singlePage'
        ];
    }
    
    /**
     * Processes the response for sub-prompt type
     * 
     * @param string $responseText The text response from the API
     * @param int $httpCode HTTP status code from the API call
     * @return array Processed response data
     */
    private function processSubPromptResponse($responseText, $httpCode) {
        // Process sub-prompt response
        // You can create a specific handler for sub-prompt responses
        return [
            'code' => $httpCode,
            'data' => $responseText,
            'type' => 'subPrompt'
        ];
    }
}

/**
 * Wrapper function to maintain backward compatibility
 * 
 * @param array $formData Form data containing website generation parameters
 * @param string $promptType Type of prompt to use (default: 'master')
 * @return array Response data from the API call
 */
function handleApiRequest($formData, $promptType = 'master') {
    $handler = new ApiHandler($formData, $promptType);
    return $handler->handleRequest();
}
