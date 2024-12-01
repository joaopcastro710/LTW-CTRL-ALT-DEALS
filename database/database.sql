
PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Address;
DROP TABLE IF EXISTS Buyer;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS ItemBought;
DROP TABLE IF EXISTS Chat;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS ShoppingCart;
DROP TABLE IF EXISTS CartItem;
DROP TABLE IF EXISTS Wishlist;
DROP TABLE IF EXISTS WishlistItem;
DROP TABLE IF EXISTS Size;
DROP TABLE IF EXISTS Condition;
DROP TABLE IF EXISTS ItemImage;
DROP TABLE IF EXISTS UserImage;
DROP TABLE IF EXISTS Continents;

PRAGMA foreign_keys = ON;

CREATE TABLE Continents (
    continentId INTEGER   CONSTRAINT Continents_continentId_pk    PRIMARY KEY,
    continentName TEXT    CONSTRAINT Continents_continentName_nn  NOT NULL
);

CREATE TABLE User (
    userId INTEGER        CONSTRAINT User_userId_pk            PRIMARY KEY,
    email TEXT            CONSTRAINT User_email_u              UNIQUE NOT NULL
                          CONSTRAINT User_email_nn             UNIQUE NOT NULL,
    password TEXT         CONSTRAINT User_password_nn          NOT NULL,
    username TEXT         CONSTRAINT User_username_u           UNIQUE NOT NULL,
    name TEXT             CONSTRAINT User_name_nn              NOT NULL,
    favCurrency INTEGER   CONSTRAINT User_favCurrency_nn       NOT NULL,
    isAdmin BOOLEAN       DEFAULT 0                            CONSTRAINT User_isAdmin_ck    CHECK (isAdmin IN (0, 1)),
    continentId INTEGER   DEFAULT 1                            CONSTRAINT User_continentId_nn       NOT NULL,
                                                               CONSTRAINT User_continentId_fk FOREIGN KEY (continentId) REFERENCES Continents(continentId)
                                                               ON DELETE SET DEFAULT
                                                               ON UPDATE CASCADE
);

CREATE TABLE Address (
    idAddress INTEGER     CONSTRAINT Address_idAddress_pk      PRIMARY KEY,
    userId INTEGER        CONSTRAINT Address_userId_fk         REFERENCES User(userId)
                                                               ON UPDATE CASCADE
                                                               ON DELETE CASCADE,
    address TEXT          CONSTRAINT Address_address_nn        NOT NULL,
    postalCode TEXT       CONSTRAINT Address_postalCode_nn     NOT NULL
);

CREATE TABLE Review (
    idReview INTEGER     CONSTRAINT Review_idReview_pk          PRIMARY KEY,
    grade INTEGER        CONSTRAINT Review_grade_ck             CHECK (grade BETWEEN 1 AND 5),
    userId INTEGER       CONSTRAINT Review_userId_fk            REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
    sellerId INTEGER     CONSTRAINT Review_sellerId_fk          REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE
);

CREATE TABLE Category (
    categoryId INTEGER   CONSTRAINT Category_categoryId_pk      PRIMARY KEY,
    categoryName TEXT    CONSTRAINT Category_categoryName_nn    NOT NULL
);

CREATE TABLE ItemBought(
    itemId INTEGER          CONSTRAINT ItemBought_itemId_pk       PRIMARY KEY REFERENCES Item(itemId)
                                                                    ON UPDATE CASCADE
                                                                    ON DELETE RESTRICT,
    buyer INTEGER           CONSTRAINT ItemBought_buyer_fk        REFERENCES User(userId)
                                                                   ON UPDATE CASCADE
                                                                   ON DELETE CASCADE,
    delivered BOOLEAN       DEFAULT 0                              CONSTRAINT Item_delivered_ck    CHECK (delivered IN (0, 1))
);

