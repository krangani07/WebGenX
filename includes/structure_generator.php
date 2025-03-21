<?php
/**
 * Structure Generator
 * 
 * Creates files and folders based on a JSON structure
 */

class StructureGenerator {
    private $basePath;
    
    /**
     * Constructor
     * 
     * @param string $basePath Base path where files will be created
     */
    public function __construct($basePath = null) {
        require_once __DIR__ . '/../config/config.php';
        if ($basePath === null) {
            $this->basePath = USER_WEBSITES;
        } else {
            $this->basePath = $basePath;
        }
    }
    
    /**
     * Create structure from JSON string
     * 
     * @param string $jsonString JSON structure as string
     * @return bool Success status
     */
    public function createFromJson($jsonString) {
        // Clean JSON if it contains markdown code block markers
        $jsonString = $this->cleanJson($jsonString);
        
        // Parse JSON
        $structure = json_decode($jsonString, true);
        
        if ($structure === null) {
            error_log("Error parsing JSON: " . json_last_error_msg());
            return false;
        }
        
        // Create the structure
        $this->createStructure($this->basePath, $structure);
        error_log("Structure creation completed successfully");
        return true;
    }
    
    /**
     * Clean JSON string by removing markdown code block markers
     * 
     * @param string $jsonString JSON string potentially with markdown markers
     * @return string Clean JSON string
     */
    public function cleanJson($jsonString) {
        // Check if the JSON is wrapped in markdown code block
        if (preg_match('/```json\s*(.*?)\s*```/s', $jsonString, $matches)) {
            return $matches[1];
        }
        
        return $jsonString;
    }
    
    /**
     * Recursively create folders and files
     * 
     * @param string $basePath Current base path
     * @param array $structure Structure to create
     */
    private function createStructure($basePath, $structure) {
        foreach ($structure as $name => $content) {
            $path = $basePath . '/' . $name;
            
            if (is_array($content)) {
                // Create directory
                if (!file_exists($path)) {
                    if (mkdir($path, 0777, true)) {
                        error_log("Created directory: $path");
                    } else {
                        error_log("Failed to create directory: $path");
                    }
                }
                
                // Process contents recursively if not empty
                if (!empty($content)) {
                    $this->createStructure($path, $content);
                }
            } else {
                // Create file
                if (!file_exists($path)) {
                    if (touch($path)) {
                        error_log("Created file: $path");
                    } else {
                        error_log("Failed to create file: $path");
                    }
                }
            }
        }
    }
    
    /**
     * Extract code blocks from a string
     * 
     * @param string $content String containing code blocks
     * @return array Array of extracted code blocks with language and content
     */
    public function extractCodeBlocks($content) {
        $codeBlocks = [];
        $languages = ['php', 'css', 'js', 'javascript', 'json', 'html'];
        
        // Pattern to match code blocks with language identifier
        $pattern = '/```(' . implode('|', $languages) . ')\s*(.*?)\s*```/s';
        
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $language = $match[1];
                $code = trim($match[2]);
                
                // Normalize language name
                if ($language === 'js') {
                    $language = 'javascript';
                }
                
                // Extract file path using multiple patterns
                $filePath = null;
                
                // First line patterns for different comment styles
                $firstLine = strtok($code, "\n");
                
                if (preg_match('/^\s*<\?php\s*\/\/\s*(.*?)\s*(\?>)?/', $firstLine, $pathMatch)) {
                    // PHP comment: <?php // includes/header.php /?/>
                    $filePath = trim($pathMatch[1]);
                } elseif (preg_match('/^\s*\/\/\s*(.*?)$/', $firstLine, $pathMatch)) {
                    // JS comment: // assets/js/global.js
                    $filePath = trim($pathMatch[1]);
                } elseif (preg_match('/^\s*\/\*\s*(.*?)\s*\*\//', $firstLine, $pathMatch)) {
                    // CSS comment: /* assets/css/global.css */
                    $filePath = trim($pathMatch[1]);
                } elseif (preg_match('/^\s*<!--\s*(.*?)\s*-->/', $firstLine, $pathMatch)) {
                    // HTML comment: <!-- includes/header.php -->
                    $filePath = trim($pathMatch[1]);
                }
                
                // If file path found, remove the first line from the code
                if ($filePath) {
                    // Remove the first line (file path comment)
                    $lines = explode("\n", $code);
                    array_shift($lines);
                    $code = trim(implode("\n", $lines));
                }
                
                // Debug logging
                error_log("Extracted code block: Language=$language, FilePath=" . ($filePath ? $filePath : "NONE"));
                
                $codeBlocks[] = [
                    'language' => $language,
                    'content' => $code,
                    'file_path' => $filePath
                ];
            }
        }
        
        return $codeBlocks;
    }
    
    /**
     * Save extracted code blocks to files
     * 
     * @param array $codeBlocks Array of code blocks
     * @param string $websiteName Name of the website (for path prefixing)
     * @return array Results of the save operation
     */
    public function saveCodeBlocks($codeBlocks, $websiteName = '') {
        $results = [];
        
        foreach ($codeBlocks as $block) {
            if (empty($block['file_path'])) {
                $results[] = "⚠️ Skipped code block with no file path";
                continue;
            }
            
            $filePath = $block['file_path'];
            
            // Prepend website name if provided and not already included
            if (!empty($websiteName) && strpos($filePath, $websiteName) === false) {
                $firstDir = explode('/', $filePath)[0];
                if (in_array($firstDir, ['assets', 'includes', 'pages'])) {
                    $filePath = $websiteName . '/' . $filePath;
                }
            }
            
            // Prepend base path
            $fullPath = $this->basePath . '/' . $filePath;
            
            try {
                // Create directory if it doesn't exist
                $dirPath = dirname($fullPath);
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                    $results[] = "📁 Created directory: $dirPath";
                }
                
                // Add PHP opening tag for PHP files if not present
                $content = $block['content'];
                if (pathinfo($filePath, PATHINFO_EXTENSION) === 'php' && strpos($content, '<?php') === false) {
                    $content = "<?php\n" . $content;
                }
                
                // Save the code to the file
                file_put_contents($fullPath, $content);
                $results[] = "✅ Saved: $filePath";
            } catch (Exception $e) {
                $results[] = "❌ Error saving $filePath: " . $e->getMessage();
            }
        }
        
        return $results;
    }
}
