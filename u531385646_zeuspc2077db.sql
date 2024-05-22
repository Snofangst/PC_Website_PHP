-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

create database zeuspc2077;
use zeuspc2077;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `u531385646_zeuspc2077db`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE  PROCEDURE `sp_getNewIDAcc` ()  BEGIN
	DECLARE ID CHAR(20);
    SELECT SUBSTRING(MAX(IdAcc),5,7) INTO ID FROM account ;
    IF ID IS NULL THEN
    	SET ID='USER00001';
    ELSE
    	SET ID=CONCAT('USER',LPAD((ID + 1), 5, '0'));
    END IF;
    SELECT ID;
END$$

CREATE  PROCEDURE `sp_getNewIDImage` ()  BEGIN
	DECLARE ID CHAR(20);
    SELECT SUBSTRING(MAX(IdImage),5,7) INTO ID FROM image ;
    IF ID IS NULL THEN
    	SET ID='IMAG00001';
    ELSE
    	SET ID=CONCAT('IMAG',LPAD((ID + 1), 5, '0'));
    END IF;
    SELECT ID;
END$$

CREATE  PROCEDURE `sp_getNewIDOrder` ()  BEGIN
	DECLARE ID CHAR(20);
    SELECT SUBSTRING(MAX(IdOrder),5,7) INTO ID FROM orders ;
    IF ID IS NULL THEN
    	SET ID='ORDE00001';
    ELSE
    	SET ID=CONCAT('ORDE',LPAD((ID + 1), 5, '0'));
    END IF;
    SELECT ID;
END$$

CREATE  PROCEDURE `sp_getNewIDProduct` ()  BEGIN
	DECLARE ID CHAR(20);
    SELECT SUBSTRING(MAX(IdProduct),5,7) INTO ID FROM product ;
    IF ID IS NULL THEN
    	SET ID='PROD00001';
    ELSE
    	SET ID=CONCAT('PROD',LPAD((ID + 1), 5, '0'));
    END IF;
    SELECT ID;
END$$

CREATE  PROCEDURE `sp_getTotal` (IN `Orderid` CHAR(20), OUT `Total` DOUBLE)  BEGIN
	if EXISTS(SELECT IdOrder FROM orders WHERE IdOrder LIKE `Orderid`) then
    	select sum((price-price*discount)*orderdetails.Quantity) INTO `Total` from orders,orderdetails,product where orders.IdOrder=`Orderid` and orders.IdOrder=orderdetails.IdOrder and orderdetails.IdProduct=product.IdProduct;
     else 
     	SIGNAL SQLSTATE '45000'
      		SET MESSAGE_TEXT = 'Order ID does not exist!';
     end if;
END$$

CREATE  PROCEDURE `sp_selectAllBrands` ()  BEGIN
	SELECT * FROM brand;
END$$

CREATE  PROCEDURE `sp_selectAllCatalogs` ()  BEGIN
	SELECT * FROM catalog;
END$$

CREATE  PROCEDURE `sp_selectAllProductInfo` ()  BEGIN
	Select product.idproduct,nameproduct,fileimage,catalog.namecatalog,brand.namebrand,price,discount,quantity,state,description from product left join catalog on product.IdCatalog=catalog.IdCatalog Left join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage where product.state!='HIDDEN';
END$$

CREATE  PROCEDURE `sp_selectItemsInCart` (IN `id` CHAR(20))  BEGIN
	SELECT product.idproduct, nameproduct, price, fileimage, orderdetails.quantity, product.quantity as max,product.discount from account right join orders on account.IdAcc=orders.IdAcc and orders.Delivery='IN PROGRESS' left join orderdetails on orderdetails.IdOrder=orders. IdOrder  left join product on product.IdProduct=orderdetails.IdProduct left join imagedetails on product.IdProduct=imagedetails. IdProduct and ImageType LIKE '%POST%' left join image on image.Idimage=imagedetails. Idimage where account.IdAcc LIKE `id` and orderdetails.idorder is not NULL;
END$$

CREATE  PROCEDURE `sp_selectProductById` (IN `Id` CHAR(20))  BEGIN
SELECT product.idproduct,nameproduct,brand.namebrand,catalog.namecatalog,price,discount,description,fileimage,quantity,state from product JOIN catalog ON product.IdCatalog = catalog.IdCatalog join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage WHERE product.idproduct is not null and product.IdProduct=`Id`;
END$$

CREATE  PROCEDURE `sp_selectProductInfoWithCondition` (IN `NameProduct` VARCHAR(100), IN `IdCatalog` CHAR(20), IN `IdBrand` CHAR(20))  BEGIN
	Select product.idproduct,product.nameproduct,fileimage,catalog.namecatalog,brand.namebrand,price,discount,quantity,state,description from product left join catalog on product.IdCatalog=catalog.IdCatalog Left join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage where (product.idproduct=CONCAT('%',`NameProduct`,'%') OR nameproduct like CONCAT('%',`NameProduct`,'%')) AND product.IdCatalog like `IdCatalog` AND product.IdBrand like `IdBrand`;
END$$

CREATE  PROCEDURE `sp_selectSlideImageById` (IN `Id` CHAR(20))  BEGIN
	SELECT image.idimage, fileimage from imagedetails,image where imagedetails.IdProduct=`Id` and imagedetails.IdImage=image.IdImage and imagedetails.ImageType='SLIDE' Order by imagedetails.IdImage asc; 
END$$

CREATE  PROCEDURE `sp_validateNameProduct` (IN `ProductName` VARCHAR(100))  BEGIN
	IF EXISTS(SELECT * FROM product where product.NameProduct LIKE `ProductName`)THEN
    	SIGNAL SQLSTATE '45000'
      	SET MESSAGE_TEXT = 'Product name has been taken!';
    END IF;
END$$

--
-- Các hàm
--
CREATE  FUNCTION `fc_getQuantityById` (`Productid` CHAR(20), `Orderid` CHAR(20)) RETURNS INT(11) DETERMINISTIC BEGIN
	DECLARE `Quantity` INT;
    select orderdetails.Quantity INTO `Quantity` from orders,orderdetails,product where orders.IdOrder=`Orderid` and product.IdProduct=`Productid` and orderdetails.IdOrder=orders.IdOrder and orderdetails.IdProduct=product.IdProduct;
    return (`Quantity`);
    
END$$

