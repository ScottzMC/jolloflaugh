-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2021 at 03:01 PM
-- Server version: 10.3.31-MariaDB-log-cll-lve
-- PHP Version: 7.3.32

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
(2, 'Home', 'DISCOUNT OFFERS', 'batch_6U4A8661.jpg'),
(3, 'Home', 'Healthy Meals for a healthy appetite', 'batch_6U4A8840.jpg'),
(4, 'Home', 'Tasty Food varieties', 'batch_6U4A8678.jpg'),
(5, 'Staff', 'Get your meals', 'slider1.jpg'),
(6, 'Staff', 'Buy Now', 'banner1.jpg'),
(8, 'jollof_n_laugh', 'DISCOUNT OFFERS', 'batch_6U4A8661.jpg'),
(9, 'jollof_n_laugh', 'Healthy Meals for a healthy appetite', 'batch_6U4A8840.jpg'),
(10, 'jollof_n_laugh', 'Tasty Food varieties', 'batch_6U4A8678.jpg');

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
  `type` varchar(100) NOT NULL,
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

INSERT INTO `food` (`id`, `code`, `title`, `price`, `description`, `type`, `category`, `side_meal`, `side_drink`, `meal_voucher`, `delivery_start`, `date`, `delivery_end`, `stock`, `image1`, `image2`, `image3`, `image4`, `image5`, `sold`, `created_time`, `created_date`) VALUES
(2, 'XBYCAVUD227', 'Fried-Wings', 5, 'Fresh water Tilapia<br>', 'jollof_n_laugh', 'Side', 'Yes', 'Yes', 'No', '10:00', 'Tuesday,Thursday,Saturday', '16:30', 'Yes', 'batch_6U4A8634-2.jpg', 'batch_6U4A8599.jpg', 'batch_6U4A8599.jpg', 'batch_6U4A8599.jpg', 'batch_6U4A8599.jpg', 0, 1628276954, '2021-07-02 21:16:17'),
(3, 'DUXBYAVC762', 'Puff-Puff', 3, 'Steamed chicken slices<br>', 'jollof_n_laugh', 'Side', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'Yes', 'puff-puff-pepper.png', 'batch_6U4A8700.jpg', 'batch_6U4A8700.jpg', 'batch_6U4A8700.jpg', 'batch_6U4A8700.jpg', 0, 1628276954, '2021-07-02 21:20:16'),
(4, 'VCUDXAYB788', 'Plaintain', 3, 'Tasty Goat meat stew<br>', 'jollof_n_laugh', 'Side', 'No', 'No', 'No', '09:00', 'Monday,Wednesday,Friday', '18:00', 'Yes', 'batch_6U4A8781.jpg', 'batch_6U4A8748.jpg', 'batch_6U4A8748.jpg', 'batch_6U4A8748.jpg', 'batch_6U4A8748.jpg', 0, 1628276954, '2021-07-02 21:22:40'),
(12, 'DVEBXFGVCZXYCUA78', 'Jollof-Stew', 7, '<p></p>Assorted vegetables sandwich to your customization<br><p><br></p><p><br></p><p><br></p><p><br></p>', 'jollof_n_laugh', 'Vegan', 'Yes', 'Yes', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'Yes', 'batch_6U4A8785.jpg', 'batch_6U4A8874.jpg', 'batch_6U4A8874.jpg', 'batch_6U4A8874.jpg', 'batch_6U4A8874.jpg', 0, 1627757184, '2021-07-31 18:46:24'),
(13, 'DACVFZEBXYCVUXG822', 'Black-Eye-Beans-Stew', 8, 'Freshly grilled fish slices with vegetables<br><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p>', 'jollof_n_laugh', 'Vegan', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '17:00', 'Yes', 'batch_6U4A8868.jpg', 'batch_6U4A8553-2.jpg', 'batch_6U4A8553-2.jpg', 'batch_6U4A8553-2.jpg', 'batch_6U4A8553-2.jpg', 0, 1628202028, '2021-08-05 22:20:28'),
(14, 'AVXDEZVBCFGUCYX956', 'Okra-Stew', 8, '<p>Assorted fruit vegetables<br></p>', 'jollof_n_laugh', 'Vegan', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'Yes', 'batch_6U4A8739.jpg', 'batch_6U4A8535-2.jpg', 'batch_6U4A8535-2.jpg', 'batch_6U4A8535-2.jpg', 'batch_6U4A8535-2.jpg', 0, 1628276954, '2021-08-06 19:09:14'),
(34, 'KSOKFIE99', 'Bananna-Cake', 3, '', 'jollof_n_laugh', 'Dessert', '', '', '', '', '', '', '', 'banana-cake-7.jpg', '', '', '', '', 0, 1638202613, '2021-11-29 16:16:53'),
(24, 'DZCEVCGVBYUXFXA198', 'White-Rice', 4, '<p>none</p>', 'jollof_n_laugh', 'Rice', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'No', 'batch_6U4A8724(1).jpg', '', '', '', '', 0, 1638094085, '2021-11-28 10:08:05'),
(20, 'FGACVECVUXGE522', 'Malangwa-Fish-and-Kwanga', 15, '<p><b>White boiled rice with fresh vegetables.&nbsp; </b><br></p>', 'jollof_n_laugh', 'Stew', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '18:00', 'No', 'batch_6U4A8599.jpg', 'batch_6U4A8513.jpg', 'batch_6U4A8517.jpg', 'batch_6U4A8513.jpg', 'batch_6U4A8517.jpg', 0, 1628276954, '2021-08-07 10:13:21'),
(32, 'SKMFK090F', 'Salad', 4, '', 'jollof_n_laugh', 'Side', '', '', '', '', '', '', '', 'batch_6U4A8675.jpg', '', '', '', '', 0, 1638202132, '2021-11-29 16:08:52'),
(30, 'KOKOPEE980', 'Grilled-Quarter-Chicken', 8, '', 'jollof_n_laugh', 'Stew', '', '', '', '', '', '', '', 'batch_6U4A8622.jpg', '', '', '', '', 0, 1638200280, '2021-11-29 15:38:00'),
(26, 'XCYVXBCAEFDUVZG543', 'Jollof-Rice', 6, '<p>none</p>', 'jollof_n_laugh', 'Rice', 'No', 'No', 'No', '09:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '17:00', 'Yes', 'rice.jpg', '', '', '', '', 0, 1638132837, '2021-11-28 20:53:57'),
(33, 'EEMOPEMIOR', 'Olives', 3, '', 'jollof_n_laugh', 'Side', '', '', '', '', '', '', '', 'batch_6U4A8783.jpg', '', '', '', '', 0, 1638202278, '2021-11-29 16:11:18'),
(31, 'EVRMMLOALK', 'Jollof-Stew-Lamb', 9, '', 'jollof_n_laugh', 'Stew', '', '', '', '', '', '', '', 'batch_6U4A8745.jpg', '', '', '', '', 0, 1638200404, '2021-11-29 15:40:04');

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
(1, 'SCJSL', 'Meal 11', '<p>none<br></p>', 55, 0, 'Yes', 'bankai', 'Large', 'Offer-Meals', 1),
(3, 'VVO023', 'Green Meal deal', '<p>This is a meal deal voucher.</p>', 6.5, 0, 'Yes', 'fish-fastery', 'Select', 'Offer Meals', 5);

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
(1, 'Burger-Deal'),
(2, 'Pizza'),
(3, 'Chicken'),
(13, 'Stew'),
(6, 'Offer-Meals'),
(7, 'Family-Deals'),
(12, 'Rice'),
(15, 'Side'),
(14, 'Vegan'),
(16, 'Dessert');

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

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
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
(137, '-UBDFTAEHGXYVC61577', '', 'none', 'Customer1 Musana', 'Musana', '0777777777', '256', '256', 'Kampala'),
(138, 'FBH-XVCDUAEYGT19647', '', 'none', 'Customer1 Musana', 'Musana', '0777777777', '256', '256', 'Kampala'),
(120, 'YXUBAEHTDGCFV-85711', '', 'none', 'Michael', 'Scott', '07448457194', '93 Wilmington Gardens', 'RM13 8NL', 'Barking'),
(121, 'DEVUYGBFHAC-XT13964', '', 'none', 'Michael', 'Scott', '07448457194', '93 Wilmington Gardens', 'RM13 8NL', 'Barking'),
(133, 'DTY-UAFCHXBEVG83576', '', 'none', 'Mike Mikaela', 'Mikaela', '07368660611', '42 Manser Road, 42', 'RM13 8N', 'Illford'),
(131, 'VYCTFUXGHB-EDA57356', '', 'none', 'Scott ', 'Michael ', '0736866011', '93 Wilmington Gardens ', 'IG11 9tr ', 'Barking '),
(132, '-TVCFGHXEBAUDY71630', '', 'none', 'Scott Nnaghor', 'Nnaghor', '07368660611', '93 Wilmington Gardens', 'IG11 9TR', 'Barking');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `voucher_code` varchar(20) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
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
  `seat_type` varchar(100) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `voucher_code`, `firstname`, `lastname`, `email`, `title`, `price`, `quantity`, `image`, `order_notes`, `side_meal`, `side_drink`, `delivery_date`, `delivery_category`, `status`, `seat_type`, `created_time`, `created_date`) VALUES
