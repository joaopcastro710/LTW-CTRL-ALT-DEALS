'use strict';

if (document.getElementById('login')) {
    document.getElementById('login-button').addEventListener("click", submitLoginForm);
}

function submitLoginForm(event) {
    event.preventDefault();

    let form = document.getElementById('login-form');
    let email = form.querySelector('input[name="email"]').value;
    let password = form.querySelector('input[name="password"]').value;

    clearLoginHTML();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/action_login.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response['login-email-error']) {
                    formMessage('login-email-error', response['login-email-error']);
                } else if (response['login-password-error']) {
                    formMessage('login-password-error', response['login-password-error']);
                } else if (response['login-error']) {
                    formMessage('login-error', response['login-error']);
                }
                if (response['success']) {
                    window.location.href = '/pages/mainPage.php';
                }
            }
        }
    };
    xhr.send('email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password) + '&token=' + event.target.nonce);
}

function clearLoginHTML() {
    document.getElementById('login-email-error').innerText = '';
    document.getElementById('login-password-error').innerText = '';
    document.getElementById('login-error').innerText = '';
}
