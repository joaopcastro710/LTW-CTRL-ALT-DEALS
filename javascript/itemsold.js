window.addEventListener('DOMContentLoaded', (event) => {
    attachPrintButtonListeners();
});

document.addEventListener("DOMContentLoaded", function () {
    let profileTab = document.querySelector('a[data-tab="items sold"]');
    if (profileTab) {
        profileTab.classList.add("active");
    }
});

function attachPrintButtonListeners() {
    const printButtons = document.querySelectorAll('.downloadPdf');
    printButtons.forEach(button => {
        button.addEventListener('click', printAddressInfo);
    });
}

function printAddressInfo(event) {
    event.preventDefault();

    let parentRow = event.target.parentNode.parentNode;
    let brand = parentRow.cells[0].textContent;
    let model = parentRow.cells[1].textContent;
    let price = parentRow.cells[2].textContent;
    let buyerName = parentRow.cells[3].textContent;
    let address = parentRow.cells[4].textContent;
    let postalCode = parentRow.cells[5].textContent;
    let shipping = parentRow.cells[7].textContent;
    let printWindow = window.open('', '_blank');

    printWindow.document.write('<html><head><title>Print</title></head><body>');
    printWindow.document.write('<h2>Item Sold</h2>');
    printWindow.document.write('<p>Brand: ' + brand + '</p>');
    printWindow.document.write('<p>Model: ' + model + '</p>');
    printWindow.document.write('<p>Price: ' + price + '</p>');
    printWindow.document.write('<p>Shipping Cost: ' + shipping + '</p>');
    printWindow.document.write('<h2>Buyer Info</h2>');
    printWindow.document.write('<p>Name: ' + buyerName + '</p>');
    printWindow.document.write('<p>Address: ' + address + '</p>');
    printWindow.document.write('<p>Postal Code: ' + postalCode + '</p>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}