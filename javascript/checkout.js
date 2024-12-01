window.addEventListener('DOMContentLoaded', (event) => {
    attachFormValidation();
});

function attachFormValidation() {
    const form = document.querySelector('form');
    form.addEventListener('submit', validateForm);
}

function validateForm(event) {
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');

    for (let i = 0; i < requiredFields.length; i++) {
        if (requiredFields[i].value === '') {
            event.preventDefault();
            alert('Please fill out all required fields');
            return;
        }
    }
}