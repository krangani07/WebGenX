<?php
require_once __DIR__ . '/../config/config.php';

/**
 * ApiHandler Class
 *
 * Handles API requests to generate website content using AI.
 * Supports different types of prompts for various generation scenarios.
 */
class ApiHandler
{
    /**
     * @var array $formData Form data containing website generation parameters
     */
    private $formData;

    /**
     * @var string $promptType Type of prompt to use (master, singlePage, subPrompt)
     */
    private $promptType;

    /**
     * Constructor for ApiHandler
     *
     * @param array $formData Form data containing website generation parameters
     * @param string $promptType Type of prompt to use (default: 'master')
     */
    public function __construct($formData, $promptType = 'master')
    {
        $this->formData   = $formData;
        $this->promptType = $promptType;
    }

    /**
     * Handles the API request process
     *
     * @return array Response data from the API call
     */
    public function handleRequest()
    {
        $promptText = $this->generatePrompt();
        $data       = $this->buildRequestData($promptText);
        return $this->makeApiCall($data);
    }

    /**
     * Generates the appropriate prompt based on promptType
     *
     * @return string Generated prompt text
     */
    private function generatePrompt()
    {
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

    private function masterPrompt()
    {
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
        $promptText .= "- each page should have section releated to the page and try add minimum4 to 5  setcion for single page \n\n";
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
        $promptText .= "- Each page should be modular with separate section files\n";
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
        $promptText .= "9. Response Format (imp)\n";
        $promptText .= "- First, provide the file and folder structure in a JSON format code block\n";
        $promptText .= "- **for home page and home sections use strictly 'index'.** \n";
        $promptText .= "- Do not use the any name for the sections and pages. and  section name should be logical and related to page name\n\n";
        $promptText.= "- strictly use name of the section like sectionname and then pagename. \n";
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
        $promptText .= "        \"[pageName].js\": '',\n";
        $promptText .= "        \"[othePageName].js\": ''\n";
        $promptText .= "      },\n";
        $promptText .= "      \"images\": {},\n";
        $promptText .= "      \"fonts\": {}\n";
        $promptText .= "    },\n";
        $promptText .= "    \"includes\": {\n";
        $promptText .= "      \"header.php\": '',\n";
        $promptText .= "      \"footer.php\": '',\n";
        $promptText .= "      \"[PageName]_template\": {\n";
        $promptText .= "        \"[sectionName]_[pageName].php\": '',\n";
        $promptText .= "      },\n";
        $promptText .= "      \"[otherPageName]_template\": {\n";
        $promptText .= "        \"[sectionName]_[pageName].php\": '',\n";
        $promptText .= "      }\n";
        $promptText .= "    },\n";
        $promptText .= "    \"pages\": {\n";
        $promptText .= "      \"index.php\": '',\n";
        $promptText .= "      \"[pageName].php\": '',\n";
        $promptText .= "    }\n";
        $promptText .= "  }\n";
        $promptText .= "}\n";
        $promptText .= "```\n";

        $promptText .= "First, generate the full folder and file structure in a JSON code block, following the format previously described.\n";
        $promptText .= "Immediately after the JSON structure, generate code for header.php, footer.php, assets/js/global.js, and assets/css/global.css in separate code blocks.\n";
        $promptText .= "The header should be sticky, responsive, and include a hamburger menu toggle for mobile view.\n";
        $promptText .= "Use Tailwind CSS for layout and style. Include a placeholder logo (https://placehold.co/100x40/000000/FFF.webp) and nav links in '../pages/pagename.php' format.\n";
        $promptText .= "Footer should include © 2025 {$this->formData['websiteName']}. All rights reserved. Add placeholder social icons.\n";
        $promptText .= "Add interactivity using global.js — toggle the mobile menu. If CSS is needed for layout tweaks, put it in global.css.\n";
        $promptText .= "also dont forgot to include the necesary cdns and respouces link in all the files \n";

        $promptText .= "Strictly follow this output format with no extra explanations:\n";
        $promptText .= "```json\n";
        $promptText .= "{ ... full file/folder structure ... }\n";
        $promptText .= "```\n";
        $promptText .= "```php\n";
        $promptText .= "<?php // includes/header.php ?>\n";
        $promptText .= "<header>";
        $promptText .= "// header code here\n";
        $promptText .= "</header>";
        $promptText .= "```\n";
        $promptText .= "```php\n";
        $promptText .= "<?php // includes/footer.php ?>\n";
        $promptText .= "<footer>";
        $promptText .= "// footer code here\n";
        $promptText .= "</footer>";
        $promptText .= "```\n";
        $promptText .= "```javascript\n";
        $promptText .= "// assets/js/global.js\n";
        $promptText .= "// JS code here\n";
        $promptText .= "```\n";
        $promptText .= "```css\n";
        $promptText .= "/* assets/css/global.css */\n";
        $promptText .= "/* CSS code here */\n";
        $promptText .= "```\n";

        // error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        // error_log("prompt::==".$promptText);
        // error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }

    private function singlePagePrompt()
    {
        $promptText = "You are WebGenX, an expert AI web developer. Your job is to generate structured, scalable, interactive website code based on user specifications. Follow the format strictly. Do NOT generate extra text or explanation.\n\n";

        // General Guidelines
        $promptText .= "### General Guidelines:\n";
        $promptText .= "- Follow best practices in **HTML5, CSS3, JS (ES6+), and PHP 8.2+**.\n";
        $promptText .= "- Code must be **clean, modular**, and organized into **separate files**.\n";
        $promptText .= "- Use the existing **header.php** and **footer.php** for main {$this->formData['pageName']} (do not generate new ones).\n";
        $promptText .= "- All output must be **PHP code blocks**, with a comment at the top specifying the file path like this:\n";
        $promptText .= "  `<?php // includes/home_template/hero_home.php ?>`\n";
        $promptText .= "- Do not include inline styles or JS in PHP files unless essential.\n";
        $promptText .= "- Use **Tailwind CSS** for styling and **DaisyUI** for UI components.\n";
        $promptText .= "- Use **Heroicons 2** and **custom SVG icons** where needed.\n\n";

        $promptText .= "---\n\n";

        // Interactivity & Animation Requirements
        $promptText .= "### Interactivity & Animation Requirements (MANDATORY):\n";
        $promptText .= "- Add **at least one interactive feature per page**:\n";
        $promptText .= "  - Examples: dropdown menus, modals, accordions, sliders, dark mode toggle, animated counters, form validation.\n";
        $promptText .= "- Include **scroll-based animations** using **GSAP** or other libraries.\n";
        $promptText .= "- Animate section titles, images, call-to-actions, and feature blocks.\n";
        $promptText .= "- Use **hover effects** and **smooth transitions** for buttons, links, cards.\n";
        $promptText .= "- Implement **lazy loading** for images/videos for performance.\n";
        $promptText .= "- Write **page-specific JS files** for each page (e.g., `assets/js/home.js`) and a shared `main.js` for global features.\n";
        $promptText .= "- Add **script tags** in the generated page files to include relevant JS.\n\n";

        $promptText .= "---\n\n";

        // JavaScript Libraries
        $promptText .= "### JavaScript Libraries Allowed (Pick based on use case):\n";
        $promptText .= "- **GSAP** - Advanced animations\n";
        $promptText .= "- **AOS.js** - Easy scroll animations\n";
        $promptText .= "- **Anime.js** - Lightweight animations\n";
        $promptText .= "- **Swiper.js** - Sliders and carousels\n";
        $promptText .= "- **Chart.js** - Graphs (if needed)\n";
        $promptText .= "- **Vanilla JS (ES6)** - For simple interactivity\n\n";

        $promptText .= "---\n\n";

        // Folder & File Structure Format
        $promptText .= "### Folder & File Structure Format (JSON):\n";
        $promptText .= "- Generate a **JSON-formatted file/folder tree** first.\n";
        $promptText .= "- Organize assets: `/assets/js/`, `/assets/css/`, `/assets/images/`, `/assets/fonts/`.\n";
        $promptText .= "- Include PHP files in `/pages/` and partials in `/includes/`.\n\n";
        $promptText .= " - Example format:\n\n";
        $promptText .= " ```json\n";
        $promptText .= "   {\n";
        $promptText .= "  \"WebsiteName\": {\n";
        $promptText .= "    \"assets\": {\n";
        $promptText .= "      \"css\": {\n";
        $promptText .= "        \"global.css\": \"\",\n";
        $promptText .= "        \"[pagename].css\": \"\", // if needed\n";
        $promptText .= "        // other css files\n";
        $promptText .= "      },\n";
        $promptText .= "      \"js\": {\n";
        $promptText .= "        \"global.js\": \"\",\n";
        $promptText .= "        \"[pagename].js\": \"\",\n";
        $promptText .= "        // other pages js file\n";
        $promptText .= "      },\n";
        $promptText .= "      \"images\": {},\n";
        $promptText .= "      \"fonts\": {}\n";
        $promptText .= "    },\n";
        $promptText .= "    \"includes\": {\n";
        $promptText .= "      \"header.php\": \"\",\n";
        $promptText .= "      \"footer.php\": \"\",\n";
        $promptText .= "      \"[pagename]_template\": {\n";
        $promptText .= "        \"[sectionname]_[pagename].php\": \"\",\n";
        $promptText .= "        // list all thee section used for the [pagename] \n";
        $promptText .= "      },\n";
        $promptText .= "      //  other page templets folders \n";
        $promptText .= "    },\n";
        $promptText .= "    \"pages\": {\n";
        $promptText .= "      \"index.php\": \"\",\n";
        $promptText .= "      // oterh pages php files\n";
        $promptText .= "    }\n";
        $promptText .= "  }\n";
        $promptText .= "}\n";
        $promptText .= " ```\n";

        $promptText .= "---\n\n";

        // Output Format
        $promptText .= "### Output Format:\n";
        $promptText .= "1. First, output the **JSON file structure**.\n";
        $promptText .= "2. Then, output the **PHP code blocks** for each page and section.\n";
        $promptText .= "3. Then, output the **JavaScript code blocks**, one per file:\n";
        $promptText .= "   - Use `<?php // assets/js/home.js ?>` at the top of each block.\n";
        $promptText .= "   - Include page-specific interactions and animations.\n";
        $promptText .= "   - Use event listeners like `DOMContentLoaded`.\n\n";

        $promptText .= "---\n\n";

        $promptText .= " ```code\n";
        $promptText .= "    <!-- /WebsiteName or root foldername/includes/PageName_template/SectionName_PageName.php -->\n";
        $promptText .= "    // code for the block\n";
        $promptText .= "```\n\n";

        // Code Quality
        $promptText .= "### Code Quality:\n";
        $promptText .= "- Maintain consistent naming for classes, ids, and file names.\n";
        $promptText .= "- Follow semantic HTML structure.\n";
        $promptText .= "- Avoid unnecessary code repetition.\n";
        $promptText .= "- Optimize for performance (minified assets, async loading).\n\n";

        // Final Instructions
        $promptText .= "### Final Instructions:\n";
        $promptText .= "- Do NOT generate explanatory text or notes.\n";
        $promptText .= "- Generate code only inside PHP blocks with proper file paths.\n";
        $promptText .= "- Keep code modular, clean, and fully interactive.\n\n";

        $promptText .= "---\n\n";

        // Website-specific information
        $promptText .= "General Info:\n";
        $promptText .= "- Website Name: {$this->formData['websiteName']}\n";
        $promptText .= "- Page Name: {$this->formData['pageName']}\n";
        $promptText .= '- Website Purpose: ' . ($this->formData['websiteType'] ?? 'Business') . "\n";
        $promptText .= '- Description: ' . ($this->formData['description'] ?? '') . "\n";
        $promptText .= '- Color Scheme: ' . ($this->formData['colorScheme'] ?? 'blue for hover effects, black or dark theme, white, skyblue for text') . "\n";
        $promptText .= '- Typography: ' . ($this->formData['typography'] ?? 'modern') . "\n";
        $promptText .= "- Folder Path for Sections: includes/{$this->formData['pageName']}_template/\n\n";

        $promptText .= "---\n\n";

        // Sections for this page
        $promptText .= "Sections for this page (generate each as a **separate PHP file** with only the section code):\n";
        if (!empty($this->formData['sections'])) {
            foreach ($this->formData['sections'] as $sectionId => $section) {
                $sectionName = is_array($section) ? $section['name'] : $section;
                // Use the exact section name from the session without modification
                $promptText .= "- {$sectionName}\n";
            }
        }

        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log('single page prompt::==' . $promptText);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }

    private function subPrompt()
    {
        $promptText = "You are an AI-powered component generator. Your task is to create a specific component or section for a website using HTML, PHP, JavaScript, and Tailwind.\n\n";

        // Add sub-prompt specific instructions
        $promptText .= "---\n\n";
        $promptText .= "Component Details\n";
        $promptText .= "- Component Type: {$this->formData['componentType']}\n";
        $promptText .= "- Component Purpose: {$this->formData['componentPurpose']}\n";
        $promptText .= "- Design Style: {$this->formData['designStyle']}\n\n";

        // Add more component specific instructions as needed

        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log('sub prompt::==' . $promptText);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        return $promptText;
    }

    /**
     * Builds the request data structure for the API call
     *
     * @param string $promptText The prompt text to send to the API
     * @return array Structured data for the API request
     */
    private function buildRequestData($promptText)
    {
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
    private function makeApiCall($data)
    {
        $url = API_URL . '?key=' . API_KEY;
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response     = json_decode($response, true);
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
    private function processResponse($responseText, $httpCode)
    {
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
    private function processMasterResponse($responseText, $httpCode)
    {
        // Use the structure generator to create files and folders
        require_once __DIR__ . '/structure_generator.php';
        $generator = new StructureGenerator(USER_WEBSITES);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        error_log('master response::=='. $responseText);
        error_log("\n-------------------------------------------------------------------------------------------------------------//\n");
        
        // Include the codesaver.php file
        require_once __DIR__ . '/../extras/codesaver.php';
        
        // Extract JSON data to get website name
        $jsonData = json_decode($generator->cleanJson($responseText), true);
        $websiteName = $jsonData ? array_key_first($jsonData) : 'custom_website';
        
        // Call the extractAndSaveContent function from codesaver.php
        $saveResult = extractAndSaveContent($responseText, $websiteName);
        error_log("Code extraction result: " . ($saveResult['success'] ? "Success" : "Failed") . " - " . $saveResult['message']);
        
        // Extract code blocks and save them to their respective files
        $codeBlocks = $generator->extractCodeBlocks($responseText);
        $saveResults = $generator->saveCodeBlocks($codeBlocks, $websiteName);
        
        // Log the results of code extraction
        foreach ($saveResults as $result) {
            error_log($result);
        }
        
        // Create the folder structure based on JSON
        $generator->createFromJson($responseText);
        
        // Clean response to json before further processing
        $responseText = $generator->cleanJson($responseText);
        // Create an array to store all pages and their required files
        $pageFiles = [];
        $jsonData = json_decode($responseText, true);

        // Rest of the method remains unchanged
        if ($jsonData) {
            $websiteName = array_key_first($jsonData);
            
            // Process pages directory
            if (isset($jsonData[$websiteName]['pages'])) {
                // Existing code continues...
                foreach ($jsonData[$websiteName]['pages'] as $pageFile => $content) {
                    // Handle home page (index.php) correctly
                    $pageName    = ($pageFile === 'index.php') ? 'home' : pathinfo($pageFile, PATHINFO_FILENAME);
                    $templateDir = ($pageName === 'home') ? 'index_template' : $pageName . '_template';

                    $pageFiles[$pageName] = [
                        'main_file'     => basename($pageFile),
                        'js_files'      => [],
                        'section_files' => [],
                        'dependencies'  => [
                            'header.php',
                            'footer.php'
                        ]
                    ];

                    // Add page-specific JS files
                    $jsFileName = ($pageName === 'home') ? 'index.js' : $pageName . '.js';
                    if (isset($jsonData[$websiteName]['assets']['js'][$jsFileName])) {
                        $pageFiles[$pageName]['js_files'][] = $jsFileName;
                    }

                    // Add global JS files
                    if (isset($jsonData[$websiteName]['assets']['js']['global.js'])) {
                        $pageFiles[$pageName]['js_files'][] = 'global.js';
                    }

                    // Add section template files
                    if (isset($jsonData[$websiteName]['includes'][$templateDir])) {
                        foreach ($jsonData[$websiteName]['includes'][$templateDir] as $sectionFile => $content) {
                            $sectionFileName                         = basename($sectionFile);
                            $pageFiles[$pageName]['section_files'][] = [
                                'name' => $sectionFileName,
                                'path' => $templateDir . '/' . $sectionFileName
                            ];

                            // Add section file to dependencies
                            $pageFiles[$pageName]['dependencies'][] = $templateDir . '/' . $sectionFileName;
                        }
                    }

                    // Add CSS dependencies
                    if (isset($jsonData[$websiteName]['assets']['css']['global.css'])) {
                        $pageFiles[$pageName]['dependencies'][] = 'assets/css/global.css';
                    }

                    // Add JS dependencies with full paths
                    foreach ($pageFiles[$pageName]['js_files'] as $jsFile) {
                        $pageFiles[$pageName]['dependencies'][] = 'assets/js/' . $jsFile;
                    }
                }
            }
        }

        return [
            'code'       => $httpCode,
            'data'       => $generator->cleanJson($responseText),
            'page_files' => $pageFiles
        ];
    }

    /**
     * Processes the response for single page prompt type
     *
     * @param string $responseText The text response from the API
     * @param int $httpCode HTTP status code from the API call
     * @return array Processed response data
     */
    private function processSinglePageResponse($responseText, $httpCode)
    {
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
    private function processSubPromptResponse($responseText, $httpCode)
    {
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
function handleApiRequest($formData, $promptType = 'master')
{
    $handler = new ApiHandler($formData, $promptType);
    return $handler->handleRequest();
}
