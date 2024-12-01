# LTW Project 23/24
# CTRL + ALT + DEALS

## Group LTW10G01

- João Castro (up202206575) 33.3%
- Pedro Oliveira (up202208345) 33.3%
- António Santos (up202205469) 33.3%

## Install Instructions

### Clone using ssh

    git clone git@github.com:FEUP-LTW-2024/ltw-project-2024-ltw10g01.git
### Clone using web url
	git clone https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw10g01.git

### Running the project
    git checkout final-delivery-v2
    sqlite database/database.db < database/database.sql
    sqlite database/database.db < database/populate.sql
    php -S localhost:9000

# Mockups

<p align="center" justify="center">
  <img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw10g01/blob/a89a38ed5f4c010891e33c5270b46806652f48f6/docs/mockup.png?raw=true"/>
</p>

## Screenshots

### HomePage

<div style="display: flex; justify-content: center;">
  <img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw10g01/blob/f7459fb6a2bbf05fa8f744613f76be55f5ce1cb7/docs/HomePage.png" alt="Home page of website" style="max-width: 60%; height: auto;">
</div>

### ItemPage

<div style="display: flex; justify-content: center;">
  <img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw10g01/blob/f7459fb6a2bbf05fa8f744613f76be55f5ce1cb7/docs/ItemPage.png" alt="Item page of website" style="max-width: 60%; height: auto;">
</div>

### Profile

<div style="display: flex; justify-content: center;">
  <img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw10g01/blob/f7459fb6a2bbf05fa8f744613f76be55f5ce1cb7/docs/ProfilePage.png" alt="Profile page of website" style="max-width: 60%; height: auto;">
</div>

# Description

Develop a website that facilitates the buying and selling of pre-loved items. The platform should provide a seamless
experience for users to easily list, browse, and transact.

## Implemented Features

**General**:

- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Sellers**  should be able to:

- [x] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [x] Track and manage their listed items.
- [x] Respond to inquiries from buyers regarding their items and add further information if needed.
- [x] Print shipping forms for items that have been sold.

**Buyers**  should be able to:

- [x] Browse items using filters like category, price, and condition.
- [x] Engage with sellers to ask questions or negotiate prices.
- [x] Add items to a wishlist or shopping cart.
- [x] Proceed to checkout with their shopping cart (simulate payment process).

**Admins**  should be able to:

- [x] Elevate a user to admin status.
- [x] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [x] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [x] **SQL injection**
- [x] **Cross-Site Scripting (XSS)**
- [x] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: hash_password & verify_password

**Aditional Requirements**:

We also implemented the following additional requirements:

- [x] **Rating and Review System**
- [x] **Multi-Currency Support**
- [x] **Dynamic Promotions**
- [x] **User Preferences**
- [x] **Shipping Costs**
- [x] **Real-Time Messaging System**
- [x] Edit items
- [x] Delivery Status
- [x] Add/Edit profile pictures
- [x] Sellers can Edit/Delete items
- [x] User can use text to search
- [x] Sellers can Add at any moment item images
- [x] Buyers can optionally choose to send an email to sellers
- [x] Buyers can track the history of their purchased items
- [x] Sellers can track the history of their sold items