CREATE  FUNCTION `fc_getTotal` (`Orderid` CHAR(20)) RETURNS DOUBLE DETERMINISTIC BEGIN
	DECLARE `Total` DOUBLE;
	if EXISTS(SELECT IdOrder FROM orders WHERE IdOrder LIKE `Orderid`) then
    	select sum((price-price*discount)*orderdetails.Quantity) INTO `Total` from orders,orderdetails,product where orders.IdOrder=`Orderid` and orders.IdOrder=orderdetails.IdOrder and orderdetails.IdProduct=product.IdProduct;
        return (`Total`);
     end if;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `IdAcc` char(20) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` char(100) DEFAULT NULL,
  `PhoneNumber` char(11) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Gender` char(2) DEFAULT 'UD',
  `Avatar` char(100) DEFAULT NULL,
  `Address` char(100) DEFAULT NULL,
  `TotalSpent` double DEFAULT 0,
  `TypeAcc` char(10) NOT NULL DEFAULT 'CUSTOMER',
  `ReceiverName` char(50) DEFAULT NULL,
  `Token` varchar(50) DEFAULT NULL,
  `ExpiredDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`IdAcc`, `Name`, `Email`, `PhoneNumber`, `Password`, `Gender`, `Avatar`, `Address`, `TotalSpent`, `TypeAcc`, `ReceiverName`, `Token`, `ExpiredDate`) VALUES
('USER00005', 'Thái Bảo', 'kylethebestkid@yahoo.com', '0906309259', '$2y$10$6lFJ5hpGZHaBgUHRjb6krev6bmigbcFhnSl8eRRfJqwNJrvkbh1ki', 'M', '../Images/Avatars/Male.jpg', NULL, 1591138450, 'ADMIN', NULL, NULL, NULL),
('USER00007', 'Tuan07', 'tuan07@gmail.com', '0906309256', '$2y$10$sGUw/s.f8nfrPXJz6F7GUe8EnLnz2MDpy5sKaNpuMz4DacBkWKdMm', 'M', '../Images/Avatars/Male.jpg', 'wqeqdsadwqewqdasdqerqwe', 40000, 'CUSTOMER', 'Bảo', NULL, NULL),
('USER00008', 'Snofangst', 'snofangst@gmail.com', '0906309255', '$2y$10$I/otAfIZf9/A/v68GRIHw.RrPXngMyqBl6gommCMkHkXwbmfJGcna', 'M', '../Images/Avatars/Male.jpg', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 5696193400, 'CUSTOMER', 'Nguyen Thai Bao', 'NULL', NULL),
('USER00009', 'shanenguyen', 'shanenguyen05@gmail.com', NULL, '$2y$10$5dGWOy0u7c7DuYRRSRp7muEYawR6PanGAn/K1551eGGXyOmVvJjDC', 'F', '../Images/Avatars/Female.jpg', NULL, 0, 'CUSTOMER', NULL, 'NULL', NULL),
('USER00010', 'Karakuru', 'kara@gmail.com', NULL, '$2y$10$Nss02TdVrvWQbPOl5f0zSur9al7IEybW5.RgX7akzV8b.oSwUhJoW', 'M', '../Images/Avatars/Male.jpg', NULL, 0, 'CUSTOMER', NULL, NULL, NULL),
('USER00011', 'Thái Bảo 12', 'baoren@gmail.com', NULL, '$2y$10$cCsiDtKzdjTRBHaM73xmquSXMEezj4lsSgX3TvGsyNJLIr.InRdnq', 'M', '../Images/Avatars/Male.jpg', NULL, 0, 'CUSTOMER', NULL, NULL, NULL),
('USER00012', 'USER00012', NULL, '0906309220', '$2y$10$NGg.u4GbW6DzIm3i4p4s.e5nONKDr9V2mAKa6wzPtzw9CihdBjGjG', 'UD', '../Images/Avatars/Default.png', NULL, 57960000, 'GUEST', NULL, NULL, NULL),
('USER00013', '0906309235', NULL, '0906309235', '$2y$10$bUhQr2iTUWgKv3GypE2mEeW/lo4Sti50Z0mtDBAcSRgDl2YCHT5PG', 'UD', '../Images/Avatars/Default.png', NULL, 370880000, 'GUEST', NULL, NULL, NULL),
('USER00014', 'dasdsa', 'weqdas@gmail.com', NULL, '$2y$10$85ZdzlN8ukP2eUSlsZropuc53FNj7sbBgwG1G6weFyD2n67uCGkGm', 'M', '../Images/Avatars/Male.jpg', NULL, 0, 'CUSTOMER', NULL, NULL, NULL),
('USER00015', 'zeuspc', 'kylethebestkid@gmail.com', NULL, '$2y$10$tiM9rfwKnyUqejm9Kc.ma.PzM6Cair2mujr5vnGbVhgzwhMq8peLK', 'M', '../Images/Avatars/Male.jpg', NULL, 0, 'CUSTOMER', NULL, 'NULL', NULL),
('USER00017', 'zeuspc2077', 'zeuspc2077@gmail.com', '0902351213', '$2y$10$OTk7Bzx8Vn7t7afE7MEMKOJBoG3VRq.svC/krhNLBsu3FMGKQEdBW', 'M', '../Images/Avatars/Male.jpg', '1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 575517200, 'CUSTOMER', 'Lưu Văn Tiến', NULL, NULL),
('USER00018', '0905205201', NULL, '0905205201', '$2y$10$ui9LC8qw.DOSOHzl2iAA0OWrnHEFhbxBAnOIPhT3KbOzcEcl.mU7W', 'UD', '../Images/Avatars/Default.png', '18 Vo Van Kiet', 2781600000, 'GUEST', 'Nguyễn Thái Bảo', NULL, NULL),
('USER00019', '0906309877', NULL, '0906309877', '$2y$10$etXCLuTibtpn/nMKOejT3.fh55EMY7uyjSnrf940Oes3uMoA/BzZu', 'UD', '../Images/Avatars/Default.png', NULL, 11082600, 'GUEST', NULL, NULL, NULL),
('USER00021', 'tuan123', 'tuan@gmail.com', '0587928264', '$2y$10$.aC.GaofTMCh7jEcDfKVJuzJBfuc8nagzVa.9oNV1XcDucCYX7o3e', 'M', '../Images/Avatars/Male.jpg', 'sdafasdfasdfasdfasdf', 71678000, 'CUSTOMER', 'sczc', NULL, NULL),
('USER00022', '0906309165', NULL, '0906309165', '$2y$10$JpNA9BSnGGXHAWczedqFnOyxLZDz3gz5veFDtS0BzDnixWum1JXtK', 'UD', '../Images/Avatars/Default.png', NULL, 7986000, 'GUEST', NULL, NULL, NULL),
('USER00024', '0568125681', NULL, '0568125681', '$2y$10$0teQZKYByxZtWAQM/l3gxuHQVfXEtBeWUc2.Z6boXUz3aYw9Z1xR.', 'UD', '../Images/Avatars/Default.png', '1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 5796000, 'GUEST', 'Nguyên', NULL, NULL),
('USER00025', 'KingsMan', NULL, '0906309202', '$2y$10$k75cP0HOnhio2LcRz.9l8OoQalKjfOBNM/aEuiWuO4KsBy9GHPZ.K', 'M', '../Images/Avatars/Male.jpg', '91 VO VAN KIET', 0, 'ADMIN', 'Vo Gia Thuong', NULL, NULL),
('USER00026', '0906309250', NULL, '0906309250', '$2y$10$y6ytE8QNaar/7naI38s7EeW0/PRy3gkWv2a5uvH68LnHiTsSPvOVe', 'UD', '../Images/Avatars/Default.png', '682A Trường Chinh, Phường 15, Tân Bình, Thành phố Hồ Chí Minh', 2985000, 'GUEST', 'Nguyễn Thái Bảo', NULL, NULL),
('USER00027', '0906309205', NULL, '0906309205', '$2y$10$IgPXlibouOFXP/E9pZaSauULL/j8MKQGWnUqHhAEBG6aCm1A5vINe', 'UD', '../Images/Avatars/Default.png', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 6691000, 'GUEST', 'Nguyễn Thái Bảo', NULL, NULL),
('USER00028', 'Nguyễn Thái Bảo', 'lexuthertheseventh@gmail.com', '0906309282', '$2y$10$Hjcax5IqLsDWP8SC8IuW/uRyTxij0zPkGx6z.C.Tx2djt48ncbMP.', 'M', '../Images/Avatars/Male.jpg', '718 Trường Chinh, Phường 15, Tân Bình, Thành phố Hồ Chí Minh 72100\r\n', 64071000, 'CUSTOMER', 'Bảo', 'NULL', NULL),
('USER00029', 'Nguyễn Thanh Long', 'nguyenthanhlong@gmail.com', NULL, '$2y$10$ppOhLEi48nyylYQs0lyz9eKd5EZgQ6sRecRgy7OA7hAKR7TFPgcbO', 'M', '../Images/Avatars/Male.jpg', NULL, 0, 'CUSTOMER', NULL, NULL, NULL),
('USER00030', '0906304895', NULL, '0906304895', '$2y$10$AdBSIh3RsqgkKca3qOJan.vjkXOGG.SdQpG1/xJXixUVjstHuGt8.', 'UD', '../Images/Avatars/Default.png', '155 Tân Kỳ Tân Quý, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 141158000, 'GUEST', 'Vo Van Teo', NULL, NULL),
('USER00031', 'VoVanTeo', 'vovanteo@gmail.com', '0906302053', '$2y$10$loom48p1CEZXPUPhTx3HUOFfLAM1LpVA3Kw6zDx7u5jX9WIkvaAXK', 'F', '../Images/Avatars/Female.jpg', '10 Lý Thường Kiệt', 0, 'CUSTOMER', 'Võ Văn Dũng', NULL, NULL);

--
-- Bẫy `account`
--
DELIMITER $$
CREATE TRIGGER `auto_avatar_selection` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
	if NEW.Gender like 'M' THEN
    	set NEW.Avatar='../Images/Avatars/Male.jpg';
    elseif NEW.Gender like 'F' THEN
    	set NEW.Avatar='../Images/Avatars/Female.jpg';
    ELSE
    	set NEW.Avatar='../Images/Avatars/Default.png';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `auto_increment_user_id` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
	DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
	IF(LENGTH(NEW.IdAcc)=0) THEN
        SET `current_max_id` = (SELECT SUBSTRING(MAX(IdAcc),5,7) FROM account WHERE IdAcc LIKE 'USER%');
        IF `current_max_id` IS NULL THEN
            SET `new_id` = 'USER00001';
        ELSE
            SET `new_id` = CONCAT('USER', LPAD((`current_max_id` + 1), 5, '0'));
        END IF;
        SET NEW.IdAcc = `new_id`;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_Email_insert` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
	IF LENGTH(NEW.email)!=0 THEN 
        IF NOT NEW.email REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9_-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,4}$' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid email format';
        ELSEIF EXISTS(SELECT * FROM account where Email= NEW.Email and IdAcc!=NEW.IdAcc) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email already exists';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_Email_update` BEFORE UPDATE ON `account` FOR EACH ROW BEGIN
    IF NOT NEW.email REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9_-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid email format';
    ELSEIF EXISTS(SELECT * FROM account where Email= NEW.Email and IdAcc!=NEW.IdAcc) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email already exists';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_phonenumber_insert` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
	IF LENGTH(NEW.phonenumber)!=0 THEN 
        if exists(select * from account where PhoneNumber= NEW.phonenumber and IdAcc!=NEW.IdAcc) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number already exists!';
        elseif(LENGTH(NEW.phonenumber) NOT BETWEEN 10 AND 11) and NEW.phonenumber!=null	THEN
             SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number has to be in between 10 and 11 numbers!';
        end if;
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_phonenumber_update` BEFORE UPDATE ON `account` FOR EACH ROW BEGIN
	if exists(select * from account where PhoneNumber= NEW.phonenumber and IdAcc!=NEW.IdAcc) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number already exists!';
    elseif(LENGTH(NEW.phonenumber) NOT BETWEEN 10 AND 11)	THEN
    	 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number has to be in between 10 and 11 numbers!';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_user` BEFORE DELETE ON `account` FOR EACH ROW BEGIN
    DELETE FROM orders WHERE orders.IdAcc = OLD.IdAcc;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brand`
--

CREATE TABLE `brand` (
  `IdBrand` char(20) NOT NULL,
  `NameBrand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brand`
--

INSERT INTO `brand` (`IdBrand`, `NameBrand`) VALUES
('BRAN00001', 'Akko'),
('BRAN00002', 'Asus'),
('BRAN00003', 'Corsair'),
('BRAN00004', 'DareU'),
('BRAN00005', 'GIGABYTE'),
('BRAN00006', 'HKC'),
('BRAN00018', 'HyperX'),
('BRAN00008', 'Lenovo'),
('BRAN00009', 'Leopold'),
('BRAN00010', 'LG'),
('BRAN00011', 'Logitech'),
('BRAN00012', 'Msi'),
('BRAN00013', 'Nvidia'),
('BRAN00014', 'Philips'),
('BRAN00015', 'Razer'),
('BRAN00016', 'Samsung'),
('BRAN00007', 'UNDEFINED'),
('BRAN00019', 'Viewsonic'),
('BRAN00017', 'Zeus');

--
-- Bẫy `brand`
--
DELIMITER $$
CREATE TRIGGER `auto_increment_brand_id` BEFORE INSERT ON `brand` FOR EACH ROW BEGIN
    DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
    SET `current_max_id` = (SELECT SUBSTRING(MAX(IdBrand),5,7) FROM brand WHERE IdBrand LIKE 'BRAN%');
    IF `current_max_id` IS NULL THEN
        SET `new_id` = 'BRAN00001';
    ELSE
        SET `new_id` = CONCAT('BRAN', LPAD((`current_max_id` + 1), 5, '0'));
    END IF;
    SET NEW.IdBrand = `new_id`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_brand` BEFORE DELETE ON `brand` FOR EACH ROW BEGIN
    DELETE FROM product WHERE IdBrand = OLD.IdBrand;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `catalog`
--

