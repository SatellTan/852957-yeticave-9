CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;


CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name CHAR(64) NOT NULL,
  class CHAR(64) NOT NULL
);

CREATE UNIQUE INDEX name ON categories(name);
CREATE INDEX class ON categories(class);


CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  creation_date DATETIME NOT NULL,
  name CHAR(128) NOT NULL,
  description TEXT,
  img_URL CHAR(128),
  start_price INT NOT NULL,
  finish_date DATETIME NOT NULL,
  bid_step INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL
);

CREATE INDEX name ON lots(name);
CREATE INDEX creation_date ON lots(creation_date);
CREATE INDEX start_price ON lots(start_price);
CREATE INDEX finish_date ON lots(finish_date);
CREATE INDEX author_id ON lots(author_id);
CREATE INDEX category_id ON lots(category_id);


CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  bid_date DATETIME NOT NULL,
  price INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE INDEX user_id ON bids(user_id);
CREATE INDEX lot_id ON bids(lot_id);


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  registration_date DATETIME NOT NULL,
  email CHAR(128) NOT NULL,
  name CHAR(64) NOT NULL,
  password CHAR(64) NOT NULL,
  avatar_url CHAR(128),
  contacts CHAR(128),
  lot_id INT NOT NULL,
  bid_id INT NOT NULL
);

CREATE INDEX id ON users(id);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX name ON users(name);
