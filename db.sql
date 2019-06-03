/*
  Authors: Hawon Park, Jeong Ho Shin, Sujeong Youn
*/

-- C:\Users\hawon\Documents\GitHub\cse305final\db.sql

-- DATABASE INITIALIZATION --
DROP DATABASE IF EXISTS auction_db;

CREATE DATABASE auction_db;

GRANT ALL PRIVILEGES ON auction_db.* to user@localhost identified by 'hey';

USE auction_db;

-- CREATE TABLES FOR DATABASE --
CREATE TABLE City(
  city VARCHAR(50),
  country VARCHAR(50),
  PRIMARY KEY(city)
);

CREATE TABLE User (
  email VARCHAR(20),
  first_name VARCHAR(20),
  last_name VARCHAR(20),
  password VARCHAR(20),
  phone_num VARCHAR(20),
  details VARCHAR(20),
  street VARCHAR(20),
  city VARCHAR(20),
  PRIMARY KEY(email),
  FOREIGN KEY(city) REFERENCES City(city)
);


-- CREATE TABLE Address (
--   phone_num VARCHAR(20),
  -- details VARCHAR(20),
  -- street VARCHAR(20),
  -- city VARCHAR(20),
--   PRIMARY KEY (phone_num),
--   FOREIGN KEY(phone_num) REFERENCES User(phone_num),
  -- FOREIGN KEY(city) REFERENCES City(city)
-- );

-- SHOW ERRORS;

-- SHOW ENGINE INNODB STATUS;
CREATE TABLE Sellprice_To_Bid(
  sellprice INTEGER,
  minbid INTEGER,
  PRIMARY KEY(sellprice)
);

CREATE TABLE Item_To_Category(
  iname VARCHAR(50),
  category VARCHAR(20),
  PRIMARY KEY(iname)
);

CREATE TABLE Item (
  iname VARCHAR(50),
  sellprice INTEGER,
  iid INTEGER,
  PRIMARY KEY(iid),
  FOREIGN KEY(sellprice) REFERENCES Sellprice_To_Bid(sellprice),
  FOREIGN KEY(iname) REFERENCES Item_To_Category(iname)
);

