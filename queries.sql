-- Заполнение таблицы пользователей 'users'
USE YetiCave;
INSERT INTO users (email, name, password, contact)
VALUES ('as@mail.ru', 'Анатолий Костюк', '147zaq', 'г. Обь'),
       ('af@mail.ru', 'Александр Фролов', '147az', 'г. Барнаул');

-- Заполнение таблицы категорий 'category'
INSERT INTO category (name, code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки ', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

-- Заполнение таблицы с лотами 'lots'
INSERT INTO lots (user_id, category_id, name, content, picture_url, price, date_end, step_rate)
VALUES ('1', '1', '2014 Rossignol District Snowboard', 'Описание лота', 'img/lot-1.jpg', '10999', '2019.04.25', '1'),
       ('1', '1', 'DC Ply Mens 2016/2017 Snowboard', 'Описание лота', 'img/lot-2.jpg', '159999', '2019.04.26', '1'),
       ('2', '2', 'Крепления Union Contact Pro 2015 года размер L/X', 'Описание лота', 'img/lot-3.jpg', '8000', '2019.04.24', '1'),
       ('1', '3', 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание лота', 'img/lot-4.jpg', '10999', '2019.04.28', '1'),
       ('2', '4', 'Куртка для сноуборда DC Mutiny Charocal', 'Описание лота', 'img/lot-5.jpg', '7500', '2019.04.23', '1'),
       ('2', '6', 'Маска Oakley Canopy', 'Описание лота', 'img/lot-6.jpg', '5400', '2019.04.29', '1');

-- Заполнение таблицы ставки 'rate'
INSERT INTO rate (user_id, lot_id, price)
VALUES ('2', '1', '11000'),
       ('2', '2', '16000'),
       ('1', '3', '8100'),
       ('2', '4', '11500'),
       ('1', '5', '7800');


-- ПИШЕМ ЗАПРОСЫ
-- Выводим все категории
SELECT name FROM category; -- Получаем содержимое столбца name в таблице category

-- Получаем самые новые, открытые лоты. Каждый лот включает название, стартовую цену, ссылку на изображение, цену, название категории
SELECT l.name, l.price, l.picture_url, r.price, cat.name FROM lots l /*выводим содержимое столбцов l.name, l.price, l.picture_url(из табл.lots), r.price(из табл.rate), cat.name(из табл.category)*/
JOIN category cat ON l.category_id = cat.id /*связываем таблицу lots с таблицей category по id*/
LEFT JOIN rate r ON r.lot_id = l.id /*связываем таблицу lots с таблицей rate по id(если условие не выполнится то всё равно вывод произойдёт=NULL)*/
WHERE l.date_add > '2019-04-22 12:47:43'; /*Ставим фильтр по дате (в данном случае выдаст все лоты т.к они созданны в одно время)*/

-- Показываем лот по его id. Также получаем название категории, к которой принадлежит лот
SELECT l.name, cat.name FROM lots l /*выводим содержимое столбцов l.name(из табл.lots) и cat.name(из табл.category)*/
INNER JOIN category cat ON l.category_id = cat.id /*связываем таблицу lots с таблицей category по id*/
WHERE l.id = 5; /*находим id с номером 5 в таб. lots*/

-- Переписываем название лота по его идентификатору
UPDATE lots SET name = 'DC Ply Mens 2018/2019 Snowboard' WHERE id = 2;

-- Получаем список самых свежих ставок для лота по его идентификатору
SELECT price FROM rate
ORDER BY id DESC LIMIT 3; /*выполняем обратную сортировку по id в табл. rate и выводим первые 3 записи*/