-- Создаём БД и производим вход
CREATE DATABASE YetiCave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE YetiCave;

-- Создаём таблицу, которая содержит зарегистрированных пользователей
CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  email CHAR(60) NOT NULL UNIQUE,
  name CHAR(60) NOT NULL,
  password CHAR(60) NOT NULL UNIQUE,
  avatar CHAR(120),
  contact CHAR(120)
);
CREATE INDEX u_name ON users(name);

-- Создаём таблицу, которая содержит категории
CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name CHAR(120) NOT NULL UNIQUE,
  code CHAR(60) NOT NULL UNIQUE
);
CREATE INDEX c_name ON category(name);

-- Создаём таблицу, которая содержит разыгрываемые лоты
CREATE TABLE lots (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  category_id INT(11) NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  name CHAR(120) NOT NULL,
  content TEXT,
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
