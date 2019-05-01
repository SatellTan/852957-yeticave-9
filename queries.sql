INSERT INTO categories
  (name, class)
VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');


INSERT INTO users
  (email, name, password, contacts)
VALUES
  ('My555@yandex.ru', 'Elena_J', '852258', 'UK, Edinburg city, 125, tel: 65456565654'),
  ('Rex_Terr@yandex.ru', 'Rex_Terr', 'kkkkkk', 'US, New-York city, 296-53, tel: 9612322322');


INSERT INTO lots
  (creation_date, name, description, img_URL, start_price, finish_date, bid_step, author_id, category_id)
VALUES
  (CURRENT_TIMESTAMP - INTERVAL 15 DAY, '2014 Rossignol District Snowboard', 'Крутой сноуборд', 'img/lot-1.jpg', 10999, CURRENT_TIMESTAMP + INTERVAL 15 DAY, 500, 1, 1),
  (CURRENT_TIMESTAMP - INTERVAL 25 DAY, 'DC Ply Mens 2016/2017 Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив
            снег
            мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот
            снаряд
            отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
            кэмбер
            позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,
            просто
            посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла
            равнодушным.', 'img/lot-2.jpg', 159999, CURRENT_TIMESTAMP + INTERVAL 10 DAY, 3000, 1, 1),
  (CURRENT_TIMESTAMP - INTERVAL 12 DAY, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Классические удобные крепления', 'img/lot-3.jpg', 8000, CURRENT_TIMESTAMP + INTERVAL 12 DAY, 200, 1, 2),
  (CURRENT_TIMESTAMP - INTERVAL 5 DAY, 'Ботинки для сноуборда DC Mutiny Charocal', 'Практически новые ботинки', 'img/lot-4.jpg', 10999, CURRENT_TIMESTAMP + INTERVAL 50 DAY, 200, 2, 3),
  (CURRENT_TIMESTAMP - INTERVAL 10 DAY, 'Куртка для сноуборда DC Mutiny Charocal', 'Красивая удобная куртка', 'img/lot-5.jpg', 7500, CURRENT_TIMESTAMP + INTERVAL 25 DAY, 200, 2, 4),
  (CURRENT_TIMESTAMP - INTERVAL 1 MONTH, 'Маска Oakley Canopy', 'Брендовая маска', 'img/lot-6.jpg', 5400, CURRENT_TIMESTAMP - INTERVAL 2 DAY, 100, 2, 6);


INSERT INTO bids
  (bid_date, price, user_id, lot_id)
VALUES
  (CURRENT_TIMESTAMP - INTERVAL 4 DAY, 8200, 1, 3),
  (CURRENT_TIMESTAMP - INTERVAL 2 DAY, 8400, 2, 3);


/* Запрос для получения всех категорий */
SELECT * FROM categories;

/* Запрос для получения самых новых, открытых лотов.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */
SELECT l.name, l.start_price, l.img_URL, coalesce(MAX(b.price), l.start_price) AS price, c.name AS category
FROM lots l
LEFT JOIN bids b ON b.lot_id = l.id
INNER JOIN categories c ON l.category_id = c.id
WHERE l.finish_date > CURRENT_TIMESTAMP
GROUP BY l.id
ORDER BY l.creation_date DESC;

/* Запрос для получения лота по его id. Получить также название категории, к которой принадлежит лот */
SELECT l.name, c.name FROM lots l
JOIN categories c
ON l.category_id = c.id
WHERE l.id = 4;

/* Запрос для обновления названия лота по его идентификатору */
UPDATE lots SET name = 'New name of the lot 4' WHERE id = 4;

/* Запрос для получения самых свежих ставок для лота по его идентификатору */
SELECT bid_date FROM bids
WHERE lot_id = 4
ORDER BY bid_date DESC
LIMIT 2;
