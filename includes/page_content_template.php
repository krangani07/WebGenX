<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-700 capitalize"><?php echo $currentPage; ?> Page</h2>
    
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Page Name:</label>
        <input type="text" value="<?php echo $currentPage; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
    </div>
    
    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Sections:</label>
        <ul id="sections-<?php echo $currentPage; ?>" class="space-y-2 sections-list">
            <?php if (isset($pageData['sections']) && is_array($pageData['sections'])): ?>
                <?php foreach ($pageData['sections'] as $index => $section): ?>
                    <li class="flex items-center border border-gray-300 rounded-md bg-gray-50 p-3" data-index="<?php echo $index; ?>">
                        <div class="cursor-move mr-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                                <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="<?php echo $currentPage; ?>[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($section); ?>" class="flex-grow px-3 py-2 border border-gray-300 rounded-md">
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-gray-500">No sections found for this page.</li>
            <?php endif; ?>
        </ul>
        <input type="hidden" name="<?php echo $currentPage; ?>_order" id="<?php echo $currentPage; ?>-order" value="<?php echo implode(',', array_keys($pageData['sections'] ?? [])); ?>">
        
        <!-- Add Section Button -->
        <div class="mt-4">
            <button type="button" class="add-section-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm flex items-center" data-page="<?php echo $currentPage; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Section
            </button>
        </div>
    </div>
    
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Folder Structure:</label>
        <div class="border border-gray-300 rounded-md bg-gray-50 p-4 h-64 overflow-auto">
            <pre class="text-sm text-gray-700 whitespace-pre-wrap"><?php 
                // Format the JSON structure for better readability
                if (isset($pageData['pagefolderstructure'])) {
                    $structure = json_decode($pageData['pagefolderstructure'], true);
                    if ($structure) {
                        echo formatFolderStructure($structure);
                    } else {
                        echo htmlspecialchars($pageData['pagefolderstructure']);
                    }
                }
            ?></pre>
        </div>
    </div>
</div>

<?php
/**
 * Format the folder structure as a tree
 * @param array $structure The folder structure array
 * @param int $level The current indentation level
 * @return string Formatted folder structure
 */
function formatFolderStructure($structure, $level = 0) {
    $output = '';
    $indent = str_repeat('    ', $level);
    
    foreach ($structure as $key => $value) {
        if (is_array($value)) {
            $output .= $indent . '📁 ' . htmlspecialchars($key) . "\n";
            $output .= formatFolderStructure($value, $level + 1);
        } else {
            $output .= $indent . '📄 ' . htmlspecialchars($key) . "\n";
        }
    }
    
    return $output;
}
?>