CREATE TABLE Item (
    itemId INTEGER       CONSTRAINT Item_itemId_pk              PRIMARY KEY,
    categoryId INTEGER   CONSTRAINT Item_categoryId_fk          REFERENCES Category(categoryId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE RESTRICT,
    brand TEXT,
    model TEXT,
    size INTEGER            CONSTRAINT Item_sizeId_fk           REFERENCES Size(sizeId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE RESTRICT,

    condition INTEGER       CONSTRAINT Item_conditionId_fk      REFERENCES Condition(conditionId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE RESTRICT,

    price REAL           CONSTRAINT Item_price_nn               NOT NULL,
    description TEXT    CONSTRAINT Item_description             NOT NULL,
    seller INTEGER       CONSTRAINT Item_seller_fk              REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE

);

CREATE TABLE Chat (
    chatId INTEGER       CONSTRAINT Chat_chatId_pk              PRIMARY KEY,
    buyer INTEGER        CONSTRAINT Chat_buyer_fk               REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
    item INTEGER         CONSTRAINT Chat_item_fk                REFERENCES Item(itemId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE
);

CREATE TABLE Messages (
    msgId INTEGER        CONSTRAINT Messages_msgId_pk           PRIMARY KEY,
    sender INTEGER       CONSTRAINT Messages_sender_fk          REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
    timestamp DATETIME   CONSTRAINT Messages_timestamp_nn       NOT NULL,
    content TEXT         CONSTRAINT Messages_content_nn         NOT NULL,
    chat INTEGER         CONSTRAINT Messages_chat_fk            REFERENCES Chat(chatId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE
);

CREATE TABLE ShoppingCart (
    cartId INTEGER       CONSTRAINT ShoppingCart_cartId_pk      PRIMARY KEY,
    uid INTEGER          CONSTRAINT ShoppingCart_uid_fk         REFERENCES User(userId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
    total REAL           CONSTRAINT ShoppingCart_total_nn       NOT NULL,
    promo BOOLEAN        CONSTRAINT ShoppingCart_boolean        NOT NULL
);

CREATE TABLE CartItem (
    cartId INTEGER       CONSTRAINT CartItem_cartId_fk          REFERENCES ShoppingCart(cartId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
    itemId INTEGER       CONSTRAINT CartItem_itemId_fk          REFERENCES Item(itemId)
                                                                ON UPDATE CASCADE
                                                                ON DELETE CASCADE,
                         CONSTRAINT CartItem_cartId_itemId_pk   PRIMARY KEY (cartId, itemId)
);

CREATE TABLE Wishlist (
    wishlistID INTEGER       CONSTRAINT Wishlist_cartId_pk          PRIMARY KEY,
    uid INTEGER              CONSTRAINT Wishlist_uid_fk             REFERENCES User(userId)
                                                                    ON UPDATE CASCADE
                                                                    ON DELETE CASCADE
);

CREATE TABLE WishlistItem (
    wishlistID INTEGER       CONSTRAINT WishlistItem_wishListID_fk                  REFERENCES Wishlist(wishListID)
                                                                                    ON UPDATE CASCADE
                                                                                    ON DELETE CASCADE,
    itemId INTEGER           CONSTRAINT WishlistItem_itemId_fk                      REFERENCES Item(itemId)
                                                                                    ON UPDATE CASCADE
                                                                                    ON DELETE CASCADE,
                             CONSTRAINT WishlistItem_wishListID_itemId_pk           PRIMARY KEY (wishListID, itemId)
);

CREATE TABLE Size(
    sizeId INTEGER          CONSTRAINT Size_sizeId_pk                              PRIMARY KEY,
    sizeName TEXT           CONSTRAINT Size_sizeName_nn                            NOT NULL
);

CREATE TABLE Condition(
    conditionId INTEGER     CONSTRAINT Condition_conditionId_pk                    PRIMARY KEY,
    conditionName TEXT      CONSTRAINT Condition_conditionName_nn                  NOT NULL
);

CREATE TABLE ItemImage(
    itemImageId INTEGER     CONSTRAINT ItemImage_itemImageId_pk                    PRIMARY KEY,
    itemId INTEGER          CONSTRAINT ItemImage_itemId_fk                         REFERENCES Item(itemId)
                                                                                   ON UPDATE CASCADE
                                                                                   ON DELETE CASCADE
);

CREATE TABLE UserImage(
    userImageId INTEGER     CONSTRAINT UserImage_userImageId_pk                    PRIMARY KEY,
    userId INTEGER          CONSTRAINT UserImage_userId_fk                         REFERENCES User(userId)
                                                                                   ON UPDATE CASCADE
                                                                                   ON DELETE CASCADE
);