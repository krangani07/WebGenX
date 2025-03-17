import { validateForm } from './formValidation.js';
document.addEventListener('DOMContentLoaded', function () {
    const addCustomPageBtn = document.getElementById('addCustomPage');
    const customPagesContainer = document.getElementById('customPagesContainer');
    
    // Add custom page field
    addCustomPageBtn.addEventListener('click', function () {
        const newPageField = document.createElement('div');
        newPageField.className = 'flex mb-2';
        newPageField.innerHTML = `
            <input type="text" name="customPages[]" class="flex-grow px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter custom page name">
            <button type="button" class="ml-2 bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 remove-page">Remove</button>
        `;
        customPagesContainer.appendChild(newPageField);
        
        const firstRemoveBtn = document.querySelector('.remove-page.hidden');
        if (firstRemoveBtn) {
            firstRemoveBtn.classList.remove('hidden');
        }
    });
    
    // Remove custom page field
    customPagesContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-page')) {
            e.target.parentElement.remove();
            
            const removeButtons = document.querySelectorAll('.remove-page');
            if (removeButtons.length === 1) {
                removeButtons[0].classList.add('hidden');
            }
        }
    });
    
    // Form validation
    const form = document.getElementById('generatorForm');
    form.addEventListener('submit', validateForm);
});