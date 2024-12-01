function addItemToShoppingCart(event) {
    let itemID = event.target.attributes['value'].value;
    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/actions/action_addItemToShoppingCart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            event.target.innerText = 'Remove from cart';
            event.target.className = 'removeFromCart';
            event.target.removeEventListener('click', addItemToShoppingCart);
            event.target.addEventListener('click', removeFromShoppingCart);
        }
    };

    const data = 'type=' + encodeURIComponent('add') + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);

    xhr.send(data);
}

function addItemToWishList(event) {
    let itemID = event.target.attributes['value'].value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_addItemToWishlist.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            event.target.innerText = 'Remove from wishlist';
            event.target.className = 'removeFromWishlist';
            event.target.removeEventListener('click', addItemToWishList);
            event.target.addEventListener('click', removeFromWishlist);
        }
    };


    const data = 'type=' + encodeURIComponent('add') + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);


    xhr.send(data);
}

function removeFromShoppingCart(event) {

    let itemID = event.target.attributes['value'].value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_addItemToShoppingCart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');


    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {

            event.target.innerText = 'Add to cart';
            event.target.className = 'addToCartButton';
            event.target.removeEventListener('click', removeFromShoppingCart);
            event.target.addEventListener('click', addItemToShoppingCart);
        }
    };
    const data = 'type=' + encodeURIComponent('remove') + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);
    xhr.send(data);
}

function removeFromWishlist(event) {
    let itemID = event.target.attributes['value'].value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_addItemToWishlist.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {

            event.target.innerText = 'Add to wishlist';
            event.target.className = 'addToWishlistButton';
            event.target.removeEventListener('click', removeFromWishlist);
            event.target.addEventListener('click', addItemToWishList);
        }
    };

    const data = 'type=' + encodeURIComponent('remove') + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);

    xhr.send(data);
}

function reorganizeElements() {
    let itemPageAside = document.querySelector('.itemPage > aside');
    let itemPage = document.querySelector('.itemPage');

    if (window.outerWidth <= 950) {
        if (itemPageAside !== null) {

            itemPage.insertBefore(document.querySelector('.itemPrice'), itemPage.firstChild);
            itemPage.append(document.querySelector('.itemSeller'));

        }
    } else {
        let itemPageAsideCheck = document.querySelector('.itemPage > aside > .itemPrice');
        let itemPageAside = document.querySelector('.itemPage > aside');

        if (itemPageAsideCheck === null) {
            itemPageAside.append(document.querySelector('.itemPage > .itemPrice'));
            itemPageAside.append(document.querySelector('.itemPage > .itemSeller'));
        }
    }
}

function sendMessageToSeller(event) {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/actions/action_addChat.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            window.location.replace("/pages/messages.php");
        }
    };

    const data = 'type=' + encodeURIComponent('remove') + '&itemID=' + encodeURIComponent(event.target.value)
        + "&token=" + encodeURIComponent(event.target.nonce);

    xhr.send(data);
}

document.addEventListener('DOMContentLoaded', (event) => {
    const itemImagesElement = document.querySelector('.itemImages');
    const images = JSON.parse(itemImagesElement.getAttribute('data-images'));

    let currentIndex = 0;
    const carouselImage = document.getElementById('carouselImage');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    function updateArrows() {
        if (currentIndex === 0) {
            prevButton.classList.add('disabled');
        } else {
            prevButton.classList.remove('disabled');
        }

        if (currentIndex === images.length - 1) {
            nextButton.classList.add('disabled');
        } else {
            nextButton.classList.remove('disabled');
        }
    }

    function showImage(index) {
        carouselImage.src = images[index];
        updateArrows();
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showImage(currentIndex);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < images.length - 1) {
            currentIndex++;
            showImage(currentIndex);
        }
    });

    showImage(currentIndex);
    updateArrows();
});

reorganizeElements();
window.addEventListener('resize', reorganizeElements);
const xhr = new XMLHttpRequest();
xhr.open('POST', '/actions/action_checkIfItemBought.php', true);
xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
        let res = JSON.parse(xhr.response);
        let remove = false;

        if (Object.values(res)[0]) {
            remove = true;
        }

        const sendMessageSeller = document.querySelector('.sendMessageToSeller');
        const addWishlistButton = document.querySelector('.addToWishlistButton');
        const addShoppingCartButton = document.querySelector('.addToCartButton');
        const editItem = document.querySelector('.editItem');
        const removeItem = document.querySelector('.removeItem');

        if (addShoppingCartButton !== null && editItem === null && removeItem === null) {
            addShoppingCartButton.addEventListener('click', addItemToShoppingCart);
        } else if (addShoppingCartButton !== null && editItem !== null && removeItem !== null) {

            addShoppingCartButton.addEventListener('click', (event) => {
                window.location.href = '/pages/edit_item.php/?id=' + event.target.value;
            });

        } else {
            removeShoppingCartButton = document.querySelector('.removeFromCart');
            removeShoppingCartButton.addEventListener('click', removeFromShoppingCart);
        }

        if (addWishlistButton !== null && editItem === null && removeItem === null) {
            addWishlistButton.addEventListener('click', addItemToWishList);
        } else if (addWishlistButton !== null && editItem !== null && removeItem !== null) {

            addWishlistButton.addEventListener('click', (event) => {
                window.location.href = '/pages/edit_item.php/?id=' + event.target.value;
            });

        } else {
            removeWishlistButton = document.querySelector('.removeFromWishlist');
            removeWishlistButton.addEventListener('click', removeFromWishlist);
        }

        if (sendMessageSeller !== null && editItem === null && removeItem === null) {
            sendMessageSeller.addEventListener('click', sendMessageToSeller);
        }

        if (remove) {
            if (addWishlistButton) {
                addWishlistButton.remove();
            }

            if (addShoppingCartButton) {
                addShoppingCartButton.remove();
            }

            if (removeItem) {
                removeItem.remove();
            }

            if (editItem) {
                editItem.remove();
            }
        }

    }
};


xhr.send('itemId=' + encodeURIComponent(parseInt(document.querySelector('.itemIdentification > p').innerText.toString().substring(7))));