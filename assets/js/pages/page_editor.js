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
    document.getElementById('deleteModal').classList.remove('hidden');
    
    // Set up the confirm delete button
    document.getElementById('confirmDelete').onclick = function() {
        // Add AJAX call to delete the section
        fetch('../../includes/section_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete&section_index=' + sectionIndex + '&page=' + document.querySelector('meta[name="current-page"]').getAttribute('content')
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // Try to parse as JSON, but handle text response as fallback
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error parsing JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            if (data.success) {
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the section: ' + error.message);
        });
        
        // Hide the modal
        document.getElementById('deleteModal').classList.add('hidden');
    };
    
    // Set up the cancel button
    document.getElementById('cancelDelete').onclick = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };
}

// Function to open the add section modal
function openAddSectionModal(pageName) {
    document.getElementById('pageName').value = pageName;
    
    // Update the suffix display based on the page
    const sectionSuffix = document.getElementById('sectionSuffix');
    if (pageName === 'home') {
        sectionSuffix.textContent = '_index.php';
    } else {
        sectionSuffix.textContent = '_' + pageName + '.php';
    }
    
    document.getElementById('addSectionModal').classList.remove('hidden');
}

// Function to edit a section
function editSection(sectionPath) {
    // Get the current page from the meta tag
    const currentPage = document.querySelector('meta[name="current-page"]').getAttribute('content');
    
    // Redirect to edit_section.php (correct file name) with the section path and page parameters
    window.location.href = 'edit_section.php?section=' + encodeURIComponent(sectionPath) + '&page=' + encodeURIComponent(currentPage);
}

// Function to generate a page
function generatePage(pageName) {
    // Show loader
    const generateBtn = document.getElementById('generatePageBtn');
    const generateLoader = document.getElementById('generateLoader');
    
    // Check if the elements exist
    if (!generateBtn || !generateLoader) {
        console.error('Generate button or loader not found');
        return;
    }
    
    // Get the text span or create a text reference
    let generateText = generateBtn.querySelector('span');
    if (!generateText) {
        // If no span exists, we'll just update the button's text content directly
        generateText = generateBtn;
    }
    
    // Store original text
    const originalText = generateText.textContent || `Generate ${pageName.charAt(0).toUpperCase() + pageName.slice(1)}`;
    
    // Show loader and disable button
    generateBtn.disabled = true;
    generateLoader.classList.remove('hidden');
    generateText.textContent = 'Generating...';
    
    // Add AJAX call to generate the page using the correct endpoint
    fetch('../../pages/generate/generate_page.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ page: pageName })
    })
    .then(response => response.json())
    .then(data => {
        // Hide loader
        generateBtn.disabled = false;
        generateLoader.classList.add('hidden');
        generateText.textContent = originalText;
        
        if (data.success) {
            // alert('Page generated successfully!');
            // Redirect to the view page if a redirect URL is provided
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        // Hide loader
        generateBtn.disabled = false;
        generateLoader.classList.add('hidden');
        generateText.textContent = originalText;
        
        console.error('Error:', error);
        alert('An error occurred while generating the page.');
    });
}

// Set up event listeners when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up the cancel button for add section modal
    document.getElementById('cancelAddSection').addEventListener('click', function() {
        document.getElementById('addSectionModal').classList.add('hidden');
    });
    
    // Set up the form submission for add section
    document.getElementById('addSectionForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Get the section name and page name
        const sectionName = document.getElementById('sectionName').value;
        const sectionNameError = document.getElementById('sectionNameError');
        const pageName = document.getElementById('pageName').value;
        
        // Validate section name (only letters and underscores)
        if (!sectionName.match(/^[a-zA-Z_]+$/)) {
            sectionNameError.textContent = 'Section name can only contain letters and underscores (no numbers)';
            sectionNameError.classList.remove('hidden');
            document.getElementById('sectionName').classList.add('border-red-500');
            return false;
        }
        
        // Check for duplicate section names
        const formData = new FormData();
        formData.append('action', 'check_duplicate');
        formData.append('section_name', sectionName);
        formData.append('page_name', pageName);
        
        fetch('../../includes/section_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // Try to parse as JSON, but handle text response as fallback
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error parsing JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            if (data.duplicate) {
                // Show duplicate error
                sectionNameError.textContent = 'A section with this name already exists';
                sectionNameError.classList.remove('hidden');
                document.getElementById('sectionName').classList.add('border-red-500');
            } else {
                // Hide error message if previously shown
                sectionNameError.classList.add('hidden');
                document.getElementById('sectionName').classList.remove('border-red-500');
                
                // Continue with form submission
                const submitFormData = new FormData(this);
                submitFormData.append('action', 'add');
                
                fetch('../../includes/section_handler.php', {
                    method: 'POST',
                    body: submitFormData
                })
                .then(response => {
                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // Try to parse as JSON, but handle text response as fallback
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Error parsing JSON:', text);
                            throw new Error('Invalid JSON response from server');
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        // Reload the page to reflect changes
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the section: ' + error.message);
                });
                
                // Hide the modal
                document.getElementById('addSectionModal').classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while checking for duplicate sections: ' + error.message);
        });
    });
    
    // Add input validation for section name field
    document.getElementById('sectionName').addEventListener('input', function() {
        const sectionNameError = document.getElementById('sectionNameError');
        
        // Validate section name (only letters and underscores)
        if (!this.value.match(/^[a-zA-Z_]*$/)) {
            sectionNameError.classList.remove('hidden');
            this.classList.add('border-red-500');
        } else {
            sectionNameError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
    });
    
    // Set up folder toggle functionality
    const folderToggles = document.querySelectorAll('.folder-toggle');
    folderToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const folderContent = this.nextElementSibling;
            folderContent.classList.toggle('hidden');
            
            // Toggle folder icon
            const folderIcon = this.querySelector('i');
            if (folderIcon.classList.contains('fa-folder')) {
                folderIcon.classList.remove('fa-folder');
                folderIcon.classList.add('fa-folder-open');
            } else {
                folderIcon.classList.remove('fa-folder-open');
                folderIcon.classList.add('fa-folder');
            }
        });
    });
});