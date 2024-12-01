
PRAGMA foreign_keys = ON;

INSERT INTO Continents (continentId, continentName)
VALUES
    (0, 'Europe'),
    (1, 'Africa'),
    (2, 'Antarctica'),
    (3, 'Asia'),
    (4, 'North America'),
    (5, 'Oceania'),
    (6, 'South America');

INSERT INTO User (userId, email, password, username, name, favCurrency, isAdmin, continentId)
VALUES
    (1, 'john.doe@example.com', '$2y$10$TgC8.K3yUYmkotL0fS52GewamdB/YjB50aU26QkyuJqeMEZr8LJ7S', 'johndoe', 'John Doe', 0, 0, 0), -- 'Password123!'
    (2, 'jane.smith@example.com', '$2y$10$tdmJkeeFnqfPNbBRKuuIt.S5er8.zNGU32oYP4sE2vH.sTxAzEbdy', 'janesmith', 'Jane Smith', 0, 0, 1), -- 'Letmein!1'
    (3, 'admin@admin.com', '$2y$10$oLbU.sV4mh1QXYwBvyEfwez1ci9/UCW/tnZbEkOZtVVJ4nF3CPpla', 'admin', 'Admin User', 0, 1, 2), -- 'Adminpassword1!'
    (4, 'michael.jackson@example.com', '$2y$10$xfoIkiGa/XRKfDFZIIOavO3I1eWX9/dchj0oLeLupFgZprW1B.Xi2', 'mjackson', 'Michael Jackson', 0, 0, 3), -- 'Kingofpop1!'
    (5, 'maria.garcia@example.com', '$2y$10$nacYfJWgrrpTFBASCmKeNufJ0jP6cJbJdhSO7YaA.KiBLipCfovzu', 'mariagarcia', 'Maria Garcia', 0, 0, 4), -- 'Password123!'
    (6, 'david.wilson@example.com', '$2y$10$MGUI0J5qI0cTCZoHDkBFQucOgBLVbqUtGrUGEMdMeK0o8aP8DtAPK', 'davidwilson', 'David Wilson', 0, 0, 5), -- 'Wilson123!'
    (7, 'emily.jones@example.com', '$2y$10$8d0i1FioJK87v/i.ezq0P.pBoIQV0cNKy2y5o3JC85O2EqNn7eaze', 'emilyjones', 'Emily Jones', 0, 0, 6), -- 'Emily123!'
    (8, 'james.brown@example.com', '$2y$10$XT4d.Gr/JutaK92N4zNAe.fwRC9eaoPBrtSSX9OdyG7iQ1t7mUGoa', 'jamesbrown', 'James Brown', 0, 0, 0), -- 'Jbrown123!'
    (9, 'laura.taylor@example.com', '$2y$10$a9i.nt8SC255fUKc/bOPreuQnQNpqNWjD.Qantxb8BbrpXngieW8C', 'laurataylor', 'Laura Taylor', 0, 0, 1), -- 'Taylor123!'
    (10, 'william.thomas@example.com', '$2y$10$gnFWtdA12YfR5JEp9IC0geQYPqyhGHcrlHeaLck15DNl.s0geHDae', 'williamthomas', 'William Thomas', 0, 0, 2), -- 'Thomas123!'
    (11, 'alex.johnson@example.com', '$2y$10$nQyjAzLOK92RKvlIZnCVI.Hn4WhD.ulzyR4QK7ncjgQLCGzHUjmr.', 'alexjohnson', 'Alex Johnson', 0, 0, 3), -- 'Password123!'
    (12, 'olivia.white@example.com', '$2y$10$Ce4QeAjfrcDQpjQe2tWPA.boOKY09eb0tpljwZJqk76NmxiWCZf6K', 'oliviawhite', 'Olivia White', 0, 0, 4), -- 'Letmein!1'
    (13, 'chris.miller@example.com', '$2y$10$JAHwIwh5AFfCt07MwoTHrezYXwfLunWNWmxkxVOONBb0OMcitm.Zi', 'chrismiller', 'Chris Miller', 0, 0, 5), -- 'Password123!'
    (14, 'sophia.davis@example.com', '$2y$10$ErC/BIdyeF.r6kYxsabcre2oCV6mIAPBIDPAft0TqvNcm7BtEz05q', 'sophiadavis', 'Sophia Davis', 0, 0, 6), -- 'Sophia123!'
    (15, 'ryan.hernandez@example.com', '$2y$10$WgJs5HVRpDyGRxDxNV8MyeR2vArbFVb2n.E/iyWEsJnCtLt/FhZrq', 'ryanhernandez', 'Ryan Hernandez', 0, 0, 1), -- 'Ryan123!'
    (16, 'goncalo@gmail.com', '$2y$12$nuAZGBK00itnfY4Njg6rnuAd/BXr65ZRfUObGdG40K5o2bSmXb2Zq', 'goncalo18', 'Gonçalo Oliveira', 0, 1, 0); -- 'Password123!'

