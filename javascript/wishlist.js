function removeItemFromWishlist(event) {
    const wishlistID = event.target.className;
    let itemID = event.target.id;
    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/actions/action_removeItemFromWishlist.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let obj = JSON.parse(xhr.response);
            if (Object.values(obj)[0]) {
                if (Object.keys(obj)[0] === 'Success') {
                    event.target.parentElement.remove();
                } else {
                    event.target.parentElement.parentElement.remove();
                    let mainWishlist = document.querySelector('.wishlistPage');
                    let noItemsArticle = document.createElement('article');
                    noItemsArticle.classList.add('wishlist_info');
                    let noItemsInfo = document.createElement('p');
                    noItemsInfo.textContent = 'No items in wishlist';
                    noItemsArticle.appendChild(noItemsInfo);
                    let mainElement = document.querySelector('main');
                    mainWishlist.appendChild(noItemsArticle);
                }
            }
        } else {
            alert('Failed to remove item from wishlist');
        }
    };

    const data = 'wishlistID=' + encodeURIComponent(wishlistID) + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);
    xhr.send(data);
}

if (document.location.pathname === '/pages/wishlist.php') {
    document.querySelectorAll('button').forEach(element => {
        element.addEventListener('click', removeItemFromWishlist);
    });
}