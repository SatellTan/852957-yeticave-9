CREATE DATABASE yeticave_852957
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave_852957;


CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(64) UNIQUE NOT NULL,
  class VARCHAR(64) NOT NULL
);


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(64) NOT NULL,
  password VARCHAR(64) NOT NULL,
  avatar_url VARCHAR(128),
  contacts TEXT
);


CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  creation_date TIMESTAMP NOT NULL,
  name VARCHAR(128) NOT NULL,
  description TEXT,
  img_URL VARCHAR(128),
  start_price INT NOT NULL,
  finish_date TIMESTAMP NOT NULL,
  bid_step INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL,
  FULLTEXT (name, description),
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);


CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  bid_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  price INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);
