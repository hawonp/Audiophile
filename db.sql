/*
  Authors:  Hawon Park    hawon.park@stonybrook.edu
            Jeong Ho Shin jeongho.shin@stonybrook.edu
            Sujeong Youn  sujeong.youn@stonybrook.edu

            This sql script forms the backbone of our web application.

            This script initializes the db tables, and populates them with a moderate amount of data.
            The reason why there is not an extensive amount of data hard coded within the script itself is
            that most of the functions of the web application dynamically populate the tables,
            or rely on the dynamically populated tables.

            The script also contains the following triggers:
              ItemLike
              ItemInAuction
              ItemSold
              ItemBid

            ItemLike triggers when an user likes a new item.
            As auctions are based on the number of likes an item has,
            the trigger adds an item to the Auction table every third like (in our case).

            Users are notified that an item they liked in on auction while the owner is notified that
            their item is now on auction.

            ItemsInAuction triggers after a new item is put on Auction.
            The web application limits the total number of ongoing auctions to 10, so this trigger is
            used to delete any new insertion to the Auction table when there are already 10 existing auctions.

            This is similiar to using ROLLBACK with procedures.

            The user who liked the last item that was deleted from the Auction table is notified as such.

            ItemSold triggers when an item is sold to a buyer.

            The total stock of the item is decreased by one, and proper transactions are made to the owner
            and buyer. If buyer does not live in the same country as the owner, a 10% shipping fee is included
            in the total price of the item.

            Both the owner and buyer are notified of the successful transation.

            ItemBid triggers upon a successful bid on an ongoing auction. The user who made the highest bid
            is notified that he is now the highest bidder.
*/

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
  email VARCHAR(50),
  first_name VARCHAR(20),
  last_name VARCHAR(20),
  password VARCHAR(20),
  phone_num VARCHAR(20),
  details VARCHAR(50),
  street VARCHAR(50),
  city VARCHAR(50),
  credit INTEGER DEFAULT 0,
  PRIMARY KEY(email),
  FOREIGN KEY(city) REFERENCES City(city)
);

CREATE TABLE Sellprice_To_Bid(
  sellprice INTEGER,
  minbid INTEGER,
  PRIMARY KEY(sellprice)
);

CREATE TABLE Subcategory_To_Category(
  subcategory VARCHAR(50),
  category VARCHAR(50),
  PRIMARY KEY(subcategory)
);

CREATE TABLE Item_To_Subcategory(
  iname VARCHAR(100),
  subcategory VARCHAR(50),
  PRIMARY KEY(iname),
  FOREIGN KEY(subcategory) REFERENCES Subcategory_To_Category(subcategory)
);

CREATE TABLE Item (
  iname VARCHAR(100),
  sellprice INTEGER,
  iid INTEGER NOT NULL AUTO_INCREMENT,
  email VARCHAR(50),
  stock INTEGER,
  PRIMARY KEY(iid),
  FOREIGN KEY(sellprice) REFERENCES Sellprice_To_Bid(sellprice),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iname) REFERENCES Item_To_Subcategory(iname)
);

