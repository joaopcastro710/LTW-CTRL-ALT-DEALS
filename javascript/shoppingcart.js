function removeItemFromCart(event) {
    const shoppingcartID = event.target.className;
    let itemID = event.target.id;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_removeItemOfShoppingCart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let obj = JSON.parse(xhr.response);
            if (Object.values(obj)[0]) {
                if (Object.keys(obj)[0] === 'Success') {
                    event.target.parentElement.remove();
                } else {
                    event.target.parentElement.parentElement.remove();
                    let mainWishlist = document.querySelector('.shoppingcartPage');
                    let noItemsArticle = document.createElement('article');
                    noItemsArticle.classList.add('cart-item');
                    let noItemsInfo = document.createElement('p');
                    noItemsInfo.textContent = 'No items in shoppingcart';
                    noItemsArticle.appendChild(noItemsInfo);
                    let mainElement = document.querySelector('main');
                    mainWishlist.appendChild(noItemsArticle);
                }
            }
        } else {
            alert('Failed to remove item from shoppingcart');
        }
    };

    const data = 'shoppingcartID=' + encodeURIComponent(shoppingcartID) + '&itemID=' + encodeURIComponent(itemID)
        + '&token=' + encodeURIComponent(event.target.nonce);
    xhr.send(data);
}

if (document.location.pathname === '/pages/shoppingcart.php') {
    document.querySelectorAll('button').forEach(element => {
        element.addEventListener('click', removeItemFromCart);
    });
}
