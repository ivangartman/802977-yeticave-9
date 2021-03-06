-- Создаём БД и производим вход
CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;

-- Создаём таблицу, которая содержит зарегистрированных пользователей
CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  name VARCHAR(60) NOT NULL,
  password VARCHAR(120) NOT NULL,
  avatar VARCHAR(120),
  contact VARCHAR(120)
);
CREATE INDEX u_name ON users(name);

-- Создаём таблицу, которая содержит категории
CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  code VARCHAR(60) NOT NULL UNIQUE
);
CREATE INDEX c_name ON category(name);

-- Создаём таблицу, которая содержит разыгрываемые лоты
CREATE TABLE lots (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  category_id INT(11) NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  name VARCHAR(120) NOT NULL,
  content TEXT(500),
  picture_url TEXT,
  price INT(11) NOT NULL,
  date_end DATETIME,
  step_rate INT(11),
  winner_id INT(11)
);
CREATE INDEX l_name ON lots(name);
CREATE FULLTEXT INDEX name_content ON lots(name, content);

-- Создаём таблицу, которая содержит ставки пользователей
CREATE TABLE rates (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  lot_id INT(11) NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  price INT(11) NOT NULL
);
