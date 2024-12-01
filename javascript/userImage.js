'use strict';

function submitUserImage(event, imageFile, userId, token) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('userId', userId);
        formData.append('token', token);
        formData.append('image', imageFile);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../actions/action_upload_user_image.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        console.error('Registration error:', response.error);
                        reject(response.general_error);
                    } else if (response.success) {
                        resolve(true);
                    }
                } else {
                    console.error('Failed to create item. Status code:', xhr.status);
                    reject(xhr.status);
                }
            }
        };
        xhr.send(formData);
    });
}