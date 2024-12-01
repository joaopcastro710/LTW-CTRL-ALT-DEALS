'use strict';

if (document.getElementById('register')) {
    document.getElementById('register-button').addEventListener("click", submitRegistrationForm);
}

function submitRegistrationForm(event) {
    event.preventDefault();

    let form = document.getElementById('register-form');
    let formData = new FormData(form);
    let imageFile = formData.get('image');
    clearRegisterHTML();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/action_register.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.username_error) {
                    formMessage('register-username-error', response.username_error);
                } else if (response.email_error) {
                    formMessage('register-email-error', response.email_error);
                } else if (response.password_error) {
                    formMessage('register-password-error', response.password_error);
                } else if (response.name_error) {
                    formMessage('register-name-error', response.name_error);
                } else if (response.address_error) {
                    formMessage('register-address-error', response.address_error);
                } else if (response.postcode_error) {
                    formMessage('register-postcode-error', response.postcode_error);
                } else if (response.continent_error) {
                    formMessage('register-continent-error', response.continent_error);
                } else if (response.general_error) {
                    console.error('Registration error:', response.general_error);
                } else if (response.success) {
                    if (imageFile.valueOf().name !== "") {
                        let userId = response.userId;
                        submitUserImage(event, imageFile, userId, event.target.nonce)
                            .then(success => {
                                window.location.href = '/pages/login.php';
                            })
                            .catch(error => {
                                console.error(error);
                            });

                    } else {
                        window.location.href = '/pages/login.php';
                    }
                }
            }
        }
    };
    formData.append("token", event.target.nonce);
    xhr.send(formData);
}

function clearRegisterHTML() {
    document.getElementById('register-username-error').innerText = '';
    document.getElementById('register-email-error').innerText = '';
    document.getElementById('register-password-error').innerText = '';
    document.getElementById('register-name-error').innerText = '';
    document.getElementById('register-address-error').innerText = '';
    document.getElementById('register-postcode-error').innerText = '';
}
