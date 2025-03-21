document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality specific to the edit section page
    
    // Example: Add confirmation before leaving page with unsaved changes
    const form = document.querySelector('form');
    const originalContent = document.getElementById('section_content').value;
    
    window.addEventListener('beforeunload', function(e) {
        const currentContent = document.getElementById('section_content').value;
        if (currentContent !== originalContent && !form.submitted) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    form.addEventListener('submit', function() {
        form.submitted = true;
    });
});