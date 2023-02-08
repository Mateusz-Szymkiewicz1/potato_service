-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 07 Lut 2023, 20:21
-- Wersja serwera: 10.3.15-MariaDB
-- Wersja PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `baza_testowa`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `address` varchar(400) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `zip_code` varchar(75) DEFAULT NULL,
  `credit_rating` enum('EXCELLENT','GOOD','POOR') DEFAULT NULL,
  `sales_rep_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `customer`
--

INSERT INTO `customer` (`id`, `name`, `phone`, `address`, `city`, `state`, `country`, `zip_code`, `credit_rating`, `sales_rep_id`, `region_id`, `comments`) VALUES
(201, 'Unisports', '55-2066101', '72 Via Bahia', 'Sao Paolo', NULL, 'Brazil', NULL, 'EXCELLENT', 12, 2, NULL),
(202, 'OJ Atheletics', '81-20101', '6741 Takashi Blvd.', 'Osaka', NULL, 'Japan', NULL, 'POOR', 14, 4, NULL),
(203, 'Delhi Sports', '91-10351', '11368 Chanakya', 'New Delhi', NULL, 'India', NULL, 'GOOD', 14, 4, NULL),
(204, 'Womansport', '1-206-104-0103', '281 King Street', 'Seattle', 'Washington', 'USA', NULL, 'EXCELLENT', 11, 1, NULL),
(205, 'Kam\'s Sporting Goods', '852-3692888', '15 Henessey Road', 'Hong Kong', NULL, NULL, NULL, 'EXCELLENT', 15, 4, NULL),
(206, 'Sportique', '33-2257201', '172 Rue de Rivoli', 'Cannes', NULL, 'France', NULL, 'EXCELLENT', 15, 5, NULL),
(207, 'Sweet Rock Sports', '234-6036201', '6 Saint Antoine', 'Lagos', NULL, 'Nigeria', NULL, 'GOOD', NULL, 3, NULL),
(208, 'Muench Sports', '49-527454', '435 Gruenestrasse', 'Stuttgart', NULL, 'Germany', NULL, 'GOOD', 15, 5, NULL),
(209, 'Beisbol Si!', '809-352689', '792 Playa Del Mar', 'San Pedro de Macon\'s', NULL, 'Dominican Republic', NULL, 'EXCELLENT', 11, 1, NULL),
(210, 'Futbol Sonora', '52-404562', '3 Via Saguaro', 'Nogales', NULL, 'Mexico', NULL, 'EXCELLENT', 12, 2, NULL),
(211, 'Kuhn\'s Sports', '42-111292', '7 Modrany', 'Prague', NULL, 'Czechoslovakia', NULL, 'EXCELLENT', 15, 5, NULL),
(212, 'Hamada Sport', '20-1209211', '57A Corniche', 'Alexandria', NULL, 'Egypt', NULL, 'EXCELLENT', 13, 3, NULL),
(213, 'Big John\'s Sports Emporium', '1-415-555-6281', '4783 18th Street', 'San Francisco', 'CA', 'USA', NULL, 'EXCELLENT', 11, 1, NULL),
(214, 'Ojibway Retail', '1-716-555-7171', '415 Main Street', 'Buffalo', 'NY', 'USA', NULL, 'POOR', 11, 1, NULL),
(215, 'Sporta Russia', '7-3892456', '6000 Yekatamina', 'Saint Petersburg', NULL, 'Russia', NULL, 'POOR', 15, 5, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dept`
--

CREATE TABLE `dept` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `dept`
--

