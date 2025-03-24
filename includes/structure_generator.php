<?php
/**
 * Structure Generator
 * 
 * Creates files and folders based on a JSON structure
 */

class StructureGenerator {
    private $basePath;
    private $logEntries = [];
    
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
            $this->logEntry("Error", "JSON Parsing", json_last_error_msg(), "❌");
            return false;
        }
        
        // Create the structure
        $this->createStructure($this->basePath, $structure);
        $this->logEntry("Success", "Structure", "Creation completed successfully", "✅");
        
        // Display the log table
        $this->displayLogTable();
        
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
                        $this->logEntry("Created", "Directory", $path, "📁");
                    } else {
                        $this->logEntry("Failed", "Directory", $path, "❌");
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
                        $this->logEntry("Created", "File", $path, "📄");
                    } else {
                        $this->logEntry("Failed", "File", $path, "❌");
                    }
                }
            }
        }
    }
    
    /**
     * Add a log entry
     * 
     * @param string $status Status of the operation
     * @param string $type Type of item (File, Directory, etc.)
     * @param string $path Path or message
     * @param string $icon Icon to display
     */
    private function logEntry($status, $type, $path, $icon) {
        $this->logEntries[] = [
            'status' => $status,
            'type' => $type,
            'path' => $path,
            'icon' => $icon
        ];
    }
    
    /**
     * Display log entries in a table format
     */
    private function displayLogTable() {
        if (empty($this->logEntries)) {
            error_log("┌──────────────────────────────────────────────────────────────┐");
            error_log("│ No operations performed                                      │");
            error_log("└──────────────────────────────────────────────────────────────┘");
            return;
        }
        
        error_log("┌──────────┬───────────┬────────────────────────────────────────────┐");
        error_log("│ Status   │ Type      │ Path/Message                               │");
        error_log("├──────────┼───────────┼────────────────────────────────────────────┤");
        
        foreach ($this->logEntries as $entry) {
            $status = str_pad($entry['status'], 8, ' ');
            $type = str_pad($entry['type'], 9, ' ');
            $path = $entry['path'];
            
            // Truncate path if too long
            if (strlen($path) > 39) {
                $path = "..." . substr($path, -36);
            }
            $path = str_pad($path, 39, ' ');
            
            error_log("│ {$status} │ {$type} │ {$entry['icon']} {$path} │");
        }
        
        error_log("└──────────┴───────────┴────────────────────────────────────────────┘");
        error_log("Total operations: " . count($this->logEntries));
    }
}
