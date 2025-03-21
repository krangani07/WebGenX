document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable for the sections
    new Sortable(document.getElementById('sortable-sections'), {
        animation: 150,
        ghostClass: 'bg-blue-100',
        onEnd: function(evt) {
            // Send the new order to the server
            const sectionIds = Array.from(evt.from.children).map(item => 
                item.getAttribute('data-section-index')
            );
            
            updateSectionOrder(sectionIds);
        }
    });
    
    // Add folder toggle functionality
    document.querySelectorAll('.folder-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const ul = this.nextElementSibling;
            const isHidden = ul.classList.contains('hidden');
            ul.classList.toggle('hidden');
            
            // Toggle folder icon based on the folder's current state
            const icon = this.querySelector('i');
            if (isHidden) {
                // Folder is being opened
                icon.classList.remove('fa-folder');
                icon.classList.add('fa-folder-open');
            } else {
                // Folder is being closed
                icon.classList.remove('fa-folder-open');
                icon.classList.add('fa-folder');
            }
        });
    });
    
    // Set up delete modal handlers
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
    });
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const sectionIndex = this.getAttribute('data-section-index');
        deleteSection(sectionIndex);
        document.getElementById('deleteModal').classList.add('hidden');
    });
});

// Function to update section order
function updateSectionOrder(sectionIds) {
    const currentPage = document.querySelector('meta[name="current-page"]').getAttribute('content');
    
    // Send the new order to the server using fetch
    fetch('update_section_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            page: currentPage,
            sections: sectionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success notification could be added here
        } else {
            alert('Error updating section order: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating section order');
    });
}

// Function to confirm section deletion
function confirmDeleteSection(sectionIndex, sectionName) {
    document.getElementById('deleteSectonName').textContent = sectionName;
    document.getElementById('confirmDelete').setAttribute('data-section-index', sectionIndex);
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Function to delete a section
function deleteSection(sectionIndex) {
    const currentPage = document.querySelector('meta[name="current-page"]').getAttribute('content');
    
    fetch('delete_section.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            page: currentPage,
            section_index: sectionIndex
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to reflect changes
            window.location.reload();
        } else {
            alert('Error deleting section: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the section');
    });
}

// Function to edit a section
function editSection(sectionPath) {
    const currentPage = document.querySelector('meta[name="current-page"]').getAttribute('content');
    window.location.href = 'edit_section.php?page=' + currentPage + '&section=' + encodeURIComponent(sectionPath);
}

// Add this function to your existing page_editor.js file
// Function to generate page (updated path)
function generatePage(pageName) {
    if (!pageName) {
        alert('Page name is required');
        return;
    }
    
    // Show loading indicator
    const generateBtn = document.getElementById('generatePageBtn');
    const originalText = generateBtn.innerHTML;
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
    generateBtn.disabled = true;
    
    // Create the request data
    const requestData = {
        page: pageName
    };
    
    // Log the data being sent to the server
    console.log('Sending data to generate_page.php:', requestData);
    
    // Send the request to generate the page (updated path)
    fetch('../generate/generate_page.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        // Log the response from the server
        console.log('Response from generate_page.php:', data);
        
        if (data.success) {
            // Redirect to the view generated page
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + data.message);
            // Reset the button
            generateBtn.innerHTML = originalText;
            generateBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating the page. Please try again.');
        // Reset the button
        generateBtn.innerHTML = originalText;
        generateBtn.disabled = false;
    });
}