<?php
/**
 * Parses an AI response with code blocks and saves each section to its respective file.
 *
 * @param string $aiResponse Full AI response containing multiple code blocks.
 * @return array List of saved files or errors.
 */
function saveSectionsFromAIResponse(string $aiResponse): array {
    $results = [];
    $websiteName = '';
    
    // First, try to extract the JSON structure
    if (preg_match('/```json\s*(.*?)\s*```/s', $aiResponse, $jsonMatch)) {
        $jsonData = json_decode(trim($jsonMatch[1]), true);
        
        if ($jsonData && is_array($jsonData)) {
            // Get the website name (first key in the JSON)
            $websiteName = array_key_first($jsonData);
            $results[] = "📂 Found website structure for: $websiteName";
            
            // Create the folder structure based on the JSON
            createFolderStructure($jsonData, $results);
        } else {
            $results[] = "❌ Error parsing JSON structure";
        }
    }
 
    preg_match_all('/```(?:php|javascript|css)\s*(?:(?:<\?php \/\/|\/\/|\/\*) (.*?)(?:\?>|\s*)|<!-- (.*?) -->)\s*(.*?)```/s', $aiResponse, $matches, PREG_SET_ORDER);
    
    if (empty($matches)) {
        $results[] = '⚠️ No valid code blocks found.';
    } else {
        foreach ($matches as $match) {
            // Get the file path from either the first or second capturing group
            $filePath = !empty($match[1]) ? trim($match[1]) : trim($match[2]);
            $codeContent = trim($match[3]);
            
            // Prepend the website name if it exists and the path doesn't already include it
            if (!empty($websiteName) && strpos($filePath, $websiteName) === false) {
                // Check if the path starts with a directory that exists in the JSON structure
                $firstDir = explode('/', $filePath)[0];
                if (in_array($firstDir, ['assets', 'includes', 'pages'])) {
                    $filePath = $websiteName . '/' . $filePath;
                }
            }
            
            try {
                // Create directory if it doesn't exist
                $dirPath = dirname($filePath);
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
                
                // Save the code to the file
                file_put_contents($filePath, $codeContent);
                $results[] = "✅ Saved: $filePath";
            } catch (Exception $e) {
                $results[] = "❌ Error saving $filePath: " . $e->getMessage();
            }
        }
    }
    
    return $results;
}

/**
 * Creates folder structure based on JSON data
 * 
 * @param array $structure The JSON structure
 * @param array &$results Results array to append messages
 * @param string $basePath Base path for creating directories
 */
function createFolderStructure(array $structure, array &$results, string $basePath = '') {
    foreach ($structure as $key => $value) {
        $currentPath = $basePath ? $basePath . '/' . $key : $key;
        
        if (is_array($value)) {
            // Create directory
            if (!is_dir($currentPath)) {
                mkdir($currentPath, 0777, true);
                $results[] = "📁 Created directory: $currentPath";
            }
            
            // Recursively create subdirectories
            createFolderStructure($value, $results, $currentPath);
        } else {
            // This is a file, create an empty file if it doesn't exist
            if (!file_exists($currentPath) && !empty($key)) {
                $dirPath = dirname($currentPath);
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
                
                file_put_contents($currentPath, '');
                $results[] = "📄 Created empty file: $currentPath";
            }
        }
    }
}
