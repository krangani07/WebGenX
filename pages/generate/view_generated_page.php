<?php
session_start();
include '../../includes/header.php';

// Check if there's generated page data in the session
if (!isset($_SESSION['generated_page']) || empty($_SESSION['generated_page'])) {
    echo '<div class="container mx-auto px-4 py-6 text-center">';
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
    echo '<p>No generated page data found. Please generate a page first.</p>';
    echo '</div>';
    echo '<div class="mt-4"><a href="../page_and_sections/page_editor.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back to Page Editor</a></div>';
    echo '</div>';
    include '../../includes/footer.php';
    exit;
}

$generatedPage = $_SESSION['generated_page'];
$pageName = $generatedPage['name'];
$response = $generatedPage['response'];
?>

<!-- Main Content Area -->
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold mb-4">Generated Page: <?php echo ucfirst($pageName); ?></h1>
        
        <div class="mb-4">
            <a href="../page_and_sections/page_editor.php?page=<?php echo $pageName; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Editor
            </a>
            <button id="savePageBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center ml-2">
                <i class="fas fa-save mr-2"></i> Save Page
            </button>
        </div>
        
        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
            <h2 class="text-xl font-semibold mb-2">Generated Content</h2>
            
            <?php if (isset($response['code']) && $response['code'] == 200): ?>
                <div class="bg-white p-4 rounded border border-gray-300 overflow-auto max-h-96">
                    <pre class="text-sm whitespace-pre-wrap"><?php echo htmlspecialchars($response['data']); ?></pre>
                </div>
            <?php else: ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p>Error generating page content. Please try again.</p>
                    <?php if (isset($response['data'])): ?>
                        <pre class="text-sm mt-2 whitespace-pre-wrap"><?php echo htmlspecialchars($response['data']); ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- After the generated content section -->
<div class="border border-gray-300 rounded-lg p-4 bg-gray-50 mt-4">
    <h2 class="text-xl font-semibold mb-2">Debug Information</h2>
    <div class="bg-white p-4 rounded border border-gray-300 overflow-auto max-h-96">
        <h3 class="font-bold">Form Data Sent to API:</h3>
        <pre class="text-sm whitespace-pre-wrap"><?php echo htmlspecialchars(print_r($generatedPage['formData'] ?? [], true)); ?></pre>
    </div>
</div>

<script>
document.getElementById('savePageBtn').addEventListener('click', function() {
    // Add functionality to save the generated page
    alert('Save functionality will be implemented here');
    // You can add AJAX call to save the page content to files
});
</script>

<?php include '../../includes/footer.php'; ?>