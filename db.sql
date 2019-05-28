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
CREATE TABLE Address (
  aid INTEGER,
  details VARCHAR(20),
  street VARCHAR(20),
  city VARCHAR(20),
  PRIMARY KEY (aid)
);

CREATE TABLE User (
  uid INTEGER,
  first_name VARCHAR(20),
  last_name VARCHAR(20),
  email VARCHAR(20),
  phone_num VARCHAR(20),
  aid INTEGER,
  PRIMARY KEY(uid),
  FOREIGN KEY(aid) REFERENCES Address(aid)
);

CREATE TABLE City(
  city VARCHAR(50),
  country VARCHAR(50),
  PRIMARY KEY(city)
);

CREATE TABLE Item (
  iid INTEGER,
  iname VARCHAR(20),
  sellprice INTEGER,
  PRIMARY KEY(iid)
);

CREATE TABLE Item_To_Category(
  iname VARCHAR(20),
  category VARCHAR(20),
  PRIMARY KEY(iname)
);

CREATE TABLE Sellprice_To_Bid(
  sellprice INTEGER,
  minbid INTEGER,
  PRIMARY KEY(sellprice)
);

CREATE TABLE Sells(
  uid INTEGER,
  iid INTEGER,
  stock INTEGER,
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Buys (
  uid INTEGER,
  iid INTEGER,
  bdate DATE,
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Auction (
  uid INTEGER,
  iid INTEGER,
  curr_bid INTEGER,
  start_date DATE,
  end_date DATE,
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

-- CREATE TABLE Notification (
--   uid INTEGER,
--   iid INTEGER,
--   ncontent VARCHAR(20),
--   nnumber VARCHAR(20),
--   PRIMARY KEY(uid, iid),
--   FOREIGN KEY(uid) REFERENCES User(uid),
--   FOREIGN KEY(iid) REFERENCES Item(iid)
-- );

CREATE TABLE Discussion (
  uid INTEGER,
  iid INTEGER,
  thread INTEGER,
  comment_date DATE,
  comment VARCHAR(64),
  PRIMARY KEY(thread),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Review (
  uid INTEGER,
  iid INTEGER,
  rating FLOAT,
  rcontent VARCHAR(64),
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Wishlist (
  uid INTEGER,
  iid INTEGER,
  likes INTEGER,
  wcontent VARCHAR(64),
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

-- POPULATE TABLES --
-- user 1
INSERT INTO Address (aid, details, street, city) VALUES (001, "A535", "Moonwharo-119", "Songdo");
INSERT INTO City(city, country) VALUES ("Songdo", "South Korea");
INSERT INTO User(uid, first_name, last_name, email, phone_num, aid)
  VALUES (9898, "Hawon", "Park", "bottomfrag@gmail.com", "991", 001);

-- user 2
INSERT INTO Address (aid, details, street, city) VALUES (002, "A516", "Moonwharo-119", "Songdo");

INSERT INTO User(uid, first_name, last_name, email, phone_num, aid)
  VALUES (9899, "Jeong Ho", "Shin", "topfrag@gmail.com", "119", 002);

-- user 3
INSERT INTO User(uid, first_name, last_name, email, phone_num, aid)
  VALUES (9900, "Suhyun", "Shin", "lol@gmail.com", "919", 002);


-- Item 0
INSERT INTO Item (iid, iname, sellprice) VALUES (001, "SENNHEISER MOMENTUM True Wireless", 399000);
INSERT INTO Item_To_Category (iname, category) VALUES ("SENNHEISER MOMENTUM True Wireless", "earbuds");


--User 1 selling Item 0
INSERT INTO Sells (uid, iid, stock) VALUES (9898, 001, 5);
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (399000, 359100);

--Auction 0: Item 0, user 1
INSERT INTO Auction (uid, iid, curr_bid, start_date, end_date) VALUES (9899, 001, 360000, "2000-03-06", "2000-03-12");


--Buy 0: 
INSERT INTO Buys (uid, iid, bdate) VALUES (9900, 001, "2000-03-05");



-- TEST SQL QUERIES --
SELECT * FROM User;

SELECT * FROM City;

SELECT * FROM Address;