CREATE TABLE Sells(
  email VARCHAR(20),
  iid INTEGER,
  stock INTEGER,
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Buys (
  email VARCHAR(20),
  iid INTEGER,
  bdate DATE,
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Auction (
  email VARCHAR(20),
  iid INTEGER,
  curr_bid INTEGER,
  start_date DATE,
  end_date DATE,
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

-- CREATE TABLE Notification (
--   email VARCHAR(20),
--   iid INTEGER,
--   ncontent VARCHAR(20),
--   nnumber VARCHAR(20),
--   PRIMARY KEY(email, iid),
--   FOREIGN KEY(email) REFERENCES User(email),
--   FOREIGN KEY(iid) REFERENCES Item(iid)
-- );

CREATE TABLE Discussion (
  email VARCHAR(20),
  iid INTEGER,
  thread INTEGER NOT NULL AUTO_INCREMENT,
  comment_date DATE,
  comment VARCHAR(64),
  PRIMARY KEY(thread),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Review (
  email VARCHAR(20),
  iid INTEGER,
  rating INTEGER,
  rcontent VARCHAR(64),
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Wishlist (
  email VARCHAR(20),
  iid INTEGER,
  likes INTEGER,
  wcontent VARCHAR(64),
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

-- POPULATE TABLES --
-- user 1
INSERT INTO City(city, country) VALUES ("Songdo", "South Korea");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Hawon", "Park", "ps1", "hawonp@gmail.com", "991", "A535", "Moonwharo-119", "Songdo");

-- user 2
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Jeong Ho", "Shin", "ps2", "topfrag@gmail.com", "119", "A516", "Moonwharo-119", "Songdo");

-- user 3
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Suhyun", "Shin", "ps3", "lol@gmail.com", "919", "A516", "Moonwharo-119", "Songdo");

-- Item 0
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (399000, 299250);
INSERT INTO Item_To_Category (iname, category) VALUES ("SENNHEISER Momentum True Wireless", "True wireless earphones");
INSERT INTO Item (iname, sellprice, iid) VALUES ("SENNHEISER Momentum True Wireless", 399000, 1);

-- Item 1
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (35000, 26250);
INSERT INTO Item_To_Category (iname, category) VALUES ("LYPERTEK Bevi", "Wired earphones");
INSERT INTO Item (iid, iname, sellprice) VALUES (2, "LYPERTEK Bevi", 35000);

-- Item 2
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (180000, 135000);
INSERT INTO Item_To_Category (iname, category) VALUES ("JAYBIRD Tarah Pro", "Sports earphones");
INSERT INTO Item (iid, iname, sellprice) VALUES (3, "JAYBIRD Tarah Pro", 180000);

--Adding Items 3 - 
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (110000, 82500);
INSERT INTO Item_To_Category (iname, category) VALUES ("CORSAIR Void Pro RGB Wireless", "Gaming headphones");
INSERT INTO Item (iid, iname, sellprice) VALUES (4, "CORSAIR Void Pro RGB Wireless", 110000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (1900000, 1425000);
INSERT INTO Item_To_Category (iname, category) VALUES ("SENNHEISER HD800", "Studio monitor headphones");
INSERT INTO Item (iid, iname, sellprice) VALUES (5, "SENNHEISER HD800", 1900000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (350000, 262500);
INSERT INTO Item_To_Category (iname, category) VALUES ("SONY WH-1000XM3", "Noise cancelling headphones");
INSERT INTO Item (iid, iname, sellprice) VALUES (6, "SONY WH-1000XM3", 350000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (2800000, 2100000);
INSERT INTO Item_To_Category (iname, category) VALUES ("ASTELL & KERN AK240", "Digital audio player");
INSERT INTO Item (iid, iname, sellprice) VALUES (7, "ASTELL & KERN AK240", 2800000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (32000000, 24000000);
INSERT INTO Item_To_Category (iname, category) VALUES ("BOWERS & WILKINS 802 D3", "Floorstanding speaker");
INSERT INTO Item (iid, iname, sellprice) VALUES (8, "BOWERS & WILKINS 802 D3", 32000000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (1300000, 975000);
INSERT INTO Item_To_Category (iname, category) VALUES ("KEF LS50", "Bookshelf speaker");
INSERT INTO Item (iid, iname, sellprice) VALUES (9, "KEF LS50", 1300000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (220000, 165000);
INSERT INTO Item_To_Category (iname, category) VALUES ("YAMAHA YAS-108", "Sound bar");
INSERT INTO Item (iid, iname, sellprice) VALUES (10, "YAMAHA YAS-108", 220000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (380000, 285000);
INSERT INTO Item_To_Category (iname, category) VALUES ("DENON DP-300F", "Turntable");
INSERT INTO Item (iid, iname, sellprice) VALUES (11, "DENON DP-300F", 380000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (330000, 247500);
INSERT INTO Item_To_Category (iname, category) VALUES ("ONKYO A-9030", "Integrated Amplifier");
INSERT INTO Item (iid, iname, sellprice) VALUES (12, "ONKYO A-9030", 330000);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (330000, 247500);
INSERT INTO Item_To_Category (iname, category) VALUES ("JBL Studio 520C", "Center speaker");
INSERT INTO Item (iid, iname, sellprice) VALUES (13, "JBL Studio 520C", 330000);

--User 1 selling Item 0
INSERT INTO Sells (email, iid, stock) VALUES ("hawonp@gmail.com", 1, 5);

--User 1 selling Item 1
INSERT INTO Sells (email, iid, stock) VALUES ("hawonp@gmail.com", 2, 2);

--User 2 selling Item 3
INSERT INTO Sells (email, iid, stock) VALUES ("topfrag@gmail.com", 3, 10);

--Auction 0: Item 0, user 1
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("topfrag@gmail.com", 1, 360000, "2000-03-06", "2000-03-12");
--Auction 1: Item 1, user 1
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("topfrag@gmail.com", 2, 360000, "2000-02-01", "2000-03-7");

--Buy 0:
INSERT INTO Buys (email, iid, bdate) VALUES ("lol@gmail.com", 1, "2000-03-05");

-- TEST SQL QUERIES --
SELECT * FROM User;
--
SELECT * FROM City;

-- SELECT * FROM Address;

SELECT * FROM Auction;

SELECT * FROM Buys;

SELECT * FROM Item_To_Category;
