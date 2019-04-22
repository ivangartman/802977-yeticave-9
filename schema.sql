-- Создаём БД и производим вход
CREATE DATABASE YetiCave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE YetiCave;

-- Создаём таблицу, которая содержит зпрегистрированных пользователей
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

-- Создаём таблицу, которая содержит котегории
CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name CHAR(120) NOT NULL UNIQUE,
  code CHAR(60) NOT NULL UNIQUE
);
CREATE INDEX c_name ON category(name);

-- Создаём таблицу, которая содержит разыгрываемые лоты
CREATE TABLE lots (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_user INT(11) NOT NULL,
  id_category INT(11) NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  name CHAR(120) NOT NULL,
  content TEXT,
  picture TEXT,
  price INT(11) NOT NULL,
  date_end TIMESTAMP,
  step_rate INT(11)
);
CREATE INDEX l_name ON lots(name);

-- Создаём таблицу, которая содержит ставки пользователей
CREATE TABLE rate (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_user INT(11) NOT NULL,
  id_lot INT(11) NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  price INT(11) NOT NULL
);