CREATE TABLE Buys (
  bid INTEGER NOT NULL AUTO_INCREMENT,
  email VARCHAR(50),
  iid INTEGER,
  bdate DATE,
  PRIMARY KEY(bid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Auction (
  email VARCHAR(50),
  iid INTEGER,
  curr_bid INTEGER DEFAULT 0,
  start_date DATE,
  end_date DATE,
  count INTEGER DEFAULT 0,
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Notification (
  email VARCHAR(50),
  iid INTEGER,
  ncontent VARCHAR(50),
  nnumber INTEGER NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(nnumber),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid)
);

CREATE TABLE Discussion (
  email VARCHAR(50),
  iid INTEGER,
  thread INTEGER NOT NULL AUTO_INCREMENT,
  comment_date DATE,
  comment VARCHAR(64),
  PRIMARY KEY(thread),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Review (
  email VARCHAR(50),
  iid INTEGER,
  rating INTEGER,
  rcontent VARCHAR(256),
  PRIMARY KEY(email, iid),
  FOREIGN KEY(email) REFERENCES User(email),
  FOREIGN KEY(iid) REFERENCES Item(iid) ON DELETE CASCADE
);

CREATE TABLE Likes(
  email VARCHAR(50),
  iid INTEGER,
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
  VALUES ("Jeong Ho", "Shin", "ps2", "topfrag@gmail.com", "119", "A532", "Moonwharo-119", "Songdo");

-- user 3
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Suhyun", "Shin", "ps3", "lol@gmail.com", "919", "A516", "Moonwharo-119", "Songdo");

-- Other users
INSERT INTO City(city, country) VALUES ("Suwon-si", "South Korea");
INSERT INTO City(city, country) VALUES ("Daejeon", "South Korea");
INSERT INTO City(city, country) VALUES ("Hyderabad", "India");
INSERT INTO City(city, country) VALUES ("Mumbai", "India");
INSERT INTO City(city, country) VALUES ("Hampi", "India");

INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Jaein", "Park", "ps4", "presidentJP@gmail.com", "114", "50", "Daehak-1-ro", "Suwon-si");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Youngwon", "Shin", "ps5", "halpmyleif@gmail.com", "03211231654", "A535", "Gajeong-ro", "Daejeon");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Vijay", "Reddy", "ps6", "sanjaylanjay@gmail.com", "993211", "A535", "Rd Number 52", "Hyderabad");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Sneha", "Mangina", "ps7", "nits@gmail.com", "9925973170", "Yes535", "Some Duck Rd", "Mumbai");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Anand", "Hari", "ps8", "ihmsm@gmail.com", "9279909520", "161", "Some Temple Rd", "Hampi");
INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
  VALUES ("Arthur", "Lee", "give100pls", "artlee@gmail.com", "999291191", "Indu Fortune Fields 299", "Hafeezpet Rd", "Hyderabad");

-- Item 0
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (399000, 299250);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("True wireless earphones", "Earphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("SENNHEISER Momentum True Wireless", "True wireless earphones");
INSERT INTO Item (iname, sellprice, iid, email, stock) VALUES ("SENNHEISER Momentum True Wireless", 399000, 1, "hawonp@gmail.com", 10);

-- Item 1
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (35000, 26250);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Wired earphones", "Earphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("LYPERTEK Bevi", "Wired earphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (2, "LYPERTEK Bevi", 35000, "hawonp@gmail.com", 10);

-- Item 2
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (180000, 135000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Sports earphones", "Earphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("JAYBIRD Tarah Pro", "Sports earphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (3, "JAYBIRD Tarah Pro", 180000, "hawonp@gmail.com", 10);

--Adding Items 3 - 13
INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (110000, 82500);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Gaming headphones", "Headphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("CORSAIR Void Pro RGB Wireless", "Gaming headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (4, "CORSAIR Void Pro RGB Wireless", 110000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (1900000, 1425000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Studio monitor headphones", "Headphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("SENNHEISER HD800", "Studio monitor headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (5, "SENNHEISER HD800", 1900000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (350000, 262500);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Noise cancelling headphones", "Headphones");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("SONY WH-1000XM3", "Noise cancelling headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (6, "SONY WH-1000XM3", 350000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (2800000, 2100000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Portable digital audio player", "Media_Players");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("ASTELL & KERN AK240", "Portable digital audio player");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (7, "ASTELL & KERN AK240", 2800000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (32000000, 24000000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Floorstanding speaker", "Speakers");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("BOWERS & WILKINS 802 D3", "Floorstanding speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (8, "BOWERS & WILKINS 802 D3", 32000000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (1300000, 975000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Bookshelf speaker", "Speakers");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("KEF LS50", "Bookshelf speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (9, "KEF LS50", 1300000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (220000, 165000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Sound bar", "Speakers");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("YAMAHA YAS-108", "Sound bar");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (10, "YAMAHA YAS-108", 220000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (380000, 285000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Turntable", "Media_Players");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("DENON DP-300F", "Turntable");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (11, "DENON DP-300F", 380000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (330000, 247500);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Integrated Amplifier", "Amplifiers");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("ONKYO A-9030", "Integrated Amplifier");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (12, "ONKYO A-9030", 330000, "artlee@gmail.com", 10);

INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Center speaker", "Speakers");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("JBL Studio 520C", "Center speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (13, "JBL Studio 520C", 330000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (60000, 45000);
INSERT INTO Subcategory_To_Category (subcategory, category) VALUES ("Speaker cable", "Accessories");
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("NAIM NAC A5", "Speaker cable");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (14, "NAIM NAC A5", 60000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (450000, 337500);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("BOSE Quiet Comfort 35 II", "Noise cancelling headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (15, "BOSE Quiet Comfort 35 II", 450000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (200000, 150000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("AUDIO TECHNICA ATH-ANC700BT", "Noise cancelling headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (16, "AUDIO TECHNICA ATH-ANC700BT", 200000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (230000, 172500);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("BEYERDYNAMIC DT-770 Pro", "Studio monitor headphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (17, "BEYERDYNAMIC DT-770 Pro", 230000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (260000 , 195000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("BOSE SoundSport Pulse Wireless", "Sports earphones");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (18, "BOSE SoundSport Pulse Wireless", 260000, "artlee@gmail.com", 10);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (340000000, 255000000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("MAGICO Q7 MKII", "Floorstanding speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (19, "MAGICO Q7 MKII", 340000000, "artlee@gmail.com", 5);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (1200000, 900000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("POLK AUDIO RTi A7", "Floorstanding speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (20, "POLK AUDIO RTi A7", 1200000, "artlee@gmail.com", 6);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (660000, 495000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("BOSTON ACOUSTICS A250", "Floorstanding speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (21, "BOSTON ACOUSTICS A250", 660000, "artlee@gmail.com", 3);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (23500000, 17625000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("FOCAL Diablo Utopia III Evo", "Bookshelf speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (22, "FOCAL Diablo Utopia III Evo", 23500000, "artlee@gmail.com", 3);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (520000, 390000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("JAMO C91", "Bookshelf speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (23, "JAMO C91", 520000, "artlee@gmail.com", 3);

INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("DENON DHT-T110", "Sound bar");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (24, "DENON DHT-T110", 450000, "artlee@gmail.com", 3);

INSERT INTO Sellprice_To_Bid (sellprice, minbid) VALUES (33000000, 24750000);
INSERT INTO Item_To_Subcategory (iname, subcategory) VALUES ("DYNAUDIO Evidence Center", "Center speaker");
INSERT INTO Item (iid, iname, sellprice, email, stock) VALUES (25, "DYNAUDIO Evidence Center", 33000000, "artlee@gmail.com", 3);

--Auction 0: Item 0, user 1
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("topfrag@gmail.com", 1, 360000, "2019-06-03", "2019-06-13");
--Auction 1: Item 1, user 1
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("topfrag@gmail.com", 2, 360000, "2019-06-01", "2019-06-8");
--Auction by auctionmastaxX
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("artlee@gmail.com", 15, 337500, "2019-06-01", "2019-06-8");
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("artlee@gmail.com", 16, 150000, "2019-06-01", "2019-06-8");
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("artlee@gmail.com", 17, 172500, "2019-06-01", "2019-06-8");
INSERT INTO Auction (email, iid, curr_bid, start_date, end_date) VALUES ("artlee@gmail.com", 18, 195000, "2019-06-01", "2019-06-8");

--Buy 0:
INSERT INTO Buys (email, iid, bdate) VALUES ("artlee@gmail.com", 1, "2000-03-05");

--Adding pre reviews for some
INSERT INTO Review (email, iid, rating, rcontent) VALUES("ihmsm@gmail.com", 1, 3, "It was eh. Expected more from senn");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("lol@gmail.com", 1, 5, "Do you know sennheiser?");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("sanjaylanjay@gmail.com", 1, 5, "Me no know headphone but me like");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("nits@gmail.com", 2, 5, "idk my friend told me to give 5");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("halpmyleif@gmail.com", 2, 5, "Unreal sound quality at this price");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("ihmsm@gmail.com", 2, 4, "Quality too cheap");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("lol@gmail.com", 10, 4, "Decent quality at this price");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("hawonp@gmail.com", 10, 3, "the ting goes skrrra pap pap ka ka ka raw sauce no ketchup 2 + 2 = 4, - 1 = 3 quick mafs hence you get a 3");
INSERT INTO Review (email, iid, rating, rcontent) VALUES("sanjaylanjay@gmail.com", 10, 2, "Me no like");

--TRIGGERs

-- Activates after the User purchases an Item
delimiter //
CREATE TRIGGER ItemSold AFTER INSERT ON Buys
  FOR EACH ROW
  BEGIN
    -- Decrease Item Stock
    UPDATE Item SET Item.stock = Item.stock-1 WHERE iid=NEW.iid;

    -- Transaction Fees (with shipping)
    SELECT c.country INTO @sellerCountry FROM City c INNER JOIN (SELECT u.city FROM User u INNER JOIN
      (SELECT i.email FROM item i WHERE i.iid=NEW.iid) AS j1 ON j1.email=u.email) AS j2 ON c.city=j2.city;
    SELECT c.country INTO @buyerCountry FROM City c INNER JOIN (SELECT u.city FROM User u WHERE u.email=NEW.email) AS j1 ON j1.city=c.city;
    SELECT sellprice INTO @sellprice FROM Item WHERE iid=NEW.iid;

    -- Decrease credits from Buyer
    IF (@sellerCountry != @buyerCountry) THEN
      UPDATE User SET credit = credit - (@sellprice * 1.1) WHERE email=NEW.email;
    ELSE
      UPDATE User SET credit = credit - @sellprice WHERE email=NEW.email;
    END iF;

    -- Increase credits of Seller
    UPDATE User SET credit = credit + @sellprice WHERE email=@hey;

    -- Add Notifications
    SELECT email INTO @email FROM Item WHERE iid=NEW.iid;
    INSERT INTO Notification(email, iid, ncontent) VALUES(@email, NEW.iid, "Your Item Has Been Sold!");
    INSERT INTO Notification(email, iid, ncontent) VALUES(NEW.email, NEW.iid, "You bought this item! Leave a review!");
  END; //
delimiter ;

-- Activates when User Likes an Item
delimiter //
CREATE TRIGGER ItemLike AFTER INSERT ON Likes
  FOR EACH ROW
  BEGIN

    -- Insert an item into Auction every 3rd like
    SELECT email INTO @name FROM Item WHERE iid=NEW.iid;
    SELECT COUNT(*) INTO @info FROM Likes l WHERE l.iid=NEW.iid;
    SELECT stock INTO @stock FROM Item WHERE iid=NEW.iid;

    IF(@stock > 0 AND @info % 3 = 0) THEN
      -- Insert the item into the Auction Table
      SELECT s.minbid INTO @minbid FROM Sellprice_To_Bid s, Item i WHERE i.iid=NEW.iid AND s.sellprice=i.sellprice;
      INSERT INTO Auction(email, iid, curr_bid, start_date, end_date) VALUES(@name, NEW.iid, @minbid, CURDATE(), CURDATE());
      INSERT INTO Notification(email, iid, ncontent) VALUES(@name, NEW.iid, "Your item is on auction!");
    END IF;

    -- Notify users about their likes
    INSERT INTO Notification(email, iid, ncontent) VALUES(@name, NEW.iid, "Someone liked your item!");
    INSERT INTO Notification(email, iid, ncontent) VALUES(NEW.email, NEW.iid, "You liked this item!");
  END; //
delimiter ;

-- Activates on New Auction
delimiter //
CREATE TRIGGER ItemInAuction AFTER INSERT ON Auction
  FOR EACH ROW
  BEGIN
    -- Get current number of auctions
    SELECT COUNT(*) INTO @auctionNum FROM Auction a WHERE a.iid=NEW.iid;

    -- If number of auctions exceeds 10
    IF(@auctionNum > 10) THEN
      -- ROLLBACK insert on Auction;
      DELETE FROM Auction WHERE iid=NEW.iid AND email=NEW.email AND curr_bid = 0;
      UPDATE Item SET Item.stock = Item.stock+1 WHERE iid=NEW.iid;
      INSERT INTO Notification(email, iid, ncontent) VALUES(NEW.email, NEW.iid, "Auction Limit Reached :(");

    END IF;
  END; //
delimiter ;

-- after update on Auction
delimiter //
CREATE TRIGGER ItemBid AFTER UPDATE ON Auction
  FOR EACH ROW
  BEGIN
    INSERT INTO Notification(email, iid, ncontent) VALUES(NEW.email, NEW.iid, "You are now the top bidder!");
  END; //
delimiter ;
