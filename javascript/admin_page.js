window.addEventListener('DOMContentLoaded', (event) => {
    attachFormValidation();
});

function attachFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            validateForm(event, form)
        });
    });
}

function validateForm(event, form) {
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    for (let i = 0; i < requiredFields.length; i++) {
        if (requiredFields[i].value === '') {
            alert('Please fill out all required fields');
            isValid = false;
            break;
        }
    }

    if (isValid) {
        const csrfToken = document.getElementById('csrfToken').value;
        const formData = new FormData(form);
        formData.append('token', csrfToken);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                let response = JSON.parse(xhr.response);
                alert(response.message);
            } else {
                console.error('Failed to submit form');
            }
        };
        xhr.send(formData);
    }
    event.preventDefault();
}