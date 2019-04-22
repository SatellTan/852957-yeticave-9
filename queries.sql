INSERT INTO categories (name, class) VALUES ('Доски и лыжи', 'boards');
INSERT INTO categories (name, class) VALUES ('Крепления', 'attachment');
INSERT INTO categories (name, class) VALUES ('Ботинки', 'boots');
INSERT INTO categories (name, class) VALUES ('Одежда', 'clothing');
INSERT INTO categories (name, class) VALUES ('Инструменты', 'tools');
INSERT INTO categories (name, class) VALUES ('Разное', 'other');


INSERT INTO users (email, name, password, contacts)
VALUES ('My555@yandex.ru', 'Elena_J', '852258', 'UK, Edinburg city, 125, tel: 65456565654');

INSERT INTO users (email, name, password, contacts)
VALUES ('Rex_Terr@yandex.ru', 'Rex_Terr', 'kkkkkk', 'US, New-York city, 296-53, tel: 9612322322');


INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, '2014 Rossignol District Snowboard', '', 'img/lot-1.jpg', 10999, CURRENT_TIMESTAMP, 500, 4, 1);

INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, 'DC Ply Mens 2016/2017 Snowboard', '', 'img/lot-2.jpg', 159999, CURRENT_TIMESTAMP, 3000, 4, 1);

INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, 'Крепления Union Contact Pro 2015 года размер L/XL', '', 'img/lot-3.jpg', 8000, CURRENT_TIMESTAMP, 200, 4, 2);

INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, 'Ботинки для сноуборда DC Mutiny Charocal', '', 'img/lot-4.jpg', 10999, CURRENT_TIMESTAMP, 200, 5, 3);

INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, 'Куртка для сноуборда DC Mutiny Charocal', '', 'img/lot-5.jpg', 7500, CURRENT_TIMESTAMP, 200, 5, 4);

INSERT INTO lots (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES (CURRENT_TIMESTAMP, 'Маска Oakley Canopy', '', 'img/lot-6.jpg', 5400, CURRENT_TIMESTAMP, 100, 5, 6);


INSERT INTO bids (bid_date, price, user_id, lot_id) VALUES (CURRENT_TIMESTAMP, 8200, 4, 6);
INSERT INTO bids (bid_date, price, user_id, lot_id) VALUES (CURRENT_TIMESTAMP, 8400, 5, 6);


/* Запрос для получения всех категорий */
SELECT name FROM categories;

/* Запрос для получения самых новых, открытых лотов */


/* Запрос для получения лота по его id. Получить также название категории, к которой принадлежит лот */
SELECT name FROM lots
WHERE lot_id = 6;

/* Запрос для обновления названия лота по его идентификатору */
UPDATE lots SET name = 'New name of lot' WHERE id = 6;

/* Запрос для получения самых свежих ставок для лота по его идентификатору */
SELECT bid_date FROM bids LIMIT 2
WHERE lot_id = 6
ORDER BY bid_date DESC;