INSERT INTO Address (idAddress, userId, address, postalCode)
VALUES
    (1, 1, '123 Main St', '12345'),
    (2, 2, '456 Elm St', '67890'),
    (3, 3, '789 Oak St', '45678'),
    (4, 4, '101 Pine St', '23456'),
    (5, 5, '202 Maple St', '78901'),
    (6, 6, '303 Cedar St', '34567'),
    (7, 7, '404 Walnut St', '89012'),
    (8, 8, '505 Birch St', '56789'),
    (9, 9, '606 Elm St', '12345'),
    (10, 10, '707 Oak St', '67890'),
    (11, 11, '809 Pine St', '23456'),
    (12, 12, '910 Cedar St', '34567'),
    (13, 13, '111 Walnut St', '45678'),
    (14, 14, '212 Maple St', '56789'),
    (15, 15, '313 Elm St', '67890'),
    (16, 16, 'Rua Dr. Roberto Frias', '4200-465');

INSERT INTO Review (idReview, grade, userId, sellerId)
VALUES
    (1, 5, 1, 2),
    (2, 4, 2, 1),
    (3, 3, 3, 4),
    (4, 5, 4, 3),
    (5, 4, 5, 6),
    (6, 5, 6, 5),
    (7, 4, 7, 6),
    (8, 3, 8, 7),
    (9, 5, 9, 10),
    (10, 4, 10, 9),
    (11, 5, 11, 10),
    (12, 4, 12, 6),
    (13, 3, 13, 14),
    (14, 5, 14, 13),
    (15, 4, 15, 4);

INSERT INTO Category (categoryId, categoryName)
VALUES
    (1, 'Electronics'),
    (2, 'Clothing'),
    (3, 'Home & Garden'),
    (4, 'Books'),
    (5, 'Toys & Games'),
    (6, 'Sports & Outdoors'),
    (7, 'Health & Beauty'),
    (8, 'Jewelry'),
    (9, 'Automotive'),
    (10, 'Movies & Music'),
    (11, 'Food & Beverage');

INSERT INTO Size (sizeId, sizeName)
VALUES
    (1,''),
    (2,'XL'),
    (3,'M'),
    (4,'Size 5'),
    (5,'XS'),
    (6,'1 lb');

INSERT INTO Condition(conditionId, conditionName)
VALUES
    (1,'New'),
    (2,'Used');

INSERT INTO Item (itemId, categoryId, brand, model, size, condition, price, seller, description)
VALUES
    (1, 1, 'Samsung', 'Galaxy S20', 2, 1, 799.99, 1, 'A top-of-the-line Samsung Galaxy S20 smartphone, featuring a stunning display, powerful camera, and lightning-fast performance. This device is in like-new condition, ready to provide you with the latest in mobile technology at a great price.'),
    (2, 2, 'Nike', 'Air Max', 3, 2, 99.99, 2, 'These Nike Air Max shoes offer both style and comfort, with their iconic design and reliable performance. In good condition, they''re perfect for everyday wear or hitting the gym.'),
    (3, 3, 'KitchenAid', 'Stand Mixer', 1, 1, 249.99, 3, 'A KitchenAid Stand Mixer, the ultimate kitchen companion for baking enthusiasts and culinary professionals alike. This mixer is in like-new condition, ready to whip up delicious treats with ease.'),
    (4, 4, 'George Orwell', '1984', 1, 2, 12.99, 4, 'George Orwell''s timeless dystopian classic, "1984," in good condition. Explore the chilling world of Big Brother and thought control in this thought-provoking novel.'),
    (5, 5, 'LEGO', 'Star Wars Millennium Falcon', 1, 1, 199.99, 5, 'Build your own adventures in a galaxy far, far away with this LEGO Star Wars Millennium Falcon set. In like-new condition, it''s ready to transport you to the epic battles of the Star Wars universe.'),
    (6, 6, 'Nike', 'Football', 4, 1, 29.99, 6, 'Get ready to hit the field with this Nike football. Designed for optimal performance and durability, it''s in like-new condition, perfect for practice sessions or competitive games.'),
    (7, 1, 'Apple', 'iPhone 13', 5, 1, 999.99, 7, 'The latest in Apple''s lineup, the iPhone 13, offers cutting-edge features and sleek design. This device is in like-new condition, providing you with the ultimate smartphone experience.'),
    (8, 2, 'Adidas', 'Superstar', 3, 1, 79.99, 7, 'Step out in style with these Adidas Superstar shoes. In like-new condition, they offer classic Adidas comfort and iconic design, perfect for any casual outing.'),
    (9, 3, 'Dyson', 'Cordless Vacuum Cleaner', 1, 1, 399.99, 9, 'Experience the power and convenience of a Dyson cordless vacuum cleaner. This innovative device, in like-new condition, makes cleaning a breeze with its lightweight and powerful suction.'),
    (10, 4, 'J.K. Rowling', 'Harry Potter and the Sorcerers Stone', 1, 2, 15.99, 10, 'Enter the enchanting world of Harry Potter with J.K. Rowling''s beloved novel, "Harry Potter and the Sorcerer''s Stone." In good condition, this book is perfect for fans of all ages.'),
    (11, 7, 'L''Oréal', 'Revitalift Anti-Aging Cream', 1, 1, 29.99, 10, 'Rejuvenate your skin with L''Oréal''s Revitalift Anti-Aging Cream. In like-new condition, this skincare essential helps reduce the appearance of wrinkles and promotes a more youthful complexion.'),
    (12, 8, 'Pandora', 'Charm Bracelet', 1, 1, 89.99, 13, 'Add a touch of elegance to any outfit with this Pandora Charm Bracelet. In like-new condition, it''s the perfect accessory for expressing your personal style and story.'),
    (13, 9, 'Toyota', 'Corolla', 1, 1, 19999.99, 13, 'Cruise the streets in style with this Toyota Corolla. In like-new condition, it offers reliability, fuel efficiency, and a smooth ride, making it the perfect choice for your daily commute.'),
    (14, 10, 'Warner Bros.', 'The Matrix Trilogy Blu-ray Set', 1, 1, 39.99, 14, 'Immerse yourself in the groundbreaking world of "The Matrix" with this Blu-ray set. In like-new condition, it includes all three films for hours of thrilling entertainment.'),
    (15, 11, 'Starbucks', 'Coffee Beans', 6, 1, 12.99, 15, 'Indulge in the rich and bold flavors of Starbucks coffee beans. Sourced from the finest coffee beans around the world, this blend promises a delightful coffee experience every time.');

