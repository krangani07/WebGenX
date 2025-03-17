<?php
require_once __DIR__ . '/../config/config.php';

class ApiHandler {
    private $formData;
    
    public function __construct($formData) {
        $this->formData = $formData;
    }
    
    public function handleRequest() {
        $promptText = $this->buildPrompt();
        $data = $this->buildRequestData($promptText);
        return $this->makeApiCall($data);
    }
    
    private function buildPrompt() {
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

        error_log("\n/////////////////////////////////////////////////////////////////////////////////////////////////////\n");
        return $promptText;
    }
    
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
        
        // Use the structure generator to create files and folders
        require_once __DIR__ . '/structure_generator.php';
        $generator = new StructureGenerator(USER_WEBSITES);
        $generator->createFromJson($responseText);
        
        return [
            'code' => $httpCode,
            'data' => $generator->cleanJson($responseText)
        ];
    }
}

// Wrapper function to maintain backward compatibility
function handleApiRequest($formData) {
    $handler = new ApiHandler($formData);
    return $handler->handleRequest();
}
