'use strict';

document.addEventListener("DOMContentLoaded", function () {
    let itemTab = document.querySelector('a[data-tab="add-item"]');
    if (itemTab) {
        itemTab.classList.add("active");
    }
});

if (document.getElementById('edit-item')) {
    document.getElementById('edit-item-button').addEventListener("click", submitEditItemForm);
}

function submitEditItemForm(event) {
    clearEditItemHTML();
    event.preventDefault();

    let form = document.getElementById('edit-item-form');
    let formData = new FormData(form)
    const queryParams = new URLSearchParams(window.location.search);
    const itemId = queryParams.get('id');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_edit_item.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response['edit-item-brand-error']) {
                        formMessage('edit-item-brand-error', response['edit-item-brand-error']);
                    } else if (response['edit-item-model-error']) {
                        formMessage('edit-item-model-error', response['edit-item-model-error']);
                    } else if (response['edit-item-price-error']) {
                        formMessage('edit-item-price-error', response['edit-item-price-error']);
                    } else if (response['edit-item-image-error']) {
                        formMessage('edit-item-image-error', response['edit-item-image-error']);
                    } else if (response['edit-item-description-error']) {
                        formMessage('edit-item-description-error', response['edit-item-description-error']);
                    } else if (response['success']) {
                        const imageFiles = form.querySelectorAll('input[type="file"]');
                        let hasImage = false;
                        imageFiles.forEach(input => {
                            if (input.files.length > 0) {
                                hasImage = true;
                                return;
                            }
                        });
                        if (hasImage) {
                            submitItemImage(event, imageFiles, itemId, event.target.nonce);
                        } else window.location.href = `/pages/item.php?id=` + itemId;
                    } else {
                        formMessage('edit-item-image-error', "PUTS");
                    }
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
    formData.append('creatingItem', 0);
    imageFiles.forEach(fileInput => {
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            formData.append('image[]', files[i]);
        }
    });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_upload_item_image.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response['item-image-error']) {
                        formMessage('edit-item-image-error', response['item-image-error']);
                    } else if (response['success']) {
                        window.location.href = `/pages/item.php?id=${itemId}`;
                    } else {
                        formMessage('edit-item-image-error', response);
                    }
                }
            }
        }
    };
    xhr.send(formData);
}

if (document.getElementById('delete-item-button')) {
    document.getElementById('delete-item-button').addEventListener("click", deleteItem);
}

function deleteItem(event) {
    event.preventDefault();
    if (!confirm('Are you sure you want to delete this item?').valueOf()) return;
    let form = document.getElementById('delete-item-form');
    let formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_delete_item.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response['success']) {
                    window.location.href = '/pages/seller_items.php';
                } else {
                    alert('Failed to delete item. Please try again.');
                }
            } else {
                alert('Failed to delete item. Please try again.');
            }
        }
    };
    formData.append("token", event.target.nonce);
    xhr.send(formData);
}

function clearEditItemHTML() {
    document.getElementById('edit-item-brand-error').innerText = '';
    document.getElementById('edit-item-model-error').innerText = '';
    document.getElementById('edit-item-price-error').innerText = '';
    document.getElementById('edit-item-image-error').innerText = '';
}
