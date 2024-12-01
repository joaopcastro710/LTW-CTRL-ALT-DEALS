window.addEventListener('DOMContentLoaded', (event) => {
    attachPaymentFormValidation();
});

const discountCodeButton = document.getElementById('discount-code-button');
discountCodeButton.addEventListener('click', validateCode);
const removeDiscountButton = document.getElementById('remove-discount-button');
if (removeDiscountButton !== null) {
    removeDiscountButton.addEventListener('click', removeDiscount);
}

function removeDiscount(event) {
    event.preventDefault();
    let xhr = new XMLHttpRequest();

    xhr.open('POST', '/actions/action_removeDiscount.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let serverResponse = JSON.parse(xhr.response)
            if (Object.values(serverResponse)[0]) {
                location.reload();
            }
        }
    };
    const data = 'dicountCode=' + encodeURIComponent(document.getElementById('discount-code').value.toString())
        + '&token=' + event.target.nonce;
    xhr.send(data);
}


function validateCode(event) {
    event.preventDefault();
    let xhr = new XMLHttpRequest();

    xhr.open('POST', '/actions/action_setDiscount.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let serverResponse = JSON.parse(xhr.response)
            if (Object.values(serverResponse)[0]) {
                alert('Discount code applied');
                location.reload();
            } else {
                alert('Discount code not applied');
            }
        }
    };

    const data = 'dicountCode=' + encodeURIComponent(document.getElementById('discount-code').value.toString())
        + '&token=' + event.target.nonce;
    xhr.send(data);
}


function attachPaymentFormValidation() {
    const form = document.querySelector('#payment-form');
    form.addEventListener('submit', validatePaymentForm);
}

function validatePaymentForm(event) {
    const paymentOptions = document.querySelectorAll('input[name="payment"]');
    let isOptionSelected = false;
    for (let i = 0; i < paymentOptions.length; i++) {
        if (paymentOptions[i].checked) {
            isOptionSelected = true;
            break;
        }
    }

    if (!isOptionSelected) {
        event.preventDefault();
        alert('Please select a payment option');
    }
}
