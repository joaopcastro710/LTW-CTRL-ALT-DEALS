'use strict';

function decodeHtml(html) {
    let txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

document.addEventListener("DOMContentLoaded", function () {
    let sellerTab = document.querySelector('a[data-tab="selling-items"]');
    if (sellerTab) {
        sellerTab.classList.add("active");
    }
});

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
    fetch(`../actions/action_getFilteredItems.php?category=${categoryValue}&condition=${conditionValue}&size=${sizeValue}&sort=${sortByValue}`)
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

    const tbody = document.querySelector('.selling-item-list table tbody');
    tbody.innerText = '';
    if (items.length === 0) {

        document.querySelector('table').style.display = 'none';
        document.getElementById('no-items-message').style.display = 'block';
    } else {

        document.querySelector('table').style.display = 'table';
        document.getElementById('no-items-message').style.display = 'none';
        items.forEach(item => {
            const row = document.createElement('tr');
            row.setAttribute('id', 'item-details');

            let itemSize = (item.sizeName !== "") ? item.sizeName : "-";
            let thumbnailUrl;
            if (item.mainImageId) {
                thumbnailUrl = `../assets/items/thumbs_small/${item.mainImageId}.jpg`;
            } else {
                thumbnailUrl = "https://picsum.photos/600/300?random";
            }
            const cells = [
                {type: "image", src: thumbnailUrl, href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(item.brand), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(item.model), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(item.categoryName), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(itemSize), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(item.price), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {text: decodeHtml(item.conditionName), href: `item.php/?id=${encodeURIComponent(item.itemId)}`},
                {type: "edit", id: item.itemId}
            ];

            cells.forEach(cellInfo => {
                const cell = document.createElement('td');
                if (cellInfo.type === "image") {
                    const img = document.createElement('img');
                    img.src = cellInfo.src;
                    img.alt = "Item Image";
                    const link = document.createElement('a');
                    link.href = cellInfo.href;
                    link.appendChild(img);
                    cell.appendChild(link);
                } else if (cellInfo.type === "edit") {
                    const form = document.createElement('form');
                    form.action = `/pages/edit_item.php/?id=${encodeURIComponent(cellInfo.id)}`;
                    form.method = 'POST';
                    form.enctype = 'multipart/form-data';
                    const hiddenToken = document.createElement('input');
                    hiddenToken.type = 'hidden';
                    hiddenToken.name = 'token';
                    hiddenToken.value = (sessionStorage.getItem('csrfToken'));
                    const editBtn = document.createElement('button');
                    editBtn.type = 'submit';
                    editBtn.classList.add('edit-button');
                    const icon = document.createElement('i');
                    icon.classList.add('fa', 'fa-pencil');
                    editBtn.appendChild(icon);
                    form.appendChild(hiddenToken);
                    form.appendChild(editBtn);
                    cell.appendChild(form);
                } else {
                    const link = document.createElement('a');
                    link.href = cellInfo.href;
                    link.textContent = cellInfo.text;
                    cell.appendChild(link);
                }
                row.appendChild(cell);
            });

            tbody.appendChild(row);
        });
    }
}

fetchFilteredItems();