<?php
/**
 * Extract file path from PHP comment and save content to that path in the project structure
 * 
 * @param string $response The content containing PHP code with file path in comment
 * @param string $websiteName The base directory of the project (optional)
 * @return bool True if the operation was successful, false otherwise
 */
function extractAndSaveContent($response, $websiteName) {
    // Skip JSON code blocks
    $response = preg_replace('/```json\s*(.*?)\s*```/s', '', $response);
    
    $results = [];
    $languages = ['php', 'javascript', 'css'];
    
    foreach ($languages as $language) {
        // Find all code blocks of the current language
        $pattern = '/```' . $language . '\s*(.*?)\s*```/s';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $codeContent = $match[1];
                
                // Extract file path from comment based on language
                $filePath = null;
                if ($language === 'php' && preg_match('/^\s*<\?php\s*\/\/\s*([^\s]+)/', $codeContent, $pathMatch)) {
                    $filePath = trim($pathMatch[1]);
                    // Do NOT remove the file path comment for PHP
                } elseif ($language === 'javascript' && preg_match('/^\s*\/\/\s*([^\s]+)/', $codeContent, $pathMatch)) {
                    $filePath = trim($pathMatch[1]);
                    // Do NOT remove the file path comment for JavaScript
                } elseif ($language === 'css' && preg_match('/^\s*\/\*\s*([^\s]+)\s*\*\//', $codeContent, $pathMatch)) {
                    $filePath = trim($pathMatch[1]);
                    // Do NOT remove the file path comment for CSS
                }
                
                if ($filePath) {
                    require_once __DIR__ . '/../config/config.php';
                    $fullPath = USER_WEBSITES . '/' . $websiteName . '/' . $filePath;
                    
                    // Create directory if it doesn't exist
                    $directory = dirname($fullPath);
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                        $results[] = "📁 Created directory: $directory";
                    }
                    
                    // Save the content to the file WITHOUT removing the file path comment
                    if (file_put_contents($fullPath, trim($codeContent))) {
                        $results[] = "✅ Saved $language file: $filePath";
                        error_log("Successfully saved $language file to: $fullPath");
                    } else {
                        $results[] = "❌ Failed to save $language file: $filePath";
                        error_log("Failed to save $language file to: $fullPath");
                    }
                } else {
                    $results[] = "⚠️ No file path found in $language code block";
                    error_log("No file path found in $language code block");
                }
            }
        }
    }
    
    // Log all results
    foreach ($results as $result) {
        error_log($result);
    }
    
    return [
        'success' => !empty($results),
        'message' => !empty($results) ? "Processed " . count($results) . " code blocks" : "No code blocks processed",
        'details' => $results
    ];
}

?>