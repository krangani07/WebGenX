function validateForm(event) {
    let isValid = true;
    const fields = [
        { id: 'websiteName', error: 'websiteNameError' },
        { id: 'websiteType', error: 'websiteTypeError' },
        { id: 'description', error: 'descriptionError' },
        { id: 'colorScheme', error: 'colorSchemeError' },
        { id: 'typography', error: 'typographyError' }
    ];

    fields.forEach(field => {
        const element = document.getElementById(field.id);
        const value = element.value.trim();
        if (!value) {
            document.getElementById(field.error).classList.remove('hidden');
            isValid = false;
        } else {
            document.getElementById(field.error).classList.add('hidden');
        }
    });

    const pages = document.querySelectorAll('input[name="pages[]"]:checked');
    if (pages.length === 0) {
        document.getElementById('pagesError').classList.remove('hidden');
        isValid = false;
    } else {
        document.getElementById('pagesError').classList.add('hidden');
    }

    if (!isValid) {
        event.preventDefault();
    }
}

export { validateForm };