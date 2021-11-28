-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2021 at 03:35 PM
-- Server version: 10.3.31-MariaDB-log-cll-lve
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scottjpq_fastfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `type`, `title`, `image`) VALUES
(2, 'Home', '50% discount', 'banner1.webp'),
(3, 'Home\r\n', 'Basic Food Delivery Collect', 'banner2.webp\r\n'),
(4, 'Home', 'Food Delivery Collection', 'banner3.webp'),
(5, 'Staff', 'Get your meals', 'slider1.jpg'),
(6, 'Staff', 'Buy Now', 'banner1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `company_name` varchar(70) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`id`, `code`, `company_name`) VALUES
(1, 'BVFGHF', 'bankai'),
(2, 'SCJSL', 'shikai');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(70) NOT NULL,
  `delivery_days` varchar(100) NOT NULL,
  `delivery_hours` varchar(100) NOT NULL,
  `main_address` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `code`, `title`, `delivery_days`, `delivery_hours`, `main_address`) VALUES
(1, 'BVFGHF', 'bankai', 'Mon-Fri', '9am-5pm', '45 Test Baker Street, London, IG11 8RR'),
(7, 'FshMel1', 'fish-fastery', 'Mon - Fri', '9am - 5pm', '<p>25 Baker Street<br></p>'),
(3, 'SCJSL', 'shikai', 'Mon - Fri', '9am - 5pm', '<p><span>25 Test Movies Street, London, IG11 2RR</span><br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `company_address`
--

CREATE TABLE `company_address` (
  `id` int(11) NOT NULL,
  `company` varchar(70) NOT NULL,
  `delivery_address` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_address`
--

INSERT INTO `company_address` (`id`, `company`, `delivery_address`) VALUES
(1, 'bankai', '402 Test Baker Street, London, IG11 8RT'),
(2, 'bankai', '35 Test Baker Street, London, IG11 8RT'),
(9, 'shikai', '402 Test Baker Street, London, IG11 8RT'),
(8, 'Fish Fastery', '35 Test Baker Street, London, IG11 8RT');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `side_meal` varchar(5) NOT NULL,
  `side_drink` varchar(5) NOT NULL,
  `meal_voucher` varchar(5) NOT NULL,
  `delivery_start` varchar(50) NOT NULL,
  `date` text NOT NULL,
  `delivery_end` varchar(50) NOT NULL,
  `stock` varchar(10) NOT NULL,
  `image1` varchar(100) NOT NULL,
  `image2` varchar(100) NOT NULL,
  `image3` varchar(100) NOT NULL,
  `image4` varchar(100) NOT NULL,
  `image5` varchar(100) NOT NULL,
  `sold` int(10) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `code`, `title`, `price`, `description`, `category`, `side_meal`, `side_drink`, `meal_voucher`, `delivery_start`, `date`, `delivery_end`, `stock`, `image1`, `image2`, `image3`, `image4`, `image5`, `sold`, `created_time`, `created_date`) VALUES
(2, 'XBYCAVUD227', 'Big-Mac-Burger', 5.87, 'Lorem ipsum dolor sit amet, consectetur adipic it, sed do eiusmod tempor labor incididunt ut et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.\r\n\r\n', 'Burger', 'Yes', 'Yes', 'No', '10:00', 'Tuesday,Wednesday,Friday', '16:30', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628276954, '2021-07-02 21:16:17'),
(3, 'DUXBYAVC762', 'Pepperoni-Pizza', 3.56, 'Lorem ipsum dolor sit amet, consectetur adipic it, sed do eiusmod tempor labor incididunt ut et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.\r\n\r\n', 'Pizza', 'No', 'No', 'No', '09:00', 'Monday', '18:00', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628276954, '2021-07-02 21:20:16'),
(4, 'VCUDXAYB788', 'Barbeque-Pizza', 3.44, 'Lorem ipsum dolor sit amet, consectetur adipic it, sed do eiusmod tempor labor incididunt ut et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.\r\n\r\n', 'Pizza', 'No', 'No', 'No', '09:00', 'Monday', '18:00', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628276954, '2021-07-02 21:22:40'),
(12, 'DVEBXFGVCZXYCUA78', 'Fried-Seabream-Meal', 5.5, '<p><p>Loremp ispoum<br></p>&nbsp;</p><p>Loremp ispoum<br></p><p>Loremp ispoum<br></p><p>Loremp ispoum<br></p><p>Loremp ispoum<br></p><p><br></p><p><br></p><p><br></p><p><br></p>', 'Fish', 'Yes', 'Yes', 'No', '09:00', 'Wednesday', '18:00', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1627757184, '2021-07-31 18:46:24'),
(13, 'DACVFZEBXYCVUXG822', 'Fish-Fastery', 5.5, '<p>Lorem ipsum Lorem ipsum </p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p>Lorem ipsum Lorem ipsum<br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p>', 'Fish', 'No', 'No', 'No', '09:00', 'Monday', '17:00', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628202028, '2021-08-05 22:20:28'),
(14, 'AVXDEZVBCFGUCYX956', 'Fish-Meal-test', 4.57, '<p>none<br></p>', 'Burger', 'No', 'No', 'No', '09:00', 'Monday', '18:00', 'Yes', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628276954, '2021-08-06 19:09:14'),
(21, 'FVCDUYZCVXGBXAE394', 'Spiela', 10, '<p>Lorem ipsum color doem psoeudo&nbsp;<span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span><span style=\"font-family: &quot;varela round&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">Lorem ipsum color doem psoeudo&nbsp;</span></p>', 'Burger', 'Yes', 'Yes', 'Yes', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'Yes', 'banner1.jpg', 'banner1.jpg', 'banner1.jpg', 'banner1.jpg', 'banner1.jpg', 0, 1630502207, '2021-09-01 13:16:47'),
(20, 'FGACVEBXYCVUXGE522', 'Meal-Test', 5.5, '<p><b>none</b><br></p>', 'Burger', 'No', 'No', 'No', '09:00', 'Thursday,Friday,Saturday,Sunday', '18:00', 'No', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 'health-article-2.jpg', 0, 1628276954, '2021-08-07 10:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `food_details`
--

CREATE TABLE `food_details` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food_details`
--

INSERT INTO `food_details` (`id`, `code`) VALUES
(1, 'CYVAUBXD356'),
(2, 'XBYCAVUD227'),
(3, 'DUXBYAVC762'),
(4, 'VCUDXAYB788'),
(5, 'BVXACUYD404'),
(6, 'VXAUCYDB29'),
(7, 'UABYCXDV937');

-- --------------------------------------------------------

--
-- Table structure for table `meal_vouchers`
--

CREATE TABLE `meal_vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(70) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `discount` int(10) NOT NULL,
  `bulk` varchar(10) NOT NULL,
  `company` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meal_vouchers`
--

INSERT INTO `meal_vouchers` (`id`, `code`, `title`, `description`, `price`, `discount`, `bulk`, `company`, `type`, `category`, `quantity`) VALUES
(1, 'SCJSL', 'Meal 11', '<p>none<br></p>', 55, 0, 'Yes', 'bankai', 'Large', 'Offer-Meals', 1);

-- --------------------------------------------------------

--
-- Table structure for table `meal_voucher_details`
--

CREATE TABLE `meal_voucher_details` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `discount` int(10) NOT NULL,
  `company` varchar(100) NOT NULL,
  `type` varchar(70) NOT NULL,
  `category` varchar(70) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meal_voucher_details`
--

INSERT INTO `meal_voucher_details` (`id`, `code`, `email`, `title`, `description`, `discount`, `company`, `type`, `category`, `price`, `quantity`, `created_time`, `created_date`) VALUES
(5, 'SCJSL', 'staff@gmail.com', 'Meal 11', '<p>none<br></p>', 0, 'bankai', 'Large', 'Offer-Meals', 55, 1, 1631086994, '2021-09-08 07:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category`) VALUES
(1, 'Burger'),
(2, 'Pizza'),
(3, 'Chicken'),
(4, 'Fish'),
(6, 'Offer-Meals'),
(7, 'Family-Deals'),
(8, 'Chicken-Meal');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `order_title` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `order_id`, `firstname`, `lastname`, `email`, `order_title`, `subject`, `body`, `status`, `created_time`, `created_date`) VALUES
(4, 'ADTBVXYUC804', 'Scott', 'Phoenix', 'scottphenix24@gmail.com', 'Barbeque-Pizza', 'non', 'thiu', 'Rejected', 1628292989, '2021-08-06 23:36:29'),
(3, 'ADTBVXYUC804', 'Scott', 'Phoenix', 'scottphenix24@gmail.com', 'Barbeque-Pizza', 'Need to change', 'Change my order', 'Rejected', 1627759196, '2021-07-31 19:19:56'),
(2, 'ADTBVXYUC804', 'Scott', 'Phoenix', 'scottphenix24@gmail.com', 'Barbeque-Pizza', 'Needed change', 'At least update the meal.', 'Rejected', 1626604490, '2021-07-18 10:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(30) NOT NULL,
  `staff_code` varchar(30) NOT NULL,
  `company` varchar(70) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `telephone` varchar(22) NOT NULL,
  `address` text NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `town` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `staff_code`, `company`, `firstname`, `lastname`, `telephone`, `address`, `postcode`, `town`) VALUES
