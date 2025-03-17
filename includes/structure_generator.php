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
}
