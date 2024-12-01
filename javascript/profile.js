'use strict';

let imageChanged = false;

document.addEventListener("DOMContentLoaded", function () {
    let profileTab = document.querySelector('a[data-tab="profile-data"]');
    if (profileTab) {
        profileTab.classList.add("active");
    }
});

function showResetPasswordForm() {
    clearProfileMessageBoxes();
    document.getElementById('reset-password-form').style.display = 'block';
    document.getElementById('edit-profile-form').style.display = 'none';
}

function showProfileForm() {
    clearProfileMessageBoxes();
    document.getElementById('reset-password-form').style.display = 'none';
    document.getElementById('edit-profile-form').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function () {

    const resetPasswordForm = document.getElementById('reset-password-form');
    resetPasswordForm.style.display = 'none';


    const goToPasswordResetButton = document.getElementById('goToPasswordResetButton');
    goToPasswordResetButton.addEventListener('click', showResetPasswordForm);


    const cancelPasswordResetButton = document.getElementById('cancelPasswordResetButton');
    cancelPasswordResetButton.addEventListener('click', showProfileForm);
});


document.querySelectorAll('.edit-profile-icon').forEach(function (button) {
    button.addEventListener('click', editModeManager);
});

document.getElementById('edit-profile-button').addEventListener('click', submitProfileForm);

function submitProfileForm(event) {
    event.preventDefault();
    let form = document.getElementById('edit-profile-form');


    let continentSelect = document.getElementById('continentInput');
    let wasContinentSelectDisabled = continentSelect.disabled;

    if (wasContinentSelectDisabled) {
        continentSelect.disabled = false;
    }

    let currencySelect = document.getElementById('currencyInput');
    let wasCurrencySelectDisabled = currencySelect.disabled;
    if (wasContinentSelectDisabled) {
        currencySelect.disabled = false;
    }

    let formData = new FormData(form);
    let imageFile = formData.get('image');


    if (wasContinentSelectDisabled) {
        continentSelect.disabled = true;
    }

    // Restore the disabled state of the currency select after extracting the form data
    if (wasCurrencySelectDisabled) {
        currencySelect.disabled = true;
    }


    clearProfileMessageBoxes();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/action_edit_profile.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response['profile-username-error']) {
                    formMessage('profile-username-error', response['profile-username-error']);
                } else if (response['profile-email-error']) {
                    formMessage('profile-email-error', response['profile-email-error']);
                } else if (response['profile-name-error']) {
                    formMessage('profile-name-error', response['profile-name-error']);
                } else if (response['profile-address-error']) {
                    formMessage('profile-address-error', response['profile-address-error']);
                } else if (response['profile-postalCode-error']) {
                    formMessage('profile-postalCode-error', response['profile-postalCode-error']);
                } else if (response['profile-continent-error']) {
                    formMessage('profile-continent-error', response['profile-continent-error']);
                } else if (response['profile-error']) {
                    formMessage('profile-error', response['profile-error']);
                } else if (response['success']) {
                    if (imageChanged) {
                        let userId = response['userId'];
                        submitUserImage(event, imageFile, userId, event.target.nonce)
                            .then(success => {
                                formMessage("profile-data-success-message", "Profile updated successfully.");
                            })
                            .catch(error => {
                                formMessage('profile-error', 'Failed to update profile.');
                            });
                    } else {
                        formMessage("profile-data-success-message", response['message']);
                    }
                }
            }
        }
    };
    formData.append("token", event.target.nonce);
    xhr.send(formData);
}

function editModeManager(event) {
    event.preventDefault();
    let label = event.target.closest('div.input-wrapper');
    if (label.classList.contains('editing')) {
        disableEdit(label, event.target.id);
    } else {
        enableEdit(label, event.target.id);
    }
}

function enableEdit(label, buttonId) {
    let input = label.querySelector('input');
    let select = label.querySelector('select');
    if (input) {
        input.removeAttribute('readonly');
    }
    if (select && (buttonId === 'editContinentButton' || buttonId === 'editContinentIcon' || buttonId === "editCurrencyButton" || buttonId === "editCurrencyIcon")) {
        select.removeAttribute('disabled');
    }
    label.classList.add('editing');
}

function disableEdit(label, buttonId) {
    let input = label.querySelector('input');
    let select = label.querySelector('select');
    if (input) {
        input.setAttribute('readonly', true);
    }
    if (select && (buttonId === 'editContinentButton' || buttonId === 'editContinentIcon' || buttonId === "editCurrencyButton" || buttonId === "editCurrencyIcon")) {
        select.setAttribute('disabled', true);
    }
    label.classList.remove('editing');
}

document.getElementById('editProfilePictureButton').addEventListener('click', function () {
    document.getElementById('profilePictureInput').click();
});

document.getElementById('profilePictureInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (allowedTypes.indexOf(file.type) === -1) {
        alert('Please select a valid image file (JPEG, PNG, or GIF)');
        return;
    }
    imageChanged = true;
    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('userProfilePic').src = e.target.result;
    };
    reader.readAsDataURL(file);
});

document.querySelectorAll('.visibility-toggle-icon').forEach(function (button) {
    button.addEventListener('click', toggleVisibility);
});

document.getElementById('reset-password-button').addEventListener('click', submitPasswordForm);

function submitPasswordForm(event) {
    event.preventDefault();
    let form = document.getElementById('reset-password-form');
    let currentPassword = form.querySelector('input[name="currentPassword"]').value;
    let newPassword = form.querySelector('input[name="newPassword"]').value;

    clearProfileMessageBoxes();
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/action_edit_password.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response['profile-currentPassword-error']) {
                    formMessage('profile-currentPassword-error', response['profile-currentPassword-error']);
                } else if (response['profile-newPassword-error']) {
                    formMessage('profile-newPassword-error', response['profile-newPassword-error']);
                } else if (response['reset-password-error']) {
                    formMessage('reset-password-error', response['reset-password-error']);
                } else if (response['success']) {
                    form.reset();
                    showProfileForm();
                    formMessage("profile-data-success-message", response['message']);
                }
            } else {

            }
        }
    };

    xhr.send('currentPassword=' + encodeURIComponent(currentPassword) + '&newPassword=' + encodeURIComponent(newPassword) + '&token=' + encodeURIComponent(event.target.nonce));
}

function toggleVisibility(event) {
    event.preventDefault();
    let label = event.target.closest('div.input-wrapper');
    let input = label.querySelector('input');
    let icon = label.querySelector('i');

    if (input.type === 'password') {
        showPassword(label, input, icon);
    } else {
        hidePassword(label, input, icon);
    }
}

function showPassword(label, input, icon) {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
    label.classList.add('visible-password');
}

function hidePassword(label, input, icon) {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
    label.classList.remove('visible-password');
}

function clearProfileMessageBoxes() {
    document.getElementById('profile-username-error').innerText = '';
    document.getElementById('profile-email-error').innerText = '';
    document.getElementById('profile-name-error').innerText = '';
    document.getElementById('profile-address-error').innerText = '';
    document.getElementById('profile-postalCode-error').innerText = '';
    document.getElementById('profile-error').innerText = '';
    document.getElementById('profile-currentPassword-error').innerText = '';
    document.getElementById('profile-newPassword-error').innerText = '';
    document.getElementById('reset-password-error').innerText = '';
    document.getElementById('profile-data-success-message').innerText = '';
}