INSERT INTO Chat (chatId, buyer, item)
VALUES
    (1, 1, 2),
    (2, 3, 4),
    (3, 5, 6),
    (4, 7, 9),
    (5, 9, 10),
    (6, 11, 6),
    (7, 13, 7),
    (8, 15, 8);


INSERT INTO Messages (msgId, sender, timestamp, content, chat)
VALUES
    (1, 1, '2024-04-07 14:00:00', 'Hello Jane, how are you?', 1),
    (2, 2, '2024-04-07 15:00:00', 'Hi John, Im doing well, thanks. How about you?', 1),
    (3, 3, '2024-04-07 16:00:00', 'Hey there, I have a question about the product.', 2),
    (4, 4, '2024-04-07 17:00:00', 'Sure, feel free to ask.', 2),
    (5, 5, '2024-04-07 18:00:00', 'Hi, Im interested in buying your item.', 3),
    (6, 6, '2024-04-07 19:00:00', 'Thats great! Let me know if you have any questions.', 3),
    (7, 7, '2024-04-07 20:00:00', 'Hello, can you confirm the shipping address?', 4),
    (8, 9, '2024-04-07 21:00:00', 'Yes, the address is correct.', 4),
    (9, 9, '2024-04-07 22:00:00', 'Hi, when can I expect the delivery?', 5),
    (10, 10, '2024-04-07 23:00:00', 'The delivery should arrive within 2-3 business days.', 5),
    (11, 6, '2024-04-08 10:00:00', 'Hello Alex, I hope youre having a great day!', 6),
    (12, 11, '2024-04-08 11:00:00', 'Hi David, thanks for reaching out. Im excited about our transaction!', 6),
    (13, 13, '2024-04-08 12:00:00', 'Hey Chris, do you have any updates?', 7),
    (14, 7, '2024-04-08 13:00:00', 'Sure Sophia, let me check and get back to you.', 7),
    (15, 15, '2024-04-08 14:00:00', 'Hi Ryan, I am interested in buying some items', 8);

INSERT INTO Wishlist (wishlistID, uid)
VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10),
    (11, 12),
    (12, 13),
    (13, 11),
    (14, 14),
    (15, 15);

INSERT INTO ItemImage (itemImageId, itemId)
VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10),
    (11, 11),
    (12, 12),
    (13, 13),
    (14, 14),
    (15, 15),
    (16, 8),
    (17, 8),
    (18, 8),
    (19, 13),
    (20, 13),
    (21, 1),
    (22, 1),
    (23, 4),
    (24, 12),
    (25, 9),
    (26, 9),
    (27, 5),
    (28, 10);

INSERT INTO UserImage (userImageId, userId)
VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6);
