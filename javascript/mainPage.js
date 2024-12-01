'use strict';
document.addEventListener("DOMContentLoaded", function () {
    let sellerTab = document.querySelector('a[data-tab="mainPage-items"]');
    if (sellerTab) {
        sellerTab.classList.add("active");
    }
});
document.getElementById('category-filter').addEventListener('change', handleFilterChange);
document.getElementById('condition-filter').addEventListener('change', handleFilterChange);
document.getElementById('size-filter').addEventListener('change', handleFilterChange);
document.getElementById('sort-by').addEventListener('change', handleFilterChange);


if (document.getElementById('category-filter')) {
    const categoryFilter = document.getElementById('category-filter');
    categoryFilter.addEventListener('change', handleFilterChange);
}

if (document.getElementById('condition-filter')) {
    const conditionFilter = document.getElementById('condition-filter');
    conditionFilter.addEventListener('change', handleFilterChange);
}

if (document.getElementById('size-filter')) {
    const sizeFilter = document.getElementById('size-filter');
    sizeFilter.addEventListener('change', handleFilterChange);
}

if (document.getElementById('sort-by')) {
    const sortBy = document.getElementById('sort-by');
    sortBy.addEventListener('change', handleFilterChange);
}

function handleFilterChange() {
    fetchFilteredItems();
}

function fetchFilteredItems() {
    const categoryValue = document.getElementById('category-filter').value;
    const conditionValue = document.getElementById('condition-filter').value;
    const sizeValue = document.getElementById('size-filter').value;
    const sortByValue = document.getElementById('sort-by').value;

    fetch(`../actions/action_getFilteredItemsMainPage.php?category=${categoryValue}&condition=${conditionValue}&size=${sizeValue}&sort=${sortByValue}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(items => {
            renderItems(items);
        })
        .catch(error => {
            console.error('Error fetching filtered items:', error);
        });
}

function renderItems(items) {
    const container = document.querySelector('#item-container');
    container.innerHTML = '';

    items.forEach(item => {
        let itemSize = (item.sizeName !== "") ? item.sizeName : "-";

        const article = document.createElement('article');
        article.className = "mainPageItem";

        const imgLink = document.createElement('a');
        imgLink.id = "responsiveImage";
        imgLink.href = `item.php/?id=${encodeURIComponent(item.itemId)}`;

        const img = document.createElement('img');
        if (item.mainImageId) {
            img.src = `../assets/items/thumbs_small/${item.mainImageId}.jpg`;
        } else {
            img.src = `https://picsum.photos/600/300?random`;
        }
        img.alt = "Item Image";

        imgLink.appendChild(img);

        article.appendChild(imgLink);

        const header = document.createElement('header');

        const elements = [
            {tag: 'h4', className: 'itemBrand', text: escapeHtml(item.brand)},
            {tag: 'h3', className: 'itemModel', text: escapeHtml(item.model)},
            {tag: 'h4', className: 'itemPriceValue', text: escapeHtml(item.price)},
            {tag: 'h4', className: 'itemSize', text: `Size: ${escapeHtml(itemSize)}`},
            {tag: 'h4', className: 'itemCondition', text: `Condition: ${escapeHtml(item.conditionName)}`}
        ];

        elements.forEach(elementInfo => {
            const element = document.createElement(elementInfo.tag);
            element.className = elementInfo.className;

            const link = document.createElement('a');
            link.href = `item.php/?id=${encodeURIComponent(item.itemId)}`;
            link.textContent = elementInfo.text;

            element.appendChild(link);
            header.appendChild(element);
        });

        article.appendChild(header);
        container.appendChild(article);
    });
}

// fetchFilteredItems();

function escapeHtml(unsafe) {
    return String(unsafe).replace(/[&<"'>]/g, function (match) {
        switch (match) {
            case '&':
                return '&amp;';
            case '<':
                return '&lt;';
            case '>':
                return '&gt;';
            case '"':
                return '&quot;';
            case "'":
                return '&#039;';
            default:
                return match;
        }
    });
}


let blockerDiv = document.createElement('div');
let popup = document.getElementById('popup');

blockerDiv.className = 'blockerDiv';
blockerDiv.addEventListener('click', () => {
    return false
});

if (popup !== null) {
    document.querySelector('body').appendChild(blockerDiv);

    let popupDiv = document.createElement('div');

    popupDiv.className = 'popup';
    popupDiv.id = 'popup';

    let popupContentDiv = document.createElement('div');
    popupContentDiv.className = 'popup-content';

    let closeButtonSpan = document.createElement('span');
    closeButtonSpan.className = 'close';
    closeButtonSpan.id = 'close';
    closeButtonSpan.innerHTML = '&times;';

    let messageParagraph = document.createElement('p');
    messageParagraph.textContent = '';

    popupContentDiv.appendChild(closeButtonSpan);
    popupContentDiv.appendChild(messageParagraph);
    popupDiv.appendChild(popupContentDiv);

    popup.style.display = 'block';

    document.getElementById('close').addEventListener('click', function (event) {
        document.getElementById('popup').style.display = 'none';
        document.body.removeChild(document.querySelector('.blockerDiv'));

        let xhr = new XMLHttpRequest();

        xhr.open('POST', '/actions/action_promotion.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('token=' + event.target.nonce);
    });
}
