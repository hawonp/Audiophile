/*
  Authors: Hawon Park, Jeong Ho Shin, Sujeong Youn
*/

-- DATABASE INITIALIZATION --
DROP DATABASE IF EXISTS auction_db;

CREATE DATABASE auction_db;

GRANT ALL PRIVILEGES ON auction_db.* to user@localhost identified by 'hey';

USE auction_db;

-- CREATE TABLES FOR DATABASE --
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

CREATE TABLE Address (
  aid INTEGER,
  details VARCHAR(20),
  street VARCHAR(20),
  city VARCHAR(20),
  PRIMARY KEY (aid)
);

CREATE TABLE City(
  city VARCHAR(20),
  country VARCHAR(20),
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
  FOREIGN KEY(iid) REFERENCES Item(iid)
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
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Review (
  uid INTEGER,
  iid INTEGER,
  rating FLOAT,
  rcontent VARCHAR(64),
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Wishlist (
  uid INTEGER,
  iid INTEGER,
  likes INTEGER,
  wcontent VARCHAR(64),
  PRIMARY KEY(uid, iid),
  FOREIGN KEY(uid) REFERENCES User(uid),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

-- POPULATE TABLES --