(127, 'CHT-YFBAXVDUEG5441', '', '', '', 'tigerphenix24@gmail.com', 'Goat-stew', 3.44, 1, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Delivering', '', 1638048372, '2021-11-27 21:26:12'),
(128, 'CHT-YFBAXVDUEG5441', '', '', '', 'tigerphenix24@gmail.com', 'Beef-Stew', 5.5, 1, 'batch_6U4A8555.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638048372, '2021-11-27 21:26:12'),
(135, 'XYAVDF-EBCGUHT9369', '', '', '', 'scottmike275@gmail.com', 'Goat-leg', 6.5, 2, 'banner3.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1638095348, '2021-11-28 10:29:08'),
(137, 'VYCTFUXGHB-EDA57356', '', '', '', 'scottmike275@gmail.com', 'Jollof-stew', 10, 1, 'banner1.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1638095733, '2021-11-28 10:35:33'),
(138, 'VYCTFUXGHB-EDA57356', '', '', '', 'scottmike275@gmail.com', 'Goat-stew', 3.44, 3, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Cancelled', '', 1638095733, '2021-11-28 10:35:33'),
(139, '-TVCFGHXEBAUDY71630', '', '', '', 'scottphenix24@gmail.com', 'Fresh-Fruit-salads', 4.57, 3, 'batch_6U4A8535-2.jpg', 'none', 'none', 'none', '', '', 'Cancelled', '', 1638132570, '2021-11-28 20:49:30'),
(140, '-TVCFGHXEBAUDY71630', '', '', '', 'scottphenix24@gmail.com', 'Goat-stew', 3.44, 3, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1638132570, '2021-11-28 20:49:30'),
(141, 'DTY-UAFCHXBEVG83576', '', '', '', 'tigerphenix24@gmail.com', 'Shaki-and-Pomo', 6.5, 1, 'banner2.jpg', 'none', 'none', 'none', '', '', 'Delivering', '', 1638180059, '2021-11-29 10:00:59'),
(142, 'DTY-UAFCHXBEVG83576', '', '', '', 'tigerphenix24@gmail.com', 'Meal', 10, 1, 'banner1.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1638180059, '2021-11-29 10:00:59'),
(146, 'DCTBEHYX-VFAUG73120', '', '', '', 'webxtest4@outlook.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1638315126, '2021-11-30 23:32:06'),
(125, 'DEFHYAGTBV-XCU34708', '', '', '', 'tigerphenix24@gmail.com', 'Meal', 10, 1, 'banner1.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638047745, '2021-11-27 21:15:45'),
(156, 'UYH-TXVECBGDAF7160', '', 'Customer1', 'Musana', 'webxtest4@outlook.com', 'Jollof-Stew', 7, 1, 'batch_6U4A8785.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Premuim Table 4', 1638391304, '2021-12-01 20:41:44'),
(123, 'DXEGHTAUB-VCYF64300', '', '', '', 'tigerphenix24@gmail.com', 'Goat-stew', 3.44, 1, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638046838, '2021-11-27 21:00:38'),
(143, 'UTYFGBVEXDHA-C80257', '', '', '', 'webxtest4@outlook.com', 'Grilled-Quarter-Chicken', 8, 1, 'batch_6U4A8622.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638275885, '2021-11-30 12:38:05'),
(121, 'BY-AFVGXTECUDH6926', '', '', '', 'tigerphenix24@gmail.com', 'Goat-stew', 3.44, 1, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638046186, '2021-11-27 20:49:46'),
(148, 'XADCGHB-UFETVY69188', '', '', '', 'webxtest4@outlook.com', 'Bananna-Cake', 3, 1, 'banana-cake-7.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638367782, '2021-12-01 14:09:42'),
(110, 'BEHYFUGX-VCTDA76541', '', '', '', 'webxtest4@outlook.com', 'Fresh-Fruit-salads', 4.57, 1, 'batch_6U4A8535-2.jpg', 'none', 'none', 'none', '', '', 'Delivered', '', 1637949561, '2021-11-26 17:59:21'),
(133, 'U-CEBGXVAHTYDF28843', '', '', '', 'scottphenix24@gmail.com', 'Goat-leg', 6.5, 2, 'banner3.jpg', 'none', 'none', 'none', '', '', 'Cancelled', '', 1638094577, '2021-11-28 10:16:17'),
(112, 'GDVHCTUXBE-YAF68228', '', '', '', 'don@tgelokdon.com', 'Fresh-Fruit-salads', 4.57, 1, 'batch_6U4A8535-2.jpg', 'none', 'none', 'none', '', '', 'Cancelled', '', 1638002516, '2021-11-27 08:41:56'),
(155, 'UYH-TXVECBGDAF7160', '', 'Customer1', 'Musana', 'webxtest4@outlook.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Premuim Table 4', 1638391304, '2021-12-01 20:41:44'),
(114, 'YEV-XGFHBATDCU20087', '', '', '', 'makalokoita66@gmail.com', 'Goat-stew', 3.44, 1, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638023940, '2021-11-27 14:39:00'),
(157, 'UYH-TXVECBGDAF7160', '', 'Customer1', 'Musana', 'webxtest4@outlook.com', 'Grilled-Quarter-Chicken', 8, 1, 'batch_6U4A8622.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Premuim Table 4', 1638391304, '2021-12-01 20:41:44'),
(124, 'DEFHYAGTBV-XCU34708', '', '', '', 'tigerphenix24@gmail.com', 'Goat-stew', 3.44, 1, 'batch_6U4A8748.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638047745, '2021-11-27 21:15:45'),
(149, '-UBDFTAEHGXYVC61577', '', '', '', 'webxtest4@outlook.com', 'Black-Eye-Beans-Stew', 8, 1, 'batch_6U4A8868.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638368348, '2021-12-01 14:19:08'),
(150, '-UBDFTAEHGXYVC61577', '', '', '', 'webxtest4@outlook.com', 'Jollof-Rice', 6, 2, 'rice.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638368348, '2021-12-01 14:19:08'),
(151, 'FBH-XVCDUAEYGT19647', '', '', '', 'webxtest4@outlook.com', 'Grilled-Quarter-Chicken', 8, 1, 'batch_6U4A8622.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638368420, '2021-12-01 14:20:20'),
(152, 'FBH-XVCDUAEYGT19647', '', '', '', 'webxtest4@outlook.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', '', 1638368420, '2021-12-01 14:20:20'),
(153, 'XBHDVUEYAGF-CT4029', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Grilled-Quarter-Chicken', 8, 1, 'batch_6U4A8622.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Executive Table 1', 1638369325, '2021-12-01 14:35:25'),
(154, 'XBHDVUEYAGF-CT4029', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Executive Table 1', 1638369325, '2021-12-01 14:35:25'),
(158, '-GATCFEUVXDBYH72910', '', 'Customer 2', 'Customer2', 'webxtest3@outlook.com', 'Grilled-Quarter-Chicken', 8, 2, 'batch_6U4A8622.jpg', 'Special', 'none', 'none', '', '', 'Delivering', 'VIP', 1638452765, '2021-12-02 13:46:05'),
(159, 'CADVTEBUG-YFHX88248', '', 'Test', 'Customer', 'muszoton@outlook.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'Fast delivery ', 'none', 'none', '', '', 'Pending', 'VIP', 1638453947, '2021-12-02 14:05:47'),
(160, 'CADVTEBUG-YFHX88248', '', 'Test', 'Customer', 'muszoton@outlook.com', 'Olives', 3, 1, 'batch_6U4A8783.jpg', 'Fast delivery ', 'none', 'none', '', '', 'Pending', 'VIP', 1638453947, '2021-12-02 14:05:47'),
(161, 'GFEUAH-VBTDCXY60030', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459652, '2021-12-02 15:40:52'),
(162, 'GFEUAH-VBTDCXY60030', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Jollof-Stew', 7, 1, 'batch_6U4A8785.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459652, '2021-12-02 15:40:52'),
(163, 'GFEUAH-VBTDCXY60030', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Jollof-Rice', 6, 1, 'rice.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459652, '2021-12-02 15:40:52'),
(164, 'GFEUAH-VBTDCXY60030', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Bananna-Cake', 3, 1, 'banana-cake-7.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459652, '2021-12-02 15:40:52'),
(165, 'TCEVHDYUXGFAB-28084', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459875, '2021-12-02 15:44:35'),
(166, 'TCEVHDYUXGFAB-28084', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Jollof-Stew', 7, 1, 'batch_6U4A8785.jpg', 'none', 'none', 'none', '', '', 'Pending', 'VIP', 1638459875, '2021-12-02 15:44:35'),
(167, 'UG-HCAVXTEFBYD42918', '', 'Scott', 'Nnaghor', 'scottphenix24@gmail.com', 'Black-Eye-Beans-Stew', 8, 2, 'batch_6U4A8868.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Executive Table 2', 1638460253, '2021-12-02 15:50:53'),
(168, 'DYFVTCAEXHB-GU30568', '', 'Scott', 'Nnaghor', 'kingphenix24@gmail.com', 'Okra-Stew', 8, 1, 'batch_6U4A8739.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Executive Table 3', 1638461612, '2021-12-02 16:13:32'),
(169, 'DYFVTCAEXHB-GU30568', '', 'Scott', 'Nnaghor', 'kingphenix24@gmail.com', 'Black-Eye-Beans-Stew', 8, 1, 'batch_6U4A8868.jpg', 'none', 'none', 'none', '', '', 'Pending', 'Executive Table 3', 1638461612, '2021-12-02 16:13:32');

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
(1, 'scottphenix24@gmail.com', '', '', '', ''),
(2, 'tigerphenix24@gmail.com', '01st Sep 2021', 'Wednesday', '09:00', 'IG119TR'),
(3, 'joseph.mangu@webxconsult.co.uk', '', '', '', ''),
(4, 'admin@fastfood.com', '13th Sep 2021', 'Monday', '', ''),
(12, 'kitchen@fastfood.com', '', '', '', ''),
(11, 'user@fastfood.com', '', '', '', ''),
(9, 'staff@gmail.com', '08th Sep 2021', 'Wednesday', '11:00', 'IG119TR'),
(13, 'josephmmx@gmail.com', '', '', '', ''),
(14, 'joseph.mangu@webxconsult.co.uk', '', '', '', ''),
(20, 'kingphenix24@gmail.com', '17th Sep 2021', 'Friday', '09:00', 'IG119TR'),
(16, 'simmyphenix24@gmail.com', '14th Sep 2021', 'Tuesday', '09:00', 'IG119TR'),
(17, 'celoowerbs@gmail.com', '', '', '', ''),
(18, 'celoowerbs@gmail.com', '', '', '', ''),
(19, 'seluwabaire25@outlook.com', '22nd Sep 2021', 'Wednesday', '14:00', 'M60'),
(21, 'scottmike275@gmail.com', '', '', '', ''),
(22, 'nnaghorc@roehampton.ac.uk', '', '', '', ''),
(23, 'muszoton@gmail.com', '16th Sep 2021', 'Thursday', '09:00', '256'),
(24, 'muszoton@outlook.com', '', '', '', ''),
(25, 'muszoton@outlook.com', '', '', '', ''),
(26, 'nnaghorc@roehampton.ac.uk', '', '', '', ''),
(27, 'nnaghorc@roehampton.ac.uk', '', '', '', ''),
(28, 'seluwabaire25@outlook.com', '22nd Sep 2021', 'Wednesday', '14:00', 'M60'),
(29, 'celoowerbs@gmail.com', '', '', '', ''),
(30, 'muszoton@outlook.com', '', '', '', ''),
(31, 'muszoton1@outlook.com', '17th Sep 2021', 'Friday', '11:30', ''),
(32, 'portlandAJ03@gmail.com', '17th Sep 2021', 'Friday', '13:00', 'M60'),
(33, 'muszoton2@outlook.com', '17th Sep 2021', 'Friday', '12:30', ''),
(34, 'mariambaker90@outlook.com', '19th Sep 2021', 'Sunday', '14:30', 'M60'),
(35, 'salmaporter55@gmail.com', '18th Sep 2021', 'Saturday', '12:00', 'M60'),
(36, 'muszoton3@gmail.com', '17th Sep 2021', 'Friday', '', '222'),
(37, 'salmaporter55@gmail.com', '', '', '', '');

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
(12, 'batch_6U4A8562-2.jpg', 'Fresh Organic Meals', 'Delivered to your convenient location', 'jollof_n_laugh'),
(3, 'slider3.jpg', 'Amazing Meals', 'Get the best food delivered to your homes', 'Staff'),
(4, 'batch_6U4A8562-2.jpg', 'Fresh Organic Meals', 'Delivered to your convenient location', 'Home'),
(11, 'batch_6U4A8623.jpg', 'Chicken Delicacies', 'Order for your craving', 'Home'),
(10, 'batch_6U4A8585.jpg', 'French Fries', 'Crunchy and Tasty', 'Home'),
(13, 'batch_6U4A8623.jpg', 'Chicken Delicacies', 'Order for your craving', 'jollof_n_laugh'),
(14, 'batch_6U4A8585.jpg', 'French Fries', 'Crunchy and Tasty', 'jollof_n_laugh');

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
(17, 'DEFGHZXC340', '', '', 'FirstName', 'LastName', 'muszoton@outlook.com', '$2a$08$3IYWAqU2FbZkLnSer8VWheVDPVfxPZq1qgjEYPLPtaSKjt9IwzVUW', 'Staff', 'Activated', '000', '', 'none', 'none', 1631712275, '2021-09-15 13:24:35'),
(18, 'DEFGHZXC287', '', '', 'FirstName', 'LastName', 'muszoton@outlook.com', '$2a$08$87c/Snbw/98bPLNBPI7QfugDvALA27EKC73U.EFdZYDozvUVzS.X6', 'Staff', 'Activated', '000', '', 'none', 'none', 1631712304, '2021-09-15 13:25:04'),
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
(15, 'ABCDEFGHZXCQWE713', 'FirstName', 'LastName', 'josephmmx@gmail.com', '$2a$08$mhiZpUrz6P.2NjgKIVEhmuHENsnCq/mUHxfOiulTD9pv1nJg5Y7P.', 'User', 'Activated', '000', 'none', 'none', 'none', 1631556061, '2021-09-13 18:01:01'),
(18, 'ABCDEFGHZXCQWE403', 'FirstName', 'LastName', 'celoowerbs@gmail.com', '$2a$08$sqG6iXsm62aWSWH6KGwT6eVhmRnE8PcttdWtwLrBYJoMMi/j9cFlS', 'User', 'Activated', '000', 'none', 'none', 'none', 1631702423, '2021-09-15 10:40:23'),
(21, 'ABCDEFGHZXCQWE926', 'FirstName', 'LastName', 'kingphenix24@gmail.com', '$2a$08$nJZcWE7iiwCgpfe0jJ/2w.muDvt.kd9IsEvkiFoC.3KQaiKTy8HTy', 'User', 'Activated', '000', 'none', 'none', 'none', 1631704902, '2021-09-15 11:21:42'),
(19, 'ABCDEFGHZXCQWE176', 'FirstName', 'LastName', 'celoowerbs@gmail.com', '$2a$08$9x8BOHAIWLkgh6iB6eMKd.VFFoZXTGE/hQCgpX2WYGp2zRAvLkh1G', 'User', 'Activated', '000', 'none', 'none', 'none', 1631702487, '2021-09-15 10:41:27'),
(20, 'ABCDEFGHZXCQWE692', 'FirstName', 'LastName', 'seluwabaire25@outlook.com', '$2a$08$nzbPqGwHfsR61woKqNjdcuW6IoUEF2L8JWX.FKpKpkqp0t5WMxkti', 'User', 'Activated', '000', 'none', 'none', 'none', 1631703107, '2021-09-15 10:51:47'),
(22, 'ABCDEFGHZXCQWE910', 'FirstName', 'LastName', 'scottmike275@gmail.com', '$2a$08$RIREDcbbCS0KMxK1LXp6n.PFOz.Nlq1lcKhxozJ2BXgeQU0Z.nW/K', 'User', 'Activated', '000', 'none', 'none', 'none', 1631704963, '2021-09-15 11:22:43'),
(23, 'ABCDEFGHZXCQWE840', 'FirstName', 'LastName', 'nnaghorc@roehampton.ac.uk', '$2a$08$C2DvrphOS99e8yggnd9htOKI9nPd7S/fGxhFa9Ya9uDnt5NdrBCVW', 'User', 'Activated', '000', 'none', 'none', 'none', 1631705045, '2021-09-15 11:24:05'),
(24, 'ABCDEFGHZXCQWE508', 'FirstName', 'LastName', 'muszoton@gmail.com', '$2a$08$0MkFDEpeYMdZv/eNI/p.HeW87/Bd5VBPGxXIbqzMEIQAP.gsEdrSC', 'User', 'Activated', '000', 'none', 'none', 'none', 1631707501, '2021-09-15 12:05:01'),
(25, 'ABCDEFGHZXCQWE401', 'FirstName', 'LastName', 'seluwabaire25@outlook.com', '$2a$08$qByd7VsX/SN6jUIlcHHGbudodHITfKd4iJKAOOvf1MPPxiD.ns5hy', 'User', 'Activated', '000', 'none', 'none', 'none', 1631772349, '2021-09-16 06:05:49'),
(26, 'ABCDEFGHZXCQWE777', 'FirstName', 'LastName', 'celoowerbs@gmail.com', '$2a$08$TFanBhuwWmDDriG6DAz3tOt6JKX6lvrqJ3bGlWWS/houAg1lYn69C', 'User', 'Activated', '000', 'none', 'none', 'none', 1631772782, '2021-09-16 06:13:02'),
(27, 'ABCDEFGHZXCQWE436', 'FirstName', 'LastName', 'muszoton@outlook.com', '$2a$08$h17JJF/v03mIQl2BreXPqOenE0eilOqT7Ks.GLYsBFmJwFR3r5Gcy', 'User', 'Activated', '000', 'none', 'none', 'none', 1631778600, '2021-09-16 07:50:00'),
(28, 'ABCDEFGHZXCQWE531', 'FirstName', 'LastName', 'muszoton1@outlook.com', '$2a$08$ZRFVrnNneSrMyjBjIJup8OuzdIGOOV0LpG0kikxBAYa/1iBnrEkNq', 'User', 'Activated', '000', 'none', 'none', 'none', 1631780199, '2021-09-16 08:16:39'),
(29, 'ABCDEFGHZXCQWE835', 'FirstName', 'LastName', 'portlandAJ03@gmail.com', '$2a$08$8ato5FvzKVGFijm3OfSicu4b/WdNxS0ghYzssg979dtG3Miiee5N6', 'User', 'Activated', '000', 'none', 'none', 'none', 1631780449, '2021-09-16 08:20:49'),
(30, 'ABCDEFGHZXCQWE931', 'FirstName', 'LastName', 'muszoton2@outlook.com', '$2a$08$yo8BmAwoTHROI5YFr3B8meHnnF/ne1wnQ7bPicWVy3y4ZFEiD6S4K', 'User', 'Activated', '000', 'none', 'none', 'none', 1631780794, '2021-09-16 08:26:34'),
(31, 'ABCDEFGHZXCQWE572', 'FirstName', 'LastName', 'mariambaker90@outlook.com', '$2a$08$sf2MR7ynDBKyD0LcvwK69ujnWQUu4IYgAtTZ5IMHNfcbpvCiHlyxi', 'User', 'Activated', '000', 'none', 'none', 'none', 1631781650, '2021-09-16 08:40:50'),
(32, 'ABCDEFGHZXCQWE918', 'FirstName', 'LastName', 'salmaporter55@gmail.com', '$2a$08$r5Lihb2FPnaynscsU6mroeAebVs0x9uWvGmoPxR9C4OO0Dw04aTci', 'User', 'Activated', '000', 'none', 'none', 'none', 1631783912, '2021-09-16 09:18:32'),
(33, 'ABCDEFGHZXCQWE988', 'FirstName', 'LastName', 'muszoton3@gmail.com', '$2a$08$oVkulv1zepS0pepRJfU8SOSCLP4OQlPt8.0fzoWRnXBvkGNIThZUm', 'User', 'Activated', '000', 'none', 'none', 'none', 1631899405, '2021-09-17 17:23:25'),
(34, 'ABCDEFGHZXCQWE227', 'FirstName', 'LastName', 'webxtest1@outlook.com', '$2a$08$5uWsZzF6ccU/0pzBkm5DRez75BnFJ0V02bjlDUfmUsv7uOCjOaop6', 'User', 'Activated', '000', 'none', 'none', 'none', 1637946413, '2021-11-26 17:06:53'),
(35, 'ABCDEFGHZXCQWE167', 'FirstName', 'LastName', 'webxtest2@outlook.com', '$2a$08$AMXs630lx5BYwXuzvln4LOXX368clZHGa2.pjwkbAB7J9et/P0J/K', 'User', 'Activated', '000', 'none', 'none', 'none', 1637947227, '2021-11-26 17:20:27'),
(36, 'ABCDEFGHZXCQWE478', 'FirstName', 'LastName', 'webxtest3@outlook.com', '$2a$08$ccrhpWpTOltsAHPL2YB/3OEH7ufg4J105MS4Ik495RsycJU67lI1q', 'User', 'Activated', '000', 'none', 'none', 'none', 1637947629, '2021-11-26 17:27:09'),
(37, 'ABCDEFGHZXCQWE714', 'FirstName', 'LastName', 'webxtest4@outlook.com', '$2a$08$4118fDPw/UKXxEAw0V1r9.GuKU3dFIFKoj1uyEdzO24X8JhirPkI.', 'User', 'Activated', '000', 'none', 'none', 'none', 1637948182, '2021-11-26 17:36:22'),
(38, 'ABCDEFGHZXCQWE99', 'FirstName', 'LastName', 'webxtest4@outlook.com', '$2a$08$.6nHtSKWh2SR3RrF8cjtOe5ZuQ40d0gSg8dvoHHqZ4OqLOo96Bj96', 'User', 'Activated', '000', 'none', 'none', 'none', 1637948447, '2021-11-26 17:40:47'),
(39, 'ABCDEFGHZXCQWE351', 'FirstName', 'LastName', 'salmaporter55@gmail.com', '$2a$08$Qz9.B2ifeGvhhNjwdNgEtuBPwMsA3/Rlmz0okWx6qzXtoYiZLNwZu', 'User', 'Activated', '000', 'none', 'none', 'none', 1638275751, '2021-11-30 12:35:51'),
(40, 'ABCDEFGHZXCQWE192', 'FirstName', 'LastName', 'muszoton@gmail.com', '$2a$08$rRdGxBUl9tkBAr2NyBCdWONaaERH8dsI78vwuJ5ee76wUrUTlNG4i', 'User', 'Activated', '000', 'none', 'none', 'none', 1638464285, '2021-12-02 16:58:05');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `food_details`
--
ALTER TABLE `food_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meal_vouchers`
--
ALTER TABLE `meal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meal_voucher_details`
--
ALTER TABLE `meal_voucher_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
