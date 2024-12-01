'use strict';
document.addEventListener("DOMContentLoaded", function () {
    let itemTab = document.querySelector('a[data-tab="add-item"]');
    if (itemTab) {
        itemTab.classList.add("active");
    }
});

if (document.getElementById('create-item')) {
    document.getElementById('create-item-button').addEventListener("click", submitCreateItemForm);
}

function submitCreateItemForm(event) {
    clearCreateItemHTML();
    event.preventDefault();

    let form = document.getElementById('create-item-form');
    let formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_create_item.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response['create-item-brand-error']) {
                        formMessage('create-item-brand-error', response['create-item-brand-error']);
                    } else if (response['create-item-model-error']) {
                        formMessage('create-item-model-error', response['create-item-model-error']);
                    } else if (response['create-item-price-error']) {
                        formMessage('create-item-price-error', response['create-item-price-error']);
                    } else if (response['create-item-image-error']) {
                        formMessage('create-item-image-error', response['create-item-image-error']);
                    } else if (response['create-item-image-error']) {
                        formMessage('create-item-description-error', response['create-item-description-error']);
                    } else if (response['success']) {
                        const imageFiles = form.querySelectorAll('input[type="file"]');
                        submitItemImage(event, imageFiles, response['lastInsertedId'], event.target.nonce);
                    } else {
                        formMessage('create-item-image-error', "PUTS");
                    }
                } else {
                    console.error('Failed to create item. Status code:', xhr.status);
                }
            }
        }
    };
    formData.append("token", event.target.nonce);

    xhr.send(formData);
}

function submitItemImage(event, imageFiles, itemId, token) {
    const formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('token', token);
        imageFiles.forEach(fileInput => {
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            formData.append('image[]', files[i]);
        }
    });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/action_upload_item_image.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response['item-image-error']) {
                        formMessage('create-item-image-error', response['item-image-error']);
                    } else if (response['success']) {
                        window.location.href = `../pages/item.php?id=${itemId}`;
                    } else {
                        formMessage('create-item-image-error', response);
                    }
                } else {
                    console.error('Failed to create item. Status code:', xhr.status);
                }
            }
        }
    };
    formData.append("creatingItem", "1");
    xhr.send(formData);
}

function clearCreateItemHTML() {
    document.getElementById('create-item-brand-error').innerText = '';
    document.getElementById('create-item-model-error').innerText = '';
    document.getElementById('create-item-price-error').innerText = '';
    document.getElementById('create-item-image-error').innerText = '';
}
