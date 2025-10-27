USE job_poster;

INSERT INTO
    LOGIN (Email, Password, Role, Name, Avatar)
VALUES
    (
        'user1@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User One',
        NULL
    ),
    (
        'user2@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Two',
        NULL
    ),
    (
        'user3@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Three',
        NULL
    ),
    (
        'admin1@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Admin',
        'Admin One',
        NULL
    ),
    (
        'admin2@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Admin',
        'Admin Two',
        NULL
    ),
    (
        'user4@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Four',
        NULL
    ),
    (
        'user5@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Five',
        NULL
    ),
    (
        'admin3@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Admin',
        'Admin Three',
        NULL
    ),
    (
        'user6@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Six',
        NULL
    ),
    (
        'user7@example.com',
        '$2y$10$O5iit7HS7r3xemxSFW2gBuDPnQcgnVShE6BLjcIyn4DCBPE.48ejy',
        'Customer',
        'User Seven',
        NULL
    );

INSERT INTO DISCOUNT_COUPON (`Code`, `MoneyDeduct`, `Condition`, `Quantity`, `Status`)
VALUES
    ('GIAM10K', 10000.00, 'Female', 50, 'Activate'),
    ('GIAM50K', 50000.00, '> 200000', 50, 'Activate'),
    ('GIAM30%', 30.00, 'Chẵn', 50, 'Outdated'),
    ('GIAM50%', 50.00, '< 500000', 50, 'Activate'),
    ('GIAMCOD', 25000.00, 'COD', 50, 'Outdated'),
    ('FREESHIP', 0.00, 'Tất cả', 50, 'Activate'),
    ('GIAM15K', 15000.00, 'Đơn > 150000', 50, 'Activate'),
    ('GIAM20K', 20000.00, 'Đơn > 200000', 50, 'Activate'),
    ('GIAM5%', 5.00, 'Tất cả', 50, 'Activate'),
    ('GIAM25K', 25000.00, 'Đơn > 250000', 50, 'Activate');