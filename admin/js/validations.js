document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addLibraryForm');
    const inputs = form.querySelectorAll('input[required]');

    function showError(input, message) {
        const errorElement = document.getElementById(input.id + 'Error');
        errorElement.textContent = message;
        input.setAttribute('aria-invalid', 'true');
        input.classList.add('error');
    }

    function clearError(input) {
        const errorElement = document.getElementById(input.id + 'Error');
        errorElement.textContent = '';
        input.removeAttribute('aria-invalid');
        input.classList.remove('error');
    }

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearError(input);
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                showError(input, `${input.previousElementSibling.textContent} is required`);
                isValid = false;
            } else if (input.value.length < input.minLength) {
                showError(input, `${input.previousElementSibling.textContent} must be at least ${input.minLength} characters`);
                isValid = false;
            }
        });

        if (isValid) {
            // TODO: Add your form submission logic here
            console.log('Form is valid, ready to submit');
        }
    });
});