CREATE TABLE `catalog` (
  `IdCatalog` char(20) NOT NULL,
  `NameCatalog` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `catalog`
--

INSERT INTO `catalog` (`IdCatalog`, `NameCatalog`) VALUES
('CATA00003', 'Keyboard'),
('CATA00004', 'Monitor'),
('CATA00002', 'Mouse'),
('CATA00001', 'PC'),
('CATA00005', 'UNDEFINED');

--
-- Bẫy `catalog`
--
DELIMITER $$
CREATE TRIGGER `auto_increment_catalog_id` BEFORE INSERT ON `catalog` FOR EACH ROW BEGIN
    DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
    SET `current_max_id` = (SELECT SUBSTRING(MAX(IdCatalog),5,7) FROM catalog WHERE IdCatalog LIKE 'CATA%');
    IF `current_max_id` IS NULL THEN
        SET `new_id` = 'CATA00001';
    ELSE
        SET `new_id` = CONCAT('CATA', LPAD((`current_max_id` + 1), 5, '0'));
    END IF;
    SET NEW.IdCatalog = `new_id`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_catalog` BEFORE DELETE ON `catalog` FOR EACH ROW BEGIN
    DELETE FROM product WHERE IdCatalog = OLD.IdCatalog;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `image`
--

CREATE TABLE `image` (
  `IdImage` char(20) NOT NULL,
  `FileImage` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `image`
--

INSERT INTO `image` (`IdImage`, `FileImage`) VALUES
('IMAG00002', '../Images/Products/PROD00001_Slide0.jpg'),
('IMAG00003', '../Images/Products/PROD00001_Slide1.jpg'),
('IMAG00004', '../Images/Products/PROD00001_Slide2.jpg'),
('IMAG00005', '../Images/Products/PROD00001_Slide3.jpg'),
('IMAG00006', '../Images/Products/PROD00001_Slide4.jpg'),
('IMAG00147', '../Images/Products/PROD00001.png'),
('IMAG00008', '../Images/Products/PROD00002_Slide0.png'),
('IMAG00009', '../Images/Products/PROD00002_Slide1.png'),
('IMAG00010', '../Images/Products/PROD00002_Slide2.png'),
('IMAG00011', '../Images/Products/PROD00002_Slide3.png'),
('IMAG00012', '../Images/Products/PROD00002_Slide4.png'),
('IMAG00007', '../Images/Products/PROD00002.png'),
('IMAG00142', '../Images/Products/PROD00003_Slide0.png'),
('IMAG00143', '../Images/Products/PROD00003_Slide1.png'),
('IMAG00144', '../Images/Products/PROD00003_Slide2.png'),
('IMAG00145', '../Images/Products/PROD00003_Slide3.png'),
('IMAG00146', '../Images/Products/PROD00003_Slide4.png'),
('IMAG00148', '../Images/Products/PROD00003.png'),
('IMAG00014', '../Images/Products/PROD00007_Slide0.png'),
('IMAG00015', '../Images/Products/PROD00007_Slide1.png'),
('IMAG00016', '../Images/Products/PROD00007_Slide2.png'),
('IMAG00017', '../Images/Products/PROD00007_Slide3.png'),
('IMAG00013', '../Images/Products/PROD00007.png'),
('IMAG00019', '../Images/Products/PROD00008_Slide0.png'),
('IMAG00020', '../Images/Products/PROD00008_Slide1.png'),
('IMAG00021', '../Images/Products/PROD00008_Slide2.png'),
('IMAG00022', '../Images/Products/PROD00008_Slide3.png'),
('IMAG00023', '../Images/Products/PROD00008_Slide4.png'),
('IMAG00149', '../Images/Products/PROD00008.png'),
('IMAG00025', '../Images/Products/PROD00009_Slide0.png'),
('IMAG00026', '../Images/Products/PROD00009_Slide1.png'),
('IMAG00027', '../Images/Products/PROD00009_Slide2.png'),
('IMAG00028', '../Images/Products/PROD00009_Slide3.png'),
('IMAG00029', '../Images/Products/PROD00009_Slide4.png'),
('IMAG00024', '../Images/Products/PROD00009.png'),
('IMAG00031', '../Images/Products/PROD00010_Slide0.png'),
('IMAG00032', '../Images/Products/PROD00010_Slide1.png'),
('IMAG00033', '../Images/Products/PROD00010_Slide2.png'),
('IMAG00034', '../Images/Products/PROD00010_Slide3.png'),
('IMAG00035', '../Images/Products/PROD00010_Slide4.png'),
('IMAG00030', '../Images/Products/PROD00010.png'),
('IMAG00037', '../Images/Products/PROD00011_Slide0.png'),
('IMAG00038', '../Images/Products/PROD00011_Slide1.png'),
('IMAG00039', '../Images/Products/PROD00011_Slide2.png'),
('IMAG00040', '../Images/Products/PROD00011_Slide3.png'),
('IMAG00041', '../Images/Products/PROD00011_Slide4.png'),
('IMAG00036', '../Images/Products/PROD00011.png'),
('IMAG00043', '../Images/Products/PROD00012_Slide0.png'),
('IMAG00044', '../Images/Products/PROD00012_Slide1.png'),
('IMAG00045', '../Images/Products/PROD00012_Slide2.png'),
('IMAG00046', '../Images/Products/PROD00012_Slide3.png'),
('IMAG00047', '../Images/Products/PROD00012_Slide4.png'),
('IMAG00042', '../Images/Products/PROD00012.png'),
('IMAG00049', '../Images/Products/PROD00013_Slide0.png'),
('IMAG00050', '../Images/Products/PROD00013_Slide1.png'),
('IMAG00051', '../Images/Products/PROD00013_Slide2.png'),
('IMAG00052', '../Images/Products/PROD00013_Slide3.png'),
('IMAG00053', '../Images/Products/PROD00013_Slide4.png'),
('IMAG00048', '../Images/Products/PROD00013.png'),
('IMAG00055', '../Images/Products/PROD00014_Slide0.png'),
('IMAG00056', '../Images/Products/PROD00014_Slide1.png'),
('IMAG00057', '../Images/Products/PROD00014_Slide2.png'),
('IMAG00058', '../Images/Products/PROD00014_Slide3.png'),
('IMAG00059', '../Images/Products/PROD00014_Slide4.png'),
('IMAG00054', '../Images/Products/PROD00014.png'),
('IMAG00061', '../Images/Products/PROD00015_Slide0.png'),
('IMAG00062', '../Images/Products/PROD00015_Slide1.png'),
('IMAG00063', '../Images/Products/PROD00015_Slide2.png'),
('IMAG00064', '../Images/Products/PROD00015_Slide3.png'),
('IMAG00065', '../Images/Products/PROD00015_Slide4.png'),
('IMAG00060', '../Images/Products/PROD00015.png'),
('IMAG00067', '../Images/Products/PROD00016_Slide0.png'),
('IMAG00068', '../Images/Products/PROD00016_Slide1.png'),
('IMAG00066', '../Images/Products/PROD00016.png'),
('IMAG00070', '../Images/Products/PROD00018_Slide0.png'),
('IMAG00071', '../Images/Products/PROD00018_Slide1.png'),
('IMAG00072', '../Images/Products/PROD00018_Slide2.png'),
('IMAG00073', '../Images/Products/PROD00018_Slide3.png'),
('IMAG00069', '../Images/Products/PROD00018.png'),
('IMAG00075', '../Images/Products/PROD00019_Slide0.png'),
('IMAG00076', '../Images/Products/PROD00019_Slide1.png'),
('IMAG00077', '../Images/Products/PROD00019_Slide2.png'),
('IMAG00078', '../Images/Products/PROD00019_Slide3.png'),
('IMAG00074', '../Images/Products/PROD00019.png'),
('IMAG00080', '../Images/Products/PROD00020_Slide0.png'),
('IMAG00081', '../Images/Products/PROD00020_Slide1.png'),
('IMAG00082', '../Images/Products/PROD00020_Slide2.png'),
('IMAG00079', '../Images/Products/PROD00020.png'),
('IMAG00084', '../Images/Products/PROD00021_Slide0.png'),
('IMAG00085', '../Images/Products/PROD00021_Slide1.png'),
('IMAG00086', '../Images/Products/PROD00021_Slide2.png'),
('IMAG00087', '../Images/Products/PROD00021_Slide3.png'),
('IMAG00083', '../Images/Products/PROD00021.png'),
('IMAG00089', '../Images/Products/PROD00022_Slide0.png'),
('IMAG00090', '../Images/Products/PROD00022_Slide1.png'),
('IMAG00091', '../Images/Products/PROD00022_Slide2.png'),
('IMAG00092', '../Images/Products/PROD00022_Slide3.png'),
('IMAG00088', '../Images/Products/PROD00022.png'),
('IMAG00094', '../Images/Products/PROD00023_Slide0.png'),
('IMAG00095', '../Images/Products/PROD00023_Slide1.png'),
('IMAG00096', '../Images/Products/PROD00023_Slide2.png'),
('IMAG00097', '../Images/Products/PROD00023_Slide3.png'),
('IMAG00093', '../Images/Products/PROD00023.png'),
('IMAG00104', '../Images/Products/PROD00024_Slide0.png'),
('IMAG00101', '../Images/Products/PROD00024_Slide2.png'),
('IMAG00102', '../Images/Products/PROD00024_Slide3.png'),
('IMAG00103', '../Images/Products/PROD00024.png'),
('IMAG00106', '../Images/Products/PROD00025_Slide0.png'),
('IMAG00107', '../Images/Products/PROD00025_Slide1.png'),
('IMAG00108', '../Images/Products/PROD00025_Slide2.png'),
('IMAG00105', '../Images/Products/PROD00025.png'),
('IMAG00110', '../Images/Products/PROD00026_Slide0.png'),
('IMAG00111', '../Images/Products/PROD00026_Slide1.png'),
('IMAG00112', '../Images/Products/PROD00026_Slide2.png'),
('IMAG00109', '../Images/Products/PROD00026.png'),
('IMAG00114', '../Images/Products/PROD00027_Slide0.png'),
('IMAG00115', '../Images/Products/PROD00027_Slide1.png'),
('IMAG00113', '../Images/Products/PROD00027.png'),
('IMAG00117', '../Images/Products/PROD00028_Slide0.png'),
('IMAG00118', '../Images/Products/PROD00028_Slide1.png'),
('IMAG00119', '../Images/Products/PROD00028_Slide2.png'),
('IMAG00120', '../Images/Products/PROD00028_Slide3.png'),
('IMAG00121', '../Images/Products/PROD00028_Slide4.png'),
('IMAG00116', '../Images/Products/PROD00028.png'),
('IMAG00123', '../Images/Products/PROD00029_Slide0.png'),
('IMAG00124', '../Images/Products/PROD00029_Slide1.png'),
('IMAG00125', '../Images/Products/PROD00029_Slide2.png'),
('IMAG00126', '../Images/Products/PROD00029_Slide3.png'),
('IMAG00122', '../Images/Products/PROD00029.png'),
('IMAG00128', '../Images/Products/PROD00030_Slide0.png'),
('IMAG00129', '../Images/Products/PROD00030_Slide1.png'),
('IMAG00130', '../Images/Products/PROD00030_Slide2.png'),
('IMAG00127', '../Images/Products/PROD00030.png'),
('IMAG00132', '../Images/Products/PROD00031_Slide0.png'),
('IMAG00133', '../Images/Products/PROD00031_Slide1.jpg'),
('IMAG00134', '../Images/Products/PROD00031_Slide2.png'),
('IMAG00135', '../Images/Products/PROD00031_Slide3.jpg'),
('IMAG00131', '../Images/Products/PROD00031.png'),
('IMAG00137', '../Images/Products/PROD00032_Slide0.png'),
('IMAG00138', '../Images/Products/PROD00032_Slide1.png'),
('IMAG00139', '../Images/Products/PROD00032_Slide2.png'),
('IMAG00140', '../Images/Products/PROD00032_Slide3.png'),
('IMAG00136', '../Images/Products/PROD00032.png'),
('IMAG00151', '../Images/Products/PROD00033_Slide0.jpg'),
('IMAG00152', '../Images/Products/PROD00033_Slide1.png'),
('IMAG00153', '../Images/Products/PROD00033_Slide2.png'),
('IMAG00150', '../Images/Products/PROD00033.png'),
('IMAG00155', '../Images/Products/PROD00034_Slide0.png'),
('IMAG00156', '../Images/Products/PROD00034_Slide1.png'),
('IMAG00157', '../Images/Products/PROD00034_Slide2.png'),
('IMAG00154', '../Images/Products/PROD00034.jpg'),
('IMAG00159', '../Images/Products/PROD00035_Slide0.png'),
('IMAG00160', '../Images/Products/PROD00035_Slide1.png'),
('IMAG00158', '../Images/Products/PROD00035.png'),
('IMAG00162', '../Images/Products/PROD00036_Slide0.png'),
('IMAG00163', '../Images/Products/PROD00036_Slide1.png'),
('IMAG00164', '../Images/Products/PROD00036_Slide2.png'),
('IMAG00161', '../Images/Products/PROD00036.jpg'),
('IMAG00166', '../Images/Products/PROD00037_Slide0.png'),
('IMAG00167', '../Images/Products/PROD00037_Slide1.png'),
('IMAG00165', '../Images/Products/PROD00037.jpg'),
('IMAG00169', '../Images/Products/PROD00038_Slide0.png'),
('IMAG00170', '../Images/Products/PROD00038_Slide1.png'),
('IMAG00171', '../Images/Products/PROD00038_Slide2.jpg'),
('IMAG00168', '../Images/Products/PROD00038.jpg');

--
-- Bẫy `image`
--
DELIMITER $$
CREATE TRIGGER `auto_increment_image_id` BEFORE INSERT ON `image` FOR EACH ROW BEGIN
    DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
    SET `current_max_id` = (SELECT SUBSTRING(MAX(IdImage),5,7) FROM image WHERE IdImage LIKE 'IMAG%');
    IF `current_max_id` IS NULL THEN
        SET `new_id` = 'IMAG00001';
    ELSE
        SET `new_id` = CONCAT('IMAG', LPAD((`current_max_id` + 1), 5, '0'));
    END IF;
    SET NEW.IdImage = `new_id`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_name_insert` BEFORE INSERT ON `image` FOR EACH ROW BEGIN
	IF EXISTS(SELECT image.FileImage FROM image WHERE image.FileImage LIKE NEW.FileImage) THEN
    	SIGNAL SQLSTATE '45000'
   		SET MESSAGE_TEXT = 'File location has already in database!';
     END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_image` BEFORE DELETE ON `image` FOR EACH ROW BEGIN
    DELETE FROM imagedetails WHERE IdImage = OLD.IdImage;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `imagedetails`
--

CREATE TABLE `imagedetails` (
  `IdImage` char(20) NOT NULL,
  `IdProduct` char(20) NOT NULL,
  `ImageType` varchar(20) NOT NULL DEFAULT 'POST'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `imagedetails`
--

INSERT INTO `imagedetails` (`IdImage`, `IdProduct`, `ImageType`) VALUES
('IMAG00002', 'PROD00001', 'SLIDE'),
('IMAG00003', 'PROD00001', 'SLIDE'),
('IMAG00004', 'PROD00001', 'SLIDE'),
('IMAG00005', 'PROD00001', 'SLIDE'),
('IMAG00006', 'PROD00001', 'SLIDE'),
('IMAG00007', 'PROD00002', 'POST'),
('IMAG00008', 'PROD00002', 'SLIDE'),
('IMAG00009', 'PROD00002', 'SLIDE'),
('IMAG00010', 'PROD00002', 'SLIDE'),
('IMAG00011', 'PROD00002', 'SLIDE'),
('IMAG00012', 'PROD00002', 'SLIDE'),
('IMAG00013', 'PROD00007', 'POST'),
('IMAG00014', 'PROD00007', 'SLIDE'),
('IMAG00015', 'PROD00007', 'SLIDE'),
('IMAG00016', 'PROD00007', 'SLIDE'),
('IMAG00017', 'PROD00007', 'SLIDE'),
('IMAG00019', 'PROD00008', 'SLIDE'),
('IMAG00020', 'PROD00008', 'SLIDE'),
('IMAG00021', 'PROD00008', 'SLIDE'),
('IMAG00022', 'PROD00008', 'SLIDE'),
('IMAG00023', 'PROD00008', 'SLIDE'),
('IMAG00024', 'PROD00009', 'POST'),
('IMAG00025', 'PROD00009', 'SLIDE'),
('IMAG00026', 'PROD00009', 'SLIDE'),
('IMAG00027', 'PROD00009', 'SLIDE'),
('IMAG00028', 'PROD00009', 'SLIDE'),
('IMAG00029', 'PROD00009', 'SLIDE'),
('IMAG00030', 'PROD00010', 'POST'),
('IMAG00031', 'PROD00010', 'SLIDE'),
('IMAG00032', 'PROD00010', 'SLIDE'),
('IMAG00033', 'PROD00010', 'SLIDE'),
('IMAG00034', 'PROD00010', 'SLIDE'),
('IMAG00035', 'PROD00010', 'SLIDE'),
('IMAG00036', 'PROD00011', 'POST'),
('IMAG00037', 'PROD00011', 'SLIDE'),
('IMAG00038', 'PROD00011', 'SLIDE'),
('IMAG00039', 'PROD00011', 'SLIDE'),
('IMAG00040', 'PROD00011', 'SLIDE'),
('IMAG00041', 'PROD00011', 'SLIDE'),
('IMAG00042', 'PROD00012', 'POST'),
('IMAG00043', 'PROD00012', 'SLIDE'),
('IMAG00044', 'PROD00012', 'SLIDE'),
('IMAG00045', 'PROD00012', 'SLIDE'),
('IMAG00046', 'PROD00012', 'SLIDE'),
('IMAG00047', 'PROD00012', 'SLIDE'),
('IMAG00048', 'PROD00013', 'POST'),
('IMAG00049', 'PROD00013', 'SLIDE'),
('IMAG00050', 'PROD00013', 'SLIDE'),
('IMAG00051', 'PROD00013', 'SLIDE'),
('IMAG00052', 'PROD00013', 'SLIDE'),
('IMAG00053', 'PROD00013', 'SLIDE'),
('IMAG00054', 'PROD00014', 'POST'),
('IMAG00055', 'PROD00014', 'SLIDE'),
('IMAG00056', 'PROD00014', 'SLIDE'),
('IMAG00057', 'PROD00014', 'SLIDE'),
('IMAG00058', 'PROD00014', 'SLIDE'),
('IMAG00059', 'PROD00014', 'SLIDE'),
('IMAG00060', 'PROD00015', 'POST'),
('IMAG00061', 'PROD00015', 'SLIDE'),
('IMAG00062', 'PROD00015', 'SLIDE'),
('IMAG00063', 'PROD00015', 'SLIDE'),
('IMAG00064', 'PROD00015', 'SLIDE'),
('IMAG00065', 'PROD00015', 'SLIDE'),
('IMAG00066', 'PROD00016', 'POST'),
('IMAG00067', 'PROD00016', 'SLIDE'),
('IMAG00068', 'PROD00016', 'SLIDE'),
('IMAG00069', 'PROD00018', 'POST'),
('IMAG00070', 'PROD00018', 'SLIDE'),
('IMAG00071', 'PROD00018', 'SLIDE'),
('IMAG00072', 'PROD00018', 'SLIDE'),
('IMAG00073', 'PROD00018', 'SLIDE'),
('IMAG00074', 'PROD00019', 'POST'),
('IMAG00075', 'PROD00019', 'SLIDE'),
('IMAG00076', 'PROD00019', 'SLIDE'),
('IMAG00077', 'PROD00019', 'SLIDE'),
('IMAG00078', 'PROD00019', 'SLIDE'),
('IMAG00079', 'PROD00020', 'POST'),
('IMAG00080', 'PROD00020', 'SLIDE'),
('IMAG00081', 'PROD00020', 'SLIDE'),
('IMAG00082', 'PROD00020', 'SLIDE'),
('IMAG00083', 'PROD00021', 'POST'),
('IMAG00084', 'PROD00021', 'SLIDE'),
('IMAG00085', 'PROD00021', 'SLIDE'),
('IMAG00086', 'PROD00021', 'SLIDE'),
('IMAG00087', 'PROD00021', 'SLIDE'),
('IMAG00088', 'PROD00022', 'POST'),
('IMAG00089', 'PROD00022', 'SLIDE'),
('IMAG00090', 'PROD00022', 'SLIDE'),
('IMAG00091', 'PROD00022', 'SLIDE'),
('IMAG00092', 'PROD00022', 'SLIDE'),
('IMAG00093', 'PROD00023', 'POST'),
('IMAG00094', 'PROD00023', 'SLIDE'),
('IMAG00095', 'PROD00023', 'SLIDE'),
('IMAG00096', 'PROD00023', 'SLIDE'),
('IMAG00097', 'PROD00023', 'SLIDE'),
('IMAG00103', 'PROD00024', 'POST'),
('IMAG00104', 'PROD00024', 'SLIDE'),
('IMAG00105', 'PROD00025', 'POST'),
('IMAG00106', 'PROD00025', 'SLIDE'),
('IMAG00107', 'PROD00025', 'SLIDE'),
('IMAG00108', 'PROD00025', 'SLIDE'),
('IMAG00109', 'PROD00026', 'POST'),
('IMAG00110', 'PROD00026', 'SLIDE'),
('IMAG00111', 'PROD00026', 'SLIDE'),
('IMAG00112', 'PROD00026', 'SLIDE'),
('IMAG00113', 'PROD00027', 'POST'),
('IMAG00114', 'PROD00027', 'SLIDE'),
('IMAG00115', 'PROD00027', 'SLIDE'),
('IMAG00116', 'PROD00028', 'POST'),
('IMAG00117', 'PROD00028', 'SLIDE'),
('IMAG00118', 'PROD00028', 'SLIDE'),
('IMAG00119', 'PROD00028', 'SLIDE'),
('IMAG00120', 'PROD00028', 'SLIDE'),
('IMAG00121', 'PROD00028', 'SLIDE'),
('IMAG00122', 'PROD00029', 'POST'),
('IMAG00123', 'PROD00029', 'SLIDE'),
('IMAG00124', 'PROD00029', 'SLIDE'),
('IMAG00125', 'PROD00029', 'SLIDE'),
('IMAG00126', 'PROD00029', 'SLIDE'),
('IMAG00127', 'PROD00030', 'POST'),
('IMAG00128', 'PROD00030', 'SLIDE'),
('IMAG00129', 'PROD00030', 'SLIDE'),
('IMAG00130', 'PROD00030', 'SLIDE'),
('IMAG00131', 'PROD00031', 'POST'),
('IMAG00132', 'PROD00031', 'SLIDE'),
('IMAG00133', 'PROD00031', 'SLIDE'),
('IMAG00134', 'PROD00031', 'SLIDE'),
('IMAG00135', 'PROD00031', 'SLIDE'),
('IMAG00136', 'PROD00032', 'POST'),
('IMAG00137', 'PROD00032', 'SLIDE'),
('IMAG00138', 'PROD00032', 'SLIDE'),
('IMAG00139', 'PROD00032', 'SLIDE'),
('IMAG00140', 'PROD00032', 'SLIDE'),
('IMAG00142', 'PROD00003', 'SLIDE'),
('IMAG00143', 'PROD00003', 'SLIDE'),
('IMAG00144', 'PROD00003', 'SLIDE'),
('IMAG00145', 'PROD00003', 'SLIDE'),
('IMAG00146', 'PROD00003', 'SLIDE'),
('IMAG00147', 'PROD00001', 'POST'),
('IMAG00148', 'PROD00003', 'POST'),
('IMAG00149', 'PROD00008', 'POST'),
('IMAG00150', 'PROD00033', 'POST'),
('IMAG00151', 'PROD00033', 'SLIDE'),
('IMAG00152', 'PROD00033', 'SLIDE'),
('IMAG00153', 'PROD00033', 'SLIDE'),
('IMAG00154', 'PROD00034', 'POST'),
('IMAG00155', 'PROD00034', 'SLIDE'),
('IMAG00156', 'PROD00034', 'SLIDE'),
('IMAG00157', 'PROD00034', 'SLIDE'),
('IMAG00158', 'PROD00035', 'POST'),
('IMAG00159', 'PROD00035', 'SLIDE'),
('IMAG00160', 'PROD00035', 'SLIDE'),
('IMAG00161', 'PROD00036', 'POST'),
('IMAG00162', 'PROD00036', 'SLIDE'),
('IMAG00163', 'PROD00036', 'SLIDE'),
('IMAG00164', 'PROD00036', 'SLIDE'),
('IMAG00165', 'PROD00037', 'POST'),
('IMAG00166', 'PROD00037', 'SLIDE'),
('IMAG00167', 'PROD00037', 'SLIDE'),
('IMAG00168', 'PROD00038', 'POST'),
('IMAG00169', 'PROD00038', 'SLIDE'),
('IMAG00170', 'PROD00038', 'SLIDE'),
('IMAG00171', 'PROD00038', 'SLIDE');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderdetails`
--

CREATE TABLE `orderdetails` (
  `IdOrder` char(20) NOT NULL,
  `IdProduct` char(20) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `SavedPrice` double NOT NULL DEFAULT 0,
  `SavedDiscount` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orderdetails`
--

INSERT INTO `orderdetails` (`IdOrder`, `IdProduct`, `Quantity`, `SavedPrice`, `SavedDiscount`) VALUES
('ORDE00005', 'PROD00003', 2, 11000000, 0),
('ORDE00009', 'PROD00002', 1, 30510000, 0.3),
('ORDE00010', 'PROD00002', 8, 30510000, 0.3),
('ORDE00011', 'PROD00001', 1, 10000000, 0),
('ORDE00012', 'PROD00001', 1, 10000000, 0),
('ORDE00013', 'PROD00002', 1, 30510000, 0.3),
('ORDE00013', 'PROD00024', 3, 200000, 0.2),
('ORDE00013', 'PROD00025', 2, 3050000, 0.3),
('ORDE00014', 'PROD00001', 1, 10000000, 0),
('ORDE00015', 'PROD00001', 1, 10000000, 0),
('ORDE00017', 'PROD00001', 1, 10000000, 0),
('ORDE00024', 'PROD00009', 5, 6440000, 0.1),
('ORDE00025', 'PROD00009', 10, 6440000, 0.1),
('ORDE00026', 'PROD00009', 10, 6440000, 0.1),
('ORDE00027', 'PROD00010', 4, 122000000, 0.24),
('ORDE00028', 'PROD00012', 1, 107000000, 0.2),
('ORDE00029', 'PROD00010', 4, 122000000, 0.24),
('ORDE00029', 'PROD00021', 53, 1840000, 0.2),
('ORDE00029', 'PROD00025', 23, 3050000, 0.3),
('ORDE00029', 'PROD00027', 15, 4000000, 0.1),
('ORDE00031', 'PROD00008', 2, 11790000, 0.06),
('ORDE00031', 'PROD00009', 8, 6440000, 0.1),
('ORDE00031', 'PROD00030', 2, 849000, 0),
('ORDE00032', 'PROD00002', 20, 30510000, 0.3),
('ORDE00033', 'PROD00028', 25, 2990000, 0),
('ORDE00033', 'PROD00030', 4, 849000, 0),
('ORDE00034', 'PROD00010', 10, 122000000, 0.24),
('ORDE00035', 'PROD00010', 20, 122000000, 0.24),
('ORDE00036', 'PROD00003', 20, 11000000, 0),
('ORDE00037', 'PROD00003', 20, 11000000, 0),
('ORDE00039', 'PROD00008', 1, 11790000, 0.06),
('ORDE00045', 'PROD00008', 44, 11790000, 0.06),
('ORDE00046', 'PROD00007', 50, 65000000, 0.28),
('ORDE00046', 'PROD00009', 3, 6440000, 0.1),
('ORDE00047', 'PROD00010', 4, 122000000, 0.24),
('ORDE00048', 'PROD00003', 18, 11000000, 0),
('ORDE00048', 'PROD00010', 1, 122000000, 0.24),
('ORDE00050', 'PROD00009', 4, 6440000, 0.1),
('ORDE00050', 'PROD00019', 2, 1190000, 0),
('ORDE00051', 'PROD00013', 5, 32000000, 0.2),
('ORDE00052', 'PROD00009', 8, 6440000, 0.1),
('ORDE00053', 'PROD00016', 99, 712000, 0),
('ORDE00056', 'PROD00001', 2, 12000000, 0.05),
('ORDE00056', 'PROD00002', 2, 30510000, 0.3),
('ORDE00056', 'PROD00030', 1, 849000, 0),
('ORDE00057', 'PROD00019', 3, 1190000, 0),
('ORDE00057', 'PROD00021', 3, 1840000, 0.2),
('ORDE00059', 'PROD00002', 8, 30510000, 0.3),
('ORDE00059', 'PROD00008', 20, 11790000, 0.06),
('ORDE00060', 'PROD00007', 17, 65000000, 0.28),
('ORDE00062', 'PROD00007', 1, 65000000, 0.28),
('ORDE00062', 'PROD00010', 1, 122000000, 0.24),
('ORDE00063', 'PROD00009', 1, 6440000, 0.1),
('ORDE00064', 'PROD00009', 1, 6440000, 0.1),
('ORDE00065', 'PROD00016', 3, 712000, 0),
('ORDE00065', 'PROD00030', 1, 849000, 0),
('ORDE00066', 'PROD00010', 1, 122000000, 0.24),
('ORDE00067', 'PROD00016', 4, 712000, 0),
('ORDE00067', 'PROD00038', 1, 5490000, 0.3),
('ORDE00068', 'PROD00002', 3, 30510000, 0.3),
('ORDE00069', 'PROD00021', 1, 1840000, 0.2),
('ORDE00070', 'PROD00003', 3, 11000000, 0),
('ORDE00071', 'PROD00002', 2, 30510000, 0.3),
('ORDE00071', 'PROD00015', 1, 90000000, 0),
('ORDE00071', 'PROD00021', 1, 1840000, 0.2),
('ORDE00071', 'PROD00026', 3, 3320000, 0.3);

--
-- Bẫy `orderdetails`
--
DELIMITER $$
CREATE TRIGGER `check_quantity_insert` AFTER INSERT ON `orderdetails` FOR EACH ROW BEGIN
  DECLARE `product_quantity` INT;
  DECLARE `product_price` DOUBLE;
  DECLARE `product_discount` DOUBLE;
  DECLARE `order_state` VARCHAR(20);
  SELECT quantity,price,discount INTO `product_quantity`, `product_price`,`product_discount` FROM product WHERE IdProduct = NEW.IdProduct;
  SELECT delivery INTO `order_state` FROM orders where orders.IdOrder=NEW.IdOrder;
  IF NEW.quantity > `product_quantity` THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Invalid Quantity';
  ELSE
  	call sp_getTotal(NEW.IdOrder,@sum);
  	UPDATE orders
    SET total = @sum
    WHERE orders.IdOrder=NEW.IdOrder;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_quantity_update` AFTER UPDATE ON `orderdetails` FOR EACH ROW BEGIN
  DECLARE `product_quantity` INT;
  DECLARE `product_price` DOUBLE;
  DECLARE `product_discount` DOUBLE;
  DECLARE `order_state` VARCHAR(20);
  SELECT quantity,price,discount INTO `product_quantity`, `product_price`,`product_discount` FROM product WHERE IdProduct = NEW.IdProduct;
  SELECT delivery INTO `order_state` FROM orders where orders.IdOrder=NEW.IdOrder;
  IF NEW.quantity > `product_quantity` THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Invalid Quantity';
  ELSE
  	call sp_getTotal(NEW.IdOrder,@sum);
  	UPDATE orders
    SET total = @sum
    WHERE orders.IdOrder=NEW.IdOrder;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_delete` AFTER DELETE ON `orderdetails` FOR EACH ROW BEGIN
	if exists(select IdOrder from orders where orders.IdOrder=OLD.IdOrder) then
       UPDATE orders
       SET total=fc_getTotal(IdOrder)
       Where orders.IdOrder=OLD.IdOrder;
   end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `IdOrder` char(20) NOT NULL,
  `IdAcc` char(20) NOT NULL,
  `Date` datetime NOT NULL,
  `Total` double DEFAULT 0,
  `Delivery` varchar(20) NOT NULL DEFAULT 'IN PROGRESS',
  `Payment` varchar(20) NOT NULL DEFAULT 'NONE',
  `Address` char(100) DEFAULT NULL,
  `Name` char(50) DEFAULT NULL,
  `PhoneNumber` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`IdOrder`, `IdAcc`, `Date`, `Total`, `Delivery`, `Payment`, `Address`, `Name`, `PhoneNumber`) VALUES
('ORDE00005', 'USER00008', '2023-05-12 10:56:12', 22000000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309257'),
('ORDE00009', 'USER00008', '2023-05-12 10:58:05', 21357000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309257'),
('ORDE00010', 'USER00008', '2023-05-12 11:00:21', 170856000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309257'),
('ORDE00011', 'USER00008', '2023-05-12 11:07:07', 11400000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00012', 'USER00008', '2023-05-12 11:13:48', 11400000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00013', 'USER00008', '2023-05-12 11:14:33', 26107000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00014', 'USER00008', '2023-05-12 11:15:53', 11400000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00015', 'USER00008', '2023-05-12 11:16:20', 11400000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00017', 'USER00008', '2023-05-12 23:28:21', 11400000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00024', 'USER00008', '2023-05-17 10:12:51', 28980000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00025', 'USER00008', '2023-05-13 20:21:52', 57960000, 'CONFIRMED', 'Banking', '872 Trường Chinh, Phường 15, Tân Bình, Thành phố Hồ Chí Minh', 'Luong Van Hoa', '0906309255'),
('ORDE00026', 'USER00012', '2023-05-13 20:23:17', 57960000, 'CONFIRMED', 'COD', '829 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Luong Van Hoa', '0906309220'),
('ORDE00027', 'USER00013', '2023-05-13 20:25:47', 370880000, 'CONFIRMED', 'Banking', '36 Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyễn Thanh Long', '0906309235'),
('ORDE00028', 'USER00008', '2023-05-13 21:32:48', 85600000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Luong Van Hoa', '0906309255'),
('ORDE00029', 'USER00005', '2023-05-14 12:30:58', 552001000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Long Dragon', '0906309259'),
('ORDE00031', 'USER00017', '2023-05-15 15:48:31', 69759600, 'CONFIRMED', 'Banking', '1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Lưu Văn Tiến', '0902351213'),
('ORDE00032', 'USER00017', '2023-05-15 15:55:20', 427140000, 'CONFIRMED', 'Banking', '19 Hoa Bằng, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'No name', '0902351213'),
('ORDE00033', 'USER00017', '2023-05-15 16:08:07', 78146000, 'CONFIRMED', 'Banking', '1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Lưu Văn Tiến', '0902351213'),
('ORDE00034', 'USER00018', '2023-05-15 16:10:42', 927200000, 'CONFIRMED', 'Banking', '2 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyễn Thanh Long', '0905205201'),
('ORDE00035', 'USER00018', '2023-05-15 16:12:22', 1854400000, 'CONFIRMED', 'Banking', '100 Nguyễn Phúc Chu, Phường 15, Tân Bình, Thành phố Hồ Chí Minh', 'Trần Văn Đức', '0905205201'),
('ORDE00036', 'USER00008', '2023-05-17 10:21:00', 220000000, 'CONFIRMED', 'COD', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00037', 'USER00008', '2023-05-17 10:22:20', 220000000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Admin', '0906309255'),
('ORDE00038', 'USER00008', '2023-05-17 10:24:31', 220000000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh\r\n', 'Bảo', '0906309255'),
('ORDE00039', 'USER00019', '2023-05-17 10:26:14', 10846800, 'CONFIRMED', 'Banking', 'Lầu 3, Pandora City, 1/1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309877'),
('ORDE00045', 'USER00008', '2023-05-17 10:53:39', 477259200, 'CONFIRMED', 'Banking', 'Lầu 3, Pandora City, 1/1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Dạ Phi Cơ', '0906309255'),
('ORDE00046', 'USER00008', '2023-05-17 14:06:50', 2357388000, 'CONFIRMED', 'Banking', '93 Tân Kỳ Tân Quý, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'ds', '0906309255'),
('ORDE00047', 'USER00005', '2023-05-17 15:15:50', 370880000, 'CONFIRMED', 'Banking', '93 Tân Kỳ Tân Quý, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309259'),
('ORDE00048', 'USER00008', '2023-05-20 15:42:14', 290720000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00050', 'USER00008', '2023-05-18 09:07:28', 25564000, 'CONFIRMED', 'Banking', 'Lầu 3, Pandora City, 1/1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', '321321fd', '0906309255'),
('ORDE00051', 'USER00008', '2023-05-18 09:20:42', 128000000, 'CONFIRMED', 'Banking', '213 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309255'),
('ORDE00052', 'USER00008', '2023-05-18 09:24:58', 46368000, 'CONFIRMED', 'Banking', 'Lầu 3, Pandora City, 1/1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309255'),
('ORDE00053', 'USER00021', '2023-05-18 21:34:35', 70488000, 'CONFIRMED', 'Banking', 'sdafasdfasdfasdfasdf', 'sczc', '0587928264'),
('ORDE00056', 'USER00008', '2023-05-19 23:34:52', 66363000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309255'),
('ORDE00057', 'USER00022', '2023-05-19 23:39:04', 7986000, 'CONFIRMED', 'Banking', '93 Tân Kỳ Tân Quý, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309165'),
('ORDE00059', 'USER00008', '2023-05-20 15:56:26', 387792000, 'CONFIRMED', 'Banking', 'dfasfdsadsadsa', 'Bảo', '0906309255'),
('ORDE00060', 'USER00008', '2023-05-20 15:59:30', 795600000, 'CONFIRMED', 'Banking', '45 Hồ Đắc Di, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309255'),
('ORDE00062', 'USER00008', '2023-05-21 23:03:39', 139520000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00063', 'USER00024', '2023-05-20 23:33:13', 5796000, 'CONFIRMED', 'Banking', '1 Trường Chinh, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyên', '0568125681'),
('ORDE00064', 'USER00008', '2023-05-20 23:43:06', 5796000, 'CONFIRMED', 'Banking', '28/8A Đ. Chế Lan Viên, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Bảo', '0906309255'),
('ORDE00065', 'USER00026', '2023-05-22 13:43:33', 2985000, 'CONFIRMED', 'Banking', '682A Trường Chinh, Phường 15, Tân Bình, Thành phố Hồ Chí Minh', 'Nguyễn Thái Bảo', '0906309250'),
('ORDE00066', 'USER00008', '2023-05-22 13:57:34', 92720000, 'CONFIRMED', 'Banking', 'Hẻm 83/20 Phạm Văn Bạch, Phường 15, Tân Bình, Thành phố Hồ Chí Minh', 'Nguyễn Thái Bảo', '0906309255'),
('ORDE00067', 'USER00027', '2023-05-22 14:14:36', 6691000, 'CONFIRMED', 'Banking', '51 Lê Trọng Tấn, Sơn Kỳ, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyễn Thái Bảo', '0906309205'),
('ORDE00068', 'USER00028', '2023-05-22 14:16:50', 64071000, 'CONFIRMED', 'Banking', '718 Trường Chinh, Phường 15, Tân Bình, Thành phố Hồ Chí Minh 72100\r\n', 'Bảo', '0906309282'),
('ORDE00069', 'USER00008', '2023-05-22 14:30:54', 1472000, 'CONFIRMED', 'Banking', '28/8A Đ. Chế Lan Viên, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyễn Thái Bảo', '0906309255'),
('ORDE00070', 'USER00008', '2023-05-22 14:34:14', 33000000, 'CONFIRMED', 'Banking', '253 Đ. Tân Sơn Nhì, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Nguyen Thai Bao', '0906309255'),
('ORDE00071', 'USER00030', '2023-05-22 15:37:36', 141158000, 'CONFIRMED', 'Banking', '155 Tân Kỳ Tân Quý, Tân Sơn Nhì, Tân Phú, Thành phố Hồ Chí Minh', 'Vo Van Teo', '0906304895');

--
-- Bẫy `orders`
--
DELIMITER $$
CREATE TRIGGER `auto_increment_order_id` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
    IF LENGTH(NEW.IdOrder)=0 THEN
        SET `current_max_id` = (SELECT SUBSTRING(MAX(IdOrder),5,7) FROM orders WHERE IdOrder LIKE 'ORDE%');
        IF `current_max_id` IS NULL THEN
            SET `new_id` = 'ORDE00001';
        ELSE
            SET `new_id` = CONCAT('ORDE', LPAD((`current_max_id` + 1), 5, '0'));
        END IF;
        SET NEW.IdOrder = `new_id`;
     END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_order` BEFORE DELETE ON `orders` FOR EACH ROW BEGIN
    DELETE FROM orderdetails WHERE IdOrder = OLD.IdOrder;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_total_insert` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
	IF(NEW.Total is null)THEN
    	set new.Total=0;
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_total_update` BEFORE UPDATE ON `orders` FOR EACH ROW BEGIN
	IF(NEW.Total is null)THEN
    	set new.Total=0;
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `IdProduct` char(20) NOT NULL,
  `NameProduct` varchar(100) NOT NULL,
  `IdCatalog` char(20) NOT NULL,
  `IdBrand` char(20) NOT NULL,
  `Price` double NOT NULL,
  `Quantity` int(11) DEFAULT 0,
  `Discount` double DEFAULT 0,
  `Description` varchar(1000) DEFAULT NULL,
  `State` varchar(10) NOT NULL DEFAULT 'NEW'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`IdProduct`, `NameProduct`, `IdCatalog`, `IdBrand`, `Price`, `Quantity`, `Discount`, `Description`, `State`) VALUES
('PROD00001', 'TITAN Plus a3080Ti', 'CATA00001', 'BRAN00017', 12000000, 7, 0.05, 'Mainboard: ASUS CROSSHAIR VIII HERO WIFI X570 (AMD Socket AM4)#CPU: AMD Ryzen 9 5950X / 64MD / 3.4GHz Boost 4.9GHz / 16 Cores 32 Threads#RAM: G.SKILL Trident Z Royal RGB SILVER 2x8GB bus 5066#RAM: G.SKILL Trident Z Royal RGB SILVER 2x8GB bus 5066#VGA - Card đồ họa: MSI GeForce RTX 3080 Ti GAMING X TRIO 12G#HDD: Optional upgrade#SSD: SSD Samsung 970 Evo Plus 500G M.2 NVMe 500GB#PSU: Nguồn ASUS Rog Thor 1200P - 80 Plus Platinum - Full Modular#Case: ASUS ROG Strix Helios GX601#Cooling: ASUS ROG RYUJIN II 360', 'HIDDEN'),
('PROD00002', 'PHANTOM i3050', 'CATA00001', 'BRAN00017', 30510000, 11, 0.3, 'Mainboard: GIGABYTE B760M AORUS ELITE AX DDR4#CPU: Intel Core i7 13700F / 2.1GHz Turbo 5.2GHz / 16 Nhân 24 Luồng / 30MB / LGA 1700#RAM: PNY XLR8 Silver 8GB 3600 RGB x 2#VGA: GIGABYTE GeForce RTX 3050 EAGLE OC 8G#HDD: Có thể tùy chọn Nâng cấp#SSD: Kingston NV2 250GB M.2 PCIe NVMe Gen4#PSU: Nguồn CoolerMaster MWE 750 BRONZE - V2 230V#Case: Case Cooler Master CMP 510 (3 fan ARGB)#Cooling: Cooler Master MASTERLIQUID ML240 ILLUSION', 'NEW'),
('PROD00003', 'Black MINION i1650', 'CATA00001', 'BRAN00017', 11000000, 17, 0, 'Mainboard: GIGABYTE H510M H (rev. 1.0)#CPU: Intel Core i3 10105F / 6MB / 4.4GHZ / 4 nhân 8 luồng / LGA 1200#RAM: Ram PNY XLR8 Silver 1x8GB 3200 RGB#VGA: ASUS TUF Gaming GeForce GTX 1650 4GB GDDR6#HDD: Có thể tùy chọn Nâng cấp#SSD: PNY CS900 240G 2.5 Sata 3#PSU: Deepcool PF450D - 80 Plus#Case: Case Xigmatek PHANTOM 3F (3 fan RGB)', 'NEW'),
('PROD00007', 'PHANTOM Plus i4070Ti', 'CATA00001', 'BRAN00017', 65000000, 2, 0.28, 'Mainboard: GIGABYTE Z790 AERO G DDR5#CPU : Intel Core i7 13700K / 3.4GHz Turbo 5.4GHz / 16 Nhân 24 Luồng / 30MB / LGA 1700#RAM: Corsair Vengeance RGB 32GB (2x16GB) 5600 DDR5 White#VGA: GIGABYTE GeForce RTX 4070 Ti AERO OC 12G#SSD: GIGABYTE NVMe 512Gb#HDD: Tùy chọn nâng cấp#PSU: GIGABYTE UD850GM 80 Plus Gold#CASE: NZXT H7 Flow White#FAN: Cooler Master MasterFan MF120 Halo White Kit 3 Fan', 'NEW'),
('PROD00008', 'MINION i1650', 'CATA00001', 'BRAN00017', 11790000, 20, 0.08, 'Mainboard: GIGABYTE H610M H DDR4#CPU: Intel Core i3 12100F / 3.3GHz Turbo 4.3GHz / 4 Nhân 8 Luồng / 12MB / LGA 1700#RAM: Ram PNY XLR8 Silver 1x8GB 3200 RGB#VGA: ASUS TUF Gaming GeForce GTX 1650 OC Edition 4GB GDDR6#HDD: Có thể tùy chọn Nâng cấp#SSD: SSD PNY CS900 240G 2.5 Sata 3#PSU: ( 450W ) Nguồn Gigabyte P450B - 80 Plus Bronze#Case: Case Xigmatek PHANTOM 3F White (3 fan RGB)', 'NEW'),
('PROD00009', 'Homework R3', 'CATA00001', 'BRAN00017', 6440000, 15, 0.1, 'Mainboard: Mainboard Asus PRIME A320M-K#CPU: AMD Ryzen 3 3200G / 6MB / 4.0GHz / 4 nhân 4 luồng / AM4#RAM: RAM TeamGroup T-Force Vulcan Z Red 8GB 3200 DDR4#HDD: Có thể tùy chọn Nâng cấp	 #SSD: SSD PNY CS900 240G 2.5\" Sata 3#PSU: Jetek 350W Elite V2#Case: Jetek EM4', 'NEW'),
('PROD00010', 'POSEIDON 9 i3090', 'CATA00001', 'BRAN00017', 122000000, 77, 0.24, 'Mainboard: ASUS ROG STRIX Z690-A GAMING WIFI DDR5#CPU: Intel Core i9 12900K / 3.2GHz Turbo 5.2GHz / 16 Nhân 24 Luồng / 30MB / LGA 1700#RAM: Corsair Dominator Platinum 32GB (2x16GB) RGB 5600 DDR5 White#Graphic Card: ASUS ROG Strix GeForce RTX 3090 Gaming OC edition 24G#HDD: Có thể tùy chọn Nâng cấp#SSD: SamSung 980 PRO 1TB M.2 PCIe gen 4 NVMe#PSU: ASUS Rog Thor 1200P - 80 Plus Platinum - Full Modular#Case: Cougar ConQuer 2 - Full Tower#Fan: Corsair LL120 RGB LED 120mm White — Triple Pack with Lighting Node PRO#Fan: Corsair LL120 RGB LED 120mm White — Triple Pack with Lighting Node PRO', 'NEW'),
('PROD00011', 'VIPER Plus i3060', 'CATA00001', 'BRAN00017', 30870000, 50, 0.15, 'Mainboard: GIGABYTE B760M AORUS ELITE AX DDR4#CPU: Intel Core i5 13600KF / 3.5GHz Turbo 5.1GHz / 14 Nhân 20 Luồng / 24MB / LGA 1700#RAM: PNY XLR8 Silver 8GB 3200 RGB x 2#VGA: GIGABYTE GeForce RTX 3060 GAMING OC 12G (rev 2.0)#HDD: Có thể tùy chọn nâng cấp#SSD: WD Blue SN570 250G M.2 NVMe PCIe Gen3#PSU: ASUS TUF Gaming 750B 80 Plus Bronze#Case: Case Corsair 4000D AIRFLOW Black#Fan: Corsair iCUE SP120 RGB ELITE 120mm — Triple Pack with Lighting Node', 'NEW'),
('PROD00012', 'POSEIDON 7 i3080', 'CATA00001', 'BRAN00017', 107000000, 24, 0.2, 'Mainboard: ASUS ROG STRIX Z690-F GAMING WIFI DDR5#CPU: Intel Core i7 12700K / 3.6GHz Turbo 5.0GHz / 12 Nhân 20 Luồng / 25MB / LGA 1700#RAM: Corsair Vengeance RGB 32GB (2x16GB) 5600 DDR5#Graphic card: MSI GeForce RTX 3080 Suprim X 10G (LHR)#HDD: Có thể tùy chọn Nâng cấp#SSD: SamSung 980 PRO 1TB M.2 PCIe gen 4 NVMe#PSU: ASUS ROG STRIX 1000W - 80 Plus Gold - Full Modular#Case: Lian Li O11D Evo Black#Fan: Lian Li UNI Fan SL 120 Triple Black (LED ARGB - Fan ghép nối không dây)#Fan: Lian Li UNI Fan SL 120 Triple Black (LED ARGB - Fan ghép nối không dây)#Fan: Lian Li UNI Fan SL 120 Triple Black (LED ARGB - Fan ghép nối không dây)', 'NEW'),
('PROD00013', 'POSEIDON S', 'CATA00001', 'BRAN00017', 32000000, 4, 0.2, 'Mainboard: MSI MAG B660M MORTAR WIFI DDR4#CPU: Intel Core i5 13600KF / 3.5GHz Turbo 5.1GHz / 14 Nhân 20 Luồng / 24MB / LGA 1700#RAM: PNY XLR8 Silver 8GB 3600 RGB x 2#Graphic card: ASUS TUF Gaming GeForce RTX 3060 V2 O12G GDDR6 (LHR)#HDD: Có thể tùy chọn Nâng cấp#SSD: WD Blue SN570 500G M.2 NVMe PCIe Gen3#PSU: CoolerMaster MWE 750 BRONZE - V2 230V#Case: Case Cooler Master CMP 510 (3 fan ARGB) ', 'NEW'),
('PROD00014', 'POSEIDON 7 i3060', 'CATA00001', 'BRAN00017', 34000000, 15, 0.1, 'Mainboard: GIGABYTE Z690 AORUS ELITE AX DDR4#CPU: Intel Core i7 13700F / 2.1GHz Turbo 5.2GHz / 16 Nhân 24 Luồng / 30MB / LGA 1700#RAM: Corsair Vengeance RS RGB 8GB 3600 x 2#Graphic card: GIGABYTE GeForce RTX 3060 GAMING OC 12G #HDD: Có thể tùy chọn Nâng cấp#SSD: WD Blue SN570 500G M.2 NVMe PCIe Gen3#PSU: Thermaltake Smart BM2 750W - 80 Plus Bronze#Case: Corsair 4000D AIRFLOW Black', 'NEW'),
('PROD00015', 'TITAN Plus i3090', 'CATA00001', 'BRAN00017', 90000000, 19, 0, 'Mainboard: ASUS ROG STRIX Z690-F GAMING WIFI DDR5#CPU: Intel Core i9 13900K / 3.0GHz Turbo 5.8GHz / 24 Nhân 32 Luồng / 36MB / LGA 1700#RAM: Corsair Vengeance RGB 32GB (2x16GB) 5600 DDR5#VGA: ASUS ROG Strix GeForce RTX 3090 Gaming OC edition 24G#SSD: Samsung 980 M.2 PCIe NVMe 1TB#HDD: Tùy chọn nâng cấp#PSU: ASUS ROG STRIX 1000W - 80 Plus Gold - Full Modular#CASE: ASUS ROG Strix Helios GX601#COOLING: ASUS ROG RYUJIN II 360#FAN: Corsair LL120 RGB LED 120mm — Triple Pack with Lighting Node PRO', 'NEW'),
('PROD00016', 'DareU EM901X', 'CATA00002', 'BRAN00004', 712000, 12, 0, 'Bảo hành: 24 tháng#Model: DareU EM901X RGB Superlight Wireless Pink#Màu sắc: Hồng#Thiết kế: Đối xứng #Kiểu kết nối: Wireless 2.4Ghz#Thời gian sử dụng: 30h / 18h (khi bật led RGB)#Thời gian sạc: 3h', 'NEW'),
('PROD00018', 'Logitech G502', 'CATA00002', 'BRAN00011', 1799000, 50, 0.1, 'Model: Logitech G502 Hero KDA#Sensor: HERO™#Resolution: 100 - 25,600 dpi#Maximum acceleration: > 40 G#Maximum speed: > 400 IPS#USB data format: 16 bit/axis#USB report rate: 1000 Hz (1ms)#Processor: ARM 32-bit', 'NEW'),
('PROD00019', 'Razer Basilisk V3', 'CATA00002', 'BRAN00015', 1190000, 44, 0, 'Design: Right-handed#Connection: Wired (Razer Speedflex Cable)#Battery life: None#RGB Lights: Razer Chroma RGB#Sensor: Optical#Maximum sensitivity: 26000#Maximum speed: 650', 'NEW'),
('PROD00020', 'Aerox 3 Wireless', 'CATA00002', 'BRAN00001', 2290000, 0, 0, 'Color: White#CPI: 100–18,000 in 100 CPI increments#IPS: 400#Polling Rate: 1000Hz / 1 ms#Number of buttons: 6#Switch life: Up to 80 million taps#Led: 3 RGB Zones#Weight: 68g#Connect: 2.4GHz / Bluetooth 5.0#Battery life: 80 Hours (2.4GHz) / 200 Hours (Bluetooth)', 'OOS'),
('PROD00021', 'Corsair Glaive Pro', 'CATA00002', 'BRAN00003', 1840000, 112, 0.2, 'Number of buttons: 7#DPI: 18000#Sensor: PWM3391#LED Display: RGB#Switch Type: 50 million clicks of Switch Omron#Connect: Wired#Hand Form Type: Palms#Weight: 115g#Software compatibility: iCUE#Cable length: 1.8m#Report Rate: 1000Hz/500Hz/250Hz/125Hz (selectable)', 'NEW'),
('PROD00022', 'Clutch GM08', 'CATA00002', 'BRAN00012', 599000, 17, 0.15, 'Color: Black#Connection Type: Wired, USB 2.0#Response time: 1000Hz#LED: Red#Sensitivity ( DPI ): 200 / 400 / 800 / 1600 / 3200 (up to 4200 using software)#Sensor: PAW-3519#Sensor type:  Optical#Number of buttons: 6#Switch life: Up to 10 million keystrokes#Dimensions: 128 x 68.5 x 40.5 mm#Weight: 92g#Cable length: 1.8m', 'NEW'),
('PROD00023', 'Pulsefire Haste ', 'CATA00002', 'BRAN00018', 1990000, 25, 0.05, 'Color: White#Connection type: Wireless 2.4GHz / Wired#Led light: RGB#Design Type: Symmetry#Sensitivity (DPI): Up to 16000#Speed: 450 IPS#Sensor: Pixart PAW3335#Number of buttons: 6#Switches: TTC Golden Micro with IP55 . dust resistance#Switch life: 80 million presses#Battery: Polymer Li-ion 370mAh (Up to 100 hours of battery life)#Compatible: PC, PS5™, and PS4™', 'NEW'),
('PROD00024', 'DareU LM115G', 'CATA00002', 'BRAN00004', 200000, 17, 0.2, 'Type: Wireless Mouse#Dimensions: 107.5 x 59.15 x 38.29 (mm)#Sensor: PAW3512#Wave: 2.4GHz#DPI: 800 / 1200 / 1600#Switch: Silent 3 million clicks#Weight: 90 g#Number of buttons: 6', 'NEW'),
('PROD00025', 'Leopold FC660R', 'CATA00003', 'BRAN00009', 3050000, 0, 0.3, 'Switch Cherry Brown / Red / Black / Blue#PCB: FR4 Dual layer#Connectivity: Bluetooth 5.1 and USB type-C#Style: TKL (66 keys)#Weight: 700g#Dimensions (W x D x H): 113.4 x 326 x 30.5 mm', 'OOS'),
('PROD00026', 'Leopold FC750RPD', 'CATA00003', 'BRAN00009', 3320000, 12, 0.3, 'Connection: Bluetooth 5.1 and USB-C#Keyboard type: 87 Keys#Keycap PBT Doubleshot#Switch: Cherry MX Brown / Red / Slilent Red / Blue#LED: No#PCB Board FR4 DUAL LAYER#Dimensions: 361.5(L) x 139.3(W) x 31(H) mm#Weight: 1 kg', 'NEW'),
('PROD00027', 'DAREU A98 Pro', 'CATA00003', 'BRAN00004', 4000000, 0, 0.1, 'Compatible Devices: Gaming Console#Connectivity Technology: 2.4G Wireless, USB-C, BT5.1#Keyboard Description: Gaming#Special Feature: Battery LED Screen, Ergonomic, Backlit, Rechargeable, Customizable: Display Keys#Color: Mecha Blue#Operating System: Mac#Number of Keys: 98#Keyboard backlighting color support: RGB#Style: Modern', 'OOS'),
('PROD00028', 'AKKO 5108B Plus', 'CATA00003', 'BRAN00001', 2990000, 0, 0, 'Series/Model: AKKO 5108B Plus Demon Slayer – Kamado Tanjirou#Hotswap: 5 pin TTC socket#Connectivity: USB Type C, detachable / Bluetooth 5.0 (up to 3 devices) / Wireless 2.4Ghz (1 in 3). Recommends only plugging a 2.4ghz USB receiver into a USB 2.0 port for the best wireless signal.#Designs: Fullsize (108 keys)#Keycaps: Keycap PBT Dye-Subbbed, JDA Profile#Led: RGB background LED (6028 SMD LED) with multiple modes#Switch: AKKO CS Switch – Crystal#Accessory: 1 user manual + 1 USB Type-C to USB cord + 1 2.4Ghz USB Receiver + Keycap included#Battery life: 3000mah battery (Consume 12ma/hour in wireless mode and without LED on)#Software:AKKO Cloud (Support Audio Visualizer in 2.4Ghz connection mode) can keymap and adjust LED#Compatible: Windows / MacOS / Linux', 'OOS'),
('PROD00029', 'Corsair K60 Pro', 'CATA00003', 'BRAN00001', 1990000, 0, 0, 'Led: RED#Color: Black#Size: FullSize#Switch: CHERRY® VIOLA#Keycaps: ABS Doubleshot#Anti-Ghosting: Yes#Software: iCUE#Connectivity: Wired, USB 2.0 Type A#Weight: 0.88kg', 'OOS'),
('PROD00030', 'DareU EK1280s', 'CATA00003', 'BRAN00001', 849000, 11, 0, 'Model: EK1280s Pink and White#Switch: DareU Super Durable D Switch#Led: Pink#Keycap: ABS DoubleShot#Size: Full size#Wire Length: 1.8m#Connectivity: USB', 'NEW'),
('PROD00031', 'G512 GX RGB', 'CATA00003', 'BRAN00011', 2090000, 22, 0, 'Model: G512 GX#Color: Black#Switch: GX Switch Blue (Clicky)#Connection Type: USB 2.0#USB protocol: USB 2.0#USB port (Built-in): 2.0#Led: RGB#Dimensions: 132 x 445 x 35.5 mm#Weight (without cord): 1130 g#Cable length: 1.8 m', 'NEW'),
('PROD00032', 'Strix Scope NX Deluxe', 'CATA00003', 'BRAN00002', 3590000, 26, 0.1, 'Warranty: 24 months#Series/Model: Asus ROG Strix Scope NX Deluxe#Color: Black#Connection: Wired#Style: Full-size#Keyboard type: Mechanical keyboard#LEDs: Per-Key RGB LEDs#Switch: ROG NX Mechanical Switches: Blue/Red#Accessories: 2 ROG Logo Stickers, FPS keycaps (WASD), keycap puller, quick start guide', 'NEW'),
('PROD00033', 'Philips 272E1GSJ', 'CATA00004', 'BRAN00014', 4890000, 200, 0.05, 'Size: 27 inches#Aspect ratio: 16:9 #Display area: 597.89 x 336.31mm#Response time (normal): 4 ms (Time taken for pixels to transition between two gray levels)#Panels: VA#Viewing angle: 178°/178° .#Pixel Pitch: 0.2745mm#Resolution: 1920x1080#Brightness: 350cd/㎡#Contrast Ratio: 3000:1#Pixel density: 82 PPI#Color display: 16.7 million colors#Display time: 1ms MPRT#Refresh rate: 144 Hz .#Display Coating: Anti-Glare, 3H, 25% Glare#LowBlue Mode: Yes#EasyRead: Yes#Interface: DisplayPort x 1 / HDMI (digital, HDCP)#Advanced AMD FreeSync Technology#Power Consumption:#- Standby : 0.5W#- Off mode: 0.3W', 'NEW'),
('PROD00034', 'Lenovo L24Q-35', 'CATA00004', 'BRAN00008', 6790000, 100, 0.3, 'Size: 23.8 inches#Aspect ratio: 16:9#Panels: IPS #Viewing angle: 178°/178°#Resolution: QHD 2560 x 1440#Brightness: 300 cd/m2#Static contrast ratio: 1000:1#Dynamic Contrast Ratio: 3M:1#Color display: 16.7 million colors#Response time: 4 ms (Severe Mode) | 6 ms (Typical mode)#Scan frequency: 75Hz', 'NEW'),
('PROD00035', 'SAMSUNG QLED LC49G95 ', 'CATA00004', 'BRAN00016', 51999000, 10, 0.5, 'Size: 49 inch screen#Resolution: 5,120 x 1,440 (32:9)#Backplate: VA#Scan frequency: 240Hz#Response time: 1ms#Screen type (flat / curved): Curved 1000R#Brightness: 420cd/m2#Viewing Angle: 178 (H) / 178 (V)#Color rendering: 1.07 billion colors, 125% sRGB, Adobe RGB92%, NTSC 1976)88%#Static Contrast: 2500:1#Dynamic Contrast: 100,000,000 : 1', 'NEW'),
('PROD00036', 'Viewsonic VX2416', 'CATA00004', 'BRAN00019', 3190000, 20, 0.23, 'Size: 23.8 inches#Resolution: 1920 x 1080 FHD (Full HD)#Panel: IPS#Frequency: scan 100Hz#Response time: 1ms#Screen Type: Flat#Brightness: 250 cd/m2 (typ)#Viewing Angle: 178 (H) / 178 (V)#Color rendering capacity: 16.7 million colors#Static Contrast: 1,000:1 (typ)#Dynamic Contrast: 80M:1', 'NEW'),
('PROD00037', 'HKC MB27V9', 'CATA00004', 'BRAN00006', 4590000, 20, 0, 'Model: MB27V9#Screen size: 27 inches#Resolution: FHD 1920 x 1080#Panel: IPS#Ratio: 16:9#Scan frequency: 75hz#Brightness: 250 cd/m2#Color rendering capacity: 16.7 million colors#Response time: 8ms (GTG)#Viewing Angle: 178°(H)/178°(V)#Connector: VGA | HDMI#Weight: 5.51 kg#Dimensions: 710x120x443mm#Accessories: Adapter | HDMI cable Wall Mount 75x75mm', 'NEW'),
('PROD00038', 'LG 27MK600M-B', 'CATA00004', 'BRAN00010', 5490000, 18, 0.3, 'Size: 27 inches#Resolution: 1920 x 1080 (16:9)#Panel: IPS#Scan frequency: 75Hz#Response time: 5ms#Screen Type: Flat#Brightness: 250cd/m2 (Typical) 200cd/m2 (Min)#Viewing Angle: 178 / 178#Color rendering capacity: 16.7 million colors#Static Contrast: 1000:1 (Typical)#Dynamic Contrast: Mega#Output port: 2 x HDMI, 1 x D-Sub#Special Features: Anti-glare, 3H#Weight: 4.8 kg (including stand)#Power Consumption: 25.5 W#Dimensions: 61.2 cm x 20.8 cm x 45.5 cm', 'NEW');

--
-- Bẫy `product`
--
DELIMITER $$
CREATE TRIGGER `auto_increment_product_id` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE `current_max_id` INT;
    DECLARE `new_id` VARCHAR(14);
    SET `current_max_id` = (SELECT SUBSTRING(MAX(IdProduct),5,7) FROM product WHERE IdProduct LIKE 'PROD%');
    IF `current_max_id` IS NULL THEN
        SET `new_id` = 'PROD00001';
    ELSE
        SET `new_id` = CONCAT('PROD', LPAD((`current_max_id` + 1), 5, '0'));
    END IF;
    SET NEW.IdProduct = `new_id`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `change_state_insert` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
	IF NEW.State!='HIDDEN' AND NEW.Quantity=0 THEN
    	SET NEW.State='OOS';
    ELSEIF NEW.State!='HIDDEN' AND NEW.Quantity>0 THEN
    	SET NEW.State='NEW';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `change_state_update` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
	IF NEW.State!='HIDDEN' AND NEW.Quantity=0 THEN
    	SET NEW.State='OOS';
    ELSEIF NEW.State!='HIDDEN' AND NEW.Quantity>0 THEN
    	SET NEW.State='NEW';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_Discount_insert` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    IF NEW.Discount is null or NEW.Discount="" THEN
    	set NEW.Discount=0;
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_Discount_update` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
    IF NEW.Discount is null or NEW.Discount="" THEN
    	set NEW.Discount=0;
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_product` BEFORE DELETE ON `product` FOR EACH ROW BEGIN
    DELETE FROM orderdetails WHERE IdProduct = OLD.IdProduct;
    DELETE FROM imagedetails WHERE IdProduct = OLD.IdProduct;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_product_update_order` AFTER UPDATE ON `product` FOR EACH ROW BEGIN
	IF(NEW.price!=OLD.price or NEW.discount!=OLD.discount) then
        UPDATE orders
        set total = fc_getTotal(IdOrder)
        where IdOrder=(select orders.IdOrder from product,orderdetails where NEW.IdProduct=product.IdProduct and product.IdProduct=orderdetails.IdProduct and orderdetails.IdOrder=orders.IdOrder);
     end if;
END
$$
DELIMITER ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`IdAcc`),
  ADD UNIQUE KEY `NONCLUSTERED` (`Name`);