INSERT INTO `dept` (`id`, `name`, `region_id`) VALUES
(50, 'Administration', 1),
(10, 'Finance', 1),
(41, 'Operations', 1),
(42, 'Operations', 2),
(43, 'Operations', 3),
(44, 'Operations', 4),
(45, 'Operations', 5),
(31, 'Sales', 1),
(32, 'Sales', 2),
(33, 'Sales', 3),
(34, 'Sales', 4),
(35, 'Sales', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `emp`
--

CREATE TABLE `emp` (
  `id` int(11) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `userid` varchar(8) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `salary` decimal(11,2) DEFAULT NULL,
  `commission_pct` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `emp`
--

INSERT INTO `emp` (`id`, `last_name`, `first_name`, `userid`, `start_date`, `comments`, `manager_id`, `title`, `dept_id`, `salary`, `commission_pct`) VALUES
(1, 'Velasquez', 'Carmen', 'cvelasqu', '1990-03-03', NULL, NULL, 'President', 50, '2500.00', NULL),
(2, 'Ngao', 'LaDoris', 'lngao', '1990-03-08', NULL, 1, 'VP, Operations', NULL, '1450.00', NULL),
(3, 'Nagayama', 'Midori', 'mnagayam', '1991-06-17', NULL, 1, 'VP, Sales', 31, '1400.00', NULL),
(4, 'Quick-To-See', 'Mark', 'mquickto', '1990-04-07', NULL, 1, 'VP, Finance', 10, '1450.00', NULL),
(5, 'Ropeburn', 'Audry', 'aropebur', '0000-00-00', NULL, 1, 'VP, Administration', 50, '1550.00', NULL),
(6, 'Urguhart', 'Molly', 'murguhar', '1991-01-18', NULL, 2, 'Warehouse Manager', NULL, '1200.00', NULL),
(7, 'Menchu', 'Roberta', 'rmenchu', '1990-05-14', NULL, 2, 'Warehouse Manager', NULL, '1250.00', NULL),
(8, 'Biri', 'Ben', 'bbiri', '1990-04-07', NULL, 2, 'Warehouse Manager', 43, '1100.00', NULL),
(9, 'Catchpole', 'Antoinette', 'acatchpo', '1992-02-09', NULL, 2, 'Warehouse Manager', 44, '1300.00', NULL),
(10, 'Havel', 'Marta', 'mhavel', '1991-02-27', NULL, 2, 'Warehouse Manager', 45, '1307.00', NULL),
(11, 'Magee', 'Colin', 'cmagee', '1990-05-14', NULL, 3, 'Sales Representative', 31, '1400.00', '10.00'),
(12, 'Giljum', 'Henry', 'hgiljum', '1992-01-18', NULL, 3, 'Sales Representative', 32, '1490.00', '12.50'),
(13, 'Sedeghi', 'Yasmin', 'ysedeghi', '1991-02-08', NULL, 3, 'Sales Representative', 33, '1515.00', '10.00'),
(14, 'Nguyen', 'Mai', 'mnguyen', '1992-01-22', NULL, 3, 'Sales Representative', 34, '1525.00', '15.00'),
(15, 'Dumas', 'Andre', 'adumas', '1991-10-09', NULL, 3, 'Sales Representative', 35, '1450.00', '17.50'),
(16, 'Maduro', 'Elena', 'emaduro', '1992-02-07', NULL, 6, 'Stock Clerk', NULL, '1400.00', NULL),
(17, 'Smith', 'George', 'gsmith', '1990-03-08', NULL, 6, 'Stock Clerk', NULL, '940.00', NULL),
(18, 'Nozaki', 'Akira', 'anozaki', '1991-02-09', NULL, 7, 'Stock Clerk', NULL, '1200.00', NULL),
(19, 'Patel', 'Vikram', 'vpatel', '1991-08-06', NULL, 7, 'Stock Clerk', NULL, '1000.00', NULL),
(20, 'Newman', 'Chad', 'cnewman', '1991-07-21', NULL, 8, 'Stock Clerk', 43, '750.00', NULL),
(21, 'Markarian', 'Alexander', 'amarkari', '1991-05-26', NULL, 8, 'Stock Clerk', 43, '850.00', NULL),
(22, 'Chang', 'Eddie', 'echang', '1990-11-30', NULL, 9, 'Stock Clerk', 44, '800.00', NULL),
(23, 'Patel', 'Radha', 'rpatel', '1990-10-17', NULL, 9, 'Stock Clerk', 34, '795.00', NULL),
(24, 'Dancs', 'Bela', 'bdancs', '1991-03-17', NULL, 10, 'Stock Clerk', 45, '860.00', NULL),
(25, 'Schwartz', 'Sylvie', 'sschwart', '1991-05-09', NULL, 10, 'Stock Clerk', 45, '1100.00', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `amount_in_stock` int(11) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `max_in_stock` int(11) DEFAULT NULL,
  `out_of_stock_explanation` varchar(255) DEFAULT NULL,
  `restock_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `inventory`
--

INSERT INTO `inventory` (`product_id`, `warehouse_id`, `amount_in_stock`, `reorder_point`, `max_in_stock`, `out_of_stock_explanation`, `restock_date`) VALUES
(10011, 101, 650, 625, 1100, NULL, NULL),
(10012, 101, 600, 560, 1000, NULL, NULL),
(10012, 10501, 300, 300, 525, NULL, NULL),
(10013, 101, 400, 400, 700, NULL, NULL),
(10013, 10501, 314, 300, 525, NULL, NULL),
(10021, 101, 500, 425, 740, NULL, NULL),
(10022, 101, 300, 200, 350, NULL, NULL),
(10022, 10501, 502, 300, 525, NULL, NULL),
(10023, 101, 400, 300, 525, NULL, NULL),
(10023, 10501, 500, 300, 525, NULL, NULL),
(20106, 101, 993, 625, 1000, NULL, NULL),
(20106, 201, 220, 150, 260, NULL, NULL),
(20106, 10501, 150, 100, 175, NULL, NULL),
(20108, 101, 700, 700, 1225, NULL, NULL),
(20108, 201, 166, 150, 260, NULL, NULL),
(20108, 10501, 222, 200, 350, NULL, NULL),
(20201, 101, 802, 800, 1400, NULL, NULL),
(20201, 201, 320, 200, 350, NULL, NULL),
(20201, 10501, 275, 200, 350, NULL, NULL),
(20510, 101, 1389, 850, 1400, NULL, NULL),
(20510, 201, 175, 100, 175, NULL, NULL),
(20510, 301, 69, 40, 100, NULL, NULL),
(20510, 401, 88, 50, 100, NULL, NULL),
(20510, 10501, 57, 50, 87, NULL, NULL),
(20512, 101, 850, 850, 1450, NULL, NULL),
(20512, 201, 162, 100, 175, NULL, NULL),
(20512, 301, 28, 20, 50, NULL, NULL),
(20512, 401, 75, 75, 140, NULL, NULL),
(20512, 10501, 62, 50, 87, NULL, NULL),
(30321, 101, 2000, 1500, 2500, NULL, NULL),
(30321, 201, 96, 80, 140, NULL, NULL),
(30321, 301, 85, 80, 140, NULL, NULL),
(30321, 401, 102, 80, 140, NULL, NULL),
(30321, 10501, 194, 150, 275, NULL, NULL),
(30326, 101, 2100, 2000, 3500, NULL, NULL),
(30326, 201, 147, 120, 210, NULL, NULL),
(30326, 401, 113, 80, 140, NULL, NULL),
(30326, 10501, 277, 250, 440, NULL, NULL),
(30421, 101, 1822, 1800, 3150, NULL, NULL),
(30421, 201, 102, 80, 140, NULL, NULL),
(30421, 301, 102, 80, 140, NULL, NULL),
(30421, 401, 85, 80, 140, NULL, NULL),
(30421, 10501, 190, 150, 275, NULL, NULL),
(30426, 101, 2250, 2000, 3500, NULL, NULL),
(30426, 201, 200, 120, 210, NULL, NULL),
(30426, 401, 135, 80, 140, NULL, NULL),
(30426, 10501, 423, 250, 450, NULL, NULL),
(30433, 101, 650, 600, 1050, NULL, NULL),
(30433, 201, 130, 130, 230, NULL, NULL),
(30433, 301, 35, 20, 35, NULL, NULL),
(30433, 401, 0, 100, 175, 'A defective shipment was sent to Hong Kong and needed to be returned.\r\nThe soonest ACME can turn this around is early February.', '1992-08-07 00:00:00'),
(30433, 10501, 273, 200, 350, NULL, NULL),
(32779, 101, 2120, 1250, 2200, NULL, NULL),
(32779, 201, 180, 150, 260, NULL, NULL),
(32779, 301, 102, 95, 175, NULL, NULL),
(32779, 401, 135, 100, 175, NULL, NULL),
(32779, 10501, 280, 200, 350, NULL, NULL),
(32861, 101, 505, 500, 875, NULL, NULL),
(32861, 201, 132, 80, 140, NULL, NULL),
(32861, 301, 57, 50, 100, NULL, NULL),
(32861, 401, 250, 150, 250, NULL, NULL),
(32861, 10501, 288, 200, 350, NULL, NULL),
(40421, 101, 578, 350, 600, NULL, NULL),
(40421, 301, 70, 40, 70, NULL, NULL),
(40421, 401, 47, 40, 70, NULL, NULL),
(40421, 10501, 97, 80, 140, NULL, NULL),
(40422, 101, 0, 350, 600, 'Phenomenal sales...', '1993-02-08 00:00:00'),
(40422, 301, 65, 40, 70, NULL, NULL),
(40422, 401, 50, 40, 70, NULL, NULL),
(40422, 10501, 90, 80, 140, NULL, NULL),
(41010, 101, 250, 250, 437, NULL, NULL),
(41010, 301, 59, 40, 70, NULL, NULL),
(41010, 401, 80, 70, 220, NULL, NULL),
(41010, 10501, 151, 140, 245, NULL, NULL),
(41020, 101, 471, 450, 750, NULL, NULL),
(41020, 301, 61, 40, 70, NULL, NULL),
(41020, 401, 91, 70, 220, NULL, NULL),
(41020, 10501, 224, 140, 245, NULL, NULL),
(41050, 101, 501, 450, 750, NULL, NULL),
(41050, 301, 49, 40, 70, NULL, NULL),
(41050, 401, 169, 70, 220, NULL, NULL),
(41050, 10501, 157, 140, 245, NULL, NULL),
(41080, 101, 400, 400, 700, NULL, NULL),
(41080, 301, 50, 40, 70, NULL, NULL),
(41080, 401, 100, 70, 220, NULL, NULL),
(41080, 10501, 159, 140, 245, NULL, NULL),
(41100, 101, 350, 350, 600, NULL, NULL),
(41100, 301, 42, 40, 70, NULL, NULL),
(41100, 401, 75, 70, 220, NULL, NULL),
(41100, 10501, 141, 140, 245, NULL, NULL),
(50169, 101, 2530, 1500, 2600, NULL, NULL),
(50169, 201, 225, 220, 385, NULL, NULL),
(50169, 401, 240, 200, 350, NULL, NULL),
(50273, 101, 233, 200, 350, NULL, NULL),
(50273, 201, 75, 60, 100, NULL, NULL),
(50273, 401, 224, 150, 280, NULL, NULL),
(50417, 101, 518, 500, 875, NULL, NULL),
(50417, 201, 82, 60, 100, NULL, NULL),
(50417, 401, 130, 120, 210, NULL, NULL),
(50418, 101, 244, 100, 275, NULL, NULL),
(50418, 201, 98, 60, 100, NULL, NULL),
(50418, 401, 156, 100, 175, NULL, NULL),
(50419, 101, 230, 120, 310, NULL, NULL),
(50419, 201, 77, 60, 100, NULL, NULL),
(50419, 401, 151, 150, 280, NULL, NULL),
(50530, 101, 669, 400, 700, NULL, NULL),
(50530, 201, 62, 60, 100, NULL, NULL),
(50530, 401, 119, 100, 175, NULL, NULL),
(50532, 101, 0, 100, 175, 'Wait for Spring.', '1993-04-12 00:00:00'),
(50532, 201, 67, 60, 100, NULL, NULL),
(50532, 401, 233, 200, 350, NULL, NULL),
(50536, 101, 173, 100, 175, NULL, NULL),
(50536, 201, 97, 60, 100, NULL, NULL),
(50536, 401, 138, 100, 175, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `item`
--

CREATE TABLE `item` (
  `ord_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `quantity_shipped` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `item`
--

INSERT INTO `item` (`ord_id`, `item_id`, `product_id`, `price`, `quantity`, `quantity_shipped`) VALUES
(97, 1, 20106, '9.00', 1000, 1000),
(97, 2, 30321, '1500.00', 50, 50),
(98, 1, 40421, '85.00', 7, 7),
(99, 1, 20510, '9.00', 18, 18),
(99, 2, 20512, '8.00', 25, 25),
(99, 3, 50417, '80.00', 53, 53),
(99, 4, 50530, '45.00', 69, 69),
(100, 1, 10011, '135.00', 500, 500),
(100, 2, 10013, '380.00', 400, 400),
(100, 3, 10021, '14.00', 500, 500),
(100, 4, 10023, '36.00', 400, 400),
(100, 5, 30326, '582.00', 600, 600),
(100, 6, 30433, '20.00', 450, 450),
(100, 7, 41010, '8.00', 250, 250),
(101, 1, 30421, '16.00', 15, 15),
(101, 2, 40422, '50.00', 30, 30),
(101, 3, 41010, '8.00', 20, 20),
(101, 4, 41100, '45.00', 35, 35),
(101, 5, 50169, '4.29', 40, 40),
(101, 6, 50417, '80.00', 27, 27),
(101, 7, 50530, '45.00', 50, 50),
(102, 1, 20108, '28.00', 100, 100),
(102, 2, 20201, '123.00', 45, 45),
(103, 1, 30433, '20.00', 15, 15),
(103, 2, 32779, '7.00', 11, 11),
(104, 1, 20510, '9.00', 7, 7),
(104, 2, 20512, '8.00', 12, 12),
(104, 3, 30321, '1669.00', 19, 19),
(104, 4, 30421, '16.00', 35, 35),
(105, 1, 50273, '22.89', 16, 16),
(105, 2, 50419, '80.00', 13, 13),
(105, 3, 50532, '47.00', 28, 28),
(106, 1, 20108, '28.00', 46, 46),
(106, 2, 20201, '123.00', 21, 21),
(106, 3, 50169, '4.29', 125, 125),
(106, 4, 50273, '22.89', 75, 75),
(106, 5, 50418, '75.00', 98, 98),
(106, 6, 50419, '80.00', 27, 27),
(107, 1, 20106, '11.00', 50, 50),
(107, 2, 20108, '28.00', 22, 22),
(107, 3, 20201, '115.00', 130, 130),
(107, 4, 30321, '1669.00', 75, 75),
(107, 5, 30421, '16.00', 55, 55),
(108, 1, 20510, '9.00', 9, 9),
(108, 2, 20512, '8.00', 18, 18),
(108, 3, 30321, '1669.00', 85, 85),
(108, 4, 32779, '7.00', 60, 60),
(108, 5, 32861, '60.00', 57, 57),
(108, 6, 41080, '35.00', 50, 50),
(108, 7, 41100, '45.00', 42, 42),
(109, 1, 10011, '140.00', 150, 150),
(109, 2, 10012, '175.00', 600, 600),
(109, 3, 10022, '21.95', 300, 300),
(109, 4, 30326, '582.00', 1500, 1500),
(109, 5, 30426, '18.25', 500, 500),
(109, 6, 32861, '60.00', 50, 50),
(109, 7, 50418, '75.00', 43, 43),
(110, 1, 50273, '22.89', 17, 17),
(110, 2, 50536, '50.00', 23, 23),
(111, 1, 40421, '65.00', 27, 27),
(111, 2, 41080, '35.00', 29, 29),
(112, 1, 20106, '11.00', 50, 50);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ord`
--

CREATE TABLE `ord` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_ordered` datetime DEFAULT NULL,
  `date_shipped` datetime DEFAULT NULL,
  `sales_rep_id` int(11) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `payment_type` enum('CASH','CREDIT') DEFAULT NULL,
  `order_filled` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `ord`
--

INSERT INTO `ord` (`id`, `customer_id`, `date_ordered`, `date_shipped`, `sales_rep_id`, `total`, `payment_type`, `order_filled`) VALUES
(97, 201, '1992-08-28 00:00:00', '1992-09-17 00:00:00', 12, '84000.00', 'CREDIT', 'Y'),
(98, 202, '1992-08-31 00:00:00', '1992-09-10 00:00:00', 14, '595.00', 'CASH', 'Y'),
(99, 203, '1992-08-31 00:00:00', '1992-09-18 00:00:00', 14, '7707.00', 'CREDIT', 'Y'),
(100, 204, '1992-08-31 00:00:00', '1992-09-10 00:00:00', 11, '601100.00', 'CREDIT', 'Y'),
(101, 205, '1992-08-31 00:00:00', '1992-09-15 00:00:00', 14, '8056.60', 'CREDIT', 'Y'),
(102, 206, '1992-09-01 00:00:00', '1992-09-08 00:00:00', 15, '8335.00', 'CREDIT', 'Y'),
(103, 208, '1992-09-02 00:00:00', '1992-09-22 00:00:00', 15, '377.00', 'CASH', 'Y'),
(104, 208, '1992-09-03 00:00:00', '1992-09-23 00:00:00', 15, '32430.00', 'CREDIT', 'Y'),
(105, 209, '1992-09-04 00:00:00', '1992-09-18 00:00:00', 11, '2722.24', 'CREDIT', 'Y'),
(106, 210, '1992-09-07 00:00:00', '1992-09-15 00:00:00', 12, '15634.00', 'CREDIT', 'Y'),
(107, 211, '1992-09-07 00:00:00', '1992-09-21 00:00:00', 15, '142171.00', 'CREDIT', 'Y'),
(108, 212, '1992-09-07 00:00:00', '1992-09-10 00:00:00', 13, '149570.00', 'CREDIT', 'Y'),
(109, 213, '1992-09-08 00:00:00', '1992-09-28 00:00:00', 11, '1020935.00', 'CREDIT', 'Y'),
(110, 214, '1992-09-09 00:00:00', '1992-09-21 00:00:00', 11, '1539.13', 'CASH', 'Y'),
(111, 204, '1992-09-09 00:00:00', '1992-09-21 00:00:00', 11, '2770.00', 'CASH', 'Y'),
(112, 210, '1992-08-31 00:00:00', '1992-09-10 00:00:00', 12, '550.00', 'CREDIT', 'Y');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `suggested_price` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `product`
--

INSERT INTO `product` (`id`, `name`, `short_desc`, `suggested_price`) VALUES
(10011, 'Bunny Boot', 'Beginner\'s ski boot', '150.00'),
(10012, 'Ace Ski Boot', 'Intermediate ski boot', '200.00'),
(10013, 'Pro Ski Boot', 'Advanced ski boot', '410.00'),
(10021, 'Bunny Ski Pole', 'Beginner\'s ski pole', '16.25'),
(10022, 'Ace Ski Pole', 'Intermediate ski pole', '21.95'),
(10023, 'Pro Ski Pole', 'Advanced ski pole', '40.95'),
(20106, 'Junior Soccer Ball', 'Junior soccer ball', '11.00'),
(20108, 'World Cup Soccer Ball', 'World cup soccer ball', '28.00'),
(20201, 'World Cup Net', 'World cup net', '123.00'),
(20510, 'Black Hawk Knee Pads', 'Knee pads, pair', '9.00'),
(20512, 'Black Hawk Elbow Pads', 'Elbow pads, pair', '8.00'),
(30321, 'Grand Prix Bicycle', 'Road bicycle', '1669.00'),
(30326, 'Himalaya Bicycle', 'Mountain bicycle', '582.00'),
(30421, 'Grand Prix Bicycle Tires', 'Road bicycle tires', '16.00'),
(30426, 'Himalaya Tires', 'Mountain bicycle tires', '18.25'),
(30433, 'New Air Pump', 'Tire pump', '20.00'),
(32779, 'Slaker Water Bottle', 'Water bottle', '7.00'),
(32861, 'Safe-T Helmet', 'Bicycle helmet', '60.00'),
(40421, 'Alexeyer Pro Lifting Bar', 'Straight bar', '65.00'),
(40422, 'Pro Curling Bar', 'Curling bar', '50.00'),
(41010, 'Prostar 10 Pound Weight', 'Ten pound weight', '8.00'),
(41020, 'Prostar 20 Pound Weight', 'Twenty pound weight', '12.00'),
(41050, 'Prostar 50 Pound Weight', 'Fifty pound weight', '25.00'),
(41080, 'Prostar 80 Pound Weight', 'Eighty pound weight', '35.00'),
(41100, 'Prostar 100 Pound Weight', 'One hundred pound weight', '45.00'),
(50169, 'Major League Baseball', 'Baseball', '4.29'),
(50273, 'Chapman Helmet', 'Batting helmet', '22.89'),
(50417, 'Griffey Glove', 'Outfielder\'s glove', '80.00'),
(50418, 'Alomar Glove', 'Infielder\'s glove', '75.00'),
(50419, 'Steinbach Glove', 'Catcher\'s glove', '80.00'),
(50530, 'Cabrera Bat', 'Thirty inch bat', '45.00'),
(50532, 'Puckett Bat', 'Thirty-two inch bat', '47.00'),
(50536, 'Winfield Bat', 'Thirty-six inch bat', '50.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `region`
--

CREATE TABLE `region` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `region`
--

INSERT INTO `region` (`id`, `name`) VALUES
(3, 'Africa / Middle East'),
(4, 'Asia'),
(5, 'Europe'),
(1, 'North America'),
(2, 'South America');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `title`
--

CREATE TABLE `title` (
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `title`
--

INSERT INTO `title` (`name`) VALUES
('President'),
('Sales Representative'),
('Stock Clerk'),
('VP, Administration'),
('VP, Finance'),
('VP, Operations'),
('VP, Sales'),
('Warehouse Manager');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `address` longtext DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `zip_code` varchar(75) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `warehouse`
--

INSERT INTO `warehouse` (`id`, `region_id`, `address`, `city`, `state`, `country`, `zip_code`, `phone`, `manager_id`) VALUES
(101, 1, '283 King Street', 'Seattle', 'WA', 'USA', NULL, NULL, 6),
(201, 2, '68 Via Centrale', 'Sao Paolo', NULL, 'Brazil', NULL, NULL, 7),
(301, 3, '6921 King Way', 'Lagos', NULL, 'Nigeria', NULL, NULL, 8),
(401, 4, '86 Chu Street', 'Hong Kong', NULL, NULL, NULL, NULL, 9),
(10501, 5, '5 Modrany', 'Bratislava', NULL, 'Czechozlovakia', NULL, NULL, 10);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_region_id_fk` (`region_id`),
  ADD KEY `customer_sales_rep_id_fk` (`sales_rep_id`);

--
-- Indeksy dla tabeli `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_name_region_id_uk` (`name`,`region_id`),
  ADD KEY `dept_region_id_fk` (`region_id`);

--
-- Indeksy dla tabeli `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_userid_uk` (`userid`),
  ADD KEY `emp_dept_id_fk` (`dept_id`),
  ADD KEY `emp_manager_id_fk` (`manager_id`),
  ADD KEY `emp_title_fk` (`title`);

--
-- Indeksy dla tabeli `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`,`warehouse_id`),
  ADD KEY `inventory_warehouse_id_fk` (`warehouse_id`);

--
-- Indeksy dla tabeli `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ord_id`,`item_id`),
  ADD KEY `item_ordid_prodid_uk` (`ord_id`,`product_id`),
  ADD KEY `item_product_id_fk` (`product_id`);

--
-- Indeksy dla tabeli `ord`
--
ALTER TABLE `ord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ord_customer_id_fk` (`customer_id`),
  ADD KEY `ord_sales_rep_id_fk` (`sales_rep_id`);

--
-- Indeksy dla tabeli `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_name_uk` (`name`);

--
-- Indeksy dla tabeli `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_name_uk` (`name`);

--
-- Indeksy dla tabeli `title`
--
ALTER TABLE `title`
  ADD PRIMARY KEY (`name`);

--
-- Indeksy dla tabeli `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_manager_id_fk` (`manager_id`),
  ADD KEY `warehouse_region_id_fk` (`region_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT dla tabeli `dept`
--
ALTER TABLE `dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT dla tabeli `emp`
--
ALTER TABLE `emp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT dla tabeli `ord`
--
ALTER TABLE `ord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT dla tabeli `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50537;

--
-- AUTO_INCREMENT dla tabeli `region`
--
ALTER TABLE `region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10502;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_region_id_fk` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `customer_sales_rep_id_fk` FOREIGN KEY (`sales_rep_id`) REFERENCES `emp` (`id`);

--
-- Ograniczenia dla tabeli `dept`
--
ALTER TABLE `dept`
  ADD CONSTRAINT `dept_region_id_fk` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Ograniczenia dla tabeli `emp`
--
ALTER TABLE `emp`
  ADD CONSTRAINT `emp_dept_id_fk` FOREIGN KEY (`dept_id`) REFERENCES `dept` (`id`),
  ADD CONSTRAINT `emp_manager_id_fk` FOREIGN KEY (`manager_id`) REFERENCES `emp` (`id`),
  ADD CONSTRAINT `emp_title_fk` FOREIGN KEY (`title`) REFERENCES `title` (`name`);

--
-- Ograniczenia dla tabeli `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `inventory_warehouse_id_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Ograniczenia dla tabeli `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ord_id_fk` FOREIGN KEY (`ord_id`) REFERENCES `ord` (`id`),
  ADD CONSTRAINT `item_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ograniczenia dla tabeli `ord`
--
ALTER TABLE `ord`
  ADD CONSTRAINT `ord_customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `ord_sales_rep_id_fk` FOREIGN KEY (`sales_rep_id`) REFERENCES `emp` (`id`);

--
-- Ograniczenia dla tabeli `warehouse`
--
ALTER TABLE `warehouse`
  ADD CONSTRAINT `warehouse_manager_id_fk` FOREIGN KEY (`manager_id`) REFERENCES `emp` (`id`),
  ADD CONSTRAINT `warehouse_region_id_fk` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
