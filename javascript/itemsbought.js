'use strict';

function decodeHtml(html) {
    let txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

document.addEventListener("DOMContentLoaded", function () {
    attachAcceptDeliveryListeners();
    attachSubmitRatingsListener();
    attachStarRatingListeners();
    attachCloseButtonListener();
});

document.addEventListener("DOMContentLoaded", function () {
    let profileTab = document.querySelector('a[data-tab="items bought"]');
    if (profileTab) {
        profileTab.classList.add("active");
    }
});

function attachAcceptDeliveryListeners() {
    const acceptDeliveryButtons = document.querySelectorAll('.accept-delivery-button');
    acceptDeliveryButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const itemId = this.form.querySelector('input[name="itemId"]').value;
            const token = this.form.querySelector('input[name="token"]').nonce;
            const sellerName = this.form.querySelector('input[name="sellerName"]').value;
            const seller = this.form.querySelector('input[name="seller"]').value;
            const formData = new FormData();
            acceptDelivery(itemId, token, seller, sellerName);
        });
    });
}

let currentSellerId = 0;

function acceptDelivery(itemId, token, seller, sellerName) {
    const formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('token', token);
    formData.append('seller', seller);
    formData.append('sellerName', decodeHtml(sellerName));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_accept_delivery.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                currentSellerId = formData.get('seller');
                displayRatingPopup(formData.get('sellerName'));
            }
        }
    };
    xhr.send(formData);
}

function attachSubmitRatingsListener() {
    const submitRatingsButton = document.getElementById('submit-ratings');
    submitRatingsButton.addEventListener('click', submitRatings);
}

function displayRatingPopup(sellerName) {
    const ratingPopup = document.getElementById('rating-popup');
    const h2Element = ratingPopup.querySelector('h2');
    h2Element.textContent += ' ' + sellerName;
    ratingPopup.classList.add('active');
}

function submitRatings() {
    const token = document.querySelector('#rating-popup input[name="token"]').nonce;
    const ratings = document.querySelectorAll('#rating-stars .star.selected').length;


    const formData = new FormData();
    formData.append('grade', ratings);
    formData.append("token", token);
    formData.append("sellerId", currentSellerId);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_add_rating.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                closeRatingPopup();
                refreshPage();
            }
        }
    };

    xhr.send(formData);
}

function attachStarRatingListeners() {
    const stars = document.querySelectorAll('#rating-stars .star');
    stars.forEach(star => {
        star.addEventListener('click', selectStar);
    });
}

function selectStar(event) {
    const clickedStar = event.target;
    const clickedRating = parseInt(clickedStar.getAttribute('data-rating'));
    const stars = document.querySelectorAll('#rating-stars .star');

    stars.forEach((star, index) => {
        if (index < clickedRating) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}

function closeRatingPopup() {
    const ratingPopup = document.getElementById('rating-popup');
    ratingPopup.style.display = 'none';
}

function attachCloseButtonListener() {
    const closeBtn = document.querySelector('.close-popup-btn');
    closeBtn.addEventListener('click', function () {
        closeRatingPopup();
        refreshPage();
    });
}

function refreshPage() {
    window.location.reload();
}