--
-- Chỉ mục cho bảng `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`IdBrand`),
  ADD UNIQUE KEY `NONCLUSTERED` (`NameBrand`);

--
-- Chỉ mục cho bảng `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`IdCatalog`),
  ADD UNIQUE KEY `NONCLUSTERED` (`NameCatalog`);

--
-- Chỉ mục cho bảng `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`IdImage`),
  ADD UNIQUE KEY `NONCLUSTERED` (`FileImage`),
  ADD UNIQUE KEY `FileImage` (`FileImage`);

--
-- Chỉ mục cho bảng `imagedetails`
--
ALTER TABLE `imagedetails`
  ADD PRIMARY KEY (`IdImage`,`IdProduct`),
  ADD KEY `FK_ImageDetails_Product` (`IdProduct`);

--
-- Chỉ mục cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`IdOrder`,`IdProduct`),
  ADD KEY `FK_OderDetails_Product` (`IdProduct`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`IdOrder`),
  ADD KEY `FK_Order_User` (`IdAcc`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`IdProduct`),
  ADD KEY `FK_Product_Catalog` (`IdCatalog`),
  ADD KEY `FK_Product_Brand` (`IdBrand`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `imagedetails`
--
ALTER TABLE `imagedetails`
  ADD CONSTRAINT `FK_ImageDetails_Image` FOREIGN KEY (`IdImage`) REFERENCES `image` (`IdImage`),
  ADD CONSTRAINT `FK_ImageDetails_Product` FOREIGN KEY (`IdProduct`) REFERENCES `product` (`IdProduct`);

--
-- Các ràng buộc cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `FK_OderDetails_Order` FOREIGN KEY (`IdOrder`) REFERENCES `orders` (`IdOrder`),
  ADD CONSTRAINT `FK_OderDetails_Product` FOREIGN KEY (`IdProduct`) REFERENCES `product` (`IdProduct`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_Order_User` FOREIGN KEY (`IdAcc`) REFERENCES `account` (`IdAcc`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_Product_Brand` FOREIGN KEY (`IdBrand`) REFERENCES `brand` (`IdBrand`),
  ADD CONSTRAINT `FK_Product_Catalog` FOREIGN KEY (`IdCatalog`) REFERENCES `catalog` (`IdCatalog`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