(31, 'UABCVTYXD339', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', '30 Test Baker Street, London, IG11 8RT', 'IG11 8RR', 'Barking'),
(30, 'BVAXYUCDT720', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(29, 'BVYTDUACX743', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(28, 'TBCYUXADV735', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(27, 'TBAUXCVYD191', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(26, 'UXVTBAYDC595', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(25, 'XAYVTUDBC709', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(23, 'ADTBVXYUC804', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(22, 'CXDTYAVBU56', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', '30 Test Baker Street, London, IG11 8RT', 'IG11 8RR', 'Barking'),
(24, 'XAYVTUDBC709', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(32, 'CYAVDUXBT915', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', 'Select a delivery address', 'IG11 8RR', 'Barking'),
(33, 'CYAVDUXBT915', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', 'Select a delivery address', 'IG11 8RR', 'Barking'),
(34, 'CYAVDUXBT915', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', 'Select a delivery address', 'IG11 8RR', 'Barking'),
(35, 'CYAVDUXBT915', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', '07445487194', 'Select a delivery address', 'IG11 8RR', 'Barking'),
(36, 'BDVUCYXAT283', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(37, 'BDVUCYXAT283', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(38, 'TYCUAXBVD995', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(39, 'TYCUAXBVD995', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(40, 'YVDCTUBXA3', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(41, 'YVDCTUBXA3', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(42, 'BDATCVXYU958', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(43, 'AXBYTDCVU423', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(44, 'TAYCXDUBV659', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(45, 'BDUCVAXYT25', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(46, 'DBVATUYXC979', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(47, 'VXDYUBTAC943', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford'),
(48, 'VUDXCTBAY474', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(49, 'VUDXCTBAY474', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(50, 'DBVXAYUTC218', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(51, 'VYBACTDUX647', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', 'Select a delivery address', 'none', 'none'),
(52, 'TDYUAVXBC950', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(53, 'BTUACYDVX808', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', 'Select a delivery address', 'none', 'none'),
(54, 'TXABUCVYD663', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(55, 'CTVXYADBU554', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(56, 'CTVXYADBU554', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(57, 'DATUVBYCX120', 'BVFGHF', 'bankai', 'FirstName', 'LastName', '000', '30 Test Baker Street, London, IG11 8RT', 'none', 'none'),
(58, 'DTACYVBXU86', '', 'none', 'Scott', 'Phoenix', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG11 9TR', 'Illford'),
(59, 'VTBDXCUAY831', '', 'none', 'FirstName', 'LastName', '000', 'none', 'IG119TR', 'none'),
(60, 'TYUXVBCAD477', '', 'none', 'Armina', 'Phata', '07445487194', '45 Test Baker Street, Ilford, IG115RT', '', 'Illford');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `voucher_code` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL,
  `order_notes` text NOT NULL,
  `side_meal` varchar(100) NOT NULL,
  `side_drink` varchar(100) NOT NULL,
  `delivery_date` varchar(50) NOT NULL,
  `delivery_category` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `voucher_code`, `email`, `title`, `price`, `quantity`, `image`, `order_notes`, `side_meal`, `side_drink`, `delivery_date`, `delivery_category`, `status`, `created_time`, `created_date`) VALUES
(68, 'TYUXVBCAD477', '', 'admin@fastfood.com', 'Fish-Fastery', 5.5, 1, 'health-article-2.jpg', 'none', 'none', 'none', '13th Sep 2021', 'Delivery', 'Pending', 1631556786, '2021-09-13 18:13:06'),
(67, 'VTBDXCUAY831', '', 'tigerphenix24@gmail.com', 'Spiela', 10, 1, 'banner1.jpg', 'none', 'Fried Fish', 'Coca Cola', '01st Sep 2021', 'Delivery', 'Cancelled', 1631086633, '2021-09-08 07:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `scheduler`
--

CREATE TABLE `scheduler` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `delivery_date` varchar(50) NOT NULL,
  `delivery_day` varchar(30) NOT NULL,
  `num_time` varchar(10) NOT NULL,
  `postcode` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scheduler`
--

INSERT INTO `scheduler` (`id`, `email`, `delivery_date`, `delivery_day`, `num_time`, `postcode`) VALUES
(1, 'scottphenix24@gmail.com', '03rd Sep 2021', 'Friday', '09:00', 'IG119TR'),
(2, 'tigerphenix24@gmail.com', '01st Sep 2021', 'Wednesday', '09:00', 'IG119TR'),
(3, 'joseph.mangu@webxconsult.co.uk', '', '', '', ''),
(4, 'admin@fastfood.com', '13th Sep 2021', 'Monday', '', ''),
(12, 'kitchen@fastfood.com', '', '', '', ''),
(11, 'user@fastfood.com', '', '', '', ''),
(9, 'staff@gmail.com', '08th Sep 2021', 'Wednesday', '11:00', 'IG119TR'),
(13, 'josephmmx@gmail.com', '', '', '', ''),
(14, 'joseph.mangu@webxconsult.co.uk', '', '', '', ''),
(15, 'kingphenix24@gmail.com', '14th Sep 2021', 'Tuesday', '14:00', 'IG119TR'),
(16, 'simmyphenix24@gmail.com', '14th Sep 2021', 'Tuesday', '09:00', 'IG119TR');

-- --------------------------------------------------------

--
-- Table structure for table `side_drink`
--

CREATE TABLE `side_drink` (
  `id` int(11) NOT NULL,
  `category` varchar(70) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `side_drink`
--

INSERT INTO `side_drink` (`id`, `category`, `title`) VALUES
(1, 'Burger', 'Fanta'),
(2, 'Burger', 'Pepsi'),
(3, 'Burger', 'Sprite'),
(4, 'Burger', 'Coca Cola');

-- --------------------------------------------------------

--
-- Table structure for table `side_meal`
--

CREATE TABLE `side_meal` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `side_meal`
--

INSERT INTO `side_meal` (`id`, `category`, `title`) VALUES
(1, 'Burger', 'Chicken with Plantain'),
(2, 'Chicken', 'Fried Chicken'),
(7, 'Burger', 'Fried Fish');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(200) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `image`, `title`, `subtitle`, `category`) VALUES
(3, 'slider3.jpg', 'Amazing Meals', 'Get the best food delivered to your homes', 'Staff'),
(4, 'slider4.jpg', 'Meals for Me', 'Get the best food delivered to your homes', 'Home');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `staff_code` varchar(20) NOT NULL,
  `company` varchar(70) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `telephone` varchar(22) NOT NULL,
  `address` text NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `town` varchar(50) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `code`, `staff_code`, `company`, `firstname`, `lastname`, `email`, `password`, `role`, `status`, `telephone`, `address`, `postcode`, `town`, `created_time`, `created_date`) VALUES
(2, 'DEFGHZXC44', 'BVFGHF', 'bankai', 'Simmy', 'Phenix', 'simmyphenix24@gmail.com', '$2a$08$rUrbWbZVzL06sgqR6S5EHeNG4AT06ons9yZSfGLmj1h8hp4BKgXH2', 'Staff', 'Activated', '07445487194', '45 Test Baker Street, London, IG11 8RR', 'IG11 8RR', 'Barking', 1625522851, '2021-07-05 22:07:31'),
(15, 'DEFGHZXC785', 'BVFGHF', 'bankai', 'FirstName', 'LastName', 'staff@gmail.com', '$2a$08$vkXycLeEQ7B0xhTQncUquOvTF9oWWtL0SHtJ7.o3ZkkzPCvF1no42', 'Staff', 'Activated', '000', '45 Test Baker Street, London, IG11 8RR', 'none', 'none', 1628352313, '2021-08-07 16:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `temp_vouchers`
--

CREATE TABLE `temp_vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(70) NOT NULL,
  `price` float NOT NULL,
  `company` varchar(70) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` varchar(70) NOT NULL,
  `discount` int(5) NOT NULL,
  `quantity` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL,
  `telephone` varchar(22) NOT NULL,
  `address` text NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `town` varchar(70) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `code`, `firstname`, `lastname`, `email`, `password`, `role`, `status`, `telephone`, `address`, `postcode`, `town`, `created_time`, `created_date`) VALUES
(6, 'ABCDEFGHZXCQWE900', 'Scott', 'Phoenix', 'scottphenix24@gmail.com', '$2a$08$0ao0qbaZzWdjx8I5LOxaAe5Hp5kIAz0ozFmlo6GmZVXarzUVZhIWC', 'Admin', 'Activated', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford', 1625408601, '2021-07-04 14:23:21'),
(12, 'ABCDEFGHZXCQWE871', 'FirstName', 'LastName', 'tigerphenix24@gmail.com', '$2a$08$rFxy/XXlSpBWY8beyW.nv.H0Fktcx9foKrjcpDiCnN79xpCiLvKC.', 'Kitchen', 'Activated', '000', 'none', 'none', 'none', 1626465927, '2021-07-16 20:05:27'),
(8, 'ABCDEFGHZXCQWE841', 'First', 'Last', 'joseph.mangu@webxconsult.co.uk', '$2a$08$mVr.5yAx1X8JHmDKbLE8xeeCrJJvxhWzlel5er8l4S9fwLgyEkfXG', 'User', 'Activated', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford', 1625507133, '2021-07-05 17:45:33'),
(11, 'ABCDEFGHZXCQWE933', 'Armina', 'Phata', 'admin@fastfood.com', '$2a$08$XI.bUv6zgAN9aTuv1K4ZX.O4brEpQcX3PPy.0s3KpLA.7hnDzLJaq', 'Admin', 'Activated', '07445487194', '45 Test Baker Street, Ilford, IG115RT', 'IG115RT', 'Illford', 1626296211, '2021-07-14 20:56:51'),
(13, 'ABCDEFGHZXCQWE228', 'FirstName', 'LastName', 'user@fastfood.com', '$2a$08$kv7AIQAIjmD6TeHarecsHu2P0ZjYPDgVQA1XGTfvd5Ac7fixZAJvO', 'User', 'Activated', '000', 'none', 'none', 'none', 1628381139, '2021-08-08 00:05:39'),
(14, 'ABCDEFGHZXCQWE489', 'FirstName', 'LastName', 'kitchen@fastfood.com', '$2a$08$bk/d.UIn2hGj9Kyek88H4ONqYSFFr09gMI/a/EN.3DNSBI2wZIWyi', 'Kitchen', 'Activated', '000', 'none', 'none', 'none', 1628381570, '2021-08-08 00:12:50'),
(15, 'ABCDEFGHZXCQWE713', 'FirstName', 'LastName', 'josephmmx@gmail.com', '$2a$08$mhiZpUrz6P.2NjgKIVEhmuHENsnCq/mUHxfOiulTD9pv1nJg5Y7P.', 'User', 'Deactivated', '000', 'none', 'none', 'none', 1631556061, '2021-09-13 18:01:01'),
(17, 'ABCDEFGHZXCQWE249', 'FirstName', 'LastName', 'kingphenix24@gmail.com', '$2a$08$And6sEjLT0xyRPe6HkJduuSf1Ow3CfNqdDeYYZ6imO7xW73CBBmb6', 'User', 'Activated', '000', 'none', 'none', 'none', 1631558301, '2021-09-13 18:38:21');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(70) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `discount` int(5) NOT NULL,
  `bulk` varchar(10) NOT NULL,
  `company` varchar(70) NOT NULL,
  `type` varchar(70) NOT NULL,
  `quantity` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `title`, `description`, `price`, `discount`, `bulk`, `company`, `type`, `quantity`) VALUES
(1, 'CHK1', 'ChknMel11', 'This voucher is for Chicken Meal and it used for only one item.', 5.5, 0, 'Yes', 'bankai', 'Large', 1),
(2, 'CHC1', 'ChknMel10', 'This voucher is for Chicken Meal and it used for only ten items.', 55, 0, 'Yes', 'bankai', 'Large', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_details`
--

CREATE TABLE `voucher_details` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `discount` int(5) NOT NULL,
  `company` varchar(70) NOT NULL,
  `type` varchar(70) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(5) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voucher_details`
--

INSERT INTO `voucher_details` (`id`, `code`, `email`, `title`, `description`, `discount`, `company`, `type`, `price`, `quantity`, `created_time`, `created_date`) VALUES
(8, 'CHK1', 'staff@gmail.com', 'ChknMel11', 'This voucher is for Chicken Meal and it used for only one item.', 0, 'bankai', 'Large', 5.5, 1, 1631093700, '2021-09-08 09:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `food_id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(70) NOT NULL,
  `price` int(10) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `food_id`, `email`, `title`, `category`, `price`, `created_time`, `created_date`) VALUES
(1, 12, 'scottphenix24@gmail.com', 'Fried Seabream Meal', 'Fish', 6, 1628101531, '2021-08-04 18:25:31'),
(2, 9, 'scottphenix24@gmail.com', 'German-Fries', 'Chips', 4, 1628102528, '2021-08-04 18:42:08'),
(3, 12, 'scottphenix24@gmail.com', 'Fried Seabream Meal', 'Fish', 6, 1628105049, '2021-08-04 19:24:09'),
(4, 9, 'scottphenix24@gmail.com', 'German-Fries', 'Chips', 4, 1628291717, '2021-08-06 23:15:17'),
(5, 20, 'staff@gmail.com', 'Meal-Test', 'Burger', 6, 1628360965, '2021-08-07 18:29:25'),
(6, 20, 'admin@fastfood.com', 'Meal-Test', 'Burger', 6, 1631556722, '2021-09-13 18:12:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_address`
--
ALTER TABLE `company_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_details`
--
ALTER TABLE `food_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_vouchers`
--
ALTER TABLE `meal_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_voucher_details`
--
ALTER TABLE `meal_voucher_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduler`
--
ALTER TABLE `scheduler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `side_drink`
--
ALTER TABLE `side_drink`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `side_meal`
--
ALTER TABLE `side_meal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `company_address`
--
ALTER TABLE `company_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `food_details`
--
ALTER TABLE `food_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meal_vouchers`
--
ALTER TABLE `meal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `meal_voucher_details`
--
ALTER TABLE `meal_voucher_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `side_drink`
--
ALTER TABLE `side_drink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `side_meal`
--
ALTER TABLE `side_meal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `voucher_details`
--
ALTER TABLE `voucher_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
