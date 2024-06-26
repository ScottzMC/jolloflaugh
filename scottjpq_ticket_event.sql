-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 05, 2021 at 12:43 PM
-- Server version: 10.3.31-MariaDB-log-cll-lve
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scottjpq_ticket_event`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_book`
--

CREATE TABLE `address_book` (
  `address_book_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `entry_gender` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_company` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_street_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_suburb` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_postcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `entry_state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_country_id` int(11) NOT NULL DEFAULT 0,
  `entry_zone_id` int(11) NOT NULL DEFAULT 0,
  `entry_customer_email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address_book`
--

INSERT INTO `address_book` (`address_book_id`, `customers_id`, `entry_gender`, `entry_company`, `entry_firstname`, `entry_lastname`, `entry_street_address`, `entry_suburb`, `entry_postcode`, `entry_city`, `entry_state`, `entry_country_id`, `entry_zone_id`, `entry_customer_email`) VALUES
(1, 1, 'm', '', 'Gordon', 'Adams', '123 High Street', 'Ealing', 'EW1 568', 'Greater London', 'London', 222, 4076, ''),
(2, 2, '', NULL, 'box', 'office', 'box office', '', 'boxoffice', 'Box Office', '', 999, 9999, ''),
(3, 3, NULL, NULL, 'Joe', 'Blow', '', NULL, '', '', '', 222, 4017, 'webmaster@cybergord.com'),
(4, 4, NULL, NULL, 'joe', 'joe', '', NULL, '', '', '', 222, 4017, 'don@thelokdon.com'),
(5, 5, NULL, NULL, 'Mike', 'Mikaela', '', NULL, '', '', '', 222, 4017, 'scottphenix24@gmail.com'),
(6, 6, NULL, NULL, 'Don', 'Don', '', NULL, '', '', '', 222, 4017, 'don@thelokdon.com');

-- --------------------------------------------------------

--
-- Table structure for table `address_format`
--

CREATE TABLE `address_format` (
  `address_format_id` int(11) NOT NULL,
  `address_format` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address_summary` varchar(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address_format`
--

INSERT INTO `address_format` (`address_format_id`, `address_format`, `address_summary`) VALUES
(1, '$firstname $lastname$cr$streets$cr$city$cr$postcode$cr$statecomma$country$cr$customer_email', '$city $postcode $state/$country'),
(2, '$firstname $lastname$cr$streets$cr$city$cr$postcode$cr$statecomma$country$cr$customer_email', '$city $postcode $state/$country'),
(3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country$cr$customer_email', '$state / $country'),
(4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country$cr$customer_email', '$postcode / $country'),
(5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country$cr$customer_email', '$postcode $city / $country');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_groups_id` int(11) DEFAULT 0,
  `admin_firstname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_lastname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_password` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_created` datetime DEFAULT NULL,
  `admin_modified` datetime DEFAULT NULL,
  `admin_logdate` datetime DEFAULT NULL,
  `admin_lognum` int(11) NOT NULL DEFAULT 0,
  `admin_hide_backend` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'N',
  `encryption_style` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'O'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_groups_id`, `admin_firstname`, `admin_lastname`, `admin_email_address`, `admin_password`, `admin_created`, `admin_modified`, `admin_logdate`, `admin_lognum`, `admin_hide_backend`, `encryption_style`) VALUES
(1, 1, 'admin', 'admin', 'webmaster@osconcert.com', '4de065cdc660c1fbc50edf24c81f1d82:84', '2020-01-11 00:00:00', '2020-01-11 00:00:00', '2021-11-05 05:21:29', 39, 'N', 'O');

-- --------------------------------------------------------

--
-- Table structure for table `admin_files`
--

CREATE TABLE `admin_files` (
  `admin_files_id` int(11) NOT NULL,
  `admin_files_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `admin_files_is_boxes` tinyint(5) NOT NULL DEFAULT 0,
  `admin_files_to_boxes` int(11) NOT NULL DEFAULT 0,
  `admin_groups_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `admin_files_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  `admin_files_help_id` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  `admin_new_group_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_files`
--

INSERT INTO `admin_files` (`admin_files_id`, `admin_files_name`, `admin_files_is_boxes`, `admin_files_to_boxes`, `admin_groups_id`, `admin_files_type`, `admin_files_help_id`, `admin_new_group_id`) VALUES
(10, 'shop_admin_members.php', 1, 1, '1,3', 'D', 'D', NULL),
(12, 'configuration.php', 1, 2, '1', 'D', 'D', 31),
(15, 'products_manufacturers.php', 0, 3, '1', 'D', 'D', 16),
(17, 'products_specials.php', 0, 3, '1,3', 'D', 'D', 16),
(19, 'modules.php', 0, 4, '1', 'D', 'D', 30),
(21, 'customers_orders.php', 1, 5, '1,3', 'D', 'D', 12),
(22, 'shop_countries.php', 1, 6, '1', 'D', 'D', NULL),
(23, 'shop_zones.php', 1, 6, '1', 'D', 'D', 19),
(24, 'shop_geo_zones.php', 1, 6, '1', 'D', 'D', 19),
(25, 'payment_tax_classes.php', 1, 6, '1', 'D', 'D', 30),
(26, 'payment_tax_rates.php', 1, 6, '1', 'D', 'D', 30),
(27, 'payment_currencies.php', 1, 7, '1', 'D', 'D', 30),
(29, 'shop_orders_status.php', 1, 7, '1', 'D', 'D', 28),
(30, 'reports_products_viewed.php', 0, 8, '1', 'D', 'D', 20),
(31, 'stats_products_purchased.php', 0, 8, '1,3', 'D', 'D', 21),
(32, 'stats_customers.php', 0, 8, '1,3', 'D', 'D', 21),
(33, 'shop_backup.php', 1, 9, '1', 'D', 'D', 1),
(36, 'shop_define_language.php', 1, 9, '1', 'D', 'D', 13),
(37, 'shop_file_manager.php', 1, 9, '1', 'D', 'D', 3),
(39, 'marketing_newsletters.php', 0, 9, '1,3', 'D', 'D', 10),
(40, 'server_info.php', 1, 9, '1', 'D', 'D', 3),
(41, 'shop_whos_online.php', 1, 9, '1', 'D', 'D', 3),
(43, 'shop_languages.php', 1, 7, '1', 'D', 'D', 13),
(65, 'cms_mainpage.php', 0, 5, '1', 'D', 'D', 2),
(66, 'customers_edit_orders.php', 1, 5, '1', 'D', 'D', 6),
(67, 'shop_infobox_configuration.php', 1, 173, '1', 'D', 'D', 28),
(69, 'sales_maker.php', 0, 3, '1', 'D', 'D', 28),
(70, 'myaccount_update_account.php', 0, 1, '1', 'D', 'D', 28),
(71, 'sales_coupon_listcategories.php', 1, 3, '1', 'D', 'D', 25),
(82, 'products_featured.php', 0, 3, '1', 'D', 'D', NULL),
(83, 'products_salemaker_info.php', 1, 3, '1', 'D', 'D', 17),
(84, 'customers_create_account_success.php', 1, 5, '1', 'D', 'D', 5),
(85, 'customers_create_account_process.php', 1, 5, '1', 'D', 'D', 5),
(103, 'marketing_events_messages.php', 1, 92, '1', 'E', 'M', NULL),
(143, 'marketing_email_messages.php', 0, 1, '1', 'D', 'D', 10),
(194, 'events_preview_tickets.php', 1, 92, '1,4', 'D', 'D', NULL),
(198, 'events_ticket.php', 0, 5, '1,4', 'D', 'D', 9),
(200, 'payment_general_templates.php', 1, 92, '1', 'D', 'D', 9),
(201, 'customers_groups.php', 0, 5, '1', 'D', 'D', 5),
(204, 'customers_payment_wallet.php', 0, 5, '1', 'D', 'D', 8),
(205, 'customers_wallet_confirmation.php', 1, 5, '1', 'D', 'D', 8),
(206, 'customers_wallet_process.php', 1, 5, '1', 'D', 'D', 8),
(207, 'customers_wallet_success.php', 1, 5, '1', 'D', 'D', 8),
(208, 'marketing_survey_referrals.php', 1, 5, '1', 'D', 'D', 15),
(209, 'reports_sales_referral_sources.php', 0, 8, '1,3', 'D', 'D', 21),
(218, 'marketing_survey_customer_options.php', 1, 5, '1', 'D', 'D', 15),
(221, 'marketing_seo.php', 0, 2, '1', 'D', 'D', 10),
(231, 'marketing_products_message.php', 0, 3, '1,3', 'D', 'D', 10),
(1052, 'shop_compat_test.php', 1, 9, '1', 'D', 'D', 3),
(1056, 'cms_level_pages_update.php', 1, 0, '1', 'D', 'D', 2),
(1057, 'cms_level_pages.php', 1, 0, '1', 'D', 'D', 2),
(1058, 'customers_order_addproducts.php', 1, 5, '1', 'D', 'D', 7),
(1060, 'reports_sales_direct_deposit.php', 1, 8, '1', 'D', 'D', 21),
(1066, 'paypalipn_txn.php', 0, 59, '1', 'N', 'N', NULL),
(1067, 'paypalipn_tests.php', 0, 59, '1', 'N', 'N', NULL),
(1093, 'quick_links.php', 0, 2, '1', 'D', 'D', 28),
(1094, 'customers_mainpage.php', 1, 0, '1,3', 'D', 'D', 11),
(1096, 'products_mainpage.php', 1, 3, '1', 'D', 'D', 16),
(1103, 'cms_homepage.php', 0, 1, '1', 'D', 'D', 2),
(1104, 'marketing_wallet.php', 0, 1, '1,3', 'D', 'D', 12),
(1110, 'shop_template_configuration.php', 1, 1, '1', 'D', 'D', 18),
(1119, 'reports_wallet.php', 0, 0, '1,3', 'D', 'D', 12),
(1120, 'sales_coupon.php', 1, 1, '1', 'D', 'D', 25),
(1121, 'sales_giftvoucher_mail.php', 1, 1, '1', 'D', 'D', 26),
(1122, 'sales_giftvoucher_queue.php', 1, 1, '1', 'D', 'D', 26),
(1123, 'sales_giftvoucher_sent.php', 1, 1, '1', 'D', 'D', 26),
(1129, 'sales_coupon_listproducts.php', 1, 3, '1', 'D', 'D', 25),
(1132, 'sales_coupon_listsubcategories.php', 1, 3, '1', 'D', 'D', 25),
(1136, 'products_salemaker.php', 1, 3, '1', 'D', 'D', 17),
(1140, 'products', 0, 0, '1', 'D', 'D', NULL),
(1142, 'cms', 0, 0, '1', 'D', 'D', NULL),
(1143, 'marketing', 0, 0, '1,3', 'D', 'D', NULL),
(1144, 'sales', 0, 0, '1', 'D', 'D', NULL),
(1145, 'payment', 1, 0, '1', 'D', 'D', NULL),
(1146, 'reports', 1, 0, '1', 'D', 'D', NULL),
(1147, 'shop', 1, 0, '1', 'D', 'D', NULL),
(1148, 'myaccount', 0, 0, '1', 'D', 'D', NULL),
(1151, 'customers_packingslip.php', 1, 0, '1,3', 'D', 'D', 12),
(1152, 'reports_mainpage.php', 1, 8, '1,3', 'D', 'D', 21),
(1154, 'shop_admin_groups_file_permission.php', 1, 0, '1', 'D', 'D', 22),
(1158, 'customers_invoice.php', 1, 1, '1,3', 'D', 'D', 12),
(1160, 'reports_refund_products.php', 0, 8, '1,3', 'D', 'D', 21),
(1163, 'reports_shipping.php', 0, 8, '1,3', 'D', 'D', 21),
(1164, 'reports_discount_coupon.php', 0, 0, '1,3', 'D', 'D', 21),
(1165, 'sales_discount_coupons.php', 1, 1, '1', 'D', 'D', 25),
(1171, 'product_manage.php', 0, 0, '1', 'D', 'D', NULL),
(1176, 'shop_sale_items.php', 0, 0, '1', 'D', 'D', 28),
(1182, 'customers_info_fields.php', 1, 1, '1,3', 'D', 'D', 11),
(1184, 'products_ticket.php', 0, 5, '1', 'D', 'D', NULL),
(1185, 'products_sales_new.php', 1, 8, '1,3', 'D', 'D', 21),
(1187, 'concert_details.php', 1, 1, '1', 'D', 'D', 14),
(1190, 'backup_categories.php', 1, 0, '1', 'D', 'D', 16),
(1192, 'concert_details_ajax.php', 0, 0, '1', 'N', 'N', 14),
(1195, 'seatplan_channels.php', 1, 0, '1', 'D', 'D', 3),
(1196, 'seatplan_channels_ajax.php', 1, 0, '1', 'D', 'D', 3),
(1197, 'edit_orders.php', 0, 0, '1,3', 'D', 'D', 12),
(1198, 'edit_orders_add_product.php', 0, 0, '1,3', 'D', 'D', 12),
(1199, 'help_manuals.php', 0, 0, '1', 'D', 'D', 28),
(1205, 'products_ticket_print.php', 0, 0, '1', 'D', 'D', NULL),
(1207, 'stats_sales_csv.php', 0, 0, '1', 'D', 'D', NULL),
(1208, 'products_sales.php', 0, 0, '1', 'D', 'D', 28),
(1209, 'barcodes_export.php', 0, 0, '1', 'D', 'D', 28),
(1210, 'update_barcodes.php', 0, 0, '1', 'D', 'D', NULL),
(1211, 'products_only_data.php', 0, 0, '1', 'D', 'D', NULL),
(1212, 'products_data.php', 0, 0, '1', 'D', 'D', NULL),
(1213, 'categories_data.php', 0, 0, '1', 'D', 'D', NULL),
(1214, 'customer_export.php', 0, 0, '1', 'D', 'D', NULL),
(1216, 'event_report.php', 0, 0, '1', 'D', 'D', 28),
(1217, 'design_data.php', 0, 0, '1', 'D', 'D', NULL),
(1219, 'orders_only_data.php', 0, 0, '1', 'D', 'D', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_files_groups`
--

CREATE TABLE `admin_files_groups` (
  `admin_files_groups_id` int(11) NOT NULL DEFAULT 0,
  `admin_files_groups_name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_files_groups_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_groups_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_files_groups`
--

INSERT INTO `admin_files_groups` (`admin_files_groups_id`, `admin_files_groups_name`, `admin_files_groups_desc`, `admin_groups_id`) VALUES
(1, 'backups', NULL, '1'),
(2, 'cms_level', NULL, '1'),
(3, 'server', NULL, '1'),
(4, 'customers_checkout', NULL, ''),
(5, 'customers_create', NULL, ''),
(6, 'customers_edit_orders', NULL, ''),
(7, 'customers_orders', NULL, '1,3'),
(8, 'customers_wallet', NULL, '1,3'),
(9, 'tickets', NULL, '1,3'),
(10, 'marketing', NULL, '1,3'),
(11, 'customers', NULL, '1,3'),
(12, 'orders', NULL, '1,3'),
(13, 'language', NULL, '1'),
(14, 'concert', NULL, '1'),
(15, 'marketing_survey', NULL, '1'),
(16, 'products', NULL, '1,3'),
(17, 'products_salemaker', NULL, '1'),
(18, 'templates', NULL, '1'),
(19, 'zones', NULL, '1'),
(20, 'reports_products', NULL, '1'),
(21, 'reports_sales', NULL, '1,3'),
(22, 'groups', NULL, '1'),
(25, 'sales_coupon', NULL, '1'),
(26, 'sales_giftvoucher', NULL, '1'),
(28, 'osConcert', NULL, '1'),
(30, 'payment', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `admin_groups`
--

CREATE TABLE `admin_groups` (
  `admin_groups_id` int(11) NOT NULL,
  `admin_groups_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_groups_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_groups`
--

INSERT INTO `admin_groups` (`admin_groups_id`, `admin_groups_name`, `admin_groups_type`) VALUES
(1, 'Top Administrator', 'A'),
(3, 'Call Centre Manager', 'E'),
(4, 'Call Centre Staff', 'E'),
(5, 'Product Manager', 'E'),
(6, 'Reservation Manager', 'E'),
(7, 'Demo User Group', 'D'),
(9, 'Blocked', 'E');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus`
--

CREATE TABLE `admin_menus` (
  `menu_id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `admin_groups_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `menu_pos` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `filename` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `params` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `menu_item_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_menus`
--

INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES
(1, 0, '1', 2, 'customers_mainpage.php', '', ''),
(2, 7, '1', 500, 'marketing_products_message.php', '', ''),
(4, 0, '1', 5, 'products_mainpage.php', '', ''),
(6, 0, '1', 7, 'cms_level_pages.php', 'home_page=true', ''),
(7, 0, '1', 8, '', '', ''),
(8, 0, '1', 1, 'customers_orders.php', '', ''),
(9, 0, '1', 9, '', '', ''),
(10, 0, '1', 11, 'reports_mainpage.php', '', ''),
(11, 0, '1', 1, 'configuration.php', 'gID=1', ''),
(12, 0, '1', 12, 'myaccount_update_account.php', '', ''),
(13, 318, '1', 13, 'customers_mainpage.php', 'command=new', ''),
(15, 1, '1', 15, 'configuration.php', '', ''),
(16, 15, '1', 16, 'customers_groups.php', '', ''),
(37, 4, '1', 1, 'products_mainpage.php', '', ''),
(39, 4, '1', 3, 'products_manufacturers.php', '', 'X'),
(40, 4, '1', 4, 'modules.php', 'set=shipping', ''),
(43, 4, '1', 8, 'products_specials.php', '', ''),
(46, 4, '1', 11, 'configuration.php', '', ''),
(47, 37, '1', 1, 'products_mainpage.php', 'create_category=new', ''),
(53, 6, '1', 1, 'cms_mainpage.php', '', ''),
(54, 6, '1', 54, 'cms_level_pages.php', 'level=1', ''),
(56, 6, '1', 56, '', '', ''),
(58, 7, '1', 58, 'marketing_email_messages.php', '', ''),
(59, 7, '1', 59, 'configuration.php', '', ''),
(60, 7, '1', 60, 'configuration.php', '', ''),
(61, 7, '1', 61, 'configuration.php', '', ''),
(63, 7, '1', 63, 'marketing_seo.php', '', ''),
(65, 7, '1', 65, 'marketing_newsletters.php', '', ''),
(69, 60, '1', 69, 'marketing_events_messages.php', '', ''),
(71, 60, '1', 71, 'marketing_products_message.php', '', ''),
(79, 62, '1', 79, 'configuration.php', '', ''),
(85, 4, '1', 85, '', '', ''),
(87, 8, '1', 87, 'sales_coupon.php', '', ''),
(89, 85, '1', 89, 'sales_maker.php', '', ''),
(91, 9, '1', 91, 'modules.php', 'set=payment&from=col&top=1&mPath=9_91', ''),
(93, 9, '1', 93, 'configuration.php', 'gID=152', ''),
(94, 9, '1', 94, 'payment_currencies.php', '', ''),
(95, 9, '1', 95, 'payment_general_templates.php', 'type=TIC', ''),
(97, 9, '1', 99, 'configuration.php', 'gID=153', ''),
(98, 95, '1', 98, 'payment_general_templates.php', 'action=create_template&type=TIC', ''),
(102, 10, '1', 102, 'reports_mainpage.php', '', ''),
(107, 10, '1', 107, '', '', ''),
(109, 102, '1', 109, 'reports_sales_referral_sources.php', '', ''),
(110, 102, '1', 110, 'reports_sales_direct_deposit.php', '', ''),
(128, 104, '1', 128, 'reports_products_viewed.php', '', ''),
(151, 107, '1', 151, 'reports_wallet.php', '', ''),
(152, 11, '1', 152, 'configuration.php', '', ''),
(153, 11, '1', 153, 'shop_template_configuration.php', '', ''),
(154, 11, '1', 154, 'shop_zones.php', '', ''),
(155, 11, '1', 155, 'configuration.php', '', ''),
(156, 11, '1', 156, 'shop_languages.php', '', ''),
(158, 11, '1', 158, 'shop_admin_members.php', '', ''),
(159, 11, '1', 159, 'configuration.php', 'gID=16', ''),
(160, 11, '1', 160, 'configuration.php', 'top=1&mPath=11_160', ''),
(165, 160, '1', 2, 'configuration.php', 'gID=2', ''),
(166, 160, '1', 3, 'configuration.php', 'gID=10', ''),
(168, 160, '1', 6, 'configuration.php', 'gID=15', ''),
(169, 160, '1', 7, 'configuration.php', 'gID=906', ''),
(170, 160, '1', 170, 'configuration.php', 'gID=112', ''),
(171, 160, '1', 10, 'shop_backup.php', '', ''),
(172, 160, '1', 11, 'shop_whos_online.php', '', ''),
(173, 160, '1', 12, 'shop_compat_test.php', '', ''),
(176, 11, '1', 176, 'account.php', '', ''),
(179, 40, '1', 1, 'configuration.php', '', ''),
(180, 4, '1', 180, 'products_featured.php', '', ''),
(182, 61, '1', 5, 'modules.php', 'set=sms', ''),
(183, 12, '1', 1, 'myaccount_update_account.php', 'action=check_account', ''),
(194, 46, '1', 1, 'configuration.php', 'gID=9', ''),
(195, 46, '1', 2, 'configuration.php', 'gID=905', ''),
(196, 46, '1', 3, 'configuration.php', 'gID=14', ''),
(197, 59, '1', 4, 'marketing_survey_referrals.php', '', ''),
(198, 59, '1', 5, 'marketing_survey_customer_options.php', 'type=O', ''),
(199, 59, '1', 6, 'marketing_survey_customer_options.php', 'type=I', ''),
(200, 7, '1', 200, 'marketing_wallet.php', '', ''),
(201, 93, '1', 1, 'shop_geo_zones.php', '', ''),
(202, 93, '1', 2, 'payment_tax_classes.php', '', ''),
(203, 93, '1', 2, 'payment_tax_rates.php', '', ''),
(204, 46, '1', 204, 'configuration.php', '', ''),
(207, 9, '1', 207, 'modules.php', 'set=ordertotal', ''),
(208, 97, '1', 208, 'configuration.php', '', ''),
(209, 160, '1', 209, 'configuration.php', '', ''),
(227, 11, '1', 227, 'shop_orders_status.php', '', ''),
(228, 11, '1', 228, 'quick_links.php', '', ''),
(231, 7, '1', 231, 'configuration.php', '', ''),
(236, 153, '1', 2, 'shop_infobox_configuration.php', '', ''),
(238, 37, '1', 325, 'products_mainpage.php', 'create_product=new', ''),
(244, 97, '1', 244, 'configuration.php', 'gID=202', ''),
(245, 158, '1', 245, 'shop_admin_groups_file_permission.php', '', ''),
(300, 3, '1', 300, 'configuration.php', 'gID=916', ''),
(305, 102, '1', 248, 'reports_refund_products.php', '', ''),
(308, 87, '1', 308, 'sales_discount_coupons.php', '', ''),
(309, 102, '1', 309, 'reports_shipping.php', '', ''),
(310, 102, '1', 310, 'reports_discount_coupon.php', '', ''),
(314, 46, '1', 8, 'product_manage.php', '', ''),
(316, 160, '1', 316, 'configuration.php', 'gID=11', ''),
(322, 160, '1', 322, 'shop_sale_items.php', '', ''),
(327, 160, '1', 323, 'configuration.php', 'gID=917', ''),
(328, 1, '1', 1, 'customers_mainpage.php', '', ''),
(329, 160, '1', 161, 'customers_info_fields.php', '', ''),
(400, 0, '1', 4, 'concert_details.php', '', ''),
(401, 8, '1', 401, 'products_sales_new.php', '', ''),
(402, 8, '1', 402, 'stats_products_purchased.php', '', ''),
(403, 4, '1', 12, 'backup_categories.php', '', ''),
(404, 0, '1', 13, 'seatplan_channels.php', '', ''),
(406, 9, '1', 92, 'configuration.php', 'gID=92', 'X'),
(407, 11, '1', 230, 'help_manuals.php', '', ''),
(408, 9, '1', 250, 'configuration.php', 'gID=919', 'P'),
(409, 8, '1', 409, 'stats_customers.php', '', ''),
(411, 160, '1', 325, 'configuration.php', 'gID=12', ''),
(412, 8, '1', 412, 'products_sales.php', '', 'P'),
(414, 160, '444', 1, 'configuration.php', 'gID=920', ''),
(415, 7, '1', 415, 'configuration.php', 'gID=921', ''),
(416, 46, '1', 9, 'configuration.php', '', 'P'),
(417, 400, '1', 400, 'configuration.php', '', 'P'),
(419, 400, '1', 2, 'configuration.php', '', 'P'),
(420, 8, '1', 410, 'stats_sales_csv.php', '', 'P'),
(421, 102, '1', 300, 'products_sales_new.php', '', 'P'),
(422, 102, '1', 299, 'stats_sales_csv.php', '', 'P'),
(423, 102, '1', 298, 'products_sales.php', '', 'P'),
(424, 8, '1', 413, 'barcodes_export.php', '', 'P'),
(425, 7, '1', 70, 'configuration.php', 'gID=927', 'P'),
(426, 1, '1', 29, 'customer_export.php', '', 'P'),
(427, 7, '1', 427, 'configuration.php', 'gID=928', 'P'),
(428, 102, '1', 11, 'products_sales_new.php', '', 'P'),
(429, 102, '1', 11, 'products_sales.php', '', 'P'),
(430, 102, '1', 11, 'stats_sales_csv.php', '', 'P'),
(431, 8, '1', 413, 'event_report.php', '', 'P'),
(432, 46, '1', 5, 'configuration.php', 'gID=929', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus_description`
--

CREATE TABLE `admin_menus_description` (
  `menu_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `menu_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_id` int(11) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_menus_description`
--

INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES
(1, 'Customers', 1),
(2, 'Email Template', 1),
(4, 'Products', 1),
(6, 'CMS', 1),
(7, 'Marketing', 1),
(8, 'Orders', 1),
(9, 'Payment', 1),
(10, 'Reports', 1),
(11, 'Shop Settings', 1),
(12, 'My Account', 1),
(13, 'Create Customer', 1),
(15, 'Advanced', 1),
(16, 'Customer Groups', 1),
(25, 'Advanced', 1),
(26, 'Create Category', 1),
(27, 'Messages', 1),
(28, 'Advanced', 1),
(32, 'Resources', 1),
(34, 'Attributes', 1),
(36, 'Create Category', 1),
(37, 'Manage Products', 1),
(38, 'Attributes', 1),
(39, 'Featured Location', 1),
(40, 'Shipping', 1),
(42, 'Reviews', 1),
(43, 'Specials', 1),
(44, 'Expected Products', 1),
(45, 'Quick Uploads', 1),
(46, 'Advanced', 1),
(47, 'Create Category', 1),
(52, 'Create Category', 1),
(53, 'Homepage', 1),
(54, 'Static Pages', 1),
(55, 'Level2', 1),
(58, 'General', 1),
(59, 'Surveys', 1),
(63, 'SEO', 1),
(65, 'Newsletters', 1),
(79, 'Advanced', 1),
(85, 'Discounts', 1),
(87, 'Vouchers/Coupons', 1),
(89, 'Products', 1),
(90, 'Products', 1),
(91, 'Payment Gateways', 1),
(93, 'Tax', 1),
(94, 'Currencies', 1),
(95, 'Tickets', 1),
(97, 'Advanced', 1),
(102, 'General', 1),
(104, 'Products', 1),
(107, 'Wallet', 1),
(108, 'Sales', 1),
(109, 'Referral Sources', 1),
(110, 'Bank Deposits', 1),
(111, 'Surveys', 1),
(119, 'Customer Details', 1),
(123, 'Mailing Labels', 1),
(126, 'Tickets', 1),
(128, 'Products Viewed', 1),
(135, 'Surveys', 1),
(139, 'Call Center Orders', 1),
(140, 'Customer Details', 1),
(142, 'Resources View', 1),
(144, 'Tickets', 1),
(150, 'Packing Slip', 1),
(151, 'Wallet Uploads', 1),
(152, 'General', 1),
(153, 'Template', 1),
(154, 'Zones', 1),
(155, 'Images', 1),
(156, 'Languages', 1),
(158, 'Admin User Groups', 1),
(159, 'Site Maintenance', 1),
(160, 'Advanced', 1),
(165, 'Values', 1),
(166, 'Logging', 1),
(168, 'Sessions', 1),
(169, 'Reports', 1),
(170, 'HTMLArea', 1),
(171, 'Backups', 1),
(172, 'Who\'s Online', 1),
(173, 'Compatability Test', 1),
(179, 'Options', 1),
(180, 'Featured', 1),
(183, 'Update Account', 1),
(184, 'Summary', 1),
(185, 'Payment', 1),
(186, 'Sales', 1),
(189, 'Contact', 1),
(194, 'Stock', 1),
(195, 'Free Checkout', 1),
(196, 'GZip Compression', 1),
(197, 'Customer Referral', 1),
(198, 'Customer Occupation', 1),
(199, 'Customer Interest', 1),
(200, 'Wallet', 1),
(201, 'Zones', 1),
(202, 'Classes', 1),
(203, 'Rates', 1),
(204, 'Listing', 1),
(207, 'Order Totals', 1),
(208, 'Wallet', 1),
(209, 'Download', 1),
(223, 'Logs', 1),
(227, 'Orders Status', 1),
(228, 'Quick Links', 1),
(229, 'Payments', 1),
(231, 'Options', 1),
(234, 'Terms', 1),
(235, 'Information', 1),
(236, 'Infoboxes', 1),
(238, 'Create Product', 1),
(244, 'geoIP', 1),
(245, 'New Permission', 1),
(300, 'Advanced', 1),
(301, 'Orders', 1),
(305, 'Refunds', 1),
(308, 'Discount Coupons', 1),
(309, 'Shipping', 1),
(310, 'Discount Coupon', 1),
(313, 'Products Export', 1),
(314, 'Product Manage', 1),
(316, 'Cache', 1),
(322, 'Delete all items', 1),
(327, 'osConcert Settings', 1),
(328, 'Manage Customers', 1),
(329, 'Customers', 1),
(400, 'Concert Details', 1),
(401, 'osConcert SALES', 1),
(402, 'PURCHASED', 1),
(403, 'Alternative Edit Categories', 1),
(404, 'Seatplan Channels', 1),
(407, 'Help Manuals', 1),
(408, 'Cancel CRON', 1),
(409, 'Best Customers', 1),
(410, 'Edit Language', 1),
(411, 'Email Settings', 1),
(412, 'product SALES', 1),
(414, 'eTicket Settings', 1),
(415, 'Mailerlite', 1),
(416, 'Category Products', 1),
(417, 'Seat Plan', 1),
(419, 'Box Office', 1),
(420, 'Sales Orders CSV', 1),
(421, 'osConcert SALES', 1),
(422, 'Sales Orders CSV', 1),
(423, 'GA Product Sales', 1),
(424, 'Orders Barcode Scanned', 1),
(425, 'PDF Invoice', 1),
(426, 'Customer Export', 1),
(427, 'Video', 1),
(428, 'osConcert SALES', 1),
(429, 'product GA SALES', 1),
(430, 'Sales CSV', 1),
(431, 'Active Event Report', 1),
(432, 'Event Expiry', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `title`, `category`, `image`) VALUES
(1, 'Ads 1', 'Music', 'sidebar-banner4.jpg'),
(2, 'Ads Home', 'Home', 'sidebar-banner4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL,
  `video` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `title`, `image`, `type`, `category`, `subcategory`, `video`) VALUES
(42, 'A DOT COMEDIAN', 'AdotComedianPhoto1-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(34, 'WHITE YARDIE', 'Jollofnlaugh_WhiteYardie2-min.jpg', 'Home', 'Performers', 'None', 'none'),
(35, 'CHRISTOPHER SAVAGE', 'JOllofNLaugh_ChristopherSavageARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(36, 'KEVIN J', 'JollofNLaugh_KevinJ_ARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(37, 'NQOBILÉ DANSEUR', 'JollofNLaugh_NQOBILÉ_ARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(52, 'THE JOLLOF N LAUGH SHOW', 'Jollfnlaugh_AuntyJollof_UncleJollof-min.jpg', 'Home', 'Join-Us', 'None', 'none'),
(38, 'THE RARA', 'JollofNLaugh_TheRara_ARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(32, 'A DOT COMEDIAN', 'JollofnLaugh_ADotComedianARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(39, 'JORDZ THE JAY', 'JollofNLaugh_JordztheJay_ARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(40, 'TBAZE', 'JollofNLaugh_TBaze_ARTIST-min.jpg', 'Home', 'Performers', 'None', 'none'),
(41, 'NQOBILE', 'JollofNLaugh_NQOBILÉ_ARTIST-min.jpg', 'Home', 'Eat-Laugh-Dance', 'Main', 'none'),
(43, 'CONGOLESE CUSINE', 'MalangwaandKwangaMeal-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(44, 'ORGANIC AFRICAN GINGER BEER', 'DK_GingerBeer-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(45, 'WHITE YARDIE', 'whiteyardie-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(46, 'SIERRA LEONEAN CUSINE', 'JollofRiceandJollofStew-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(47, 'MALANGWA', 'MalangwaandJollofrice-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(48, 'FRIED WINGS', 'FriedChickenWings-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(49, 'CHRISTOPHER SAVAGE', 'ChristopherSavage3-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(50, 'MALANGWA & JOLLOF RICE', 'MalangwaandJollofrice-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(51, 'THOMSON', 'ThomsonFish-min.jpg', 'Home', 'Eat-Laugh-Dance', 'None', 'none'),
(53, 'HOSTED BY A DOT COMEDIAN', 'AdotComedianPhoto1-min.jpg', 'Home', 'Join-Us', 'None', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) NOT NULL,
  `maps` longtext NOT NULL,
  `video` text NOT NULL,
  `req_age` varchar(50) NOT NULL,
  `req_dress_code` varchar(50) NOT NULL,
  `req_last_entry` varchar(50) NOT NULL,
  `req_id_verified` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `title`, `description`, `image`, `maps`, `video`, `req_age`, `req_dress_code`, `req_last_entry`, `req_id_verified`, `created_date`) VALUES
(1, 'Jollof n Laugh', '<div><b>EVENTSTHRONEVIP</b></div><div><br></div><div>Presents The Jollof N Laugh Show!</div><div>Recorded at Tudor Rose and in conjunction with Tudor Rose Venue, a brand new show launches in West London.</div><div><br></div><div>Join A DOT COMEDIAN for an evening of top quality Entertainment on Sunday 5th December.</div><div><br></div><div>Special Guest’s include Whyte Yardie, Christopher Savage, and Kevin J. With Live PA’s from Nqobilé, and The Rara, Driss, JordsThe J & Tbaze.</div><div><br></div><div>Take part in games, competitions, & prizes.</div><div><br></div><div>Attractions:</div><div><br></div><div>✨ Live DJs</div><div>✨ Live Comedy</div><div>✨ Live Performances</div><div>✨ Games</div><div>✨ Competitions</div><div>✨ 241 Cocktails - 4pm - 6pm</div><div>✨ Sierra Leonean & Congolese Cuisine</div><div>✨ VIP Lounge</div><div>+ Much More</div><div><br></div><div>✨ Tudor Rose, 68 The Green, UB2 4BG⠀</div><div>⏰ Doors open 4pm, Shows starts 6:00PM. After party 10pm - Midnight</div><div><br></div><div>Please note; This show will be Recorded and by purchasing a ticket you give consent to eventsthronevip to use any footage for promotions & brand building purposes.</div><div><br></div><div>Dresscode; Glamorous - No Tracksuits, No Hoodies, No Caps or Hats  </div><div>Essential: No ID, No Entry</div><div><br></div><div>Limited early bird tickets from £10</div><div>Birthday, Table and VIP booking available.</div><div>For info contact jollofnlaugh on 0744 426 5568 IG:jollofnlaugh email: bookings@jollofnlaugh.com</div>', '627ACA40-81D4-4328-B5C4-246821674C65.jpeg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.5300811201846!2d-0.38283938424874586!3d51.50349037963448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48767327e832bd7d%3A0x956a54747b40c43a!2sTudor%20Rose%20Events!5e0!3m2!1sen!2suk!4v1635975973012!5m2!1sen!2suk', 'https://www.youtube.com/embed/WDGoy7hSDLM', '18+', 'Glamorous', '10:00pm', 'Yes', '2021-10-09 16:13:22');

-- --------------------------------------------------------

--
-- Stand-in structure for view `carts_united`
-- (See below for the actual view)
--
CREATE TABLE `carts_united` (
`customers_basket_id` int(11)
,`products_id` text
,`customers_id` varchar(40)
,`customers_basket_date_added` datetime
,`customers_basket_quantity` int(11)
,`discount_id` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_image` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `categories_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `categories_products_lock` int(1) NOT NULL DEFAULT 1,
  `date_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `plan_id` int(1) NOT NULL,
  `categories_quantity` int(6) NOT NULL DEFAULT 0,
  `categories_quantity_remaining` int(6) NOT NULL DEFAULT 0,
  `categories_GA` tinyint(1) NOT NULL DEFAULT 0,
  `categories_image_2` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `color_code` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `manufacturers_id` varchar(28) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `categories_is_printable` tinyint(1) NOT NULL DEFAULT 1,
  `section_id` int(11) NOT NULL,
  `categories_shipping` tinyint(1) NOT NULL,
  `concert_date_unix` bigint(20) NOT NULL,
  `categories_image_3` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `categories_image_4` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bg_height` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_image`, `parent_id`, `sort_order`, `date_added`, `last_modified`, `categories_status`, `categories_products_lock`, `date_id`, `plan_id`, `categories_quantity`, `categories_quantity_remaining`, `categories_GA`, `categories_image_2`, `color_code`, `manufacturers_id`, `categories_is_printable`, `section_id`, `categories_shipping`, `concert_date_unix`, `categories_image_3`, `categories_image_4`, `bg_height`) VALUES
(1, 'jnl_bg-01.jpg', 0, 1, NULL, '2021-10-20 00:00:00', 1, 1, '05-12-2021 1800', 6, 0, 0, 0, 'icon_icon.jpg', '', '6', 1, 1, 0, 1638662400, 'jnl_bg-_dancefloor-01.jpg', '', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `categories_description`
--

CREATE TABLE `categories_description` (
  `categories_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `categories_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `categories_heading_title` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `categories_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `concert_venue` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `concert_date` varchar(28) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `concert_time` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `venue_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories_description`
--

INSERT INTO `categories_description` (`categories_id`, `language_id`, `categories_name`, `categories_heading_title`, `categories_description`, `concert_venue`, `concert_date`, `concert_time`, `venue_id`, `section_id`) VALUES
(1, 1, 'JollofNLaugh', 'JollofNLaugh', '', 'Tuder Rose', '05-12-2021', '1800', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `configuration_id` int(11) NOT NULL,
  `configuration_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `configuration_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `configuration_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `configuration_group_id` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `use_function` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `set_function` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1, 'Store Name', 'STORE_NAME', 'JollofNLaugh', 'The name of my store', 1, 1, '2021-10-19 12:45:23', '2020-01-01 00:00:00', NULL, NULL),
(2, 'Store Owner', 'STORE_OWNER', 'JollofNLaugh', 'The name of my store owner', 1, 2, '2021-10-30 20:58:59', '2020-01-01 00:00:00', NULL, NULL),
(3, 'E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'bookings@jollofnlaugh.com', 'The e-mail address of my store owner', 1, 3, '2021-10-20 07:14:39', '2020-01-01 00:00:00', NULL, NULL),
(4, 'E-Mail From', 'EMAIL_FROM', 'bookings@jollofnlaugh.com', 'The e-mail address used in (sent) e-mails', 1, 4, '2021-10-20 07:15:00', '2020-01-01 00:00:00', NULL, NULL),
(5, 'Country', 'STORE_COUNTRY', '222', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', 1, 6, '2021-10-19 07:57:43', '2020-01-01 00:00:00', 'tep_get_country_name', 'tep_cfg_pull_down_country_list('),
(6, 'State/Province', 'STORE_ZONE', '4017', 'The zone my store is located in', 1, 7, '2021-10-19 07:57:54', '2020-01-01 00:00:00', 'tep_cfg_get_zone_name', 'tep_cfg_pull_down_zone_list('),
(8, 'Send Admin Confirmation and Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send admin confirmation order emails and extra order emails to the following email addresses, in this format: email@address, email@address2', 1, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(9, 'Store Address and Phone', 'STORE_NAME_ADDRESS', 'Tudor Rose, 68 The Green, London, UB2 4BG', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18, '2021-10-30 21:00:11', '2020-01-01 00:00:00', NULL, 'tep_cfg_textarea('),
(10, 'Default theme', 'DEFAULT_TEMPLATE', 'newzone', 'Use this to set the default theme.', 1, 0, '2016-12-20 19:24:39', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_template_list('),
(11, 'Company Logo', 'COMPANY_LOGO', 'osconcert.png', 'Your Store Logo', 1, 0, '2021-10-20 23:25:35', '2020-01-01 00:00:00', NULL, 'file_upload'),
(12, 'Date format to display dates', 'EVENTS_DATE_FORMAT', 'd-m-Y', 'Date format used to display date entries', 1, 4, '2021-10-23 12:21:05', '2020-01-01 00:00:00', NULL, 'tep_cfg_date_formats('),
(13, 'Server Date Offset (Not Required)', 'EVENTS_SERVER_DATE_OFFSET', '0', 'Ensure messages are sent in correct time', 1, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_date_offset('),
(14, 'We are selling seats as Products', 'SHOP_SELL_ITEMS', 'P', 'Items Sold in the shop are seats as products. osConcert does not support other sections of this software. Use at your own risk', 1, 101, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_cfg_select_option_title', 'tep_cfg_select_multioption(array(\'P\'),'),
(15, 'Time format to display time', 'TIME_FORMAT', '12', 'Time format used to display time entries', 1, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_time_formats('),
(16, 'Notify Admin of SIGN UP', 'ADMIN_SIGNUP_NOTIFICATION', 'false', 'Send Notification of SIGN UP to Top Administrator', 1, 23, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(17, 'Timezone', 'STORE_TIMEZONE', 'UTC', 'The timezone my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', 1, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_timezone', 'tep_cfg_pull_down_timezone_list('),
(18, 'First Name', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', 2, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(19, 'Last Name', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', 2, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(20, 'Date of Birth', 'ENTRY_DOB_MIN_LENGTH', '10', 'Minimum length of date of birth', 2, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(21, 'E-Mail Address', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of e-mail address', 2, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(22, 'Street Address', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5', 'Minimum length of street address', 2, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(23, 'Company', 'ENTRY_COMPANY_MIN_LENGTH', '2', 'Minimum length of company name', 2, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(24, 'Post Code', 'ENTRY_POSTCODE_MIN_LENGTH', '4', 'Minimum length of post code', 2, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(25, 'City', 'ENTRY_CITY_MIN_LENGTH', '3', 'Minimum length of city', 2, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(26, 'State', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', 2, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(27, 'Telephone Number', 'ENTRY_TELEPHONE_MIN_LENGTH', '8', 'Minimum length of telephone number', 2, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(28, 'Password', 'ENTRY_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', 2, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(29, 'User name', 'ENTRY_USERNAME_MIN_LENGTH', '6', 'Minimum length of user name', 2, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(30, 'Small Image Width', 'SMALL_IMAGE_WIDTH', '75', 'The pixel width of small images', 4, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(31, 'Small Image Height', 'SMALL_IMAGE_HEIGHT', '', 'The pixel height of small images', 4, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(32, 'Heading Image Width', 'HEADING_IMAGE_WIDTH', '200', 'The pixel width of heading images', 4, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(33, 'Heading Image Height', 'HEADING_IMAGE_HEIGHT', '', 'The pixel height of heading images', 4, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(34, 'Subcategory Image Width', 'SUBCATEGORY_IMAGE_WIDTH', '200', 'The pixel width of subcategory images', 4, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(35, 'Subcategory Image Height', 'SUBCATEGORY_IMAGE_HEIGHT', '', 'The pixel height of subcategory images', 4, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(36, 'Calculate Image Size', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true', 'Calculate the size of images?', 4, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(37, 'Image Upload Width', 'UPLOAD_WIDTH', '1000', 'Image Upload Width Restriction', 4, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"400\",\"450\",\"500\",\"550\",\"600\",\"650\",\"700\",\"750\",\"800\"),'),
(38, 'Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '6', 'Minimum length of credit card owner name', 5, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(39, 'Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '12', 'Minimum length of credit card number', 5, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(40, 'Min. Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', 5, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(41, 'Min. Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the \'This Customer Also Purchased\' box', 5, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(42, 'Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '100', 'Maximum address book entries a customer is allowed to have', 5, 17, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(43, 'Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '20', 'Amount of products to list', 5, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(44, 'Page Links', 'MAX_DISPLAY_PAGE_LINKS', '15', 'Number of \'number\' links use for page-sets', 5, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(45, 'Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '7', 'How many categories to list per row', 5, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(46, 'Max. Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', 5, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(47, 'Max. Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '6', 'Maximum number of products to display in the \'This Customer Also Purchased\' box', 5, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(48, 'Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', 5, 17, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(49, 'My Account', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', 5, 18, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(50, 'Gender', 'ACCOUNT_GENDER', 'false', 'Display gender in the customers account', 5, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(51, 'Date of Birth', 'ACCOUNT_DOB', 'false', 'Display date of birth in the customers account', 5, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(52, 'Company', 'ACCOUNT_COMPANY', 'false', 'Display company in the customers account', 5, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(53, 'Address 2', 'ACCOUNT_SUBURB', 'true', 'Display Address 2 in the customers account', 5, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(54, 'State', 'ACCOUNT_STATE', 'true', 'Display state in the customers account', 5, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(55, 'Second Phone number', 'ACCOUNT_SECOND_PHONE', 'true', 'Second phone number moved manually to primary by admin if needed', 5, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\',\'false\'),'),
(56, 'Second Email address', 'ACCOUNT_SECOND_EMAIL', 'true', 'Second Email address moved manually to primary by admin if needed', 5, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\',\'false\'),'),
(57, 'Display \"Other\" Referral option', 'DISPLAY_REFERRAL_OTHER', 'true', 'Display \"Other - please specify\" with text box in referral source in account creation', 5, 22, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(58, 'Hide the Referral Question in the Front End', 'HIDE_REFERRAL_FRONT', 'false', 'Hide the Question in the front end in customer details page', 5, 23, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(59, 'Hide the Referral Question in the Back End', 'HIDE_REFERRAL_BACK', 'false', 'Hide the Question in the back end in customer details page', 5, 23, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\',\'false\'),'),
(60, 'User name', 'ACCOUNT_USERNAME', 'true', 'Display username in the customers account', 5, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(61, 'Telephone', 'ACCOUNT_TELEPHONE', 'true', 'Display Telephone in the customers account', 5, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\',\'false\'),'),
(62, 'Encryption Hash', 'ENCRYPTION_HASH_VALUE', '@sC@mmRes', 'Encryption Hash Value applied in cookies for storage of password', 5, 100, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', ''),
(63, 'My Account on Login', 'MY_ACCOUNT_LOGIN', 'false', 'To set the Customer to view their MyAccount page directly.', 5, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'true\',\'false\'),'),
(64, 'Customers Password Strength', 'MODULE_CUSTOMERS_PASSWORD_STRENGTH', '3', 'Password Strength: 1 = no checking, 2 = alphanumeric, 3 = alphanumeric + symbols', 5, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_password_option_title', 'tep_cfg_password_option(array(\'1\',\'2\',\'3\'),'),
(65, 'Admin Password Strength', 'MODULE_ADMIN_PASSWORD_STRENGTH', '3', 'Password Strength: 1 = no checking, 2 = alphanumeric, 3 = alphanumeric + symbols', 5, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_password_option_title', 'tep_cfg_password_option(array(\'1\',\'2\',\'3\'),'),
(66, 'Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'reservation.php;stripesca.php;boxoffice.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cc.php;cod.php;paypal.php)', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(67, 'Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_service_fee.php;ot_subtotal.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(68, 'Installed Modules', 'MODULE_SHIPPING_INSTALLED', '', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(69, 'Default Currency', 'DEFAULT_CURRENCY', 'GBP', 'Default Currency', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(70, 'Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(71, 'Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(75, 'Owner Payment ID', 'OWNER_IDENTIFICATION', '', 'The payment ID of the store owner', 153, 3, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(76, 'Store Owner Telephone', 'STORE_OWNER_TELEPHONE', '', 'The telephone number of the store owner', 1, 3, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(84, 'Installed Modules', 'MODULE_SMS_GATEWAYS_INSTALLED', 'clickatell.php', 'This is automatically updated. No need to edit.', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(86, 'Last Database Restore', 'DB_LAST_RESTORE', 'none.sql', 'Last database restore file', 6, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', ''),
(87, 'Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', 7, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_country_name', 'tep_cfg_pull_down_country_list('),
(88, 'Postal Code', 'SHIPPING_ORIGIN_ZIP', 'LW1', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', 7, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(89, 'Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', 7, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(90, 'Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '0', 'What is the weight of typical packaging of small to medium packages?', 7, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(91, 'Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', 7, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(92, 'Shipping Estimator', 'SHOW_SHIPPING_ESTIMATOR', 'false', 'Show Shipping Estimator on Shopping Cart <br>true= always <br>false= button popup only', 7, 102, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(93, 'Packaging Surcharge', 'PRODUCT_PACKAGING_SURCHARGE', '', 'Packaging surcharge for products ($)', 7, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(94, 'Packaging Cost', 'PRODUCT_PACKAGING_COST', '', 'Packaging cost for products (%)', 7, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(95, 'Display Handling Charges', 'DISPLAY_HANDLING_CHARGE', 'false', 'Display handling charge in shipping cost', 7, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(96, 'Weight Unit', 'SHOP_WEIGHT_UNIT', 'KG', 'Shop Weight Unit used', 7, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_cfg_pull_down_shop_weight_unit_title', 'tep_cfg_pull_down_shop_weight_unit('),
(97, 'Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', 8, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(98, 'Display Product Manufacturer Name', 'PRODUCT_LIST_MANUFACTURER', '0', 'Do you want to display the Product Manufacturer Name?', 8, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(99, 'Display Product Model', 'PRODUCT_LIST_MODEL', '1', 'Do you want to display the Product Model?', 8, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(100, 'Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', 8, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(101, 'Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', 8, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(102, 'Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '0', 'Do you want to display the Product Quantity?', 8, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(103, 'Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', 8, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(104, 'Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', 8, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(105, 'Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '0', 'Do you want to display the Category/Manufacturer Filter?', 8, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(106, 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '3', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 8, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(107, 'Check stock level', 'STOCK_CHECK', 'true', 'Check to see if sufficent stock is available', 9, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(108, 'Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', 9, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(109, 'Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'false', 'Allow customer to checkout even if there is insufficient stock', 9, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(110, 'Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***THESE ITEMS ARE OUT OF STOCK***', 'Display something on screen so customer can see which product has insufficient stock', 9, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(111, 'Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', 9, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(112, 'Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', 10, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(113, 'Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', 10, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(114, 'Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', 10, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(115, 'Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', 10, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(116, 'Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log (PHP4 only)', 10, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(117, 'Use Cache', 'USE_CACHE', 'true', 'Use caching features', 11, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(118, 'Cache Directory', 'DIR_FS_CACHE', '/tmp/', 'The directory where the cached files are saved', 11, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(119, 'Enable SEO URLs?', 'SEO_ENABLED', 'false', 'Enable the SEO URLs?  This is a global setting and will turn them off completely.', 11, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'false\'),'),
(120, 'E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', 12, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'LF\', \'CRLF\'),'),
(121, 'Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'false', 'Send e-mails in HTML format', 12, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(122, 'Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESSES_CHECK', 'false', 'Verify e-mail address through a DNS server', 12, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(123, 'Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', 12, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(124, 'Test Email address', 'EVENTS_TEST_EMAIL_ADDRESS', 'bookings@jollofnlaugh.com', 'testing email address used in messaging system', 12, 1, '2021-10-30 21:28:55', '2020-01-01 00:00:00', NULL, NULL),
(125, 'Messaging Times', 'EVENTS_EMAIL_TIMINGS', '0:00,0:00', 'Limit the times messages are sent', 12, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_msg_times('),
(126, 'Email Activation', 'EMAIL_ACTIVATE', '1', 'Activation flag used to enable the email sending process', 12, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_email_option('),
(127, 'Email Interval(Sec,)', 'EMAIL_TIME_LIMIT', '1', 'Set TimeLimit for Email sending process', 12, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_email_timelimit('),
(128, 'Email method', 'EMAIL_SMTP_TRANSPORT', 'sendmail', 'PHP native mail or SMTP?', 12, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'sendmail\', \'smtp\'),'),
(129, 'SMTP: Server URL', 'EMAIL_SMTP_HOST_SERVER', 'smtp.yourmailserver.com', 'URL for your SMTP server.', 12, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(130, 'SMTP: Access port', 'EMAIL_SMTP_PORT_SERVER', '25', 'Port to be used for connecting to the server. Default: 25. SSL: 465 , TSL 587', 12, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(131, 'SMTP: Username', 'EMAIL_SMTP_USERNAME', 'username', 'Username for authenticating your connection.', 12, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(132, 'SMTP: Password', 'EMAIL_SMTP_PASSWORD', 'password', 'Password for the above username.', 12, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(133, 'Enable download', 'DOWNLOAD_ENABLED', 'true', 'Enable the products download functions.', 13, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(134, 'Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'true', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(135, 'Expiry delay (days)', 'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', 13, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, ''),
(136, 'Maximum number of downloads', 'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, ''),
(137, 'Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', 14, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(138, 'Compression Level', 'GZIP_LEVEL', '5', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', 14, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(139, 'Session Directory', 'SESSION_WRITE_DIRECTORY', 'tmp', 'If sessions are file based, store them in this directory.', 15, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(140, 'Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', 15, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),'),
(141, 'Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'True', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', 15, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),'),
(142, 'Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', 15, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),'),
(143, 'Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'True', 'Validate the clients IP address on every page request.', 15, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),'),
(144, 'Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'true', 'Prevent known spiders from starting a session.', 15, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(145, 'Recreate Session', 'SESSION_RECREATE', 'true', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', 15, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(146, 'Session Timeout for Front-end users', 'SESSION_TIMEOUT_FRONTEND', '10', 'Set the  session timeout value for  for front-end users', 15, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_text_field('),
(147, 'Session Timeout for Back-end users', 'SESSION_TIMEOUT_BACKEND', '1000', 'Set the session timeout value for for back-end users', 15, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_text_field('),
(148, '<B>Down for Maintenance: ON/OFF</B>', 'DOWN_FOR_MAINTENANCE', 'false', 'Down for Maintenance <br>(true=on false=off)', 16, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(149, 'Down for Maintenance: filename', 'DOWN_FOR_MAINTENANCE_FILENAME', 'down_for_maintenance.php', 'Down for Maintenance filename Default=down_for_maintenance.php', 16, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, ''),
(150, 'Down for Maintenance: Hide Header', 'DOWN_FOR_MAINTENANCE_HEADER_OFF', 'false', 'Down for Maintenance: Hide Header <br>(true=hide false=show)', 16, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(151, 'Down for Maintenance: Hide Column Left', 'DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF', 'false', 'Down for Maintenance: Hide Column Left <br>(true=hide false=show)', 16, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(152, 'Down for Maintenance: Hide Column Right', 'DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF', 'true', 'Down for Maintenance: Hide Column Right <br>(true=hide false=show)r', 16, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(153, 'Down for Maintenance: Hide Footer', 'DOWN_FOR_MAINTENANCE_FOOTER_OFF', 'false', 'Down for Maintenance: Hide Footer <br>(true=hide false=show)', 16, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(154, 'Down for Maintenance: Hide Prices', 'DOWN_FOR_MAINTENANCE_PRICES_OFF', 'false', 'Down for Maintenance: Hide Prices <br>(true=hide false=show)', 16, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(155, 'Down For Maintenance (exclude this IP-Address)', 'EXCLUDE_ADMIN_IP_FOR_MAINTENANCE', '144.136.152.5', 'This IP Address is able to access the website while it is Down For Maintenance (like webmaster)', 16, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(156, 'NOTIFY PUBLIC Before going Down for Maintenance: ON/OFF', 'WARN_BEFORE_DOWN_FOR_MAINTENANCE', 'false', 'Give a WARNING some time before you put your website Down for Maintenance<br>(true=on false=off)<br>If you set the \'Down For Maintenance: ON/OFF\' to true this will automaticly be updated to false', 16, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(157, 'Date and hours for notice before maintenance', 'PERIOD_BEFORE_DOWN_FOR_MAINTENANCE', '12/02/2004  between the hours of 11.00-11.30 PM Australian Estern Standard Time', 'Date and hours for notice before maintenance website, enter date and hours for maintenance website', 16, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(158, 'Display when webmaster has enabled maintenance', 'DISPLAY_MAINTENANCE_TIME', 'true', 'Display when Webmaster has enabled maintenance <br>(true=on false=off)<br>', 16, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(159, 'Display website maintenance period', 'DISPLAY_MAINTENANCE_PERIOD', 'true', 'Display Website maintenance period <br>(true=on false=off)<br>', 16, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(160, 'Website maintenance period', 'TEXT_MAINTENANCE_PERIOD_TIME', '0h30', 'Enter Website Maintenance period (hh:mm)', 16, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(188, 'HTML Editor to be used in Back End', 'HTML_EDITOR', 'tinyMce', 'HTML Editor to be used in Back End', 112, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"tinyMce\",\"htmlArea\"),'),
(189, 'Show Category Counts', 'SHOW_COUNTS', 'true', 'Count recursively how many products are in each category', 150, 19, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(190, 'Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '3', 'Maximum number of products on special to display', 150, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(191, 'New Products Module', 'MAX_DISPLAY_NEW_PRODUCTS', '3', 'Maximum number of new products to display in a category', 150, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(192, 'Products Expected', 'MAX_DISPLAY_UPCOMING_PRODUCTS', '3', 'Maximum number of products expected to display', 150, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(193, 'Manufacturers Swap to List', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '5', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', 150, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(194, 'Manufacturers Display Format', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', 150, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(195, 'Length of Manufacturers Name', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15', 'Used in manufacturers box; maximum length of manufacturers name to display', 150, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(196, 'Selection of Random New Products', 'MAX_RANDOM_SELECT_NEW', '10', 'How many records to select from to choose one random new product to display', 150, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(197, 'Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', 150, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(198, 'New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '8', 'Maximum number of new products to display in new products page', 150, 14, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(199, 'Allow Category Descriptions', 'ALLOW_CATEGORY_DESCRIPTIONS', 'true', 'Allow use of full text descriptions for categories', 150, 19, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(200, 'Product Featured Maximum Display', 'MAX_DISPLAY_FEATURED_PRODUCTS', '6', 'Amount of products to on main page', 150, 170, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(201, 'Product Featured Display Results', 'MAX_DISPLAY_FEATURED_PRODUCTS_LISTING', '10', 'Amount of products to list per page', 150, 171, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(202, 'Category/Products Display Order', 'CATEGORIES_SORT_ORDER', 'model', '<b>Valid Orders:<br>products_name<br>products_name-desc<br>model<br>model-desc</b>', 150, 99, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(203, 'Hide Featured Product id', 'HIDE_FEATURED_PRODUCT_ID', 'yes', 'Do you want to Hide the Featured Product id on Front End?.', 150, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'Yes\', \'No\'),'),
(204, 'SKU length', 'SKU_LENGTH', 'false', 'Enable SKU', 150, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(205, 'Number of SKU Characters', 'SKU_COUNT', '13', 'Total Length of characters for SKU', 150, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_sku_length_count_option('),
(206, 'Hide Product added text', 'HIDE_PRODUCT_ADDED_TEXT', 'true', 'Hide this product was added to our catalog ..', 150, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(207, 'Hide Also available', 'HIDE_ALSO_AVAILABLE', 'true', 'Hide the display of Also Available products on the \r\nhomepage', 150, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(208, 'Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', 151, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'false\'),'),
(209, 'GA Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or go direct to checkout)', 19, 14, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(211, 'Encryption style', 'ENCRYPTION_STYLE', 'O', 'Encryption style used in password storage and checking', 151, 100, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_encryption_name', 'tep_cfg_pull_down_encryption_type('),
(212, 'Single Page Checkout Enable', 'SINGLE_PAGE_CHECKOUT', 'False', 'Single Page Checkout or Normal Checkout.DISABLED', 151, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'False\'),'),
(213, 'Upload File Format', 'UPLOAD_FORMAT', 'jpeg,jpg,png,gif', 'Enter the type of format to be uploaded', 151, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', ''),
(214, 'Admin Single Page Checkout Enable', 'ADMIN_SINGLE_PAGE_CHECKOUT', 'True', 'Admin Single Page Checkout or Normal Checkout', 151, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'True\'),'),
(215, 'Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', 152, 20, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(216, 'Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'true', 'Display prices with tax included (true) or add the tax at the end (false)', 152, 21, '2020-04-21 16:14:29', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(217, 'Allow Guest To See Prices', 'ALLOW_GUEST_TO_SEE_PRICES', 'true', 'Allow guests to view default prices', 153, 31, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(218, 'Guest Discount', 'GUEST_DISCOUNT', '0', 'Guest discount', 153, 32, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(219, 'Show Featured Products', 'SHOW_MAIN_FEATURED_PRODUCTS', 'false', 'true= on<br>false= off', 19, 20, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(220, 'Add Social Networking', 'SOCIAL_NETWORKING', 'no', 'Add social networking buttons at checkout payment.', 154, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(221, 'Add Social Bar', 'ADD_SOCIALBAR', 'false', 'Enable Social Networking Bar in Header', 154, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(222, 'Twitter ID', 'TWITTER_ID', '', 'Enable Twitter in Social Networking Bar in Header', 154, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(223, 'Facebook ID', 'FACEBOOK_ID', '', 'Enable Facebook in Social Networking Bar in Header', 154, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(224, 'Linkedin ID', 'LINKEDIN_ID', '', 'Enable Linkedin in Social Networking Bar in Header', 154, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(225, 'Instagram ID', 'INSTAGRAM_ID', '', 'Enable Instagram in Social Networking Bar in Header', 154, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(226, 'Show banner in header', 'SHOW_HEADER_BANNER', 'false', 'Show a banner image - source: images/banners/', 154, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(227, 'Display Featured Categories Page', 'SHOW_MAIN_FEATURED_CATEGORIES', 'true', 'Display Featured Categories Page from Menu', 19, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(228, 'Page url for sharing', 'SHAREPAGE', 'https://www.osconcert.com', 'The page url you want to share for social networking', 154, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(229, 'Title text for sharing', 'SHAREPAGE_TITLE', 'osConcert Seat Booking Software', 'The title text you want to share for social networking', 154, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(230, 'Block Differing Country and IP', 'BLOCK_DIFFER_COUNTRY_IP', 'No', 'Block Differing Country Address and IP Address during payment selection', 202, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'Yes\',\'No\'),'),
(231, 'Block Excluded Countries', 'BLOCK_EXCLUDED_COUNTRIES', 'No', 'Block Payment Excluded countries during payment selection', 202, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'Yes\',\'No\'),'),
(232, 'GeoIP Service License key', 'GEOIP_LICENSE_KEY', '', 'GeoIP License ', 202, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(233, 'Minimum Amount', 'WALLET_MINIMUM_AMOUNT', '50', 'Minimum amount needed in Customers Wallet', 902, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_text_field('),
(234, 'Minimum Amount Type', 'WALLET_MINIMUM_TYPE', 'P', 'Type of Minimum Amount either percentage of last 3 orders or currency value made by wallet payment', 902, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'tep_get_wallet_minimum_type', 'tep_cfg_pull_down_minimum_type('),
(235, 'First Order Balance', 'WALLET_FIRST_ORDER_BALANCE', '500', 'Minimum money allowed for  Subscription Order', 902, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_text_field('),
(236, 'SMS Gateway', 'SMS_GATEWAY', 'clickatell', 'Gateway to send sms email messages', 903, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_sms_gateway('),
(237, 'Send order confirmation SMS?', 'SEND_ORDER_SUCCESS_SMS', 'false', 'Do you want to send a customer sms to confirm the order', 903, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(238, 'No checkout for zero price events', 'NO_CHECKOUT_ZERO_PRICE', '0', 'Flag used to redirect users with zero event to checkout success without doing checkout process', 905, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_email_option('),
(239, 'Maximum Rows', 'REPORT_MAX_ROWS_PAGE', '25', 'No of Rows per page in Report', 906, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_report_max_rows('),
(240, 'Maximum Links', 'REPORT_MAX_LINKS_PAGE', '5', 'No of page links to display in Report', 906, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_report_max_links('),
(242, 'osConcert Version', 'OSCONCERT_VERSION', 'osConcert v9.5 2021', 'What version osConcert is?', 917, 1, '2020-04-20 11:04:46', '2020-01-01 00:00:00', NULL, NULL),
(243, 'Enable \'How To\' Guide', 'HOW_TO_GUIDE', 'true', 'Enables How To Help Guide below the seat plan', 922, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(244, 'Hide Choose Billing Address for BOX OFFICE', 'HIDE_BILLING_ADDRESS', 'no', 'Hide Choose Billing Address at Checkout. Unhide this for BOX OFFICE use', 923, 1, '2021-10-23 12:31:46', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(245, 'Hide Delivery Address', 'HIDE_DELIVERY_ADDRESS', 'yes', 'Hide Delivery Address at Checkout Confirmation', 917, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(246, 'Seat Plan Cache', 'SEAT_PLAN_CACHE', 'false', 'Enable Seat Plan Caching', 922, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(247, 'Expand Menus List', 'MENU_LIST', 'false', 'Enable More Menu Listing -Venue,Date,Time. ONLY for Left Column Categories InfoBox', 19, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(248, 'Seatplan Live Logging', 'SEATPLAN_LOGGING', 'false', 'Enable Seatplan Live Logging (Debug Mode)', 922, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', '', 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(249, 'Seatplan Timeout', 'SEATPLAN_TIMEOUT', '600', 'Cart timeout in seconds', 922, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"60\",\"120\",\"180\",\"240\",\"300\", \"360\", \"420\",\"480\",\"540\",\"600\",\"660\",\"720\",\"780\",\"840\",\"900\",\"960\",\"1020\",\"1080\",\"1140\",\"1200\"),'),
(250, 'Seatplan Refresh', 'SEATPLAN_REFRESH', '2000', 'The seatplan refresh-rate in milliseconds.', 922, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"2000\", \"4000\",\"6000\",\"8000\",\"10000\",\"12000\",\"14000\",\"16000\",\"18000\",\"20000\",\"22000\",\"24000\",\"26000\",\"28000\",\"30000\",\"32000\",\"34000\",\"36000\",\"38000\",\"40000\",\"100000\"),'),
(251, 'Google Analytics Account', 'GOOGLE_ANALYTICS', '', 'Enter your Google Analytics account ID here.', 917, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(252, 'Seatplan Require Login', 'SEATPLAN_LOGIN_ENFORCED', 'false', 'Seat reservation only possible with an active login.', 922, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(253, 'Add Extra Fields', 'EXTRA_FIELDS', 'no', 'Add extra fields at checkout payment.Configurable in includes/languages/english.php', 917, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(254, 'Maximum quantity in cart', 'MAX_IN_CART_AMOUNT', '0', 'Set the maximum number of tickets per order. Zero = unlimited. Do NOT use with GIFT voucher feature.', 917, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, ''),
(255, 'Purchase without an account', 'PURCHASE_WITHOUT_ACCOUNT', 'yes', 'Do you want customers to be able to purchase without an account?', 917, 14, '2021-10-16 16:27:18', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(256, 'Disable creating account entirely? (Will override any setting under Purchase Without Account)', 'PURCHASE_NO_ACCOUNT', 'no', 'Do you want to skip login and send customers direct to the checkout process?', 917, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(257, 'Show Subcategory Count', 'SUBCATEGORY_COUNT', 'false', 'Enable Subcategory count to calculate remaining seats available or count of reserved seats in the category. For Seat Plans with Sub Categories', 19, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"reserved\", \"remaining\", \"false\"),'),
(259, 'Hide GA Quantities', 'HIDE_GA_QTY', 'yes', 'Hide GA product quantities when using category master quantities', 19, 18, '2021-10-21 11:40:05', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(260, 'Hide Footer Info Links', 'HIDE_FOOTER_INFO', 'no', 'Hide Privacy, Terms and Contact Us Links in the Footer', 19, 19, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(261, 'Enforced Coupon', 'ENFORCED_COUPON', 'no', 'Enforced Coupon Entry at Checkout', 917, 20, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(262, 'Enable STAGE', 'HAS_STAGE', 'true', 'Enable stage image above seat plan', 922, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(263, 'Add Conditions at checkout confirmation', 'ADD_CONDITIONS', 'true', 'Add Conditions Agreement at Checkout Confirmation page. Editable in Admin>CMS>TandC', 917, 22, '2021-10-16 16:28:19', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(264, 'Hide Sign Up Page', 'HIDE_SIGNUP', 'false', 'Hide Sign Up Page with Enforced Login', 917, 23, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(265, 'Seatplan Restrict Logins at Load Value', 'SEATPLAN_LOGIN_ENFORCED_LOAD', '100', 'Percentage of server use to restrict logins at - only enforced Seatplan Require Login = TRUE', 922, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"30\",\"35\",\"40\",\"45\",\"50\", \"55\", \"60\",\"65\",\"70\",\"75\",\"80\",\"85\",\"90\",\"95\",\"99\",\"100\"),'),
(266, 'Restrict number of concurrently logged on customers?', 'RESTRICT_NO_LOGONS', '0', 'Allows you to limit how many customers may be logged on simultaneously', 917, 25, '2020-03-21 04:19:19', '2020-01-01 00:00:00', NULL, NULL),
(267, 'Login to see seat plan and GA products only', 'LOGIN_SEATPLAN', 'false', 'Block Seat Plan unless Logged in', 922, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(268, 'Skip Payment Method', 'SKIP_PAYMENT_METHOD', 'false', 'Skip Payment Method page if there is only one payment module INSTALLED', 917, 27, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(269, 'Pop Up Discount Width', 'POPUP_DISCOUNT_WIDTH', '500', 'The custom width of your pop up discount box - default = 500 pixels.', 917, 28, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(270, 'Pop Up Discount Height', 'POPUP_DISCOUNT_HEIGHT', '150', 'The custom height of your pop up discount box - default = 150 pixels.', 917, 29, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(271, 'Set Family Ticket Quantity', 'FAMILY_TICKET_QTY', '4', 'How many tickets for a Family Ticket to subtract from stock - especially when used with GA Master Quantity', 19, 30, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"1\",\"2\",\"3\",\"4\",\"5\", \"6\", \"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"20\"),'),
(273, 'Show Disabled Events With A Message', 'SHOW_DISABLED_CATEGORIES', 'false', 'Show a message \'Event Disabled Message\' when an EVENT is disabled in Concert Details', 922, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(275, 'Show Discount Popup', 'DISCOUNT_POPUP', 'true', 'Show the discount pop up box when seat is selected', 917, 34, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(276, 'Account Review', 'REVIEW_ACCOUNT', 'no', 'Set the Sign In Registration Account for review by Administrator (Customer will need to wait for account activation)', 917, 35, '2020-03-21 07:08:08', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');
INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(277, 'Hide GA (Master Quantity) quantity remaining message', 'HIDE_GA_ONLY_QTY', 'no', 'When your GA event is set to GA only (Master Quantity) with concessions, hide this here if you do not want your visitors to know the quantity of tickets remaining', 19, 36, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(278, 'PWA Logoff', 'PWA_LOGOFF', 'true', 'Logoff a PWA account at checkout success -continue- button', 917, 37, '2021-10-16 16:27:25', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(279, 'Hide CMS listings in the INFORMATION infobox', 'HIDE_CMS_INFO', 'yes', 'Hide the CMS listings but keep only the Contact Us link when the Informtion Infobox id enabled', 917, 38, '2020-03-21 07:30:38', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(282, 'Product Info Message', 'PRODUCT_INFO_MESSAGE', 'For more information please contact us.', 'Short Message below Product Info Page', 19, 41, '2020-03-21 07:36:13', '2020-01-01 00:00:00', NULL, NULL),
(283, 'Hide Row Categories in Admin>Products', 'HIDE_ROW_CATS', 'no', 'Hide the Rows when they are not needed', 917, 42, '2016-12-20 19:31:49', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(284, 'Event Disabled Message', 'EVENT_DISABLED_MESSAGE', 'Sorry, this event is over', 'Message for Event Disabled option', 922, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(285, 'Expiry Time Difference', 'SHOW_TIME_DIFF', 'no', 'Show Date Expiry Time Difference in the footer', 919, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(286, 'Date Expiry Cron', 'EXPIRY_CRON', '3600', 'Date expiry cron in seconds', 919, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"60\",\"1800\",\"3600\"),'),
(288, 'Enable PDF E-Tickets', 'E_TICKETS', 'true', 'Enable E-Tickets', 920, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(289, 'E-Ticket Delivered Status', 'E_TICKET_STATUS', '3', 'The payment status needed to enable E-Tickets to be viewed (default-Delivered)', 920, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(290, 'Display PDF E-Tickets for Delivered Status', 'DISPLAY_PDF_DELIVERED_ONLY', 'true', 'Display PDF E-Tickets when payment status is Delivered and E-Tickets Enabled', 920, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(292, 'Ticket Template', 'TICKET_TEMPLATE', '1', 'Choose a LOADED e-Ticket template', 920, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"1\",\"2\",\"3\",\"4\",\"5\"),'),
(293, 'Force eTicket Download ', 'DOWNLOAD_TICKET', 'false', 'Force Download of PDF eTicket (More Secure)', 920, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(294, 'eTicket Set Up Options', 'BARCODE', 'QR', '128 Barcode - QR Code - No Barcode', 920, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"QR\",\"128\",\"none\"),'),
(295, 'Box Office Direct Checkout', 'DIRECT_CHECKOUT', 'false', 'Direct Generation of eTickets by Box Office Staff user -NO CHECKOUT. Please turn OFF when Box Office Reservation is enabled.', 923, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(296, 'Ticket Print Master Quantity', 'TICKET_MASTER_QUANTITY', 'false', 'Add a master quantity for products when printing all tickets', 920, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(297, 'Attach PDF E-Tickets to order email for Delivered Status', 'EMAIL_PDF_DELIVERED_ONLY', 'true', 'Email PDF E-Tickets when payment status is Delivered and E-Tickets Enabled', 920, 10, '2020-04-21 16:22:03', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(298, 'Top Font Size', 'TOP_FONT_SIZE', '20', 'The size of the font for the TOP line', 920, 11, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"10\",\"11\",\"12\",\"13\",\"14\", \"15\", \"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"26\"),'),
(299, 'Mid Font Size', 'MID_FONT_SIZE', '12', 'The size of the font for the Middle text', 920, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"10\",\"11\",\"12\",\"13\",\"14\", \"15\", \"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"26\"),'),
(300, 'Middle Text Spacing', 'MID_TEXT_SPACING', '4', 'Vertical spacing for the middle text', 920, 13, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"2\",\"3\",\"4\",\"5\",\"6\",\"7\", \"8\",\"9\",\"10\"),'),
(301, 'Text Positioning from Left', 'TEXT_LEFT_POSITION', '0', 'Text positioning from left side of the ticket', 920, 14, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(302, 'Text Positioning from Top', 'TEXT_TOP_POSITION', '0', 'Text positioning from top of the ticket', 920, 15, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(303, 'Bottom Font Size', 'BOTTOM_FONT_SIZE', '8', 'The size of the font for the Bottom Conditions text', 920, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"7\",\"8\",\"9\",\"10\",\"11\",\"12\"),'),
(304, 'QR Code Width', 'QR_WIDTH', '30', 'The size of the QR Code', 920, 17, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"20\",\"21\",\"22\",\"23\",\"24\", \"25\", \"26\",\"27\",\"28\",\"29\",\"30\",\"31\",\"32\",\"33\",\"34\",\"35\",\"36\"),'),
(305, 'Tear Line', 'TEAR_LINE', '90', 'Tear line from left side of the ticket', 920, 18, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(306, 'Read QR information on PDF eTicket', 'TEST_QR_TEXT', 'no', 'Read QR Code text in the eTicket for testing only', 920, 19, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(307, 'Mailerlite API Key', 'ML_KEY', '0', 'Your API Key Assigned by MailerLIte.com', 921, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(308, 'Mailerlite API Group ID', 'MAIL_API_GROUP_ID', '0', 'Your Group ID Assigned by MailerLite.com', 921, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(309, 'Amount Rounding Factor', 'EVENTS_ORDER_AMOUNT_ROUND', '0.01', 'Round up gross amounts for the total amount of order reservations', 1000, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_pull_down_round_factors('),
(310, 'Last sql file executed during previous installation', 'LAST_SQL_FILE_EXECUTED', '', '', 99999, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(311, 'Enable RESERVATION Module', 'MODULE_PAYMENT_RESERVATION_STATUS', 'True', 'Do you want to enable free reservations?', 6, 1, NULL, '2019-10-11 16:00:19', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), '),
(312, 'RESERVATION Payment Zone', 'MODULE_PAYMENT_RESERVATION_ZONE', '1,2,3', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2019-10-11 16:00:19', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(313, 'RESERVATION Exclude these Countries', 'MODULE_PAYMENT_RESERVATION_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', 6, 3, NULL, '2019-10-11 16:00:19', 'tep_get_zone_except_country', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_RESERVATION_ZONE,'),
(314, 'RESERVATION Exclude these Zones', 'MODULE_PAYMENT_RESERVATION_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', 6, 4, NULL, '2019-10-11 16:00:19', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(315, 'RESERVATION Sort order of  display.', 'MODULE_PAYMENT_RESERVATION_SORT_ORDER', '1', 'Sort order of RESERVATION display. Lowest is displayed first.', 6, 5, NULL, '2019-10-11 16:00:19', NULL, NULL),
(316, 'RESERVATION Set Order Status', 'MODULE_PAYMENT_RESERVATION_ORDER_STATUS_ID', '3', 'Set the status of orders made with this payment module to this value', 6, 6, NULL, '2019-10-11 16:00:19', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(317, 'Display Name', 'MODULE_PAYMENT_RESERVATION_DISPLAY_NAME', 'Reservations', 'Set the Display name to payment module', 6, 7, NULL, '2019-10-11 16:00:19', NULL, NULL),
(318, 'Image', 'MODULE_PAYMENT_RESERVATION_IMAGE', 'reservation.png', 'Set the Image of payment module', 6, 8, NULL, '2019-10-11 16:00:19', NULL, 'tep_cfg_file_field('),
(324, 'Facebook App ID', 'FB_APP_ID', '', 'Get it here: https://developers.facebook.com/docs/apps/register', 154, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(325, 'Facebook Site Name', 'FB_SITE_NAME', 'osConcert', 'Example- osConcert Tickets', 154, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(326, 'Facebook Url', 'FB_URL', '', 'The web address of the page you want to share', 154, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(327, 'Facebook Type', 'FB_TYPE', 'website', 'Example - website', 154, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(328, 'Facebook Title', 'FB_TITLE', '', 'The title of the page you want to share', 154, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(329, 'Facebook Description', 'FB_DESCRIPTION', '', 'The description of the page you want to share', 154, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(330, 'Facebook Image', 'FB_IMAGE', '', 'The image of the page you want to share - full url address', 154, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(331, 'Facebook Locale', 'FB_LOCALE', 'en_US ', 'The language locality of your business e.g en_US = USA find it here- http://fbdevwiki.com/wiki/Locales', 154, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(332, 'Facebook Layout Size', 'FB_DATA_SIZE', 'large', 'The size of your button/layout', 154, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(333, 'Facebook Layout', 'FB_DATA_LAYOUT', 'button', 'The button layout e.g button, button-count', 154, 10, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(334, 'Allow PopOver Information for BO', 'BO_POPOVER', 'yes', 'Allow Popover information about the order on SOLD seats at the seat plan. Box Office Only (IMPORTANT! disable seat plan cache or everyone will see the info)', 923, 59, '2020-03-21 07:42:48', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(335, 'Customer Email Address', 'ACCOUNT_CUSTOMER_EMAIL', 'true', 'Box Office send email to customer', 5, 4, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(336, 'Facebook Connect', 'FB_CONNECT', '', 'Usually //connect.facebook.net/', 154, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(337, 'Customer Newsletter', 'ACCOUNT_NEWSLETTER', 'false', 'Account Newsletter', 5, 9, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(557, 'GA Enable Multiple Quantity Selection', 'ALT_GA_TEMPLATE', 'yes', 'GA Multiple Quantities or BUY NOW buttons', 19, 50, '2020-04-20 17:46:59', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(558, 'Use Barcode Scanning', 'BARCODE_SCAN', 'yes', 'Use Barcode Scanning App', 920, 50, '2020-04-21 16:22:18', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(559, 'Timespan BEFORE expiry', 'PLUS_TIME', '+10 hours', 'Timespan AFTER concert time BEFORE expiry', 920, 62, '2021-10-23 12:12:30', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"+1 hours\",\"+2 hours\",\"+3 hours\",\"+4 hours\",\"+5 hours\",\"+6 hours\",\"+7 hours\",\"+8 hours\",\"+9 hours\",\"+10 hours\"),'),
(560, 'Timespan BEFORE concert start', 'MINUS_TIME', '-48 hours', 'Timespan BEFORE concert time BEFORE expiry', 920, 63, '2021-10-23 12:12:41', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"-1 hours\",\"-2 hours\",\"-3 hours\",\"-4 hours\",\"-5 hours\",\"-6 hours\",\"-7 hours\",\"-8 hours\",\"-9 hours\",\"-10 hours\",\"-24 hours\",\"-48 hours\"),'),
(562, 'Quick Order Update Email', 'EMAIL_QUICK_ORDER_UPDATE', 'false', 'Allow Quick Order Update Send New Order Confirmation', 917, 50, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(563, 'Box Office Reservation Agent Only', 'BOR_AGENT_ONLY', 'false', 'Allow BOR Agents to see only their own reservations', 923, 8, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(564, 'Force HTTPS', 'FORCE_HTTPS', 'true', 'Force HTTPS when SSL certicate installed', 917, 60, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(565, 'Hide GDPR Links', 'HIDE_DATA_PROTECT', 'false', 'Hide GDPR links in customer_account', 917, 62, '2021-10-16 16:27:42', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(567, 'Orientation', 'ORIENTATION', 'L', 'Orientation of the ticket Portrait or Landscape', 920, 30, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"Portrait\",\"Landscape\"),'),
(568, 'Page Format', 'PAGE_FORMAT', 'custom', 'Ticket Page Size', 920, 8, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"A1\",\"A2\",\"A3\",\"A4\",\"A5\",\"A6\", \"A8\",\"custom\"),'),
(569, 'QR Left Position', 'QR_LEFT_POSITION', '150', 'QR image from left edge of the ticket', 920, 27, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(571, 'Set Status for pseudo CRON Restock', 'CRON_RESTOCK_STATUS', '8', 'Cancel orders with this status when the CRON job runs', 919, 36, '2019-04-15 21:29:19', '2019-10-01 00:00:00', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(572, 'Set Expiry Time pseudo CRON Restock', 'CRON_RESTOCK_EXPIRY', '24', 'Set expiry time in hours', 919, 37, '2018-04-11 08:18:32', '2019-10-01 00:00:00', NULL, NULL),
(573, 'Set Cancelled Status for pseudo CRON Restock', 'CRON_CANX_STATUS', '9', 'Status for cancelled orders', 919, 38, '2019-04-15 21:29:05', '2019-10-01 00:00:00', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(574, 'Send pseudo CRON Cancellation Notification', 'SEND_CRON_CANCEL_NOTIFICATION', 'no', 'Send psuedo CRON Cancellation Notification by Email', 919, 39, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(575, 'Enable Cancel pseudo CRON', 'ENABLE_CANCEL_CRON', 'false', 'Enable pseudo CRON for auto cancel pending orders', 919, 40, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(576, 'Contact Us form link', 'BOX_INFORMATION_CONTACT_LINK', 'contact_us.php', 'Redirect to another contact us form or keep the default osconcert link.', 917, 100, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, NULL),
(577, 'QR Top Position', 'QR_TOP_POSITION', '0', 'QR image from top edge of the ticket', 920, 27, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(880, 'Only display invoice at particular Order Status setting?', 'DISPLAY_PDF_STATUS_ONLY', 'true', 'Would you like to display a PDF invoice link only if the order status has been set as delivered?<br />[ Default = true ]<br />', 927, 1, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(881, 'Order Status', 'PDF_INV_STATUS_SWITCH', 'Delivered', 'Enter the Order Status level you wish the Invoice to be available at - only works if above is True.<br />', 927, 2, '2019-09-27 04:41:05', '2013-02-12 12:00:00', NULL, ''),
(882, 'Invoice Date', 'PDF_INV_DATE', 'order', 'Do you want the invoice date to be today\'s date or the date of order?<br />[ Default = order ]<br />', 927, 3, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'order\', \'today\'),'),
(883, 'Choose Logo', 'PDF_INVOICE_IMAGE', 'images/osconcert.png', 'Path and name of store logo to insert into PDF<br />Max dimensions 600 x 180px<br />Valid formats: jpg, gif, png<br />', 927, 4, '2019-09-27 04:23:50', '2013-02-12 12:00:00', NULL, ''),
(884, 'Image Correction Factor', 'PDF_INV_IMG_CORRECTION', '0.18', 'Adjust value to fine-tune image size.<br />[ Default = 0.18 ]<br />See readme.txt for details.<br />', 927, 5, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(885, 'Set Invoice Font', 'PDF_INV_CORE_FONT', 'Arial', 'Choose a font for the PDF invoice.<br />Arial<br />Times<br />Courier<br />Helvetica<br />', 927, 6, '2019-09-27 07:09:23', '2013-02-12 12:00:00', NULL, 'tep_cfg_select_option(array(\'arial\', \'times\',\'courier\',\'helvetica\'),'),
(886, 'Set Default Text Colour', 'PDF_INV_STANDARD_COLOR', '#000000', 'Choose a hexidecimal colour for normal invoice text.<br />Enter as six character hex number, with or without #<br />', 927, 7, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(887, 'Set Store Name Text Colour', 'PDF_INV_COM_NAME_COLOR', '#000099', 'Choose a hexidecimal colour for the company name.<br />Enter as six character hex number, with or without #<br />', 927, 8, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(888, 'Set Company Address Text Colour', 'PDF_INV_COM_ADDRESS_COLOR', '#424242', 'Choose a hexidecimal colour for the company address &amp; other details.<br />Enter as six character hex number, with or without #<br />', 927, 9, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(889, 'Set Company email Address Text Colour', 'PDF_INV_COM_EMAIL_COLOR', '#009999', 'Choose a hexidecimal colour for the company email address &amp; other details.<br />Enter as six character hex number, with or without #<br />', 927, 10, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(890, 'Set Company Web Address Text Colour', 'PDF_INV_COM_WEB_ADDRESS_COLOR', '#009999', 'Choose a hexidecimal colour for the company web address &amp; other details.<br />Enter as six character hex number, with or without #<br />', 927, 11, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(891, 'Set Invoice Number, ID &amp; Date Text Colour', 'PDF_INV_NUMIDDATE_COLOR', '#990000', 'Choose a hexidecimal colour for the invoice number, id &amp; date.<br />Enter as six character hex number, with or without #<br />', 927, 12, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(892, 'Set Invoice Line &amp; Text Colour', 'PDF_INV_INVLINE_COLOR', '#808080', 'Choose a hexidecimal colour for invoice line &amp; text.<br />Enter as six character hex number, with or without #<br />', 927, 13, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(893, 'Set Invoice Footer Text Colour', 'PDF_INV_FOOTER_COLOR', '#990000', 'Choose a hexidecimal colour for invoice footer text.<br />Enter as six character hex number, with or without #<br />', 927, 14, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(894, 'Set Box Border Colour', 'PDF_INV_BORDER_COLOR', '#666666', 'Choose a hexidecimal colour for the box border colour.<br />Enter as six character hex number, with or without #<br />', 927, 15, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(895, 'Set Box Fill Colour', 'PDF_INV_CELL_COLOR', '#EEEEEE', 'Choose a hexidecimal colour for the box fill colour.<br />Enter as six character hex number, with or without #<br />', 927, 16, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(896, 'Do you want to display a text watermark?', 'PDF_SHOW_WATERMARK', 'false', 'Would you like to display a text watermark through the invoice?<br />[ Default = false ]<br />', 927, 17, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(897, 'Set Watermark Text', 'PDF_INV_WATERMARK_TEXT', 'Copy Invoice', 'Enter text for watermark. [ Default = \"Copy Invoice\" ]<br />', 927, 18, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(898, 'Set Watermark Colour', 'PDF_INV_WATERMARK_COLOR', '#EEEEEE', 'Choose a hexidecimal colour for the watermark. KEEP IT PALE!<br />Enter as six character hex number, with or without #<br />', 927, 19, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(899, 'Footer Text', 'PDF_INV_FOOTER_TEXT', 'Thank you for your order.', 'Enter some text (eg address or marketing) for the invoice footer.<br />', 927, 20, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(900, 'Do you want to display a Tax reference?', 'DISPLAY_PDF_TAX_NUMBER', 'false', 'Would you like to display your VAT / Tax reference on the invoice?<br />[ Default = false ]<br />', 927, 21, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(901, 'Set Tax Number description', 'PDF_TAX_NAME', 'VAT number:', 'Choose a prefix description for your VAT / Tax reference.<br />[ Default = VAT number: ]<br />', 927, 22, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(902, 'Enter Tax Number', 'PDF_TAX_NUMBER', '', 'Enter your VAT / Tax number here.<br />', 927, 23, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(903, 'Remove http:// from the web address?', 'REMOVE_HTTP_WEB_ADDRESS', 'true', 'Would you like to remove http:// (if present) from the web address on the invoice?<br />[ Default = true ]<br />', 927, 24, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(904, 'Show the customer reference number?', 'DISPLAY_CUSTOMER_REFERENCE', 'false', 'Would you like to display the customer reference number on the invoice?<br />[ Default = false ]<br />', 927, 25, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(905, 'Do you want to display order comments?', 'DISPLAY_PDF_COMMENTS', 'false', 'Would you like to display comments linked to this order?<br />[ Default = false ]<br />', 927, 26, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(906, 'Show the products model?', 'DISPLAY_PRODUCT_MODEL', 'false', 'Would you like to display the product model on the invoice?<br />[ Default = false ]<br />', 927, 27, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(907, 'Choose the size of the products model box.', 'PRODUCT_MODEL_BOX_WIDTH', '24', 'If you have chosen to show the products model box how wide do you want it?<br />[ Default = 24 ]<br />', 927, 28, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(908, 'Display currency symbol in product lines?', 'DISPLAY_PROD_LINE_CURRENCY', 'false', 'Would you like to display the currency symbol in the product lines on the invoice?<br />[ Default = false ]<br />', 927, 29, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(909, 'Force PDF download?', 'FORCE_PDF_INVOICE_DOWNLOAD', 'false', 'Choose how you want the generated invoice displayed to the customer.<br />Inline - opens within browser using plugin.<br />[ This is the default value - false ]<br />Download - force browser to offer PDF for download - set to true to enable this feature.<', 927, 30, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(910, 'Do you want to display a page number?', 'DISPLAY_PAGE_NUMBER', 'false', 'Would you like to display the page number in the footer?<br />[ Default = false ]<br />', 927, 31, '2013-02-12 12:00:00', '2013-02-12 12:00:00', '', 'tep_cfg_select_option(array(\'false\', \'true\'),'),
(911, 'Message at Checkout about Discount', 'DISCOUNT_CHECKOUT_MESSAGE', '', 'Enable a Message at Checkout about Discount, no text no message.', 917, 1, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(912, 'Use Text Logo NOT Image', 'TEXT_LOGO', 'yes', 'Enable Text Logo/Brand Name', 1, 53, '2020-04-18 08:14:52', '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'), '),
(913, 'Text Logo Part 1', 'TEXT_LOGO1', 'Jollof N Laugh', 'First part of alternative Text Logo/Brand Name', 1, 54, '2021-10-30 21:01:42', '2019-10-01 00:00:00', NULL, NULL),
(914, 'Text Logo Part 2', 'TEXT_LOGO2', '', 'First part of alternative Text Logo/Brand Name', 1, 55, '2021-10-30 21:01:47', '2019-10-01 00:00:00', NULL, NULL),
(915, 'Disable Featured Categories Overlay', 'DISABLE_OVERLAY', 'yes', 'Disable the overlay on the top of the Featured Categories uploaded image /portfolio', 19, 53, NULL, '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'), '),
(916, 'Subcategory Settings', 'SHOW_SUBCATEGORIES', 'no', 'Enable for projects that are SHOW>plus SUB-CATEGORIES .e.g Stalls and Balcony where these parents are not thr truly MAIN categories', 19, 54, NULL, '2019-10-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'), '),
(917, 'Display Tax/Discount on eTickets', 'SHOW_TICKET_DISCOUNT', 'true', 'Show the Ticket Discount or Tax on the PDF eTickets', 920, 62, '2020-04-21 16:22:23', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(919, 'Meta Title', 'PDF_META_TITLE', 'Invoice', 'Invoice', 924, 20, '2013-02-12 12:00:00', '2013-02-12 12:00:00', NULL, ''),
(920, 'Admin Directory Name', 'LOG_PATH', 'admin', 'Admin Directory Name for payment and other txt logs', 917, 1, '2019-10-01 00:00:00', '2019-10-01 00:00:00', NULL, NULL),
(921, 'Address', 'ACCOUNT_ADDRESS', 'true', 'Display Address in customers account', 5, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(922, 'City', 'ACCOUNT_CITY', 'true', 'Display City in customers account', 5, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(923, 'Post Code/Zip', 'ACCOUNT_POST_CODE', 'true', 'Display Post Code in customers account', 5, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(924, 'Show products SOLD OUT (Out of  Stock)', 'SHOW_GA_SOLDOUT', 'yes', 'Show products with SOLD OUT button or HIDE products from listing altogether', 19, 62, '2021-10-25 06:34:20', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(925, 'Paypal Image', 'MODULE_PAYMENT_PAYPAL_API_IMAGE', 'paypalcards.png', '', 0, 0, NULL, NULL, NULL, NULL),
(932, 'Allow product expiry', 'ALLOW_PRODUCT_EXPIRY', 'no', 'Allowed to set expiry time for individual GA products', 929, 20, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(934, 'Use buttons for Featured Categories', 'FEATURED_CATEGORY_BUTTONS', 'no', 'Use buttons instead of images for Featured Categories', 19, 21, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(935, 'Display Featured Categories under Event Pages', 'SHOW_FEATURED_CATEGORIES', 'false', 'Show Featured Categories under all SHOWS', 19, 2, '2020-06-17 12:25:58', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(936, 'Allow Box Office Blocker', 'BOX_OFFICE_BLOCKING', 'no', 'Allow Box Office Agents to Block Out Seats for Social Distancing purposes. ONLY WORKS WITH SEAT PLAN INTEGRATION NOT DESIGN MODE', 923, 19, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(938, 'Disallow Email Receipts for Box Office Agents', 'NO_BOXOFFICE_EMAIL', 'yes', 'Disallow Email Receipts for Box Office Agents', 923, 21, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(939, 'QR Text Separator', 'SEP', ';', 'QR Text Information Separator character', 920, 111, '2016-09-15 12:25:47', '2010-01-01 00:00:00', NULL, NULL),
(940, 'Hide Search Events', 'HIDE_SEARCH_EVENTS', 'yes', 'Hide Search Events links in navigation', 19, 19, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(946, 'Fill DATE-ID with Date and Time', 'AUTOFILL_DATEID', 'yes', 'Auto-Fill DATE-ID with Date and Time fields', 922, 14, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(947, 'Show the product expiry', 'SHOW_PRODUCT_EXPIRY', 'no', 'Show the product expiry date GA listings', 929, 24, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(948, 'Show EVENTS DATE FORMAT', 'SHOW_EVENTS_DATE_FORMAT', 'yes', 'Show EVENTS_DATE_FORMAT e.g d-mY in Concert Dates', 19, 25, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(949, 'Use CINE settings', 'USE_CINE', 'no', 'Using some CINEMA set up (for CINE project by DEV)', 19, 30, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(950, 'Use CIRCLE buttons', 'USE_CIRCLE_BUTTONS', 'no', 'Using circle buttons in nested categories', 19, 31, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(951, 'Use buttons for nested categories', 'NESTED_CATEGORY_BUTTONS', 'yes', 'Using buttons in nested categories', 19, 32, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(952, 'Show 7Day Navigation Tabs', 'NAVIGATE_7DAY_TABS', 'no', 'Show 7Day Navigation Tabs. Code needs to be set in templates/calendar_tabs.php', 19, 28, '2020-07-14 09:55:15', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(953, 'Allow full category expiry', 'ALLOW_CATEGORY_EXPIRY', 'no', 'Allow full category de-activation with expiry of categories with optional choice to hide de-activated categories', 929, 29, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"yes_and_hide_expired\", \"no\"),'),
(954, 'Display Product Description', 'PRODUCT_LIST_DESCRIPTION', '1', 'Do you want to display the Product Description?', 8, 12, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, NULL),
(955, 'Low Stock Email', 'ADMIN_LOW_STOCK_EMAIL_SENT', '0', 'Send Low Stock Email To Administrator', 9, 100, NULL, NULL, NULL, NULL),
(956, 'Enable Low Stock Alert for GA products', 'ENABLE_LOW_STOCK_ALERT', 'false', 'Enable Low Stock Alert for GA products', 9, 99, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(957, 'Show Data Tools', 'SHOW_ADMIN_DATATOOLS', 'no', 'Show Data Editing Tools for experienced Admins ONLY', 19, 61, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(1032, 'Livestream & Video', 'VIDEO', 'false', 'Livestream & Video', 928, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(1033, 'Account Livestream  Title', 'LIVESTREAM_TITLE', 'Livestream and Video', 'Heading  Title  for the  user  Account', 928, 2, '2020-05-12 04:06:02', NULL, NULL, NULL),
(1034, 'User Account Message Yes Livestream', 'ACCOUNT_MESSAGE_YES', 'Join the livestream...', 'Message in User Account when Livestream is Active', 928, 3, '2020-08-12 03:07:58', NULL, NULL, NULL),
(1035, 'Date ID of Livestream', 'LIVESTREAM_DATE_ID', '', 'Date ID of Livestream Event', 928, 4, '2020-08-12 03:48:16', NULL, NULL, NULL),
(1036, 'Alt. Channel URL', 'CHANNEL_LINK', '', 'Full Channel URL with https', 928, 5, '2020-08-12 03:52:22', NULL, NULL, NULL),
(1037, 'Alt. Livestream URL', 'LIVESTREAM_URL', 'https://www.osconcert.com/livestream', 'Full Livestream URL with https', 928, 6, '2020-08-12 03:17:45', NULL, NULL, NULL),
(1038, 'YouTube Channnel ID ', 'YOUTUBE_CHANNEL_ID', '', 'YouTube Channel ID', 928, 7, '2020-08-12 03:29:06', NULL, NULL, NULL),
(1039, 'You Tube  Livestream URL', 'YOUTUBE_LINK', 'https://www.youtube.com', 'Livestream URL Complete', 928, 8, '2020-08-12 02:51:53', NULL, NULL, NULL),
(1040, 'User Account Message No Livestream', 'ACCOUNT_MESSAGE_NO', 'Sorry No Livestream for this order', 'Message in User Account when Livestream is NOT Active', 928, 9, '2020-08-12 03:07:11', NULL, NULL, NULL),
(1041, 'Show osConcert Help Messages', 'SHOW_OSCONCERT_HELP', 'yes', 'Show osConcert Help Messages around the admin', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1042, 'Categories Hour of expiry', 'TIME_CATEGORIES_EXPIRE', '21', 'How many hours after midnight categories expire in relation to your TimeZone. For DATE expiry only and not DATE AND TIME', 929, 29, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"6\",\"7\",\"8\",\"9\",\"10\", \"11\", \"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\"),'),
(1043, 'Featured Categories Orderby', 'FEATURED_CATEGORIES_ORDERBY', 'concert_date_unix', 'Featured Categories Orderby Date or Sort Order', 19, 80, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'concert_date_unix\', \'sort_order\'),'),
(1044, 'Use Pop Up Modal', 'USE_POPUP', 'no', 'Use Pop Up Modal for Alternative Shopping Projects Only', 917, 51, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1045, 'Show Orders Products Status (admin check) ', 'SHOW_ORDERS_PRODUCTS_STATUS', 'no', 'Admin check orders products status - Edit>Order', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1046, 'UNIX expire date and time', 'EXPIRE_DATE_TIME', 'no', 'When you want to expire categories by date and time e.g when using session time 1pm =1300', 929, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1047, 'BO Design Screen size', 'SCREEN_EDIT', 'desktop', 'If you have to design on a smaller screen try this with ipad setting', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'desktop\', \'ipad\'),'),
(1048, 'Allow BO Design', 'ALLOW_BO_DESIGN', 'yes', 'Allow a Box Office User to edit the seat plan design', 923, 15, '2021-10-17 13:12:47', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1049, 'Activate Design Mode', 'DESIGN_MODE', 'yes', 'Activate Design Mode so that BO can edit and display the Front End Design', 923, 14, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1050, 'Allow BO Refund', 'ALLOW_BO_REFUND', 'no', 'Allow a Box Office User to make Front End Visual Refunds with BO Refund feature', 923, 15, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1051, 'Enable Design Grid', 'SET_GRID_BACKGROUND', 'no', 'Enable basic 20/20 Background Grid', 923, 15, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1052, 'Map URL for Contact Us Page', 'CONTACT_MAP_URL', '', 'Map URL like Google Map', 917, 2, '2020-05-12 04:06:02', NULL, NULL, NULL),
(1053, 'Design Stage Name', 'DESIGN_STAGE_NAME', 'Stage', 'Design Stage Name Overlay', 923, 2, '2020-05-12 04:06:02', NULL, NULL, NULL),
(1054, 'Design Snap', 'DESIGN_SNAP', '2', 'Design Snap for mouse movements', 923, 4, '2020-05-12 04:06:02', NULL, NULL, 'tep_cfg_select_option(array(\"1\",\"2\",\"3\",\"4\",\"5\", \"10\", \"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"50\"),'),
(1055, 'Set Conditions at Payment', 'PAYMENT_SET_TERMS', 'no', 'If you require a strict reminder and checkbox with terms or conditions AFTER selecting payment method and before moving to the confirmation page. Possible to remind customers about a COVID pass for example ', 917, 150, '2021-10-16 16:27:36', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1056, 'Ticket Background from left', 'BG_IMAGE_LEFT', '110', 'Adjustment to move the ticket background image from the left side', 920, 26, '2021-10-30 21:10:24', '2010-01-01 00:00:00', NULL, NULL),
(1057, 'Ticket Background from top', 'BG_IMAGE_TOP', '30', 'Adjustment to move the ticket background image from the uppermost top', 920, 26, '2021-10-30 21:06:34', '2010-01-01 00:00:00', NULL, NULL),
(1058, 'Ticket Background Width', 'BG_IMAGE_WIDTH', '70', 'Width of your ticket background image, 180 is full width default settings', 920, 24, '2021-10-30 21:08:44', '2010-01-01 00:00:00', NULL, NULL),
(1059, 'Ticket Background Height', 'BG_IMAGE_HEIGHT', '40', 'Height of your ticket background image, 70 is full height default  settings', 920, 25, '2021-10-23 14:20:54', '2010-01-01 00:00:00', NULL, NULL),
(1060, 'Display Featured Categories on HOMEPAGE', 'SHOW_MAINPAGE_FEATURED_CATEGORIES', 'true', 'Show Featured Categories on Home Page or disable here', 19, 20, '2010-01-01 00:00:00', '2010-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(1061, 'Display Contact Form on HOMEPAGE', 'SHOW_MAINPAGE_CONTACT_FORM', 'false', 'Show Contact Form at the bottom of the HOMEPAGE', 917, 20, '2010-01-01 00:00:00', '2020-01-01 00:00:00', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),'),
(1062, 'Set ALL Price Headings', 'ALL_INVOICE_HEADINGS', 'yes', 'If you want only the products price itemized in the totals, remove the sub-pricing totals here.', 927, 150, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1063, 'Enable Stripe Payments', 'MODULE_PAYMENT_STRIPESCA_STATUS', 'True', 'Do you want to accept Stripe payments?', 6, 10, NULL, '2021-10-16 11:19:52', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), '),
(1064, 'Payment Zone', 'MODULE_PAYMENT_STRIPESCA_ZONE', '2', 'If a zone is selected, only enable this payment method for that zone.', 6, 20, NULL, '2021-10-16 11:19:52', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(1065, 'Exclude these Countries', 'MODULE_PAYMENT_STRIPESCA_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', 6, 21, NULL, '2021-10-16 11:19:52', 'tep_get_zone_except_country', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_STRIPESCA_ZONE,'),
(1066, 'Exclude these Zones', 'MODULE_PAYMENT_STRIPESCA_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', 6, 22, NULL, '2021-10-16 11:19:52', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(1067, 'Sort order of display.', 'MODULE_PAYMENT_STRIPESCA_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 30, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1068, 'Set Initial Order Status', 'MODULE_PAYMENT_STRIPESCA_ORDER_STATUS_ID', '1', 'Set the initial status of orders made with this payment module to this value - do not use Delivered', 6, 40, NULL, '2021-10-16 11:19:52', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(1069, 'Set Failed Order Status', 'MODULE_PAYMENT_STRIPESCA_CANX_ORDER_STATUS_ID', '10', 'Set the status of failed/cancelled orders made with this payment module to this value', 6, 40, NULL, '2021-10-16 11:19:52', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(1070, 'Set Completed Order Status', 'MODULE_PAYMENT_STRIPESCA_COMP_ORDER_STATUS_ID', '3', 'Set the status of succcesful orders made with this payment module to this value', 6, 40, NULL, '2021-10-16 11:19:52', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(1071, 'Transaction Mode', 'MODULE_PAYMENT_STRIPESCA_TESTMODE', 'Production', 'Transaction mode used for processing orders', 6, 50, NULL, '2021-10-16 11:19:52', NULL, 'tep_cfg_select_option(array(\'Test\', \'Production\'), '),
(1072, 'Testing Secret Key', 'MODULE_PAYMENT_STRIPESCA_TESTING_SECRET_KEY', 'sk_test_5R4BqLLl8JglbXAvXAXZbr87', 'Testing Secret Key - obtainable in your Stripe dashboard.', 6, 61, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1073, 'Testing Publishable Key', 'MODULE_PAYMENT_STRIPESCA_TESTING_PUBLISHABLE_KEY', 'pk_test_mZdFgeJEONEvPvDuYcB3zYWq', 'Testing Publishable Key  - obtainable in your Stripe dashboard.', 6, 60, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1074, 'Live Secret key', 'MODULE_PAYMENT_STRIPESCA_LIVE_SECRET_KEY', '', 'Live Secret key  - obtainable in your Stripe dashboard.', 6, 63, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1075, 'Live Publishable key', 'MODULE_PAYMENT_STRIPESCA_LIVE_PUBLISHABLE_KEY', 'pk_live_51JlAEWC26O1rh9i1H8yCf8UyeTsGfYDVBA6DVGWvKIUUZtth7Zp2nH8GDEOdWuOXLdPxRMZPJeBnFQG4yg9CsCJK00UL9XOyIn', 'Live Publishable key  - obtainable in your Stripe dashboard.', 6, 62, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1076, 'Webhook key', 'MODULE_PAYMENT_STRIPESCA_WEBHOOK_KEY', '', 'Webhook key  - obtainable in your Stripe dashboard.', 6, 64, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1077, 'Image', 'MODULE_PAYMENT_STRIPESCA_IMAGE', 'stripesca.png', 'Set the Image of payment module', 6, 70, NULL, '2021-10-16 11:19:52', NULL, 'tep_cfg_file_field('),
(1078, 'Display Name', 'MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME', 'Stripe Secure Payments', 'Set the Display name to payment module', 6, 80, NULL, '2021-10-16 11:19:52', NULL, NULL),
(1079, 'Left Symbol', 'LEFT_SYMBOL', '£', 'Left Symbol', 920, 0, NULL, NULL, NULL, NULL),
(1080, 'Display Service Fee', 'MODULE_ORDER_TOTAL_SERVICE_FEE_STATUS', 'true', 'Do you want to enable the service fee module?', 6, 1, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1081, 'Sort Order', 'MODULE_ORDER_TOTAL_SERVICE_FEE_SORT_ORDER', '10', 'Sort order of display.', 6, 2, NULL, '2021-10-23 07:23:49', NULL, NULL),
(1082, 'Disable If Coupon Used', 'MODULE_ORDER_TOTAL_SERVICE_FEE_DISABLE_WITH_COUPON', 'true', 'Do you want to disable the service fee module if a discount coupon is being used by the user?', 6, 3, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1083, 'Fee Rate Type', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE', 'flat rate', 'Choose the type of fee - percentage or flat rate', 6, 4, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'percentage\', \'flat rate\'), '),
(1084, 'Booking fee', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATES', '0.75', 'Fee per ticket', 6, 5, NULL, '2021-10-23 07:23:49', NULL, NULL),
(1085, 'Include Shipping', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_SHIPPING', 'false', 'Include Shipping in calculation', 6, 6, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1086, 'Include Tax', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_TAX', 'false', 'Include Tax in calculation.', 6, 7, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1087, 'Calculate Tax', 'MODULE_ORDER_TOTAL_SERVICE_FEE_CALC_TAX', 'true', 'Re-calculate Tax on fee amount.', 6, 8, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1088, 'Exempt categories', 'MODULE_ORDER_TOTAL_SERVICE_FEE_EXEMPT_CAT', '', 'A comma delimited list of categories that you DO NOT wish the fee to be charged on e.g. 34,78,99 If you list a parent category all children will also be excluded', 6, 10, NULL, '2021-10-23 07:23:49', NULL, NULL),
(1089, 'Exempt Box Office sales?', 'MODULE_ORDER_TOTAL_SERVICE_FEE_BOX_OFFICE', 'true', '', 6, 20, NULL, '2021-10-23 07:23:49', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1090, 'Tax Class', 'MODULE_ORDER_TOTAL_SERVICE_FEE_TAX_CLASS', '0', 'Use the following tax class on the booking fee.', 6, 7, NULL, '2021-10-23 07:23:49', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
(1091, 'Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', 6, 1, NULL, '2021-10-23 07:24:01', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1092, 'Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '50', 'Sort order of display.', 6, 2, NULL, '2021-10-23 07:24:01', NULL, NULL),
(1093, 'Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', 6, 1, NULL, '2021-10-23 07:24:17', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'), '),
(1094, 'Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '100', 'Sort order of display.', 6, 2, NULL, '2021-10-23 07:24:17', NULL, NULL),
(1095, 'Enable Box Office module', 'MODULE_PAYMENT_BOXOFFICE_STATUS', 'True', 'Do you want to accept Box Office payments?', 6, 1, NULL, '2021-10-23 07:27:28', NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), '),
(1096, 'Payment Zone', 'MODULE_PAYMENT_BOXOFFICE_ZONE', '4', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2021-10-23 07:27:28', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(1097, 'Box Office Exclude these Countries', 'MODULE_PAYMENT_BOXOFFICE_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', 6, 3, NULL, '2021-10-23 07:27:28', 'tep_get_zone_except_country', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_BOXOFFICE_ZONE,'),
(1098, 'Box Office Exclude these Zones', 'MODULE_PAYMENT_BOXOFFICE_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', 6, 4, NULL, '2021-10-23 07:27:28', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
(1099, 'Sort order of display.', 'MODULE_PAYMENT_BOXOFFICE_SORT_ORDER', '4', 'Sort order of Box Office display. Lowest is displayed first.', 6, 5, NULL, '2021-10-23 07:27:28', NULL, NULL),
(1100, 'Box Office Set Order Status', 'MODULE_PAYMENT_BOXOFFICE_ORDER_STATUS_ID', '3', 'Set the status of orders made with this payment module to this value', 6, 6, NULL, '2021-10-23 07:27:28', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
(1101, 'Display Name', 'MODULE_PAYMENT_BOXOFFICE_DISPLAY_NAME', 'Box Office Staff', 'Set the Display name to payment module', 6, 7, NULL, '2021-10-23 07:27:28', NULL, NULL),
(1102, 'Image', 'MODULE_PAYMENT_BOXOFFICE_IMAGE', 'boxoffice', 'Set the Image of payment module', 6, 8, NULL, '2021-10-23 07:27:28', NULL, 'tep_cfg_file_field(');

-- --------------------------------------------------------

--
-- Table structure for table `configuration_group`
--

CREATE TABLE `configuration_group` (
  `configuration_group_id` int(11) NOT NULL,
  `configuration_group_title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `configuration_group_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `visible` int(1) DEFAULT 1,
  `configuration_access_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuration_group`
--

INSERT INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`, `configuration_access_key`) VALUES
(1, 'My Store', 'General information about my store', 1, 1, 'Shop Settings General'),
(2, 'Values', 'The minimum values for functions / data', 2, 1, 'Shop Settings Advanced Max/Min Values'),
(4, 'Images', 'Image parameters', 4, 1, 'Shop Settings Images'),
(5, 'Customer Details', 'Customer account configuration', 5, 1, 'Customers Advanced'),
(6, 'Module Options', 'Hidden from configuration', 6, 1, ''),
(7, 'Delivery', 'Shipping options available at my store', 7, 1, 'Products Shipping Options'),
(8, 'Product Listing', 'Product Listing    configuration options', 8, 1, 'Products Advanced Listing'),
(9, 'Stock', 'Stock configuration options', 9, 1, 'Products Advanced Stock'),
(10, 'Logging', 'Logging configuration options', 10, 1, 'Shop Settings Advanced Logging'),
(11, 'Cache', 'Cache configuration options', 11, 1, 'Shop Settings Advanced Cache'),
(12, 'Email Settings', 'Email Settings', 0, 1, 'Shop Settings Advanced Email Settings'),
(13, 'Download', 'Downloadable products options', 13, 1, 'Shop Settings Advanced Download'),
(14, 'GZip Compression', 'GZip compression options', 14, 1, 'Products Advanced GZip Compression'),
(15, 'Sessions', 'Session options', 15, 1, 'Shop Settings Advanced Sessions'),
(16, 'Site Maintenance', 'Site Maintenance Options', 16, 1, 'Shop Settings Emergency Processes'),
(17, 'Site Maintenance', 'Site Maintenance Options', 17, 1, 'shop settings emergency processes site maintenance wizard'),
(18, 'Site Maintenance', 'Site Maintenance Options', 18, 1, 'shop settings emergency processes emergency support'),
(19, 'Category Products', 'Category Products settings', 19, 1, 'Products Advanced Category Products'),
(112, 'HtmlArea', 'HTMLArea 1.7 Options', 15, 1, 'Shop Settings Advanced HtmlArea'),
(150, 'Product Advanced', 'Product Listing Options', 16, 1, 'Products Advanced'),
(151, 'My Store Advanced', 'Advanced Information about store', 17, 1, 'Shop Settings Advanced'),
(152, 'Tax Options', 'Tax Decimal places and display', 18, 1, 'Payment Tax'),
(153, 'Payment Options', 'General Payment options', 16, 1, 'Payment General'),
(154, 'Marketing Options', 'General Marketing options', 17, 1, 'Marketing Options'),
(202, 'geoIP', 'Payment vertification based on IP of customer', 16, 1, 'Payment Advanced geoIP'),
(407, 'Help Manuals', 'List of Help Manuals', 20, 1, 'Help'),
(902, 'Wallet', 'Payment_process', 19, 1, 'Payment Advanced Wallet'),
(904, 'Surveys', 'Survey Options', 13, 1, 'Marketing Surveys'),
(905, 'Free Checkout', 'Set Free Checkout', 14, 1, 'Products Advanced Free Checkout'),
(906, 'Reports', 'Report Options', 15, 1, 'Shop Settings Advanced Reports'),
(917, 'osConcert Settings', 'osConcert Settings', 0, 1, 'Shop Settings Advanced osConcert Settings'),
(919, 'Cancel pseudo CRON', 'Cancel pseudo CRON', 5, 1, 'Cancel CRON'),
(920, 'eTicket Settings', 'eTicket Settings', 0, 1, 'Shop Settings Advanced eTicket Settings'),
(921, 'MailerLIte', 'MailerLIte', 0, 1, 'Marketing Mailerlite'),
(922, 'Seat Plan', 'Seat Plan settings', 0, 1, 'Concert Details Seat Plan'),
(923, 'Box Office', 'Box Office settings', 0, 1, 'Concert Details Box Office'),
(924, 'Email Template', 'Product Email Messages', 0, 1, 'Marketing Email Template'),
(927, 'PDF Invoice', 'PDF Invoice', 0, 1, 'Marketing PDF Invoice'),
(928, 'Video', 'Video', 0, 1, 'Marketing Video'),
(929, 'Events Expiry', 'Events Expiry', 0, 1, 'Products Advanced Events Expiry');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `countries_id` int(11) NOT NULL,
  `countries_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `countries_iso_code_2` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `countries_iso_code_3` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address_format_id` int(11) NOT NULL DEFAULT 0,
  `country_code` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countries_id`, `countries_name`, `countries_iso_code_2`, `countries_iso_code_3`, `address_format_id`, `country_code`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', 1, 93),
(2, 'Albania', 'AL', 'ALB', 1, 355),
(3, 'Algeria', 'DZ', 'DZA', 1, 213),
(4, 'American Samoa', 'AS', 'ASM', 1, 684),
(5, 'Andorra', 'AD', 'AND', 1, 376),
(6, 'Angola', 'AO', 'AGO', 1, 244),
(7, 'Anguilla', 'AI', 'AIA', 1, 1264),
(8, 'Antarctica', 'AQ', 'ATA', 1, 67212),
(9, 'Antigua and Barbuda', 'AG', 'ATG', 1, 268),
(10, 'Argentina', 'AR', 'ARG', 1, 54),
(11, 'Armenia', 'AM', 'ARM', 1, 374),
(12, 'Aruba', 'AW', 'ABW', 1, 297),
(13, 'Australia', 'AU', 'AUS', 1, 61),
(14, 'Austria', 'AT', 'AUT', 5, 43),
(15, 'Azerbaijan', 'AZ', 'AZE', 1, 994),
(16, 'Bahamas', 'BS', 'BHS', 1, 1242),
(17, 'Bahrain', 'BH', 'BHR', 1, 973),
(18, 'Bangladesh', 'BD', 'BGD', 1, 880),
(19, 'Barbados', 'BB', 'BRB', 1, 1246),
(20, 'Belarus', 'BY', 'BLR', 1, 375),
(21, 'Belgium', 'BE', 'BEL', 1, 32),
(22, 'Belize', 'BZ', 'BLZ', 1, 501),
(23, 'Benin', 'BJ', 'BEN', 1, 229),
(24, 'Bermuda', 'BM', 'BMU', 1, 1441),
(25, 'Bhutan', 'BT', 'BTN', 1, 975),
(26, 'Bolivia', 'BO', 'BOL', 1, 591),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1, 387),
(28, 'Botswana', 'BW', 'BWA', 1, 267),
(29, 'Bouvet Island', 'BV', 'BVT', 1, 0),
(30, 'Brazil', 'BR', 'BRA', 1, 55),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', 1, 1284),
(32, 'Brunei Darussalam', 'BN', 'BRN', 1, 673),
(33, 'Bulgaria', 'BG', 'BGR', 1, 359),
(34, 'Burkina Faso', 'BF', 'BFA', 1, 226),
(35, 'Burundi', 'BI', 'BDI', 1, 257),
(36, 'Cambodia', 'KH', 'KHM', 1, 855),
(37, 'Cameroon', 'CM', 'CMR', 1, 237),
(38, 'Canada', 'CA', 'CAN', 1, 1),
(39, 'Cape Verde', 'CV', 'CPV', 1, 238),
(40, 'Cayman Islands', 'KY', 'CYM', 1, 1345),
(41, 'Central African Republic', 'CF', 'CAF', 1, 236),
(42, 'Chad', 'TD', 'TCD', 1, 235),
(43, 'Chile', 'CL', 'CHL', 1, 56),
(44, 'China', 'CN', 'CHN', 1, 86),
(45, 'Christmas Island', 'CX', 'CXR', 1, 6724),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1, 6722),
(47, 'Colombia', 'CO', 'COL', 1, 57),
(48, 'Comoros', 'KM', 'COM', 1, 269),
(49, 'Congo', 'CG', 'COG', 1, 243),
(50, 'Cook Islands', 'CK', 'COK', 1, 682),
(51, 'Costa Rica', 'CR', 'CRI', 1, 506),
(52, 'Cote D\'Ivoire', 'CI', 'CIV', 1, 225),
(53, 'Croatia', 'HR', 'HRV', 1, 385),
(54, 'Cuba', 'CU', 'CUB', 1, 53),
(55, 'Cyprus', 'CY', 'CYP', 1, 357),
(56, 'Czech Republic', 'CZ', 'CZE', 1, 420),
(57, 'Denmark', 'DK', 'DNK', 1, 45),
(58, 'Djibouti', 'DJ', 'DJI', 1, 253),
(59, 'Dominica', 'DM', 'DMA', 1, 1767),
(60, 'Dominican Republic', 'DO', 'DOM', 1, 1809),
(61, 'East Timor', 'TP', 'TMP', 1, 670),
(62, 'Ecuador', 'EC', 'ECU', 1, 593),
(63, 'Egypt', 'EG', 'EGY', 1, 20),
(64, 'El Salvador', 'SV', 'SLV', 1, 503),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', 1, 240),
(66, 'Eritrea', 'ER', 'ERI', 1, 291),
(67, 'Estonia', 'EE', 'EST', 1, 372),
(68, 'Ethiopia', 'ET', 'ETH', 1, 251),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1, 500),
(70, 'Faroe Islands', 'FO', 'FRO', 1, 298),
(71, 'Fiji', 'FJ', 'FJI', 1, 679),
(72, 'Finland', 'FI', 'FIN', 1, 358),
(73, 'France', 'FR', 'FRA', 1, 33),
(74, 'France  Metropolitan', 'FX', 'FXX', 1, 0),
(75, 'French Guiana', 'GF', 'GUF', 1, 594),
(76, 'French Polynesia', 'PF', 'PYF', 1, 689),
(77, 'French Southern Territories', 'TF', 'ATF', 1, 596),
(78, 'Gabon', 'GA', 'GAB', 1, 241),
(79, 'Gambia', 'GM', 'GMB', 1, 220),
(80, 'Georgia', 'GE', 'GEO', 1, 995),
(81, 'Germany', 'DE', 'DEU', 5, 49),
(82, 'Ghana', 'GH', 'GHA', 1, 233),
(83, 'Gibraltar', 'GI', 'GIB', 1, 350),
(84, 'Greece', 'GR', 'GRC', 1, 30),
(85, 'Greenland', 'GL', 'GRL', 1, 299),
(86, 'Grenada', 'GD', 'GRD', 1, 1473),
(87, 'Guadeloupe', 'GP', 'GLP', 1, 590),
(88, 'Guam', 'GU', 'GUM', 1, 1671),
(89, 'Guatemala', 'GT', 'GTM', 1, 502),
(90, 'Guinea', 'GN', 'GIN', 1, 224),
(91, 'Guinea-bissau', 'GW', 'GNB', 1, 245),
(92, 'Guyana', 'GY', 'GUY', 1, 592),
(93, 'Haiti', 'HT', 'HTI', 1, 509),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1, 0),
(95, 'Honduras', 'HN', 'HND', 1, 504),
(96, 'Hong Kong', 'HK', 'HKG', 1, 852),
(97, 'Hungary', 'HU', 'HUN', 1, 36),
(98, 'Iceland', 'IS', 'ISL', 1, 354),
(99, 'India', 'IN', 'IND', 1, 91),
(100, 'Indonesia', 'ID', 'IDN', 1, 62),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1, 98),
(102, 'Iraq', 'IQ', 'IRQ', 1, 964),
(103, 'Ireland', 'IE', 'IRL', 1, 353),
(104, 'Israel', 'IL', 'ISR', 1, 972),
(105, 'Italy', 'IT', 'ITA', 1, 39),
(106, 'Jamaica', 'JM', 'JAM', 1, 1876),
(107, 'Japan', 'JP', 'JPN', 1, 81),
(108, 'Jordan', 'JO', 'JOR', 1, 962),
(109, 'Kazakhstan', 'KZ', 'KAZ', 1, 7),
(110, 'Kenya', 'KE', 'KEN', 1, 254),
(111, 'Kiribati', 'KI', 'KIR', 1, 686),
(112, 'Korea Democratic People\'s Republic of', 'KP', 'PRK', 1, 850),
(113, 'Korea Republic of', 'KR', 'KOR', 1, 82),
(114, 'Kuwait', 'KW', 'KWT', 1, 965),
(115, 'Kyrgyzstan', 'KG', 'KGZ', 1, 7),
(116, 'Lao People\'s Democratic Republic', 'LA', 'LAO', 1, 856),
(117, 'Latvia', 'LV', 'LVA', 1, 371),
(118, 'Lebanon', 'LB', 'LBN', 1, 961),
(119, 'Lesotho', 'LS', 'LSO', 1, 266),
(120, 'Liberia', 'LR', 'LBR', 1, 231),
(121, 'Libya', 'LY', 'LBY', 1, 218),
(122, 'Liechtenstein', 'LI', 'LIE', 1, 423),
(123, 'Lithuania', 'LT', 'LTU', 1, 370),
(124, 'Luxembourg', 'LU', 'LUX', 1, 352),
(125, 'Macau', 'MO', 'MAC', 1, 853),
(126, 'Macedonia', 'MK', 'MKD', 1, 389),
(127, 'Madagascar', 'MG', 'MDG', 1, 261),
(128, 'Malawi', 'MW', 'MWI', 1, 265),
(129, 'Malaysia', 'MY', 'MYS', 1, 60),
(130, 'Maldives', 'MV', 'MDV', 1, 960),
(131, 'Mali', 'ML', 'MLI', 1, 223),
(132, 'Malta', 'MT', 'MLT', 1, 356),
(133, 'Marshall Islands', 'MH', 'MHL', 1, 692),
(134, 'Martinique', 'MQ', 'MTQ', 1, 596),
(135, 'Mauritania', 'MR', 'MRT', 1, 222),
(136, 'Mauritius', 'MU', 'MUS', 1, 230),
(137, 'Mayotte', 'YT', 'MYT', 1, 269),
(138, 'Mexico', 'MX', 'MEX', 1, 52),
(139, 'Micronesia Federated States of', 'FM', 'FSM', 1, 691),
(140, 'Moldova Republic of', 'MD', 'MDA', 1, 373),
(141, 'Monaco', 'MC', 'MCO', 1, 377),
(142, 'Mongolia', 'MN', 'MNG', 1, 976),
(143, 'Montserrat', 'MS', 'MSR', 1, 1664),
(144, 'Morocco', 'MA', 'MAR', 1, 212),
(145, 'Mozambique', 'MZ', 'MOZ', 1, 258),
(146, 'Myanmar', 'MM', 'MMR', 1, 95),
(147, 'Namibia', 'NA', 'NAM', 1, 264),
(148, 'Nauru', 'NR', 'NRU', 1, 674),
(149, 'Nepal', 'NP', 'NPL', 1, 977),
(150, 'Netherlands', 'NL', 'NLD', 1, 31),
(151, 'Netherlands Antilles', 'AN', 'ANT', 1, 599),
(152, 'New Caledonia', 'NC', 'NCL', 1, 687),
(153, 'New Zealand', 'NZ', 'NZL', 1, 64),
(154, 'Nicaragua', 'NI', 'NIC', 1, 505),
(155, 'Niger', 'NE', 'NER', 1, 227),
(156, 'Nigeria', 'NG', 'NGA', 1, 234),
(157, 'Niue', 'NU', 'NIU', 1, 683),
(158, 'Norfolk Island', 'NF', 'NFK', 1, 672),
(159, 'Northern Mariana Islands', 'MP', 'MNP', 1, 1670),
(160, 'Norway', 'NO', 'NOR', 1, 47),
(161, 'Oman', 'OM', 'OMN', 1, 968),
(162, 'Pakistan', 'PK', 'PAK', 1, 92),
(163, 'Palau', 'PW', 'PLW', 1, 680),
(164, 'Panama', 'PA', 'PAN', 1, 507),
(165, 'Papua New Guinea', 'PG', 'PNG', 1, 675),
(166, 'Paraguay', 'PY', 'PRY', 1, 595),
(167, 'Peru', 'PE', 'PER', 1, 51),
(168, 'Philippines', 'PH', 'PHL', 1, 63),
(169, 'Pitcairn', 'PN', 'PCN', 1, 872),
(170, 'Poland', 'PL', 'POL', 1, 48),
(171, 'Portugal', 'PT', 'PRT', 1, 351),
(172, 'Puerto Rico', 'PR', 'PRI', 1, 787),
(173, 'Qatar', 'QA', 'QAT', 1, 974),
(174, 'Reunion', 'RE', 'REU', 1, 262),
(175, 'Romania', 'RO', 'ROM', 1, 40),
(176, 'Russian Federation', 'RU', 'RUS', 1, 7),
(177, 'Rwanda', 'RW', 'RWA', 1, 250),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1, 1869),
(179, 'Saint Lucia', 'LC', 'LCA', 1, 1758),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1, 1784),
(181, 'Samoa', 'WS', 'WSM', 1, 685),
(182, 'San Marino', 'SM', 'SMR', 1, 378),
(183, 'Sao Tome and Principe', 'ST', 'STP', 1, 239),
(184, 'Saudi Arabia', 'SA', 'SAU', 1, 966),
(185, 'Serbia and Montenegro', 'CS', 'SCG', 1, 381),
(186, 'Seychelles', 'SC', 'SYC', 1, 248),
(187, 'Sierra Leone', 'SL', 'SLE', 1, 232),
(188, 'Singapore', 'SG', 'SGP', 4, 65),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1, 421),
(190, 'Slovenia', 'SI', 'SVN', 1, 386),
(191, 'Solomon Islands', 'SB', 'SLB', 1, 677),
(192, 'Somalia', 'SO', 'SOM', 1, 252),
(193, 'South Africa', 'ZA', 'ZAF', 1, 27),
(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1, 0),
(195, 'Spain', 'ES', 'ESP', 3, 34),
(196, 'Sri Lanka', 'LK', 'LKA', 1, 94),
(197, 'St. Helena', 'SH', 'SHN', 1, 290),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1, 508),
(199, 'Sudan', 'SD', 'SDN', 1, 249),
(200, 'Suriname', 'SR', 'SUR', 1, 597),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1, 47),
(202, 'Swaziland', 'SZ', 'SWZ', 1, 268),
(203, 'Sweden', 'SE', 'SWE', 1, 46),
(204, 'Switzerland', 'CH', 'CHE', 1, 41),
(205, 'Syrian Arab Republic', 'SY', 'SYR', 1, 963),
(206, 'Taiwan', 'TW', 'TWN', 1, 886),
(207, 'Tajikistan', 'TJ', 'TJK', 1, 7),
(208, 'Tanzania United Republic of', 'TZ', 'TZA', 1, 255),
(209, 'Thailand', 'TH', 'THA', 1, 66),
(210, 'Togo', 'TG', 'TGO', 1, 228),
(211, 'Tokelau', 'TK', 'TKL', 1, 690),
(212, 'Tonga', 'TO', 'TON', 1, 676),
(213, 'Trinidad and Tobago', 'TT', 'TTO', 1, 1868),
(214, 'Tunisia', 'TN', 'TUN', 1, 216),
(215, 'Turkey', 'TR', 'TUR', 1, 90),
(216, 'Turkmenistan', 'TM', 'TKM', 1, 993),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', 1, 1649),
(218, 'Tuvalu', 'TV', 'TUV', 1, 688),
(219, 'Uganda', 'UG', 'UGA', 1, 256),
(220, 'Ukraine', 'UA', 'UKR', 1, 380),
(221, 'United Arab Emirates', 'AE', 'ARE', 1, 971),
(222, 'United Kingdom', 'GB', 'GBR', 1, 44),
(223, 'United States', 'US', 'USA', 2, 1),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1, 0),
(225, 'Uruguay', 'UY', 'URY', 1, 598),
(226, 'Uzbekistan', 'UZ', 'UZB', 1, 7),
(227, 'Vanuatu', 'VU', 'VUT', 1, 678),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1, 39),
(229, 'Venezuela', 'VE', 'VEN', 1, 58),
(230, 'Viet Nam', 'VN', 'VNM', 1, 84),
(231, 'Virgin Islands (British)', 'VG', 'VGB', 1, 284),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1, 340),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1, 681),
(234, 'Western Sahara', 'EH', 'ESH', 1, 685),
(235, 'Yemen', 'YE', 'YEM', 1, 967),
(236, 'Yugoslavia', 'YU', 'YUG', 1, 381),
(237, 'Zaire', 'ZR', 'ZAR', 1, 243),
(238, 'Zambia', 'ZM', 'ZMB', 1, 260),
(239, 'Zimbabwe', 'ZW', 'ZWE', 1, 263),
(240, 'Congo -Democractic Republic', 'CD', 'COD', 1, 242),
(241, 'Senegal', 'SN', 'SEN', 1, 221),
(999, 'Box Office', 'BO', 'BOP', 1, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `coupon_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coupon_amount` decimal(8,4) NOT NULL,
  `coupon_minimum_order` decimal(10,4) NOT NULL,
  `coupon_start_date` datetime DEFAULT NULL,
  `coupon_expire_date` datetime DEFAULT NULL,
  `uses_per_coupon` int(5) NOT NULL DEFAULT 1,
  `uses_per_user` int(5) NOT NULL DEFAULT 0,
  `uses_per_order` int(5) NOT NULL,
  `restrict_to_products` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `restrict_to_categories` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `restrict_to_customers` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `coupon_active` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `coupon_tax_class_id` int(11) NOT NULL DEFAULT 0,
  `coupon_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'U',
  `orders_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupons_description`
--

CREATE TABLE `coupons_description` (
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `coupon_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coupon_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_discount_email`
--

CREATE TABLE `coupon_discount_email` (
  `discount_coupon_id` int(11) UNSIGNED NOT NULL,
  `coupon_id` int(11) UNSIGNED DEFAULT NULL,
  `discount_coupon_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `amount` decimal(8,4) DEFAULT NULL,
  `tax` int(11) UNSIGNED DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_email_track`
--

CREATE TABLE `coupon_email_track` (
  `unique_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `customer_id_sent` int(11) NOT NULL DEFAULT 0,
  `sent_firstname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sent_lastname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailed_to` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_gv_customer`
--

CREATE TABLE `coupon_gv_customer` (
  `customer_id` int(5) NOT NULL DEFAULT 0,
  `amount` decimal(8,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_gv_queue`
--

CREATE TABLE `coupon_gv_queue` (
  `unique_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL DEFAULT 0,
  `order_id` int(5) NOT NULL DEFAULT 0,
  `amount` decimal(8,4) NOT NULL,
  `date_created` datetime NOT NULL,
  `ipaddr` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `release_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_redeem_track`
--

CREATE TABLE `coupon_redeem_track` (
  `unique_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `redeem_date` datetime NOT NULL,
  `redeem_ip` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `email_redeem_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_season_customer`
--

CREATE TABLE `coupon_season_customer` (
  `customer_id` int(5) NOT NULL DEFAULT 0,
  `amount` int(5) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_season_queue`
--

CREATE TABLE `coupon_season_queue` (
  `season_queue_id` int(6) NOT NULL,
  `customer_id` int(6) NOT NULL,
  `order_id` int(6) NOT NULL,
  `amount` int(6) NOT NULL,
  `flag` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_season_track`
--

CREATE TABLE `coupon_season_track` (
  `unique_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currencies_id` int(11) NOT NULL,
  `title` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `symbol_left` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol_right` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_point` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `thousands_point` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_places` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currencies_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_point`, `thousands_point`, `decimal_places`, `value`, `last_updated`) VALUES
(1, 'British Pound', 'GBP', '£', '', '.', ',', '2', 1.00000000, '2019-10-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customers_id` int(11) NOT NULL,
  `customers_gender` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_firstname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_lastname` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_dob` datetime NOT NULL DEFAULT '2020-01-01 00:00:00',
  `customers_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_default_address_id` int(11) NOT NULL DEFAULT 0,
  `customers_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_fax` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_password` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `customers_newsletter` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `guest_account` tinyint(4) NOT NULL DEFAULT 0,
  `customers_selected_template` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_second_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_second_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_reserve_newsletter` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_subscription_newsletter` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  `idcards_printed` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `customers_discount` decimal(8,2) DEFAULT NULL,
  `customers_groups_id` int(11) NOT NULL DEFAULT 1,
  `customers_username` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_occupation` int(11) NOT NULL DEFAULT 0,
  `customers_interest` int(11) NOT NULL DEFAULT 0,
  `encryption_style` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'O',
  `admin_groups_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `resume_from` date DEFAULT NULL,
  `suspend_from` date DEFAULT NULL,
  `is_blocked` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `cust_status` enum('Y','N') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `mail_status` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_token` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_timestamp` int(10) UNSIGNED NOT NULL,
  `consent` int(1) NOT NULL,
  `age_consent` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customers_id`, `customers_gender`, `customers_firstname`, `customers_lastname`, `customers_dob`, `customers_email_address`, `customers_default_address_id`, `customers_telephone`, `customers_fax`, `customers_password`, `customers_newsletter`, `guest_account`, `customers_selected_template`, `customers_second_email_address`, `customers_second_telephone`, `customers_reserve_newsletter`, `customers_subscription_newsletter`, `customers_type`, `idcards_printed`, `customers_discount`, `customers_groups_id`, `customers_username`, `customers_occupation`, `customers_interest`, `encryption_style`, `admin_groups_id`, `resume_from`, `suspend_from`, `is_blocked`, `cust_status`, `mail_status`, `customers_token`, `customers_timestamp`, `consent`, `age_consent`) VALUES
(1, 'm', 'user', 'name', '2020-01-01 00:00:00', 'gordon@osconcert.com', 1, '123456789', '', '29a048f93c04e50c02b2305694864333:a0', '1', 0, '', '', '', NULL, NULL, 'D', 'N', 0.00, 1, 'username', 0, 0, 'O', 0, '0000-00-00', '0000-00-00', 'N', 'N', '', '', 0, 0, 0),
(2, '', 'box', 'office', '2020-01-01 00:00:00', 'boxoffice@osconcert.com', 2, '1234567890', '', 'c212d8e09d3098c789c5d8b2b35224e5:33', '', 0, '', '', '', NULL, NULL, 'D', 'N', 0.00, 1, 'boxoffice', 0, 0, 'O', 0, '0000-00-00', '0000-00-00', 'N', 'N', '', '', 0, 0, 0),
(3, '', 'Joe', 'Blow', '2020-01-01 00:00:00', 'webmaster@cybergord.com', 3, '1234567890', NULL, '2d6dfdefac1f48680a9f709df8e4ab54:44', NULL, 1, '', '', '', NULL, NULL, 'D', 'N', NULL, 1, NULL, 0, 0, 'O', 0, NULL, NULL, 'N', 'N', '', '', 0, 0, 0),
(4, '', 'joe', 'joe', '2020-01-01 00:00:00', 'don@thelokdon.com', 4, '07725865855', NULL, '7e5088405199fc66f5f6895226ea749a:c8', NULL, 0, '', '', '', NULL, NULL, 'D', 'N', NULL, 1, NULL, 0, 0, 'O', 0, NULL, NULL, 'N', 'N', '', '', 0, 0, 0),
(5, '', 'Mike', 'Mikaela', '2020-01-01 00:00:00', 'scottphenix24@gmail.com', 5, '07448457194', NULL, 'b158d9e1d9876cd4d00bf92f3a66392c:bb', NULL, 0, '', '', '', NULL, NULL, 'D', 'N', NULL, 1, NULL, 0, 0, 'O', 0, NULL, NULL, 'N', 'N', '', '', 0, 0, 0),
(6, '', 'Don', 'Don', '2020-01-01 00:00:00', 'don@thelokdon.com', 6, '077725865855', NULL, '578b077e256687d3d076031459dfa85a:14', NULL, 1, '', '', '', NULL, NULL, 'D', 'N', NULL, 1, NULL, 0, 0, 'O', 0, NULL, NULL, 'N', 'N', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers_away_basket`
--

CREATE TABLE `customers_away_basket` (
  `customers_basket_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT 0,
  `products_id` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL DEFAULT 0,
  `final_price` decimal(15,4) DEFAULT NULL,
  `customers_basket_date_added` datetime DEFAULT NULL,
  `products_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P',
  `old_orders_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `answer_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_id` int(11) NOT NULL DEFAULT 0,
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket`
--

CREATE TABLE `customers_basket` (
  `customers_basket_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT 0,
  `products_id` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL DEFAULT 0,
  `final_price` decimal(15,4) DEFAULT NULL,
  `customers_basket_date_added` datetime DEFAULT NULL,
  `products_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P',
  `old_orders_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `answer_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_id` int(11) NOT NULL DEFAULT 0,
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_basket_address`
--

CREATE TABLE `customers_basket_address` (
  `orders_id` int(11) NOT NULL DEFAULT 0,
  `customers_id` int(11) NOT NULL DEFAULT 0,
  `delivery_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_company` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_street_address` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_suburb` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_city` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_state` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_country` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_address_format_id` int(5) NOT NULL DEFAULT 0,
  `billing_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_company` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_street_address` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_suburb` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_city` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_state` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_country` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_address_format_id` int(5) NOT NULL DEFAULT 0,
  `customers_second_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_second_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_fax` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '2020-01-01 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_extra_info`
--

CREATE TABLE `customers_extra_info` (
  `uniquename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fieldvalue` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_groups`
--

CREATE TABLE `customers_groups` (
  `customers_groups_id` int(11) NOT NULL,
  `customers_groups_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_groups_discount` decimal(8,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_groups`
--

INSERT INTO `customers_groups` (`customers_groups_id`, `customers_groups_name`, `customers_groups_discount`) VALUES
(1, 'Default', 0.00),
(2, 'WholeSale', -20.00);

-- --------------------------------------------------------

--
-- Table structure for table `customers_info`
--

CREATE TABLE `customers_info` (
  `customers_info_id` int(11) NOT NULL DEFAULT 0,
  `customers_info_date_of_last_logon` datetime DEFAULT NULL,
  `customers_info_number_of_logons` int(5) DEFAULT NULL,
  `customers_info_date_account_created` datetime DEFAULT NULL,
  `customers_info_date_account_last_modified` datetime DEFAULT NULL,
  `global_product_notifications` int(1) DEFAULT 0,
  `customers_info_source_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_info`
--

INSERT INTO `customers_info` (`customers_info_id`, `customers_info_date_of_last_logon`, `customers_info_number_of_logons`, `customers_info_date_account_created`, `customers_info_date_account_last_modified`, `global_product_notifications`, `customers_info_source_id`) VALUES
(3, NULL, 0, '2021-10-17 06:07:29', NULL, 0, 0),
(4, NULL, 0, '2021-10-23 11:58:34', NULL, 0, 0),
(5, NULL, 0, '2021-10-27 09:45:08', NULL, 0, 0),
(6, NULL, 0, '2021-11-03 22:00:31', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers_info_fields`
--

CREATE TABLE `customers_info_fields` (
  `info_id` int(11) NOT NULL,
  `uniquename` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `system` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `locked` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `show_label` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `input_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'T',
  `default_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `textbox_size` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `textbox_min_length` int(6) NOT NULL DEFAULT 0,
  `textbox_max_length` int(50) NOT NULL DEFAULT 0,
  `required` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  `options_values` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `storage_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'E',
  `display_page` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'C,E',
  `extra_param` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_info_fields`
--

INSERT INTO `customers_info_fields` (`info_id`, `uniquename`, `active`, `system`, `locked`, `show_label`, `input_type`, `default_value`, `textbox_size`, `textbox_min_length`, `textbox_max_length`, `required`, `sort_order`, `options_values`, `storage_type`, `display_page`, `extra_param`) VALUES
(1, 'title_1', 'Y', 'Y', 'N', 'Y', 'L', '', '0', 0, 0, 'Y', 1, NULL, 'C', 'C,B1,B2', NULL),
(2, '#1_gender', 'N', 'Y', 'N', 'Y', 'O', 'M', '0', 0, 0, 'N', 2, ' Male @@m## Female @@f', 'C,A', 'C,E,B1,B2,A', NULL),
(3, '#1_firstname', 'Y', 'Y', 'Y', 'Y', 'T', '', '30', 2, 0, 'Y', 3, NULL, 'C,A', 'C,E,A,B1,B2', NULL),
(4, '#1_lastname', 'Y', 'Y', 'Y', 'Y', 'T', NULL, '30', 2, 0, 'Y', 4, NULL, 'C,A', 'C,E,A,B1,B2', NULL),
(5, 'customers_dob', 'N', 'Y', 'N', 'N', 'T', '', '30', 10, 10, 'N', 5, NULL, 'C', 'C,B1,B2', NULL),
(6, 'customers_username', 'N', 'Y', 'N', 'N', 'T', '', '30', 6, 15, 'Y', 6, NULL, 'C', 'C,E,B1,B2', NULL),
(7, 'customers_email_address', 'Y', 'Y', 'Y', 'Y', 'T', NULL, '30', 6, 0, 'Y', 7, NULL, 'C', 'C,E,B1,B2', NULL),
(8, 'customers_confirm_email_address', 'N', 'Y', 'N', 'N', 'T', '', '30', 6, 0, 'N', 8, NULL, '', 'C,E,B1,B2', NULL),
(9, 'customers_second_email_address', 'N', 'Y', 'N', 'N', 'T', '', '30', 6, 0, 'N', 9, NULL, 'C', 'C,E,B1,B2', NULL),
(10, 'title_2', 'N', 'Y', 'N', 'N', 'L', NULL, '5', 0, 0, 'Y', 12, NULL, 'C', 'C,B1,B2', NULL),
(11, 'entry_company', 'N', 'N', 'N', 'Y', 'T', '', '30', 3, 0, 'N', 13, '', 'A', 'C,E,B1,B2', NULL),
(12, 'entry_street_address', 'N', 'Y', 'N', 'N', 'T', NULL, '30', 5, 0, 'Y', 14, NULL, 'A', 'C,A,B1,B2', NULL),
(13, 'entry_suburb', 'N', 'Y', 'N', 'Y', 'T', NULL, '30', 5, 0, 'N', 15, NULL, 'A', 'C,A,B1,B2', NULL),
(14, 'entry_postcode', 'N', 'Y', 'N', 'N', 'T', NULL, '30', 4, 15, 'Y', 17, NULL, 'A', 'C,E,B1,B2,A', NULL),
(15, 'entry_city', 'N', 'Y', 'N', 'N', 'T', NULL, '30', 3, 25, 'Y', 16, NULL, 'A', 'C,A,B1,B2', NULL),
(16, 'country_state', 'Y', 'Y', 'N', 'Y', 'U', '222', '0', 0, 0, 'Y', 18, NULL, 'A', 'C,E,B1,B2,A', NULL),
(17, 'title_3', 'N', 'Y', 'N', 'N', 'L', NULL, '5', 0, 0, 'Y', 19, NULL, 'C', 'C,B1,B2', NULL),
(18, 'customers_telephone', 'Y', 'Y', 'N', 'Y', 'T', '', '30', 8, 15, 'Y', 20, NULL, 'C', 'C,E,B1,B2', NULL),
(19, 'customers_second_telephone', 'N', 'Y', 'N', 'Y', 'T', '', '30', 8, 0, 'N', 21, NULL, 'C', 'C,E,B1,B2', NULL),
(20, 'customers_fax', 'N', 'Y', 'N', 'Y', 'T', '', '30', 7, 0, 'N', 22, NULL, 'C', 'C,E,B1,B2', NULL),
(21, 'title_4', 'N', 'Y', 'N', 'N', 'L', NULL, '5', 0, 0, 'Y', 23, NULL, 'C', 'C,B1,B2', NULL),
(22, 'customers_newsletter', 'N', 'Y', 'N', 'N', 'C', NULL, '5', 0, 0, 'N', 24, NULL, 'C', 'C,B1,B2', NULL),
(23, 'consent', 'N', 'Y', 'N', 'Y', 'C', '', '0', 0, 0, 'Y', 24, NULL, 'C', 'C,B1,B2', NULL),
(24, 'age_consent', 'N', 'Y', 'N', 'N', 'C', '', '0', 0, 0, 'Y', 24, NULL, 'C', 'C,B1,B2', NULL),
(25, 'customers_occupation', 'N', 'Y', 'N', 'Y', 'U', NULL, '5', 0, 0, 'N', 27, NULL, 'C', 'C,B1,B2', NULL),
(26, 'customers_interest', 'N', 'Y', 'N', 'Y', 'U', NULL, '5', 0, 0, 'N', 28, NULL, 'C', 'C,B1,B2', NULL),
(27, 'title_5', 'N', 'Y', 'N', 'N', 'L', '', '0', 0, 0, 'N', 29, NULL, 'C', 'C,B1', NULL),
(28, 'customers_referal', 'N', 'Y', 'N', 'N', 'U', '', '0', 0, 0, 'N', 30, NULL, 'C', 'C,B1', NULL),
(29, 'title_6', 'Y', 'Y', 'N', 'Y', 'L', '', '', 0, 0, 'Y', 31, '', 'C', 'C,B1', NULL),
(30, 'password_and_confirm', 'Y', 'Y', 'Y', 'Y', 'U', NULL, '30', 4, 0, 'Y', 32, NULL, 'C', 'C,B1', NULL),
(31, 'customers_photo', 'N', 'Y', 'N', 'N', 'U', '', '0', 0, 0, 'N', 10, NULL, 'E', 'C,E', NULL),
(32, 'security_code', 'N', 'Y', 'N', 'N', 'U', 'captcha', '0', 0, 0, 'N', 33, NULL, 'E', 'C', NULL),
(33, 'customers_groups_id', 'Y', 'N', 'N', 'Y', 'U', '', '0', 0, 0, 'N', 35, '', 'C', 'B1,B2', NULL),
(34, 'customers_discount', 'Y', 'N', 'N', 'Y', 'U', '', '0', 0, 0, 'N', 36, '', 'C', 'B1,B2', NULL),
(35, 'is_blocked', 'Y', 'N', 'N', 'Y', 'C', '', '0', 0, 0, 'N', 37, '', 'C', 'B1,B2', 'checkon=Y;checkoff=N'),
(36, 'customers_type', 'Y', 'N', 'N', 'Y', 'C', '', '0', 0, 0, 'N', 38, '', 'C', 'B1,B2', 'checkon=Y;checkoff=N'),
(37, 'suspend_from', 'Y', 'N', 'N', 'Y', 'U', '', '0', 0, 0, 'N', 39, '', 'C', 'B1,B2', NULL),
(38, 'resume_from', 'Y', 'N', 'N', 'Y', 'U', '', '0', 0, 0, 'N', 40, '', 'C', 'B1,B2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers_info_fields_description`
--

CREATE TABLE `customers_info_fields_description` (
  `info_id` int(11) NOT NULL DEFAULT 0,
  `label_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `input_title` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `input_description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `error_text` varchar(400) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `languages_id` int(11) NOT NULL DEFAULT 0,
  `unique_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_info_fields_description`
--

INSERT INTO `customers_info_fields_description` (`info_id`, `label_text`, `input_title`, `input_description`, `error_text`, `languages_id`, `unique_id`) VALUES
(1, 'Your Personal Details', '', '', '', 1, 0),
(2, 'Gender', 'Choose your gender', '', 'Please select a gender', 1, 0),
(3, 'First Name', 'Enter your First Name', '', 'Your First Name must contain a minimum of ==MIN== characters', 1, 0),
(4, 'Last Name', 'Enter your Last Name', NULL, 'Your Last Name must contain a minimum of ==MIN== characters', 1, 0),
(5, 'Date of Birth', 'Enter your Date of Birth in specified format', 'eg. ==EX_DATE==', 'Your Date of Birth must be in this format ==DATE_FORMAT== eg. ==EX_DATE==', 1, 0),
(6, 'Username', 'Enter your Username.', '', 'Your username must contain a minimum of ==MIN== characters##Username already exists in our records. Select a different one', 1, 0),
(7, 'Email', 'Enter your E-mail Address', NULL, 'Your E-mail Address must contain a minimum of ==MIN== characters##Your E-mail Address is not an valid one##Your E-mail Address already exists in our records.Please choose another one', 1, 0),
(8, 'Confirm Email', 'Confirm your E-mail Address', '', 'Your E-mail Address must match your Confirm E-mail Address', 1, 0),
(9, 'Second Email', 'Enter your Second Email Address', '', 'Your Second Email Address must be an valid one', 1, 0),
(10, 'Your Address', NULL, NULL, NULL, 1, 0),
(11, 'Company Name', 'Enter your company name', '', 'Your company name must contain a minimum of ==MIN== characters', 1, 0),
(12, 'Street Address', 'Enter your Street Address', NULL, 'Your Street Address must contain a minimum of ==MIN== characters', 1, 0),
(13, 'Address 2', 'Address Line 2', NULL, 'Address cannot be empty', 1, 0),
(14, 'Zip Code', 'Enter your Zip Code', NULL, 'Your Zip Code must contain a minimum of ==MIN== characters', 1, 0),
(15, 'City', 'Enter your City', NULL, 'Your City must contain a minimum of ==MIN== characters', 1, 0),
(16, 'Country##State/Province', 'Select Your country and state', '', 'Select a country##Select a State##Your state must contain a minimum of ==MIN== characters', 1, 0),
(17, 'Your Contact Information', NULL, NULL, NULL, 1, 0),
(18, 'Telephone Number', 'Enter your Telephone Number', 'No Spaces Please', 'Your Telephone number must contain a minimum of ==MIN== Characters', 1, 0),
(19, 'Second Telephone Number', 'Enter your Second Telephone Number', 'No Spaces Please', 'Your Second Telephone number cannot be empty', 1, 0),
(20, 'Mobile Number', 'Enter your Mobile Number', 'No Spaces Please', 'Your Mobile Number cannot be empty', 1, 0),
(21, 'Options', NULL, NULL, NULL, 1, 0),
(22, 'Newsletter', 'Tick for Newsletter E-mails', NULL, NULL, 1, 0),
(23, 'I consent to [Your Company or Application Name] collecting and storing data from this form', 'Your Consent is Required', '', 'Please give your consent.', 1, 0),
(24, 'I am 16 years - Article 8 EU privacy, 2016/679 Protection of minors', 'I am 16 years', '', 'Please confirm you are aged 16 and above', 1, 0),
(25, 'Occupation', 'Select Your Occupation', NULL, 'Select Your Occupation', 1, 0),
(26, 'Interest', 'Select Your Interest', NULL, 'Select Your Interest', 1, 0),
(27, 'Referral Source', '', '', '', 1, 0),
(28, 'How did you Hear about us##if \"Other\" please specify', 'Select Your Referal Source', '', 'Select Your Referal Source##Referal Source cannot be empty', 1, 0),
(29, 'Your Password', 'input title', 'input desc', '', 1, 0),
(30, 'Password##Password Confirmation', 'Enter password for your account', NULL, 'Password must contain a minimum of ==MIN== characters##Password Confirmation must match your Password##Password strength is poor', 1, 0),
(31, 'Upload your Photo', 'Upload your photo to account', '', 'Photo is required##Photo image format must be .jpg or .png. or .gif##Photo Upload failed##Photo Image size should be', 1, 0),
(32, 'Security Code', 'Enter the security code seen below', '', 'Security code must not be empty##Invalid Security code', 1, 0),
(33, 'Customers Groups', 'Customers Groups', 'Customers Groups', 'Select any customer group', 1, 0),
(34, 'Customers Discount', 'Customers Discount', '', 'Customers Discount is invalid', 1, 0),
(35, 'Block this Customer', 'Block this Customer', '', 'Please select block customer', 1, 0),
(36, 'VIP Customer', 'VIP Customer', '', 'Please select VIP customer', 1, 0),
(37, 'Suspend From', 'Enter your Suspend from in specified format', 'eg. ==DATE_FORMAT==', 'Suspend from date must be in this format ==DATE_FORMAT== eg. ==EX_DATE==', 1, 0),
(38, 'Resume From', 'Enter your resume from in specified format', 'eg. ==DATE_FORMAT==', ' Resume from date must be in this format ==DATE_FORMAT== eg. ==EX_DATE==', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers_temp_basket`
--

CREATE TABLE `customers_temp_basket` (
  `customers_basket_id` int(11) NOT NULL,
  `customers_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_id` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_basket_quantity` int(2) NOT NULL DEFAULT 0,
  `final_price` decimal(15,4) DEFAULT NULL,
  `customers_basket_date_added` datetime DEFAULT NULL,
  `products_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P',
  `old_orders_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `answer_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `discount_id` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_to_customers`
--

CREATE TABLE `customers_to_customers` (
  `group_customer_id` int(11) NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `orders_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_to_customers`
--

INSERT INTO `customers_to_customers` (`group_customer_id`, `customer_id`, `orders_id`) VALUES
(6, 0, 10),
(2, 0, 9),
(2, 0, 11);

-- --------------------------------------------------------

--
-- Table structure for table `customer_options`
--

CREATE TABLE `customer_options` (
  `options_id` tinyint(3) NOT NULL,
  `options_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `options_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'O'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_data`
--

CREATE TABLE `email_data` (
  `email_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `send_data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `merge_data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_data`
--

INSERT INTO `email_data` (`email_id`, `customer_id`, `order_id`, `send_data`, `merge_data`) VALUES
(4, 4, 4, 'YToxOntpOjA7YTo0OntzOjc6InRvX25hbWUiO3M6Nzoiam9lIGpvZSI7czo4OiJ0b19lbWFpbCI7czoxNzoiZG9uQHRoZWxva2Rvbi5jb20iO3M6OToiZnJvbV9uYW1lIjtzOjk6Im9zQ29uY2VydCI7czoxMDoiZnJvbV9lbWFpbCI7czoyNToiYm9va2luZ3NAam9sbG9mbmxhdWdoLmNvbSI7fX0=', 'YTo2Mjp7czoxMjoiT3JkZXJfTnVtYmVyIjtpOjQ7czoxODoiT3JkZXJfSW52b2ljZV9MaW5rIjtzOjIyNToiPGEgaHJlZj0iaHR0cHM6Ly93d3cub3Njb25jZXJ0Lm5ldC9qb2Uvb3Njb25jZXJ0L2FjY291bnRfaGlzdG9yeV9pbmZvLnBocD9vcmRlcl9pZD00IiBzdHlsZT0iZm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgc2Fucy1zZXJpZjtmb250LXNpemU6MTNweDtjb2xvcjojMzk2NkIxO2ZvbnQtd2VpZ2h0OmJvbGQ7dGV4dC1kZWNvcmF0aW9uOm5vbmU7Ij5PcmRlciBJbnZvaWNlIExpbms8L2E+IjtzOjE0OiJEYXRlX1B1cmNoYXNlZCI7czoxOToiU2F0dXJkYXkgMjMgT2N0b2JlciI7czoxNDoiT3JkZXJfQ29tbWVudHMiO3M6MDoiIjtzOjE1OiJTaGlwcGluZ19NZXRob2QiO047czoyMDoiQmFua19EZXBvc2l0X01lc3NhZ2UiO3M6MDoiIjtzOjEyOiJSZWZlcmVuY2VfSUQiO3M6MTA6IjE2MzQ5OTAzMzkiO3M6MjU6IlJlc2VydmF0aW9uIE9yZGVyIE1lc3NhZ2UiO3M6MDoiIjtzOjExOiJNb25leV9PcmRlciI7czowOiIiO3M6MTQ6IlBheXBhbF9NZXNzYWdlIjtzOjA6IiI7czoxNjoiV2lsbGNhbGwgTWVzc2FnZSI7czowOiIiO3M6MTY6IlByb2R1Y3RzX09yZGVyZWQiO3M6OTExOiI8dGFibGUgd2lkdGg9IjEwMCUiPjx0ciBoZWlnaHQ9IjMwIiBjbGFzcz0idGV4dFRhYmxlU3ViSGVhZCI+PHRkIHdpZHRoPSIyMCI+PGI+UXVhbnRpdHk8L2I+PC90ZD48dGQgY29sc3Bhbj0iMiI+PGI+VGlja2V0PC9iPjwvdGQ+PHRkPjwvdGQ+PHRkIGFsaWduPSJyaWdodCIgd2lkdGg9IjEwMCI+PGI+UHJpY2U8L2I+PC90ZD48L3RyPgkNCQk8dHIgaGVpZ2h0PSI1MCI+DQkJPHRkIHdpZHRoPSIxMCUiIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjogdGV4dC10b3A7dGV4dC1hbGlnbjpsZWZ0Ij4NCQkxIHggDQkJPC90ZD4NCQk8dGQgd2lkdGg9IjEwJSIgc3R5bGU9InRleHQtYWxpZ246bGVmdCI+DQkJPGltZyBzcmM9Imh0dHBzOi8vd3d3Lm9zY29uY2VydC5uZXQvam9lL29zY29uY2VydC9pbWFnZXMvc21hbGwvdGlja2V0X2ljb24ucG5nIiB3aWR0aD01MCBoZWlnaHQ9NTAgc3R5bGU9IndpZHRoOjUwcHg7aGVpZ2h0OjUwcHgiPg0JCTwvdGQ+DQkJPHRkIHdpZHRoPSIyMCUiIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjogdGV4dC10b3A7dGV4dC1hbGlnbjpsZWZ0Ij4NCQlFeGVjdXRpdmUgVGFibGUgMiBTZWF0IDENCQkNCQk8L3RkPg0JCTx0ZCB3aWR0aD0iNTAlIiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRleHQtdG9wO3RleHQtYWxpZ246bGVmdCI+DQkJPHNtYWxsPg0JCUpvbGxvZk5MYXVnaCAgIDxicj5UdWRlciBSb3NlICAgPGJyPlN1bmRheSwgRGVjZW1iZXIgNXRoICAgNjowMCBwbTxicj48L3NtYWxsPg0JCTwvdGQ+DQkJPHRkIHdpZHRoPSIxMCUiIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjogdGV4dC10b3A7dGV4dC1hbGlnbjpsZWZ0Ij4NCQnCozQwLjAwIA0JCTwvdGQ+DQkJPC90cj4NCQk8dHI+DQkJPHRkIGNvbHNwYW49IjUiIGhlaWdodD0iMTAiPg0JCTwvdGQ+DQkJPC90cj4NCQk8L3RhYmxlPiI7czo3OiJURVhUX0RMIjtzOjE1OiI8dGFibGU+PC90YWJsZT4iO3M6MTc6IlRleHRfT3JkZXJfTnVtYmVyIjtzOjEzOiJPcmRlciBOdW1iZXI6IjtzOjk6IlRleHRfRGVhciI7czo1OiJEZWFyICI7czoyMDoiVGV4dF9UaGFua3NfUHVyY2hhc2UiO3M6NjU6IlRoYW5rIHlvdSBmb3IgeW91ciBwdXJjaGFzZS4gVGhlIGRldGFpbHMgb2YgeW91ciBvcmRlciBhcmUgYmVsb3c6IjtzOjIxOiJUZXh0X0RlbGl2ZXJ5X0RldGFpbHMiO3M6MTY6IkRlbGl2ZXJ5IERldGFpbHMiO3M6MTI6IlRleHRfQWRkcmVzcyI7czo3OiJBZGRyZXNzIjtzOjE0OiJUZXh0X1RlbGVwaG9uZSI7czo5OiJUZWxlcGhvbmUiO3M6MTA6IlRleHRfRW1haWwiO3M6MTA6IllvdXIgRW1haWwiO3M6MjA6IlRleHRfUGF5bWVudF9EZXRhaWxzIjtzOjE1OiJQYXltZW50IERldGFpbHMiO3M6MTk6IlRleHRfUGF5bWVudF9NZXRob2QiO3M6MTQ6IlBheW1lbnQgTWV0aG9kIjtzOjEyOiJUZXh0X1RpY2tldHMiO3M6MTU6IlRpY2tldHMgT3JkZXJlZCI7czoxNjoiVGV4dF9XaXRoX1RoYW5rcyI7czoxMjoiV2l0aCBUaGFua3MgIjtzOjExOiJUZXh0X1RoYW5rcyI7czoxNToiVEVYVF9USEFOS1NfRk9SIjtzOjEyOiJPcmRlcl9Ub3RhbHMiO3M6MzczOiI8dGFibGUgYm9yZGVyPScwJyBjZWxscGFkZGluZz0nMicgY2VsbHNwYWNpbmc9JzAnIHdpZHRoPScxMDAlJz48dHIgY2xhc3M9InRleHRNYWluIiBoZWlnaHQ9IjIxIj48dGQgY2xhc3M9InRleHRUYWJsZVRvdGFsRSIgYWxpZ249InJpZ2h0Ij5TdWItVG90YWw6PC90ZD48dGQgY2xhc3M9InRleHRUYWJsZVRvdGFsRSIgYWxpZ249InJpZ2h0IiB3aWR0aD0iMTAwIj7CozQwLjAwIDwvdGQ+PHRyIGNsYXNzPSJ0ZXh0TWFpbiIgaGVpZ2h0PSIyMSI+PHRkIGNsYXNzPSJ0ZXh0VG90YWwiIGFsaWduPSJyaWdodCI+VG90YWw6ICAgIDwvdGQ+PHRkIGNsYXNzPSJ0ZXh0VG90YWwiIGFsaWduPSJyaWdodCIgd2lkdGg9IjEwMCI+wqM0MC4wMCA8L3RkPjwvdGFibGU+IjtzOjEwOiJGaXJzdF9OYW1lIjtzOjM6ImpvZSI7czo5OiJMYXN0X05hbWUiO3M6Mzoiam9lIjtzOjg6IlVzZXJuYW1lIjtOO3M6NzoiQ29tcGFueSI7TjtzOjExOiJTdG9yZV9Pd25lciI7czo5OiJvc0NvbmNlcnQiO3M6MTQ6IlN0cmVldF9BZGRyZXNzIjtzOjA6IiI7czo5OiJBZGRyZXNzXzIiO047czo0OiJDaXR5IjtzOjA6IiI7czo5OiJQb3N0X0NvZGUiO3M6MDoiIjtzOjU6IlN0YXRlIjtzOjE0OiJHcmVhdGVyIExvbmRvbiI7czo3OiJDb3VudHJ5IjtzOjE0OiJVbml0ZWQgS2luZ2RvbSI7czo5OiJUZWxlcGhvbmUiO3M6MTE6IjA3NzI1ODY1ODU1IjtzOjEzOiJFbWFpbF9BZGRyZXNzIjtzOjE3OiJkb25AdGhlbG9rZG9uLmNvbSI7czoxMjoiQmlsbGluZ19OYW1lIjtzOjg6IiBqb2Ugam9lIjtzOjE1OiJCaWxsaW5nX0NvbXBhbnkiO047czoyMjoiQmlsbGluZ19TdHJlZXRfQWRkcmVzcyI7czowOiIiO3M6MTc6IkJpbGxpbmdfQWRkcmVzc18yIjtOO3M6MTM6IkJpbGxpbmdfRW1haWwiO3M6MTc6ImRvbkB0aGVsb2tkb24uY29tIjtzOjEyOiJCaWxsaW5nX0NpdHkiO3M6MDoiIjtzOjE3OiJCaWxsaW5nX1Bvc3RfQ29kZSI7czowOiIiO3M6MTM6IkJpbGxpbmdfU3RhdGUiO3M6MTQ6IkdyZWF0ZXIgTG9uZG9uIjtzOjE1OiJCaWxsaW5nX0NvdW50cnkiO3M6MTQ6IlVuaXRlZCBLaW5nZG9tIjtzOjEzOiJEZWxpdmVyeV9OYW1lIjtzOjg6IiBqb2Ugam9lIjtzOjE2OiJEZWxpdmVyeV9Db21wYW55IjtOO3M6MTQ6IkRlbGl2ZXJ5X0VtYWlsIjtzOjE3OiJkb25AdGhlbG9rZG9uLmNvbSI7czoyMzoiRGVsaXZlcnlfU3RyZWV0X0FkZHJlc3MiO3M6MDoiIjtzOjE4OiJEZWxpdmVyeV9BZGRyZXNzXzIiO047czoxMzoiRGVsaXZlcnlfQ2l0eSI7czowOiIiO3M6MTg6IkRlbGl2ZXJ5X1Bvc3RfQ29kZSI7czowOiIiO3M6MTQ6IkRlbGl2ZXJ5X1N0YXRlIjtzOjE0OiJHcmVhdGVyIExvbmRvbiI7czoxNjoiRGVsaXZlcnlfQ291bnRyeSI7czoxNDoiVW5pdGVkIEtpbmdkb20iO3M6MTQ6IlBheW1lbnRfTWV0aG9kIjtzOjIyOiJTdHJpcGUgU2VjdXJlIFBheW1lbnRzIjtzOjEwOiJTdG9yZV9MaW5rIjtzOjE5MDoiPGEgaHJlZj0iaHR0cHM6Ly93d3cub3Njb25jZXJ0Lm5ldC9qb2Uvb3Njb25jZXJ0L2luZGV4LnBocCIgc3R5bGU9ImZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWY7Zm9udC1zaXplOjEzcHg7Y29sb3I6IzM5NjZCMTtmb250LXdlaWdodDpib2xkO3RleHQtZGVjb3JhdGlvbjpub25lOyI+b3NDb25jZXJ0PC9hPiI7czoxMDoiT3JkZXJfTGluayI7czoyNjU6IjxiPkNvbGxlY3QgWW91ciBQREYgQ29uY2VydCBFLVRpY2tldHMgSGVyZTogPC9iPjxhIGhyZWY9Imh0dHBzOi8vd3d3Lm9zY29uY2VydC5uZXQvam9lL29zY29uY2VydC9hY2NvdW50X2hpc3RvcnlfaW5mby5waHA/b3JkZXJfaWQ9NCIgc3R5bGU9ImZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWY7Zm9udC1zaXplOjEzcHg7Y29sb3I6IzM5NjZCMTtmb250LXdlaWdodDpib2xkO3RleHQtZGVjb3JhdGlvbjpub25lOyI+Q2xpY2sgSGVyZTwvYT4iO3M6MTA6IlN0b3JlX0xvZ28iO3M6MTExOiI8aW1nIHNyYz0iaHR0cHM6Ly93d3cub3Njb25jZXJ0Lm5ldC9qb2Uvb3Njb25jZXJ0L3RlbXBsYXRlcy9uZXd6b25lL2ltYWdlcy9vc2NvbmNlcnQucG5nIiB0aXRsZT0iSm9sbG9mTkxhdWdoIj4iO3M6MTM6IlN0b3JlX0FkZHJlc3MiO3M6MjE6IkxvbmRvbiBOZXcgWW9yayBQYXJpcyI7fQ==');

-- --------------------------------------------------------

--
-- Table structure for table `email_messages`
--

CREATE TABLE `email_messages` (
  `message_id` int(11) NOT NULL,
  `message_send` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_reply_to` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_text` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message_format` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'T',
  `message_type` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_messages`
--

INSERT INTO `email_messages` (`message_id`, `message_send`, `message_subject`, `message_reply_to`, `message_text`, `message_format`, `message_type`) VALUES
(1, '', 'osConcert Registration Confirmation', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p><p>Welcome to %%Store_Name%%.</p><p>Your details are as follows:</p><p>Username: %%Login_Email%%</p><p>Password: &lt;HIDDEN&gt;</p><p>With thanks, %%Store_Owner%%</p>', 'B', 'AUT'),
(2, '', 'osConcert Account Confirmation', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p><p>Your %%Store_Name%% account has been created. You can now take part in the <br />various services we have to offer you. Some of these services include:</p><p>Integrated Online Theatre Seat Reservation</p><p>The details of your account are as follows:</p><p>Username: %%Login_Email%%</p><p>Password: &lt;HIDDEN&gt;</p><p>For help with any of our online services, please email the <br />store-owner: %%Store_Email%%</p><p>With thanks,</p><p>%%Store_Owner%%</p><p>%%Store_Link%%</p><p>Note: This email address was given to us by one of our customers. If you did not signup to be a member, please send an email to %%Store_Email%%.</p>', 'B', 'CUS'),
(3, '', 'osConcert Password', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p><p>Your %%Store_Name%% account details are as follows:</p><p>Username: %%Login_Email%%</p><p>Password:&lt;HIDDEN&gt;</p><p>If you have trouble accessing your account, please contact us at: %%Store_Email%%</p><p>With thanks, %%Store_Owner%%</p>', 'B', 'AUR'),
(4, '', 'osConcert Account Details', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p><p>Your %%Store_Name%% account details are as follows:</p><p>Username: %%Login_Email%%</p><p>Password:&lt;HIDDEN&gt;</p><p>If you have trouble accessing your account, please contact us at: %%Store_Email%%</p><p>With thanks, %%Store_Owner%%</p>', 'B', 'CUR'),
(5, '', 'The Jollof N Laugh Show Reservation Order', 'bookings@jollofnlaugh.com', '<table style=\"border: 1px solid #BBBBBB;\" cellspacing=\"0\" cellpadding=\"20\" align=\"center\"><tbody><tr><td><table style=\"width: 700px;\" cellspacing=\"10\" cellpadding=\"5\" align=\"center\"><tbody><tr><td><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody><tr><td width=\"10\">&nbsp;</td><td>%%Store_Logo%%</td><td align=\"right\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong><span style=\"color: #000000;\">Order Number: %%Order_Number%%</span></strong></span></td><td width=\"10\">&nbsp;</td></tr></tbody></table></td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"2\"><tbody><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Dear <strong> %%First_Name%%&nbsp;%%Last_Name%%</strong>,</span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Thank you for your purchase. The details of your order are below:</span></td></tr></tbody></table></td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"2\"><tbody><tr><td align=\"left\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>%%Date_Purchased%%</strong></span></td><td align=\"right\" width=\"500\"><span class=\"link\">%%Order_Link%%</span></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Delivery Details</strong></span></td></tr><tr><td valign=\"top\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td valign=\"top\" width=\"120\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Address: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Delivery_Name%%<br /> %%Delivery_Street_Address%%<br /> %%Delivery_City%%<br /> %%Delivery_State%%, %%Delivery_Post_Code%%<br /> %%Delivery_Country%%<br /> </span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Phone: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Telephone%%</span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Email: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Email_Address%%</span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 700px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Payment Details</strong></span></td></tr><tr><td valign=\"top\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td><table style=\"width: 100%;\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td valign=\"top\" width=\"120\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Address: </span></td><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Billing_Name%%<br /> %%Billing_Street_Address%%<br /> %%Billing_City%%<br /> %%Billing_State%%, %%Billing_Post_Code%%<br /> %%Billing_Country%%<br /> </span></td></tr><tr><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Payment Method: </span></td><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Payment_Method%%<br /> %%Bank_Deposit_Message%%<br /> </span></td></tr></tbody></table></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: xx-small;\">%%Order_Comments%%</span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Tickets</strong></span></td></tr><tr><td valign=\"top\">%%Products_Ordered%%</td></tr><tr><td>%%Order_Totals%%</td></tr></tbody></table></td></tr><tr><td>&nbsp;</td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"0\"><tbody><tr><td align=\"left\" valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"> <strong>With thanks, </strong> </span></td></tr><tr><td class=\"link\">%%Store_Link%%</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>', 'B', 'PRD'),
(6, '', 'osConcert Seat Reservation Order', 'info@osconcert.com', '<table style=\"border: 1px solid #BBBBBB;\" cellspacing=\"0\" cellpadding=\"20\" align=\"center\"><tbody><tr><td><table style=\"width: 700px;\" cellspacing=\"10\" cellpadding=\"5\" align=\"center\"><tbody><tr><td><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody><tr><td width=\"10\">&nbsp;</td><td>%%Store_Logo%%</td><td align=\"right\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong><span style=\"color: #000000;\">Order Number: %%Order_Number%%</span></strong></span></td><td width=\"10\">&nbsp;</td></tr></tbody></table></td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"2\"><tbody><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Dear <strong> %%First_Name%%&nbsp;%%Last_Name%%</strong>,</span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Thank you for your purchase. The details of your order are below:</span></td></tr></tbody></table></td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"2\"><tbody><tr><td align=\"left\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>%%Date_Purchased%%</strong></span></td><td align=\"right\" width=\"500\"><span class=\"link\">%%Order_Link%%</span></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Delivery Details</strong></span></td></tr><tr><td valign=\"top\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td valign=\"top\" width=\"120\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Address: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Delivery_Name%%<br /> %%Delivery_Street_Address%%<br /> %%Delivery_City%%<br /> %%Delivery_State%%, %%Delivery_Post_Code%%<br /> %%Delivery_Country%%<br /> </span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Phone: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Telephone%%</span></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Email: </span></td><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Email_Address%%</span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 700px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Payment Details</strong></span></td></tr><tr><td valign=\"top\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td><table style=\"width: 100%;\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td valign=\"top\" width=\"120\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Address: </span></td><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Billing_Name%%<br /> %%Billing_Street_Address%%<br /> %%Billing_City%%<br /> %%Billing_State%%, %%Billing_Post_Code%%<br /> %%Billing_Country%%<br /> </span></td></tr><tr><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">Payment Method: </span></td><td valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\">%%Payment_Method%%<br /> %%Bank_Deposit_Message%%<br /> </span></td></tr></tbody></table></td></tr><tr><td><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: xx-small;\">%%Order_Comments%%</span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style=\"border: 1px solid #F5EED1;\" valign=\"top\" bgcolor=\"#e5fcff\"><table style=\"width: 100%;\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tbody><tr><td bgcolor=\"#17a2b8\"><span style=\"color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: small;\"><strong>Tickets</strong></span></td></tr><tr><td valign=\"top\">%%Products_Ordered%%</td></tr><tr><td>%%Order_Totals%%</td></tr></tbody></table></td></tr><tr><td>&nbsp;</td></tr><tr><td><table style=\"width: 700px;\" cellspacing=\"0\" cellpadding=\"0\"><tbody><tr><td align=\"left\" valign=\"top\"><span style=\"color: #000000; font-family: Arial,Helvetica,sans-serif; font-size: small;\"> <strong>With thanks, </strong> </span></td></tr><tr><td class=\"link\">%%Store_Link%%</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>', 'B', 'PRS'),
(10, '', 'Contact Us', 'contactus', '<div class=\"controls\">\n        <div class=\"row\">\n            <div class=\"col-md-12\">\n                <div class=\"form-group\">\n                    <label for=\"form_name\">Firstname <span class=\"inputRequirement\">*</span></label>\n                    %%Name Text Box%%\n                    <div class=\"help-block with-errors\"></div>\n                </div>\n            </div>\n        </div>\n        <div class=\"row\">\n            <div class=\"col-md-12\">\n                <div class=\"form-group\">\n                    <label for=\"form_email\">Email <span class=\"inputRequirement\">*</span></label>\n                    %%Email Text Box%%\n                </div>\n            </div>\n        </div>\n        <div class=\"row\">\n            <div class=\"col-md-12\">\n                <div class=\"form-group\">\n                    <label for=\"form_message\">Message <span class=\"inputRequirement\">*</span></label>\n                    %%Comments Text Box%%\n                </div>\n            </div>\n            <div class=\"col-md-12\">\n                %%Continue Button%%\n            </div>\n        </div>\n    </div>', '', 'CON'),
(11, '', 'Contact Us', 'contactus', '<section id=\"contact\">\r\n<div class=\"container\">\r\n        <div class=\"form\">\r\n            <div class=\"row\">\r\n              <div class=\"form-group col-md-6\">\r\n                %%Name Text Box%%\r\n              </div>\r\n              <div class=\"form-group col-md-6 mt-3 mt-md-0\">\r\n                 %%Email Text Box%%\r\n              </div>\r\n            </div>\r\n            <div class=\"form-group mt-3\">\r\n              %%Comments Text Box%%\r\n            </div>\r\n            <div class=\"text-center\">%%Continue Button%%</div>\r\n        </div>\r\n      </div>\r\n    </section><!-- End Contact Section -->\r\n </form>\r\n', '', 'HCF'),
(19, '', 'osConcert Wallet Upload Admin', 'info@osconcert.com', '<p>Dear %%First_Name%%, Your Wallet Upload is complete:</p><p>Your new balance is:%%Current_balance%%</p><p>&nbsp;</p><p>With thanks&nbsp;</p>', 'B', 'WFU'),
(20, '', 'osConcert Wallet Upload', 'info@osconcert.com', '<p>Dear %%First_Name%%, Your Wallet Upload is complete:</p><p>Your new balance is:%%Current_balance%%</p><p>&nbsp;</p><p>With thanks&nbsp;</p>', 'B', 'WFW'),
(21, '', 'osConcert Forgotten Password', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p><p>A request to reset your password has been received at %%Store_Name%%</p><p>To reset your password use the following link: <br /><a href=\"%%Login_Password%%\">%%Login_Password%%</a></p><p>The link is valid for %%Login_Email%% hours.</p><p>If you did not request a change of password then you need do nothing.</p><p>If you have further trouble accessing your account, please contact us at: %%Store_Email%%</p><p>With thanks, %%Store_Owner%%</p>', 'B', 'CUX'),
(22, '', 'Account Under Review', 'info@osconcert.com', '<p>Dear %%First_Name%%,</p>\r\n<p>Your %%Store_Name%% account has been created. </p>\r\n<p>Your account is under review for approval. </p>\r\n<p>The details of your account are as follows:</p>\r\n<p>Username: %%Login_Email%%<br />\r\nPassword: ***password hidden***</p>\r\n<p>For help with any of our online services, please email the <br />store-owner: %%Store_Email%%\r\n<br><br>With thanks,%%Store_Owner%%</p><br>%%Store_Link%%\r\n<p>Note: This email address was given to us by one of our customers. <br />\r\nIf you did not signup to be a member, please send an email to %%Store_Email%%. </p>', 'B', 'APV'),
(23, '', 'New Sign Up', 'info@osconcert.com', '<p>New Sign UP at %%Store_Name%%</p>', 'B', 'ADM'),
(24, '', 'osConcert Order Status Update', 'info@osconcert.com', '<p>%%Store_Logo%%</p><p><strong>Order Number:</strong> %%Order_Number%%<br /> <br /> <strong>Date Ordered:</strong> %%Date_Purchased%%<br /> <br /> <br /> <br /> Thank you so much for your order.<br /> <br /> We confirm your payment has been made. Please click on the link below and log into your account. From there you will be able to retrieve and print your e-tickets.<br /> <br /> <strong>New status:</strong> %%Order_Status_Update%%</p><p>%%Order_Invoice_Link%%</p><p>&nbsp;</p><p><strong>Comments:</strong> %%Order_Comments%%</p><p>%%Store_Owner%%</p><p>%%Store_Name%%</p>', 'B', 'OSU'),
(25, NULL, 'Order Amount Refunded', 'info@osconcert.com', '<p>Dear %%First_Name%% %%Last_Name%%</p><p>&nbsp;</p><p>%%Username%%</p><p><--HIDDEN--></p><p>%%Login_Email%%</p><p>&nbsp;</p><p>Your order&nbsp; has been successfully refunded.</p><p>%%Order_Amount_Refunded%%</p><p>%%Order_Amount%%</p><p>%%Payment_Date%%</p><p>&nbsp;</p><p>%%Order_Invoice_Link%%</p><p>&nbsp;</p><p>%%Refund_Amount%%</p><p>Comments: %%Refund_Comments%%</p><p>&nbsp;</p><p>Regards</p><p>%%Store_Logo%%</p><p>%%Store_Owner%%</p><p>%%Store_Name%%</p><p>%%Store_Email%%</p><p>%%Store_Link%%</p>', 'B', 'OAR');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` longtext NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `maps` text NOT NULL,
  `video` text NOT NULL,
  `req_age` varchar(100) NOT NULL,
  `req_dress_code` varchar(100) NOT NULL,
  `req_last_entry` varchar(100) NOT NULL,
  `req_id` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `event_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `code`, `title`, `body`, `category`, `subcategory`, `status`, `maps`, `video`, `req_age`, `req_dress_code`, `req_last_entry`, `req_id`, `image`, `event_date`, `created_time`, `created_date`) VALUES
(1, 'ham_ty57h', 'Hamilton', '<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.\r\nBlack farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n', 'Music', 'Festivals', 'Trending', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.582705894648!2d0.0014754157511722317!3d51.50252477963436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a81c5507b387%3A0x183f693c66bc31aa!2sThe%20Millennium%20Dome%20Millennium%20Way%2C%20London%20SE10%200BB!5e0!3m2!1sen!2suk!4v1633796374811!5m2!1sen!2suk\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', '', '', '', '', '', 'bg-03.jpg', '2021-10-06 20:36:41', 0, '2021-10-06 20:36:41'),
(2, 'liam_gif2930', 'Liam Gallagher', '<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.\r\nBlack farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n', 'Music', 'Alternative-and-indie', 'Featured', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.582705894648!2d0.0014754157511722317!3d51.50252477963436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a81c5507b387%3A0x183f693c66bc31aa!2sThe%20Millennium%20Dome%20Millennium%20Way%2C%20London%20SE10%200BB!5e0!3m2!1sen!2suk!4v1633796374811!5m2!1sen!2suk\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', '', '', '', '', '', 'bg-03.jpg', '2021-10-06 20:45:15', 0, '2021-10-06 20:45:15'),
(3, 'fiba_ki890', 'Fiba World cup', '<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n\r\n<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.\r\nBlack farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>\r\n', 'Sport', 'Basketball', 'Trending', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.582705894648!2d0.0014754157511722317!3d51.50252477963436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a81c5507b387%3A0x183f693c66bc31aa!2sThe%20Millennium%20Dome%20Millennium%20Way%2C%20London%20SE10%200BB!5e0!3m2!1sen!2suk!4v1633796374811!5m2!1sen!2suk\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', '', '', '', '', '', 'bg-03.jpg', '2021-10-06 20:48:49', 0, '2021-10-06 20:48:49'),
(4, 'ybvdfgzcxuvcxea475', 'Ghost of Tsunoda', '<p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>  <p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>  <p>Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds. Black farmers in the US’s South—faced with continued failure their efforts to run successful farms their launched a lawsuit claiming that “white racism” is to blame for their inability to the produce crop yields and on equivalent to that switched seeds.</p>', 'Arts', '', 'Discover', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.582705894648!2d0.0014754157511722317!3d51.50252477963436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a81c5507b387%3A0x183f693c66bc31aa!2sThe%20Millennium%20Dome%20Millennium%20Way%2C%20London%20SE10%200BB!5e0!3m2!1sen!2suk!4v1633796374811!5m2!1sen!2suk\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'none', '', '', '', '', 'bg-03.jpg', '2021-10-29 04:00:00', 0, '2021-10-25 17:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `featured`
--

CREATE TABLE `featured` (
  `featured_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT 0,
  `featured_date_added` datetime DEFAULT NULL,
  `featured_last_modified` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `general_templates`
--

CREATE TABLE `general_templates` (
  `template_id` int(11) NOT NULL,
  `template_type` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'TICE',
  `template_width` decimal(6,4) DEFAULT 0.0000,
  `template_height` decimal(6,4) DEFAULT 0.0000,
  `template_content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_templates`
--

INSERT INTO `general_templates` (`template_id`, `template_type`, `template_width`, `template_height`, `template_content`) VALUES
(1, 'TIC', 18.0000, 7.0000, 'shop_logo_image{}ticket.png{}shop_logo_position{}L{}event_details_content{}EventsThroneVIP presents\n\n%%Concert Name%%\n%%Concert Venue%%\n%%Concert Date%% - %%Concert Time%%\nTicket: %%Products Name%% Price: %%Concert Price%%\n%%Discount Type%%\n%%Coupon%%\n%%Customers Name%% %%Billing Name%%\nTicket ref: %%Ref ID%%%%Order ID%%_%%Prd ID%%%%GA ID%%\n\nTICKETS: JollofNLaugh Box Office\nbookings@jollofnlaugh.com www.jollofnlaugh.com{}event_details_position{}L{}sponsor_logo_image{}sponsor_logo.png{}sponsor_logo_position{}R{}event_condition_content{}Refundable only if event is cancelled{}event_condition_position{}L{}bar_image_position{}'),
(2, 'TIC2', 18.0000, 7.0000, 'shop_logo_image{}ticket.png{}shop_logo_position{}L{}event_details_content{}osConcert presents\nDouble Sided Ticket\n\n%%Concert Name%%\n%%Concert Venue%%\n%%Concert Date%%\n%%Concert Time%% \nSeat: %%Products Name%% \nPrice: %%Concert Price%% \n%%Discount Type%% \n%%Coupon%%\n%%Customers Name%%\nRef: %%Ref ID%%%%Order ID%%_%%Prd ID%%{}event_details_position{}L{}sponsor_logo_image{}sponsor_logo.png{}sponsor_logo_position{}R{}event_condition_content{}Refundable only if event is cancelled{}event_condition_position{}L{}bar_image_position{}R'),
(3, 'TIC3', 18.0000, 7.0000, 'shop_logo_image{}ticket.png{}shop_logo_position{}L{}event_details_content{}Movie: %%Concert Name%%\n\nScreen: %%Concert Venue%%\nDate: %%Concert Date%% \nTime: %%Products Name%%\n\nPrice: %%Symbol%%%%Concert Price%% %%Discount Type%% \n%%Coupon%%\n%%Customers Name%%\nTicket ref: %%Ref ID%%%%Prd ID%% %%Order ID%%\n\nTICKETS: osConcert Box Office - OBO \nTel - 123 456-1122 www.osconcert.com{}event_details_position{}L{}sponsor_logo_image{}sponsor_logo.png{}sponsor_logo_position{}R{}event_condition_content{}Refundable only if event is cancelled{}event_condition_position{}L{}bar_image_position{}R');

-- --------------------------------------------------------

--
-- Table structure for table `geo_zones`
--

CREATE TABLE `geo_zones` (
  `geo_zone_id` int(11) NOT NULL,
  `geo_zone_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `geo_zone_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `geo_zones`
--

INSERT INTO `geo_zones` (`geo_zone_id`, `geo_zone_name`, `geo_zone_description`, `last_modified`, `date_added`) VALUES
(1, 'USA', 'United States of America', '2020-01-01 00:00:00', '2017-04-26 17:35:28'),
(2, 'World Zones', 'All International Zones', '2020-01-01 00:00:00', '2017-04-26 17:35:28'),
(3, 'Australia', 'Australia Zones', '2020-01-01 00:00:00', '2017-04-26 17:35:28'),
(4, 'Box Office', 'Box Office', '2020-01-01 00:00:00', '2020-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `infobox_configuration`
--

CREATE TABLE `infobox_configuration` (
  `template_id` int(3) UNSIGNED DEFAULT NULL,
  `infobox_id` int(11) NOT NULL,
  `infobox_file_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `infobox_define` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'BOX_HEADING_',
  `infobox_display` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `display_in_column` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'left',
  `location` int(3) NOT NULL DEFAULT 0,
  `sort_order` int(5) DEFAULT 0,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `box_heading` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `box_template` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'infobox'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `infobox_configuration`
--

INSERT INTO `infobox_configuration` (`template_id`, `infobox_id`, `infobox_file_name`, `infobox_define`, `infobox_display`, `display_in_column`, `location`, `sort_order`, `last_modified`, `date_added`, `box_heading`, `box_template`) VALUES
(1, 1, 'languages.php', 'BOX_HEADING_LANGUAGES', 'no', 'left', 0, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Languages', 'infobox'),
(1, 2, 'manufacturer_info.php', 'BOX_HEADING_MANUFACTURER_INFO', 'no', 'left', 0, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Manufacturers Info', 'infobox'),
(1, 3, 'manufacturers.php', 'BOX_HEADING_MANUFACTURERS', 'no', 'left', 1, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Manufacturers', 'infobox'),
(1, 4, 'order_history.php', 'BOX_HEADING_CUSTOMER_ORDERS', 'no', 'left', 0, 9, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Order History', 'infobox'),
(1, 6, 'search.php', 'BOX_HEADING_SEARCH', 'no', 'left', 3, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Quick Find', 'infobox'),
(1, 7, 'shopping_cart.php', 'BOX_HEADING_SHOPPING_CART', 'no', 'left', 0, 1, '2020-03-21 00:00:00', '2020-01-01 00:00:00', 'Shopping Cart', 'infobox'),
(1, 8, 'specials.php', 'BOX_HEADING_SPECIALS', 'no', 'left', 0, 14, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Specials', 'infobox'),
(1, 9, 'whats_new.php', 'BOX_HEADING_WHATS_NEW', 'no', 'left', 0, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Latest products', 'infobox'),
(1, 11, 'theme_select.php', 'BOX_HEADING_TEMPLATE_SELECT', 'no', 'left', 0, 18, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Select Template', 'infobox'),
(1, 12, 'featured.php', 'BOX_HEADING_FEATURED', 'no', 'left', 0, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Featured Products', 'infobox'),
(1, 17, 'best_sellers.php', 'BOX_HEADING_BESTSELLERS', 'no', 'left', 13, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Best Sellers', 'infobox'),
(1, 18, 'categories.php', 'BOX_HEADING_CATEGORIES', 'yes', 'left', 1, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Our Shows', 'infobox'),
(1, 19, 'currencies.php', 'BOX_HEADING_CURRENCIES', 'no', 'left', 0, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Currencies', 'infobox'),
(1, 20, 'information.php', 'BOX_HEADING_INFORMATION', 'yes', 'left', 2, 5, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Information', 'infobox'),
(1, 21, 'loginbox.php', 'BOX_HEADING_LOGIN', 'no', 'left', 0, 22, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Login', 'infobox'),
(1, 22, 'wallet.php', 'BOX_HEADING_WALLET', 'no', 'left', 0, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'My Wallet', 'infobox'),
(1, 23, 'featured_categories.php', 'BOX_HEADING_FEATURED_CATEGORIES', 'no', 'left', 0, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Featured Events', 'infobox'),
(1, 24, 'donate.php', 'BOX_HEADING_DONATE', 'no', 'left', 0, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Donate', 'infobox'),
(1, 25, 'banner.php', 'BOX_HEADING_BANNER', 'no', 'left', 0, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Banner', 'infobox'),
(1, 26, 'box_office.php', 'BOX_HEADING_BOX_OFFICE', 'no', 'left', 1, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Box Office', 'infobox'),
(2, 27, 'languages.php', 'BOX_HEADING_LANGUAGES', 'no', 'left', 3, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Languages', 'infobox'),
(2, 28, 'manufacturer_info.php', 'BOX_HEADING_MANUFACTURER_INFO', 'no', 'left', 0, 7, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Manufacturers Info', 'infobox'),
(2, 29, 'manufacturers.php', 'BOX_HEADING_MANUFACTURERS', 'no', 'left', 0, 8, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Manufacturers', 'infobox'),
(2, 30, 'order_history.php', 'BOX_HEADING_CUSTOMER_ORDERS', 'yes', 'left', 2, 9, '2020-04-21 00:00:00', '2020-01-01 00:00:00', 'Order History', 'infobox'),
(2, 32, 'search.php', 'BOX_HEADING_SEARCH', 'no', 'left', 4, 12, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Quick Find', 'infobox'),
(2, 33, 'shopping_cart.php', 'BOX_HEADING_SHOPPING_CART', 'no', 'left', 0, 1, '2020-04-21 00:00:00', '2020-01-01 00:00:00', 'Shopping Cart', 'infobox'),
(2, 34, 'specials.php', 'BOX_HEADING_SPECIALS', 'no', 'left', 0, 14, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Specials', 'infobox'),
(2, 35, 'whats_new.php', 'BOX_HEADING_WHATS_NEW', 'no', 'left', 0, 16, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Latest products', 'infobox'),
(2, 37, 'theme_select.php', 'BOX_HEADING_TEMPLATE_SELECT', 'no', 'left', 0, 18, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Select Template', 'infobox'),
(2, 38, 'featured.php', 'BOX_HEADING_FEATURED', 'no', 'left', 0, 19, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Featured Tickets', 'infobox'),
(2, 43, 'best_sellers.php', 'BOX_HEADING_BESTSELLERS', 'no', 'left', 0, 1, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Best Sellers', 'infobox'),
(2, 44, 'categories.php', 'BOX_HEADING_CATEGORIES', 'yes', 'left', 1, 2, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Our Shows', 'infobox'),
(2, 45, 'currencies.php', 'BOX_HEADING_CURRENCIES', 'no', 'left', 0, 3, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Currencies', 'infobox'),
(2, 46, 'information.php', 'BOX_HEADING_INFORMATION', 'yes', 'left', 3, 5, '2020-04-21 00:00:00', '2020-01-01 00:00:00', 'Information', 'infobox'),
(2, 47, 'loginbox.php', 'BOX_HEADING_LOGIN', 'no', 'left', 0, 22, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Login', 'infobox'),
(2, 48, 'wallet.php', 'BOX_HEADING_WALLET', 'no', 'left', 0, 6, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'My Wallet', 'infobox'),
(2, 49, 'featured_categories.php', 'BOX_HEADING_FEATURED_CATEGORIES', 'no', 'left', 0, 4, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Featured Events', 'infobox'),
(2, 50, 'donate.php', 'BOX_HEADING_DONATE', 'no', 'left', 0, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Donate', 'infobox'),
(2, 51, 'banner.php', 'BOX_HEADING_BANNER', 'no', 'left', 0, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Banner', 'infobox'),
(2, 52, 'box_office.php', 'BOX_HEADING_BOX_OFFICE', 'no', 'left', 4, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 'Box Office', ''),
(3, 53, 'languages.php', 'BOX_HEADING_', 'no', 'left', 0, 6, NULL, '2020-04-16 00:00:00', 'Languages', 'infobox'),
(3, 54, 'manufacturer_info.php', 'BOX_HEADING_', 'no', 'left', 0, 7, NULL, '2020-04-16 00:00:00', 'Manufacturers Info', 'infobox'),
(3, 55, 'manufacturers.php', 'BOX_HEADING_', 'no', 'left', 1, 8, NULL, '2020-04-16 00:00:00', 'Manufacturers', 'infobox'),
(3, 56, 'order_history.php', 'BOX_HEADING_', 'no', 'left', 0, 9, NULL, '2020-04-16 00:00:00', 'Order History', 'infobox'),
(3, 57, 'search.php', 'BOX_HEADING_', 'no', 'left', 3, 12, NULL, '2020-04-16 00:00:00', 'Quick Find', 'infobox'),
(3, 58, 'shopping_cart.php', 'BOX_HEADING_', 'no', 'left', 0, 1, NULL, '2020-04-16 00:00:00', 'Shopping Cart', 'infobox'),
(3, 59, 'specials.php', 'BOX_HEADING_', 'no', 'left', 0, 14, NULL, '2020-04-16 00:00:00', 'Specials', 'infobox'),
(3, 60, 'whats_new.php', 'BOX_HEADING_', 'no', 'left', 0, 16, NULL, '2020-04-16 00:00:00', 'Latest products', 'infobox'),
(3, 61, 'theme_select.php', 'BOX_HEADING_', 'no', 'left', 0, 18, NULL, '2020-04-16 00:00:00', 'Select Template', 'infobox'),
(3, 62, 'featured.php', 'BOX_HEADING_', 'no', 'left', 0, 4, NULL, '2020-04-16 00:00:00', 'Featured Products', 'infobox'),
(3, 63, 'best_sellers.php', 'BOX_HEADING_', 'no', 'left', 13, 1, NULL, '2020-04-16 00:00:00', 'Best Sellers', 'infobox'),
(3, 64, 'categories.php', 'BOX_HEADING_', 'yes', 'left', 1, 2, NULL, '2020-04-16 00:00:00', 'Our Shows', 'infobox'),
(3, 65, 'currencies.php', 'BOX_HEADING_', 'no', 'left', 0, 3, NULL, '2020-04-16 00:00:00', 'Currencies', 'infobox'),
(3, 66, 'information.php', 'BOX_HEADING_', 'yes', 'left', 2, 5, NULL, '2020-04-16 00:00:00', 'Information', 'infobox'),
(3, 67, 'loginbox.php', 'BOX_HEADING_', 'no', 'left', 0, 22, NULL, '2020-04-16 00:00:00', 'Login', 'infobox'),
(3, 68, 'wallet.php', 'BOX_HEADING_', 'no', 'left', 0, 6, NULL, '2020-04-16 00:00:00', 'My Wallet', 'infobox'),
(3, 69, 'featured_categories.php', 'BOX_HEADING_', 'no', 'left', 0, 4, NULL, '2020-04-16 00:00:00', 'Featured Events', 'infobox'),
(3, 70, 'donate.php', 'BOX_HEADING_', 'no', 'left', 0, 0, NULL, '2020-04-16 00:00:00', 'Donate', 'infobox'),
(3, 71, 'banner.php', 'BOX_HEADING_', 'no', 'left', 0, 0, NULL, '2020-04-16 00:00:00', 'Banner', 'infobox'),
(3, 72, 'box_office.php', 'BOX_HEADING_', 'no', 'left', 1, 0, NULL, '2020-04-16 00:00:00', 'Box Office', 'infobox'),
(4, 73, 'languages.php', 'BOX_HEADING_', 'no', 'left', 0, 6, NULL, '2020-04-21 00:00:00', 'Languages', 'infobox'),
(4, 74, 'manufacturer_info.php', 'BOX_HEADING_', 'no', 'left', 0, 7, NULL, '2020-04-21 00:00:00', 'Manufacturers Info', 'infobox'),
(4, 75, 'manufacturers.php', 'BOX_HEADING_', 'no', 'left', 1, 8, NULL, '2020-04-21 00:00:00', 'Manufacturers', 'infobox'),
(4, 76, 'order_history.php', 'BOX_HEADING_', 'no', 'left', 0, 9, NULL, '2020-04-21 00:00:00', 'Order History', 'infobox'),
(4, 77, 'search.php', 'BOX_HEADING_', 'no', 'left', 3, 12, NULL, '2020-04-21 00:00:00', 'Quick Find', 'infobox'),
(4, 78, 'shopping_cart.php', 'BOX_HEADING_', 'no', 'left', 0, 1, NULL, '2020-04-21 00:00:00', 'Shopping Cart', 'infobox'),
(4, 79, 'specials.php', 'BOX_HEADING_', 'no', 'left', 0, 14, NULL, '2020-04-21 00:00:00', 'Specials', 'infobox'),
(4, 80, 'whats_new.php', 'BOX_HEADING_', 'no', 'left', 0, 16, NULL, '2020-04-21 00:00:00', 'Latest products', 'infobox'),
(4, 81, 'theme_select.php', 'BOX_HEADING_', 'no', 'left', 0, 18, NULL, '2020-04-21 00:00:00', 'Select Template', 'infobox'),
(4, 82, 'featured.php', 'BOX_HEADING_', 'no', 'left', 0, 4, NULL, '2020-04-21 00:00:00', 'Featured Products', 'infobox'),
(4, 83, 'best_sellers.php', 'BOX_HEADING_', 'no', 'left', 13, 1, NULL, '2020-04-21 00:00:00', 'Best Sellers', 'infobox'),
(4, 84, 'categories.php', 'BOX_HEADING_', 'yes', 'left', 1, 2, NULL, '2020-04-21 00:00:00', 'Our Shows', 'infobox'),
(4, 85, 'currencies.php', 'BOX_HEADING_', 'no', 'left', 0, 3, NULL, '2020-04-21 00:00:00', 'Currencies', 'infobox'),
(4, 86, 'information.php', 'BOX_HEADING_', 'yes', 'left', 2, 5, NULL, '2020-04-21 00:00:00', 'Information', 'infobox'),
(4, 87, 'loginbox.php', 'BOX_HEADING_', 'no', 'left', 0, 22, NULL, '2020-04-21 00:00:00', 'Login', 'infobox'),
(4, 88, 'wallet.php', 'BOX_HEADING_', 'no', 'left', 0, 6, NULL, '2020-04-21 00:00:00', 'My Wallet', 'infobox'),
(4, 89, 'featured_categories.php', 'BOX_HEADING_', 'no', 'left', 0, 4, NULL, '2020-04-21 00:00:00', 'Featured Events', 'infobox'),
(4, 90, 'donate.php', 'BOX_HEADING_', 'no', 'left', 0, 0, NULL, '2020-04-21 00:00:00', 'Donate', 'infobox'),
(4, 91, 'banner.php', 'BOX_HEADING_', 'no', 'left', 0, 0, NULL, '2020-04-21 00:00:00', 'Banner', 'infobox'),
(4, 92, 'box_office.php', 'BOX_HEADING_', 'no', 'left', 1, 0, NULL, '2020-04-21 00:00:00', 'Box Office', 'infobox');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `languages_id` int(11) NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `directory` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` int(3) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languages_id`, `name`, `code`, `image`, `directory`, `sort_order`) VALUES
(1, 'English', 'en', 'icon.gif', 'english', 1);

-- --------------------------------------------------------

--
-- Table structure for table `main_pages`
--

CREATE TABLE `main_pages` (
  `page_id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `page_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_pages`
--

INSERT INTO `main_pages` (`page_id`, `parent_id`, `date_created`, `date_modified`, `sort_order`, `page_status`) VALUES
(1, 0, '2020-01-01 00:00:00', '2021-10-30 00:00:00', 1, 1),
(2, 0, '2020-01-01 00:00:00', '2021-10-30 00:00:00', 2, 1),
(3, 0, '2020-01-01 00:00:00', '2021-10-30 00:00:00', 3, 1),
(4, 0, '2020-01-01 00:00:00', '2021-10-30 00:00:00', 4, 1),
(5, 0, '2020-01-01 00:00:00', '2020-01-01 00:00:00', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `main_page_description`
--

CREATE TABLE `main_page_description` (
  `page_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `page_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_id` int(11) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_page_description`
--

INSERT INTO `main_page_description` (`page_id`, `page_name`, `description`, `language_id`) VALUES
(1, 'Ticket Delivery', '<p>Thanks for your booking. Enjoy the show!</p>', 1),
(2, 'Privacy Notice', '<h2><strong>PRIVACY POLICY<br /></strong></h2><p><strong>Introduction</strong><br />This policy sets out how we collect, process and hold your personal data if you visit our event ticket shop or otherwise provide personal data to us. We are Jollof N Laugh of 68 The Green, UB2 4BG. We are the data controller of your personal data.<br /><br />This policy affects your legal rights and obligations so please read it carefully. If you have any questions, please contact us at bookings@jollofnlaugh.com or call us on&nbsp;<span style=\"background-color: #f5f5f5; color: #333333; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 12px;\">0744 426 5568</span>.</p><p><strong>Personal data we collect</strong><br /><br />We collect, process, store and use personal data when you book a ticket to an event including your name, address and email address together with payment information. We may also collect personal data that you give to us about other people if you register them to attend an event. You agree that you have notified any other person whose personal data that you provide to us of this privacy notice and, where necessary, obtained their consent so that we can lawfully process their personal data in accordance with this policy.<br /><br />All personal data that you provide to us must be true, complete and accurate. If you provide us with inaccurate or false data, and we suspect or identify fraud, we will record this.<br /><br />You do not need to provide us with any personal data to view our event ticket shop. However, we may still collect the information set under the Data we automatically collect section of this policy, and marketing communications in accordance with the Marketing Communications section of this policy.<br /><br />When you contact us by email or post, we may keep a record of the correspondence and we may also record any telephone call we have with you.</p><p><strong>Data we automatically collect</strong><br /><br />When you visit our event ticket shop, we, or third parties on our behalf, automatically collect and store information about your device and your activities. This information could include (a) your computer or other device\'s unique ID number; (b) technical information about your device such as type of device, web browser or operating system; (c) your preferences and settings such as time zone and language; and (d) statistical data about your browsing actions and patterns. We collect this information using cookies in accordance with the Cookie section of this policy and we use the information we collect on an anonymous basis to improve our event ticket shop, our events and the services we provide, and for analytical and research purposes.</p><p><strong>Marketing Communications</strong><br /><br />If you opt in to receive marketing communications from us you consent to the processing of your data to send you such communications, which may include newsletters, blog posts, surveys and information about new events. We retain a record of your consent.<br /><br />You can choose to no longer receive marketing communications by contacting us at webmaster@osconcert.com or clicking unsubscribe from a marketing email. If you do unsubscribe to marketing communications, it may take up to 5 business days for your new preferences to take effect. We shall therefore retain your personal data in our records for marketing purposes until you notify us that you no longer wish to receive marketing emails from us.</p><p><strong>Lawful processing of your personal data</strong><br /><br />We will use your personal data in order to comply with our contractual obligation to supply to you the tickets to an event that you have booked, including to contact you with any information relating to the event, to deliver the event to you in accordance with any requests you make and that we agree to, and to deal with any questions, comments or complaints you have in relation to the event.<br /><br />We may also use your personal data for our legitimate interests, including dealing with any customer services you require, enforcing the terms of any other agreement between us, for regulatory and legal purposes (for example anti-money laundering), for audit purposes and to contact you about changes to this policy.</p><p><strong>Who do we share your data with?</strong><br /><br />We may share your personal data with any service providers, sub-contractors and agents that we may appoint to perform functions on our behalf and in accordance with our instructions, including payment providers, event ticketing providers, email communication providers, IT service providers, accountants, auditors and lawyers.<br /><br />Under certain circumstances we may have to disclose your personal data under applicable laws and/or regulations, for example, as part of anti-money laundering processes or protect a third party\'s rights, property, or safety.<br /><br />We may also share your personal data in connection with, or during negotiations of, any merger, sale of assets, consolidation or restructuring, financing, or acquisition of all or a portion of our business by or into another company.</p><p><strong>Where we hold and process your personal data</strong><br /><br />Some or all of your personal data may be stored or transferred outside of the European Union (the EU) for any reason, including for example, if our email server is located in a country outside the EU or if any of our service providers or their servers are based outside of the EU. We shall only transfer your personal data to organisations that have provided adequate safeguards in respect of your personal data.</p><p><strong>Cookies</strong><br /><br />A cookie is a small text file containing a unique identification number that is transferred (through your browser) from a website to the hard drive of your computer. The cookie identifies your browser but will not let a website know any personal data about you, such as your name and/or address. These files are then used by websites to identify when users revisit that website.<br /><br />Our event ticket shop uses cookies so that we can recognise you when you return and personalise your settings and preferences. Most browsers are initially set up to accept cookies. You can change your browser settings either to notify you when you have received a cookie, or to refuse to accept cookies. Please note that our event ticket shop may not operate efficiently if you refuse to accept cookies.<br /><br />We also use Google Analytics to monitor how the event ticket shop is used. Google Analytics collects information anonymously and generates reports detailing information such as the number of visits to the event ticket shop, where visitors generally came from, how long they stayed on the event ticket shop, and which pages they visited. Google Analytics places several persistent cookies on your computers hard drive. These do not collect any personal data. If you do not agree to this you can disable persistent cookies in your browser. This will prevent Google Analytics from logging your visits.</p><p><strong>Security</strong><br /><br />We shall process your personal data in a manner that ensures appropriate security of the personal data, including protection against unauthorised or unlawful processing and against accidental loss, destruction or damage, using appropriate technical or organisational measures. All information you provide to us is stored on our secure servers. Any payment transactions are encrypted using SSL technology.<br /><br />Where we have given, or you have chosen a password, you are responsible for keeping this password confidential.<br /><br />However, you acknowledge that no system can be completely secure. Therefore, although we take these steps to secure your personal data, we do not promise that your personal data will always remain completely secure.</p><p><strong>Your rights</strong><br /><br />You have the right to obtain from us a copy of the personal data that we hold for you, and to require us to correct errors in the personal data if it is inaccurate or incomplete. You also have the right at any time to require that we delete your personal data. To exercise these rights, or any other rights you may have under applicable laws, please contact us.<br /><br />Please note, we reserve the right to charge an administrative fee if your request is manifestly unfounded or excessive.<br /><br />If you have any complaints in relation to this policy or otherwise in relation to our processing of your personal data, you should contact the UK supervisory authority: the Information Commissioner, see www.ico.org.uk.<br /><br />Our event ticket shop may contain links to other sites of interest. Once you have used these links to leave our event ticket shop, you should note that we do not have any control over that other site. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this policy. You should exercise caution and look at the privacy policy applicable to the site in question.</p><p><strong>Retention</strong><br /><br />If you register with us, we shall retain your personal data until you close your account.<br /><br />If you receive marketing communications from us, we shall retain your personal data until you opt out of receiving such communications.<br /><br />If you have otherwise booked a ticket with us or contacted us with a question or comment, we shall retain your personal data for 6 months following such contact to respond to any further queries you might have.</p><p><strong>General</strong><br /><br />If any provision of this policy is held by a court of competent jurisdiction to be invalid or unenforceable, then such provision shall be construed, as nearly as possible, to reflect the intentions of the parties and all other provisions shall remain in full force and effect.<br /><br />This policy shall be governed by and construed in accordance with the law of England and Wales, and you agree to submit to the exclusive jurisdiction of the English Courts.<br /><br />We may change the terms of this policy from time to time. You are responsible for regularly reviewing this policy so that you are aware of any changes to it. If you continue to use our event ticket shop after the time we state the changes will take effect, you will have accepted the changes.</p>', 1),
(3, 'Terms of Service', '<div><p><strong>Acceptance of Terms.</strong> The following are the rules (\"Terms\") governing the use of the JollofNLaugh web site (\"Site\"). By using the Site, you agree to be bound by these Terms and to follow these Terms and all applicable laws and regulations governing the Site. JollofNLaugh reserves the right to change these Terms at any time by posting them on the Site. Links or content from other web sites (\"Third-Party Sites\") are governed by the Terms for the Third-Party Sites.</p><p><strong>Permitted Use.</strong> The information, images, and software (\"Content\") on this Site is the property of JollofNLaugh and/or third parties supplying information to the Site. The Content is protected by UK and international copyright laws. You are only authorized to view and to retain a copy of pages of this Site for your own personal use. Any reproduction, publication, or further distribution or public exhibition of materials provided at this site in whole or in part is strictly prohibited.</p><p><strong>Links.</strong> The Site may reference or link to Third-Party Sites. JOLLOFNLAUGH has no control over these Third-Party Sites or the content within them. JOLLOFNLAUGH can not guarantee that the content contained in these Third-Party Sites is complete, accurate, and/or legal. JOLLOFNLAUGH does not warrant that these Third Party Sites will not contain viruses or otherwise impact your computer. By using the Site to purchase products, search, or link to Third-Party Sites, you agree and understand that you may not make any claim against JOLLOFNLAUGH for any damages or losses, whatsoever, resulting from your use of the Site or Third Party Sites.</p><p><strong>Privacy.</strong> The privacy of our users is important. Please review our <a href=\"index.php?stcPath=2\">privacy policy</a>.</p><p>Children under the age of 13 are prohibited from submitting personal information to JOLLOFNLAUGH. Federal Law prohibits websites from collecting and/or distributing personal information from children without parental consent. At this time, JollofNLaugh has no effective means to obtain and track parental consent. Therefore, JollofNLaugh must prohibit the submission of personal information from children under the age of 13.</p><p><strong>DISCLAIMER</strong></p><p>JollofNLaugh does not promise that the Site or any services, such as email, links, products, pricing, or searches offered on the Site will be error-free, uninterrupted, nor that it will provide specific results from use of the Site or any Content, search or link on it. The Site, and its Content are delivered on an \"as-is\" and \"as-available\" basis. JollofNLaugh can not ensure that files you download from the Site will be free of viruses or contamination or destructive features.</p><p>JOLLOFNLAUGH DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING ANY IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. JOLLOFNLAUGH WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF THIS SITE, INCLUDING WITHOUT, LIMITATION, DIRECT, INDIRECT, INCIDENTAL, PUNITIVE AND CONSEQUENTIAL DAMAGES.</p><p><strong>Indemnity.</strong> You agree to indemnify and hold JollofNLaugh, its subsidiaries, affiliates, officers, agents and other partners and employees, harmless from any loss, liability, claim or demand, including reasonable attorney\'s fees, made by any third party due to or arising out of your use of the Site.</p></div>', 1),
(4, 'About us', '<p>EventsThroneVIP presents The Jollof N Laugh Show. A dinner, laugh, live music, and dance experience showcasing the best comedy, musical and entertainment talent. Join the first ever launch of this show on Sunday 5th December 2021 at the Tudor Rose Venue.</p><p><br />Our first event is hosted by A DOT COMEDIAN. there will be appearances by White Yardie, Christopher Savage, Kevin J &amp; more UK talent. We will also have live performances from NQOBIL&Eacute;, The Rara, and a host of UK upcoming artists.</p>', 1),
(5, 'TandC', '<p>Admin&gt;CMS&gt;Static Pages&gt;<strong>TandC</strong> (Do Not Change the Page Name)<br />Click the \'red\' button to \'green\'. Edit your Popup Conditions here for your Terms and Conditons Popup Window at CHECKOUT.<br />When editing is done click the \'green button to \'red\' and your Popup Conditions at CHECKOUT will be yours.<br /><br /></p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `manufacturers_id` int(11) NOT NULL,
  `manufacturers_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `manufacturers_image` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers_info`
--

CREATE TABLE `manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL DEFAULT 0,
  `languages_id` int(11) NOT NULL DEFAULT 0,
  `manufacturers_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url_clicked` int(5) NOT NULL DEFAULT 0,
  `date_last_click` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_category`
--

CREATE TABLE `menu_category` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu_category`
--

INSERT INTO `menu_category` (`id`, `category`) VALUES
(1, 'Music'),
(2, 'Sport'),
(3, 'Arts'),
(4, 'Theatre-&-Comedy'),
(5, 'Family-&-Attractions');

-- --------------------------------------------------------

--
-- Table structure for table `menu_subcategory`
--

CREATE TABLE `menu_subcategory` (
  `id` int(11) NOT NULL,
  `category_id` int(10) NOT NULL,
  `category` text NOT NULL,
  `subcategory` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu_subcategory`
--

INSERT INTO `menu_subcategory` (`id`, `category_id`, `category`, `subcategory`) VALUES
(1, 5, 'Family-&-Attractions', 'Family Shows'),
(2, 5, 'Family-&-Attractions', 'Exhibitions'),
(3, 2, 'Sport', 'Basketball'),
(9, 1, 'Music', 'Festivals'),
(10, 1, 'Music', 'Alternative-and-indie');

-- --------------------------------------------------------

--
-- Table structure for table `meta_tags`
--

CREATE TABLE `meta_tags` (
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `title` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meta_tags`
--

INSERT INTO `meta_tags` (`filename`, `date_modified`, `title`, `description`, `keywords`, `tag_id`) VALUES
('SHOW 01', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 1),
('SHOW 02', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 2),
('SHOW 03', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 3),
('SHOW 04', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 4),
('SHOW 05', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 5),
('SHOW 06', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 6),
('SHOW 07', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 7),
('SHOW 08', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 8),
('SHOW 09', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 9),
('SHOW 10', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 10),
('SHOW 11', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 11),
('SHOW 12', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 12),
('SHOW 13', '2016-11-03 00:00:00', '%%Categories_Name%%', '%%Categories_Name%%', '%%Categories_Name%%', 13);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `newsletters_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT 0,
  `locked` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL DEFAULT 0,
  `customers_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_username` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_company` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_street_address` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_suburb` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_city` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_state` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_country` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_address_format_id` int(5) NOT NULL DEFAULT 0,
  `customers_dummy_account` tinyint(3) UNSIGNED NOT NULL,
  `delivery_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_company` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_street_address` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_suburb` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_city` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_state` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_country` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `delivery_address_format_id` int(5) NOT NULL DEFAULT 0,
  `billing_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_company` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_street_address` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_suburb` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_city` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_postcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_state` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_country` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `billing_address_format_id` int(5) NOT NULL DEFAULT 0,
  `payment_method` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_owner` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_number` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_expires` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL DEFAULT 0,
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `payment_info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_cvv_number` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_second_telephone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_second_email_address` varchar(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_fax` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_value_date` date DEFAULT NULL,
  `question_value_text` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_value_number` decimal(15,4) DEFAULT NULL,
  `ticket_printed` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `reference_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payment_return1` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payment_return2` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `owd_cost` decimal(15,4) NOT NULL,
  `shipping_method` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shipping_date` date DEFAULT NULL,
  `shipping_weight` decimal(5,2) DEFAULT NULL,
  `date_paid` datetime DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_module` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bor_random_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bor_expiry` decimal(10,4) DEFAULT NULL,
  `bor_datetime` datetime DEFAULT NULL,
  `customers_language` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orders_id`, `customers_id`, `customers_name`, `customers_username`, `customers_company`, `customers_street_address`, `customers_suburb`, `customers_city`, `customers_postcode`, `customers_state`, `customers_country`, `customers_telephone`, `customers_email_address`, `customers_address_format_id`, `customers_dummy_account`, `delivery_name`, `delivery_company`, `delivery_email`, `delivery_street_address`, `delivery_suburb`, `delivery_city`, `delivery_postcode`, `delivery_state`, `delivery_country`, `delivery_address_format_id`, `billing_name`, `billing_company`, `billing_email`, `billing_street_address`, `billing_suburb`, `billing_city`, `billing_postcode`, `billing_state`, `billing_country`, `billing_address_format_id`, `payment_method`, `cc_type`, `cc_owner`, `cc_number`, `cc_expires`, `last_modified`, `date_purchased`, `orders_status`, `orders_date_finished`, `currency`, `currency_value`, `payment_info`, `cc_cvv_number`, `customers_second_telephone`, `customers_second_email_address`, `customers_fax`, `question_value_date`, `question_value_text`, `question_value_number`, `ticket_printed`, `reference_id`, `payment_return1`, `payment_return2`, `owd_cost`, `shipping_method`, `shipping_date`, `shipping_weight`, `date_paid`, `ip_address`, `shipping_module`, `bor_random_id`, `bor_expiry`, `bor_datetime`, `customers_language`) VALUES
(9, 2, 'box office', 'boxoffice', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', '1234567890', 'boxoffice@osconcert.com', 1, 0, 'box office', '', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', 1, 'box office', '', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', 1, 'Box Office Staff', '', '', '', '', '2021-11-03 21:52:02', '2021-11-03 21:52:02', 3, NULL, 'GBP', 1.000000, '', '', '', '', '', NULL, NULL, NULL, 'Y', 'boxof1635976193', '', '', 0.0000, '', NULL, 0.00, '2021-11-03 00:00:00', '90.205.210.162', '', NULL, NULL, NULL, '1'),
(10, 6, 'Don Don', '', '', '', '', '', '', 'Greater London', 'United Kingdom', '077725865855', 'don@thelokdon.com', 1, 0, 'Don Don', '', 'don@thelokdon.com', '', '', '', '', 'Greater London', 'United Kingdom', 1, 'Don Don', '', 'don@thelokdon.com', '', '', '', '', 'Greater London', 'United Kingdom', 1, 'Reservations', '', '', '', '', '2021-11-03 22:00:46', '2021-11-03 22:00:46', 3, NULL, 'GBP', 1.000000, '', '', '', '', '', NULL, NULL, NULL, 'Y', '1635976833', '', '', 0.0000, '', NULL, 0.00, '2021-11-03 00:00:00', '90.205.210.162', '', NULL, NULL, NULL, '1'),
(11, 2, 'box office', 'boxoffice', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', '1234567890', 'boxoffice@osconcert.com', 1, 0, 'box office', '', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', 1, 'box office', '', '', 'box office', '', 'Box Office', 'boxoffice', '', 'Box Office', 1, 'Box Office Staff', '', '', '', '', '2021-11-04 16:53:28', '2021-11-04 16:53:28', 3, NULL, 'GBP', 1.000000, '', '', '', '', '', NULL, NULL, NULL, 'Y', 'boxof1636044797', '', '', 0.0000, '', NULL, 0.00, '2021-11-04 00:00:00', '90.205.210.162', '', NULL, NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `orders_barcode`
--

CREATE TABLE `orders_barcode` (
  `barcode_id` bigint(255) NOT NULL,
  `orders_id` int(255) NOT NULL DEFAULT 0,
  `products_id` bigint(255) NOT NULL DEFAULT 0,
  `showtime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `barcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created` int(50) NOT NULL DEFAULT 0,
  `scanned` int(1) NOT NULL DEFAULT 0,
  `scanned_date` int(50) NOT NULL DEFAULT 0,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_barcode`
--

INSERT INTO `orders_barcode` (`barcode_id`, `orders_id`, `products_id`, `showtime`, `products_name`, `barcode`, `created`, `scanned`, `scanned_date`, `location`, `data`) VALUES
(15, 9, 16, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 6', '9_16_1', 1635976322, 0, 0, '', '{\"products_id\":\"16\",\"customers_id\":\"2\",\"orders_id\":\"9\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1635976193\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 6\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00009000016001982949\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"30.0000\"}'),
(16, 9, 17, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 7', '9_17_1', 1635976322, 0, 0, '', '{\"products_id\":\"17\",\"customers_id\":\"2\",\"orders_id\":\"9\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1635976193\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 7\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00009000017001392967\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"30.0000\"}'),
(17, 9, 18, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 8', '9_18_1', 1635976322, 0, 0, '', '{\"products_id\":\"18\",\"customers_id\":\"2\",\"orders_id\":\"9\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1635976193\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 8\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00009000018001202612\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"25.0000\"}'),
(18, 9, 19, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 9', '9_19_1', 1635976322, 0, 0, '', '{\"products_id\":\"19\",\"customers_id\":\"2\",\"orders_id\":\"9\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1635976193\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 9\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00009000019001846182\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"25.0000\"}'),
(19, 10, 33, 'Sunday, 5th December 6:00 pm', 'Executive Table 2 Seat 1', '10_33_1', 1635976846, 0, 0, '', '{\"products_id\":\"33\",\"customers_id\":\"6\",\"orders_id\":\"10\",\"customers_firstname\":\"Don\",\"customers_lastname\":\"Don\",\"customers_name\":\"Don Don\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"1635976833\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Executive Table 2 Seat 1\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"2030-01-01 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00010000033001495870\",\"billing_name\":\"Don Don\",\"payment_method\":\"Reservations\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"40.0000\"}'),
(20, 11, 16, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 6', '11_16_1', 1636044808, 0, 0, '', '{\"products_id\":\"16\",\"customers_id\":\"2\",\"orders_id\":\"11\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1636044797\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 6\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00011000016001979916\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"30.0000\"}'),
(21, 11, 17, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 7', '11_17_1', 1636044808, 0, 0, '', '{\"products_id\":\"17\",\"customers_id\":\"2\",\"orders_id\":\"11\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1636044797\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 7\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00011000017001405851\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"30.0000\"}'),
(22, 11, 18, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 8', '11_18_1', 1636044808, 0, 0, '', '{\"products_id\":\"18\",\"customers_id\":\"2\",\"orders_id\":\"11\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1636044797\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 8\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00011000018001992064\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"25.0000\"}'),
(23, 11, 19, 'Sunday, 5th December 6:00 pm', 'Right Side Table Seat 9', '11_19_1', 1636044808, 0, 0, '', '{\"products_id\":\"19\",\"customers_id\":\"2\",\"orders_id\":\"11\",\"customers_firstname\":\"box\",\"customers_lastname\":\"office\",\"customers_name\":\"box office\",\"customers_groups_id\":\"1\",\"run\":\"_1\",\"reference_id\":\"boxof1636044797\",\"products_model\":\"05-12-2021 1800\",\"products_name\":\"Right Side Table Seat 9\",\"concert_venue\":\"Tuder Rose\",\"concert_date\":\"Sunday, 5th December\",\"concert_time\":\"6:00 pm\",\"products_date_available\":\"0000-00-00 00:00:00\",\"events_type\":\"P\",\"unique_number\":\"00011000019001631973\",\"billing_name\":\"box office\",\"payment_method\":\"Box Office Staff\",\"manufacturers_name\":null,\"categories_name\":\"JollofNLaugh\",\"products_price\":\"25.0000\"}');

-- --------------------------------------------------------

--
-- Table structure for table `orders_products`
--

CREATE TABLE `orders_products` (
  `orders_products_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT 0,
  `products_id` int(11) NOT NULL DEFAULT 0,
  `products_model` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_price` decimal(15,4) DEFAULT NULL,
  `final_price` decimal(15,4) DEFAULT NULL,
  `products_tax` decimal(7,4) DEFAULT NULL,
  `products_quantity` int(2) NOT NULL DEFAULT 0,
  `products_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P',
  `events_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `waitlist_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `events_fees` decimal(15,4) DEFAULT NULL,
  `waitlist_orders_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `orders_products_status` tinyint(1) DEFAULT 0,
  `invoice_printed` tinyint(3) UNSIGNED DEFAULT 0,
  `packing_slip_printed` tinyint(3) UNSIGNED DEFAULT 0,
  `invoice_printed_time` datetime DEFAULT NULL,
  `packslip_printed_time` datetime DEFAULT NULL,
  `coupon_amount` double(15,4) DEFAULT NULL,
  `products_sku` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idcards_printed` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `support_packs_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'G',
  `discount_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_text` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_id` int(11) NOT NULL DEFAULT 0,
  `events_id` int(11) NOT NULL DEFAULT 0,
  `categories_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `concert_venue` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `concert_date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `concert_time` varchar(28) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_printable` tinyint(1) NOT NULL DEFAULT 1,
  `products_season` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bor_random_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bor_expiry` decimal(10,4) DEFAULT NULL,
  `bor_datetime` datetime DEFAULT NULL,
  `products_date_available` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_products`
--

INSERT INTO `orders_products` (`orders_products_id`, `orders_id`, `products_id`, `products_model`, `products_name`, `products_price`, `final_price`, `products_tax`, `products_quantity`, `products_type`, `events_type`, `waitlist_type`, `events_fees`, `waitlist_orders_id`, `orders_products_status`, `invoice_printed`, `packing_slip_printed`, `invoice_printed_time`, `packslip_printed_time`, `coupon_amount`, `products_sku`, `idcards_printed`, `support_packs_type`, `discount_type`, `discount_text`, `discount_id`, `events_id`, `categories_name`, `concert_venue`, `concert_date`, `concert_time`, `is_printable`, `products_season`, `bor_random_id`, `bor_expiry`, `bor_datetime`, `products_date_available`) VALUES
(18, 9, 19, '05-12-2021 1800', 'Right Side Table Seat 9', 25.0000, 25.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(19, 9, 18, '05-12-2021 1800', 'Right Side Table Seat 8', 25.0000, 25.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(20, 9, 17, '05-12-2021 1800', 'Right Side Table Seat 7', 30.0000, 30.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(21, 9, 16, '05-12-2021 1800', 'Right Side Table Seat 6', 30.0000, 30.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(22, 10, 33, '05-12-2021 1800', 'Executive Table 2 Seat 1', 40.0000, 40.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '2030-01-01 00:00:00'),
(23, 11, 19, '05-12-2021 1800', 'Right Side Table Seat 9', 25.0000, 25.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(24, 11, 18, '05-12-2021 1800', 'Right Side Table Seat 8', 25.0000, 25.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(25, 11, 17, '05-12-2021 1800', 'Right Side Table Seat 7', 30.0000, 30.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00'),
(26, 11, 16, '05-12-2021 1800', 'Right Side Table Seat 6', 30.0000, 30.0000, 0.0000, 1, 'P', 'P', NULL, NULL, 0, 3, 0, 0, NULL, NULL, 0.0000, '1', 'N', 'P', '', '', 0, 0, 'JollofNLaugh', 'Tuder Rose', 'Sunday, 5th December', '6:00 pm', 1, '', NULL, NULL, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders_products_download`
--

CREATE TABLE `orders_products_download` (
  `orders_products_download_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT 0,
  `orders_products_id` int(11) NOT NULL DEFAULT 0,
  `orders_products_filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `download_maxdays` int(2) NOT NULL DEFAULT 0,
  `download_count` int(2) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders_status`
--

CREATE TABLE `orders_status` (
  `orders_status_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `orders_status_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_status`
--

INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`) VALUES
(9, 1, 'AVS not checked, payment taken'),
(7, 1, 'AVS unsuccessful, payment taken'),
(4, 1, 'Backorder'),
(8, 1, 'CVV - not checked, payment taken'),
(6, 1, 'CVV error, payment taken'),
(3, 1, 'Delivered'),
(1, 1, 'Pending'),
(2, 1, 'Processed'),
(5, 1, 'Refunded'),
(10, 1, 'Stripe:cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `orders_status_history`
--

CREATE TABLE `orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT 0,
  `orders_status_id` int(5) NOT NULL DEFAULT 0,
  `date_added` datetime DEFAULT NULL,
  `customer_notified` int(1) DEFAULT 0,
  `comments` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_1` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_3` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_4` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `other` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_added` varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_status_history`
--

INSERT INTO `orders_status_history` (`orders_status_history_id`, `orders_id`, `orders_status_id`, `date_added`, `customer_notified`, `comments`, `field_1`, `field_2`, `field_3`, `field_4`, `other`, `user_added`) VALUES
(16, 11, 3, '2021-11-04 16:53:28', 0, ' ', '', '', '', '', '', 'web'),
(15, 10, 3, '2021-11-03 22:00:46', 0, ' ', '', '', '', '', '', 'web'),
(14, 9, 3, '2021-11-03 21:52:02', 0, ' Shoobs Orders Yaw Wright & Carol Cotterell', '', '', '', '', '', 'web');

-- --------------------------------------------------------

--
-- Table structure for table `orders_tickets`
--

CREATE TABLE `orders_tickets` (
  `orders_tickets_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `orders_products_id_index` int(4) NOT NULL,
  `unique_id` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_tickets`
--

INSERT INTO `orders_tickets` (`orders_tickets_id`, `orders_id`, `orders_products_id`, `orders_products_id_index`, `unique_id`) VALUES
(15, 9, 16, 1, '00009000016001982949'),
(16, 9, 17, 1, '00009000017001392967'),
(17, 9, 18, 1, '00009000018001202612'),
(18, 9, 19, 1, '00009000019001846182'),
(19, 10, 33, 1, '00010000033001495870'),
(20, 11, 16, 1, '00011000016001979916'),
(21, 11, 17, 1, '00011000017001405851'),
(22, 11, 18, 1, '00011000018001992064'),
(23, 11, 19, 1, '00011000019001631973');

-- --------------------------------------------------------

--
-- Table structure for table `orders_total`
--

CREATE TABLE `orders_total` (
  `orders_total_id` int(10) UNSIGNED NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(15,4) DEFAULT NULL,
  `class` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_total`
--

INSERT INTO `orders_total` (`orders_total_id`, `orders_id`, `title`, `text`, `value`, `class`, `sort_order`) VALUES
(20, 9, 'SubTotal:', '£110.00 ', 110.0000, 'ot_subtotal', 50),
(21, 9, 'Total:', '<b>£110.00 </b>', 110.0000, 'ot_total', 100),
(22, 10, '(per ticket) Booking Fee  (1 x £0.75 ) :', '£0.75 ', 0.7500, 'ot_service_fee', 10),
(23, 10, 'SubTotal:', '£40.75 ', 40.7500, 'ot_subtotal', 50),
(24, 10, 'Total:', '<b>£40.75 </b>', 40.7500, 'ot_total', 100),
(25, 11, 'SubTotal:', '£110.00 ', 110.0000, 'ot_subtotal', 50),
(26, 11, 'Total:', '<b>£110.00 </b>', 110.0000, 'ot_total', 100);

-- --------------------------------------------------------

--
-- Table structure for table `payment_response`
--

CREATE TABLE `payment_response` (
  `order_id` int(11) NOT NULL DEFAULT 0,
  `payment_response` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_response`
--

INSERT INTO `payment_response` (`order_id`, `payment_response`) VALUES
(2, 'agree => true||'),
(3, 'agree => true||'),
(4, 'agree => true||'),
(5, 'agree => true||'),
(6, 'agree => true||'),
(7, 'comments => box office Company BT||agree => true||'),
(8, 'agree => true||'),
(9, 'comments => Shoobs Orders Yaw Wright & Carol Cotterell||agree => true||'),
(10, 'agree => true||'),
(11, 'agree => true||');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `products_id` int(11) NOT NULL,
  `products_quantity` int(4) NOT NULL DEFAULT 0,
  `products_model` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_image_1` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_price` decimal(15,4) DEFAULT NULL,
  `color_code` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_date_added` datetime DEFAULT NULL,
  `products_last_modified` datetime DEFAULT NULL,
  `products_date_available` datetime DEFAULT NULL,
  `products_weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `products_status` tinyint(1) NOT NULL DEFAULT 0,
  `products_tax_class_id` int(11) NOT NULL DEFAULT 0,
  `manufacturers_id` int(11) NOT NULL DEFAULT 0,
  `products_ordered` int(11) NOT NULL DEFAULT 0,
  `products_title_1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_id` int(6) DEFAULT NULL,
  `parent_id` int(6) DEFAULT NULL,
  `products_sort_order` int(6) NOT NULL DEFAULT 0,
  `is_attributes` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `products_sku` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `author_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_price_break` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `restrict_to_groups` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `restrict_to_customers` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'G',
  `product_mode` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'P',
  `downloads_per_customer` int(11) NOT NULL DEFAULT 0,
  `download_last_date` date DEFAULT NULL,
  `download_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `download_no_of_days` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `master_quantity` int(11) NOT NULL,
  `products_season` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_x` int(11) NOT NULL,
  `products_y` int(11) NOT NULL,
  `products_w` int(11) NOT NULL,
  `products_h` int(11) NOT NULL,
  `products_r` int(11) NOT NULL,
  `products_sx` decimal(8,2) NOT NULL DEFAULT 0.00,
  `products_sy` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`products_id`, `products_quantity`, `products_model`, `products_image_1`, `products_price`, `color_code`, `products_date_added`, `products_last_modified`, `products_date_available`, `products_weight`, `products_status`, `products_tax_class_id`, `manufacturers_id`, `products_ordered`, `products_title_1`, `section_id`, `parent_id`, `products_sort_order`, `is_attributes`, `products_sku`, `author_name`, `products_price_break`, `restrict_to_groups`, `restrict_to_customers`, `product_type`, `product_mode`, `downloads_per_customer`, `download_last_date`, `download_link`, `download_no_of_days`, `master_quantity`, `products_season`, `products_x`, `products_y`, `products_w`, `products_h`, `products_r`, `products_sx`, `products_sy`) VALUES
(1, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 07:37:11', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 114, 36, 36, 0, 0.00, 0.00),
(2, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 07:40:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 154, 36, 36, 0, 0.00, 0.00),
(3, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:26:25', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 216, 36, 36, 0, 0.00, 0.00),
(4, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:26:52', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 260, 36, 36, 0, 0.00, 0.00),
(5, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:27:16', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 336, 36, 36, 0, 0.00, 0.00),
(6, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:27:45', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 380, 36, 36, 0, 0.00, 0.00),
(7, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:28:27', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 464, 36, 36, 0, 0.00, 0.00),
(8, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, '2021-10-20 11:48:15', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 508, 36, 36, 0, 0.00, 0.00),
(9, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 1, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 584, 36, 36, 0, 0.00, 0.00),
(10, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 1, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 16, 628, 36, 36, 0, 0.00, 0.00),
(11, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 10:29:19', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 84, 36, 36, 0, 0.00, 0.00),
(12, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 10:29:44', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 128, 36, 36, 0, 0.00, 0.00),
(13, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:29:46', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 212, 36, 36, 0, 0.00, 0.00),
(14, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:30:06', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 256, 36, 36, 0, 0.00, 0.00),
(15, 1, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:33:02', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 324, 36, 36, 0, 0.00, 0.00),
(16, 0, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:33:22', '0000-00-00 00:00:00', 0.00, 0, 0, 0, 1, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 368, 36, 36, 0, 0.00, 0.00),
(17, 0, '05-12-2021 1800', 'ticket_icon.png', 30.0000, 'blue', NULL, '2021-10-20 11:47:45', '0000-00-00 00:00:00', 0.00, 0, 0, 0, 1, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 468, 36, 36, 0, 0.00, 0.00),
(18, 0, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 0, 0, 0, 1, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 512, 36, 36, 0, 0.00, 0.00),
(19, 0, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 0, 0, 0, 1, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 588, 36, 36, 0, 0.00, 0.00),
(20, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 1, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 632, 36, 36, 0, 0.00, 0.00),
(21, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, NULL, NULL, 0.00, 1, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 1048, 708, 36, 36, 0, 0.00, 0.00),
(22, 1, '05-12-2021 1800', 'ticket_icon.png', 25.0000, 'red', NULL, '2021-10-17 19:34:48', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 1044, 754, 36, 36, 0, 0.00, 0.00),
(23, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:41:00', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 122, 36, 36, 0, 0.00, 0.00),
(24, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:41:14', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 140, 36, 36, 0, 0.00, 0.00),
(25, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:09:03', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 172, 36, 36, 0, 0.00, 0.00),
(26, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:09:16', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 300, 212, 36, 36, 0, 0.00, 0.00),
(27, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:40:42', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 276, 248, 36, 36, 0, 0.00, 0.00),
(28, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:37:10', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 268, 36, 36, 0, 0.00, 0.00),
(29, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:37:28', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 188, 248, 36, 36, 0, 0.00, 0.00),
(30, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:37:47', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 212, 36, 36, 0, 0.00, 0.00),
(31, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:38:03', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 172, 36, 36, 0, 0.00, 0.00),
(32, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:38:23', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 140, 36, 36, 0, 0.00, 0.00),
(33, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:38:37', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 120, 36, 36, 0, 0.00, 0.00),
(34, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:38:54', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 876, 136, 36, 36, 0, 0.00, 0.00),
(35, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:45:34', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 176, 36, 36, 0, 0.00, 0.00),
(36, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:45:17', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 220, 36, 36, 0, 0.00, 0.00),
(37, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:45:04', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 876, 252, 36, 36, 0, 0.00, 0.00),
(38, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:42:15', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 272, 36, 36, 0, 0.00, 0.00),
(39, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:44:16', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 788, 256, 36, 36, 0, 0.00, 0.00),
(40, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:44:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 756, 216, 36, 36, 0, 0.00, 0.00),
(41, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:44:48', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 756, 172, 36, 36, 0, 0.00, 0.00),
(42, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:43:16', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 788, 136, 36, 36, 0, 0.00, 0.00),
(43, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:19:12', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 320, 36, 36, 0, 0.00, 0.00),
(44, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:19:20', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 276, 340, 36, 36, 0, 0.00, 0.00),
(45, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:19:47', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 372, 36, 36, 0, 0.00, 0.00),
(46, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:20:11', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 416, 36, 36, 0, 0.00, 0.00),
(47, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:20:26', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 276, 456, 36, 36, 0, 0.00, 0.00),
(48, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:46:36', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 468, 36, 36, 0, 0.00, 0.00),
(49, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:46:50', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 448, 36, 36, 0, 0.00, 0.00),
(50, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:47:05', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 416, 36, 36, 0, 0.00, 0.00),
(51, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:47:18', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 164, 372, 36, 36, 0, 0.00, 0.00),
(52, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:22:12', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 340, 36, 36, 0, 0.00, 0.00),
(53, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:22:28', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 320, 36, 36, 0, 0.00, 0.00),
(54, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:22:41', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 872, 340, 36, 36, 0, 0.00, 0.00),
(55, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:47:39', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 372, 36, 36, 0, 0.00, 0.00),
(56, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:47:58', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 416, 36, 36, 0, 0.00, 0.00),
(57, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:48:11', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 876, 452, 36, 36, 0, 0.00, 0.00),
(58, 1, '05-12-2021 1800', 'ticket_icon.png', 45.0000, 'palegreen', NULL, '2021-10-20 13:48:23', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 464, 36, 36, 0, 0.00, 0.00),
(59, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:23:37', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 792, 448, 36, 36, 0, 0.00, 0.00),
(60, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:23:47', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 416, 36, 36, 0, 0.00, 0.00),
(61, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:23:55', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 376, 36, 36, 0, 0.00, 0.00),
(62, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'yellow', NULL, '2021-10-20 13:24:03', '0000-00-00 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 792, 340, 36, 36, 0, 0.00, 0.00),
(63, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:17:18', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 516, 36, 36, 0, 0.00, 0.00),
(64, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:17:34', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 532, 36, 36, 0, 0.00, 0.00),
(65, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:17:58', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 300, 568, 36, 36, 0, 0.00, 0.00),
(66, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:18:17', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 300, 612, 36, 36, 0, 0.00, 0.00),
(67, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:18:40', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 648, 36, 36, 0, 0.00, 0.00),
(68, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:20:08', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 664, 36, 36, 0, 0.00, 0.00),
(69, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:19:57', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 196, 644, 36, 36, 0, 0.00, 0.00),
(70, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:19:44', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 164, 608, 36, 36, 0, 0.00, 0.00),
(71, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:19:35', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 164, 568, 36, 36, 0, 0.00, 0.00),
(72, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:19:19', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 536, 36, 36, 0, 0.00, 0.00),
(73, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:24:43', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 828, 520, 36, 36, 0, 0.00, 0.00),
(74, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 00:50:17', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 868, 532, 36, 36, 0, 0.00, 0.00),
(75, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 00:51:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 900, 568, 36, 36, 0, 0.00, 0.00),
(76, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 05:25:02', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 900, 612, 36, 36, 0, 0.00, 0.00),
(77, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:01:40', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 868, 644, 36, 36, 0, 0.00, 0.00),
(78, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:01:27', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 664, 36, 36, 0, 0.00, 0.00),
(79, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:05:58', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 796, 644, 36, 36, 0, 0.00, 0.00),
(80, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:06:27', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 612, 36, 36, 0, 0.00, 0.00),
(81, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:06:49', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 568, 36, 36, 0, 0.00, 0.00),
(82, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:07:12', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 788, 536, 36, 36, 0, 0.00, 0.00),
(83, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:32:24', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 712, 36, 36, 0, 0.00, 0.00),
(84, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:32:15', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 276, 732, 36, 36, 0, 0.00, 0.00),
(85, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:32:07', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 772, 36, 36, 0, 0.00, 0.00),
(86, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:31:57', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 816, 36, 36, 0, 0.00, 0.00),
(87, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:29:00', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 848, 36, 36, 0, 0.00, 0.00),
(88, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-10-20 14:34:04', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 868, 36, 36, 0, 0.00, 0.00),
(89, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-10-20 14:34:14', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 848, 36, 36, 0, 0.00, 0.00),
(90, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-10-20 14:34:22', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 812, 36, 36, 0, 0.00, 0.00),
(91, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-10-20 14:34:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 768, 36, 36, 0, 0.00, 0.00),
(92, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:37:40', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 188, 732, 36, 36, 0, 0.00, 0.00),
(93, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-10-20 14:37:28', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 836, 716, 36, 36, 0, 0.00, 0.00),
(94, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:09:22', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 876, 732, 36, 36, 0, 0.00, 0.00),
(95, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:13:11', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 772, 36, 36, 0, 0.00, 0.00),
(96, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:12:50', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 816, 36, 36, 0, 0.00, 0.00),
(97, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:13:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 868, 848, 36, 36, 0, 0.00, 0.00),
(98, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:13:44', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 868, 36, 36, 0, 0.00, 0.00),
(99, 1, '05-12-2021 1800', 'ticket_icon.png', 40.0000, 'thistle', NULL, '2021-11-05 01:14:48', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 798, 846, 36, 36, 0, 0.00, 0.00),
(100, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:15:09', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 816, 36, 36, 0, 0.00, 0.00),
(101, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:15:30', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 760, 772, 36, 36, 0, 0.00, 0.00),
(102, 1, '05-12-2021 1800', 'ticket_icon.png', 35.0000, 'skyblue', NULL, '2021-11-05 01:15:48', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 792, 736, 36, 36, 0, 0.00, 0.00),
(103, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:40:23', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 920, 36, 36, 0, 0.00, 0.00),
(104, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:40:17', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 940, 36, 36, 0, 0.00, 0.00),
(105, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:40:12', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 976, 36, 36, 0, 0.00, 0.00),
(106, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:40:07', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 304, 1020, 36, 36, 0, 0.00, 0.00),
(107, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:40:02', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 272, 1056, 36, 36, 0, 0.00, 0.00),
(108, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:50', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 232, 1072, 36, 36, 0, 0.00, 0.00),
(109, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:46', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 192, 1056, 36, 36, 0, 0.00, 0.00),
(110, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:41', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 1016, 36, 36, 0, 0.00, 0.00),
(111, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:32', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 160, 972, 36, 36, 0, 0.00, 0.00),
(112, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:27', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 188, 940, 36, 36, 0, 0.00, 0.00),
(113, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:20', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 920, 36, 36, 0, 0.00, 0.00),
(114, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:14', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 872, 936, 36, 36, 0, 0.00, 0.00),
(115, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:39:05', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 972, 36, 36, 0, 0.00, 0.00),
(116, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:57', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 904, 1016, 36, 36, 0, 0.00, 0.00),
(117, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:52', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 872, 1052, 36, 36, 0, 0.00, 0.00),
(118, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:46', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 832, 1068, 36, 36, 0, 0.00, 0.00),
(119, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:42', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 788, 1052, 36, 36, 0, 0.00, 0.00),
(120, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:36', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 756, 1012, 36, 36, 0, 0.00, 0.00),
(121, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:32', NULL, 0.00, 8, 0, 0, 0, NULL, 1, 1, 0, '', '1', '', 'N', NULL, '', 'P', 'P', 0, NULL, '', '0', 0, '', 756, 968, 36, 36, 0, 0.00, 0.00),
(122, 0, '05-12-2021 1800', 'ticket_icon.png', 36.0000, 'skyblue', NULL, '2021-11-04 20:38:23', '0000-00-00 00:00:00', 0.00, 8, 0, 0, 0, '', 1, 1, 0, 'N', '1', 'N', 'N', '', '', 'P', 'P', 0, NULL, '', '0', 0, '', 792, 936, 36, 36, 0, 0.00, 0.00),
(123, 0, NULL, '', 0.0000, NULL, '2021-10-16 12:08:32', '2021-10-21 15:14:15', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 1, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, NULL, '', '0', 0, '', 428, 40, 250, 50, 0, 2.00, 2.00),
(124, 0, NULL, '', 0.0000, NULL, '2021-10-16 12:47:24', '2021-10-16 12:48:23', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, NULL, '', '0', 0, '', 212, 212, 75, 75, 0, 0.00, 0.00),
(125, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:49:48', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 808, 216, 75, 75, 0, 0.00, 0.00),
(126, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:50:00', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 216, 412, 75, 75, 0, 0.00, 0.00),
(127, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:50:13', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 812, 412, 75, 75, 0, 0.00, 0.00),
(128, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:50:44', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 212, 608, 75, 75, 0, 0.00, 0.00),
(129, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:51:18', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 808, 608, 75, 75, 0, 0.00, 0.00),
(130, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:51:30', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 212, 812, 75, 75, 0, 0.00, 0.00),
(131, 0, '', '', 0.0000, '', NULL, '2021-10-16 12:51:43', '2030-01-01 00:00:00', 0.00, 7, 0, 0, 0, '', 1, 1, 2, 'N', '0', 'N', 'N', '', '', 'Q', 'P', 0, '0000-00-00', '', '0', 0, '', 812, 808, 75, 75, 0, 0.00, 0.00),
(135, 0, '05-12-2021 1800', '', 0.0000, 'salmon', NULL, '2021-10-20 09:15:36', '2030-01-01 00:00:00', 0.00, 5, 0, 0, 0, '', 1, 1, 3, 'N', '0', 'N', 'N', '', '', 'L', 'P', 0, '0000-00-00', '', '0', 0, '', 446, 104, 200, 25, 0, 0.00, 0.00),
(136, 0, '05-12-2021 1800', '', 25.0000, 'red', NULL, '2021-10-16 13:42:36', '2030-01-01 00:00:00', 0.00, 4, 0, 0, 0, '', 1, 1, 3, 'N', '0', 'N', 'N', '', '', 'L', 'P', 0, '0000-00-00', '', '0', 0, '', -24, 356, 200, 25, 270, 0.00, 0.00),
(137, 0, '05-12-2021 1800', '', 25.0000, 'red', NULL, '2021-10-16 13:50:00', '2030-01-01 00:00:00', 0.00, 4, 0, 0, 0, '', 1, 1, 3, 'N', '0', 'N', 'N', '', '', 'L', 'P', 0, '0000-00-00', '', '0', 0, '', 920, 360, 200, 25, 90, 0.00, 0.00),
(138, 70, '05-12-2021 1800', 'earlybirdtickets1.jpg', 50.0000, 'fuchsia', '2021-10-16 14:17:42', '2021-10-20 22:13:27', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '9', 'N', 'N', '', '', 'G', 'P', 0, NULL, '', '0', 70, '', 0, 0, 44, 36, 0, 0.00, 0.00),
(141, 20, '05-12-2021 1800', '', 25.0000, 'red', NULL, '2021-10-25 06:35:42', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '9', 'N', 'N', '', '', 'G', 'P', 0, '0000-00-00', '', '0', 20, '', 0, 0, 44, 36, 0, 0.00, 0.00),
(142, 0, '05-12-2021 1800', '', 0.0000, 'fuchsia', NULL, '2021-10-20 08:57:56', '2030-01-01 00:00:00', 0.00, 5, 0, 0, 0, '', 1, 1, 3, 'N', '0', 'N', 'N', '', '', 'L', 'P', 0, '0000-00-00', '', '0', 0, '', 440, 472, 200, 25, 0, 0.00, 0.00),
(143, 50, '05-12-2021 1800', '', 20.0000, 'orange', NULL, '2021-10-25 06:35:51', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '9', 'N', 'N', '', '', 'G', 'P', 0, '0000-00-00', '', '0', 50, '', 0, 0, 44, 36, 0, 0.00, 0.00),
(144, 75, '05-12-2021 1800', '', 25.0000, 'teal', NULL, '2021-10-25 06:36:06', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '9', 'N', 'N', '', '', 'G', 'P', 0, '0000-00-00', '', '0', 75, '', 0, 0, 44, 36, 0, 0.00, 0.00),
(145, 50, '05-12-2021 1800', '', 30.0000, 'thistle', NULL, '2021-10-25 06:37:03', '2030-01-01 00:00:00', 0.00, 1, 0, 0, 0, '', 1, 1, 0, 'N', '9', 'N', 'N', '', '', 'G', 'P', 0, '0000-00-00', '', '0', 50, '', 0, 0, 44, 36, 0, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products_attributes`
--

CREATE TABLE `products_attributes` (
  `products_attributes_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT 0,
  `options_id` int(11) NOT NULL DEFAULT 0,
  `options_values_id` int(11) NOT NULL DEFAULT 0,
  `options_values_price` decimal(15,4) DEFAULT NULL,
  `price_prefix` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_options_sort_order` int(6) NOT NULL DEFAULT 0,
  `product_attributes_one_time` tinyint(1) NOT NULL DEFAULT 0,
  `products_attributes_weight` decimal(5,2) DEFAULT NULL,
  `products_attributes_weight_prefix` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '+',
  `products_attributes_units` int(4) NOT NULL DEFAULT 0,
  `products_attributes_units_price` decimal(15,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products_attributes_download`
--

CREATE TABLE `products_attributes_download` (
  `products_attributes_id` int(11) NOT NULL DEFAULT 0,
  `products_attributes_filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_attributes_maxdays` int(2) DEFAULT 0,
  `products_attributes_maxcount` int(2) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products_description`
--

CREATE TABLE `products_description` (
  `products_id` int(11) NOT NULL,
  `html` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `products_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_number` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `products_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_viewed` int(5) DEFAULT 0,
  `section_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_description`
--

INSERT INTO `products_description` (`products_id`, `html`, `language_id`, `products_name`, `products_number`, `products_description`, `products_url`, `products_viewed`, `section_id`) VALUES
(1, '', 1, 'Left Side Table Seat 1', '1', 'Left Side Table Seat 1', '', 0, 1),
(2, '', 1, 'Left Side Table Seat 2', '2', 'Left Side Table Seat 2', '', 0, 1),
(3, '', 1, 'Left Side Table Seat 3', '3', 'Left Side Table Seat 3', '', 0, 1),
(4, '', 1, 'Left Side Table Seat 4', '4', 'Left Side Table Seat 4', '', 0, 1),
(5, '', 1, 'Left Side Table Seat 5', '5', 'Left Side Table Seat 5', '', 0, 1),
(6, '', 1, 'Left Side Table Seat 6', '6', 'Left Side Table Seat 6', '', 0, 1),
(7, '', 1, 'Left Side Table Seat 7', '7', 'Left Side Table Seat 7', '', 0, 1),
(8, '', 1, 'Left Side Table Seat 8', '8', 'Left Side Table Seat 8', '', 0, 1),
(9, '', 1, 'Left Side Table Seat 9', '9', 'Left Side Table Seat 9', NULL, 0, 1),
(10, '', 1, 'Left Side Table Seat 10', '10', 'Left Side Table Seat 10', NULL, 0, 1),
(11, '', 1, 'Right Side Table Seat 1', '1', 'Right Side Table Seat 1', '', 0, 1),
(12, '', 1, 'Right Side Table Seat 2', '2', 'Right Side Table Seat 2', '', 0, 1),
(13, '', 1, 'Right Side Table Seat 3', '3', 'Right Side Table Seat 3', '', 0, 1),
(14, '', 1, 'Right Side Table Seat 4', '4', 'Right Side Table Seat 4', '', 0, 1),
(15, '', 1, 'Right Side Table Seat 5', '5', 'Right Side Table Seat 5', '', 0, 1),
(16, '', 1, 'Right Side Table Seat 6', '6', 'Right Side Table Seat 6', '', 0, 1),
(17, '', 1, 'Right Side Table Seat 7', '7', 'Right Side Table Seat 7', '', 0, 1),
(18, '', 1, 'Right Side Table Seat 8', '8', 'Right Side Table Seat 8', NULL, 0, 1),
(19, '', 1, 'Right Side Table Seat 9', '9', 'Right Side Table Seat 9', NULL, 0, 1),
(20, '', 1, 'Right Side Table Seat 10', '10', 'Right Side Table Seat 10', NULL, 0, 1),
(21, '', 1, 'Right Side Table Seat 11', '11', 'Right Side Table Seat 11', NULL, 0, 1),
(22, '', 1, 'Right Side Table Seat 12', '12', 'Right Side Table Seat 12', '', 0, 1),
(23, '', 1, 'Executive Table 1 Seat 1', '1', 'Table E1 Seat 1', '', 0, 1),
(24, '', 1, 'Executive Table 1 Seat 2', '2', 'Table E1 Seat 2', '', 0, 1),
(25, '', 1, 'Executive Table 1 Seat 3', '3', 'Table E1 Seat 3', '', 0, 1),
(26, '', 1, 'Executive Table 1 Seat 4', '4', 'Table E1 Seat 4', '', 0, 1),
(27, '', 1, 'Executive Table 1 Seat 5', '5', 'Table E1 Seat 5', '', 0, 1),
(28, '', 1, 'Executive Table 1 Seat 6', '6', 'Table E1 Seat 6', '', 0, 1),
(29, '', 1, 'Executive Table 1 Seat 7', '7', 'Table E1 Seat 7', '', 0, 1),
(30, '', 1, 'Executive Table 1 Seat 8', '8', 'Table E1 Seat 8', '', 0, 1),
(31, '', 1, 'Executive Table 1 Seat 9', '9', 'Table E1 Seat 9', '', 0, 1),
(32, '', 1, 'Executive Table 1 Seat 10', '10', 'Table E1 Seat 10', '', 0, 1),
(33, '', 1, 'Executive Table 2 Seat 1', '1', '<p>Executive Table</p>', '', 0, 1),
(34, '', 1, 'Executive Table 2 Seat 2', '2', '<p>Table E2 Seat 2</p>', '', 0, 1),
(35, '', 1, 'Executive Table 2 Seat 3', '3', 'Table E2 Seat 3', '', 0, 1),
(36, '', 1, 'Executive Table 2 Seat 4', '4', '<p>Table E2 Seat 4</p>', '', 0, 1),
(37, '', 1, 'Executive Table 2 Seat 5', '5', 'Table E2 Seat 5', '', 0, 1),
(38, '', 1, 'Executive Table 2 Seat 6', '6', 'Table E2 Seat 6', '', 0, 1),
(39, '', 1, 'Executive Table 2 Seat 7', '7', 'Table E2 Seat 7', '', 0, 1),
(40, '', 1, 'Executive Table 2 Seat 8', '8', 'Table E2 Seat 8', '', 0, 1),
(41, '', 1, 'Executive Table 2 Seat 9', '9', 'Table E2 Seat 9', '', 0, 1),
(42, '', 1, 'Executive Table 2 Seat 10', '10', 'Table E2 Seat 10', '', 0, 1),
(43, '', 1, 'Executive Table 3 Seat 1', '1', 'Table E3 Seat 1', '', 0, 1),
(44, '', 1, 'Executive Table 3 Seat 2', '2', 'Table E3 Seat 2', '', 0, 1),
(45, '', 1, 'Executive Table 3 Seat 3', '3', 'Table E3 Seat 3', '', 0, 1),
(46, '', 1, 'Executive Table 3 Seat 4', '4', 'Table E3 Seat 4', '', 0, 1),
(47, '', 1, 'Executive Table 3 Seat 5', '5', 'Table E3 Seat 5', '', 0, 1),
(48, '', 1, 'Executive Table 3 Seat 6', '6', 'Table E3 Seat 6', '', 0, 1),
(49, '', 1, 'Executive Table 3 Seat 7', '7', 'Table E3 Seat 7', '', 0, 1),
(50, '', 1, 'Executive Table 3 Seat 8', '8', 'Table E3 Seat 8', '', 0, 1),
(51, '', 1, 'Executive Table 3 Seat 9', '9', 'Table E3 Seat 9', '', 0, 1),
(52, '', 1, 'Executive Table 3 Seat 10', '10', 'Table E3 Seat 10', '', 0, 1),
(53, '', 1, 'Executive Table 4 Seat 1', '1', 'Table E4 Seat 1', '', 0, 1),
(54, '', 1, 'Executive Table 4 Seat 2', '2', 'Table E4 Seat 2', '', 0, 1),
(55, '', 1, 'Executive Table 4 Seat 3', '3', 'Table E4 Seat 3', '', 0, 1),
(56, '', 1, 'Executive Table 4 Seat 4', '4', 'Table E4 Seat 4', '', 0, 1),
(57, '', 1, 'Executive Table 4 Seat 5', '5', 'Table E4 Seat 5', '', 0, 1),
(58, '', 1, 'Executive Table 4 Seat 6', '6', 'Table E4 Seat 6', '', 0, 1),
(59, '', 1, 'Executive Table 4 Seat 7', '7', 'Table E4 Seat 7', '', 0, 1),
(60, '', 1, 'Executive Table 4 Seat 8', '8', 'Table E4 Seat 8', '', 0, 1),
(61, '', 1, 'Executive Table 4 Seat 9', '9', 'Table E4 Seat 9', '', 0, 1),
(62, '', 1, 'Executive Table 4 Seat 10', '10', 'Table E4 Seat 10', '', 0, 1),
(63, '', 1, 'Premium Table 1 Seat 1', '1', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(64, '', 1, 'Premium Table 1 Seat 2', '2', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(65, '', 1, 'Premium Table 1 Seat 3', '3', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(66, '', 1, 'Premium Table 1 Seat 4', '4', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(67, '', 1, 'Premium Table 1 Seat 5', '5', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(68, '', 1, 'Premium Table 1 Seat 6', '6', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(69, '', 1, 'Premium Table 1 Seat 7', '7', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(70, '', 1, 'Premium Table 1 Seat 8', '8', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(71, '', 1, 'Premium Table 1 Seat 9', '9', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(72, '', 1, 'Premium Table 1 Seat 10', '10', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(73, '', 1, 'Premium Table 2 Seat 1', '1', 'Table P2 Seat 1', '', 0, 1),
(74, '', 1, 'Premium Table 2 Seat 2', '2', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(75, '', 1, 'Premium Table 2 Seat 3', '3', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(76, '', 1, 'Premium Table 2 Seat 4', '4', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(77, '', 1, 'Premium Table 2 Seat 5', '5', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(78, '', 1, 'Premium Table 2 Seat 6', '6', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(79, '', 1, 'Premium Table 2 Seat 7', '7', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(80, '', 1, 'Premium Table 2 Seat 8', '8', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(81, '', 1, 'Premium Table 2 Seat 9', '9', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(82, '', 1, 'Premium Table 2 Seat 10', '10', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(83, '', 1, 'Premium Table 3 Seat 1', '1', 'Table P3 Seat 1', '', 0, 1),
(84, '', 1, 'Premium Table 3 Seat 2', '2', 'Table P3 Seat 2', '', 0, 1),
(85, '', 1, 'Premium Table 3 Seat 3', '3', 'Table P3 Seat 3', '', 0, 1),
(86, '', 1, 'Premium Table 3 Seat 4', '4', 'Table P3 Seat 4', '', 0, 1),
(87, '', 1, 'Premium Table 3 Seat 5', '5', 'Table P3 Seat 5', '', 0, 1),
(88, '', 1, 'Premium Table 3 Seat 6', '6', 'Table P3 Seat 6', '', 0, 1),
(89, '', 1, 'Premium Table 3 Seat 7', '7', 'Table P3 Seat 7', '', 0, 1),
(90, '', 1, 'Premium Table 3 Seat 8', '8', 'Table P3 Seat 8', '', 0, 1),
(91, '', 1, 'Premium Table 3 Seat 9', '9', 'Table P3 Seat 9', '', 0, 1),
(92, '', 1, 'Premium Table 3 Seat 10', '10', '<p>Table P3 Seat 10</p>', '', 0, 1),
(93, '', 1, 'Premium Table 4 Seat 1', '1', 'Table P4 Seat 1', '', 0, 1),
(94, '', 1, 'Premium Table 4 Seat 2', '2', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(95, '', 1, 'Premium Table 4 Seat 3', '3', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(96, '', 1, 'Premium Table 4 Seat 4', '4', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(97, '', 1, 'Premium Table 4 Seat 5', '5', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(98, '', 1, 'Premium Table 4 Seat 6', '6', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(99, '', 1, 'Premium Table 4 Seat 7', '7', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Best View! Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(100, '', 1, 'Premium Table 4 Seat 8', '8', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(101, '', 1, 'Premium Table 4 Seat 9', '9', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(102, '', 1, 'Premium Table 4 Seat 10', '10', '<p><span style=\"color: #939393; font-family: Obviously, \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 16px; background-color: #f5f5f5;\">Ticket Only: One Seat Per Ticket - Best Of Our Premium Tables. Enjoy The Show &amp; Indulge in our lovely Sierra Leonean &amp; Congolese cuisine. African Snacks &amp; African refreshment provided complimentary</span></p>', '', 0, 1),
(103, '', 1, 'Table P5 Seat 1', '1', 'Table P5 Seat 1', NULL, 0, 1),
(104, '', 1, 'Table P5 Seat 2', '2', 'Table P5 Seat 2', NULL, 0, 1),
(105, '', 1, 'Table P5 Seat 3', '3', 'Table P5 Seat 3', NULL, 0, 1),
(106, '', 1, 'Table P5 Seat 4', '4', 'Table P5 Seat 4', NULL, 0, 1),
(107, '', 1, 'Table P5 Seat 5', '5', 'Table P5 Seat 5', NULL, 0, 1),
(108, '', 1, 'Table P5 Seat 6', '6', 'Table P5 Seat 6', NULL, 0, 1),
(109, '', 1, 'Table P5 Seat 7', '7', 'Table P5 Seat 7', NULL, 0, 1),
(110, '', 1, 'Table P5 Seat 8', '8', 'Table P5 Seat 8', NULL, 0, 1),
(111, '', 1, 'Table P5 Seat 9', '9', 'Table P5 Seat 9', NULL, 0, 1),
(112, '', 1, 'Table P5 Seat 10', '10', 'Table P5 Seat 10', NULL, 0, 1),
(113, '', 1, 'Table P6 Seat 1', '1', 'Table P6 Seat 1', NULL, 0, 1),
(114, '', 1, 'Table P6 Seat 2', '2', 'Table P6 Seat 2', NULL, 0, 1),
(115, '', 1, 'Table P6 Seat 3', '3', 'Table P6 Seat 3', NULL, 0, 1),
(116, '', 1, 'Table P6 Seat 4', '4', 'Table P6 Seat 4', NULL, 0, 1),
(117, '', 1, 'Table P6 Seat 5', '5', 'Table P6 Seat 5', NULL, 0, 1),
(118, '', 1, 'Table P6 Seat 6', '6', 'Table P6 Seat 6', NULL, 0, 1),
(119, '', 1, 'Table P6 Seat 7', '7', 'Table P6 Seat 7', NULL, 0, 1),
(120, '', 1, 'Table P6 Seat 8', '8', 'Table P6 Seat 8', NULL, 0, 1),
(121, '', 1, 'Table P6 Seat 9', '9', 'Table P6 Seat 9', NULL, 0, 1),
(122, '', 1, 'Table P6 Seat 10', '10', 'Table P6 Seat 10', '', 0, 1),
(123, '', 1, 'Stage', 'STAGE', '', '', 0, 0),
(124, '', 1, 'E1', 'E1', '', '', 0, 0),
(125, '', 1, 'E2', 'E2', '', '', 0, 1),
(126, '', 1, 'E3', 'E3', '', '', 0, 1),
(127, '', 1, 'E4', 'E4', '', '', 0, 1),
(128, '', 1, 'P1', 'P1', '', '', 0, 1),
(129, '', 1, 'P2', 'P2', '', '', 0, 1),
(130, '', 1, 'P3', 'P3', '', '', 0, 1),
(131, '', 1, 'P4', 'P4', '', '', 0, 1),
(135, '', 1, 'GA', 'STANDING - GENERAL ADMISSION TICKETS', '', '141', 0, 1),
(136, '', 1, 'Left Tables', 'Left Side Tables', '', '', 0, 1),
(137, '', 1, 'Right Tables', 'Right Side Tables', '', '', 0, 1),
(138, '', 1, 'VIP - LOUNGE & VIEWING AREA (FIRST RELEASE)', '', '<p>Buy VIP TICKET. This entitles you to access to the VIP Lounge where you can meet &amp; greet our performers, private bar area. You will also have a VIP viewing area in the main venue to view all performances</p>', '', 0, 0),
(141, '', 1, 'Standing - Early Bird General Admission', '', '<p>Buy General Admission Tickets for STANDING AREA. Tickets are first come first serve. Early Release</p>', '', 0, 1),
(142, '', 1, 'VIP', 'VIP VIEWING AREA - GENERAL ADMISSION TICKETS', '', '138', 0, 1),
(143, '', 1, 'Standing - First Release General Admission', '', '<p>Buy General Admission Tickets for STANDING AREA. Tickets are first come first serve.</p>', '', 0, 1),
(144, '', 1, 'Standing - Second Release General Admission', '', '<p>Buy General Admission Tickets for STANDING AREA. Tickets are first come first serve.</p>', '', 0, 1),
(145, '', 1, 'Standing - Final Release General Admission', '', '<p>Buy General Admission Tickets for STANDING AREA. Tickets are first come first serve.</p>', '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products_price_break`
--

CREATE TABLE `products_price_break` (
  `products_id` int(11) NOT NULL DEFAULT 0,
  `quantity` int(6) NOT NULL DEFAULT 0,
  `discount_per_item` decimal(15,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products_to_categories`
--

CREATE TABLE `products_to_categories` (
  `products_id` int(11) NOT NULL DEFAULT 0,
  `categories_id` int(11) NOT NULL DEFAULT 0,
  `section_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_to_categories`
--

INSERT INTO `products_to_categories` (`products_id`, `categories_id`, `section_id`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 1),
(9, 1, 1),
(10, 1, 1),
(11, 1, 1),
(12, 1, 1),
(13, 1, 1),
(14, 1, 1),
(15, 1, 1),
(16, 1, 1),
(17, 1, 1),
(18, 1, 1),
(19, 1, 1),
(20, 1, 1),
(21, 1, 1),
(22, 1, 1),
(23, 1, 1),
(24, 1, 1),
(25, 1, 1),
(26, 1, 1),
(27, 1, 1),
(28, 1, 1),
(29, 1, 1),
(30, 1, 1),
(31, 1, 1),
(32, 1, 1),
(33, 1, 1),
(34, 1, 1),
(35, 1, 1),
(36, 1, 1),
(37, 1, 1),
(38, 1, 1),
(39, 1, 1),
(40, 1, 1),
(41, 1, 1),
(42, 1, 1),
(43, 1, 1),
(44, 1, 1),
(45, 1, 1),
(46, 1, 1),
(47, 1, 1),
(48, 1, 1),
(49, 1, 1),
(50, 1, 1),
(51, 1, 1),
(52, 1, 1),
(53, 1, 1),
(54, 1, 1),
(55, 1, 1),
(56, 1, 1),
(57, 1, 1),
(58, 1, 1),
(59, 1, 1),
(60, 1, 1),
(61, 1, 1),
(62, 1, 1),
(63, 1, 1),
(64, 1, 1),
(65, 1, 1),
(66, 1, 1),
(67, 1, 1),
(68, 1, 1),
(69, 1, 1),
(70, 1, 1),
(71, 1, 1),
(72, 1, 1),
(73, 1, 1),
(74, 1, 1),
(75, 1, 1),
(76, 1, 1),
(77, 1, 1),
(78, 1, 1),
(79, 1, 1),
(80, 1, 1),
(81, 1, 1),
(82, 1, 1),
(83, 1, 1),
(84, 1, 1),
(85, 1, 1),
(86, 1, 1),
(87, 1, 1),
(88, 1, 1),
(89, 1, 1),
(90, 1, 1),
(91, 1, 1),
(92, 1, 1),
(93, 1, 1),
(94, 1, 1),
(95, 1, 1),
(96, 1, 1),
(97, 1, 1),
(98, 1, 1),
(99, 1, 1),
(100, 1, 1),
(101, 1, 1),
(102, 1, 1),
(103, 1, 1),
(104, 1, 1),
(105, 1, 1),
(106, 1, 1),
(107, 1, 1),
(108, 1, 1),
(109, 1, 1),
(110, 1, 1),
(111, 1, 1),
(112, 1, 1),
(113, 1, 1),
(114, 1, 1),
(115, 1, 1),
(116, 1, 1),
(117, 1, 1),
(118, 1, 1),
(119, 1, 1),
(120, 1, 1),
(121, 1, 1),
(122, 1, 1),
(123, 1, 1),
(124, 1, 1),
(125, 1, 1),
(126, 1, 1),
(127, 1, 1),
(128, 1, 1),
(129, 1, 1),
(130, 1, 1),
(131, 1, 1),
(135, 1, 1),
(136, 1, 1),
(137, 1, 1),
(138, 1, 1),
(141, 1, 1),
(142, 1, 1),
(143, 1, 1),
(144, 1, 1),
(145, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

CREATE TABLE `quick_links` (
  `links_id` int(11) NOT NULL,
  `login_group_id` int(11) DEFAULT 0,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quick_links`
--

INSERT INTO `quick_links` (`links_id`, `login_group_id`, `filename`, `params`, `count`, `title`, `sort_order`) VALUES
(1, 1, 'sales_discount_coupons.php', 'mPath=8_87_308', 0, 'COUPONS', 1),
(2, 1, 'payment_general_templates.php', 'type=TIC&mPath=9_95', 0, 'ETICKET', 2),
(3, 1, 'sales_maker.php', 'mPath=8_85_89', 0, 'DISCOUNTS', 3),
(4, 1, 'configuration.php', 'mPath=4_46_416', 0, 'MORE...', 4),
(5, 1, 'configuration.php', 'mPath=400_419', 0, 'Box Office', 0),
(6, 1, 'configuration.php', 'mPath=400_417', 0, 'SP Settings', 0);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `refund_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `refund_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `amount_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `refund_amount` decimal(8,2) NOT NULL,
  `comments` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `row_status`
--

CREATE TABLE `row_status` (
  `row_status_id` int(11) NOT NULL,
  `row_status_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `products_status` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `row_status`
--

INSERT INTO `row_status` (`row_status_id`, `row_status_name`, `products_status`) VALUES
(1, 'Reserved Status', '0'),
(2, 'Hidden Status', '8'),
(3, 'Blocked Status', '3');

-- --------------------------------------------------------

--
-- Table structure for table `salemaker_sales`
--

CREATE TABLE `salemaker_sales` (
  `sale_id` int(11) NOT NULL,
  `sale_status` tinyint(4) NOT NULL DEFAULT 0,
  `sale_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sale_deduction_value` decimal(15,4) DEFAULT NULL,
  `sale_deduction_type` tinyint(4) NOT NULL DEFAULT 0,
  `sale_pricerange_from` decimal(15,4) DEFAULT NULL,
  `sale_pricerange_to` decimal(15,4) DEFAULT NULL,
  `sale_specials_condition` tinyint(4) NOT NULL DEFAULT 0,
  `sale_categories_selected` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sale_categories_all` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sale_date_start` date DEFAULT NULL,
  `sale_date_end` date DEFAULT NULL,
  `sale_date_added` date DEFAULT NULL,
  `sale_date_last_modified` date DEFAULT NULL,
  `sale_date_status_change` date DEFAULT NULL,
  `sale_discount_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `choice_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_warning` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sale_products_selected` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `apply_to_cross_sale` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seatplan_events`
--

CREATE TABLE `seatplan_events` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `log_level` tinyint(4) DEFAULT NULL,
  `cPath` bigint(20) DEFAULT NULL,
  `products_id` bigint(20) DEFAULT NULL,
  `products_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customers_id` bigint(20) DEFAULT NULL,
  `sesskey` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `event` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sesskey` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expiry` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sesskey`, `expiry`, `value`) VALUES
('94bce4ab497964e90709a3cf46a5e784', 1636131437, 'cart|O:12:\"shoppingCart\":6:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:6:\"cartID\";N;s:12:\"content_type\";b:0;s:4:\"mode\";N;}language|s:7:\"english\";languages_id|s:1:\"1\";languages_name|s:7:\"English\";languages_code|s:2:\"en\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:1:{i:0;a:4:{s:4:\"page\";s:17:\"seatplan_ajax.php\";s:4:\"mode\";s:3:\"SSL\";s:3:\"get\";s:5:\"$FGET\";s:4:\"post\";s:6:\"$FPOST\";}}s:8:\"snapshot\";a:0:{}}prev_category_viewed|s:7:\"cPath=1\";'),
('baf971a8e8b7a95e607d790d4c90eac0', 1636131436, 'cart|O:12:\"shoppingCart\":6:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:6:\"cartID\";N;s:12:\"content_type\";b:0;s:4:\"mode\";N;}language|s:7:\"english\";languages_id|s:1:\"1\";languages_name|s:7:\"English\";languages_code|s:2:\"en\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:1:{i:0;a:4:{s:4:\"page\";s:17:\"seatplan_ajax.php\";s:4:\"mode\";s:3:\"SSL\";s:3:\"get\";s:5:\"$FGET\";s:4:\"post\";s:6:\"$FPOST\";}}s:8:\"snapshot\";a:0:{}}prev_category_viewed|s:7:\"cPath=1\";'),
('922fca29bff5f5bb96e47b4dd3d64f60', 1636135248, 'language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";login_id|s:1:\"1\";login_groups_id|s:1:\"1\";login_first_name|s:5:\"admin\";login_last_name|s:5:\"admin\";login_email|s:23:\"webmaster@osconcert.com\";error_count|i:0;login_groups_type|s:17:\"Top Administrator\";top_admin_email|s:23:\"webmaster@osconcert.com\";mPath|s:1:\"4\";actions_value|s:6:\"insert\";delete_value|s:6:\"delete\";AJX_ENCRYPT_KEY|i:5;displayRowsCnt|i:-1;filename|s:21:\"products_mainpage.php\";params|s:10:\"mPath=4_37\";'),
('af4a730d29b641bc050b147a9f5e867f', 1636132598, 'language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";'),
('1af8462ee31fd4ee3fd1f9ca782694b9', 1636165575, 'language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";'),
('4a536a6f6e47e2c7eb733e38014e32a2', 1636149902, 'language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";login_id|s:1:\"1\";login_groups_id|s:1:\"1\";login_first_name|s:5:\"admin\";login_last_name|s:5:\"admin\";login_email|s:23:\"webmaster@osconcert.com\";error_count|i:0;login_groups_type|s:17:\"Top Administrator\";top_admin_email|s:23:\"webmaster@osconcert.com\";mPath|s:1:\"4\";actions_value|s:6:\"insert\";delete_value|s:6:\"delete\";filename|s:15:\"shop_backup.php\";params|s:16:\"mPath=11_160_171\";AJX_ENCRYPT_KEY|i:3;displayRowsCnt|i:-1;'),
('83780503f3a51a5a6dd1d4a2daf14061', 1636131412, 'cart|O:12:\"shoppingCart\":6:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:6:\"cartID\";N;s:12:\"content_type\";b:0;s:4:\"mode\";N;}language|s:7:\"english\";languages_id|s:1:\"1\";languages_name|s:7:\"English\";languages_code|s:2:\"en\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:1:{i:0;a:4:{s:4:\"page\";s:17:\"seatplan_ajax.php\";s:4:\"mode\";s:3:\"SSL\";s:3:\"get\";s:5:\"$FGET\";s:4:\"post\";s:6:\"$FPOST\";}}s:8:\"snapshot\";a:0:{}}prev_category_viewed|s:7:\"cPath=1\";');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `title`, `type`, `image`) VALUES
(26, 'WHITE YARDIE', 'Home', 'whiteyardie.jpg'),
(27, 'EAT MALANGWA', 'Home', 'MalangwaFishand_Kwanga.jpg'),
(25, 'EAT JOLLOF', 'Home', 'JollofRiceandJollofStew.jpg'),
(23, 'THE JOLLOF N LAUGH SHOW', 'Home', 'TheJollofNLaughShow_Flyer.jpg'),
(24, 'A DOT COMEDIAN', 'Home', 'AdotComedianPhoto1.jpg'),
(28, 'CHRISTOPHER SAVAGE', 'Home', 'ChristopherSavage2.jpg'),
(29, 'FRIED WINGS', 'Home', 'FriedChickenWings.jpg'),
(30, 'KEVIN J', 'Home', 'JollofNLaugh_KevinJ_ARTIST.jpg'),
(31, 'THE JOLLOF N LAUGH SHOW', 'Home', 'JollofnLaughBackground-min.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `sources_id` int(11) NOT NULL,
  `sources_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`sources_id`, `sources_name`) VALUES
(5, 'Advertisement'),
(1, 'Friend'),
(6, 'Magazine'),
(2, 'Promotion'),
(8, 'Radio'),
(4, 'Search Engine'),
(7, 'Television'),
(3, 'Website');

-- --------------------------------------------------------

--
-- Table structure for table `sources_other`
--

CREATE TABLE `sources_other` (
  `customers_id` int(11) NOT NULL DEFAULT 0,
  `sources_other_customers_id` int(11) NOT NULL,
  `sources_other_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `specials`
--

CREATE TABLE `specials` (
  `specials_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT 0,
  `specials_new_products_price` decimal(15,4) DEFAULT NULL,
  `specials_date_added` datetime DEFAULT NULL,
  `specials_last_modified` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `customers_groups_id` int(11) NOT NULL DEFAULT 0,
  `customers_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sql_queries`
--

CREATE TABLE `sql_queries` (
  `file_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date_installed` datetime NOT NULL DEFAULT '2020-04-11 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sql_queries`
--

INSERT INTO `sql_queries` (`file_name`, `date_installed`) VALUES
('osconcert_v9_0_0.sql', '2021-10-16 11:15:26'),
('osconcert_v9_0_1.sql', '2021-10-16 11:15:26'),
('osconcert_v9_0_2.sql', '2021-10-16 11:15:26'),
('osconcert_v9_0_3.sql', '2021-10-16 11:15:26'),
('osconcert_v9_0_4.sql', '2021-10-16 11:15:27'),
('osconcert_v9_0_5.sql', '2021-10-16 11:15:27');

-- --------------------------------------------------------

--
-- Table structure for table `tax_class`
--

CREATE TABLE `tax_class` (
  `tax_class_id` int(11) NOT NULL,
  `tax_class_title` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tax_class_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `tax_rates_id` int(11) NOT NULL,
  `tax_zone_id` int(11) NOT NULL DEFAULT 0,
  `tax_class_id` int(11) NOT NULL DEFAULT 0,
  `tax_priority` int(5) DEFAULT 1,
  `tax_rate` decimal(7,4) NOT NULL DEFAULT 0.0000,
  `tax_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `template_id` int(11) NOT NULL,
  `template_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `template_image` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_width` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '100%',
  `side_box_left_width` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `side_box_right_width` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `include_column_left` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `include_column_right` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `box_width_left` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '260',
  `header_banner` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_breadcrumb` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'yes',
  `active` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `show_header_pane` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'no',
  `languages_in_header` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'no',
  `container_border` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `cart_in_header` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'no',
  `show_page_descriptions` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `module_one` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_two` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_three` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_four` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_five` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_six` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customer_greeting` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `edit_customer_greeting_personal` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `edit_customer_greeting_personal_relogon` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `edit_greeting_guest` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `show_topbar` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'no',
  `template_color` varchar(28) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `header_height` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`template_id`, `template_name`, `date_added`, `last_modified`, `template_image`, `site_width`, `side_box_left_width`, `side_box_right_width`, `include_column_left`, `include_column_right`, `box_width_left`, `header_banner`, `show_breadcrumb`, `active`, `show_header_pane`, `languages_in_header`, `container_border`, `cart_in_header`, `show_page_descriptions`, `module_one`, `module_two`, `module_three`, `module_four`, `module_five`, `module_six`, `customer_greeting`, `edit_customer_greeting_personal`, `edit_customer_greeting_personal_relogon`, `edit_greeting_guest`, `show_topbar`, `template_color`, `header_height`) VALUES
(2, 'blackzone', '2020-04-21 00:00:00', '2020-04-21 00:00:00', '', '', '0', '0', 'no', 'no', '260', '', 'yes', '1', 'no', 'no', '', 'yes', 'no', '', '', '', '', '', '', 'yes', '', '', '', 'no', 'blue', 84),
(3, 'newzone', '2020-04-16 00:00:00', '2021-10-16 00:00:00', '', '', '0', '0', 'no', 'no', '', '', 'yes', '1', 'no', 'no', '', 'yes', 'no', '', '', '', '', '', '', 'yes', '', '', '', 'yes', 'valencia', 84),
(4, 'theevent', '2020-04-21 00:00:00', '2020-04-21 00:00:00', '', '', '0', '0', 'no', 'no', '260', '', 'yes', '1', 'no', 'no', '', 'yes', 'no', '', '', '', '', '', '', 'yes', '', '', '', 'no', 'red', 84);

-- --------------------------------------------------------

--
-- Table structure for table `timezone_master`
--

CREATE TABLE `timezone_master` (
  `timezone_id` int(10) NOT NULL,
  `timezone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone_value` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timezone_master`
--

INSERT INTO `timezone_master` (`timezone_id`, `timezone`, `timezone_value`) VALUES
(1, 'Pacific/Midway', '(GMT-11:00) Midway Island '),
(2, 'Pacific/Samoa', '(GMT-11:00) Samoa '),
(3, 'Pacific/Honolulu', '(GMT-10:00) Hawaii '),
(4, 'America/Anchorage', '(GMT-09:00) Alaska '),
(5, 'America/Los_Angeles', '(GMT-08:00) Pacific Time (US &amp; Canada) '),
(6, 'America/Tijuana', '(GMT-08:00) Tijuana '),
(7, 'America/Chihuahua', '(GMT-07:00) Chihuahua '),
(8, 'America/Mazatlan', '(GMT-07:00) Mazatlan '),
(9, 'America/Denver', '(GMT-07:00) Mountain Time (US &amp; Canada) '),
(10, 'America/Managua', '(GMT-06:00) Central America '),
(11, 'America/Chicago', '(GMT-06:00) Central Time (US &amp; Canada) '),
(12, 'America/Mexico_City', '(GMT-06:00) Mexico City '),
(13, 'America/Monterrey', '(GMT-06:00) Monterrey '),
(14, 'America/Bogota', '(GMT-05:00) Bogota '),
(15, 'America/New_York', '(GMT-05:00) Eastern Time (US &amp; Canada) '),
(16, 'America/Lima', '(GMT-05:00) Lima '),
(17, 'Canada/Atlantic', '(GMT-04:00) Atlantic Time (Canada) '),
(18, 'America/Caracas', '(GMT-04:30) Caracas '),
(19, 'America/La_Paz', '(GMT-04:00) La Paz '),
(20, 'America/Santiago', '(GMT-04:00) Santiago '),
(21, 'America/St_Johns', '(GMT-03:30) Newfoundland '),
(22, 'America/Sao_Paulo', '(GMT-03:00) Brasilia '),
(23, 'America/Argentina/Buenos_Aires', '(GMT-03:00) Buenos Aires '),
(24, 'America/Godthab', '(GMT-03:00) Greenland '),
(25, 'America/Noronha', '(GMT-02:00) Mid-Atlantic '),
(26, 'Atlantic/Azores', '(GMT-01:00) Azores '),
(27, 'Atlantic/Cape_Verde', '(GMT-01:00) Cape Verde Is. '),
(28, 'Africa/Casablanca', '(GMT+00:00) Casablanca '),
(29, 'Europe/Dublin', '(GMT+00:00) Dublin '),
(30, 'Europe/Lisbon', '(GMT+00:00) Lisbon '),
(31, 'Europe/London', '(GMT+00:00) London '),
(32, 'Africa/Monrovia', '(GMT+00:00) Monrovia '),
(33, 'UTC', '(GMT+00:00) UTC '),
(34, 'Europe/Amsterdam', '(GMT+01:00) Amsterdam '),
(35, 'Europe/Belgrade', '(GMT+01:00) Belgrade '),
(36, 'Europe/Berlin', '(GMT+01:00) Berlin '),
(37, 'Europe/Bratislava', '(GMT+01:00) Bratislava '),
(38, 'Europe/Brussels', '(GMT+01:00) Brussels '),
(39, 'Europe/Budapest', '(GMT+01:00) Budapest '),
(40, 'Europe/Copenhagen', '(GMT+01:00) Copenhagen '),
(41, 'Europe/Ljubljana', '(GMT+01:00) Ljubljana '),
(42, 'Europe/Madrid', '(GMT+01:00) Madrid '),
(43, 'Europe/Paris', '(GMT+01:00) Paris '),
(44, 'Europe/Prague', '(GMT+01:00) Prague '),
(45, 'Europe/Rome', '(GMT+01:00) Rome '),
(46, 'Europe/Sarajevo', '(GMT+01:00) Sarajevo '),
(47, 'Europe/Skopje', '(GMT+01:00) Skopje '),
(48, 'Europe/Stockholm', '(GMT+01:00) Stockholm '),
(49, 'Europe/Vienna', '(GMT+01:00) Vienna '),
(50, 'Europe/Warsaw', '(GMT+01:00) Warsaw '),
(51, 'Africa/Lagos', '(GMT+01:00) West Central Africa '),
(52, 'Europe/Zagreb', '(GMT+01:00) Zagreb '),
(53, 'Europe/Athens', '(GMT+02:00) Athens '),
(54, 'Europe/Bucharest', '(GMT+02:00) Bucharest '),
(55, 'Africa/Cairo', '(GMT+02:00) Cairo '),
(56, 'Africa/Harare', '(GMT+02:00) Harare '),
(57, 'Europe/Helsinki', '(GMT+02:00) Helsinki '),
(58, 'Europe/Istanbul', '(GMT+02:00) Istanbul '),
(59, 'Asia/Jerusalem', '(GMT+02:00) Jerusalem '),
(60, 'Africa/Johannesburg', '(GMT+02:00) Pretoria '),
(61, 'Europe/Riga', '(GMT+02:00) Riga '),
(62, 'Europe/Sofia', '(GMT+02:00) Sofia '),
(63, 'Europe/Tallinn', '(GMT+02:00) Tallinn '),
(64, 'Europe/Vilnius', '(GMT+02:00) Vilnius '),
(65, 'Asia/Baghdad', '(GMT+03:00) Baghdad '),
(66, 'Asia/Kuwait', '(GMT+03:00) Kuwait '),
(67, 'Europe/Minsk', '(GMT+03:00) Minsk '),
(68, 'Africa/Nairobi', '(GMT+03:00) Nairobi '),
(69, 'Asia/Riyadh', '(GMT+03:00) Riyadh '),
(70, 'Europe/Volgograd', '(GMT+03:00) Volgograd '),
(71, 'Asia/Tehran', '(GMT+03:30) Tehran '),
(72, 'Asia/Baku', '(GMT+04:00) Baku '),
(73, 'Europe/Moscow', '(GMT+04:00) Moscow '),
(74, 'Asia/Muscat', '(GMT+04:00) Muscat '),
(75, 'Asia/Tbilisi', '(GMT+04:00) Tbilisi '),
(76, 'Asia/Yerevan', '(GMT+04:00) Yerevan '),
(77, 'Asia/Kabul', '(GMT+04:30) Kabul '),
(78, 'Asia/Karachi', '(GMT+05:00) Karachi '),
(79, 'Asia/Tashkent', '(GMT+05:00) Tashkent '),
(80, 'Asia/Kolkata', '(GMT+05:30) Kolkata '),
(81, 'Asia/Calcutta', '(GMT+05:30) Mumbai '),
(82, 'Asia/Katmandu', '(GMT+05:45) Kathmandu '),
(83, 'Asia/Almaty', '(GMT+06:00) Almaty '),
(84, 'Asia/Dhaka', '(GMT+06:00) Dhaka '),
(85, 'Asia/Yekaterinburg', '(GMT+06:00) Ekaterinburg '),
(86, 'Asia/Rangoon', '(GMT+06:30) Rangoon '),
(87, 'Asia/Bangkok', '(GMT+07:00) Bangkok '),
(88, 'Asia/Jakarta', '(GMT+07:00) Jakarta '),
(89, 'Asia/Novosibirsk', '(GMT+07:00) Novosibirsk '),
(90, 'Asia/Chongqing', '(GMT+08:00) Chongqing '),
(91, 'Asia/Hong_Kong', '(GMT+08:00) Hong Kong '),
(92, 'Asia/Krasnoyarsk', '(GMT+08:00) Krasnoyarsk '),
(93, 'Asia/Kuala_Lumpur', '(GMT+08:00) Kuala Lumpur '),
(94, 'Australia/Perth', '(GMT+08:00) Perth '),
(95, 'Asia/Singapore', '(GMT+08:00) Singapore '),
(96, 'Asia/Taipei', '(GMT+08:00) Taipei '),
(97, 'Asia/Ulan_Bator', '(GMT+08:00) Ulaan Bataar '),
(98, 'Asia/Urumqi', '(GMT+08:00) Urumqi '),
(99, 'Asia/Irkutsk', '(GMT+09:00) Irkutsk '),
(100, 'Asia/Seoul', '(GMT+09:00) Seoul '),
(101, 'Asia/Tokyo', '(GMT+09:00) Tokyo '),
(102, 'Australia/Adelaide', '(GMT+09:30) Adelaide '),
(103, 'Australia/Darwin', '(GMT+09:30) Darwin '),
(104, 'Australia/Brisbane', '(GMT+10:00) Brisbane '),
(105, 'Australia/Canberra', '(GMT+10:00) Canberra '),
(106, 'Pacific/Guam', '(GMT+10:00) Guam '),
(107, 'Australia/Hobart', '(GMT+10:00) Hobart '),
(108, 'Australia/Melbourne', '(GMT+10:00) Melbourne '),
(109, 'Pacific/Port_Moresby', '(GMT+10:00) Port Moresby '),
(110, 'Australia/Sydney', '(GMT+10:00) Sydney '),
(111, 'Asia/Yakutsk', '(GMT+10:00) Yakutsk '),
(112, 'Asia/Vladivostok', '(GMT+11:00) Vladivostok '),
(113, 'Pacific/Auckland', '(GMT+12:00) Auckland '),
(114, 'Pacific/Fiji', '(GMT+12:00) Fiji '),
(115, 'Pacific/Kwajalein', '(GMT+12:00) International Date Line West '),
(116, 'Asia/Kamchatka', '(GMT+12:00) Kamchatka '),
(117, 'Asia/Magadan', '(GMT+12:00) Magadan '),
(118, 'Pacific/Tongatapu', '(GMT+13:00) Nukualofa ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_time` int(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `created_time`, `created_date`) VALUES
(6, 'FirstName', 'LastName', 'scottphenix24@gmail.com', '$2a$08$nfSNn5ryhaeoWOz7ZFPQke/imczYh.L2d092rdp.tFuk6FnJ5TCaS', 'Admin', 1635167542, '2021-10-25 13:12:22'),
(9, 'FirstName', 'LastName', 'admin@email.com', '$2a$08$OcO128kO2j5.47oMTCP.KuQdCr4ywGr98E86BI0CqTgxNGTDNTHS6', 'Admin', 1635200837, '2021-10-25 22:27:17'),
(8, 'FirstName', 'LastName', 'admin@gmail.com', '$2a$08$OScG6TOgKyHFGxsZBxdi/uida8d.LI414lAqfHgAvetqdzLmvIhD2', 'Admin', 1635199948, '2021-10-25 22:12:28');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image1` varchar(100) NOT NULL,
  `image2` varchar(100) NOT NULL,
  `maps` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`id`, `title`, `body`, `image1`, `image2`, `maps`, `created_date`) VALUES
(1, 'TUDOR ROSE VENUE', '<div style=\"color: rgb(0, 0, 0); font-family: arimo, sans-serif; font-size: 14px;\"><p style=\"color: rgb(33, 37, 41); font-family: Lato, sans-serif;\">Tudor Rose is black owned venue and proud to be known as the home of black entertainment. Having been established for over 30 years, Tudor Rose has welcomed every community and hosted a range of events, and community projects.&nbsp; Based in Southall, West London, Tudor Rose has always been popular amongst the African, Caribbean, Asian and Irish communities who have&nbsp; hosted a range of events from weddings, birthday parties, exhibitions, classes in the venue.</p><p style=\"color: rgb(33, 37, 41); font-family: Lato, sans-serif;\">The venue boasts three floors, and has two separate event space located in the same building but with separate entrances. Tudor Rose has a full capacity of 1000. Rose Bud, which is located inside the Tudor Rose building but is a more luxurious private club space has a full capacity of 250 people.</p><p style=\"color: rgb(33, 37, 41); font-family: Lato, sans-serif;\">EventsthroneVIP are official partners of Tudor Rose Venue. The venue is very proud to be selected to host the very first live recorded entertainment night for The Jollof N Laugh Show</p><p style=\"color: rgb(33, 37, 41); font-family: Lato, sans-serif;\"></p></div>', 'TudorRoseVenue_Side-min.jpg', 'Wyardie2.jpeg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.5300811201846!2d-0.38283938424874586!3d51.50349037963448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48767327e832bd7d%3A0x956a54747b40c43a!2sTudor%20Rose%20Events!5e0!3m2!1sen!2suk!4v1635975973012!5m2!1sen!2suk', '2021-11-01 20:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `playlist` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `type`, `url`, `playlist`) VALUES
(1, 'SUNDAY 5TH DECEMBER TUDOR ROSE', 'Home', 'https://www.youtube.com/embed/WDGoy7hSDLM', 'PLNDXSdfdkz_uKwBFHSmn_rQ-UCDOOVTL0'),
(3, 'Get Ready For Jollof N Laugh', 'Home', 'https://www.youtube.com/embed/6uPv__ld5zo', 'PLNDXSdfdkz_vV-SIa6LzFZf4sfjioF98d');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_history`
--

CREATE TABLE `wallet_history` (
  `wallet_history_id` int(11) UNSIGNED NOT NULL,
  `customers_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `drawn_date` datetime DEFAULT NULL,
  `orders_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `amount` decimal(15,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_messages_history`
--

CREATE TABLE `wallet_messages_history` (
  `wallet_history_id` int(11) UNSIGNED NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `customers_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `message_mode` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'E',
  `message_type` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'WFU'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_uploads`
--

CREATE TABLE `wallet_uploads` (
  `wallet_id` int(11) UNSIGNED NOT NULL,
  `customers_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `payment_date` datetime DEFAULT NULL,
  `payment_method` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` mediumint(3) UNSIGNED NOT NULL DEFAULT 1,
  `comments` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(15,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `whos_online`
--

CREATE TABLE `whos_online` (
  `customer_id` int(11) DEFAULT 0,
  `full_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `session_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time_entry` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time_last_click` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_page_url` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `whos_online`
--

INSERT INTO `whos_online` (`customer_id`, `full_name`, `session_id`, `ip_address`, `time_entry`, `time_last_click`, `last_page_url`) VALUES
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '90.205.210.162', '1636112893', '1636130768', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '90.205.210.162', '1636112893', '1636130768', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '2a02:c7f:dc71:bd00:d1b6:742f:3133:7a14', '1636104156', '1636130768', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '90.208.57.193', '1636113792', '1636130768', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '90.208.57.193', '1636113792', '1636130768', 'JollofNLaugh'),
(0, 'Guest', '94bce4ab497964e90709a3cf46a5e784', '90.208.57.193', '1636128038', '1636130736', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '2a02:c7f:dc71:bd00:dc78:d942:f87:5a92', '1636113968', '1636130768', 'JollofNLaugh'),
(0, 'Guest', 'baf971a8e8b7a95e607d790d4c90eac0', '90.208.57.193', '1636128039', '1636130736', 'JollofNLaugh'),
(0, 'Guest', '83780503f3a51a5a6dd1d4a2daf14061', '2a02:c7f:dc71:bd00:45e6:585a:e30a:86ce', '1636126629', '1636130768', 'JollofNLaugh');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `zone_id` int(11) NOT NULL,
  `zone_country_id` int(11) NOT NULL,
  `zone_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zone_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `placement` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`, `placement`) VALUES
(1, 1, 'BDS', 'Badakhshan', 0),
(2, 1, 'BDG', 'Badghis', 0),
(3, 1, 'BGL', 'Baghlan', 0),
(4, 1, 'BAL', 'Balkh', 0),
(5, 1, 'BAM', 'Bamian', 0),
(6, 1, 'FRA', 'Farah', 0),
(7, 1, 'FYB', 'Faryab', 0),
(8, 1, 'GHA', 'Ghazni', 0),
(9, 1, 'GHO', 'Ghowr', 0),
(10, 1, 'HEL', 'Helmand', 0),
(11, 1, 'HER', 'Herat', 0),
(12, 1, 'JOW', 'Jowzjan', 0),
(13, 1, 'KAB', 'Kabul', 0),
(14, 1, 'KAN', 'Kandahar', 0),
(15, 1, 'KAP', 'Kapisa', 0),
(16, 1, 'KHO', 'Khost', 0),
(17, 1, 'KNR', 'Konar', 0),
(18, 1, 'KDZ', 'Kondoz', 0),
(19, 1, 'LAG', 'Laghman', 0),
(20, 1, 'LOW', 'Lowgar', 0),
(21, 1, 'NAN', 'Nangrahar', 0),
(22, 1, 'NIM', 'Nimruz', 0),
(23, 1, 'NUR', 'Nurestan', 0),
(24, 1, 'ORU', 'Oruzgan', 0),
(25, 1, 'PIA', 'Paktia', 0),
(26, 1, 'PKA', 'Paktika', 0),
(27, 1, 'PAR', 'Parwan', 0),
(28, 1, 'SAM', 'Samangan', 0),
(29, 1, 'SAR', 'Sar-e Pol', 0),
(30, 1, 'TAK', 'Takhar', 0),
(31, 1, 'WAR', 'Wardak', 0),
(32, 1, 'ZAB', 'Zabol', 0),
(33, 2, 'BR', 'Berat', 0),
(34, 2, 'BU', 'Bulqize', 0),
(35, 2, 'DL', 'Delvine', 0),
(36, 2, 'DV', 'Devoll', 0),
(37, 2, 'DI', 'Diber', 0),
(38, 2, 'DR', 'Durres', 0),
(39, 2, 'EL', 'Elbasan', 0),
(40, 2, 'ER', 'Kolonje', 0),
(41, 2, 'FR', 'Fier', 0),
(42, 2, 'GJ', 'Gjirokaster', 0),
(43, 2, 'GR', 'Gramsh', 0),
(44, 2, 'HA', 'Has', 0),
(45, 2, 'KA', 'Kavaje', 0),
(46, 2, 'KB', 'Kurbin', 0),
(47, 2, 'KC', 'Kucove', 0),
(48, 2, 'KO', 'Korce', 0),
(49, 2, 'KR', 'Kruje', 0),
(50, 2, 'KU', 'Kukes', 0),
(51, 2, 'LB', 'Librazhd', 0),
(52, 2, 'LE', 'Lezhe', 0),
(53, 2, 'LU', 'Lushnje', 0),
(54, 2, 'MM', 'Malesi e Madhe', 0),
(55, 2, 'MK', 'Mallakaster', 0),
(56, 2, 'MT', 'Mat', 0),
(57, 2, 'MR', 'Mirdite', 0),
(58, 2, 'PQ', 'Peqin', 0),
(59, 2, 'PR', 'Permet', 0),
(60, 2, 'PG', 'Pogradec', 0),
(61, 2, 'PU', 'Puke', 0),
(62, 2, 'SH', 'Shkoder', 0),
(63, 2, 'SK', 'Skrapar', 0),
(64, 2, 'SR', 'Sarande', 0),
(65, 2, 'TE', 'Tepelene', 0),
(66, 2, 'TP', 'Tropoje', 0),
(67, 2, 'TR', 'Tirane', 0),
(68, 2, 'VL', 'Vlore', 0),
(69, 3, 'ADR', 'Adrar', 0),
(70, 3, 'ADE', 'Ain Defla', 0),
(71, 3, 'ATE', 'Ain Temouchent', 0),
(72, 3, 'ALG', 'Alger', 0),
(73, 3, 'ANN', 'Annaba', 0),
(74, 3, 'BAT', 'Batna', 0),
(75, 3, 'BEC', 'Bechar', 0),
(76, 3, 'BEJ', 'Bejaia', 0),
(77, 3, 'BIS', 'Biskra', 0),
(78, 3, 'BLI', 'Blida', 0),
(79, 3, 'BBA', 'Bordj Bou Arreridj', 0),
(80, 3, 'BOA', 'Bouira', 0),
(81, 3, 'BMD', 'Boumerdes', 0),
(82, 3, 'CHL', 'Chlef', 0),
(83, 3, 'CON', 'Constantine', 0),
(84, 3, 'DJE', 'Djelfa', 0),
(85, 3, 'EBA', 'El Bayadh', 0),
(86, 3, 'EOU', 'El Oued', 0),
(87, 3, 'ETA', 'El Tarf', 0),
(88, 3, 'GHA', 'Ghardaia', 0),
(89, 3, 'GUE', 'Guelma', 0),
(90, 3, 'ILL', 'Illizi', 0),
(91, 3, 'JIJ', 'Jijel', 0),
(92, 3, 'KHE', 'Khenchela', 0),
(93, 3, 'LAG', 'Laghouat', 0),
(94, 3, 'MUA', 'Muaskar', 0),
(95, 3, 'MED', 'Medea', 0),
(96, 3, 'MIL', 'Mila', 0),
(97, 3, 'MOS', 'Mostaganem', 0),
(98, 3, 'MSI', 'M&apos; &apos;Sila', 0),
(99, 3, 'NAA', 'Naama', 0),
(100, 3, 'ORA', 'Oran', 0),
(101, 3, 'OUA', 'Ouargla', 0),
(102, 3, 'OEB', 'Oum el-Bouaghi', 0),
(103, 3, 'REL', 'Relizane', 0),
(104, 3, 'SAI', 'Saida', 0),
(105, 3, 'SET', 'Setif', 0),
(106, 3, 'SBA', 'Sidi Bel Abbes', 0),
(107, 3, 'SKI', 'Skikda', 0),
(108, 3, 'SAH', 'Souk Ahras', 0),
(109, 3, 'TAM', 'Tamanghasset', 0),
(110, 3, 'TEB', 'Tebessa', 0),
(111, 3, 'TIA', 'Tiaret', 0),
(112, 3, 'TIN', 'Tindouf', 0),
(113, 3, 'TIP', 'Tipaza', 0),
(114, 3, 'TIS', 'Tissemsilt', 0),
(115, 3, 'TOU', 'Tizi Ouzou', 0),
(116, 3, 'TLE', 'Tlemcen', 0),
(117, 4, 'E', 'Eastern', 0),
(118, 4, 'M', 'Manu&apos; &apos;a', 0),
(119, 4, 'R', 'Rose Island', 0),
(120, 4, 'S', 'Swains Island', 0),
(121, 4, 'W', 'Western', 0),
(122, 5, 'ALV', 'Andorra la Vella', 0),
(123, 5, 'CAN', 'Canillo', 0),
(124, 5, 'ENC', 'Encamp', 0),
(125, 5, 'ESE', 'Escaldes-Engordany', 0),
(126, 5, 'LMA', 'La Massana', 0),
(127, 5, 'ORD', 'Ordino', 0),
(128, 5, 'SJL', 'Sant Juli', 0),
(129, 6, 'BGO', 'Bengo', 0),
(130, 6, 'BGU', 'Benguela', 0),
(131, 6, 'BIE', 'Bie', 0),
(132, 6, 'CAB', 'Cabinda', 0),
(133, 6, 'CCU', 'Cuando-Cubango', 0),
(134, 6, 'CNO', 'Cuanza Norte', 0),
(135, 6, 'CUS', 'Cuanza Sul', 0),
(136, 6, 'CNN', 'Cunene', 0),
(137, 6, 'HUA', 'Huambo', 0),
(138, 6, 'HUI', 'Huila', 0),
(139, 6, 'LUA', 'Luanda', 0),
(140, 6, 'LNO', 'Lunda Norte', 0),
(141, 6, 'LSU', 'Lunda Sul', 0),
(142, 6, 'MAL', 'Malange', 0),
(143, 6, 'MOX', 'Moxico', 0),
(144, 6, 'NAM', 'Namibe', 0),
(145, 6, 'UIG', 'Uige', 0),
(146, 6, 'ZAI', 'Zaire', 0),
(147, 7, 'SCR', 'Scrub', 0),
(148, 7, 'SAN', 'Sandy', 0),
(149, 7, 'PRI', 'Prickly Pear', 0),
(150, 7, 'LIT', 'Little Scrub', 0),
(151, 7, 'DOG', 'Dog', 0),
(152, 7, 'ANG', 'Anguillita', 0),
(153, 7, 'ANG', 'Anguila', 0),
(154, 7, 'SEA', 'Seal', 0),
(155, 7, 'SOM', 'Sombrero', 0),
(156, 9, 'ASG', 'Saint George', 0),
(157, 9, 'ASJ', 'Saint John', 0),
(158, 9, 'ASM', 'Saint Mary', 0),
(159, 9, 'ASL', 'Saint Paul', 0),
(160, 9, 'ASR', 'Saint Peter', 0),
(161, 9, 'ASH', 'Saint Philip', 0),
(162, 9, 'BAR', 'Barbuda', 0),
(163, 9, 'RED', 'Redonda', 0),
(164, 10, 'AN', 'Antartida e Islas del Atlantico', 0),
(165, 10, 'BA', 'Buenos Aires', 0),
(166, 10, 'CA', 'Catamarca', 0),
(167, 10, 'CH', 'Chaco', 0),
(168, 10, 'CU', 'Chubut', 0),
(169, 10, 'CO', 'Cordoba', 0),
(170, 10, 'CR', 'Corrientes', 0),
(171, 10, 'CF', 'Capital Federal', 0),
(172, 10, 'ER', 'Entre Rios', 0),
(173, 10, 'FO', 'Formosa', 0),
(174, 10, 'JU', 'Jujuy', 0),
(175, 10, 'LP', 'La Pampa', 0),
(176, 10, 'LR', 'La Rioja', 0),
(177, 10, 'ME', 'Mendoza', 0),
(178, 10, 'MI', 'Misiones', 0),
(179, 10, 'NE', 'Neuquen', 0),
(180, 10, 'RN', 'Rio Negro', 0),
(181, 10, 'SA', 'Salta', 0),
(182, 10, 'SJ', 'San Juan', 0),
(183, 10, 'SL', 'San Luis', 0),
(184, 10, 'SC', 'Santa Cruz', 0),
(185, 10, 'SF', 'Santa Fe', 0),
(186, 10, 'SD', 'Santiago del Estero', 0),
(187, 10, 'TF', 'Tierra del Fuego', 0),
(188, 10, 'TU', 'Tucuman', 0),
(189, 11, 'AGT', 'Aragatsotn', 0),
(190, 11, 'ARR', 'Ararat', 0),
(191, 11, 'ARM', 'Armavir', 0),
(192, 11, 'GEG', 'Geghark&apos; &apos;unik', 0),
(193, 11, 'KOT', 'Kotayk', 0),
(194, 11, 'LOR', 'Lorri', 0),
(195, 11, 'SHI', 'Shirak', 0),
(196, 11, 'SYU', 'Syunik', 0),
(197, 11, 'TAV', 'Tavush', 0),
(198, 11, 'VAY', 'Vayots&apos; Dzor', 0),
(199, 11, 'YER', 'Yerevan', 0),
(200, 12, 'ARU', 'Aruba', 0),
(201, 12, 'ORA', 'Oranjestad', 0),
(202, 12, 'DRU', 'Druif Beach', 0),
(203, 12, 'MAN', 'Manchebo Beach', 0),
(204, 12, 'NOO', 'Noord', 0),
(205, 12, 'PAL', 'Palm Beach', 0),
(206, 12, 'ROO', 'Rooi Thomas', 0),
(207, 12, 'SIN', 'Sint Nicolaas', 0),
(208, 12, 'SIN', 'Sint Nicolas', 0),
(209, 12, 'WAY', 'Wayaca', 0),
(210, 13, 'ACT', 'Australian Capital Territory', 0),
(211, 13, 'NSW', 'New South Wales', 0),
(212, 13, 'NT', 'Northern Territory', 0),
(213, 13, 'QLD', 'Queensland', 0),
(214, 13, 'SA', 'South Australia', 0),
(215, 13, 'TAS', 'Tasmania', 0),
(216, 13, 'VIC', 'Victoria', 0),
(217, 13, 'WA', 'Western Australia', 0),
(218, 14, 'BUR', 'Burgenland', 0),
(219, 14, 'KAR', 'K&auml;rnten', 0),
(220, 14, 'NOS', 'Nieder&ouml;esterreich', 0),
(221, 14, 'OOS', 'Ober&ouml;esterreich', 0),
(222, 14, 'SAL', 'Salzburg', 0),
(223, 14, 'STE', 'Steiermark', 0),
(224, 14, 'TIR', 'Tirol', 0),
(225, 14, 'VOR', 'Vorarlberg', 0),
(226, 14, 'WIE', 'Wien', 0),
(227, 15, 'AB', 'Ali Bayramli', 0),
(228, 15, 'ABS', 'Abseron', 0),
(229, 15, 'AGC', 'AgcabAdi', 0),
(230, 15, 'AGM', 'Agdam', 0),
(231, 15, 'AGS', 'Agdas', 0),
(232, 15, 'AGA', 'Agstafa', 0),
(233, 15, 'AGU', 'Agsu', 0),
(234, 15, 'AST', 'Astara', 0),
(235, 15, 'BA', 'Baki', 0),
(236, 15, 'BAB', 'BabAk', 0),
(237, 15, 'BAL', 'BalakAn', 0),
(238, 15, 'BAR', 'BArdA', 0),
(239, 15, 'BEY', 'Beylaqan', 0),
(240, 15, 'BIL', 'Bilasuvar', 0),
(241, 15, 'CAB', 'Cabrayil', 0),
(242, 15, 'CAL', 'Calilabab', 0),
(243, 15, 'CUL', 'Culfa', 0),
(244, 15, 'DAS', 'Daskasan', 0),
(245, 15, 'DAV', 'Davaci', 0),
(246, 15, 'FUZ', 'Fuzuli', 0),
(247, 15, 'GA', 'Ganca', 0),
(248, 15, 'GAD', 'Gadabay', 0),
(249, 15, 'GOR', 'Goranboy', 0),
(250, 15, 'GOY', 'Goycay', 0),
(251, 15, 'HAC', 'Haciqabul', 0),
(252, 15, 'IMI', 'Imisli', 0),
(253, 15, 'ISM', 'Ismayilli', 0),
(254, 15, 'KAL', 'Kalbacar', 0),
(255, 15, 'KUR', 'Kurdamir', 0),
(256, 15, 'LA', 'Lankaran', 0),
(257, 15, 'LAC', 'Lacin', 0),
(258, 15, 'LAN', 'Lankaran', 0),
(259, 15, 'LER', 'Lerik', 0),
(260, 15, 'MAS', 'Masalli', 0),
(261, 15, 'MI', 'Mingacevir', 0),
(262, 15, 'NA', 'Naftalan', 0),
(263, 15, 'NEF', 'Neftcala', 0),
(264, 15, 'OGU', 'Oguz', 0),
(265, 15, 'ORD', 'Ordubad', 0),
(266, 15, 'QAB', 'Qabala', 0),
(267, 15, 'QAX', 'Qax', 0),
(268, 15, 'QAZ', 'Qazax', 0),
(269, 15, 'QOB', 'Qobustan', 0),
(270, 15, 'QBA', 'Quba', 0),
(271, 15, 'QBI', 'Qubadli', 0),
(272, 15, 'QUS', 'Qusar', 0),
(273, 15, 'SA', 'Saki', 0),
(274, 15, 'SAT', 'Saatli', 0),
(275, 15, 'SAB', 'Sabirabad', 0),
(276, 15, 'SAD', 'Sadarak', 0),
(277, 15, 'SAH', 'Sahbuz', 0),
(278, 15, 'SAK', 'Saki', 0),
(279, 15, 'SAL', 'Salyan', 0),
(280, 15, 'SM', 'Sumqayit', 0),
(281, 15, 'SMI', 'Samaxi', 0),
(282, 15, 'SKR', 'Samkir', 0),
(283, 15, 'SMX', 'Samux', 0),
(284, 15, 'SAR', 'Sarur', 0),
(285, 15, 'SIY', 'Siyazan', 0),
(286, 15, 'SS', 'Susa', 0),
(287, 15, 'SUS', 'Susa', 0),
(288, 15, 'TAR', 'Tartar', 0),
(289, 15, 'TOV', 'Tovuz', 0),
(290, 15, 'UCA', 'Ucar', 0),
(291, 15, 'XA', 'Xankandi', 0),
(292, 15, 'XAC', 'Xacmaz', 0),
(293, 15, 'XAN', 'Xanlar', 0),
(294, 15, 'XIZ', 'Xizi', 0),
(295, 15, 'XCI', 'Xocali', 0),
(296, 15, 'XVD', 'Xocavand', 0),
(297, 15, 'YAR', 'Yardimli', 0),
(298, 15, 'YEV', 'Yevlax', 0),
(299, 15, 'ZAN', 'Zangilan', 0),
(300, 15, 'ZAQ', 'Zaqatala', 0),
(301, 15, 'ZAR', 'Zardab', 0),
(302, 15, 'NX', 'Naxcivan', 0),
(303, 16, 'ACK', 'Acklins', 0),
(304, 16, 'BER', 'Berry Islands', 0),
(305, 16, 'BIM', 'Bimini', 0),
(306, 16, 'BLK', 'Black Point', 0),
(307, 16, 'CAT', 'Cat Island', 0),
(308, 16, 'CAB', 'Central Abaco', 0),
(309, 16, 'CAN', 'Central Andros', 0),
(310, 16, 'CEL', 'Central Eleuthera', 0),
(311, 16, 'FRE', 'City of Freeport', 0),
(312, 16, 'CRO', 'Crooked Island', 0),
(313, 16, 'EGB', 'East Grand Bahama', 0),
(314, 16, 'EXU', 'Exuma', 0),
(315, 16, 'GRD', 'Grand Cay', 0),
(316, 16, 'HAR', 'Harbour Island', 0),
(317, 16, 'HOP', 'Hope Town', 0),
(318, 16, 'INA', 'Inagua', 0),
(319, 16, 'LNG', 'Long Island', 0),
(320, 16, 'MAN', 'Mangrove Cay', 0),
(321, 16, 'MAY', 'Mayaguana', 0),
(322, 16, 'MOO', 'Moore&apos;s Island', 0),
(323, 16, 'NAB', 'North Abaco', 0),
(324, 16, 'NAN', 'North Andros', 0),
(325, 16, 'NEL', 'North Eleuthera', 0),
(326, 16, 'RAG', 'Ragged Island', 0),
(327, 16, 'RUM', 'Rum Cay', 0),
(328, 16, 'SAL', 'San Salvador', 0),
(329, 16, 'SAB', 'South Abaco', 0),
(330, 16, 'SAN', 'South Andros', 0),
(331, 16, 'SEL', 'South Eleuthera', 0),
(332, 16, 'SWE', 'Spanish Wells', 0),
(333, 16, 'WGB', 'West Grand Bahama', 0),
(334, 17, 'CAP', 'Capital', 0),
(335, 17, 'CEN', 'Central', 0),
(336, 17, 'MUH', 'Muharraq', 0),
(337, 17, 'NOR', 'Northern', 0),
(338, 17, 'SOU', 'Southern', 0),
(339, 18, 'BAR', 'Barisal', 0),
(340, 18, 'CHI', 'Chittagong', 0),
(341, 18, 'DHA', 'Dhaka', 0),
(342, 18, 'KHU', 'Khulna', 0),
(343, 18, 'RAJ', 'Rajshahi', 0),
(344, 18, 'SYL', 'Sylhet', 0),
(345, 19, 'CC', 'Christ Church', 0),
(346, 19, 'AND', 'Saint Andrew', 0),
(347, 19, 'GEO', 'Saint George', 0),
(348, 19, 'JAM', 'Saint James', 0),
(349, 19, 'JOH', 'Saint John', 0),
(350, 19, 'JOS', 'Saint Joseph', 0),
(351, 19, 'LUC', 'Saint Lucy', 0),
(352, 19, 'MIC', 'Saint Michael', 0),
(353, 19, 'PET', 'Saint Peter', 0),
(354, 19, 'PHI', 'Saint Philip', 0),
(355, 19, 'THO', 'Saint Thomas', 0),
(356, 20, 'BR', 'Brestskaya (Brest)', 0),
(357, 20, 'HO', 'Homyel&apos;skaya (Homyel)', 0),
(358, 20, 'HM', 'Horad Minsk', 0),
(359, 20, 'HR', 'Hrodzyenskaya (Hrodna)', 0),
(360, 20, 'MA', 'Mahilyowskaya (Mahilyow)', 0),
(361, 20, 'MI', 'Minskaya', 0),
(362, 20, 'VI', 'Vitsyebskaya (Vitsyebsk)', 0),
(363, 21, 'VAN', 'Antwerpen', 0),
(364, 21, 'WBR', 'Brabant Wallon', 0),
(365, 21, 'WHT', 'Hainaut', 0),
(366, 21, 'WLG', 'Liege', 0),
(367, 21, 'VLI', 'Limburg', 0),
(368, 21, 'WLX', 'Luxembourg', 0),
(369, 21, 'WNA', 'Namur', 0),
(370, 21, 'VOV', 'Oost-Vlaanderen', 0),
(371, 21, 'VBR', 'Vlaams Brabant', 0),
(372, 21, 'VWV', 'West-Vlaanderen', 0),
(373, 22, 'BZ', 'Belize', 0),
(374, 22, 'CY', 'Cayo', 0),
(375, 22, 'CR', 'Corozal', 0),
(376, 22, 'OW', 'Orange Walk', 0),
(377, 22, 'SC', 'Stann Creek', 0),
(378, 22, 'TO', 'Toledo', 0),
(379, 23, 'AL', 'Alibori', 0),
(380, 23, 'AK', 'Atakora', 0),
(381, 23, 'AQ', 'Atlantique', 0),
(382, 23, 'BO', 'Borgou', 0),
(383, 23, 'CO', 'Collines', 0),
(384, 23, 'DO', 'Donga', 0),
(385, 23, 'KO', 'Kouffo', 0),
(386, 23, 'LI', 'Littoral', 0),
(387, 23, 'MO', 'Mono', 0),
(388, 23, 'OU', 'Oueme', 0),
(389, 23, 'PL', 'Plateau', 0),
(390, 23, 'ZO', 'Zou', 0),
(391, 24, 'DS', 'Devonshire', 0),
(392, 24, 'HC', 'Hamilton City', 0),
(393, 24, 'HA', 'Hamilton', 0),
(394, 24, 'PG', 'Paget', 0),
(395, 24, 'PB', 'Pembroke', 0),
(396, 24, 'GC', 'Saint George City', 0),
(397, 24, 'SG', 'Saint George&apos;s', 0),
(398, 24, 'SA', 'Sandys', 0),
(399, 24, 'SM', 'Smith&apos;s', 0),
(400, 24, 'SH', 'Southampton', 0),
(401, 24, 'WA', 'Warwick', 0),
(402, 25, 'BUM', 'Bumthang', 0),
(403, 25, 'CHU', 'Chukha', 0),
(404, 25, 'DAG', 'Dagana', 0),
(405, 25, 'GAS', 'Gasa', 0),
(406, 25, 'HAA', 'Haa', 0),
(407, 25, 'LHU', 'Lhuntse', 0),
(408, 25, 'MON', 'Mongar', 0),
(409, 25, 'PAR', 'Paro', 0),
(410, 25, 'PEM', 'Pemagatshel', 0),
(411, 25, 'PUN', 'Punakha', 0),
(412, 25, 'SJO', 'Samdrup Jongkhar', 0),
(413, 25, 'SAT', 'Samtse', 0),
(414, 25, 'SAR', 'Sarpang', 0),
(415, 25, 'THI', 'Thimphu', 0),
(416, 25, 'TRG', 'Trashigang', 0),
(417, 25, 'TRY', 'Trashiyangste', 0),
(418, 25, 'TRO', 'Trongsa', 0),
(419, 25, 'TSI', 'Tsirang', 0),
(420, 25, 'WPH', 'Wangdue Phodrang', 0),
(421, 25, 'ZHE', 'Zhemgang', 0),
(422, 26, 'BEN', 'Beni', 0),
(423, 26, 'CHU', 'Chuquisaca', 0),
(424, 26, 'COC', 'Cochabamba', 0),
(425, 26, 'LPZ', 'La Paz', 0),
(426, 26, 'ORU', 'Oruro', 0),
(427, 26, 'PAN', 'Pando', 0),
(428, 26, 'POT', 'Potosi', 0),
(429, 26, 'SCZ', 'Santa Cruz', 0),
(430, 26, 'TAR', 'Tarija', 0),
(431, 27, 'BRO', 'Brcko district', 0),
(432, 27, 'FUS', 'Unsko-Sanski Kanton', 0),
(433, 27, 'FPO', 'Posavski Kanton', 0),
(434, 27, 'FTU', 'Tuzlanski Kanton', 0),
(435, 27, 'FZE', 'Zenicko-Dobojski Kanton', 0),
(436, 27, 'FBP', 'Bosanskopodrinjski Kanton', 0),
(437, 27, 'FSB', 'Srednjebosanski Kanton', 0),
(438, 27, 'FHN', 'Hercegovacko-neretvanski Kanton', 0),
(439, 27, 'FZH', 'Zapadnohercegovacka Zupanija', 0),
(440, 27, 'FSA', 'Kanton Sarajevo', 0),
(441, 27, 'FZA', 'Zapadnobosanska', 0),
(442, 27, 'SBL', 'Banja Luka', 0),
(443, 27, 'SDO', 'Doboj', 0),
(444, 27, 'SBI', 'Bijeljina', 0),
(445, 27, 'SVL', 'Vlasenica', 0),
(446, 27, 'SSR', 'Sarajevo-Romanija or Sokolac', 0),
(447, 27, 'SFO', 'Foca', 0),
(448, 27, 'STR', 'Trebinje', 0),
(449, 28, 'CE', 'Central', 0),
(450, 28, 'GH', 'Ghanzi', 0),
(451, 28, 'KD', 'Kgalagadi', 0),
(452, 28, 'KT', 'Kgatleng', 0),
(453, 28, 'KW', 'Kweneng', 0),
(454, 28, 'NG', 'Ngamiland', 0),
(455, 28, 'NE', 'North East', 0),
(456, 28, 'NW', 'North West', 0),
(457, 28, 'SE', 'South East', 0),
(458, 28, 'SO', 'Southern', 0),
(459, 30, 'AC', 'Acre', 0),
(460, 30, 'AL', 'Alagoas', 0),
(461, 30, 'AP', 'Amapa', 0),
(462, 30, 'AM', 'Amazonas', 0),
(463, 30, 'BA', 'Bahia', 0),
(464, 30, 'CE', 'Ceara', 0),
(465, 30, 'DF', 'Distrito Federal', 0),
(466, 30, 'ES', 'Espirito Santo', 0),
(467, 30, 'GO', 'Goias', 0),
(468, 30, 'MA', 'Maranhao', 0),
(469, 30, 'MT', 'Mato Grosso', 0),
(470, 30, 'MS', 'Mato Grosso do Sul', 0),
(471, 30, 'MG', 'Minas Gerais', 0),
(472, 30, 'PA', 'Para', 0),
(473, 30, 'PB', 'Paraiba', 0),
(474, 30, 'PR', 'Parana', 0),
(475, 30, 'PE', 'Pernambuco', 0),
(476, 30, 'PI', 'Piaui', 0),
(477, 30, 'RJ', 'Rio de Janeiro', 0),
(478, 30, 'RN', 'Rio Grande do Norte', 0),
(479, 30, 'RS', 'Rio Grande do Sul', 0),
(480, 30, 'RO', 'Rondonia', 0),
(481, 30, 'RR', 'Roraima', 0),
(482, 30, 'SC', 'Santa Catarina', 0),
(483, 30, 'SP', 'Sao Paulo', 0),
(484, 30, 'SE', 'Sergipe', 0),
(485, 30, 'TO', 'Tocantins', 0),
(486, 31, 'PB', 'Peros Banhos', 0),
(487, 31, 'SI', 'Salomon Islands', 0),
(488, 31, 'NI', 'Nelsons Island', 0),
(489, 31, 'TB', 'Three Brothers', 0),
(490, 31, 'EA', 'Eagle Islands', 0),
(491, 31, 'DI', 'Danger Island', 0),
(492, 31, 'EG', 'Egmont Islands', 0),
(493, 31, 'DG', 'Diego Garcia', 0),
(494, 32, 'BEL', 'Belait', 0),
(495, 32, 'BRM', 'Brunei and Muara', 0),
(496, 32, 'TEM', 'Temburong', 0),
(497, 32, 'TUT', 'Tutong', 0),
(498, 33, '', 'Blagoevgrad', 0),
(499, 33, '', 'Burgas', 0),
(500, 33, '', 'Dobrich', 0),
(501, 33, '', 'Gabrovo', 0),
(502, 33, '', 'Haskovo', 0),
(503, 33, '', 'Kardjali', 0),
(504, 33, '', 'Kyustendil', 0),
(505, 33, '', 'Lovech', 0),
(506, 33, '', 'Montana', 0),
(507, 33, '', 'Pazardjik', 0),
(508, 33, '', 'Pernik', 0),
(509, 33, '', 'Pleven', 0),
(510, 33, '', 'Plovdiv', 0),
(511, 33, '', 'Razgrad', 0),
(512, 33, '', 'Shumen', 0),
(513, 33, '', 'Silistra', 0),
(514, 33, '', 'Sliven', 0),
(515, 33, '', 'Smolyan', 0),
(516, 33, '', 'Sofia', 0),
(517, 33, '', 'Sofia - town', 0),
(518, 33, '', 'Stara Zagora', 0),
(519, 33, '', 'Targovishte', 0),
(520, 33, '', 'Varna', 0),
(521, 33, '', 'Veliko Tarnovo', 0),
(522, 33, '', 'Vidin', 0),
(523, 33, '', 'Vratza', 0),
(524, 33, '', 'Yambol', 0),
(525, 34, 'BAL', 'Bale', 0),
(526, 34, 'BAM', 'Bam', 0),
(527, 34, 'BAN', 'Banwa', 0),
(528, 34, 'BAZ', 'Bazega', 0),
(529, 34, 'BOR', 'Bougouriba', 0),
(530, 34, 'BLG', 'Boulgou', 0),
(531, 34, 'BOK', 'Boulkiemde', 0),
(532, 34, 'COM', 'Comoe', 0),
(533, 34, 'GAN', 'Ganzourgou', 0),
(534, 34, 'GNA', 'Gnagna', 0),
(535, 34, 'GOU', 'Gourma', 0),
(536, 34, 'HOU', 'Houet', 0),
(537, 34, 'IOA', 'Ioba', 0),
(538, 34, 'KAD', 'Kadiogo', 0),
(539, 34, 'KEN', 'Kenedougou', 0),
(540, 34, 'KOD', 'Komondjari', 0),
(541, 34, 'KOP', 'Kompienga', 0),
(542, 34, 'KOS', 'Kossi', 0),
(543, 34, 'KOL', 'Koulpelogo', 0),
(544, 34, 'KOT', 'Kouritenga', 0),
(545, 34, 'KOW', 'Kourweogo', 0),
(546, 34, 'LER', 'Leraba', 0),
(547, 34, 'LOR', 'Loroum', 0),
(548, 34, 'MOU', 'Mouhoun', 0),
(549, 34, 'NAH', 'Nahouri', 0),
(550, 34, 'NAM', 'Namentenga', 0),
(551, 34, 'NAY', 'Nayala', 0),
(552, 34, 'NOU', 'Noumbiel', 0),
(553, 34, 'OUB', 'Oubritenga', 0),
(554, 34, 'OUD', 'Oudalan', 0),
(555, 34, 'PAS', 'Passore', 0),
(556, 34, 'PON', 'Poni', 0),
(557, 34, 'SAG', 'Sanguie', 0),
(558, 34, 'SAM', 'Sanmatenga', 0),
(559, 34, 'SEN', 'Seno', 0),
(560, 34, 'SIS', 'Sissili', 0),
(561, 34, 'SOM', 'Soum', 0),
(562, 34, 'SOR', 'Sourou', 0),
(563, 34, 'TAP', 'Tapoa', 0),
(564, 34, 'TUY', 'Tuy', 0),
(565, 34, 'YAG', 'Yagha', 0),
(566, 34, 'YAT', 'Yatenga', 0),
(567, 34, 'ZIR', 'Ziro', 0),
(568, 34, 'ZOD', 'Zondoma', 0),
(569, 34, 'ZOW', 'Zoundweogo', 0),
(570, 35, 'BB', 'Bubanza', 0),
(571, 35, 'BJ', 'Bujumbura', 0),
(572, 35, 'BR', 'Bururi', 0),
(573, 35, 'CA', 'Cankuzo', 0),
(574, 35, 'CI', 'Cibitoke', 0),
(575, 35, 'GI', 'Gitega', 0),
(576, 35, 'KR', 'Karuzi', 0),
(577, 35, 'KY', 'Kayanza', 0),
(578, 35, 'KI', 'Kirundo', 0),
(579, 35, 'MA', 'Makamba', 0),
(580, 35, 'MU', 'Muramvya', 0),
(581, 35, 'MY', 'Muyinga', 0),
(582, 35, 'MW', 'Mwaro', 0),
(583, 35, 'NG', 'Ngozi', 0),
(584, 35, 'RT', 'Rutana', 0),
(585, 35, 'RY', 'Ruyigi', 0),
(586, 36, 'PP', 'Phnom Penh', 0),
(587, 36, 'PS', 'Preah Seihanu (Kompong Som or Si', 0),
(588, 36, 'PA', 'Pailin', 0),
(589, 36, 'KB', 'Keb', 0),
(590, 36, 'BM', 'Banteay Meanchey', 0),
(591, 36, 'BA', 'Battambang', 0),
(592, 36, 'KM', 'Kampong Cham', 0),
(593, 36, 'KN', 'Kampong Chhnang', 0),
(594, 36, 'KU', 'Kampong Speu', 0),
(595, 36, 'KO', 'Kampong Som', 0),
(596, 36, 'KT', 'Kampong Thom', 0),
(597, 36, 'KP', 'Kampot', 0),
(598, 36, 'KL', 'Kandal', 0),
(599, 36, 'KK', 'Kaoh Kong', 0),
(600, 36, 'KR', 'Kratie', 0),
(601, 36, 'MK', 'Mondul Kiri', 0),
(602, 36, 'OM', 'Oddar Meancheay', 0),
(603, 36, 'PU', 'Pursat', 0),
(604, 36, 'PR', 'Preah Vihear', 0),
(605, 36, 'PG', 'Prey Veng', 0),
(606, 36, 'RK', 'Ratanak Kiri', 0),
(607, 36, 'SI', 'Siemreap', 0),
(608, 36, 'ST', 'Stung Treng', 0),
(609, 36, 'SR', 'Svay Rieng', 0),
(610, 36, 'TK', 'Takeo', 0),
(611, 37, 'ADA', 'Adamawa (Adamaoua)', 0),
(612, 37, 'CEN', 'Centre', 0),
(613, 37, 'EST', 'East (Est)', 0),
(614, 37, 'EXN', 'Extreme-Nord', 0),
(615, 37, 'LIT', 'Littoral', 0),
(616, 37, 'NOR', 'North (Nord)', 0),
(617, 37, 'NOT', 'Northwest (Nord-Ouest)', 0),
(618, 37, 'OUE', 'West (Ouest)', 0),
(619, 37, 'SUD', 'South (Sud)', 0),
(620, 37, 'SOU', 'Southwest (Sud-Ouest).', 0),
(621, 38, 'AB', 'Alberta', 0),
(622, 38, 'BC', 'British Columbia', 0),
(623, 38, 'MB', 'Manitoba', 0),
(624, 38, 'NB', 'New Brunswick', 0),
(625, 38, 'NL', 'Newfoundland and Labrador', 0),
(626, 38, 'NT', 'Northwest Territories', 0),
(627, 38, 'NS', 'Nova Scotia', 0),
(628, 38, 'NU', 'Nunavut', 0),
(629, 38, 'ON', 'Ontario', 0),
(630, 38, 'PE', 'Prince Edward Island', 0),
(631, 38, 'QC', 'Qu&eacute;bec', 0),
(632, 38, 'SK', 'Saskatchewan', 0),
(633, 38, 'YT', 'Yukon Territory', 0),
(634, 39, 'BV', 'Boa Vista', 0),
(635, 39, 'BR', 'Brava', 0),
(636, 39, 'CS', 'Calheta de Sao Miguel', 0),
(637, 39, 'MA', 'Maio', 0),
(638, 39, 'MO', 'Mosteiros', 0),
(639, 39, 'PA', 'Paul', 0),
(640, 39, 'PN', 'Porto Novo', 0),
(641, 39, 'PR', 'Praia', 0),
(642, 39, 'RG', 'Ribeira Grande', 0),
(643, 39, 'SL', 'Sal', 0),
(644, 39, 'CA', 'Santa Catarina', 0),
(645, 39, 'CR', 'Santa Cruz', 0),
(646, 39, 'SD', 'Sao Domingos', 0),
(647, 39, 'SF', 'Sao Filipe', 0),
(648, 39, 'SN', 'Sao Nicolau', 0),
(649, 39, 'SV', 'Sao Vicente', 0),
(650, 39, 'TA', 'Tarrafal', 0),
(651, 40, 'CR', 'Creek', 0),
(652, 40, 'EA', 'Eastern', 0),
(653, 40, 'ML', 'Midland', 0),
(654, 40, 'ST', 'South Town', 0),
(655, 40, 'SP', 'Spot Bay', 0),
(656, 40, 'SK', 'Stake Bay', 0),
(657, 40, 'WD', 'West End', 0),
(658, 40, 'WN', 'Western', 0),
(659, 41, 'BBA', 'Bamingui-Bangoran', 0),
(660, 41, 'BKO', 'Basse-Kotto', 0),
(661, 41, 'HKO', 'Haute-Kotto', 0),
(662, 41, 'HMB', 'Haut-Mbomou', 0),
(663, 41, 'KEM', 'Kemo', 0),
(664, 41, 'LOB', 'Lobaye', 0),
(665, 41, 'MKD', 'Mamba', 0),
(666, 41, 'MBO', 'Mbomou', 0),
(667, 41, 'NMM', 'Nana-Mambere', 0),
(668, 41, 'OMP', 'Ombella-M&apos;Poko', 0),
(669, 41, 'OUK', 'Ouaka', 0),
(670, 41, 'OUH', 'Ouham', 0),
(671, 41, 'OPE', 'Ouham-Pende', 0),
(672, 41, 'VAK', 'Vakaga', 0),
(673, 41, 'NGR', 'Nana-Grebizi', 0),
(674, 41, 'SMB', 'Sangha-Mbaere', 0),
(675, 41, 'BAN', 'Bangui', 0),
(676, 42, 'BA', 'Batha', 0),
(677, 42, 'BI', 'Biltine', 0),
(678, 42, 'BE', 'Borkou-Ennedi-Tibesti', 0),
(679, 42, 'CB', 'Chari-Baguirmi', 0),
(680, 42, 'GU', 'Guera', 0),
(681, 42, 'KA', 'Kanem', 0),
(682, 42, 'LA', 'Lac', 0),
(683, 42, 'LC', 'Logone Occidental', 0),
(684, 42, 'LR', 'Logone Oriental', 0),
(685, 42, 'MK', 'Mayo-Kebbi', 0),
(686, 42, 'MC', 'Moyen-Chari', 0),
(687, 42, 'OU', 'Ouaddai', 0),
(688, 42, 'SA', 'Salamat', 0),
(689, 42, 'TA', 'Tandjile', 0),
(690, 43, 'AI', 'Aisen del General Carlos Ibanez', 0),
(691, 43, 'AN', 'Antofagasta', 0),
(692, 43, 'AR', 'Araucania', 0),
(693, 43, 'AT', 'Atacama', 0),
(694, 43, 'BI', 'Bio-Bio', 0),
(695, 43, 'CO', 'Coquimbo', 0),
(696, 43, 'LI', 'Libertador General Bernardo O&ap', 0),
(697, 43, 'LL', 'Los Lagos', 0),
(698, 43, 'MA', 'Magallanes y de la Antartica Chi', 0),
(699, 43, 'ML', 'Maule', 0),
(700, 43, 'RM', 'Region Metropolitana', 0),
(701, 43, 'TA', 'Tarapaca', 0),
(702, 43, 'VS', 'Valparaiso', 0),
(703, 44, 'AN', 'Anhui', 0),
(704, 44, 'BE', 'Beijing', 0),
(705, 44, 'CH', 'Chongqing', 0),
(706, 44, 'FU', 'Fujian', 0),
(707, 44, 'GA', 'Gansu', 0),
(708, 44, 'GU', 'Guangdong', 0),
(709, 44, 'GX', 'Guangxi', 0),
(710, 44, 'GZ', 'Guizhou', 0),
(711, 44, 'HA', 'Hainan', 0),
(712, 44, 'HB', 'Hebei', 0),
(713, 44, 'HL', 'Heilongjiang', 0),
(714, 44, 'HE', 'Henan', 0),
(715, 44, 'HK', 'Hong Kong', 0),
(716, 44, 'HU', 'Hubei', 0),
(717, 44, 'HN', 'Hunan', 0),
(718, 44, 'IM', 'Inner Mongolia', 0),
(719, 44, 'JI', 'Jiangsu', 0),
(720, 44, 'JX', 'Jiangxi', 0),
(721, 44, 'JL', 'Jilin', 0),
(722, 44, 'LI', 'Liaoning', 0),
(723, 44, 'MA', 'Macau', 0),
(724, 44, 'NI', 'Ningxia', 0),
(725, 44, 'SH', 'Shaanxi', 0),
(726, 44, 'SA', 'Shandong', 0),
(727, 44, 'SG', 'Shanghai', 0),
(728, 44, 'SX', 'Shanxi', 0),
(729, 44, 'SI', 'Sichuan', 0),
(730, 44, 'TI', 'Tianjin', 0),
(731, 44, 'XI', 'Xinjiang', 0),
(732, 44, 'YU', 'Yunnan', 0),
(733, 44, 'ZH', 'Zhejiang', 0),
(734, 46, 'D', 'Direction Island', 0),
(735, 46, 'H', 'Home Island', 0),
(736, 46, 'O', 'Horsburgh Island', 0),
(737, 46, 'S', 'South Island', 0),
(738, 46, 'W', 'West Island', 0),
(739, 47, 'AMZ', 'Amazonas', 0),
(740, 47, 'ANT', 'Antioquia', 0),
(741, 47, 'ARA', 'Arauca', 0),
(742, 47, 'ATL', 'Atlantico', 0),
(743, 47, 'BDC', 'Bogota D.C.', 0),
(744, 47, 'BOL', 'Bolivar', 0),
(745, 47, 'BOY', 'Boyaca', 0),
(746, 47, 'CAL', 'Caldas', 0),
(747, 47, 'CAQ', 'Caqueta', 0),
(748, 47, 'CAS', 'Casanare', 0),
(749, 47, 'CAU', 'Cauca', 0),
(750, 47, 'CES', 'Cesar', 0),
(751, 47, 'CHO', 'Choco', 0),
(752, 47, 'COR', 'Cordoba', 0),
(753, 47, 'CAM', 'Cundinamarca', 0),
(754, 47, 'GNA', 'Guainia', 0),
(755, 47, 'GJR', 'Guajira', 0),
(756, 47, 'GVR', 'Guaviare', 0),
(757, 47, 'HUI', 'Huila', 0),
(758, 47, 'MAG', 'Magdalena', 0),
(759, 47, 'MET', 'Meta', 0),
(760, 47, 'NAR', 'Narino', 0),
(761, 47, 'NDS', 'Norte de Santander', 0),
(762, 47, 'PUT', 'Putumayo', 0),
(763, 47, 'QUI', 'Quindio', 0),
(764, 47, 'RIS', 'Risaralda', 0),
(765, 47, 'SAP', 'San Andres y Providencia', 0),
(766, 47, 'SAN', 'Santander', 0),
(767, 47, 'SUC', 'Sucre', 0),
(768, 47, 'TOL', 'Tolima', 0),
(769, 47, 'VDC', 'Valle del Cauca', 0),
(770, 47, 'VAU', 'Vaupes', 0),
(771, 47, 'VIC', 'Vichada', 0),
(772, 48, 'G', 'Grande Comore', 0),
(773, 48, 'A', 'Anjouan', 0),
(774, 48, 'M', 'Moheli', 0),
(775, 49, 'BO', 'Bouenza', 0),
(776, 49, 'BR', 'Brazzaville', 0),
(777, 49, 'CU', 'Cuvette', 0),
(778, 49, 'CO', 'Cuvette-Ouest', 0),
(779, 49, 'KO', 'Kouilou', 0),
(780, 49, 'LE', 'Lekoumou', 0),
(781, 49, 'LI', 'Likouala', 0),
(782, 49, 'NI', 'Niari', 0),
(783, 49, 'PL', 'Plateaux', 0),
(784, 49, 'PO', 'Pool', 0),
(785, 49, 'SA', 'Sangha', 0),
(786, 50, 'PU', 'Pukapuka', 0),
(787, 50, 'RK', 'Rakahanga', 0),
(788, 50, 'MK', 'Manihiki', 0),
(789, 50, 'PE', 'Penrhyn', 0),
(790, 50, 'NI', 'Nassau Island', 0),
(791, 50, 'SU', 'Surwarrow', 0),
(792, 50, 'PA', 'Palmerston', 0),
(793, 50, 'AI', 'Aitutaki', 0),
(794, 50, 'MA', 'Manuae', 0),
(795, 50, 'TA', 'Takutea', 0),
(796, 50, 'MT', 'Mitiaro', 0),
(797, 50, 'AT', 'Atiu', 0),
(798, 50, 'MU', 'Mauke', 0),
(799, 50, 'RR', 'Rarotonga', 0),
(800, 50, 'MG', 'Mangaia', 0),
(801, 51, 'AL', 'Alajuela', 0),
(802, 51, 'CA', 'Cartago', 0),
(803, 51, 'GU', 'Guanacaste', 0),
(804, 51, 'HE', 'Heredia', 0),
(805, 51, 'LI', 'Limon', 0),
(806, 51, 'PU', 'Puntarenas', 0),
(807, 51, 'SJ', 'San Jose', 0),
(808, 52, 'ABE', 'Abengourou', 0),
(809, 52, 'ABI', 'Abidjan', 0),
(810, 52, 'ABO', 'Aboisso', 0),
(811, 52, 'ADI', 'Adiake', 0),
(812, 52, 'ADZ', 'Adzope', 0),
(813, 52, 'AGB', 'Agboville', 0),
(814, 52, 'AGN', 'Agnibilekrou', 0),
(815, 52, 'ALE', 'Alepe', 0),
(816, 52, 'BOC', 'Bocanda', 0),
(817, 52, 'BAN', 'Bangolo', 0),
(818, 52, 'BEO', 'Beoumi', 0),
(819, 52, 'BIA', 'Biankouma', 0),
(820, 52, 'BDK', 'Bondoukou', 0),
(821, 52, 'BGN', 'Bongouanou', 0),
(822, 52, 'BFL', 'Bouafle', 0),
(823, 52, 'BKE', 'Bouake', 0),
(824, 52, 'BNA', 'Bouna', 0),
(825, 52, 'BDL', 'Boundiali', 0),
(826, 52, 'DKL', 'Dabakala', 0),
(827, 52, 'DBU', 'Dabou', 0),
(828, 52, 'DAL', 'Daloa', 0),
(829, 52, 'DAN', 'Danane', 0),
(830, 52, 'DAO', 'Daoukro', 0),
(831, 52, 'DIM', 'Dimbokro', 0),
(832, 52, 'DIV', 'Divo', 0),
(833, 52, 'DUE', 'Duekoue', 0),
(834, 52, 'FER', 'Ferkessedougou', 0),
(835, 52, 'GAG', 'Gagnoa', 0),
(836, 52, 'GBA', 'Grand-Bassam', 0),
(837, 52, 'GLA', 'Grand-Lahou', 0),
(838, 52, 'GUI', 'Guiglo', 0),
(839, 52, 'ISS', 'Issia', 0),
(840, 52, 'JAC', 'Jacqueville', 0),
(841, 52, 'KAT', 'Katiola', 0),
(842, 52, 'KOR', 'Korhogo', 0),
(843, 52, 'LAK', 'Lakota', 0),
(844, 52, 'MAN', 'Man', 0),
(845, 52, 'MKN', 'Mankono', 0),
(846, 52, 'MBA', 'Mbahiakro', 0),
(847, 52, 'ODI', 'Odienne', 0),
(848, 52, 'OUM', 'Oume', 0),
(849, 52, 'SAK', 'Sakassou', 0),
(850, 52, 'SPE', 'San-Pedro', 0),
(851, 52, 'SAS', 'Sassandra', 0),
(852, 52, 'SEG', 'Seguela', 0),
(853, 52, 'SIN', 'Sinfra', 0),
(854, 52, 'SOU', 'Soubre', 0),
(855, 52, 'TAB', 'Tabou', 0),
(856, 52, 'TAN', 'Tanda', 0),
(857, 52, 'TIE', 'Tiebissou', 0),
(858, 52, 'TIN', 'Tingrela', 0),
(859, 52, 'TIA', 'Tiassale', 0),
(860, 52, 'TBA', 'Touba', 0),
(861, 52, 'TLP', 'Toulepleu', 0),
(862, 52, 'TMD', 'Toumodi', 0),
(863, 52, 'VAV', 'Vavoua', 0),
(864, 52, 'YAM', 'Yamoussoukro', 0),
(865, 52, 'ZUE', 'Zuenoula', 0),
(866, 53, 'BB', 'Bjelovar-Bilogora', 0),
(867, 53, 'CZ', 'City of Zagreb', 0),
(868, 53, 'DN', 'Dubrovnik-Neretva', 0),
(869, 53, 'IS', 'Istra', 0),
(870, 53, 'KA', 'Karlovac', 0),
(871, 53, 'KK', 'Koprivnica-Krizevci', 0),
(872, 53, 'KZ', 'Krapina-Zagorje', 0),
(873, 53, 'LS', 'Lika-Senj', 0),
(874, 53, 'ME', 'Medimurje', 0),
(875, 53, 'OB', 'Osijek-Baranja', 0),
(876, 53, 'PS', 'Pozega-Slavonia', 0),
(877, 53, 'PG', 'Primorje-Gorski Kotar', 0),
(878, 53, 'SI', 'Sibenik', 0),
(879, 53, 'SM', 'Sisak-Moslavina', 0),
(880, 53, 'SB', 'Slavonski Brod-Posavina', 0),
(881, 53, 'SD', 'Split-Dalmatia', 0),
(882, 53, 'VA', 'Varazdin', 0),
(883, 53, 'VP', 'Virovitica-Podravina', 0),
(884, 53, 'VS', 'Vukovar-Srijem', 0),
(885, 53, 'ZK', 'Zadar-Knin', 0),
(886, 53, 'ZA', 'Zagreb', 0),
(887, 54, 'CA', 'Camaguey', 0),
(888, 54, 'CD', 'Ciego de Avila', 0),
(889, 54, 'CI', 'Cienfuegos', 0),
(890, 54, 'CH', 'Ciudad de La Habana', 0),
(891, 54, 'GR', 'Granma', 0),
(892, 54, 'GU', 'Guantanamo', 0),
(893, 54, 'HO', 'Holguin', 0),
(894, 54, 'IJ', 'Isla de la Juventud', 0),
(895, 54, 'LH', 'La Habana', 0),
(896, 54, 'LT', 'Las Tunas', 0),
(897, 54, 'MA', 'Matanzas', 0),
(898, 54, 'PR', 'Pinar del Rio', 0),
(899, 54, 'SS', 'Sancti Spiritus', 0),
(900, 54, 'SC', 'Santiago de Cuba', 0),
(901, 54, 'VC', 'Villa Clara', 0),
(902, 55, 'F', 'Famagusta', 0),
(903, 55, 'K', 'Kyrenia', 0),
(904, 55, 'A', 'Larnaca', 0),
(905, 55, 'I', 'Limassol', 0),
(906, 55, 'N', 'Nicosia', 0),
(907, 55, 'P', 'Paphos', 0),
(908, 56, 'U', '', 0),
(909, 56, 'C', 'Jihocesk', 0),
(910, 56, 'B', 'Jihomoravsk', 0),
(911, 56, 'K', 'Karlovarsk', 0),
(912, 56, 'H', 'Kr', 0),
(913, 56, 'L', 'Libereck', 0),
(914, 56, 'T', 'Moravskoslezsk', 0),
(915, 56, 'M', 'Olomouck', 0),
(916, 56, 'E', 'Pardubick', 0),
(917, 56, 'P', 'Plzensk', 0),
(918, 56, 'A', 'Hlavn', 0),
(919, 56, 'S', 'Stredocesk', 0),
(920, 56, 'J', 'Kraj Vysocina', 0),
(921, 56, 'Z', 'Zl', 0),
(922, 57, 'AR', 'Arhus', 0),
(923, 57, 'BH', 'Bornholm', 0),
(924, 57, 'CO', 'Copenhagen', 0),
(925, 57, 'FO', 'Faroe Islands', 0),
(926, 57, 'FR', 'Frederiksborg', 0),
(927, 57, 'FY', 'Fyn', 0),
(928, 57, 'KO', 'Kobenhavn', 0),
(929, 57, 'NO', 'Nordjylland', 0),
(930, 57, 'RI', 'Ribe', 0),
(931, 57, 'RK', 'Ringkobing', 0),
(932, 57, 'RO', 'Roskilde', 0),
(933, 57, 'SO', 'Sonderjylland', 0),
(934, 57, 'ST', 'Storstrom', 0),
(935, 57, 'VK', 'Vejle', 0),
(936, 57, 'VJ', 'Vestj&aelig;lland', 0),
(937, 57, 'VB', 'Viborg', 0),
(938, 58, 'S', 'Ali Sabih', 0),
(939, 58, 'K', 'Dikhil', 0),
(940, 58, 'J', 'Djibouti', 0),
(941, 58, 'O', 'Obock', 0),
(942, 58, 'T', 'Tadjoura', 0),
(943, 59, 'AND', 'Saint Andrew Parish', 0),
(944, 59, 'DAV', 'Saint David Parish', 0),
(945, 59, 'GEO', 'Saint George Parish', 0),
(946, 59, 'JOH', 'Saint John Parish', 0),
(947, 59, 'JOS', 'Saint Joseph Parish', 0),
(948, 59, 'LUK', 'Saint Luke Parish', 0),
(949, 59, 'MAR', 'Saint Mark Parish', 0),
(950, 59, 'PAT', 'Saint Patrick Parish', 0),
(951, 59, 'PAU', 'Saint Paul Parish', 0),
(952, 59, 'PET', 'Saint Peter Parish', 0),
(953, 60, 'DN', 'Distrito Nacional', 0),
(954, 60, 'AZ', 'Azua', 0),
(955, 60, 'BC', 'Baoruco', 0),
(956, 60, 'BH', 'Barahona', 0),
(957, 60, 'DJ', 'Dajabon', 0),
(958, 60, 'DU', 'Duarte', 0),
(959, 60, 'EL', 'Elias Pina', 0),
(960, 60, 'SY', 'El Seybo', 0),
(961, 60, 'ET', 'Espaillat', 0),
(962, 60, 'HM', 'Hato Mayor', 0),
(963, 60, 'IN', 'Independencia', 0),
(964, 60, 'AL', 'La Altagracia', 0),
(965, 60, 'RO', 'La Romana', 0),
(966, 60, 'VE', 'La Vega', 0),
(967, 60, 'MT', 'Maria Trinidad Sanchez', 0),
(968, 60, 'MN', 'Monsenor Nouel', 0),
(969, 60, 'MC', 'Monte Cristi', 0),
(970, 60, 'MP', 'Monte Plata', 0),
(971, 60, 'PD', 'Pedernales', 0),
(972, 60, 'PR', 'Peravia (Bani)', 0),
(973, 60, 'PP', 'Puerto Plata', 0),
(974, 60, 'SL', 'Salcedo', 0),
(975, 60, 'SM', 'Samana', 0),
(976, 60, 'SH', 'Sanchez Ramirez', 0),
(977, 60, 'SC', 'San Cristobal', 0),
(978, 60, 'JO', 'San Jose de Ocoa', 0),
(979, 60, 'SJ', 'San Juan', 0),
(980, 60, 'PM', 'San Pedro de Macoris', 0),
(981, 60, 'SA', 'Santiago', 0),
(982, 60, 'ST', 'Santiago Rodriguez', 0),
(983, 60, 'SD', 'Santo Domingo', 0),
(984, 60, 'VA', 'Valverde', 0),
(985, 61, 'AL', 'Aileu', 0),
(986, 61, 'AN', 'Ainaro', 0),
(987, 61, 'BA', 'Baucau', 0),
(988, 61, 'BO', 'Bobonaro', 0),
(989, 61, 'CO', 'Cova Lima', 0),
(990, 61, 'DI', 'Dili', 0),
(991, 61, 'ER', 'Ermera', 0),
(992, 61, 'LA', 'Lautem', 0),
(993, 61, 'LI', 'Liquica', 0),
(994, 61, 'MT', 'Manatuto', 0),
(995, 61, 'MF', 'Manufahi', 0),
(996, 61, 'OE', 'Oecussi', 0),
(997, 61, 'VI', 'Viqueque', 0),
(998, 62, 'AZU', 'Azuay', 0),
(999, 62, 'BOL', 'Bolivar', 0),
(1000, 62, 'CAN', 'Ca&ntilde;ar', 0),
(1001, 62, 'CAR', 'Carchi', 0),
(1002, 62, 'CHI', 'Chimborazo', 0),
(1003, 62, 'COT', 'Cotopaxi', 0),
(1004, 62, 'EOR', 'El Oro', 0),
(1005, 62, 'ESM', 'Esmeraldas', 0),
(1006, 62, 'GPS', 'Gal&aacute;pagos', 0),
(1007, 62, 'GUA', 'Guayas', 0),
(1008, 62, 'IMB', 'Imbabura', 0),
(1009, 62, 'LOJ', 'Loja', 0),
(1010, 62, 'LRO', 'Los R', 0),
(1011, 62, 'MAN', 'Manab&iacute;', 0),
(1012, 62, 'MSA', 'Morona Santiago', 0),
(1013, 62, 'NAP', 'Napo', 0),
(1014, 62, 'ORE', 'Orellana', 0),
(1015, 62, 'PAS', 'Pastaza', 0),
(1016, 62, 'PIC', 'Pichincha', 0),
(1017, 62, 'SUC', 'Sucumb&iacute;os', 0),
(1018, 62, 'TUN', 'Tungurahua', 0),
(1019, 62, 'ZCH', 'Zamora Chinchipe', 0),
(1020, 63, 'DHY', 'Ad Daqahliyah', 0),
(1021, 63, 'BAM', 'Al Bahr al Ahmar', 0),
(1022, 63, 'BHY', 'Al Buhayrah', 0),
(1023, 63, 'FYM', 'Al Fayyum', 0),
(1024, 63, 'GBY', 'Al Gharbiyah', 0),
(1025, 63, 'IDR', 'Al Iskandariyah', 0),
(1026, 63, 'IML', 'Al Isma&apos;iliyah', 0),
(1027, 63, 'JZH', 'Al Jizah', 0),
(1028, 63, 'MFY', 'Al Minufiyah', 0),
(1029, 63, 'MNY', 'Al Minya', 0),
(1030, 63, 'QHR', 'Al Qahirah', 0),
(1031, 63, 'QLY', 'Al Qalyubiyah', 0),
(1032, 63, 'WJD', 'Al Wadi al Jadid', 0),
(1033, 63, 'SHQ', 'Ash Sharqiyah', 0),
(1034, 63, 'SWY', 'As Suways', 0),
(1035, 63, 'ASW', 'Aswan', 0),
(1036, 63, 'ASY', 'Asyut', 0),
(1037, 63, 'BSW', 'Bani Suwayf', 0),
(1038, 63, 'BSD', 'Bur Sa&apos;id', 0),
(1039, 63, 'DMY', 'Dumyat', 0),
(1040, 63, 'JNS', 'Janub Sina', 0),
(1041, 63, 'KSH', 'Kafr ash Shaykh', 0),
(1042, 63, 'MAT', 'Matruh', 0),
(1043, 63, 'QIN', 'Qina', 0),
(1044, 63, 'SHS', 'Shamal Sina', 0),
(1045, 63, 'SUH', 'Suhaj', 0),
(1046, 64, 'AH', 'Ahuachapan', 0),
(1047, 64, 'CA', 'Cabanas', 0),
(1048, 64, 'CH', 'Chalatenango', 0),
(1049, 64, 'CU', 'Cuscatlan', 0),
(1050, 64, 'LB', 'La Libertad', 0),
(1051, 64, 'PZ', 'La Paz', 0),
(1052, 64, 'UN', 'La Union', 0),
(1053, 64, 'MO', 'Morazan', 0),
(1054, 64, 'SM', 'San Miguel', 0),
(1055, 64, 'SS', 'San Salvador', 0),
(1056, 64, 'SV', 'San Vicente', 0),
(1057, 64, 'SA', 'Santa Ana', 0),
(1058, 64, 'SO', 'Sonsonate', 0),
(1059, 64, 'US', 'Usulutan', 0),
(1060, 65, 'AN', 'Provincia Annobon', 0),
(1061, 65, 'BN', 'Provincia Bioko Norte', 0),
(1062, 65, 'BS', 'Provincia Bioko Sur', 0),
(1063, 65, 'CS', 'Provincia Centro Sur', 0),
(1064, 65, 'KN', 'Provincia Kie-Ntem', 0),
(1065, 65, 'LI', 'Provincia Litoral', 0),
(1066, 65, 'WN', 'Provincia Wele-Nzas', 0),
(1067, 66, 'MA', 'Central (Maekel)', 0),
(1068, 66, 'KE', 'Anseba (Keren)', 0),
(1069, 66, 'DK', 'Southern Red Sea (Debub-Keih-Bah', 0),
(1070, 66, 'SK', 'Northern Red Sea (Semien-Keih-Ba', 0),
(1071, 66, 'DE', 'Southern (Debub)', 0),
(1072, 66, 'BR', 'Gash-Barka (Barentu)', 0),
(1073, 67, 'HA', 'Harjumaa (Tallinn)', 0),
(1074, 67, 'HI', 'Hiiumaa (Kardla)', 0),
(1075, 67, 'IV', 'Ida-Virumaa (Johvi)', 0),
(1076, 67, 'JA', 'Jarvamaa (Paide)', 0),
(1077, 67, 'JO', 'Jogevamaa (Jogeva)', 0),
(1078, 67, 'LV', 'Laane-Virumaa (Rakvere)', 0),
(1079, 67, 'LA', 'Laanemaa (Haapsalu)', 0),
(1080, 67, 'PA', 'Parnumaa (Parnu)', 0),
(1081, 67, 'PO', 'Polvamaa (Polva)', 0),
(1082, 67, 'RA', 'Raplamaa (Rapla)', 0),
(1083, 67, 'SA', 'Saaremaa (Kuessaare)', 0),
(1084, 67, 'TA', 'Tartumaa (Tartu)', 0),
(1085, 67, 'VA', 'Valgamaa (Valga)', 0),
(1086, 67, 'VI', 'Viljandimaa (Viljandi)', 0),
(1087, 67, 'VO', 'Vorumaa (Voru)', 0),
(1088, 68, 'AF', 'Afar', 0),
(1089, 68, 'AH', 'Amhara', 0),
(1090, 68, 'BG', 'Benishangul-Gumaz', 0),
(1091, 68, 'GB', 'Gambela', 0),
(1092, 68, 'HR', 'Hariai', 0),
(1093, 68, 'OR', 'Oromia', 0),
(1094, 68, 'SM', 'Somali', 0),
(1095, 68, 'SN', 'Southern Nations - Nationalities', 0),
(1096, 68, 'TG', 'Tigray', 0),
(1097, 68, 'AA', 'Addis Ababa', 0),
(1098, 68, 'DD', 'Dire Dawa', 0),
(1099, 70, 'T&Ucirc;R', 'T&ucirc;rshavnar Kommuna', 0),
(1100, 70, 'KLA', 'Klaksv&iacute;k', 0),
(1101, 70, 'RUN', 'Runav&iacute;k', 0),
(1102, 70, 'TV&Oslash;', 'Tv&oslash;royri', 0),
(1103, 70, 'FUG', 'Fuglafj&oslash;r&eth;ur', 0),
(1104, 70, 'SUN', 'Sunda Kommuna', 0),
(1105, 70, 'V&Aacute;G', 'V&aacute;gur', 0),
(1106, 70, 'NES', 'Nes', 0),
(1107, 70, 'VES', 'Vestmanna', 0),
(1108, 70, 'MI&eth;', 'Mi&eth;v&aacute;gur', 0),
(1109, 70, 'S&Oslash;R', 'S&oslash;rv&aacute;gur', 0),
(1110, 70, 'G&Oslash;T', 'G&oslash;tu Kommuna', 0),
(1111, 70, 'SJ&Ucirc;', 'Sj&ucirc;var Kommuna', 0),
(1112, 70, 'LEI', 'Leirv&iacute;k', 0),
(1113, 70, 'SAN', 'Sandav&aacute;gur', 0),
(1114, 70, 'HVA', 'Hvalba', 0),
(1115, 70, 'EI&eth;', 'Ei&eth;i', 0),
(1116, 70, 'KV&Iacute;', 'Kv&iacute;v&iacute;k', 0),
(1117, 70, 'SAN', 'Sandur', 0),
(1118, 70, 'SKO', 'Skopun', 0),
(1119, 70, 'HVA', 'Hvannasund', 0),
(1120, 70, 'SUM', 'Sumba', 0),
(1121, 70, 'VI&eth;', 'Vi&eth;arei&eth;i', 0),
(1122, 70, 'POR', 'Porkeri', 0),
(1123, 70, 'SK&Aacute;', 'Sk&aacute;lav&iacute;k', 0),
(1124, 70, 'KUN', 'Kunoy', 0),
(1125, 70, 'H&Uacute;S', 'H&uacute;sav&iacute;k', 0),
(1126, 70, 'HOV', 'Hov', 0),
(1127, 70, 'F&Aacute;M', 'F&aacute;mjin', 0),
(1128, 70, 'FUN', 'Funningur', 0),
(1129, 70, 'H&Uacute;S', 'H&uacute;sar', 0),
(1130, 70, 'SK&Uacute;', 'Sk&uacute;voy', 0),
(1131, 70, 'SV&Iacute;', 'Sv&iacute;noy', 0),
(1132, 70, 'FUG', 'Fugloy', 0),
(1133, 71, 'C', 'Central Division', 0),
(1134, 71, 'N', 'Northern Division', 0),
(1135, 71, 'E', 'Eastern Division', 0),
(1136, 71, 'W', 'Western Division', 0),
(1137, 71, 'R', 'Rotuma', 0),
(1138, 72, 'AL', 'Ahvenanmaan Laani', 0),
(1139, 72, 'ES', 'Etela-Suomen Laani', 0),
(1140, 72, 'IS', 'Ita-Suomen Laani', 0),
(1141, 72, 'LS', 'Lansi-Suomen Laani', 0),
(1142, 72, 'LA', 'Lapin Lanani', 0),
(1143, 72, 'OU', 'Oulun Laani', 0),
(1144, 73, 'AL', 'Alsace', 0),
(1145, 73, 'AQ', 'Aquitaine', 0),
(1146, 73, 'AU', 'Auvergne', 0),
(1147, 73, 'BR', 'Brittany', 0),
(1148, 73, 'BU', 'Burgundy', 0),
(1149, 73, 'CE', 'Center Loire Valley', 0),
(1150, 73, 'CH', 'Champagne', 0),
(1151, 73, 'CO', 'Corse', 0),
(1152, 73, 'FR', 'France Comte', 0),
(1153, 73, 'LA', 'Languedoc Roussillon', 0),
(1154, 73, 'LI', 'Limousin', 0),
(1155, 73, 'LO', 'Lorraine', 0),
(1156, 73, 'MI', 'Midi Pyrenees', 0),
(1157, 73, 'NO', 'Nord Pas de Calais', 0),
(1158, 73, 'NR', 'Normandy', 0),
(1159, 73, 'PA', 'Paris Ill de France', 0),
(1160, 73, 'PI', 'Picardie', 0),
(1161, 73, 'PO', 'Poitou Charente', 0),
(1162, 73, 'PR', 'Provence', 0),
(1163, 73, 'RH', 'Rhone Alps', 0),
(1164, 73, 'RI', 'Riviera', 0),
(1165, 73, 'WE', 'Western Loire Valley', 0),
(1166, 74, 'Et', 'Etranger', 0),
(1167, 74, '1', 'Ain', 0),
(1168, 74, '2', 'Aisne', 0),
(1169, 74, '3', 'Allier', 0),
(1170, 74, '4', 'Alpes de Haute Provence', 0),
(1171, 74, '5', 'Hautes-Alpes', 0),
(1172, 74, '6', 'Alpes Maritimes', 0),
(1173, 74, '7', 'Ard&egrave;che', 0),
(1174, 74, '8', 'Ardennes', 0),
(1175, 74, '9', 'Ari&egrave;ge', 0),
(1176, 74, '10', 'Aube', 0),
(1177, 74, '11', 'Aude', 0),
(1178, 74, '12', 'Aveyron', 0),
(1179, 74, '13', 'Bouches du Rh&ocirc;ne', 0),
(1180, 74, '14', 'Calvados', 0),
(1181, 74, '15', 'Cantal', 0),
(1182, 74, '16', 'Charente', 0),
(1183, 74, '17', 'Charente Maritime', 0),
(1184, 74, '18', 'Cher', 0),
(1185, 74, '19', 'Corr&egrave;ze', 0),
(1186, 74, '2A', 'Corse du Sud', 0),
(1187, 74, '2B', 'Haute Corse', 0),
(1188, 74, '21', 'C&ocirc;te d&apos;or', 0),
(1189, 74, '22', 'C&ocirc;tes d&apos;Armor', 0),
(1190, 74, '23', 'Creuse', 0),
(1191, 74, '24', 'Dordogne', 0),
(1192, 74, '25', 'Doubs', 0),
(1193, 74, '26', 'Dr&ocirc;me', 0),
(1194, 74, '27', 'Eure', 0),
(1195, 74, '28', 'Eure et Loir', 0),
(1196, 74, '29', 'Finist&egrave;re', 0),
(1197, 74, '30', 'Gard', 0),
(1198, 74, '31', 'Haute Garonne', 0),
(1199, 74, '32', 'Gers', 0),
(1200, 74, '33', 'Gironde', 0),
(1201, 74, '34', 'H&eacute;rault', 0),
(1202, 74, '35', 'Ille et Vilaine', 0),
(1203, 74, '36', 'Indre', 0),
(1204, 74, '37', 'Indre et Loire', 0),
(1205, 74, '38', 'Is&eacute;re', 0),
(1206, 74, '39', 'Jura', 0),
(1207, 74, '40', 'Landes', 0),
(1208, 74, '41', 'Loir et Cher', 0),
(1209, 74, '42', 'Loire', 0),
(1210, 74, '43', 'Haute Loire', 0),
(1211, 74, '44', 'Loire Atlantique', 0),
(1212, 74, '45', 'Loiret', 0),
(1213, 74, '46', 'Lot', 0),
(1214, 74, '47', 'Lot et Garonne', 0),
(1215, 74, '48', 'Loz&egrave;re', 0),
(1216, 74, '49', 'Maine et Loire', 0),
(1217, 74, '50', 'Manche', 0),
(1218, 74, '51', 'Marne', 0),
(1219, 74, '52', 'Haute Marne', 0),
(1220, 74, '53', 'Mayenne', 0),
(1221, 74, '54', 'Meurthe et Moselle', 0),
(1222, 74, '55', 'Meuse', 0),
(1223, 74, '56', 'Morbihan', 0),
(1224, 74, '57', 'Moselle', 0),
(1225, 74, '58', 'Ni&egrave;vre', 0),
(1226, 74, '59', 'Nord', 0),
(1227, 74, '60', 'Oise', 0),
(1228, 74, '61', 'Orne', 0),
(1229, 74, '62', 'Pas de Calais', 0),
(1230, 74, '63', 'Puy de D&ocirc;me', 0),
(1231, 74, '64', 'Pyr&eacute;n&eacute;es Atlantiqu', 0),
(1232, 74, '65', 'Hautes Pyr&eacute;n&eacute;es', 0),
(1233, 74, '66', 'Pyr&eacute;n&eacute;es Orientale', 0),
(1234, 74, '67', 'Bas Rhin', 0),
(1235, 74, '68', 'Haut Rhin', 0),
(1236, 74, '69', 'Rh&ocirc;ne', 0),
(1237, 74, '70', 'Haute Sa&ocirc;ne', 0),
(1238, 74, '71', 'Sa&ocirc;ne et Loire', 0),
(1239, 74, '72', 'Sarthe', 0),
(1240, 74, '73', 'Savoie', 0),
(1241, 74, '74', 'Haute Savoie', 0),
(1242, 74, '75', 'Paris', 0),
(1243, 74, '76', 'Seine Maritime', 0),
(1244, 74, '77', 'Seine et Marne', 0),
(1245, 74, '78', 'Yvelines', 0),
(1246, 74, '79', 'Deux S&egrave;vres', 0),
(1247, 74, '80', 'Somme', 0),
(1248, 74, '81', 'Tarn', 0),
(1249, 74, '82', 'Tarn et Garonne', 0),
(1250, 74, '83', 'Var', 0),
(1251, 74, '84', 'Vaucluse', 0),
(1252, 74, '85', 'Vend&eacute;e', 0),
(1253, 74, '86', 'Vienne', 0),
(1254, 74, '87', 'Haute Vienne', 0),
(1255, 74, '88', 'Vosges', 0),
(1256, 74, '89', 'Yonne', 0),
(1257, 74, '90', 'Territoire de Belfort', 0),
(1258, 74, '91', 'Essonne', 0),
(1259, 74, '92', 'Hauts de Seine', 0),
(1260, 74, '93', 'Seine St-Denis', 0),
(1261, 74, '94', 'Val de Marne', 0),
(1262, 74, '95', 'Val d&apos;Oise', 0),
(1263, 75, 'AWA', 'Awala-Yalimapo', 0),
(1264, 75, 'MAN', 'Mana', 0),
(1265, 75, 'SAI', 'Saint-Laurent-Du-Maroni', 0),
(1266, 75, 'APA', 'Apatou', 0),
(1267, 75, 'GRA', 'Grand-Santi', 0),
(1268, 75, 'PAP', 'Papa&iuml;chton', 0),
(1269, 75, 'SA&Uuml;', 'Sa&uuml;l', 0),
(1270, 75, 'MAR', 'Maripasoula', 0),
(1271, 75, 'CAM', 'Camopi', 0),
(1272, 75, 'SAI', 'Saint-Georges', 0),
(1273, 75, 'OUA', 'Ouanary', 0),
(1274, 75, 'R&Eacute;G', 'R&eacute;gina', 0),
(1275, 75, 'ROU', 'Roura', 0),
(1276, 75, 'SAI', 'Saint-&Eacute;lie', 0),
(1277, 75, 'IRA', 'Iracoubo', 0),
(1278, 75, 'SIN', 'Sinnamary', 0),
(1279, 75, 'KOU', 'Kourou', 0),
(1280, 75, 'MAC', 'Macouria', 0),
(1281, 75, 'MON', 'Montsin&eacute;ry-Tonnegrande', 0),
(1282, 75, 'MAT', 'Matoury', 0),
(1283, 75, 'CAY', 'Cayenne', 0),
(1284, 75, 'REM', 'Remire-Montjoly', 0),
(1285, 76, 'M', 'Archipel des Marquises', 0),
(1286, 76, 'T', 'Archipel des Tuamotu', 0),
(1287, 76, 'I', 'Archipel des Tubuai', 0),
(1288, 76, 'V', 'Iles du Vent', 0),
(1289, 76, 'S', 'Iles Sous-le-Vent', 0),
(1290, 77, 'C', 'Iles Crozet', 0),
(1291, 77, 'K', 'Iles Kerguelen', 0),
(1292, 77, 'A', 'Ile Amsterdam', 0),
(1293, 77, 'P', 'Ile Saint-Paul', 0),
(1294, 77, 'D', 'Adelie Land', 0),
(1295, 78, 'ES', 'Estuaire', 0),
(1296, 78, 'HO', 'Haut-Ogooue', 0),
(1297, 78, 'MO', 'Moyen-Ogooue', 0),
(1298, 78, 'NG', 'Ngounie', 0),
(1299, 78, 'NY', 'Nyanga', 0),
(1300, 78, 'OI', 'Ogooue-Ivindo', 0),
(1301, 78, 'OL', 'Ogooue-Lolo', 0),
(1302, 78, 'OM', 'Ogooue-Maritime', 0),
(1303, 78, 'WN', 'Woleu-Ntem', 0),
(1304, 79, 'BJ', 'Banjul', 0),
(1305, 79, 'BS', 'Basse', 0),
(1306, 79, 'BR', 'Brikama', 0),
(1307, 79, 'JA', 'Janjangbure', 0),
(1308, 79, 'KA', 'Kanifeng', 0),
(1309, 79, 'KE', 'Kerewan', 0),
(1310, 79, 'KU', 'Kuntaur', 0),
(1311, 79, 'MA', 'Mansakonko', 0),
(1312, 79, 'LR', 'Lower River', 0),
(1313, 79, 'CR', 'Central River', 0),
(1314, 79, 'NB', 'North Bank', 0),
(1315, 79, 'UR', 'Upper River', 0),
(1316, 79, 'WE', 'Western', 0),
(1317, 80, 'AB', 'Abkhazia', 0),
(1318, 80, 'AJ', 'Ajaria', 0),
(1319, 80, 'TB', 'Tbilisi', 0),
(1320, 80, 'GU', 'Guria', 0),
(1321, 80, 'IM', 'Imereti', 0),
(1322, 80, 'KA', 'Kakheti', 0),
(1323, 80, 'KK', 'Kvemo Kartli', 0),
(1324, 80, 'MM', 'Mtskheta-Mtianeti', 0),
(1325, 80, 'RL', 'Racha Lechkhumi and Kvemo Svanet', 0),
(1326, 80, 'SZ', 'Samegrelo-Zemo Svaneti', 0),
(1327, 80, 'SJ', 'Samtskhe-Javakheti', 0),
(1328, 80, 'SK', 'Shida Kartli', 0),
(1329, 81, 'BAW', 'Baden-Württemberg', 0),
(1330, 81, 'BAY', 'Bayern', 0),
(1331, 81, 'BER', 'Berlin', 0),
(1332, 81, 'BRG', 'Brandenburg', 0),
(1333, 81, 'BRE', 'Bremen', 0),
(1334, 81, 'HAM', 'Hamburg', 0),
(1335, 81, 'HES', 'Hessen', 0),
(1336, 81, 'MEC', 'Mecklenburg-Vorpommern', 0),
(1337, 81, 'NDS', 'Niedersachsen', 0),
(1338, 81, 'NRW', 'Nordrhein-Westfalen', 0),
(1339, 81, 'RHE', 'Rheinland-Pfalz', 0),
(1340, 81, 'SAR', 'Saarland', 0),
(1341, 81, 'SAS', 'Sachsen', 0),
(1342, 81, 'SAC', 'Sachsen-Anhalt', 0),
(1343, 81, 'SCN', 'Schleswig-Holstein', 0),
(1344, 81, 'THE', 'Th&uuml;ringen', 0),
(1345, 82, 'AS', 'Ashanti Region', 0),
(1346, 82, 'BA', 'Brong-Ahafo Region', 0),
(1347, 82, 'CE', 'Central Region', 0),
(1348, 82, 'EA', 'Eastern Region', 0),
(1349, 82, 'GA', 'Greater Accra Region', 0),
(1350, 82, 'NO', 'Northern Region', 0),
(1351, 82, 'UE', 'Upper East Region', 0),
(1352, 82, 'UW', 'Upper West Region', 0),
(1353, 82, 'VO', 'Volta Region', 0),
(1354, 82, 'WE', 'Western Region', 0),
(1355, 83, 'EAS', 'East Side', 0),
(1356, 83, 'NOR', 'North District', 0),
(1357, 83, 'REC', 'Reclamation Areas', 0),
(1358, 83, 'SAN', 'Sandpits Area', 0),
(1359, 83, 'SOU', 'South District', 0),
(1360, 83, 'TOW', 'Town Area', 0),
(1361, 83, 'UPP', 'Upper Town', 0),
(1362, 83, 'OTH', 'Other', 0),
(1363, 84, 'AT', 'Attica', 0),
(1364, 84, 'CN', 'Central Greece', 0),
(1365, 84, 'CM', 'Central Macedonia', 0),
(1366, 84, 'CR', 'Crete', 0),
(1367, 84, 'EM', 'East Macedonia and Thrace', 0),
(1368, 84, 'EP', 'Epirus', 0),
(1369, 84, 'II', 'Ionian Islands', 0),
(1370, 84, 'NA', 'North Aegean', 0),
(1371, 84, 'PP', 'Peloponnesos', 0),
(1372, 84, 'SA', 'South Aegean', 0),
(1373, 84, 'TH', 'Thessaly', 0),
(1374, 84, 'WG', 'West Greece', 0),
(1375, 84, 'WM', 'West Macedonia', 0),
(1376, 85, 'A', 'Avannaa', 0),
(1377, 85, 'T', 'Tunu', 0),
(1378, 85, 'K', 'Kitaa', 0),
(1379, 86, 'A', 'Saint Andrew', 0),
(1380, 86, 'D', 'Saint David', 0),
(1381, 86, 'G', 'Saint George', 0),
(1382, 86, 'J', 'Saint John', 0),
(1383, 86, 'M', 'Saint Mark', 0),
(1384, 86, 'P', 'Saint Patrick', 0),
(1385, 86, 'C', 'Carriacou', 0),
(1386, 86, 'Q', 'Petit Martinique', 0),
(1387, 87, 'ARR', 'Arrondissements Of The Guadeloup', 0),
(1388, 87, 'CAN', 'Cantons Of The Guadeloupe Depart', 0),
(1389, 87, 'COM', 'Communes Of The Guadeloupe Depar', 0),
(1390, 88, 'AGA', 'Agana Heights', 0),
(1391, 88, 'AGA', 'Agat', 0),
(1392, 88, 'ASA', 'Asan Maina', 0),
(1393, 88, 'BAR', 'Barrigada', 0),
(1394, 88, 'CHA', 'Chalan Pago Ordot', 0),
(1395, 88, 'DED', 'Dededo', 0),
(1396, 88, 'HAG', 'Hag&aring;t&ntilde;a', 0),
(1397, 88, 'INA', 'Inarajan', 0),
(1398, 88, 'MAN', 'Mangilao', 0),
(1399, 88, 'MER', 'Merizo', 0),
(1400, 88, 'MON', 'Mongmong Toto Maite', 0),
(1401, 88, 'PIT', 'Piti', 0),
(1402, 88, 'SAN', 'Santa Rita', 0),
(1403, 88, 'SIN', 'Sinajana', 0),
(1404, 88, 'TAL', 'Talofofo', 0),
(1405, 88, 'TAM', 'Tamuning', 0),
(1406, 88, 'UMA', 'Umatac', 0),
(1407, 88, 'YIG', 'Yigo', 0),
(1408, 88, 'YON', 'Yona', 0),
(1409, 89, 'AV', 'Alta Verapaz', 0),
(1410, 89, 'BV', 'Baja Verapaz', 0),
(1411, 89, 'CM', 'Chimaltenango', 0),
(1412, 89, 'CQ', 'Chiquimula', 0),
(1413, 89, 'PE', 'El Peten', 0),
(1414, 89, 'PR', 'El Progreso', 0),
(1415, 89, 'QC', 'El Quiche', 0),
(1416, 89, 'ES', 'Escuintla', 0),
(1417, 89, 'GU', 'Guatemala', 0),
(1418, 89, 'HU', 'Huehuetenango', 0),
(1419, 89, 'IZ', 'Izabal', 0),
(1420, 89, 'JA', 'Jalapa', 0),
(1421, 89, 'JU', 'Jutiapa', 0),
(1422, 89, 'QZ', 'Quetzaltenango', 0),
(1423, 89, 'RE', 'Retalhuleu', 0),
(1424, 89, 'ST', 'Sacatepequez', 0),
(1425, 89, 'SM', 'San Marcos', 0),
(1426, 89, 'SR', 'Santa Rosa', 0),
(1427, 89, 'SO', 'Solola', 0),
(1428, 89, 'SU', 'Suchitepequez', 0),
(1429, 89, 'TO', 'Totonicapan', 0),
(1430, 89, 'ZA', 'Zacapa', 0),
(1431, 90, 'CNK', 'Conakry', 0),
(1432, 90, 'BYL', 'Beyla', 0),
(1433, 90, 'BFA', 'Boffa', 0),
(1434, 90, 'BOK', 'Boke', 0),
(1435, 90, 'COY', 'Coyah', 0),
(1436, 90, 'DBL', 'Dabola', 0),
(1437, 90, 'DLB', 'Dalaba', 0),
(1438, 90, 'DGR', 'Dinguiraye', 0),
(1439, 90, 'DBR', 'Dubreka', 0),
(1440, 90, 'FRN', 'Faranah', 0),
(1441, 90, 'FRC', 'Forecariah', 0),
(1442, 90, 'FRI', 'Fria', 0),
(1443, 90, 'GAO', 'Gaoual', 0),
(1444, 90, 'GCD', 'Gueckedou', 0),
(1445, 90, 'KNK', 'Kankan', 0),
(1446, 90, 'KRN', 'Kerouane', 0),
(1447, 90, 'KND', 'Kindia', 0),
(1448, 90, 'KSD', 'Kissidougou', 0),
(1449, 90, 'KBA', 'Koubia', 0),
(1450, 90, 'KDA', 'Koundara', 0),
(1451, 90, 'KRA', 'Kouroussa', 0),
(1452, 90, 'LAB', 'Labe', 0),
(1453, 90, 'LLM', 'Lelouma', 0),
(1454, 90, 'LOL', 'Lola', 0),
(1455, 90, 'MCT', 'Macenta', 0),
(1456, 90, 'MAL', 'Mali', 0),
(1457, 90, 'MAM', 'Mamou', 0),
(1458, 90, 'MAN', 'Mandiana', 0),
(1459, 90, 'NZR', 'Nzerekore', 0),
(1460, 90, 'PIT', 'Pita', 0),
(1461, 90, 'SIG', 'Siguiri', 0),
(1462, 90, 'TLM', 'Telimele', 0),
(1463, 90, 'TOG', 'Tougue', 0),
(1464, 90, 'YOM', 'Yomou', 0),
(1465, 91, 'BF', 'Bafata Region', 0),
(1466, 91, 'BB', 'Biombo Region', 0),
(1467, 91, 'BS', 'Bissau Region', 0),
(1468, 91, 'BL', 'Bolama Region', 0),
(1469, 91, 'CA', 'Cacheu Region', 0),
(1470, 91, 'GA', 'Gabu Region', 0),
(1471, 91, 'OI', 'Oio Region', 0),
(1472, 91, 'QU', 'Quinara Region', 0),
(1473, 91, 'TO', 'Tombali Region', 0),
(1474, 92, 'BW', 'Barima-Waini', 0),
(1475, 92, 'CM', 'Cuyuni-Mazaruni', 0),
(1476, 92, 'DM', 'Demerara-Mahaica', 0),
(1477, 92, 'EC', 'East Berbice-Corentyne', 0),
(1478, 92, 'EW', 'Essequibo Islands-West Demerara', 0),
(1479, 92, 'MB', 'Mahaica-Berbice', 0),
(1480, 92, 'PM', 'Pomeroon-Supenaam', 0),
(1481, 92, 'PI', 'Potaro-Siparuni', 0),
(1482, 92, 'UD', 'Upper Demerara-Berbice', 0),
(1483, 92, 'UT', 'Upper Takutu-Upper Essequibo', 0),
(1484, 93, 'AR', 'Artibonite', 0),
(1485, 93, 'CE', 'Centre', 0),
(1486, 93, 'GA', 'Grand&apos;Anse', 0),
(1487, 93, 'ND', 'Nord', 0),
(1488, 93, 'NE', 'Nord-Est', 0),
(1489, 93, 'NO', 'Nord-Ouest', 0),
(1490, 93, 'OU', 'Ouest', 0),
(1491, 93, 'SD', 'Sud', 0),
(1492, 93, 'SE', 'Sud-Est', 0),
(1493, 94, 'F', 'Flat Island', 0),
(1494, 94, 'M', 'McDonald Island', 0),
(1495, 94, 'S', 'Shag Island', 0),
(1496, 94, 'H', 'Heard Island', 0),
(1497, 95, 'AT', 'Atlantida', 0),
(1498, 95, 'CH', 'Choluteca', 0),
(1499, 95, 'CL', 'Colon', 0),
(1500, 95, 'CM', 'Comayagua', 0),
(1501, 95, 'CP', 'Copan', 0),
(1502, 95, 'CR', 'Cortes', 0),
(1503, 95, 'PA', 'El Paraiso', 0),
(1504, 95, 'FM', 'Francisco Morazan', 0),
(1505, 95, 'GD', 'Gracias a Dios', 0),
(1506, 95, 'IN', 'Intibuca', 0),
(1507, 95, 'IB', 'Islas de la Bahia (Bay Islands)', 0),
(1508, 95, 'PZ', 'La Paz', 0),
(1509, 95, 'LE', 'Lempira', 0),
(1510, 95, 'OC', 'Ocotepeque', 0),
(1511, 95, 'OL', 'Olancho', 0),
(1512, 95, 'SB', 'Santa Barbara', 0),
(1513, 95, 'VA', 'Valle', 0),
(1514, 95, 'YO', 'Yoro', 0),
(1515, 96, 'HCW', 'Central and Western Hong Kong Is', 0),
(1516, 96, 'HEA', 'Eastern Hong Kong Island', 0),
(1517, 96, 'HSO', 'Southern Hong Kong Island', 0),
(1518, 96, 'HWC', 'Wan Chai Hong Kong Island', 0),
(1519, 96, 'KKC', 'Kowloon City Kowloon', 0),
(1520, 96, 'KKT', 'Kwun Tong Kowloon', 0),
(1521, 96, 'KSS', 'Sham Shui Po Kowloon', 0),
(1522, 96, 'KWT', 'Wong Tai Sin Kowloon', 0),
(1523, 96, 'KYT', 'Yau Tsim Mong Kowloon', 0),
(1524, 96, 'NIS', 'Islands New Territories', 0),
(1525, 96, 'NKT', 'Kwai Tsing New Territories', 0),
(1526, 96, 'NNO', 'North New Territories', 0),
(1527, 96, 'NSK', 'Sai Kung New Territories', 0),
(1528, 96, 'NST', 'Sha Tin New Territories', 0),
(1529, 96, 'NTP', 'Tai Po New Territories', 0),
(1530, 96, 'NTW', 'Tsuen Wan New Territories', 0);
INSERT INTO `zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`, `placement`) VALUES
(1531, 96, 'NTM', 'Tuen Mun New Territories', 0),
(1532, 96, 'NYL', 'Yuen Long New Territories', 0),
(1533, 97, 'BK', 'Bacs-Kiskun', 0),
(1534, 97, 'BA', 'Baranya', 0),
(1535, 97, 'BE', 'Bekes', 0),
(1536, 97, 'BS', 'Bekescsaba', 0),
(1537, 97, 'BZ', 'Borsod-Abauj-Zemplen', 0),
(1538, 97, 'BU', 'Budapest', 0),
(1539, 97, 'CS', 'Csongrad', 0),
(1540, 97, 'DE', 'Debrecen', 0),
(1541, 97, 'DU', 'Dunaujvaros', 0),
(1542, 97, 'EG', 'Eger', 0),
(1543, 97, 'FE', 'Fejer', 0),
(1544, 97, 'GY', 'Gyor', 0),
(1545, 97, 'GM', 'Gyor-Moson-Sopron', 0),
(1546, 97, 'HB', 'Hajdu-Bihar', 0),
(1547, 97, 'HE', 'Heves', 0),
(1548, 97, 'HO', 'Hodmezovasarhely', 0),
(1549, 97, 'JN', 'Jasz-Nagykun-Szolnok', 0),
(1550, 97, 'KA', 'Kaposvar', 0),
(1551, 97, 'KE', 'Kecskemet', 0),
(1552, 97, 'KO', 'Komarom-Esztergom', 0),
(1553, 97, 'MI', 'Miskolc', 0),
(1554, 97, 'NA', 'Nagykanizsa', 0),
(1555, 97, 'NO', 'Nograd', 0),
(1556, 97, 'NY', 'Nyiregyhaza', 0),
(1557, 97, 'PE', 'Pecs', 0),
(1558, 97, 'PS', 'Pest', 0),
(1559, 97, 'SO', 'Somogy', 0),
(1560, 97, 'SP', 'Sopron', 0),
(1561, 97, 'SS', 'Szabolcs-Szatmar-Bereg', 0),
(1562, 97, 'SZ', 'Szeged', 0),
(1563, 97, 'SE', 'Szekesfehervar', 0),
(1564, 97, 'SL', 'Szolnok', 0),
(1565, 97, 'SM', 'Szombathely', 0),
(1566, 97, 'TA', 'Tatabanya', 0),
(1567, 97, 'TO', 'Tolna', 0),
(1568, 97, 'VA', 'Vas', 0),
(1569, 97, 'VE', 'Veszprem', 0),
(1570, 97, 'ZA', 'Zala', 0),
(1571, 97, 'ZZ', 'Zalaegerszeg', 0),
(1572, 98, 'AL', 'Austurland', 0),
(1573, 98, 'HF', 'Hofuoborgarsvaeoi', 0),
(1574, 98, 'NE', 'Norourland eystra', 0),
(1575, 98, 'NV', 'Norourland vestra', 0),
(1576, 98, 'SL', 'Suourland', 0),
(1577, 98, 'SN', 'Suournes', 0),
(1578, 98, 'VF', 'Vestfiroir', 0),
(1579, 98, 'VL', 'Vesturland', 0),
(1580, 99, 'AN', 'Andaman and Nicobar Islands', 0),
(1581, 99, 'AP', 'Andhra Pradesh', 0),
(1582, 99, 'AR', 'Arunachal Pradesh', 0),
(1583, 99, 'AS', 'Assam', 0),
(1584, 99, 'BI', 'Bihar', 0),
(1585, 99, 'CH', 'Chandigarh', 0),
(1586, 99, 'DA', 'Dadra and Nagar Haveli', 0),
(1587, 99, 'DM', 'Daman and Diu', 0),
(1588, 99, 'DE', 'Delhi', 0),
(1589, 99, 'GO', 'Goa', 0),
(1590, 99, 'GU', 'Gujarat', 0),
(1591, 99, 'HA', 'Haryana', 0),
(1592, 99, 'HP', 'Himachal Pradesh', 0),
(1593, 99, 'JA', 'Jammu and Kashmir', 0),
(1594, 99, 'KA', 'Karnataka', 0),
(1595, 99, 'KE', 'Kerala', 0),
(1596, 99, 'LI', 'Lakshadweep Islands', 0),
(1597, 99, 'MP', 'Madhya Pradesh', 0),
(1598, 99, 'MA', 'Maharashtra', 0),
(1599, 99, 'MN', 'Manipur', 0),
(1600, 99, 'ME', 'Meghalaya', 0),
(1601, 99, 'MI', 'Mizoram', 0),
(1602, 99, 'NA', 'Nagaland', 0),
(1603, 99, 'OR', 'Orissa', 0),
(1604, 99, 'PO', 'Pondicherry', 0),
(1605, 99, 'PU', 'Punjab', 0),
(1606, 99, 'RA', 'Rajasthan', 0),
(1607, 99, 'SI', 'Sikkim', 0),
(1608, 99, 'TN', 'Tamil Nadu', 0),
(1609, 99, 'TR', 'Tripura', 0),
(1610, 99, 'UP', 'Uttar Pradesh', 0),
(1611, 99, 'WB', 'West Bengal', 0),
(1612, 100, 'DA', 'Daista Aceh', 0),
(1613, 100, 'SU', 'Sumatera Utara', 0),
(1614, 100, 'SB', 'Sumatera Barat', 0),
(1615, 100, 'SI', 'Riau', 0),
(1616, 100, 'JA', 'Jambi', 0),
(1617, 100, 'SS', 'Sumatera Selatan', 0),
(1618, 100, 'BE', 'Bengkulu', 0),
(1619, 100, 'LA', 'Lampung', 0),
(1620, 100, 'JK', 'Dki Jakarta', 0),
(1621, 100, 'JB', 'Jawa Barat', 0),
(1622, 100, 'JT', 'Jawa Tengah', 0),
(1623, 100, 'DY', 'Daista Yogyakarta', 0),
(1624, 100, 'JT', 'Jawa Timur', 0),
(1625, 100, 'KB', 'Kalimantan Barat', 0),
(1626, 100, 'KT', 'Kalimantan Tengah', 0),
(1627, 100, 'KI', 'Kalimantan Timur', 0),
(1628, 100, 'KS', 'Kalimantan Selatan', 0),
(1629, 100, 'BA', 'Bali', 0),
(1630, 100, 'NB', 'Nusa Tenggara Barat', 0),
(1631, 100, 'NT', 'Nusa Tenggara Timur', 0),
(1632, 100, 'SN', 'Sulawesi Selatan', 0),
(1633, 100, 'ST', 'Sulawesi Tengah', 0),
(1634, 100, 'SA', 'Sulawesi Utara', 0),
(1635, 100, 'SG', 'Sulawesi Tenggara', 0),
(1636, 100, 'MA', 'Maluku', 0),
(1637, 100, 'MU', 'Maluku Utara', 0),
(1638, 100, 'IJ', 'Irian Jaya Timur', 0),
(1639, 100, 'IT', 'Irian Jaya Tengah', 0),
(1640, 100, 'IB', 'Irian Jawa Barat', 0),
(1641, 100, 'BT', 'Banten', 0),
(1642, 100, 'BB', 'Bangka Belitung', 0),
(1643, 100, 'GO', 'Gorontalo', 0),
(1644, 101, 'TEH', 'Tehran', 0),
(1645, 101, 'QOM', 'Qom', 0),
(1646, 101, 'MKZ', 'Markazi', 0),
(1647, 101, 'QAZ', 'Qazvin', 0),
(1648, 101, 'GIL', 'Gilan', 0),
(1649, 101, 'ARD', 'Ardabil', 0),
(1650, 101, 'ZAN', 'Zanjan', 0),
(1651, 101, 'EAZ', 'East Azarbaijan', 0),
(1652, 101, 'WEZ', 'West Azarbaijan', 0),
(1653, 101, 'KRD', 'Kurdistan', 0),
(1654, 101, 'HMD', 'Hamadan', 0),
(1655, 101, 'KRM', 'Kermanshah', 0),
(1656, 101, 'ILM', 'Ilam', 0),
(1657, 101, 'LRS', 'Lorestan', 0),
(1658, 101, 'KZT', 'Khuzestan', 0),
(1659, 101, 'CMB', 'Chahar Mahaal and Bakhtiari', 0),
(1660, 101, 'KBA', 'Kohkiluyeh and Buyer Ahmad', 0),
(1661, 101, 'BSH', 'Bushehr', 0),
(1662, 101, 'FAR', 'Fars', 0),
(1663, 101, 'HRM', 'Hormozgan', 0),
(1664, 101, 'SBL', 'Sistan and Baluchistan', 0),
(1665, 101, 'KRB', 'Kerman', 0),
(1666, 101, 'YZD', 'Yazd', 0),
(1667, 101, 'EFH', 'Esfahan', 0),
(1668, 101, 'SMN', 'Semnan', 0),
(1669, 101, 'MZD', 'Mazandaran', 0),
(1670, 101, 'GLS', 'Golestan', 0),
(1671, 101, 'NKH', 'North Khorasan', 0),
(1672, 101, 'RKH', 'Razavi Khorasan', 0),
(1673, 101, 'SKH', 'South Khorasan', 0),
(1674, 102, 'BD', 'Baghdad', 0),
(1675, 102, 'SD', 'Salah ad Din', 0),
(1676, 102, 'DY', 'Diyala', 0),
(1677, 102, 'WS', 'Wasit', 0),
(1678, 102, 'MY', 'Maysan', 0),
(1679, 102, 'BA', 'Al Basrah', 0),
(1680, 102, 'DQ', 'Dhi Qar', 0),
(1681, 102, 'MU', 'Al Muthanna', 0),
(1682, 102, 'QA', 'Al Qadisyah', 0),
(1683, 102, 'BB', 'Babil', 0),
(1684, 102, 'KB', 'Al Karbala', 0),
(1685, 102, 'NJ', 'An Najaf', 0),
(1686, 102, 'AB', 'Al Anbar', 0),
(1687, 102, 'NN', 'Ninawa', 0),
(1688, 102, 'DH', 'Dahuk', 0),
(1689, 102, 'AL', 'Arbil', 0),
(1690, 102, 'TM', 'At Ta&apos; &apos;mim', 0),
(1691, 102, 'SL', 'As Sulaymaniyah', 0),
(1692, 103, 'CA', 'Carlow', 0),
(1693, 103, 'CV', 'Cavan', 0),
(1694, 103, 'CL', 'Clare', 0),
(1695, 103, 'CO', 'Cork', 0),
(1696, 103, 'DO', 'Donegal', 0),
(1697, 103, 'DU', 'Dublin', 0),
(1698, 103, 'GA', 'Galway', 0),
(1699, 103, 'KE', 'Kerry', 0),
(1700, 103, 'KI', 'Kildare', 0),
(1701, 103, 'KL', 'Kilkenny', 0),
(1702, 103, 'LA', 'Laois', 0),
(1703, 103, 'LE', 'Leitrim', 0),
(1704, 103, 'LI', 'Limerick', 0),
(1705, 103, 'LO', 'Longford', 0),
(1706, 103, 'LU', 'Louth', 0),
(1707, 103, 'MA', 'Mayo', 0),
(1708, 103, 'ME', 'Meath', 0),
(1709, 103, 'MO', 'Monaghan', 0),
(1710, 103, 'OF', 'Offaly', 0),
(1711, 103, 'RO', 'Roscommon', 0),
(1712, 103, 'SL', 'Sligo', 0),
(1713, 103, 'TI', 'Tipperary', 0),
(1714, 103, 'WA', 'Waterford', 0),
(1715, 103, 'WE', 'Westmeath', 0),
(1716, 103, 'WX', 'Wexford', 0),
(1717, 103, 'WI', 'Wicklow', 0),
(1718, 104, 'BS', 'Be&apos; &apos;er Sheva', 0),
(1719, 104, 'BH', 'Bika&apos;at Hayarden', 0),
(1720, 104, 'EA', 'Eilat and Arava', 0),
(1721, 104, 'GA', 'Galil', 0),
(1722, 104, 'HA', 'Haifa', 0),
(1723, 104, 'JM', 'Jehuda Mountains', 0),
(1724, 104, 'JE', 'Jerusalem', 0),
(1725, 104, 'NE', 'Negev', 0),
(1726, 104, 'SE', 'Semaria', 0),
(1727, 104, 'SH', 'Sharon', 0),
(1728, 104, 'TA', 'Tel Aviv (Gosh Dan)', 0),
(1729, 105, 'AG', 'Agrigento', 0),
(1730, 105, 'AL', 'Alessandria', 0),
(1731, 105, 'AN', 'Ancona', 0),
(1732, 105, 'AO', 'Aosta', 0),
(1733, 105, 'AR', 'Arezzo', 0),
(1734, 105, 'AP', 'Ascoli Piceno', 0),
(1735, 105, 'AT', 'Asti', 0),
(1736, 105, 'AV', 'Avellino', 0),
(1737, 105, 'BA', 'Bari', 0),
(1738, 105, 'BL', 'Belluno', 0),
(1739, 105, 'BN', 'Benevento', 0),
(1740, 105, 'BG', 'Bergamo', 0),
(1741, 105, 'BI', 'Biella', 0),
(1742, 105, 'BO', 'Bologna', 0),
(1743, 105, 'BZ', 'Bolzano', 0),
(1744, 105, 'BS', 'Brescia', 0),
(1745, 105, 'BR', 'Brindisi', 0),
(1746, 105, 'CA', 'Cagliari', 0),
(1747, 105, 'CL', 'Caltanissetta', 0),
(1748, 105, 'CB', 'Campobasso', 0),
(1749, 105, 'CE', 'Caserta', 0),
(1750, 105, 'CT', 'Catania', 0),
(1751, 105, 'CZ', 'Catanzaro', 0),
(1752, 105, 'CH', 'Chieti', 0),
(1753, 105, 'CO', 'Como', 0),
(1754, 105, 'CS', 'Cosenza', 0),
(1755, 105, 'CR', 'Cremona', 0),
(1756, 105, 'KR', 'Crotone', 0),
(1757, 105, 'CN', 'Cuneo', 0),
(1758, 105, 'EN', 'Enna', 0),
(1759, 105, 'FE', 'Ferrara', 0),
(1760, 105, 'FI', 'Firenze', 0),
(1761, 105, 'FG', 'Foggia', 0),
(1762, 105, 'FO', 'Forl', 0),
(1763, 105, 'FR', 'Frosinone', 0),
(1764, 105, 'GE', 'Genova', 0),
(1765, 105, 'GO', 'Gorizia', 0),
(1766, 105, 'GR', 'Grosseto', 0),
(1767, 105, 'IM', 'Imperia', 0),
(1768, 105, 'IS', 'Isernia', 0),
(1769, 105, 'AQ', 'Aquila', 0),
(1770, 105, 'SP', 'La Spezia', 0),
(1771, 105, 'LT', 'Latina', 0),
(1772, 105, 'LE', 'Lecce', 0),
(1773, 105, 'LC', 'Lecco', 0),
(1774, 105, 'LI', 'Livorno', 0),
(1775, 105, 'LO', 'Lodi', 0),
(1776, 105, 'LU', 'Lucca', 0),
(1777, 105, 'MC', 'Macerata', 0),
(1778, 105, 'MN', 'Mantova', 0),
(1779, 105, 'MS', 'Massa-Carrara', 0),
(1780, 105, 'MT', 'Matera', 0),
(1781, 105, 'ME', 'Messina', 0),
(1782, 105, 'MI', 'Milano', 0),
(1783, 105, 'MO', 'Modena', 0),
(1784, 105, 'NA', 'Napoli', 0),
(1785, 105, 'NO', 'Novara', 0),
(1786, 105, 'NU', 'Nuoro', 0),
(1787, 105, 'OR', 'Oristano', 0),
(1788, 105, 'PD', 'Padova', 0),
(1789, 105, 'PA', 'Palermo', 0),
(1790, 105, 'PR', 'Parma', 0),
(1791, 105, 'PG', 'Perugia', 0),
(1792, 105, 'PV', 'Pavia', 0),
(1793, 105, 'PU', 'Pesaro Urbino', 0),
(1794, 105, 'PE', 'Pescara', 0),
(1795, 105, 'PC', 'Piacenza', 0),
(1796, 105, 'PI', 'Pisa', 0),
(1797, 105, 'PT', 'Pistoia', 0),
(1798, 105, 'PN', 'Pordenone', 0),
(1799, 105, 'PZ', 'Potenza', 0),
(1800, 105, 'PO', 'Prato', 0),
(1801, 105, 'RG', 'Ragusa', 0),
(1802, 105, 'RA', 'Ravenna', 0),
(1803, 105, 'RC', 'Reggio Calabria', 0),
(1804, 105, 'RE', 'Reggio Emilia', 0),
(1805, 105, 'RI', 'Rieti', 0),
(1806, 105, 'RN', 'Rimini', 0),
(1807, 105, 'RM', 'Roma', 0),
(1808, 105, 'RO', 'Rovigo', 0),
(1809, 105, 'SA', 'Salerno', 0),
(1810, 105, 'SS', 'Sassari', 0),
(1811, 105, 'SV', 'Savona', 0),
(1812, 105, 'SI', 'Siena', 0),
(1813, 105, 'SR', 'Siracusa', 0),
(1814, 105, 'SO', 'Sondrio', 0),
(1815, 105, 'TA', 'Taranto', 0),
(1816, 105, 'TE', 'Teramo', 0),
(1817, 105, 'TR', 'Terni', 0),
(1818, 105, 'TO', 'Torino', 0),
(1819, 105, 'TP', 'Trapani', 0),
(1820, 105, 'TN', 'Trento', 0),
(1821, 105, 'TV', 'Treviso', 0),
(1822, 105, 'TS', 'Trieste', 0),
(1823, 105, 'UD', 'Udine', 0),
(1824, 105, 'VA', 'Varese', 0),
(1825, 105, 'VE', 'Venezia', 0),
(1826, 105, 'VB', 'Verbania', 0),
(1827, 105, 'VC', 'Vercelli', 0),
(1828, 105, 'VR', 'Verona', 0),
(1829, 105, 'VV', 'Vibo Valentia', 0),
(1830, 105, 'VI', 'Vicenza', 0),
(1831, 105, 'VT', 'Viterbo', 0),
(1832, 105, 'CI', 'Carbonia-Iglesias', 0),
(1833, 105, 'VS', 'Medio Campidano', 0),
(1834, 105, 'OG', 'Ogliastra', 0),
(1835, 105, 'OT', 'Olbia-Tempio', 0),
(1836, 105, 'MB', 'Monza e Brianza', 0),
(1837, 105, 'FM', 'Fermo', 0),
(1838, 105, 'BT', 'Barletta-Andria-Trani', 0),
(1839, 106, 'CLA', 'Clarendon Parish', 0),
(1840, 106, 'HAN', 'Hanover Parish', 0),
(1841, 106, 'KIN', 'Kingston Parish', 0),
(1842, 106, 'MAN', 'Manchester Parish', 0),
(1843, 106, 'POR', 'Portland Parish', 0),
(1844, 106, 'AND', 'Saint Andrew Parish', 0),
(1845, 106, 'ANN', 'Saint Ann Parish', 0),
(1846, 106, 'CAT', 'Saint Catherine Parish', 0),
(1847, 106, 'ELI', 'Saint Elizabeth Parish', 0),
(1848, 106, 'JAM', 'Saint James Parish', 0),
(1849, 106, 'MAR', 'Saint Mary Parish', 0),
(1850, 106, 'THO', 'Saint Thomas Parish', 0),
(1851, 106, 'TRL', 'Trelawny Parish', 0),
(1852, 106, 'WML', 'Westmoreland Parish', 0),
(1853, 107, 'AI', 'Aichi', 0),
(1854, 107, 'AK', 'Akita', 0),
(1855, 107, 'AO', 'Aomori', 0),
(1856, 107, 'CH', 'Chiba', 0),
(1857, 107, 'EH', 'Ehime', 0),
(1858, 107, 'FK', 'Fukui', 0),
(1859, 107, 'FU', 'Fukuoka', 0),
(1860, 107, 'FS', 'Fukushima', 0),
(1861, 107, 'GI', 'Gifu', 0),
(1862, 107, 'GU', 'Gumma', 0),
(1863, 107, 'HI', 'Hiroshima', 0),
(1864, 107, 'HO', 'Hokkaido', 0),
(1865, 107, 'HY', 'Hyogo', 0),
(1866, 107, 'IB', 'Ibaraki', 0),
(1867, 107, 'IS', 'Ishikawa', 0),
(1868, 107, 'IW', 'Iwate', 0),
(1869, 107, 'KA', 'Kagawa', 0),
(1870, 107, 'KG', 'Kagoshima', 0),
(1871, 107, 'KN', 'Kanagawa', 0),
(1872, 107, 'KO', 'Kochi', 0),
(1873, 107, 'KU', 'Kumamoto', 0),
(1874, 107, 'KY', 'Kyoto', 0),
(1875, 107, 'MI', 'Mie', 0),
(1876, 107, 'MY', 'Miyagi', 0),
(1877, 107, 'MZ', 'Miyazaki', 0),
(1878, 107, 'NA', 'Nagano', 0),
(1879, 107, 'NG', 'Nagasaki', 0),
(1880, 107, 'NR', 'Nara', 0),
(1881, 107, 'NI', 'Niigata', 0),
(1882, 107, 'OI', 'Oita', 0),
(1883, 107, 'OK', 'Okayama', 0),
(1884, 107, 'ON', 'Okinawa', 0),
(1885, 107, 'OS', 'Osaka', 0),
(1886, 107, 'SA', 'Saga', 0),
(1887, 107, 'SI', 'Saitama', 0),
(1888, 107, 'SH', 'Shiga', 0),
(1889, 107, 'SM', 'Shimane', 0),
(1890, 107, 'SZ', 'Shizuoka', 0),
(1891, 107, 'TO', 'Tochigi', 0),
(1892, 107, 'TS', 'Tokushima', 0),
(1893, 107, 'TK', 'Tokyo', 0),
(1894, 107, 'TT', 'Tottori', 0),
(1895, 107, 'TY', 'Toyama', 0),
(1896, 107, 'WA', 'Wakayama', 0),
(1897, 107, 'YA', 'Yamagata', 0),
(1898, 107, 'YM', 'Yamaguchi', 0),
(1899, 107, 'YN', 'Yamanashi', 0),
(1900, 108, 'AM', 'Amman', 0),
(1901, 108, 'AJ', 'Ajlun', 0),
(1902, 108, 'AA', 'Al Aqabah', 0),
(1903, 108, 'AB', 'Al Balqa', 0),
(1904, 108, 'AK', 'Al Karak', 0),
(1905, 108, 'AL', 'Al Mafraq', 0),
(1906, 108, 'AT', 'At Tafilah', 0),
(1907, 108, 'AZ', 'Az Zarqa&apos;', 0),
(1908, 108, 'IR', 'Irbid', 0),
(1909, 108, 'JA', 'Jarash', 0),
(1910, 108, 'MA', 'Ma&apos;an', 0),
(1911, 108, 'MD', 'Madaba', 0),
(1912, 109, 'AL', 'Almaty', 0),
(1913, 109, 'AC', 'Almaty City', 0),
(1914, 109, 'AM', 'Aqmola', 0),
(1915, 109, 'AQ', 'Aqtobe', 0),
(1916, 109, 'AS', 'Astana City', 0),
(1917, 109, 'AT', 'Atyrau', 0),
(1918, 109, 'BA', 'Batys Qazaqstan', 0),
(1919, 109, 'BY', 'Bayqongyr City', 0),
(1920, 109, 'MA', 'Mangghystau', 0),
(1921, 109, 'ON', 'Ongtustik Qazaqstan', 0),
(1922, 109, 'PA', 'Pavlodar', 0),
(1923, 109, 'QA', 'Qaraghandy', 0),
(1924, 109, 'QO', 'Qostanay', 0),
(1925, 109, 'QY', 'Qyzylorda', 0),
(1926, 109, 'SH', 'Shyghys Qazaqstan', 0),
(1927, 109, 'SO', 'Soltustik Qazaqstan', 0),
(1928, 109, 'ZH', 'Zhambyl', 0),
(1929, 110, 'CE', 'Central', 0),
(1930, 110, 'CO', 'Coast', 0),
(1931, 110, 'EA', 'Eastern', 0),
(1932, 110, 'NA', 'Nairobi Area', 0),
(1933, 110, 'NE', 'North Eastern', 0),
(1934, 110, 'NY', 'Nyanza', 0),
(1935, 110, 'RV', 'Rift Valley', 0),
(1936, 110, 'WE', 'Western', 0),
(1937, 111, 'AG', 'Abaiang', 0),
(1938, 111, 'AM', 'Abemama', 0),
(1939, 111, 'AK', 'Aranuka', 0),
(1940, 111, 'AO', 'Arorae', 0),
(1941, 111, 'BA', 'Banaba', 0),
(1942, 111, 'BE', 'Beru', 0),
(1943, 111, 'bT', 'Butaritari', 0),
(1944, 111, 'KA', 'Kanton', 0),
(1945, 111, 'KR', 'Kiritimati', 0),
(1946, 111, 'KU', 'Kuria', 0),
(1947, 111, 'MI', 'Maiana', 0),
(1948, 111, 'MN', 'Makin', 0),
(1949, 111, 'ME', 'Marakei', 0),
(1950, 111, 'NI', 'Nikunau', 0),
(1951, 111, 'NO', 'Nonouti', 0),
(1952, 111, 'ON', 'Onotoa', 0),
(1953, 111, 'TT', 'Tabiteuea', 0),
(1954, 111, 'TR', 'Tabuaeran', 0),
(1955, 111, 'TM', 'Tamana', 0),
(1956, 111, 'TW', 'Tarawa', 0),
(1957, 111, 'TE', 'Teraina', 0),
(1958, 112, 'CHA', 'Chagang-do', 0),
(1959, 112, 'HAB', 'Hamgyong-bukto', 0),
(1960, 112, 'HAN', 'Hamgyong-namdo', 0),
(1961, 112, 'HWB', 'Hwanghae-bukto', 0),
(1962, 112, 'HWN', 'Hwanghae-namdo', 0),
(1963, 112, 'KAN', 'Kangwon-do', 0),
(1964, 112, 'PYB', 'P&apos;yongan-bukto', 0),
(1965, 112, 'PYN', 'P&apos;yongan-namdo', 0),
(1966, 112, 'YAN', 'Ryanggang-do (Yanggang-do)', 0),
(1967, 112, 'NAJ', 'Rason Directly Governed City', 0),
(1968, 112, 'PYO', 'P&apos;yongyang Special City', 0),
(1969, 113, 'CO', 'Ch&apos;ungch&apos;ong-bukto', 0),
(1970, 113, 'CH', 'Ch&apos;ungch&apos;ong-namdo', 0),
(1971, 113, 'CD', 'Cheju-do', 0),
(1972, 113, 'CB', 'Cholla-bukto', 0),
(1973, 113, 'CN', 'Cholla-namdo', 0),
(1974, 113, 'IG', 'Inchon-gwangyoksi', 0),
(1975, 113, 'KA', 'Kangwon-do', 0),
(1976, 113, 'KG', 'Kwangju-gwangyoksi', 0),
(1977, 113, 'KD', 'Kyonggi-do', 0),
(1978, 113, 'KB', 'Kyongsang-bukto', 0),
(1979, 113, 'KN', 'Kyongsang-namdo', 0),
(1980, 113, 'PG', 'Pusan-gwangyoksi', 0),
(1981, 113, 'SO', 'Soul-tukpyolsi', 0),
(1982, 113, 'TA', 'Taegu-gwangyoksi', 0),
(1983, 113, 'TG', 'Taejon-gwangyoksi', 0),
(1984, 114, 'AL', 'Al Asimah', 0),
(1985, 114, 'AA', 'Al Ahmadi', 0),
(1986, 114, 'AF', 'Al Farwaniyah', 0),
(1987, 114, 'AJ', 'Al Jahra&apos;', 0),
(1988, 114, 'HA', 'Hawalli', 0),
(1989, 115, 'GB', 'Bishkek', 0),
(1990, 115, 'B', 'Batken', 0),
(1991, 115, 'C', 'Chu', 0),
(1992, 115, 'J', 'Jalal-Abad', 0),
(1993, 115, 'N', 'Naryn', 0),
(1994, 115, 'O', 'Osh', 0),
(1995, 115, 'T', 'Talas', 0),
(1996, 115, 'Y', 'Ysyk-Kol', 0),
(1997, 116, 'VT', 'Vientiane', 0),
(1998, 116, 'AT', 'Attapu', 0),
(1999, 116, 'BK', 'Bokeo', 0),
(2000, 116, 'BL', 'Bolikhamxai', 0),
(2001, 116, 'CH', 'Champasak', 0),
(2002, 116, 'HO', 'Houaphan', 0),
(2003, 116, 'KH', 'Khammouan', 0),
(2004, 116, 'LM', 'Louang Namtha', 0),
(2005, 116, 'LP', 'Louangphabang', 0),
(2006, 116, 'OU', 'Oudomxai', 0),
(2007, 116, 'PH', 'Phongsali', 0),
(2008, 116, 'SL', 'Salavan', 0),
(2009, 116, 'SV', 'Savannakhet', 0),
(2010, 116, 'VI', 'Vientiane', 0),
(2011, 116, 'XA', 'Xaignabouli', 0),
(2012, 116, 'XE', 'Xekong', 0),
(2013, 116, 'XI', 'Xiangkhoang', 0),
(2014, 116, 'XN', 'Xaisomboun', 0),
(2015, 117, 'AIZ', 'Aizkraukles Rajons', 0),
(2016, 117, 'ALU', 'Aluksnes Rajons', 0),
(2017, 117, 'BAL', 'Balvu Rajons', 0),
(2018, 117, 'BAU', 'Bauskas Rajons', 0),
(2019, 117, 'CES', 'Cesu Rajons', 0),
(2020, 117, 'DGR', 'Daugavpils Rajons', 0),
(2021, 117, 'DOB', 'Dobeles Rajons', 0),
(2022, 117, 'GUL', 'Gulbenes Rajons', 0),
(2023, 117, 'JEK', 'Jekabpils Rajons', 0),
(2024, 117, 'JGR', 'Jelgavas Rajons', 0),
(2025, 117, 'KRA', 'Kraslavas Rajons', 0),
(2026, 117, 'KUL', 'Kuldigas Rajons', 0),
(2027, 117, 'LPR', 'Liepajas Rajons', 0),
(2028, 117, 'LIM', 'Limbazu Rajons', 0),
(2029, 117, 'LUD', 'Ludzas Rajons', 0),
(2030, 117, 'MAD', 'Madonas Rajons', 0),
(2031, 117, 'OGR', 'Ogres Rajons', 0),
(2032, 117, 'PRE', 'Preilu Rajons', 0),
(2033, 117, 'RZR', 'Rezeknes Rajons', 0),
(2034, 117, 'RGR', 'Rigas Rajons', 0),
(2035, 117, 'SAL', 'Saldus Rajons', 0),
(2036, 117, 'TAL', 'Talsu Rajons', 0),
(2037, 117, 'TUK', 'Tukuma Rajons', 0),
(2038, 117, 'VLK', 'Valkas Rajons', 0),
(2039, 117, 'VLM', 'Valmieras Rajons', 0),
(2040, 117, 'VSR', 'Ventspils Rajons', 0),
(2041, 117, 'DGV', 'Daugavpils', 0),
(2042, 117, 'JGV', 'Jelgava', 0),
(2043, 117, 'JUR', 'Jurmala', 0),
(2044, 117, 'LPK', 'Liepaja', 0),
(2045, 117, 'RZK', 'Rezekne', 0),
(2046, 117, 'RGA', 'Riga', 0),
(2047, 117, 'VSL', 'Ventspils', 0),
(2048, 118, 'BIN', 'Bint Jbeil', 0),
(2049, 118, 'HAS', 'Hasbaya', 0),
(2050, 118, 'MAR', 'Marjeyoun', 0),
(2051, 118, 'NAB', 'Nabatieh', 0),
(2052, 118, 'BAA', 'Baalbek', 0),
(2053, 118, 'HER', 'Hermel', 0),
(2054, 118, 'RAS', 'Rashaya', 0),
(2055, 118, 'WES', 'Western Beqaa', 0),
(2056, 118, 'ZAH', 'Zahle', 0),
(2057, 118, 'AKK', 'Akkar', 0),
(2058, 118, 'BAT', 'Batroun', 0),
(2059, 118, 'BSH', 'Bsharri', 0),
(2060, 118, 'KOU', 'Koura', 0),
(2061, 118, 'MIN', 'Miniyeh-Danniyeh', 0),
(2062, 118, 'TRI', 'Tripoli', 0),
(2063, 118, 'ZGH', 'Zgharta', 0),
(2064, 118, 'ALE', 'Aley', 0),
(2065, 118, 'BAA', 'Baabda', 0),
(2066, 118, 'BYB', 'Byblos', 0),
(2067, 118, 'CHO', 'Chouf', 0),
(2068, 118, 'KES', 'Kesrwan', 0),
(2069, 118, 'MAT', 'Matn', 0),
(2070, 118, 'JEZ', 'Jezzine', 0),
(2071, 118, 'SID', 'Sidon', 0),
(2072, 118, 'TYR', 'Tyre', 0),
(2073, 119, 'BE', 'Berea', 0),
(2074, 119, 'BB', 'Butha-Buthe', 0),
(2075, 119, 'LE', 'Leribe', 0),
(2076, 119, 'MF', 'Mafeteng', 0),
(2077, 119, 'MS', 'Maseru', 0),
(2078, 119, 'MH', 'Mohale&apos;s Hoek', 0),
(2079, 119, 'MK', 'Mokhotlong', 0),
(2080, 119, 'QN', 'Qacha&apos;s Nek', 0),
(2081, 119, 'QT', 'Quthing', 0),
(2082, 119, 'TT', 'Thaba-Tseka', 0),
(2083, 120, 'BI', 'Bomi', 0),
(2084, 120, 'BG', 'Bong', 0),
(2085, 120, 'GB', 'Grand Bassa', 0),
(2086, 120, 'CM', 'Grand Cape Mount', 0),
(2087, 120, 'GG', 'Grand Gedeh', 0),
(2088, 120, 'GK', 'Grand Kru', 0),
(2089, 120, 'LO', 'Lofa', 0),
(2090, 120, 'MG', 'Margibi', 0),
(2091, 120, 'ML', 'Maryland', 0),
(2092, 120, 'MS', 'Montserrado', 0),
(2093, 120, 'NB', 'Nimba', 0),
(2094, 120, 'RC', 'River Cess', 0),
(2095, 120, 'SN', 'Sinoe', 0),
(2096, 121, 'AJ', 'Ajdabiya', 0),
(2097, 121, 'AZ', 'Al Aziziyah', 0),
(2098, 121, 'FA', 'Al Fatih', 0),
(2099, 121, 'JA', 'Al Jabal al Akhdar', 0),
(2100, 121, 'JU', 'Al Jufrah', 0),
(2101, 121, 'KH', 'Al Khums', 0),
(2102, 121, 'KU', 'Al Kufrah', 0),
(2103, 121, 'NK', 'An Nuqat al Khams', 0),
(2104, 121, 'AS', 'Ash Shati', 0),
(2105, 121, 'AW', 'Awbari', 0),
(2106, 121, 'ZA', 'Az Zawiyah', 0),
(2107, 121, 'BA', 'Banghazi', 0),
(2108, 121, 'DA', 'Darnah', 0),
(2109, 121, 'GD', 'Ghadamis', 0),
(2110, 121, 'GY', 'Gharyan', 0),
(2111, 121, 'MI', 'Misratah', 0),
(2112, 121, 'MZ', 'Murzuq', 0),
(2113, 121, 'SB', 'Sabha', 0),
(2114, 121, 'SW', 'Sawfajjin', 0),
(2115, 121, 'SU', 'Surt', 0),
(2116, 121, 'TL', 'Tarabulus (Tripoli)', 0),
(2117, 121, 'TH', 'Tarhunah', 0),
(2118, 121, 'TU', 'Tubruq', 0),
(2119, 121, 'YA', 'Yafran', 0),
(2120, 121, 'ZL', 'Zlitan', 0),
(2121, 122, 'V', 'Vaduz', 0),
(2122, 122, 'A', 'Schaan', 0),
(2123, 122, 'B', 'Balzers', 0),
(2124, 122, 'N', 'Triesen', 0),
(2125, 122, 'E', 'Eschen', 0),
(2126, 122, 'M', 'Mauren', 0),
(2127, 122, 'T', 'Triesenberg', 0),
(2128, 122, 'R', 'Ruggell', 0),
(2129, 122, 'G', 'Gamprin', 0),
(2130, 122, 'L', 'Schellenberg', 0),
(2131, 122, 'P', 'Planken', 0),
(2132, 123, 'AL', 'Alytus', 0),
(2133, 123, 'KA', 'Kaunas', 0),
(2134, 123, 'KL', 'Klaipeda', 0),
(2135, 123, 'MA', 'Marijampole', 0),
(2136, 123, 'PA', 'Panevezys', 0),
(2137, 123, 'SI', 'Siauliai', 0),
(2138, 123, 'TA', 'Taurage', 0),
(2139, 123, 'TE', 'Telsiai', 0),
(2140, 123, 'UT', 'Utena', 0),
(2141, 123, 'VI', 'Vilnius', 0),
(2142, 124, 'DD', 'Diekirch', 0),
(2143, 124, 'DC', 'Clervaux', 0),
(2144, 124, 'DR', 'Redange', 0),
(2145, 124, 'DV', 'Vianden', 0),
(2146, 124, 'DW', 'Wiltz', 0),
(2147, 124, 'GG', 'Grevenmacher', 0),
(2148, 124, 'GE', 'Echternach', 0),
(2149, 124, 'GR', 'Remich', 0),
(2150, 124, 'LL', 'Luxembourg', 0),
(2151, 124, 'LC', 'Capellen', 0),
(2152, 124, 'LE', 'Esch-sur-Alzette', 0),
(2153, 124, 'LM', 'Mersch', 0),
(2154, 125, 'OLF', 'Our Lady Fatima Parish', 0),
(2155, 125, 'ANT', 'St. Anthony Parish', 0),
(2156, 125, 'LAZ', 'St. Lazarus Parish', 0),
(2157, 125, 'CAT', 'Cathedral Parish', 0),
(2158, 125, 'LAW', 'St. Lawrence Parish', 0),
(2159, 126, 'AER', 'Aerodrom', 0),
(2160, 126, 'ARA', 'Ara&#269;inovo', 0),
(2161, 126, 'BER', 'Berovo', 0),
(2162, 126, 'BIT', 'Bitola', 0),
(2163, 126, 'BOG', 'Bogdanci', 0),
(2164, 126, 'BOG', 'Bogovinje', 0),
(2165, 126, 'BOS', 'Bosilovo', 0),
(2166, 126, 'BRV', 'Brvenica', 0),
(2167, 126, 'BUT', 'Butel', 0),
(2168, 126, '&#268;AI', '&#268;air', 0),
(2169, 126, '&#268;A&scaron;', '&#268;a&scaron;ka', 0),
(2170, 126, 'CEN', 'Centar', 0),
(2171, 126, 'CEN', 'Centar &#381;upa', 0),
(2172, 126, '&#268;e&scaron;', '&#268;e&scaron;inovo-Oble&scaron', 0),
(2173, 126, '&#268;U&#268;', '&#268;u&#269;er-Sandevo', 0),
(2174, 126, 'DEB', 'Debar', 0),
(2175, 126, 'DEB', 'Debarca', 0),
(2176, 126, 'DEL', 'Del&#269;evo', 0),
(2177, 126, 'DEM', 'Demir Hisar', 0),
(2178, 126, 'DEM', 'Demir Kapija', 0),
(2179, 126, 'DOL', 'Dolneni', 0),
(2180, 126, 'DRU', 'Drugovo', 0),
(2181, 126, 'GAZ', 'Gazi Baba', 0),
(2182, 126, 'GEV', 'Gevgelija', 0),
(2183, 126, 'GJO', 'Gjor&#269;e Petrov', 0),
(2184, 126, 'GOS', 'Gostivar', 0),
(2185, 126, 'GRA', 'Gradsko', 0),
(2186, 126, 'ILI', 'Ilinden', 0),
(2187, 126, 'JEG', 'Jegunovce', 0),
(2188, 126, 'KAR', 'Karbinci', 0),
(2189, 126, 'KAR', 'Karpo&scaron;', 0),
(2190, 126, 'KAV', 'Kavadarci', 0),
(2191, 126, 'KI&#268;', 'Ki&#269;evo', 0),
(2192, 126, 'KIS', 'Kisela Voda', 0),
(2193, 126, 'KO&#269;', 'Ko&#269;ani', 0),
(2194, 126, 'KON', 'Kon&#269;e', 0),
(2195, 126, 'KRA', 'Kratovo', 0),
(2196, 126, 'KRI', 'Kriva Palanka', 0),
(2197, 126, 'KRI', 'Krivoga&scaron;tani', 0),
(2198, 126, 'KRU', 'Kru&scaron;evo', 0),
(2199, 126, 'KUM', 'Kumanovo', 0),
(2200, 126, 'LIP', 'Lipkovo', 0),
(2201, 126, 'LOZ', 'Lozovo', 0),
(2202, 126, 'MAK', 'Makedonska Kamenica', 0),
(2203, 126, 'MAK', 'Makedonski Brod', 0),
(2204, 126, 'MAV', 'Mavrovo and Rostu&scaron;a', 0),
(2205, 126, 'MOG', 'Mogila', 0),
(2206, 126, 'NEG', 'Negotino', 0),
(2207, 126, 'NOV', 'Novaci', 0),
(2208, 126, 'NOV', 'Novo Selo', 0),
(2209, 126, 'OHR', 'Ohrid', 0),
(2210, 126, 'OSL', 'Oslomej', 0),
(2211, 126, 'PEH', 'Peh&#269;evo', 0),
(2212, 126, 'PET', 'Petrovec', 0),
(2213, 126, 'PLA', 'Plasnica', 0),
(2214, 126, 'PRI', 'Prilep', 0),
(2215, 126, 'PRO', 'Probi&scaron;tip', 0),
(2216, 126, 'RAD', 'Radovi&scaron;', 0),
(2217, 126, 'RAN', 'Rankovce', 0),
(2218, 126, 'RES', 'Resen', 0),
(2219, 126, 'ROS', 'Rosoman', 0),
(2220, 126, 'SAR', 'Saraj', 0),
(2221, 126, 'SOP', 'Sopi&scaron;te', 0),
(2222, 126, 'STA', 'Star Dojran', 0),
(2223, 126, 'STA', 'Staro Nagori&#269;ane', 0),
(2224, 126, '&Scaron;TI', '&Scaron;tip', 0),
(2225, 126, 'STR', 'Struga', 0),
(2226, 126, 'STR', 'Strumica', 0),
(2227, 126, 'STU', 'Studeni&#269;ani', 0),
(2228, 126, '&Scaron;UT', '&Scaron;uto Orizari', 0),
(2229, 126, 'SVE', 'Sveti Nikole', 0),
(2230, 126, 'TEA', 'Tearce', 0),
(2231, 126, 'TET', 'Tetovo', 0),
(2232, 126, 'VAL', 'Valandovo', 0),
(2233, 126, 'VAS', 'Vasilevo', 0),
(2234, 126, 'VEL', 'Veles', 0),
(2235, 126, 'VEV', 'Vev&#269;ani', 0),
(2236, 126, 'VIN', 'Vinica', 0),
(2237, 126, 'VRA', 'Vrane&scaron;tica', 0),
(2238, 126, 'VRA', 'Vrap&#269;i&scaron;te', 0),
(2239, 126, 'ZAJ', 'Zajas', 0),
(2240, 126, 'ZEL', 'Zelenikovo', 0),
(2241, 126, '&#381;EL', '&#381;elino', 0),
(2242, 126, 'ZRN', 'Zrnovci', 0),
(2243, 127, 'AN', 'Antananarivo', 0),
(2244, 127, 'AS', 'Antsiranana', 0),
(2245, 127, 'FN', 'Fianarantsoa', 0),
(2246, 127, 'MJ', 'Mahajanga', 0),
(2247, 127, 'TM', 'Toamasina', 0),
(2248, 127, 'TL', 'Toliara', 0),
(2249, 128, 'BLK', 'Balaka', 0),
(2250, 128, 'BLT', 'Blantyre', 0),
(2251, 128, 'CKW', 'Chikwawa', 0),
(2252, 128, 'CRD', 'Chiradzulu', 0),
(2253, 128, 'CTP', 'Chitipa', 0),
(2254, 128, 'DDZ', 'Dedza', 0),
(2255, 128, 'DWA', 'Dowa', 0),
(2256, 128, 'KRG', 'Karonga', 0),
(2257, 128, 'KSG', 'Kasungu', 0),
(2258, 128, 'LKM', 'Likoma', 0),
(2259, 128, 'LLG', 'Lilongwe', 0),
(2260, 128, 'MCG', 'Machinga', 0),
(2261, 128, 'MGC', 'Mangochi', 0),
(2262, 128, 'MCH', 'Mchinji', 0),
(2263, 128, 'MLJ', 'Mulanje', 0),
(2264, 128, 'MWZ', 'Mwanza', 0),
(2265, 128, 'MZM', 'Mzimba', 0),
(2266, 128, 'NTU', 'Ntcheu', 0),
(2267, 128, 'NKB', 'Nkhata Bay', 0),
(2268, 128, 'NKH', 'Nkhotakota', 0),
(2269, 128, 'NSJ', 'Nsanje', 0),
(2270, 128, 'NTI', 'Ntchisi', 0),
(2271, 128, 'PHL', 'Phalombe', 0),
(2272, 128, 'RMP', 'Rumphi', 0),
(2273, 128, 'SLM', 'Salima', 0),
(2274, 128, 'THY', 'Thyolo', 0),
(2275, 128, 'ZBA', 'Zomba', 0),
(2276, 129, 'Johor', 'Johor', 0),
(2277, 129, 'Kedah', 'Kedah', 0),
(2278, 129, 'Kelantan', 'Kelantan', 0),
(2279, 129, 'Labuan', 'Labuan', 0),
(2280, 129, 'Melaka', 'Melaka', 0),
(2281, 129, 'Negeri Sembilan', 'Negeri Sembilan', 0),
(2282, 129, 'Pahang', 'Pahang', 0),
(2283, 129, 'Perak', 'Perak', 0),
(2284, 129, 'Perlis', 'Perlis', 0),
(2285, 129, 'Pulau Pinang', 'Pulau Pinang', 0),
(2286, 129, 'Sabah', 'Sabah', 0),
(2287, 129, 'Sarawak', 'Sarawak', 0),
(2288, 129, 'Selangor', 'Selangor', 0),
(2289, 129, 'Terengganu', 'Terengganu', 0),
(2290, 129, 'Kuala Lumpur', 'Kuala Lumpur', 0),
(2291, 130, 'THU', 'Thiladhunmathi Uthuru', 0),
(2292, 130, 'THD', 'Thiladhunmathi Dhekunu', 0),
(2293, 130, 'MLU', 'Miladhunmadulu Uthuru', 0),
(2294, 130, 'MLD', 'Miladhunmadulu Dhekunu', 0),
(2295, 130, 'MAU', 'Maalhosmadulu Uthuru', 0),
(2296, 130, 'MAD', 'Maalhosmadulu Dhekunu', 0),
(2297, 130, 'FAA', 'Faadhippolhu', 0),
(2298, 130, 'MAA', 'Male Atoll', 0),
(2299, 130, 'AAU', 'Ari Atoll Uthuru', 0),
(2300, 130, 'AAD', 'Ari Atoll Dheknu', 0),
(2301, 130, 'FEA', 'Felidhe Atoll', 0),
(2302, 130, 'MUA', 'Mulaku Atoll', 0),
(2303, 130, 'NAU', 'Nilandhe Atoll Uthuru', 0),
(2304, 130, 'NAD', 'Nilandhe Atoll Dhekunu', 0),
(2305, 130, 'KLH', 'Kolhumadulu', 0),
(2306, 130, 'HDH', 'Hadhdhunmathi', 0),
(2307, 130, 'HAU', 'Huvadhu Atoll Uthuru', 0),
(2308, 130, 'HAD', 'Huvadhu Atoll Dhekunu', 0),
(2309, 130, 'FMU', 'Fua Mulaku', 0),
(2310, 130, 'ADD', 'Addu', 0),
(2311, 131, 'GA', 'Gao', 0),
(2312, 131, 'KY', 'Kayes', 0),
(2313, 131, 'KD', 'Kidal', 0),
(2314, 131, 'KL', 'Koulikoro', 0),
(2315, 131, 'MP', 'Mopti', 0),
(2316, 131, 'SG', 'Segou', 0),
(2317, 131, 'SK', 'Sikasso', 0),
(2318, 131, 'TB', 'Tombouctou', 0),
(2319, 131, 'CD', 'Bamako Capital District', 0),
(2320, 132, 'ATT', 'Attard', 0),
(2321, 132, 'BAL', 'Balzan', 0),
(2322, 132, 'BGU', 'Birgu', 0),
(2323, 132, 'BKK', 'Birkirkara', 0),
(2324, 132, 'BRZ', 'Birzebbuga', 0),
(2325, 132, 'BOR', 'Bormla', 0),
(2326, 132, 'DIN', 'Dingli', 0),
(2327, 132, 'FGU', 'Fgura', 0),
(2328, 132, 'FLO', 'Floriana', 0),
(2329, 132, 'GDJ', 'Gudja', 0),
(2330, 132, 'GZR', 'Gzira', 0),
(2331, 132, 'GRG', 'Gargur', 0),
(2332, 132, 'GXQ', 'Gaxaq', 0),
(2333, 132, 'HMR', 'Hamrun', 0),
(2334, 132, 'IKL', 'Iklin', 0),
(2335, 132, 'ISL', 'Isla', 0),
(2336, 132, 'KLK', 'Kalkara', 0),
(2337, 132, 'KRK', 'Kirkop', 0),
(2338, 132, 'LIJ', 'Lija', 0),
(2339, 132, 'LUQ', 'Luqa', 0),
(2340, 132, 'MRS', 'Marsa', 0),
(2341, 132, 'MKL', 'Marsaskala', 0),
(2342, 132, 'MXL', 'Marsaxlokk', 0),
(2343, 132, 'MDN', 'Mdina', 0),
(2344, 132, 'MEL', 'Melliea', 0),
(2345, 132, 'MGR', 'Mgarr', 0),
(2346, 132, 'MST', 'Mosta', 0),
(2347, 132, 'MQA', 'Mqabba', 0),
(2348, 132, 'MSI', 'Msida', 0),
(2349, 132, 'MTF', 'Mtarfa', 0),
(2350, 132, 'NAX', 'Naxxar', 0),
(2351, 132, 'PAO', 'Paola', 0),
(2352, 132, 'PEM', 'Pembroke', 0),
(2353, 132, 'PIE', 'Pieta', 0),
(2354, 132, 'QOR', 'Qormi', 0),
(2355, 132, 'QRE', 'Qrendi', 0),
(2356, 132, 'RAB', 'Rabat', 0),
(2357, 132, 'SAF', 'Safi', 0),
(2358, 132, 'SGI', 'San Giljan', 0),
(2359, 132, 'SLU', 'Santa Lucija', 0),
(2360, 132, 'SPB', 'San Pawl il-Bahar', 0),
(2361, 132, 'SGW', 'San Gwann', 0),
(2362, 132, 'SVE', 'Santa Venera', 0),
(2363, 132, 'SIG', 'Siggiewi', 0),
(2364, 132, 'SLM', 'Sliema', 0),
(2365, 132, 'SWQ', 'Swieqi', 0),
(2366, 132, 'TXB', 'Ta Xbiex', 0),
(2367, 132, 'TRX', 'Tarxien', 0),
(2368, 132, 'VLT', 'Valletta', 0),
(2369, 132, 'XGJ', 'Xgajra', 0),
(2370, 132, 'ZBR', 'Zabbar', 0),
(2371, 132, 'ZBG', 'Zebbug', 0),
(2372, 132, 'ZJT', 'Zejtun', 0),
(2373, 132, 'ZRQ', 'Zurrieq', 0),
(2374, 132, 'FNT', 'Fontana', 0),
(2375, 132, 'GHJ', 'Ghajnsielem', 0),
(2376, 132, 'GHR', 'Gharb', 0),
(2377, 132, 'GHS', 'Ghasri', 0),
(2378, 132, 'KRC', 'Kercem', 0),
(2379, 132, 'MUN', 'Munxar', 0),
(2380, 132, 'NAD', 'Nadur', 0),
(2381, 132, 'QAL', 'Qala', 0),
(2382, 132, 'VIC', 'Victoria', 0),
(2383, 132, 'SLA', 'San Lawrenz', 0),
(2384, 132, 'SNT', 'Sannat', 0),
(2385, 132, 'ZAG', 'Xagra', 0),
(2386, 132, 'XEW', 'Xewkija', 0),
(2387, 132, 'ZEB', 'Zebbug', 0),
(2388, 133, 'ALG', 'Ailinginae', 0),
(2389, 133, 'ALL', 'Ailinglaplap', 0),
(2390, 133, 'ALK', 'Ailuk', 0),
(2391, 133, 'ARN', 'Arno', 0),
(2392, 133, 'AUR', 'Aur', 0),
(2393, 133, 'BKR', 'Bikar', 0),
(2394, 133, 'BKN', 'Bikini', 0),
(2395, 133, 'BKK', 'Bokak', 0),
(2396, 133, 'EBN', 'Ebon', 0),
(2397, 133, 'ENT', 'Enewetak', 0),
(2398, 133, 'EKB', 'Erikub', 0),
(2399, 133, 'JBT', 'Jabat', 0),
(2400, 133, 'JLT', 'Jaluit', 0),
(2401, 133, 'JEM', 'Jemo', 0),
(2402, 133, 'KIL', 'Kili', 0),
(2403, 133, 'KWJ', 'Kwajalein', 0),
(2404, 133, 'LAE', 'Lae', 0),
(2405, 133, 'LIB', 'Lib', 0),
(2406, 133, 'LKP', 'Likiep', 0),
(2407, 133, 'MJR', 'Majuro', 0),
(2408, 133, 'MLP', 'Maloelap', 0),
(2409, 133, 'MJT', 'Mejit', 0),
(2410, 133, 'MIL', 'Mili', 0),
(2411, 133, 'NMK', 'Namorik', 0),
(2412, 133, 'NAM', 'Namu', 0),
(2413, 133, 'RGL', 'Rongelap', 0),
(2414, 133, 'RGK', 'Rongrik', 0),
(2415, 133, 'TOK', 'Toke', 0),
(2416, 133, 'UJA', 'Ujae', 0),
(2417, 133, 'UJL', 'Ujelang', 0),
(2418, 133, 'UTK', 'Utirik', 0),
(2419, 133, 'WTH', 'Wotho', 0),
(2420, 133, 'WTJ', 'Wotje', 0),
(2421, 134, 'LAJ', 'L&apos;Ajoupa-Bouillon', 0),
(2422, 134, 'LES', 'Les Anses-d&apos;Arlet', 0),
(2423, 134, 'BAS', 'Basse-Pointe', 0),
(2424, 134, 'BEL', 'Bellefontaine', 0),
(2425, 134, 'LE', 'Le Carbet', 0),
(2426, 134, 'CAS', 'Case-Pilote', 0),
(2427, 134, 'LE', 'Le Diamant', 0),
(2428, 134, 'DUC', 'Ducos', 0),
(2429, 134, 'FON', 'Fonds-Saint-Denis', 0),
(2430, 134, 'FOR', 'Fort-De-France', 0),
(2431, 134, 'LE', 'Le Fran&ccedil;ois', 0),
(2432, 134, 'GRA', 'Grand&apos;Rivi&egrave;re', 0),
(2433, 134, 'GRO', 'Gros-Morne', 0),
(2434, 134, 'LE', 'Le Lamentin', 0),
(2435, 134, 'LE', 'Le Lorrain', 0),
(2436, 134, 'MAC', 'Macouba', 0),
(2437, 134, 'LE', 'Le Marigot', 0),
(2438, 134, 'LE', 'Le Marin', 0),
(2439, 134, 'LE', 'Le Morne-Rouge', 0),
(2440, 134, 'LE', 'Le Morne-Vert', 0),
(2441, 134, 'LE', 'Le Pr&ecirc;cheur', 0),
(2442, 134, 'RIV', 'Rivi&egrave;re-Pilote', 0),
(2443, 134, 'RIV', 'Rivi&egrave;re-Sal&eacute;e', 0),
(2444, 134, 'LE', 'Le Robert', 0),
(2445, 134, 'SAI', 'Sainte-Anne', 0),
(2446, 134, 'SAI', 'Sainte-Luce', 0),
(2447, 134, 'SAI', 'Sainte-Marie', 0),
(2448, 134, 'SAI', 'Saint-Esprit', 0),
(2449, 134, 'SAI', 'Saint-Joseph', 0),
(2450, 134, 'SAI', 'Saint-Pierre', 0),
(2451, 134, 'SCH', 'Sch&oelig;lcher', 0),
(2452, 134, 'LA', 'La Trinit&eacute;', 0),
(2453, 134, 'LES', 'Les Trois-&Icirc;lets', 0),
(2454, 134, 'LE', 'Le Vauclin', 0),
(2455, 135, 'AD', 'Adrar', 0),
(2456, 135, 'AS', 'Assaba', 0),
(2457, 135, 'BR', 'Brakna', 0),
(2458, 135, 'DN', 'Dakhlet Nouadhibou', 0),
(2459, 135, 'GO', 'Gorgol', 0),
(2460, 135, 'GM', 'Guidimaka', 0),
(2461, 135, 'HC', 'Hodh Ech Chargui', 0),
(2462, 135, 'HG', 'Hodh El Gharbi', 0),
(2463, 135, 'IN', 'Inchiri', 0),
(2464, 135, 'TA', 'Tagant', 0),
(2465, 135, 'TZ', 'Tiris Zemmour', 0),
(2466, 135, 'TR', 'Trarza', 0),
(2467, 135, 'NO', 'Nouakchott', 0),
(2468, 136, 'BR', 'Beau Bassin-Rose Hill', 0),
(2469, 136, 'CU', 'Curepipe', 0),
(2470, 136, 'PU', 'Port Louis', 0),
(2471, 136, 'QB', 'Quatre Bornes', 0),
(2472, 136, 'VP', 'Vacoas-Phoenix', 0),
(2473, 136, 'AG', 'Agalega Islands', 0),
(2474, 136, 'CC', 'Cargados Carajos Shoals (Saint B', 0),
(2475, 136, 'RO', 'Rodrigues', 0),
(2476, 136, 'BL', 'Black River', 0),
(2477, 136, 'FL', 'Flacq', 0),
(2478, 136, 'GP', 'Grand Port', 0),
(2479, 136, 'MO', 'Moka', 0),
(2480, 136, 'PA', 'Pamplemousses', 0),
(2481, 136, 'PW', 'Plaines Wilhems', 0),
(2482, 136, 'PL', 'Port Louis', 0),
(2483, 136, 'RR', 'Riviere du Rempart', 0),
(2484, 136, 'SA', 'Savanne', 0),
(2485, 137, 'DZA', 'Dzaoudzi', 0),
(2486, 137, 'PAM', 'Pamandzi', 0),
(2487, 137, 'MAM', 'Mamoudzou', 0),
(2488, 137, 'DEM', 'Dembeni', 0),
(2489, 137, 'BAN', 'Bandrele', 0),
(2490, 137, 'KAN', 'Kani-K&eacute;li', 0),
(2491, 137, 'BOU', 'Bou&eacute;ni', 0),
(2492, 137, 'CHI', 'Chirongui', 0),
(2493, 137, 'SAD', 'Sada', 0),
(2494, 137, 'OUA', 'Ouangani', 0),
(2495, 137, 'CHI', 'Chiconi', 0),
(2496, 137, 'TSI', 'Tsingoni', 0),
(2497, 137, 'MTS', 'M&apos;Tsangamouji', 0),
(2498, 137, 'ACO', 'Acoua', 0),
(2499, 137, 'MTS', 'Mtsamboro', 0),
(2500, 137, 'BAN', 'Bandraboua', 0),
(2501, 137, 'KOU', 'Koungou', 0),
(2502, 138, 'AGU', 'Aguascalientes', 0),
(2503, 138, 'BCN', 'Baja California Norte', 0),
(2504, 138, 'BCS', 'Baja California Sur', 0),
(2505, 138, 'CAM', 'Campeche', 0),
(2506, 138, 'CHP', 'Chiapas', 0),
(2507, 138, 'CHH', 'Chihuahua', 0),
(2508, 138, 'COA', 'Coahuila de Zaragoza', 0),
(2509, 138, 'COL', 'Colima', 0),
(2510, 138, 'DIF', 'Distrito Federal', 0),
(2511, 138, 'DUR', 'Durango', 0),
(2512, 138, 'GUA', 'Guanajuato', 0),
(2513, 138, 'GRO', 'Guerrero', 0),
(2514, 138, 'HID', 'Hidalgo', 0),
(2515, 138, 'JAL', 'Jalisco', 0),
(2516, 138, 'MEX', 'Mexico', 0),
(2517, 138, 'MIC', 'Michoacan de Ocampo', 0),
(2518, 138, 'MOR', 'Morelos', 0),
(2519, 138, 'NAY', 'Nayarit', 0),
(2520, 138, 'NLE', 'Nuevo Leon', 0),
(2521, 138, 'OAX', 'Oaxaca', 0),
(2522, 138, 'PUE', 'Puebla', 0),
(2523, 138, 'QUE', 'Queretaro de Arteaga', 0),
(2524, 138, 'ROO', 'Quintana Roo', 0),
(2525, 138, 'SLP', 'San Luis Potosi', 0),
(2526, 138, 'SIN', 'Sinaloa', 0),
(2527, 138, 'SON', 'Sonora', 0),
(2528, 138, 'TAB', 'Tabasco', 0),
(2529, 138, 'TAM', 'Tamaulipas', 0),
(2530, 138, 'TLA', 'Tlaxcala', 0),
(2531, 138, 'VER', 'Veracruz-Llave', 0),
(2532, 138, 'YUC', 'Yucatan', 0),
(2533, 138, 'ZAC', 'Zacatecas', 0),
(2534, 139, 'C', 'Chuuk', 0),
(2535, 139, 'K', 'Kosrae', 0),
(2536, 139, 'P', 'Pohnpei', 0),
(2537, 139, 'Y', 'Yap', 0),
(2538, 140, 'GA', 'Gagauzia', 0),
(2539, 140, 'CU', 'Chisinau', 0),
(2540, 140, 'BA', 'Balti', 0),
(2541, 140, 'CA', 'Cahul', 0),
(2542, 140, 'ED', 'Edinet', 0),
(2543, 140, 'LA', 'Lapusna', 0),
(2544, 140, 'OR', 'Orhei', 0),
(2545, 140, 'SO', 'Soroca', 0),
(2546, 140, 'TI', 'Tighina', 0),
(2547, 140, 'UN', 'Ungheni', 0),
(2548, 140, 'SN', 'Stanga Nistrulu', 0),
(2549, 141, 'FV', 'Fontvieille', 0),
(2550, 141, 'LC', 'La Condamine', 0),
(2551, 141, 'MV', 'Monaco-Ville', 0),
(2552, 141, 'MC', 'Monte-Carlo', 0),
(2553, 142, '1', 'Ulanbaatar', 0),
(2554, 142, '35', 'Orhon', 0),
(2555, 142, '37', 'Darhan uul', 0),
(2556, 142, '39', 'Hentiy', 0),
(2557, 142, '41', 'Hovsgol', 0),
(2558, 142, '43', 'Hovd', 0),
(2559, 142, '46', 'Uvs', 0),
(2560, 142, '47', 'Tov', 0),
(2561, 142, '49', 'Selenge', 0),
(2562, 142, '51', 'Suhbaatar', 0),
(2563, 142, '53', 'Omnogovi', 0),
(2564, 142, '55', 'Ovorhangay', 0),
(2565, 142, '57', 'Dzavhan', 0),
(2566, 142, '59', 'DundgovL', 0),
(2567, 142, '61', 'Dornod', 0),
(2568, 142, '63', 'Dornogov', 0),
(2569, 142, '64', 'Govi-Sumber', 0),
(2570, 142, '65', 'Govi-Altay', 0),
(2571, 142, '67', 'Bulgan', 0),
(2572, 142, '69', 'Bayanhongor', 0),
(2573, 142, '71', 'Bayan-Olgiy', 0),
(2574, 142, '73', 'Arhangay', 0),
(2575, 143, 'A', 'Saint Anthony', 0),
(2576, 143, 'G', 'Saint Georges', 0),
(2577, 143, 'P', 'Saint Peter', 0),
(2578, 144, 'AGD', 'Agadir', 0),
(2579, 144, 'HOC', 'Al Hoceima', 0),
(2580, 144, 'AZI', 'Azilal', 0),
(2581, 144, 'BME', 'Beni Mellal', 0),
(2582, 144, 'BSL', 'Ben Slimane', 0),
(2583, 144, 'BLM', 'Boulemane', 0),
(2584, 144, 'CBL', 'Casablanca', 0),
(2585, 144, 'CHA', 'Chaouen', 0),
(2586, 144, 'EJA', 'El Jadida', 0),
(2587, 144, 'EKS', 'El Kelaa des Sraghna', 0),
(2588, 144, 'ERA', 'Er Rachidia', 0),
(2589, 144, 'ESS', 'Essaouira', 0),
(2590, 144, 'FES', 'Fes', 0),
(2591, 144, 'FIG', 'Figuig', 0),
(2592, 144, 'GLM', 'Guelmim', 0),
(2593, 144, 'IFR', 'Ifrane', 0),
(2594, 144, 'KEN', 'Kenitra', 0),
(2595, 144, 'KHM', 'Khemisset', 0),
(2596, 144, 'KHN', 'Khenifra', 0),
(2597, 144, 'KHO', 'Khouribga', 0),
(2598, 144, 'LYN', 'Laayoune', 0),
(2599, 144, 'LAR', 'Larache', 0),
(2600, 144, 'MRK', 'Marrakech', 0),
(2601, 144, 'MKN', 'Meknes', 0),
(2602, 144, 'NAD', 'Nador', 0),
(2603, 144, 'ORZ', 'Ouarzazate', 0),
(2604, 144, 'OUJ', 'Oujda', 0),
(2605, 144, 'RSA', 'Rabat-Sale', 0),
(2606, 144, 'SAF', 'Safi', 0),
(2607, 144, 'SET', 'Settat', 0),
(2608, 144, 'SKA', 'Sidi Kacem', 0),
(2609, 144, 'TGR', 'Tangier', 0),
(2610, 144, 'TAN', 'Tan-Tan', 0),
(2611, 144, 'TAO', 'Taounate', 0),
(2612, 144, 'TRD', 'Taroudannt', 0),
(2613, 144, 'TAT', 'Tata', 0),
(2614, 144, 'TAZ', 'Taza', 0),
(2615, 144, 'TET', 'Tetouan', 0),
(2616, 144, 'TIZ', 'Tiznit', 0),
(2617, 144, 'ADK', 'Ad Dakhla', 0),
(2618, 144, 'BJD', 'Boujdour', 0),
(2619, 144, 'ESM', 'Es Smara', 0),
(2620, 145, 'CD', 'Cabo Delgado', 0),
(2621, 145, 'GZ', 'Gaza', 0),
(2622, 145, 'IN', 'Inhambane', 0),
(2623, 145, 'MN', 'Manica', 0),
(2624, 145, 'MC', 'Maputo (city)', 0),
(2625, 145, 'MP', 'Maputo', 0),
(2626, 145, 'NA', 'Nampula', 0),
(2627, 145, 'NI', 'Niassa', 0),
(2628, 145, 'SO', 'Sofala', 0),
(2629, 145, 'TE', 'Tete', 0),
(2630, 145, 'ZA', 'Zambezia', 0),
(2631, 146, 'AY', 'Ayeyarwady', 0),
(2632, 146, 'BG', 'Bago', 0),
(2633, 146, 'MG', 'Magway', 0),
(2634, 146, 'MD', 'Mandalay', 0),
(2635, 146, 'SG', 'Sagaing', 0),
(2636, 146, 'TN', 'Tanintharyi', 0),
(2637, 146, 'YG', 'Yangon', 0),
(2638, 146, 'CH', 'Chin State', 0),
(2639, 146, 'KC', 'Kachin State', 0),
(2640, 146, 'KH', 'Kayah State', 0),
(2641, 146, 'KN', 'Kayin State', 0),
(2642, 146, 'MN', 'Mon State', 0),
(2643, 146, 'RK', 'Rakhine State', 0),
(2644, 146, 'SH', 'Shan State', 0),
(2645, 147, 'CA', 'Caprivi', 0),
(2646, 147, 'ER', 'Erongo', 0),
(2647, 147, 'HA', 'Hardap', 0),
(2648, 147, 'KR', 'Karas', 0),
(2649, 147, 'KV', 'Kavango', 0),
(2650, 147, 'KH', 'Khomas', 0),
(2651, 147, 'KU', 'Kunene', 0),
(2652, 147, 'OW', 'Ohangwena', 0),
(2653, 147, 'OK', 'Omaheke', 0),
(2654, 147, 'OT', 'Omusati', 0),
(2655, 147, 'ON', 'Oshana', 0),
(2656, 147, 'OO', 'Oshikoto', 0),
(2657, 147, 'OJ', 'Otjozondjupa', 0),
(2658, 148, 'AO', 'Aiwo', 0),
(2659, 148, 'AA', 'Anabar', 0),
(2660, 148, 'AT', 'Anetan', 0),
(2661, 148, 'AI', 'Anibare', 0),
(2662, 148, 'BA', 'Baiti', 0),
(2663, 148, 'BO', 'Boe', 0),
(2664, 148, 'BU', 'Buada', 0),
(2665, 148, 'DE', 'Denigomodu', 0),
(2666, 148, 'EW', 'Ewa', 0),
(2667, 148, 'IJ', 'Ijuw', 0),
(2668, 148, 'ME', 'Meneng', 0),
(2669, 148, 'NI', 'Nibok', 0),
(2670, 148, 'UA', 'Uaboe', 0),
(2671, 148, 'YA', 'Yaren', 0),
(2672, 149, 'BA', 'Bagmati', 0),
(2673, 149, 'BH', 'Bheri', 0),
(2674, 149, 'DH', 'Dhawalagiri', 0),
(2675, 149, 'GA', 'Gandaki', 0),
(2676, 149, 'JA', 'Janakpur', 0),
(2677, 149, 'KA', 'Karnali', 0),
(2678, 149, 'KO', 'Kosi', 0),
(2679, 149, 'LU', 'Lumbini', 0),
(2680, 149, 'MA', 'Mahakali', 0),
(2681, 149, 'ME', 'Mechi', 0),
(2682, 149, 'NA', 'Narayani', 0),
(2683, 149, 'RA', 'Rapti', 0),
(2684, 149, 'SA', 'Sagarmatha', 0),
(2685, 149, 'SE', 'Seti', 0),
(2686, 150, 'DR', 'Drenthe', 0),
(2687, 150, 'FL', 'Flevoland', 0),
(2688, 150, 'FR', 'Friesland', 0),
(2689, 150, 'GE', 'Gelderland', 0),
(2690, 150, 'GR', 'Groningen', 0),
(2691, 150, 'LI', 'Limburg', 0),
(2692, 150, 'NB', 'Noord Brabant', 0),
(2693, 150, 'NH', 'Noord Holland', 0),
(2694, 150, 'OV', 'Overijssel', 0),
(2695, 150, 'UT', 'Utrecht', 0),
(2696, 150, 'ZE', 'Zeeland', 0),
(2697, 150, 'ZH', 'Zuid Holland', 0),
(2698, 151, 'BON', 'Bonaire', 0),
(2699, 151, 'CUR', 'Cura&ccedil;ao', 0),
(2700, 151, 'SAB', 'Saba', 0),
(2701, 151, 'SEU', 'Sint Eustatius', 0),
(2702, 151, 'SMA', 'Sint Maarten', 0),
(2703, 152, 'L', 'Iles Loyaute', 0),
(2704, 152, 'N', 'Nord', 0),
(2705, 152, 'S', 'Sud', 0),
(2706, 153, 'AUK', 'Auckland', 0),
(2707, 153, 'BOP', 'Bay of Plenty', 0),
(2708, 153, 'CAN', 'Canterbury', 0),
(2709, 153, 'COR', 'Coromandel', 0),
(2710, 153, 'GIS', 'Gisborne', 0),
(2711, 153, 'FIO', 'Fiordland', 0),
(2712, 153, 'HKB', 'Hawke&apos;s Bay', 0),
(2713, 153, 'MBH', 'Marlborough', 0),
(2714, 153, 'MWT', 'Manawatu-Wanganui', 0),
(2715, 153, 'MCM', 'Mt Cook-Mackenzie', 0),
(2716, 153, 'NSN', 'Nelson', 0),
(2717, 153, 'NTL', 'Northland', 0),
(2718, 153, 'OTA', 'Otago', 0),
(2719, 153, 'STL', 'Southland', 0),
(2720, 153, 'TKI', 'Taranaki', 0),
(2721, 153, 'WGN', 'Wellington', 0),
(2722, 153, 'WKO', 'Waikato', 0),
(2723, 153, 'WAI', 'Wairprarapa', 0),
(2724, 153, 'WTC', 'West Coast', 0),
(2725, 154, 'AN', 'Atlantico Norte', 0),
(2726, 154, 'AS', 'Atlantico Sur', 0),
(2727, 154, 'BO', 'Boaco', 0),
(2728, 154, 'CA', 'Carazo', 0),
(2729, 154, 'CI', 'Chinandega', 0),
(2730, 154, 'CO', 'Chontales', 0),
(2731, 154, 'ES', 'Esteli', 0),
(2732, 154, 'GR', 'Granada', 0),
(2733, 154, 'JI', 'Jinotega', 0),
(2734, 154, 'LE', 'Leon', 0),
(2735, 154, 'MD', 'Madriz', 0),
(2736, 154, 'MN', 'Managua', 0),
(2737, 154, 'MS', 'Masaya', 0),
(2738, 154, 'MT', 'Matagalpa', 0),
(2739, 154, 'NS', 'Nuevo Segovia', 0),
(2740, 154, 'RS', 'Rio San Juan', 0),
(2741, 154, 'RI', 'Rivas', 0),
(2742, 155, 'AG', 'Agadez', 0),
(2743, 155, 'DF', 'Diffa', 0),
(2744, 155, 'DS', 'Dosso', 0),
(2745, 155, 'MA', 'Maradi', 0),
(2746, 155, 'NM', 'Niamey', 0),
(2747, 155, 'TH', 'Tahoua', 0),
(2748, 155, 'TL', 'Tillaberi', 0),
(2749, 155, 'ZD', 'Zinder', 0),
(2750, 156, 'AB', 'Abia', 0),
(2751, 156, 'CT', 'Abuja Federal Capital Territory', 0),
(2752, 156, 'AD', 'Adamawa', 0),
(2753, 156, 'AK', 'Akwa Ibom', 0),
(2754, 156, 'AN', 'Anambra', 0),
(2755, 156, 'BC', 'Bauchi', 0),
(2756, 156, 'BY', 'Bayelsa', 0),
(2757, 156, 'BN', 'Benue', 0),
(2758, 156, 'BO', 'Borno', 0),
(2759, 156, 'CR', 'Cross River', 0),
(2760, 156, 'DE', 'Delta', 0),
(2761, 156, 'EB', 'Ebonyi', 0),
(2762, 156, 'ED', 'Edo', 0),
(2763, 156, 'EK', 'Ekiti', 0),
(2764, 156, 'EN', 'Enugu', 0),
(2765, 156, 'GO', 'Gombe', 0),
(2766, 156, 'IM', 'Imo', 0),
(2767, 156, 'JI', 'Jigawa', 0),
(2768, 156, 'KD', 'Kaduna', 0),
(2769, 156, 'KN', 'Kano', 0),
(2770, 156, 'KT', 'Katsina', 0),
(2771, 156, 'KE', 'Kebbi', 0),
(2772, 156, 'KO', 'Kogi', 0),
(2773, 156, 'KW', 'Kwara', 0),
(2774, 156, 'LA', 'Lagos', 0),
(2775, 156, 'NA', 'Nassarawa', 0),
(2776, 156, 'NI', 'Niger', 0),
(2777, 156, 'OG', 'Ogun', 0),
(2778, 156, 'ONG', 'Ondo', 0),
(2779, 156, 'OS', 'Osun', 0),
(2780, 156, 'OY', 'Oyo', 0),
(2781, 156, 'PL', 'Plateau', 0),
(2782, 156, 'RI', 'Rivers', 0),
(2783, 156, 'SO', 'Sokoto', 0),
(2784, 156, 'TA', 'Taraba', 0),
(2785, 156, 'YO', 'Yobe', 0),
(2786, 156, 'ZA', 'Zamfara', 0),
(2787, 157, 'MAK', 'Makefu', 0),
(2788, 157, 'TUA', 'Tuapa', 0),
(2789, 157, 'NAM', 'Namukulu', 0),
(2790, 157, 'HIK', 'Hikutavake', 0),
(2791, 157, 'TOI', 'Toi', 0),
(2792, 157, 'MUT', 'Mutalau', 0),
(2793, 157, 'LAK', 'Lakepa', 0),
(2794, 157, 'LIK', 'Liku', 0),
(2795, 157, 'HAK', 'Hakupu', 0),
(2796, 157, 'VAI', 'Vaiea', 0),
(2797, 157, 'AVA', 'Avatele', 0),
(2798, 157, 'TAM', 'Tamakautoga', 0),
(2799, 157, 'ALO', 'Alofi South', 0),
(2800, 157, 'ALO', 'Alofi North', 0),
(2801, 158, 'NOR', 'Norfolk Island', 0),
(2802, 159, 'N', 'Northern Islands', 0),
(2803, 159, 'R', 'Rota', 0),
(2804, 159, 'S', 'Saipan', 0),
(2805, 159, 'T', 'Tinian', 0),
(2806, 160, 'AK', 'Akershus', 0),
(2807, 160, 'AA', 'Aust-Agder', 0),
(2808, 160, 'BU', 'Buskerud', 0),
(2809, 160, 'FM', 'Finnmark', 0),
(2810, 160, 'HM', 'Hedmark', 0),
(2811, 160, 'HL', 'Hordaland', 0),
(2812, 160, 'MR', 'M&oslash;re og Romsdal', 0),
(2813, 160, 'NL', 'Nordland', 0),
(2814, 160, 'NT', 'Nord-Tr&oslash;ndelag', 0),
(2815, 160, 'OP', 'Oppland', 0),
(2816, 160, 'OL', 'Oslo', 0),
(2817, 160, 'RL', 'Rogaland', 0),
(2818, 160, 'SJ', 'Sogn og Fjordane', 0),
(2819, 160, 'ST', 'S&oslash;r-Tr&oslash;ndelag', 0),
(2820, 160, 'SV', 'Svalbard', 0),
(2821, 160, 'TM', 'Telemark', 0),
(2822, 160, 'TR', 'Troms', 0),
(2823, 160, 'VA', 'Vest-Agder', 0),
(2824, 160, 'VF', 'Vestfold', 0),
(2825, 160, 'OF', '&Oslash;stfold', 0),
(2826, 161, 'DA', 'Ad Dakhiliyah', 0),
(2827, 161, 'BA', 'Al Batinah', 0),
(2828, 161, 'WU', 'Al Wusta', 0),
(2829, 161, 'SH', 'Ash Sharqiyah', 0),
(2830, 161, 'ZA', 'Az Zahirah', 0),
(2831, 161, 'MA', 'Masqat', 0),
(2832, 161, 'MU', 'Musandam', 0),
(2833, 161, 'ZU', 'Zufar', 0),
(2834, 162, 'B', 'Balochistan', 0),
(2835, 162, 'T', 'Federally Administered Tribal Ar', 0),
(2836, 162, 'I', 'Islamabad Capital Territory', 0),
(2837, 162, 'N', 'North-West Frontier', 0),
(2838, 162, 'P', 'Punjab', 0),
(2839, 162, 'S', 'Sindh', 0),
(2840, 163, 'AM', 'Aimeliik', 0),
(2841, 163, 'AR', 'Airai', 0),
(2842, 163, 'AN', 'Angaur', 0),
(2843, 163, 'HA', 'Hatohobei', 0),
(2844, 163, 'KA', 'Kayangel', 0),
(2845, 163, 'KO', 'Koror', 0),
(2846, 163, 'ME', 'Melekeok', 0),
(2847, 163, 'NA', 'Ngaraard', 0),
(2848, 163, 'NG', 'Ngarchelong', 0),
(2849, 163, 'ND', 'Ngardmau', 0),
(2850, 163, 'NT', 'Ngatpang', 0),
(2851, 163, 'NC', 'Ngchesar', 0),
(2852, 163, 'NR', 'Ngeremlengui', 0),
(2853, 163, 'NW', 'Ngiwal', 0),
(2854, 163, 'PE', 'Peleliu', 0),
(2855, 163, 'SO', 'Sonsorol', 0),
(2856, 164, 'BT', 'Bocas del Toro', 0),
(2857, 164, 'CH', 'Chiriqui', 0),
(2858, 164, 'CC', 'Cocle', 0),
(2859, 164, 'CL', 'Colon', 0),
(2860, 164, 'DA', 'Darien', 0),
(2861, 164, 'HE', 'Herrera', 0),
(2862, 164, 'LS', 'Los Santos', 0),
(2863, 164, 'PA', 'Panama', 0),
(2864, 164, 'SB', 'San Blas', 0),
(2865, 164, 'VG', 'Veraguas', 0),
(2866, 165, 'BV', 'Bougainville', 0),
(2867, 165, 'CE', 'Central', 0),
(2868, 165, 'CH', 'Chimbu', 0),
(2869, 165, 'EH', 'Eastern Highlands', 0),
(2870, 165, 'EB', 'East New Britain', 0),
(2871, 165, 'ES', 'East Sepik', 0),
(2872, 165, 'EN', 'Enga', 0),
(2873, 165, 'GU', 'Gulf', 0),
(2874, 165, 'MD', 'Madang', 0),
(2875, 165, 'MN', 'Manus', 0),
(2876, 165, 'MB', 'Milne Bay', 0),
(2877, 165, 'MR', 'Morobe', 0),
(2878, 165, 'NC', 'National Capital', 0),
(2879, 165, 'NI', 'New Ireland', 0),
(2880, 165, 'NO', 'Northern', 0),
(2881, 165, 'SA', 'Sandaun', 0),
(2882, 165, 'SH', 'Southern Highlands', 0),
(2883, 165, 'WE', 'Western', 0),
(2884, 165, 'WH', 'Western Highlands', 0),
(2885, 165, 'WB', 'West New Britain', 0),
(2886, 166, 'AG', 'Alto Paraguay', 0),
(2887, 166, 'AN', 'Alto Parana', 0),
(2888, 166, 'AM', 'Amambay', 0),
(2889, 166, 'AS', 'Asuncion', 0),
(2890, 166, 'BO', 'Boqueron', 0),
(2891, 166, 'CG', 'Caaguazu', 0),
(2892, 166, 'CZ', 'Caazapa', 0),
(2893, 166, 'CN', 'Canindeyu', 0),
(2894, 166, 'CE', 'Central', 0),
(2895, 166, 'CC', 'Concepcion', 0),
(2896, 166, 'CD', 'Cordillera', 0),
(2897, 166, 'GU', 'Guaira', 0),
(2898, 166, 'IT', 'Itapua', 0),
(2899, 166, 'MI', 'Misiones', 0),
(2900, 166, 'NE', 'Neembucu', 0),
(2901, 166, 'PA', 'Paraguari', 0),
(2902, 166, 'PH', 'Presidente Hayes', 0),
(2903, 166, 'SP', 'San Pedro', 0),
(2904, 167, 'AM', 'Amazonas', 0),
(2905, 167, 'AN', 'Ancash', 0),
(2906, 167, 'AP', 'Apurimac', 0),
(2907, 167, 'AR', 'Arequipa', 0),
(2908, 167, 'AY', 'Ayacucho', 0),
(2909, 167, 'CJ', 'Cajamarca', 0),
(2910, 167, 'CL', 'Callao', 0),
(2911, 167, 'CU', 'Cusco', 0),
(2912, 167, 'HV', 'Huancavelica', 0),
(2913, 167, 'HO', 'Huanuco', 0),
(2914, 167, 'IC', 'Ica', 0),
(2915, 167, 'JU', 'Junin', 0),
(2916, 167, 'LD', 'La Libertad', 0),
(2917, 167, 'LY', 'Lambayeque', 0),
(2918, 167, 'LI', 'Lima', 0),
(2919, 167, 'LO', 'Loreto', 0),
(2920, 167, 'MD', 'Madre de Dios', 0),
(2921, 167, 'MO', 'Moquegua', 0),
(2922, 167, 'PA', 'Pasco', 0),
(2923, 167, 'PI', 'Piura', 0),
(2924, 167, 'PU', 'Puno', 0),
(2925, 167, 'SM', 'San Martin', 0),
(2926, 167, 'TA', 'Tacna', 0),
(2927, 167, 'TU', 'Tumbes', 0),
(2928, 167, 'UC', 'Ucayali', 0),
(2929, 168, 'ABR', 'Abra', 0),
(2930, 168, 'ANO', 'Agusan del Norte', 0),
(2931, 168, 'ASU', 'Agusan del Sur', 0),
(2932, 168, 'AKL', 'Aklan', 0),
(2933, 168, 'ALB', 'Albay', 0),
(2934, 168, 'ANT', 'Antique', 0),
(2935, 168, 'APY', 'Apayao', 0),
(2936, 168, 'AUR', 'Aurora', 0),
(2937, 168, 'BAS', 'Basilan', 0),
(2938, 168, 'BTA', 'Bataan', 0),
(2939, 168, 'BTE', 'Batanes', 0),
(2940, 168, 'BTG', 'Batangas', 0),
(2941, 168, 'BLR', 'Biliran', 0),
(2942, 168, 'BEN', 'Benguet', 0),
(2943, 168, 'BOL', 'Bohol', 0),
(2944, 168, 'BUK', 'Bukidnon', 0),
(2945, 168, 'BUL', 'Bulacan', 0),
(2946, 168, 'CAG', 'Cagayan', 0),
(2947, 168, 'CNO', 'Camarines Norte', 0),
(2948, 168, 'CSU', 'Camarines Sur', 0),
(2949, 168, 'CAM', 'Camiguin', 0),
(2950, 168, 'CAP', 'Capiz', 0),
(2951, 168, 'CAT', 'Catanduanes', 0),
(2952, 168, 'CAV', 'Cavite', 0),
(2953, 168, 'CEB', 'Cebu', 0),
(2954, 168, 'CMP', 'Compostela', 0),
(2955, 168, 'DNO', 'Davao del Norte', 0),
(2956, 168, 'DSU', 'Davao del Sur', 0),
(2957, 168, 'DOR', 'Davao Oriental', 0),
(2958, 168, 'ESA', 'Eastern Samar', 0),
(2959, 168, 'GUI', 'Guimaras', 0),
(2960, 168, 'IFU', 'Ifugao', 0),
(2961, 168, 'INO', 'Ilocos Norte', 0),
(2962, 168, 'ISU', 'Ilocos Sur', 0),
(2963, 168, 'ILO', 'Iloilo', 0),
(2964, 168, 'ISA', 'Isabela', 0),
(2965, 168, 'KAL', 'Kalinga', 0),
(2966, 168, 'LAG', 'Laguna', 0),
(2967, 168, 'LNO', 'Lanao del Norte', 0),
(2968, 168, 'LSU', 'Lanao del Sur', 0),
(2969, 168, 'UNI', 'La Union', 0),
(2970, 168, 'LEY', 'Leyte', 0),
(2971, 168, 'MAG', 'Maguindanao', 0),
(2972, 168, 'MRN', 'Marinduque', 0),
(2973, 168, 'MSB', 'Masbate', 0),
(2974, 168, 'MIC', 'Mindoro Occidental', 0),
(2975, 168, 'MIR', 'Mindoro Oriental', 0),
(2976, 168, 'MSC', 'Misamis Occidental', 0),
(2977, 168, 'MOR', 'Misamis Oriental', 0),
(2978, 168, 'MOP', 'Mountain', 0),
(2979, 168, 'NOC', 'Negros Occidental', 0),
(2980, 168, 'NOR', 'Negros Oriental', 0),
(2981, 168, 'NCT', 'North Cotabato', 0),
(2982, 168, 'NSM', 'Northern Samar', 0),
(2983, 168, 'NEC', 'Nueva Ecija', 0),
(2984, 168, 'NVZ', 'Nueva Vizcaya', 0),
(2985, 168, 'PLW', 'Palawan', 0),
(2986, 168, 'PMP', 'Pampanga', 0),
(2987, 168, 'PNG', 'Pangasinan', 0),
(2988, 168, 'QZN', 'Quezon', 0),
(2989, 168, 'QRN', 'Quirino', 0),
(2990, 168, 'RIZ', 'Rizal', 0),
(2991, 168, 'ROM', 'Romblon', 0),
(2992, 168, 'SMR', 'Samar', 0),
(2993, 168, 'SRG', 'Sarangani', 0),
(2994, 168, 'SQJ', 'Siquijor', 0),
(2995, 168, 'SRS', 'Sorsogon', 0),
(2996, 168, 'SCO', 'South Cotabato', 0),
(2997, 168, 'SLE', 'Southern Leyte', 0),
(2998, 168, 'SKU', 'Sultan Kudarat', 0),
(2999, 168, 'SLU', 'Sulu', 0),
(3000, 168, 'SNO', 'Surigao del Norte', 0),
(3001, 168, 'SSU', 'Surigao del Sur', 0),
(3002, 168, 'TAR', 'Tarlac', 0),
(3003, 168, 'TAW', 'Tawi-Tawi', 0),
(3004, 168, 'ZBL', 'Zambales', 0),
(3005, 168, 'ZNO', 'Zamboanga del Norte', 0),
(3006, 168, 'ZSU', 'Zamboanga del Sur', 0),
(3007, 168, 'ZSI', 'Zamboanga Sibugay', 0),
(3008, 169, 'PIT', 'Pitcairn Island', 0),
(3009, 170, 'DO', 'Dolnoslaskie', 0),
(3010, 170, 'KP', 'Kujawsko-Pomorskie', 0),
(3011, 170, 'LO', 'Lodzkie', 0),
(3012, 170, 'LL', 'Lubelskie', 0),
(3013, 170, 'LU', 'Lubuskie', 0),
(3014, 170, 'ML', 'Malopolskie', 0),
(3015, 170, 'MZ', 'Mazowieckie', 0),
(3016, 170, 'OP', 'Opolskie', 0);
INSERT INTO `zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`, `placement`) VALUES
(3017, 170, 'PP', 'Podkarpackie', 0),
(3018, 170, 'PL', 'Podlaskie', 0),
(3019, 170, 'PM', 'Pomorskie', 0),
(3020, 170, 'SL', 'Slaskie', 0),
(3021, 170, 'SW', 'Swietokrzyskie', 0),
(3022, 170, 'WM', 'Warminsko-Mazurskie', 0),
(3023, 170, 'WP', 'Wielkopolskie', 0),
(3024, 170, 'ZA', 'Zachodniopomorskie', 0),
(3025, 171, 'AC', 'A&ccedil;ores', 0),
(3026, 171, 'AV', 'Aveiro', 0),
(3027, 171, 'BE', 'Beja', 0),
(3028, 171, 'BR', 'Braga', 0),
(3029, 171, 'BA', 'Bragan&ccedil;a', 0),
(3030, 171, 'CB', 'Castelo Branco', 0),
(3031, 171, 'CO', 'Coimbra', 0),
(3032, 171, 'EV', '&Eacute;vora', 0),
(3033, 171, 'FA', 'Faro', 0),
(3034, 171, 'GU', 'Guarda', 0),
(3035, 171, 'LE', 'Leiria', 0),
(3036, 171, 'LI', 'Lisboa', 0),
(3037, 171, 'ME', 'Madeira', 0),
(3038, 171, 'PO', 'Portalegre', 0),
(3039, 171, 'PR', 'Porto', 0),
(3040, 171, 'SA', 'Santar&eacute;m', 0),
(3041, 171, 'SE', 'Set&uacute;bal', 0),
(3042, 171, 'VC', 'Viana do Castelo', 0),
(3043, 171, 'VR', 'Vila Real', 0),
(3044, 171, 'VI', 'Viseu', 0),
(3045, 172, 'ADJ', 'Adjuntas', 0),
(3046, 172, 'AGU', 'Aguada', 0),
(3047, 172, 'AGU', 'Aguadilla', 0),
(3048, 172, 'AGU', 'Aguas Buenas', 0),
(3049, 172, 'AIB', 'Aibonito', 0),
(3050, 172, 'A-A', 'A', 0),
(3051, 172, 'ARE', 'Arecibo', 0),
(3052, 172, 'ARR', 'Arroyo', 0),
(3053, 172, 'BAR', 'Barceloneta', 0),
(3054, 172, 'BAR', 'Barranquitas', 0),
(3055, 172, 'BAY', 'Bayam&oacute;n', 0),
(3056, 172, 'CAB', 'Cabo Rojo', 0),
(3057, 172, 'CAG', 'Caguas', 0),
(3058, 172, 'CAM', 'Camuy', 0),
(3059, 172, 'CAN', 'Can&oacute;vanas', 0),
(3060, 172, 'CAR', 'Carolina', 0),
(3061, 172, 'CAT', 'Cata&ntilde;o', 0),
(3062, 172, 'CAY', 'Cayey', 0),
(3063, 172, 'CEI', 'Ceiba', 0),
(3064, 172, 'CIA', 'Ciales', 0),
(3065, 172, 'CID', 'Cidra', 0),
(3066, 172, 'COA', 'Coamo', 0),
(3067, 172, 'COM', 'Comer&iacute;o', 0),
(3068, 172, 'COR', 'Corozal', 0),
(3069, 172, 'CUL', 'Culebra', 0),
(3070, 172, 'DOR', 'Dorado', 0),
(3071, 172, 'FAJ', 'Fajardo', 0),
(3072, 172, 'FLO', 'Florida', 0),
(3073, 172, 'GU', 'Gu', 0),
(3074, 172, 'GUA', 'Guayama', 0),
(3075, 172, 'GUA', 'Guayanilla', 0),
(3076, 172, 'GUA', 'Guaynabo', 0),
(3077, 172, 'GUR', 'Gurabo', 0),
(3078, 172, 'HAT', 'Hatillo', 0),
(3079, 172, 'HOR', 'Hormigueros', 0),
(3080, 172, 'HUM', 'Humacao', 0),
(3081, 172, 'ISA', 'Isabela', 0),
(3082, 172, 'JAY', 'Jayuya', 0),
(3083, 172, 'JUA', 'Juana D&iacute;az', 0),
(3084, 172, 'JUN', 'Juncos', 0),
(3085, 172, 'LAJ', 'Lajas', 0),
(3086, 172, 'LAR', 'Lares', 0),
(3087, 172, 'LAS', 'Las Mar&iacute;as', 0),
(3088, 172, 'LAS', 'Las Piedras', 0),
(3089, 172, 'LOA', 'Loíza', 0),
(3090, 172, 'LUQ', 'Luquillo', 0),
(3091, 172, 'MAN', 'Manat&iacute;', 0),
(3092, 172, 'MAR', 'Maricao', 0),
(3093, 172, 'MAU', 'Maunabo', 0),
(3094, 172, 'MAY', 'Mayag&uuml;ez', 0),
(3095, 172, 'MOC', 'Moca', 0),
(3096, 172, 'MOR', 'Morovis', 0),
(3097, 172, 'NAG', 'Naguabo', 0),
(3098, 172, 'NAR', 'Naranjito', 0),
(3099, 172, 'ORO', 'Orocovis', 0),
(3100, 172, 'PAT', 'Patillas', 0),
(3101, 172, 'PE-', 'Peñuelas', 0),
(3102, 172, 'PON', 'Ponce', 0),
(3103, 172, 'QUE', 'Quebradillas', 0),
(3104, 172, 'RIN', 'Rinc&oacute;n', 0),
(3105, 172, 'RIO', 'Río Grande', 0),
(3106, 172, 'SAB', 'Sabana Grande', 0),
(3107, 172, 'SAL', 'Salinas', 0),
(3108, 172, 'SAN', 'San Germ&aacute;n', 0),
(3109, 172, 'SAN', 'San Juan', 0),
(3110, 172, 'SAN', 'San Lorenzo', 0),
(3111, 172, 'SAN', 'San Sebasti&aacute;n', 0),
(3112, 172, 'SAN', 'Santa Isabel', 0),
(3113, 172, 'TOA', 'Toa Alta', 0),
(3114, 172, 'TOA', 'Toa Baja', 0),
(3115, 172, 'TRU', 'Trujillo Alto', 0),
(3116, 172, 'UTU', 'Utuado', 0),
(3117, 172, 'VEG', 'Vega Alta', 0),
(3118, 172, 'VEG', 'Vega Baja', 0),
(3119, 172, 'VIE', 'Vieques', 0),
(3120, 172, 'VIL', 'Villalba', 0),
(3121, 172, 'YAB', 'Yabucoa', 0),
(3122, 172, 'YAU', 'Yauco', 0),
(3123, 173, 'DW', 'Ad Dawhah', 0),
(3124, 173, 'GW', 'Al Ghuwayriyah', 0),
(3125, 173, 'JM', 'Al Jumayliyah', 0),
(3126, 173, 'KR', 'Al Khawr', 0),
(3127, 173, 'WK', 'Al Wakrah', 0),
(3128, 173, 'RN', 'Ar Rayyan', 0),
(3129, 173, 'JB', 'Jarayan al Batinah', 0),
(3130, 173, 'MS', 'Madinat ash Shamal', 0),
(3131, 173, 'UD', 'Umm Sa&apos;id', 0),
(3132, 173, 'UL', 'Umm Salal', 0),
(3133, 175, 'AB', 'Alba', 0),
(3134, 175, 'AR', 'Arad', 0),
(3135, 175, 'AG', 'Arges', 0),
(3136, 175, 'BC', 'Bacau', 0),
(3137, 175, 'BH', 'Bihor', 0),
(3138, 175, 'BN', 'Bistrita-Nasaud', 0),
(3139, 175, 'BT', 'Botosani', 0),
(3140, 175, 'BV', 'Brasov', 0),
(3141, 175, 'BR', 'Braila', 0),
(3142, 175, 'B', 'Bucuresti', 0),
(3143, 175, 'BZ', 'Buzau', 0),
(3144, 175, 'CS', 'Caras-Severin', 0),
(3145, 175, 'CL', 'Calarasi', 0),
(3146, 175, 'CJ', 'Cluj', 0),
(3147, 175, 'CT', 'Constanta', 0),
(3148, 175, 'CV', 'Covasna', 0),
(3149, 175, 'DB', 'Dimbovita', 0),
(3150, 175, 'DJ', 'Dolj', 0),
(3151, 175, 'GL', 'Galati', 0),
(3152, 175, 'GR', 'Giurgiu', 0),
(3153, 175, 'GJ', 'Gorj', 0),
(3154, 175, 'HR', 'Harghita', 0),
(3155, 175, 'HD', 'Hunedoara', 0),
(3156, 175, 'IL', 'Ialomita', 0),
(3157, 175, 'IS', 'Iasi', 0),
(3158, 175, 'IF', 'Ilfov', 0),
(3159, 175, 'MM', 'Maramures', 0),
(3160, 175, 'MH', 'Mehedinti', 0),
(3161, 175, 'MS', 'Mures', 0),
(3162, 175, 'NT', 'Neamt', 0),
(3163, 175, 'OT', 'Olt', 0),
(3164, 175, 'PH', 'Prahova', 0),
(3165, 175, 'SM', 'Satu-Mare', 0),
(3166, 175, 'SJ', 'Salaj', 0),
(3167, 175, 'SB', 'Sibiu', 0),
(3168, 175, 'SV', 'Suceava', 0),
(3169, 175, 'TR', 'Teleorman', 0),
(3170, 175, 'TM', 'Timis', 0),
(3171, 175, 'TL', 'Tulcea', 0),
(3172, 175, 'VS', 'Vaslui', 0),
(3173, 175, 'VL', 'Valcea', 0),
(3174, 175, 'VN', 'Vrancea', 0),
(3175, 176, 'AB', 'Abakan', 0),
(3176, 176, 'AG', 'Aginskoye', 0),
(3177, 176, 'AN', 'Anadyr', 0),
(3178, 176, 'AR', 'Arkahangelsk', 0),
(3179, 176, 'AS', 'Astrakhan', 0),
(3180, 176, 'BA', 'Barnaul', 0),
(3181, 176, 'BE', 'Belgorod', 0),
(3182, 176, 'BI', 'Birobidzhan', 0),
(3183, 176, 'BL', 'Blagoveshchensk', 0),
(3184, 176, 'BR', 'Bryansk', 0),
(3185, 176, 'CH', 'Cheboksary', 0),
(3186, 176, 'CL', 'Chelyabinsk', 0),
(3187, 176, 'CR', 'Cherkessk', 0),
(3188, 176, 'CI', 'Chita', 0),
(3189, 176, 'DU', 'Dudinka', 0),
(3190, 176, 'EL', 'Elista', 0),
(3191, 176, 'GO', 'Gomo-Altaysk', 0),
(3192, 176, 'GA', 'Gorno-Altaysk', 0),
(3193, 176, 'GR', 'Groznyy', 0),
(3194, 176, 'IR', 'Irkutsk', 0),
(3195, 176, 'IV', 'Ivanovo', 0),
(3196, 176, 'IZ', 'Izhevsk', 0),
(3197, 176, 'KA', 'Kalinigrad', 0),
(3198, 176, 'KL', 'Kaluga', 0),
(3199, 176, 'KS', 'Kasnodar', 0),
(3200, 176, 'KZ', 'Kazan', 0),
(3201, 176, 'KE', 'Kemerovo', 0),
(3202, 176, 'KH', 'Khabarovsk', 0),
(3203, 176, 'KM', 'Khanty-Mansiysk', 0),
(3204, 176, 'KO', 'Kostroma', 0),
(3205, 176, 'KR', 'Krasnodar', 0),
(3206, 176, 'KN', 'Krasnoyarsk', 0),
(3207, 176, 'KU', 'Kudymkar', 0),
(3208, 176, 'KG', 'Kurgan', 0),
(3209, 176, 'KK', 'Kursk', 0),
(3210, 176, 'KY', 'Kyzyl', 0),
(3211, 176, 'LI', 'Lipetsk', 0),
(3212, 176, 'MA', 'Magadan', 0),
(3213, 176, 'MK', 'Makhachkala', 0),
(3214, 176, 'MY', 'Maykop', 0),
(3215, 176, 'MO', 'Moscow', 0),
(3216, 176, 'MU', 'Murmansk', 0),
(3217, 176, 'NA', 'Nalchik', 0),
(3218, 176, 'NR', 'Naryan Mar', 0),
(3219, 176, 'NZ', 'Nazran', 0),
(3220, 176, 'NI', 'Nizhniy Novgorod', 0),
(3221, 176, 'NO', 'Novgorod', 0),
(3222, 176, 'NV', 'Novosibirsk', 0),
(3223, 176, 'OM', 'Omsk', 0),
(3224, 176, 'OR', 'Orel', 0),
(3225, 176, 'OE', 'Orenburg', 0),
(3226, 176, 'PA', 'Palana', 0),
(3227, 176, 'PE', 'Penza', 0),
(3228, 176, 'PR', 'Perm', 0),
(3229, 176, 'PK', 'Petropavlovsk-Kamchatskiy', 0),
(3230, 176, 'PT', 'Petrozavodsk', 0),
(3231, 176, 'PS', 'Pskov', 0),
(3232, 176, 'RO', 'Rostov-na-Donu', 0),
(3233, 176, 'RY', 'Ryazan', 0),
(3234, 176, 'SL', 'Salekhard', 0),
(3235, 176, 'SA', 'Samara', 0),
(3236, 176, 'SR', 'Saransk', 0),
(3237, 176, 'SV', 'Saratov', 0),
(3238, 176, 'SM', 'Smolensk', 0),
(3239, 176, 'SP', 'St. Petersburg', 0),
(3240, 176, 'ST', 'Stavropol', 0),
(3241, 176, 'SY', 'Syktyvkar', 0),
(3242, 176, 'TA', 'Tambov', 0),
(3243, 176, 'TO', 'Tomsk', 0),
(3244, 176, 'TU', 'Tula', 0),
(3245, 176, 'TR', 'Tura', 0),
(3246, 176, 'TV', 'Tver', 0),
(3247, 176, 'TY', 'Tyumen', 0),
(3248, 176, 'UF', 'Ufa', 0),
(3249, 176, 'UL', 'Ul&apos;yanovsk', 0),
(3250, 176, 'UU', 'Ulan-Ude', 0),
(3251, 176, 'US', 'Ust&apos;-Ordynskiy', 0),
(3252, 176, 'VL', 'Vladikavkaz', 0),
(3253, 176, 'VA', 'Vladimir', 0),
(3254, 176, 'VV', 'Vladivostok', 0),
(3255, 176, 'VG', 'Volgograd', 0),
(3256, 176, 'VD', 'Vologda', 0),
(3257, 176, 'VO', 'Voronezh', 0),
(3258, 176, 'VY', 'Vyatka', 0),
(3259, 176, 'YA', 'Yakutsk', 0),
(3260, 176, 'YR', 'Yaroslavl', 0),
(3261, 176, 'YE', 'Yekaterinburg', 0),
(3262, 176, 'YO', 'Yoshkar-Ola', 0),
(3263, 177, 'BU', 'Butare', 0),
(3264, 177, 'BY', 'Byumba', 0),
(3265, 177, 'CY', 'Cyangugu', 0),
(3266, 177, 'GK', 'Gikongoro', 0),
(3267, 177, 'GS', 'Gisenyi', 0),
(3268, 177, 'GT', 'Gitarama', 0),
(3269, 177, 'KG', 'Kibungo', 0),
(3270, 177, 'KY', 'Kibuye', 0),
(3271, 177, 'KR', 'Kigali Rurale', 0),
(3272, 177, 'KV', 'Kigali-ville', 0),
(3273, 177, 'RU', 'Ruhengeri', 0),
(3274, 177, 'UM', 'Umutara', 0),
(3275, 178, 'CCN', 'Christ Church Nichola Town', 0),
(3276, 178, 'SAS', 'Saint Anne Sandy Point', 0),
(3277, 178, 'SGB', 'Saint George Basseterre', 0),
(3278, 178, 'SGG', 'Saint George Gingerland', 0),
(3279, 178, 'SJW', 'Saint James Windward', 0),
(3280, 178, 'SJC', 'Saint John Capesterre', 0),
(3281, 178, 'SJF', 'Saint John Figtree', 0),
(3282, 178, 'SMC', 'Saint Mary Cayon', 0),
(3283, 178, 'CAP', 'Saint Paul Capesterre', 0),
(3284, 178, 'CHA', 'Saint Paul Charlestown', 0),
(3285, 178, 'SPB', 'Saint Peter Basseterre', 0),
(3286, 178, 'STL', 'Saint Thomas Lowland', 0),
(3287, 178, 'STM', 'Saint Thomas Middle Island', 0),
(3288, 178, 'TPP', 'Trinity Palmetto Point', 0),
(3289, 179, 'AR', 'Anse-la-Raye', 0),
(3290, 179, 'CA', 'Castries', 0),
(3291, 179, 'CH', 'Choiseul', 0),
(3292, 179, 'DA', 'Dauphin', 0),
(3293, 179, 'DE', 'Dennery', 0),
(3294, 179, 'GI', 'Gros-Islet', 0),
(3295, 179, 'LA', 'Laborie', 0),
(3296, 179, 'MI', 'Micoud', 0),
(3297, 179, 'PR', 'Praslin', 0),
(3298, 179, 'SO', 'Soufriere', 0),
(3299, 179, 'VF', 'Vieux-Fort', 0),
(3300, 180, 'C', 'Charlotte', 0),
(3301, 180, 'R', 'Grenadines', 0),
(3302, 180, 'A', 'Saint Andrew', 0),
(3303, 180, 'D', 'Saint David', 0),
(3304, 180, 'G', 'Saint George', 0),
(3305, 180, 'P', 'Saint Patrick', 0),
(3306, 181, 'AN', 'A&apos;ana', 0),
(3307, 181, 'AI', 'Aiga-i-le-Tai', 0),
(3308, 181, 'AT', 'Atua', 0),
(3309, 181, 'FA', 'Fa&apos;asaleleaga', 0),
(3310, 181, 'GE', 'Gaga&apos;emauga', 0),
(3311, 181, 'GF', 'Gagaifomauga', 0),
(3312, 181, 'PA', 'Palauli', 0),
(3313, 181, 'SA', 'Satupa&apos;itea', 0),
(3314, 181, 'TU', 'Tuamasaga', 0),
(3315, 181, 'VF', 'Va&apos;a-o-Fonoti', 0),
(3316, 181, 'VS', 'Vaisigano', 0),
(3317, 182, 'AC', 'Acquaviva', 0),
(3318, 182, 'BM', 'Borgo Maggiore', 0),
(3319, 182, 'CH', 'Chiesanuova', 0),
(3320, 182, 'DO', 'Domagnano', 0),
(3321, 182, 'FA', 'Faetano', 0),
(3322, 182, 'FI', 'Fiorentino', 0),
(3323, 182, 'MO', 'Montegiardino', 0),
(3324, 182, 'SM', 'Citta di San Marino', 0),
(3325, 182, 'SE', 'Serravalle', 0),
(3326, 183, 'S', 'Sao Tome', 0),
(3327, 183, 'P', 'Principe', 0),
(3328, 184, 'BH', 'Al Bahah', 0),
(3329, 184, 'HS', 'Al Hudud ash Shamaliyah', 0),
(3330, 184, 'JF', 'Al Jawf', 0),
(3331, 184, 'MD', 'Al Madinah', 0),
(3332, 184, 'QS', 'Al Qasim', 0),
(3333, 184, 'RD', 'Ar Riyad', 0),
(3334, 184, 'AQ', 'Ash Sharqiyah (Eastern)', 0),
(3335, 184, 'AS', 'Asir', 0),
(3336, 184, 'HL', 'Ha&apos;il', 0),
(3337, 184, 'JZ', 'Jizan', 0),
(3338, 184, 'ML', 'Makkah', 0),
(3339, 184, 'NR', 'Najran', 0),
(3340, 184, 'TB', 'Tabuk', 0),
(3341, 185, 'DA', 'Dakar', 0),
(3342, 185, 'DI', 'Diourbel', 0),
(3343, 185, 'FA', 'Fatick', 0),
(3344, 185, 'KA', 'Kaolack', 0),
(3345, 185, 'KO', 'Kolda', 0),
(3346, 185, 'LO', 'Louga', 0),
(3347, 185, 'MA', 'Matam', 0),
(3348, 185, 'SL', 'Saint-Louis', 0),
(3349, 185, 'TA', 'Tambacounda', 0),
(3350, 185, 'TH', 'Thies', 0),
(3351, 185, 'ZI', 'Ziguinchor', 0),
(3352, 186, 'AP', 'Anse aux Pins', 0),
(3353, 186, 'AB', 'Anse Boileau', 0),
(3354, 186, 'AE', 'Anse Etoile', 0),
(3355, 186, 'AL', 'Anse Louis', 0),
(3356, 186, 'AR', 'Anse Royale', 0),
(3357, 186, 'BL', 'Baie Lazare', 0),
(3358, 186, 'BS', 'Baie Sainte Anne', 0),
(3359, 186, 'BV', 'Beau Vallon', 0),
(3360, 186, 'BA', 'Bel Air', 0),
(3361, 186, 'BO', 'Bel Ombre', 0),
(3362, 186, 'CA', 'Cascade', 0),
(3363, 186, 'GL', 'Glacis', 0),
(3364, 186, 'GM', 'Grand Anse (on Mahe)', 0),
(3365, 186, 'GP', 'Grand Anse (on Praslin)', 0),
(3366, 186, 'DG', 'La Digue', 0),
(3367, 186, 'RA', 'La Riviere Anglaise', 0),
(3368, 186, 'MB', 'Mont Buxton', 0),
(3369, 186, 'MF', 'Mont Fleuri', 0),
(3370, 186, 'PL', 'Plaisance', 0),
(3371, 186, 'PR', 'Pointe La Rue', 0),
(3372, 186, 'PG', 'Port Glaud', 0),
(3373, 186, 'SL', 'Saint Louis', 0),
(3374, 186, 'TA', 'Takamaka', 0),
(3375, 187, 'E', 'Eastern', 0),
(3376, 187, 'N', 'Northern', 0),
(3377, 187, 'S', 'Southern', 0),
(3378, 187, 'W', 'Western', 0),
(3379, 189, 'BA', 'Banskobystricky', 0),
(3380, 189, 'BR', 'Bratislavsky', 0),
(3381, 189, 'KO', 'Kosicky', 0),
(3382, 189, 'NI', 'Nitriansky', 0),
(3383, 189, 'PR', 'Presovsky', 0),
(3384, 189, 'TC', 'Trenciansky', 0),
(3385, 189, 'TV', 'Trnavsky', 0),
(3386, 189, 'ZI', 'Zilinsky', 0),
(3387, 190, '4', 'tajerska', 0),
(3388, 190, '2A', 'Gorenjska', 0),
(3389, 190, '5', 'Prekmurje', 0),
(3390, 190, '3', 'Koroka', 0),
(3391, 190, '2B', 'Notranjska', 0),
(3392, 190, '1', 'Primorska', 0),
(3393, 190, '2C', 'Dolenjska', 0),
(3394, 190, '2C', 'Bela Krajina', 0),
(3395, 191, 'CE', 'Central', 0),
(3396, 191, 'CH', 'Choiseul', 0),
(3397, 191, 'GC', 'Guadalcanal', 0),
(3398, 191, 'HO', 'Honiara', 0),
(3399, 191, 'IS', 'Isabel', 0),
(3400, 191, 'MK', 'Makira', 0),
(3401, 191, 'ML', 'Malaita', 0),
(3402, 191, 'RB', 'Rennell and Bellona', 0),
(3403, 191, 'TM', 'Temotu', 0),
(3404, 191, 'WE', 'Western', 0),
(3405, 192, 'AW', 'Awdal', 0),
(3406, 192, 'BK', 'Bakool', 0),
(3407, 192, 'BN', 'Banaadir', 0),
(3408, 192, 'BR', 'Bari', 0),
(3409, 192, 'BY', 'Bay', 0),
(3410, 192, 'GA', 'Galguduud', 0),
(3411, 192, 'GE', 'Gedo', 0),
(3412, 192, 'HI', 'Hiiraan', 0),
(3413, 192, 'JD', 'Jubbada Dhexe', 0),
(3414, 192, 'JH', 'Jubbada Hoose', 0),
(3415, 192, 'MU', 'Mudug', 0),
(3416, 192, 'NU', 'Nugaal', 0),
(3417, 192, 'SA', 'Sanaag', 0),
(3418, 192, 'SD', 'Shabeellaha Dhexe', 0),
(3419, 192, 'SH', 'Shabeellaha Hoose', 0),
(3420, 192, 'SL', 'Sool', 0),
(3421, 192, 'TO', 'Togdheer', 0),
(3422, 192, 'WG', 'Woqooyi Galbeed', 0),
(3423, 193, 'EC', 'Eastern Cape', 0),
(3424, 193, 'FS', 'Free State', 0),
(3425, 193, 'GT', 'Gauteng', 0),
(3426, 193, 'KN', 'KwaZulu-Natal', 0),
(3427, 193, 'LP', 'Limpopo', 0),
(3428, 193, 'MP', 'Mpumalanga', 0),
(3429, 193, 'NW', 'North West', 0),
(3430, 193, 'NC', 'Northern Cape', 0),
(3431, 193, 'WC', 'Western Cape', 0),
(3432, 195, 'CA', 'La Coruña', 0),
(3433, 195, 'AL', 'Álava', 0),
(3434, 195, 'AB', 'Albacete', 0),
(3435, 195, 'AC', 'Alicante', 0),
(3436, 195, 'AM', 'Almeria', 0),
(3437, 195, 'AS', 'Asturias', 0),
(3438, 195, 'AV', 'Ávila', 0),
(3439, 195, 'BJ', 'Badajoz', 0),
(3440, 195, 'IB', 'Baleares', 0),
(3441, 195, 'BA', 'Barcelona', 0),
(3442, 195, 'BU', 'Burgos', 0),
(3443, 195, 'CC', 'Cáceres', 0),
(3444, 195, 'CZ', 'Cádiz', 0),
(3445, 195, 'CT', 'Cantabria', 0),
(3446, 195, 'CL', 'Castellón', 0),
(3447, 195, 'CE', 'Ceuta', 0),
(3448, 195, 'CR', 'Ciudad Real', 0),
(3449, 195, 'CD', 'Córdoba', 0),
(3450, 195, 'CU', 'Cuenca', 0),
(3451, 195, 'GI', 'Gerona', 0),
(3452, 195, 'GD', 'Granada', 0),
(3453, 195, 'GJ', 'Guadalajara', 0),
(3454, 195, 'GP', 'Guipúzcoa', 0),
(3455, 195, 'HL', 'Huelva', 0),
(3456, 195, 'HS', 'Huesca', 0),
(3457, 195, 'JN', 'Jaén', 0),
(3458, 195, 'RJ', 'La Rioja', 0),
(3459, 195, 'PM', 'Las Palmas', 0),
(3460, 195, 'LE', 'León', 0),
(3461, 195, 'LL', 'Lérida', 0),
(3462, 195, 'LG', 'Lugo', 0),
(3463, 195, 'MD', 'Madrid', 0),
(3464, 195, 'MA', 'Málaga', 0),
(3465, 195, 'ML', 'Melilla', 0),
(3466, 195, 'MU', 'Murcia', 0),
(3467, 195, 'NV', 'Navarra', 0),
(3468, 195, 'OU', 'Ourense', 0),
(3469, 195, 'PL', 'Palencia', 0),
(3470, 195, 'PO', 'Pontevedra', 0),
(3471, 195, 'SL', 'Salamanca', 0),
(3472, 195, 'SC', 'Santa Cruz de Tenerife', 0),
(3473, 195, 'SG', 'Segovia', 0),
(3474, 195, 'SV', 'Sevilla', 0),
(3475, 195, 'SO', 'Soria', 0),
(3476, 195, 'TA', 'Tarragona', 0),
(3477, 195, 'TE', 'Teruel', 0),
(3478, 195, 'TO', 'Toledo', 0),
(3479, 195, 'VC', 'Valencia', 0),
(3480, 195, 'VD', 'Valladolid', 0),
(3481, 195, 'VZ', 'Vizcaya', 0),
(3482, 195, 'ZM', 'Zamora', 0),
(3483, 195, 'ZR', 'Zaragoza', 0),
(3484, 196, 'CE', 'Central', 0),
(3485, 196, 'EA', 'Eastern', 0),
(3486, 196, 'NC', 'North Central', 0),
(3487, 196, 'NO', 'Northern', 0),
(3488, 196, 'NW', 'North Western', 0),
(3489, 196, 'SA', 'Sabaragamuwa', 0),
(3490, 196, 'SO', 'Southern', 0),
(3491, 196, 'UV', 'Uva', 0),
(3492, 196, 'WE', 'Western', 0),
(3493, 197, 'A', 'Ascension', 0),
(3494, 197, 'S', 'Saint Helena', 0),
(3495, 197, 'T', 'Tristan da Cunha', 0),
(3496, 198, 'P', 'Saint Pierre', 0),
(3497, 198, 'M', 'Miquelon', 0),
(3498, 199, 'ANL', 'A&apos;ali an Nil', 0),
(3499, 199, 'BAM', 'Al Bahr al Ahmar', 0),
(3500, 199, 'BRT', 'Al Buhayrat', 0),
(3501, 199, 'JZR', 'Al Jazirah', 0),
(3502, 199, 'KRT', 'Al Khartum', 0),
(3503, 199, 'QDR', 'Al Qadarif', 0),
(3504, 199, 'WDH', 'Al Wahdah', 0),
(3505, 199, 'ANB', 'An Nil al Abyad', 0),
(3506, 199, 'ANZ', 'An Nil al Azraq', 0),
(3507, 199, 'ASH', 'Ash Shamaliyah', 0),
(3508, 199, 'BJA', 'Bahr al Jabal', 0),
(3509, 199, 'GIS', 'Gharb al Istiwa&apos;iyah', 0),
(3510, 199, 'GBG', 'Gharb Bahr al Ghazal', 0),
(3511, 199, 'GDA', 'Gharb Darfur', 0),
(3512, 199, 'GKU', 'Gharb Kurdufan', 0),
(3513, 199, 'JDA', 'Janub Darfur', 0),
(3514, 199, 'JKU', 'Janub Kurdufan', 0),
(3515, 199, 'JQL', 'Junqali', 0),
(3516, 199, 'KSL', 'Kassala', 0),
(3517, 199, 'NNL', 'Nahr an Nil', 0),
(3518, 199, 'SBG', 'Shamal Bahr al Ghazal', 0),
(3519, 199, 'SDA', 'Shamal Darfur', 0),
(3520, 199, 'SKU', 'Shamal Kurdufan', 0),
(3521, 199, 'SIS', 'Sharq al Istiwa&apos;iyah', 0),
(3522, 199, 'SNR', 'Sinnar', 0),
(3523, 199, 'WRB', 'Warab', 0),
(3524, 200, 'BR', 'Brokopondo', 0),
(3525, 200, 'CM', 'Commewijne', 0),
(3526, 200, 'CR', 'Coronie', 0),
(3527, 200, 'MA', 'Marowijne', 0),
(3528, 200, 'NI', 'Nickerie', 0),
(3529, 200, 'PA', 'Para', 0),
(3530, 200, 'PM', 'Paramaribo', 0),
(3531, 200, 'SA', 'Saramacca', 0),
(3532, 200, 'SI', 'Sipaliwini', 0),
(3533, 200, 'WA', 'Wanica', 0),
(3534, 202, 'H', 'Hhohho', 0),
(3535, 202, 'L', 'Lubombo', 0),
(3536, 202, 'M', 'Manzini', 0),
(3537, 202, 'S', 'Shishelweni', 0),
(3538, 203, 'K', 'Blekinge', 0),
(3539, 203, 'W', 'Dalama', 0),
(3540, 203, 'I', 'Gotland', 0),
(3541, 203, 'X', 'G&auml;vleborg', 0),
(3542, 203, 'N', 'Halland', 0),
(3543, 203, 'Z', 'J&auml;mtland', 0),
(3544, 203, 'F', 'J&ouml;nk&ouml;ping', 0),
(3545, 203, 'H', 'Kalmar', 0),
(3546, 203, 'G', 'Kronoberg', 0),
(3547, 203, 'BD', 'Norrbotten', 0),
(3548, 203, 'M', 'Sk&aring;ne', 0),
(3549, 203, 'AB', 'Stockholm', 0),
(3550, 203, 'D', 'S&ouml;dermanland', 0),
(3551, 203, 'C', 'Uppsala', 0),
(3552, 203, 'S', 'V&auml;rmland', 0),
(3553, 203, 'AC', 'V&auml;sterbotten', 0),
(3554, 203, 'Y', 'V&auml;sternorrland', 0),
(3555, 203, 'U', 'V&auml;stmanland', 0),
(3556, 203, 'O', 'V&auml;stra G&ouml;taland', 0),
(3557, 203, 'T', '&Ouml;rebro', 0),
(3558, 203, 'E', '&Ouml;sterg&ouml;tland', 0),
(3559, 204, 'AG', 'Aargau', 0),
(3560, 204, 'AR', 'Appenzell Ausserrhoden', 0),
(3561, 204, 'AI', 'Appenzell Innerrhoden', 0),
(3562, 204, 'BS', 'Basel-Stadt', 0),
(3563, 204, 'BL', 'Basel-Landschaft', 0),
(3564, 204, 'BE', 'Bern', 0),
(3565, 204, 'FR', 'Fribourg', 0),
(3566, 204, 'GE', 'Geneva', 0),
(3567, 204, 'GL', 'Glarus', 0),
(3568, 204, 'GR', 'Graub', 0),
(3569, 204, 'JU', 'Jura', 0),
(3570, 204, 'LU', 'Lucerne', 0),
(3571, 204, 'NE', 'Neuch', 0),
(3572, 204, 'NW', 'Nidwalden', 0),
(3573, 204, 'OW', 'Obwalden', 0),
(3574, 204, 'SG', 'St. Gallen', 0),
(3575, 204, 'SH', 'Schaffhausen', 0),
(3576, 204, 'SZ', 'Schwyz', 0),
(3577, 204, 'SO', 'Solothurn', 0),
(3578, 204, 'TG', 'Thurgau', 0),
(3579, 204, 'TI', 'Ticino', 0),
(3580, 204, 'UR', 'Uri', 0),
(3581, 204, 'VS', 'Valais', 0),
(3582, 204, 'VD', 'Vaud', 0),
(3583, 204, 'ZG', 'Zug', 0),
(3584, 204, 'ZH', 'Z', 0),
(3585, 205, 'HA', 'Al Hasakah', 0),
(3586, 205, 'LA', 'Al Ladhiqiyah', 0),
(3587, 205, 'QU', 'Al Qunaytirah', 0),
(3588, 205, 'RQ', 'Ar Raqqah', 0),
(3589, 205, 'SU', 'As Suwayda', 0),
(3590, 205, 'DA', 'Dara', 0),
(3591, 205, 'DZ', 'Dayr az Zawr', 0),
(3592, 205, 'DI', 'Dimashq', 0),
(3593, 205, 'HL', 'Halab', 0),
(3594, 205, 'HM', 'Hamah', 0),
(3595, 205, 'HI', 'Hims', 0),
(3596, 205, 'ID', 'Idlib', 0),
(3597, 205, 'RD', 'Rif Dimashq', 0),
(3598, 205, 'TA', 'Tartus', 0),
(3599, 206, 'CH', 'Chang-hua', 0),
(3600, 206, 'CI', 'Chia-i', 0),
(3601, 206, 'HS', 'Hsin-chu', 0),
(3602, 206, 'HL', 'Hua-lien', 0),
(3603, 206, 'IL', 'I-lan', 0),
(3604, 206, 'KH', 'Kao-hsiung county', 0),
(3605, 206, 'KM', 'Kin-men', 0),
(3606, 206, 'LC', 'Lien-chiang', 0),
(3607, 206, 'ML', 'Miao-li', 0),
(3608, 206, 'NT', 'Nan-t&apos;ou', 0),
(3609, 206, 'PH', 'P&apos;eng-hu', 0),
(3610, 206, 'PT', 'P&apos;ing-tung', 0),
(3611, 206, 'TG', 'T&apos;ai-chung', 0),
(3612, 206, 'TA', 'T&apos;ai-nan', 0),
(3613, 206, 'TP', 'T&apos;ai-pei county', 0),
(3614, 206, 'TT', 'T&apos;ai-tung', 0),
(3615, 206, 'TY', 'T&apos;ao-yuan', 0),
(3616, 206, 'YL', 'Yun-lin', 0),
(3617, 206, 'CC', 'Chia-i city', 0),
(3618, 206, 'CL', 'Chi-lung', 0),
(3619, 206, 'HC', 'Hsin-chu', 0),
(3620, 206, 'TH', 'T&apos;ai-chung', 0),
(3621, 206, 'TN', 'T&apos;ai-nan', 0),
(3622, 206, 'KC', 'Kao-hsiung city', 0),
(3623, 206, 'TC', 'T&apos;ai-pei city', 0),
(3624, 207, 'GB', 'Gorno-Badakhstan', 0),
(3625, 207, 'KT', 'Khatlon', 0),
(3626, 207, 'SU', 'Sughd', 0),
(3627, 208, 'AR', 'Arusha', 0),
(3628, 208, 'DS', 'Dar es Salaam', 0),
(3629, 208, 'DO', 'Dodoma', 0),
(3630, 208, 'IR', 'Iringa', 0),
(3631, 208, 'KA', 'Kagera', 0),
(3632, 208, 'KI', 'Kigoma', 0),
(3633, 208, 'KJ', 'Kilimanjaro', 0),
(3634, 208, 'LN', 'Lindi', 0),
(3635, 208, 'MY', 'Manyara', 0),
(3636, 208, 'MR', 'Mara', 0),
(3637, 208, 'MB', 'Mbeya', 0),
(3638, 208, 'MO', 'Morogoro', 0),
(3639, 208, 'MT', 'Mtwara', 0),
(3640, 208, 'MW', 'Mwanza', 0),
(3641, 208, 'PN', 'Pemba North', 0),
(3642, 208, 'PS', 'Pemba South', 0),
(3643, 208, 'PW', 'Pwani', 0),
(3644, 208, 'RK', 'Rukwa', 0),
(3645, 208, 'RV', 'Ruvuma', 0),
(3646, 208, 'SH', 'Shinyanga', 0),
(3647, 208, 'SI', 'Singida', 0),
(3648, 208, 'TB', 'Tabora', 0),
(3649, 208, 'TN', 'Tanga', 0),
(3650, 208, 'ZC', 'Zanzibar Central/South', 0),
(3651, 208, 'ZN', 'Zanzibar North', 0),
(3652, 208, 'ZU', 'Zanzibar Urban/West', 0),
(3653, 209, 'Amnat Charoen', 'Amnat Charoen', 0),
(3654, 209, 'Ang Thong', 'Ang Thong', 0),
(3655, 209, 'Ayutthaya', 'Ayutthaya', 0),
(3656, 209, 'Bangkok', 'Bangkok', 0),
(3657, 209, 'Buriram', 'Buriram', 0),
(3658, 209, 'Chachoengsao', 'Chachoengsao', 0),
(3659, 209, 'Chai Nat', 'Chai Nat', 0),
(3660, 209, 'Chaiyaphum', 'Chaiyaphum', 0),
(3661, 209, 'Chanthaburi', 'Chanthaburi', 0),
(3662, 209, 'Chiang Mai', 'Chiang Mai', 0),
(3663, 209, 'Chiang Rai', 'Chiang Rai', 0),
(3664, 209, 'Chon Buri', 'Chon Buri', 0),
(3665, 209, 'Chumphon', 'Chumphon', 0),
(3666, 209, 'Kalasin', 'Kalasin', 0),
(3667, 209, 'Kamphaeng Phet', 'Kamphaeng Phet', 0),
(3668, 209, 'Kanchanaburi', 'Kanchanaburi', 0),
(3669, 209, 'Khon Kaen', 'Khon Kaen', 0),
(3670, 209, 'Krabi', 'Krabi', 0),
(3671, 209, 'Lampang', 'Lampang', 0),
(3672, 209, 'Lamphun', 'Lamphun', 0),
(3673, 209, 'Loei', 'Loei', 0),
(3674, 209, 'Lop Buri', 'Lop Buri', 0),
(3675, 209, 'Mae Hong Son', 'Mae Hong Son', 0),
(3676, 209, 'Maha Sarakham', 'Maha Sarakham', 0),
(3677, 209, 'Mukdahan', 'Mukdahan', 0),
(3678, 209, 'Nakhon Nayok', 'Nakhon Nayok', 0),
(3679, 209, 'Nakhon Pathom', 'Nakhon Pathom', 0),
(3680, 209, 'Nakhon Phanom', 'Nakhon Phanom', 0),
(3681, 209, 'Nakhon Ratchasima', 'Nakhon Ratchasima', 0),
(3682, 209, 'Nakhon Sawan', 'Nakhon Sawan', 0),
(3683, 209, 'Nakhon Si Thammarat', 'Nakhon Si Thammarat', 0),
(3684, 209, 'Nan', 'Nan', 0),
(3685, 209, 'Narathiwat', 'Narathiwat', 0),
(3686, 209, 'Nong Bua Lamphu', 'Nong Bua Lamphu', 0),
(3687, 209, 'Nong Khai', 'Nong Khai', 0),
(3688, 209, 'Nonthaburi', 'Nonthaburi', 0),
(3689, 209, 'Pathum Thani', 'Pathum Thani', 0),
(3690, 209, 'Pattani', 'Pattani', 0),
(3691, 209, 'Phangnga', 'Phangnga', 0),
(3692, 209, 'Phatthalung', 'Phatthalung', 0),
(3693, 209, 'Phayao', 'Phayao', 0),
(3694, 209, 'Phetchabun', 'Phetchabun', 0),
(3695, 209, 'Phetchaburi', 'Phetchaburi', 0),
(3696, 209, 'Phichit', 'Phichit', 0),
(3697, 209, 'Phitsanulok', 'Phitsanulok', 0),
(3698, 209, 'Phrae', 'Phrae', 0),
(3699, 209, 'Phuket', 'Phuket', 0),
(3700, 209, 'Prachin Buri', 'Prachin Buri', 0),
(3701, 209, 'Prachuap Khiri Khan', 'Prachuap Khiri Khan', 0),
(3702, 209, 'Ranong', 'Ranong', 0),
(3703, 209, 'Ratchaburi', 'Ratchaburi', 0),
(3704, 209, 'Rayong', 'Rayong', 0),
(3705, 209, 'Roi Et', 'Roi Et', 0),
(3706, 209, 'Sa Kaeo', 'Sa Kaeo', 0),
(3707, 209, 'Sakon Nakhon', 'Sakon Nakhon', 0),
(3708, 209, 'Samut Prakan', 'Samut Prakan', 0),
(3709, 209, 'Samut Sakhon', 'Samut Sakhon', 0),
(3710, 209, 'Samut Songkhram', 'Samut Songkhram', 0),
(3711, 209, 'Sara Buri', 'Sara Buri', 0),
(3712, 209, 'Satun', 'Satun', 0),
(3713, 209, 'Sing Buri', 'Sing Buri', 0),
(3714, 209, 'Sisaket', 'Sisaket', 0),
(3715, 209, 'Songkhla', 'Songkhla', 0),
(3716, 209, 'Sukhothai', 'Sukhothai', 0),
(3717, 209, 'Suphan Buri', 'Suphan Buri', 0),
(3718, 209, 'Surat Thani', 'Surat Thani', 0),
(3719, 209, 'Surin', 'Surin', 0),
(3720, 209, 'Tak', 'Tak', 0),
(3721, 209, 'Trang', 'Trang', 0),
(3722, 209, 'Trat', 'Trat', 0),
(3723, 209, 'Ubon Ratchathani', 'Ubon Ratchathani', 0),
(3724, 209, 'Udon Thani', 'Udon Thani', 0),
(3725, 209, 'Uthai Thani', 'Uthai Thani', 0),
(3726, 209, 'Uttaradit', 'Uttaradit', 0),
(3727, 209, 'Yala', 'Yala', 0),
(3728, 209, 'Yasothon', 'Yasothon', 0),
(3729, 210, 'K', 'Kara', 0),
(3730, 210, 'P', 'Plateaux', 0),
(3731, 210, 'S', 'Savanes', 0),
(3732, 210, 'C', 'Centrale', 0),
(3733, 210, 'M', 'Maritime', 0),
(3734, 211, 'A', 'Atafu', 0),
(3735, 211, 'F', 'Fakaofo', 0),
(3736, 211, 'N', 'Nukunonu', 0),
(3737, 212, 'H', 'Ha&apos;apai', 0),
(3738, 212, 'T', 'Tongatapu', 0),
(3739, 212, 'V', 'Vava&apos;u', 0),
(3740, 213, 'CT', 'Couva/Tabaquite/Talparo', 0),
(3741, 213, 'DM', 'Diego Martin', 0),
(3742, 213, 'MR', 'Mayaro/Rio Claro', 0),
(3743, 213, 'PD', 'Penal/Debe', 0),
(3744, 213, 'PT', 'Princes Town', 0),
(3745, 213, 'SG', 'Sangre Grande', 0),
(3746, 213, 'SL', 'San Juan/Laventille', 0),
(3747, 213, 'SI', 'Siparia', 0),
(3748, 213, 'TP', 'Tunapuna/Piarco', 0),
(3749, 213, 'PS', 'Port of Spain', 0),
(3750, 213, 'SF', 'San Fernando', 0),
(3751, 213, 'AR', 'Arima', 0),
(3752, 213, 'PF', 'Point Fortin', 0),
(3753, 213, 'CH', 'Chaguanas', 0),
(3754, 213, 'TO', 'Tobago', 0),
(3755, 214, 'AR', 'Ariana', 0),
(3756, 214, 'BJ', 'Beja', 0),
(3757, 214, 'BA', 'Ben Arous', 0),
(3758, 214, 'BI', 'Bizerte', 0),
(3759, 214, 'GB', 'Gabes', 0),
(3760, 214, 'GF', 'Gafsa', 0),
(3761, 214, 'JE', 'Jendouba', 0),
(3762, 214, 'KR', 'Kairouan', 0),
(3763, 214, 'KS', 'Kasserine', 0),
(3764, 214, 'KB', 'Kebili', 0),
(3765, 214, 'KF', 'Kef', 0),
(3766, 214, 'MH', 'Mahdia', 0),
(3767, 214, 'MN', 'Manouba', 0),
(3768, 214, 'ME', 'Medenine', 0),
(3769, 214, 'MO', 'Monastir', 0),
(3770, 214, 'NA', 'Nabeul', 0),
(3771, 214, 'SF', 'Sfax', 0),
(3772, 214, 'SD', 'Sidi', 0),
(3773, 214, 'SL', 'Siliana', 0),
(3774, 214, 'SO', 'Sousse', 0),
(3775, 214, 'TA', 'Tataouine', 0),
(3776, 214, 'TO', 'Tozeur', 0),
(3777, 214, 'TU', 'Tunis', 0),
(3778, 214, 'ZA', 'Zaghouan', 0),
(3779, 215, 'ADA', 'Adana', 0),
(3780, 215, 'ADI', 'Adiyaman', 0),
(3781, 215, 'AFY', 'Afyonkarahisar', 0),
(3782, 215, 'AGR', 'Agri', 0),
(3783, 215, 'AKS', 'Aksaray', 0),
(3784, 215, 'AMA', 'Amasya', 0),
(3785, 215, 'ANK', 'Ankara', 0),
(3786, 215, 'ANT', 'Antalya', 0),
(3787, 215, 'ARD', 'Ardahan', 0),
(3788, 215, 'ART', 'Artvin', 0),
(3789, 215, 'AYI', 'Aydin', 0),
(3790, 215, 'BAL', 'Balikesir', 0),
(3791, 215, 'BAR', 'Bartin', 0),
(3792, 215, 'BAT', 'Batman', 0),
(3793, 215, 'BAY', 'Bayburt', 0),
(3794, 215, 'BIL', 'Bilecik', 0),
(3795, 215, 'BIN', 'Bingol', 0),
(3796, 215, 'BIT', 'Bitlis', 0),
(3797, 215, 'BOL', 'Bolu', 0),
(3798, 215, 'BRD', 'Burdur', 0),
(3799, 215, 'BRS', 'Bursa', 0),
(3800, 215, 'CKL', 'Canakkale', 0),
(3801, 215, 'CKR', 'Cankiri', 0),
(3802, 215, 'COR', 'Corum', 0),
(3803, 215, 'DEN', 'Denizli', 0),
(3804, 215, 'DIY', 'Diyarbakir', 0),
(3805, 215, 'DUZ', 'Duzce', 0),
(3806, 215, 'EDI', 'Edirne', 0),
(3807, 215, 'ELA', 'Elazig', 0),
(3808, 215, 'EZC', 'Erzincan', 0),
(3809, 215, 'EZR', 'Erzurum', 0),
(3810, 215, 'ESK', 'Eskisehir', 0),
(3811, 215, 'GAZ', 'Gaziantep', 0),
(3812, 215, 'GIR', 'Giresun', 0),
(3813, 215, 'GMS', 'Gumushane', 0),
(3814, 215, 'HKR', 'Hakkari', 0),
(3815, 215, 'HTY', 'Hatay', 0),
(3816, 215, 'IGD', 'Igdir', 0),
(3817, 215, 'ISP', 'Isparta', 0),
(3818, 215, 'IST', 'Istanbul', 0),
(3819, 215, 'IZM', 'Izmir', 0),
(3820, 215, 'KAH', 'Kahramanmaras', 0),
(3821, 215, 'KRB', 'Karabuk', 0),
(3822, 215, 'KRM', 'Karaman', 0),
(3823, 215, 'KRS', 'Kars', 0),
(3824, 215, 'KAS', 'Kastamonu', 0),
(3825, 215, 'KAY', 'Kayseri', 0),
(3826, 215, 'KLS', 'Kilis', 0),
(3827, 215, 'KRK', 'Kirikkale', 0),
(3828, 215, 'KLR', 'Kirklareli', 0),
(3829, 215, 'KRH', 'Kirsehir', 0),
(3830, 215, 'KOC', 'Kocaeli', 0),
(3831, 215, 'KON', 'Konya', 0),
(3832, 215, 'KUT', 'Kutahya', 0),
(3833, 215, 'MAL', 'Malatya', 0),
(3834, 215, 'MAN', 'Manisa', 0),
(3835, 215, 'MAR', 'Mardin', 0),
(3836, 215, 'MER', 'Mersin', 0),
(3837, 215, 'MUG', 'Mugla', 0),
(3838, 215, 'MUS', 'Mus', 0),
(3839, 215, 'NEV', 'Nevsehir', 0),
(3840, 215, 'NIG', 'Nigde', 0),
(3841, 215, 'ORD', 'Ordu', 0),
(3842, 215, 'OSM', 'Osmaniye', 0),
(3843, 215, 'RIZ', 'Rize', 0),
(3844, 215, 'SAK', 'Sakarya', 0),
(3845, 215, 'SAM', 'Samsun', 0),
(3846, 215, 'SAN', 'Sanliurfa', 0),
(3847, 215, 'SII', 'Siirt', 0),
(3848, 215, 'SIN', 'Sinop', 0),
(3849, 215, 'SIR', 'Sirnak', 0),
(3850, 215, 'SIV', 'Sivas', 0),
(3851, 215, 'TEL', 'Tekirdag', 0),
(3852, 215, 'TOK', 'Tokat', 0),
(3853, 215, 'TRA', 'Trabzon', 0),
(3854, 215, 'TUN', 'Tunceli', 0),
(3855, 215, 'USK', 'Usak', 0),
(3856, 215, 'VAN', 'Van', 0),
(3857, 215, 'YAL', 'Yalova', 0),
(3858, 215, 'YOZ', 'Yozgat', 0),
(3859, 215, 'ZON', 'Zonguldak', 0),
(3860, 216, 'A', 'Ahal Welayaty', 0),
(3861, 216, 'B', 'Balkan Welayaty', 0),
(3862, 216, 'D', 'Dashhowuz Welayaty', 0),
(3863, 216, 'L', 'Lebap Welayaty', 0),
(3864, 216, 'M', 'Mary Welayaty', 0),
(3865, 217, 'AC', 'Ambergris Cays', 0),
(3866, 217, 'DC', 'Dellis Cay', 0),
(3867, 217, 'FC', 'French Cay', 0),
(3868, 217, 'LW', 'Little Water Cay', 0),
(3869, 217, 'RC', 'Parrot Cay', 0),
(3870, 217, 'PN', 'Pine Cay', 0),
(3871, 217, 'SL', 'Salt Cay', 0),
(3872, 217, 'GT', 'Grand Turk', 0),
(3873, 217, 'SC', 'South Caicos', 0),
(3874, 217, 'EC', 'East Caicos', 0),
(3875, 217, 'MC', 'Middle Caicos', 0),
(3876, 217, 'NC', 'North Caicos', 0),
(3877, 217, 'PR', 'Providenciales', 0),
(3878, 217, 'WC', 'West Caicos', 0),
(3879, 218, 'NMG', 'Nanumanga', 0),
(3880, 218, 'NLK', 'Niulakita', 0),
(3881, 218, 'NTO', 'Niutao', 0),
(3882, 218, 'FUN', 'Funafuti', 0),
(3883, 218, 'NME', 'Nanumea', 0),
(3884, 218, 'NUI', 'Nui', 0),
(3885, 218, 'NFT', 'Nukufetau', 0),
(3886, 218, 'NLL', 'Nukulaelae', 0),
(3887, 218, 'VAI', 'Vaitupu', 0),
(3888, 219, 'KAL', 'Kalangala', 0),
(3889, 219, 'KMP', 'Kampala', 0),
(3890, 219, 'KAY', 'Kayunga', 0),
(3891, 219, 'KIB', 'Kiboga', 0),
(3892, 219, 'LUW', 'Luwero', 0),
(3893, 219, 'MAS', 'Masaka', 0),
(3894, 219, 'MPI', 'Mpigi', 0),
(3895, 219, 'MUB', 'Mubende', 0),
(3896, 219, 'MUK', 'Mukono', 0),
(3897, 219, 'NKS', 'Nakasongola', 0),
(3898, 219, 'RAK', 'Rakai', 0),
(3899, 219, 'SEM', 'Sembabule', 0),
(3900, 219, 'WAK', 'Wakiso', 0),
(3901, 219, 'BUG', 'Bugiri', 0),
(3902, 219, 'BUS', 'Busia', 0),
(3903, 219, 'IGA', 'Iganga', 0),
(3904, 219, 'JIN', 'Jinja', 0),
(3905, 219, 'KAB', 'Kaberamaido', 0),
(3906, 219, 'KML', 'Kamuli', 0),
(3907, 219, 'KPC', 'Kapchorwa', 0),
(3908, 219, 'KTK', 'Katakwi', 0),
(3909, 219, 'KUM', 'Kumi', 0),
(3910, 219, 'MAY', 'Mayuge', 0),
(3911, 219, 'MBA', 'Mbale', 0),
(3912, 219, 'PAL', 'Pallisa', 0),
(3913, 219, 'SIR', 'Sironko', 0),
(3914, 219, 'SOR', 'Soroti', 0),
(3915, 219, 'TOR', 'Tororo', 0),
(3916, 219, 'ADJ', 'Adjumani', 0),
(3917, 219, 'APC', 'Apac', 0),
(3918, 219, 'ARU', 'Arua', 0),
(3919, 219, 'GUL', 'Gulu', 0),
(3920, 219, 'KIT', 'Kitgum', 0),
(3921, 219, 'KOT', 'Kotido', 0),
(3922, 219, 'LIR', 'Lira', 0),
(3923, 219, 'MRT', 'Moroto', 0),
(3924, 219, 'MOY', 'Moyo', 0),
(3925, 219, 'NAK', 'Nakapiripirit', 0),
(3926, 219, 'NEB', 'Nebbi', 0),
(3927, 219, 'PAD', 'Pader', 0),
(3928, 219, 'YUM', 'Yumbe', 0),
(3929, 219, 'BUN', 'Bundibugyo', 0),
(3930, 219, 'BSH', 'Bushenyi', 0),
(3931, 219, 'HOI', 'Hoima', 0),
(3932, 219, 'KBL', 'Kabale', 0),
(3933, 219, 'KAR', 'Kabarole', 0),
(3934, 219, 'KAM', 'Kamwenge', 0),
(3935, 219, 'KAN', 'Kanungu', 0),
(3936, 219, 'KAS', 'Kasese', 0),
(3937, 219, 'KBA', 'Kibaale', 0),
(3938, 219, 'KIS', 'Kisoro', 0),
(3939, 219, 'KYE', 'Kyenjojo', 0),
(3940, 219, 'MSN', 'Masindi', 0),
(3941, 219, 'MBR', 'Mbarara', 0),
(3942, 219, 'NTU', 'Ntungamo', 0),
(3943, 219, 'RUK', 'Rukungiri', 0),
(3944, 220, 'CK', 'Cherkasy', 0),
(3945, 220, 'CH', 'Chernihiv', 0),
(3946, 220, 'CV', 'Chernivtsi', 0),
(3947, 220, 'CR', 'Crimea', 0),
(3948, 220, 'DN', 'Dnipropetrovsk', 0),
(3949, 220, 'DO', 'Donetsk', 0),
(3950, 220, 'IV', 'Ivano-Frankivsk', 0),
(3951, 220, 'KL', 'Kharkiv Kherson', 0),
(3952, 220, 'KM', 'Khmelnyts&apos;kyy', 0),
(3953, 220, 'KR', 'Kirovohrad', 0),
(3954, 220, 'KV', 'Kiev', 0),
(3955, 220, 'KY', 'Kyyiv', 0),
(3956, 220, 'LU', 'Luhans&apos;k', 0),
(3957, 220, 'LV', 'L&apos;viv', 0),
(3958, 220, 'MY', 'Mykolayiv', 0),
(3959, 220, 'OD', 'Odesa', 0),
(3960, 220, 'PO', 'Poltava', 0),
(3961, 220, 'RI', 'Rivne', 0),
(3962, 220, 'SE', 'Sevastopol', 0),
(3963, 220, 'SU', 'Sumy', 0),
(3964, 220, 'TE', 'Ternopil&apos;', 0),
(3965, 220, 'VI', 'Vinnytsya', 0),
(3966, 220, 'VO', 'Volyn&apos;', 0),
(3967, 220, 'ZK', 'Zakarpattya', 0),
(3968, 220, 'ZA', 'Zaporizhzhya', 0),
(3969, 220, 'ZH', 'Zhytomyr', 0),
(3970, 221, 'AZ', 'Abu Zaby', 0),
(3971, 221, 'AJ', 'Ajman', 0),
(3972, 221, 'FU', 'Al Fujayrah', 0),
(3973, 221, 'SH', 'Ash Shariqah', 0),
(3974, 221, 'DU', 'Dubayy', 0),
(3975, 221, 'RK', 'R&apos;as al Khaymah', 0),
(3976, 221, 'UQ', 'Umm al Qaywayn', 0),
(3977, 222, 'ABN', 'Aberdeen', 0),
(3978, 222, 'ABNS', 'Aberdeenshire', 0),
(3979, 222, 'ANG', 'Anglesey', 0),
(3980, 222, 'AGS', 'Angus', 0),
(3981, 222, 'ARY', 'Argyll and Bute', 0),
(3982, 222, 'BEDS', 'Bedfordshire', 0),
(3983, 222, 'BERKS', 'Berkshire', 0),
(3984, 222, 'BLA', 'Blaenau Gwent', 0),
(3985, 222, 'BRI', 'Bridgend', 0),
(3986, 222, 'BSTL', 'Bristol', 0),
(3987, 222, 'BUCKS', 'Buckinghamshire', 0),
(3988, 222, 'CAE', 'Caerphilly', 0),
(3989, 222, 'CAMBS', 'Cambridgeshire', 0),
(3990, 222, 'CDF', 'Cardiff', 0),
(3991, 222, 'CARM', 'Carmarthenshire', 0),
(3992, 222, 'CDGN', 'Ceredigion', 0),
(3993, 222, 'CHES', 'Cheshire', 0),
(3994, 222, 'CLACK', 'Clackmannanshire', 0),
(3995, 222, 'CON', 'Conwy', 0),
(3996, 222, 'CORN', 'Cornwall', 0),
(3997, 222, 'DNBG', 'Denbighshire', 0),
(3998, 222, 'DERBY', 'Derbyshire', 0),
(3999, 222, 'DVN', 'Devon', 0),
(4000, 222, 'DOR', 'Dorset', 0),
(4001, 222, 'DGL', 'Dumfries and Galloway', 0),
(4002, 222, 'DUND', 'Dundee', 0),
(4003, 222, 'DHM', 'Durham', 0),
(4004, 222, 'ARYE', 'East Ayrshire', 0),
(4005, 222, 'DUNBE', 'East Dunbartonshire', 0),
(4006, 222, 'LOTE', 'East Lothian', 0),
(4007, 222, 'RENE', 'East Renfrewshire', 0),
(4008, 222, 'ERYS', 'East Riding of Yorkshire', 0),
(4009, 222, 'SXE', 'East Sussex', 0),
(4010, 222, 'EDIN', 'Edinburgh', 0),
(4011, 222, 'ESX', 'Essex', 0),
(4012, 222, 'FALK', 'Falkirk', 0),
(4013, 222, 'FFE', 'Fife', 0),
(4014, 222, 'FLINT', 'Flintshire', 0),
(4015, 222, 'GLAS', 'Glasgow', 0),
(4016, 222, 'GLOS', 'Gloucestershire', 0),
(4017, 222, 'LDN', 'Greater London', 1),
(4018, 222, 'MCH', 'Greater Manchester', 0),
(4019, 222, 'GDD', 'Gwynedd', 0),
(4020, 222, 'HANTS', 'Hampshire', 0),
(4021, 222, 'HWR', 'Herefordshire', 0),
(4022, 222, 'HERTS', 'Hertfordshire', 0),
(4023, 222, 'HLD', 'Highlands', 0),
(4024, 222, 'IVER', 'Inverclyde', 0),
(4025, 222, 'IOW', 'Isle of Wight', 0),
(4026, 222, 'KNT', 'Kent', 0),
(4027, 222, 'LANCS', 'Lancashire', 0),
(4028, 222, 'LEICS', 'Leicestershire', 0),
(4029, 222, 'LINCS', 'Lincolnshire', 0),
(4030, 222, 'MSY', 'Merseyside', 0),
(4031, 222, 'MERT', 'Merthyr Tydfil', 0),
(4032, 222, 'MLOT', 'Midlothian', 0),
(4033, 222, 'MMOUTH', 'Monmouthshire', 0),
(4034, 222, 'MORAY', 'Moray', 0),
(4035, 222, 'NPRTAL', 'Neath Port Talbot', 0),
(4036, 222, 'NEWPT', 'Newport', 0),
(4037, 222, 'NOR', 'Norfolk', 0),
(4038, 222, 'ARYN', 'North Ayrshire', 0),
(4039, 222, 'LANN', 'North Lanarkshire', 0),
(4040, 222, 'YSN', 'North Yorkshire', 0),
(4041, 222, 'NHM', 'Northamptonshire', 0),
(4042, 222, 'NLD', 'Northumberland', 0),
(4043, 222, 'NOT', 'Nottinghamshire', 0),
(4044, 222, 'ORK', 'Orkney Islands', 0),
(4045, 222, 'OFE', 'Oxfordshire', 0),
(4046, 222, 'PEM', 'Pembrokeshire', 0),
(4047, 222, 'PERTH', 'Perth and Kinross', 0),
(4048, 222, 'PWS', 'Powys', 0),
(4049, 222, 'REN', 'Renfrewshire', 0),
(4050, 222, 'RHON', 'Rhondda Cynon Taff', 0),
(4051, 222, 'RUT', 'Rutland', 0),
(4052, 222, 'BOR', 'Scottish Borders', 0),
(4053, 222, 'SHET', 'Shetland Islands', 0),
(4054, 222, 'SPE', 'Shropshire', 0),
(4055, 222, 'SOM', 'Somerset', 0),
(4056, 222, 'ARYS', 'South Ayrshire', 0),
(4057, 222, 'LANS', 'South Lanarkshire', 0),
(4058, 222, 'YSS', 'South Yorkshire', 0),
(4059, 222, 'SFD', 'Staffordshire', 0),
(4060, 222, 'STIR', 'Stirling', 0),
(4061, 222, 'SFK', 'Suffolk', 0),
(4062, 222, 'SRY', 'Surrey', 0),
(4063, 222, 'SWAN', 'Swansea', 0),
(4064, 222, 'TORF', 'Torfaen', 0),
(4065, 222, 'TWR', 'Tyne and Wear', 0),
(4066, 222, 'VGLAM', 'Vale of Glamorgan', 0),
(4067, 222, 'WARKS', 'Warwickshire', 0),
(4068, 222, 'WDUN', 'West Dunbartonshire', 0),
(4069, 222, 'WLOT', 'West Lothian', 0),
(4070, 222, 'WMD', 'West Midlands', 0),
(4071, 222, 'SXW', 'West Sussex', 0),
(4072, 222, 'YSW', 'West Yorkshire', 0),
(4073, 222, 'WIL', 'Western Isles', 0),
(4074, 222, 'WLT', 'Wiltshire', 0),
(4075, 222, 'WORCS', 'Worcestershire', 0),
(4076, 222, 'WRX', 'Wrexham', 0),
(4078, 223, 'AL', 'Alabama', 0),
(4079, 223, 'AK', 'Alaska', 0),
(4080, 223, 'AS', 'American Samoa', 0),
(4081, 223, 'AZ', 'Arizona', 0),
(4082, 223, 'AR', 'Arkansas', 0),
(4083, 223, 'AF', 'Armed Forces Africa', 0),
(4084, 223, 'AA', 'Armed Forces Americas', 0),
(4085, 223, 'AC', 'Armed Forces Canada', 0),
(4086, 223, 'AE', 'Armed Forces Europe', 0),
(4087, 223, 'AM', 'Armed Forces Middle East', 0),
(4088, 223, 'AP', 'Armed Forces Pacific', 0),
(4089, 223, 'CA', 'California', 0),
(4090, 223, 'CO', 'Colorado', 0),
(4091, 223, 'CT', 'Connecticut', 0),
(4092, 223, 'DE', 'Delaware', 0),
(4093, 223, 'DC', 'District of Columbia', 0),
(4094, 223, 'FM', 'Federated States Of Micronesia', 0),
(4095, 223, 'FL', 'Florida', 0),
(4096, 223, 'GA', 'Georgia', 0),
(4097, 223, 'GU', 'Guam', 0),
(4098, 223, 'HI', 'Hawaii', 0),
(4099, 223, 'ID', 'Idaho', 0),
(4100, 223, 'IL', 'Illinois', 0),
(4101, 223, 'IN', 'Indiana', 0),
(4102, 223, 'IA', 'Iowa', 0),
(4103, 223, 'KS', 'Kansas', 0),
(4104, 223, 'KY', 'Kentucky', 0),
(4105, 223, 'LA', 'Louisiana', 0),
(4106, 223, 'ME', 'Maine', 0),
(4107, 223, 'MH', 'Marshall Islands', 0),
(4108, 223, 'MD', 'Maryland', 0),
(4109, 223, 'MA', 'Massachusetts', 0),
(4110, 223, 'MI', 'Michigan', 0),
(4111, 223, 'MN', 'Minnesota', 0),
(4112, 223, 'WY', 'Wyoming', 0),
(4113, 223, 'MO', 'Missouri', 0),
(4114, 223, 'MT', 'Montana', 0),
(4115, 223, 'NE', 'Nebraska', 0),
(4116, 223, 'NV', 'Nevada', 0),
(4117, 223, 'NH', 'New Hampshire', 0),
(4118, 223, 'NJ', 'New Jersey', 0),
(4119, 223, 'NM', 'New Mexico', 0),
(4120, 223, 'NY', 'New York', 0),
(4121, 223, 'NC', 'North Carolina', 0),
(4122, 223, 'ND', 'North Dakota', 0),
(4123, 223, 'MP', 'Northern Mariana Islands', 0),
(4124, 223, 'OH', 'Ohio', 0),
(4125, 223, 'OK', 'Oklahoma', 0),
(4126, 223, 'OR', 'Oregon', 0),
(4127, 223, 'PW', 'Palau', 0),
(4128, 223, 'PA', 'Pennsylvania', 0),
(4129, 223, 'PR', 'Puerto Rico', 0),
(4130, 223, 'RI', 'Rhode Island', 0),
(4131, 223, 'SC', 'South Carolina', 0),
(4132, 223, 'SD', 'South Dakota', 0),
(4133, 223, 'TN', 'Tennessee', 0),
(4134, 223, 'TX', 'Texas', 0),
(4135, 223, 'UT', 'Utah', 0),
(4136, 223, 'VT', 'Vermont', 0),
(4137, 223, 'VI', 'Virgin Islands', 0),
(4138, 223, 'VA', 'Virginia', 0),
(4139, 223, 'WA', 'Washington', 0),
(4140, 223, 'WV', 'West Virginia', 0),
(4141, 223, 'WI', 'Wisconsin', 0),
(4142, 223, 'MS', 'Mississippi', 0),
(4144, 224, 'BI', 'Baker Island', 0),
(4145, 224, 'HI', 'Howland Island', 0),
(4146, 224, 'JI', 'Jarvis Island', 0),
(4147, 224, 'JA', 'Johnston Atoll', 0),
(4148, 224, 'KR', 'Kingman Reef', 0),
(4149, 224, 'MA', 'Midway Atoll', 0),
(4150, 224, 'NI', 'Navassa Island', 0),
(4151, 224, 'PA', 'Palmyra Atoll', 0),
(4152, 224, 'WI', 'Wake Island', 0),
(4153, 225, 'AR', 'Artigas', 0),
(4154, 225, 'CA', 'Canelones', 0),
(4155, 225, 'CL', 'Cerro Largo', 0),
(4156, 225, 'CO', 'Colonia', 0),
(4157, 225, 'DU', 'Durazno', 0),
(4158, 225, 'FS', 'Flores', 0),
(4159, 225, 'FA', 'Florida', 0),
(4160, 225, 'LA', 'Lavalleja', 0),
(4161, 225, 'MA', 'Maldonado', 0),
(4162, 225, 'MO', 'Montevideo', 0),
(4163, 225, 'PA', 'Paysandu', 0),
(4164, 225, 'RN', 'Rio Negro', 0),
(4165, 225, 'RV', 'Rivera', 0),
(4166, 225, 'RO', 'Rocha', 0),
(4167, 225, 'SL', 'Salto', 0),
(4168, 225, 'SJ', 'San Jose', 0),
(4169, 225, 'SO', 'Soriano', 0),
(4170, 225, 'TA', 'Tacuarembo', 0),
(4171, 225, 'TT', 'Treinta y Tres', 0),
(4172, 226, 'AN', 'Andijon', 0),
(4173, 226, 'BU', 'Buxoro', 0),
(4174, 226, 'FA', 'Farg&apos;ona', 0),
(4175, 226, 'JI', 'Jizzax', 0),
(4176, 226, 'NG', 'Namangan', 0),
(4177, 226, 'NW', 'Navoiy', 0),
(4178, 226, 'QA', 'Qashqadaryo', 0),
(4179, 226, 'QR', 'Qoraqalpog&apos;iston Republikas', 0),
(4180, 226, 'SA', 'Samarqand', 0),
(4181, 226, 'SI', 'Sirdaryo', 0),
(4182, 226, 'SU', 'Surxondaryo', 0),
(4183, 226, 'TK', 'Toshkent City', 0),
(4184, 226, 'TO', 'Toshkent Region', 0),
(4185, 226, 'XO', 'Xorazm', 0),
(4186, 227, 'MA', 'Malampa', 0),
(4187, 227, 'PE', 'Penama', 0),
(4188, 227, 'SA', 'Sanma', 0),
(4189, 227, 'SH', 'Shefa', 0),
(4190, 227, 'TA', 'Tafea', 0),
(4191, 227, 'TO', 'Torba', 0),
(4192, 229, 'AM', 'Amazonas', 0),
(4193, 229, 'AN', 'Anzoategui', 0),
(4194, 229, 'AP', 'Apure', 0),
(4195, 229, 'AR', 'Aragua', 0),
(4196, 229, 'BA', 'Barinas', 0),
(4197, 229, 'BO', 'Bolivar', 0),
(4198, 229, 'CA', 'Carabobo', 0),
(4199, 229, 'CO', 'Cojedes', 0),
(4200, 229, 'DA', 'Delta Amacuro', 0),
(4201, 229, 'DF', 'Dependencias Federales', 0),
(4202, 229, 'DI', 'Distrito Federal', 0),
(4203, 229, 'FA', 'Falcon', 0),
(4204, 229, 'GU', 'Guarico', 0),
(4205, 229, 'LA', 'Lara', 0),
(4206, 229, 'ME', 'Merida', 0),
(4207, 229, 'MI', 'Miranda', 0),
(4208, 229, 'MO', 'Monagas', 0),
(4209, 229, 'NE', 'Nueva Esparta', 0),
(4210, 229, 'PO', 'Portuguesa', 0),
(4211, 229, 'SU', 'Sucre', 0),
(4212, 229, 'TA', 'Tachira', 0),
(4213, 229, 'TR', 'Trujillo', 0),
(4214, 229, 'VA', 'Vargas', 0),
(4215, 229, 'YA', 'Yaracuy', 0),
(4216, 229, 'ZU', 'Zulia', 0),
(4217, 230, 'AG', 'An Giang', 0),
(4218, 230, 'BG', 'Bac Giang', 0),
(4219, 230, 'BK', 'Bac Kan', 0),
(4220, 230, 'BL', 'Bac Lieu', 0),
(4221, 230, 'BC', 'Bac Ninh', 0),
(4222, 230, 'BR', 'Ba Ria-Vung Tau', 0),
(4223, 230, 'BN', 'Ben Tre', 0),
(4224, 230, 'BH', 'Binh Dinh', 0),
(4225, 230, 'BU', 'Binh Duong', 0),
(4226, 230, 'BP', 'Binh Phuoc', 0),
(4227, 230, 'BT', 'Binh Thuan', 0),
(4228, 230, 'CM', 'Ca Mau', 0),
(4229, 230, 'CT', 'Can Tho', 0),
(4230, 230, 'CB', 'Cao Bang', 0),
(4231, 230, 'DL', 'Dak Lak', 0),
(4232, 230, 'DG', 'Dak Nong', 0),
(4233, 230, 'DN', 'Da Nang', 0),
(4234, 230, 'DB', 'Dien Bien', 0),
(4235, 230, 'DI', 'Dong Nai', 0),
(4236, 230, 'DT', 'Dong Thap', 0),
(4237, 230, 'GL', 'Gia Lai', 0),
(4238, 230, 'HG', 'Ha Giang', 0),
(4239, 230, 'HD', 'Hai Duong', 0),
(4240, 230, 'HP', 'Hai Phong', 0),
(4241, 230, 'HM', 'Ha Nam', 0),
(4242, 230, 'HI', 'Ha Noi', 0),
(4243, 230, 'HT', 'Ha Tay', 0),
(4244, 230, 'HH', 'Ha Tinh', 0),
(4245, 230, 'HB', 'Hoa Binh', 0),
(4246, 230, 'HC', 'Ho Chin Minh', 0),
(4247, 230, 'HU', 'Hau Giang', 0),
(4248, 230, 'HY', 'Hung Yen', 0),
(4249, 232, 'C', 'Saint Croix', 0),
(4250, 232, 'J', 'Saint John', 0),
(4251, 232, 'T', 'Saint Thomas', 0),
(4252, 233, 'A', 'Alo', 0),
(4253, 233, 'S', 'Sigave', 0),
(4254, 233, 'W', 'Wallis', 0),
(4255, 235, 'AB', 'Abyan', 0),
(4256, 235, 'AD', 'Adan', 0),
(4257, 235, 'AM', 'Amran', 0),
(4258, 235, 'BA', 'Al Bayda', 0),
(4259, 235, 'DA', 'Ad Dali', 0),
(4260, 235, 'DH', 'Dhamar', 0),
(4261, 235, 'HD', 'Hadramawt', 0),
(4262, 235, 'HJ', 'Hajjah', 0),
(4263, 235, 'HU', 'Al Hudaydah', 0),
(4264, 235, 'IB', 'Ibb', 0),
(4265, 235, 'JA', 'Al Jawf', 0),
(4266, 235, 'LA', 'Lahij', 0),
(4267, 235, 'MA', 'Ma&apos;rib', 0),
(4268, 235, 'MR', 'Al Mahrah', 0),
(4269, 235, 'MW', 'Al Mahwit', 0),
(4270, 235, 'SD', 'Sa&apos;dah', 0),
(4271, 235, 'SN', 'San&apos;a', 0),
(4272, 235, 'SH', 'Shabwah', 0),
(4273, 235, 'TA', 'Ta&apos;izz', 0),
(4274, 236, 'KOS', 'Kosovo', 0),
(4275, 236, 'MON', 'Montenegro', 0),
(4276, 236, 'SER', 'Serbia', 0),
(4277, 236, 'VOJ', 'Vojvodina', 0),
(4278, 237, 'BC', 'Bas-Congo', 0),
(4279, 237, 'BN', 'Bandundu', 0),
(4280, 237, 'EQ', 'Equateur', 0),
(4281, 237, 'KA', 'Katanga', 0),
(4282, 237, 'KE', 'Kasai-Oriental', 0),
(4283, 237, 'KN', 'Kinshasa', 0),
(4284, 237, 'KW', 'Kasai-Occidental', 0),
(4285, 237, 'MA', 'Maniema', 0),
(4286, 237, 'NK', 'Nord-Kivu', 0),
(4287, 237, 'OR', 'Orientale', 0),
(4288, 237, 'SK', 'Sud-Kivu', 0),
(4289, 238, 'CE', 'Central', 0),
(4290, 238, 'CB', 'Copperbelt', 0),
(4291, 238, 'EA', 'Eastern', 0),
(4292, 238, 'LP', 'Luapula', 0),
(4293, 238, 'LK', 'Lusaka', 0),
(4294, 238, 'NO', 'Northern', 0),
(4295, 238, 'NW', 'North-Western', 0),
(4296, 238, 'SO', 'Southern', 0),
(4297, 238, 'WE', 'Western', 0),
(4298, 239, 'BU', 'Bulawayo', 0),
(4299, 239, 'HA', 'Harare', 0),
(4300, 239, 'ML', 'Manicaland', 0),
(4301, 239, 'MC', 'Mashonaland Central', 0),
(4302, 239, 'ME', 'Mashonaland East', 0),
(4303, 239, 'MW', 'Mashonaland West', 0),
(4304, 239, 'MV', 'Masvingo', 0),
(4305, 239, 'MN', 'Matabeleland North', 0),
(4306, 239, 'MS', 'Matabeleland South', 0),
(4307, 239, 'MD', 'Midlands', 0),
(4308, 999, 'BO', 'Agent', 0);

-- --------------------------------------------------------

--
-- Table structure for table `zones_to_geo_zones`
--

CREATE TABLE `zones_to_geo_zones` (
  `association_id` int(11) NOT NULL,
  `zone_country_id` int(11) NOT NULL DEFAULT 0,
  `zone_id` int(11) DEFAULT 0,
  `geo_zone_id` int(11) DEFAULT 0,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones_to_geo_zones`
--

INSERT INTO `zones_to_geo_zones` (`association_id`, `zone_country_id`, `zone_id`, `geo_zone_id`, `last_modified`, `date_added`) VALUES
(1, 1, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(2, 2, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(3, 3, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(4, 4, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(5, 5, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(6, 6, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(7, 7, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(8, 8, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(9, 9, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(10, 10, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(11, 11, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(12, 12, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(13, 223, 0, 1, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(14, 14, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(15, 15, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(16, 16, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(17, 17, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(18, 18, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(19, 19, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(20, 20, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(21, 21, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(22, 22, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(23, 23, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(24, 24, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(25, 25, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(26, 26, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(27, 27, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(28, 28, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(29, 29, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(30, 30, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(31, 31, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:32'),
(32, 32, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(33, 33, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(34, 34, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(35, 35, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(36, 36, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(37, 37, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(38, 38, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(39, 39, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(40, 40, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(41, 41, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(42, 42, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(43, 43, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(44, 44, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(45, 45, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(46, 46, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(47, 47, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(48, 48, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(49, 49, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(50, 50, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(51, 51, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(52, 52, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(53, 53, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(54, 54, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(55, 55, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(56, 56, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(57, 57, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(58, 58, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(59, 59, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(60, 60, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(61, 61, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(62, 62, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(63, 63, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(64, 64, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(65, 65, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(66, 66, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(67, 67, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:33'),
(68, 68, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(69, 69, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(70, 70, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(71, 71, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(72, 72, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(73, 73, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(74, 74, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(75, 75, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(76, 76, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(77, 77, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(78, 78, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(79, 79, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(80, 80, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(81, 81, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(82, 82, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(83, 83, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(84, 84, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(85, 85, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(86, 86, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(87, 87, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(88, 88, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(89, 89, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(90, 90, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(91, 91, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(92, 92, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(93, 93, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(94, 94, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(95, 95, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(96, 96, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(97, 97, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(98, 98, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(99, 99, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(100, 100, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(101, 101, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(102, 102, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(103, 103, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(104, 104, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(105, 105, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(106, 106, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(107, 107, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(108, 108, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(109, 109, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:34'),
(110, 110, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(111, 111, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(112, 112, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(113, 113, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(114, 114, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(115, 115, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(116, 116, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(117, 117, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(118, 118, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(119, 119, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(120, 120, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(121, 121, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(122, 122, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(123, 123, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(124, 124, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(125, 125, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(126, 126, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(127, 127, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(128, 128, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(129, 129, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(130, 130, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(131, 131, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(132, 132, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(133, 133, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(134, 134, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(135, 135, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(136, 136, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(137, 137, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(138, 138, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(139, 139, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(140, 140, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(141, 141, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(142, 142, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(143, 143, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(144, 144, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(145, 145, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(146, 146, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(147, 147, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(148, 148, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(149, 149, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(150, 150, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(151, 151, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(152, 152, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(153, 153, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(154, 154, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(155, 155, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(156, 156, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(157, 157, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(158, 158, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(159, 159, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(160, 160, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(161, 161, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(162, 162, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(163, 163, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(164, 164, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(165, 165, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(166, 166, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(167, 167, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(168, 168, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(169, 169, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(170, 170, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(171, 171, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(172, 172, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(173, 173, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(174, 174, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(175, 175, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(176, 176, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(177, 177, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:35'),
(178, 178, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(179, 179, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(180, 180, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(181, 181, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(182, 182, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(183, 183, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(184, 184, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(185, 185, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(186, 186, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(187, 187, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(188, 188, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(189, 189, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(190, 190, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(191, 191, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(192, 192, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(193, 193, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(194, 194, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(195, 195, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(196, 196, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(197, 197, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(198, 198, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(199, 199, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(200, 200, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(201, 201, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(202, 202, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(203, 203, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(204, 204, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(205, 205, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(206, 206, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(207, 207, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(208, 208, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(209, 209, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(210, 210, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(211, 211, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(212, 212, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(213, 213, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(214, 214, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(215, 215, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(216, 216, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(217, 217, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(218, 218, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(219, 219, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(220, 220, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(221, 221, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(222, 222, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(223, 223, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(224, 224, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(225, 225, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(226, 226, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(227, 227, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(228, 228, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(229, 229, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(230, 230, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(231, 231, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(232, 232, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:36'),
(233, 233, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(234, 234, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(235, 235, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(236, 236, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(237, 237, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(238, 238, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(239, 239, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(240, 13, 0, 2, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(241, 13, 0, 3, '2020-01-01 00:00:00', '2017-04-26 17:35:37'),
(242, 999, 0, 4, '2020-01-01 00:00:00', '2010-09-19 18:08:42');

-- --------------------------------------------------------

--
-- Structure for view `carts_united`
--
DROP TABLE IF EXISTS `carts_united`;

CREATE ALGORITHM=UNDEFINED DEFINER=`scottjpq`@`localhost` SQL SECURITY DEFINER VIEW `carts_united`  AS SELECT `cb`.`customers_basket_id` AS `customers_basket_id`, `cb`.`products_id` AS `products_id`, `cb`.`customers_id` AS `customers_id`, `cb`.`customers_basket_date_added` AS `customers_basket_date_added`, `cb`.`customers_basket_quantity` AS `customers_basket_quantity`, `cb`.`discount_id` AS `discount_id` FROM `customers_basket` AS `cb` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_book`
--
ALTER TABLE `address_book`
  ADD PRIMARY KEY (`address_book_id`),
  ADD KEY `idx_address_book_customers_id` (`customers_id`);

--
-- Indexes for table `address_format`
--
ALTER TABLE `address_format`
  ADD PRIMARY KEY (`address_format_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email_address` (`admin_email_address`);

--
-- Indexes for table `admin_files`
--
ALTER TABLE `admin_files`
  ADD PRIMARY KEY (`admin_files_id`);

--
-- Indexes for table `admin_files_groups`
--
ALTER TABLE `admin_files_groups`
  ADD PRIMARY KEY (`admin_files_groups_id`),
  ADD UNIQUE KEY `admin_files_groups_id` (`admin_files_groups_id`);

--
-- Indexes for table `admin_groups`
--
ALTER TABLE `admin_groups`
  ADD PRIMARY KEY (`admin_groups_id`),
  ADD UNIQUE KEY `admin_groups_name` (`admin_groups_name`);

--
-- Indexes for table `admin_menus`
--
ALTER TABLE `admin_menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `admin_menus_description`
--
ALTER TABLE `admin_menus_description`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`),
  ADD KEY `idx_categories_parent_id` (`parent_id`);

--
-- Indexes for table `categories_description`
--
ALTER TABLE `categories_description`
  ADD PRIMARY KEY (`categories_id`,`language_id`),
  ADD KEY `idx_categories_name` (`categories_name`);

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`configuration_id`);

--
-- Indexes for table `configuration_group`
--
ALTER TABLE `configuration_group`
  ADD PRIMARY KEY (`configuration_group_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countries_id`),
  ADD KEY `IDX_COUNTRIES_NAME` (`countries_name`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `coupons_description`
--
ALTER TABLE `coupons_description`
  ADD PRIMARY KEY (`coupon_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `coupon_discount_email`
--
ALTER TABLE `coupon_discount_email`
  ADD PRIMARY KEY (`discount_coupon_id`);

--
-- Indexes for table `coupon_email_track`
--
ALTER TABLE `coupon_email_track`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `coupon_gv_customer`
--
ALTER TABLE `coupon_gv_customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `coupon_gv_queue`
--
ALTER TABLE `coupon_gv_queue`
  ADD PRIMARY KEY (`unique_id`),
  ADD KEY `uid` (`unique_id`,`customer_id`,`order_id`);

--
-- Indexes for table `coupon_redeem_track`
--
ALTER TABLE `coupon_redeem_track`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `coupon_season_customer`
--
ALTER TABLE `coupon_season_customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `coupon_season_queue`
--
ALTER TABLE `coupon_season_queue`
  ADD PRIMARY KEY (`season_queue_id`);

--
-- Indexes for table `coupon_season_track`
--
ALTER TABLE `coupon_season_track`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currencies_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customers_id`);

--
-- Indexes for table `customers_away_basket`
--
ALTER TABLE `customers_away_basket`
  ADD PRIMARY KEY (`customers_basket_id`);

--
-- Indexes for table `customers_basket`
--
ALTER TABLE `customers_basket`
  ADD PRIMARY KEY (`customers_basket_id`);

--
-- Indexes for table `customers_extra_info`
--
ALTER TABLE `customers_extra_info`
  ADD PRIMARY KEY (`uniquename`,`customers_id`);

--
-- Indexes for table `customers_groups`
--
ALTER TABLE `customers_groups`
  ADD PRIMARY KEY (`customers_groups_id`);

--
-- Indexes for table `customers_info`
--
ALTER TABLE `customers_info`
  ADD PRIMARY KEY (`customers_info_id`);

--
-- Indexes for table `customers_info_fields`
--
ALTER TABLE `customers_info_fields`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `customers_info_fields_description`
--
ALTER TABLE `customers_info_fields_description`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `customers_temp_basket`
--
ALTER TABLE `customers_temp_basket`
  ADD PRIMARY KEY (`customers_basket_id`);

--
-- Indexes for table `customer_options`
--
ALTER TABLE `customer_options`
  ADD PRIMARY KEY (`options_id`);

--
-- Indexes for table `email_data`
--
ALTER TABLE `email_data`
  ADD PRIMARY KEY (`email_id`);

--
-- Indexes for table `email_messages`
--
ALTER TABLE `email_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured`
--
ALTER TABLE `featured`
  ADD PRIMARY KEY (`featured_id`);

--
-- Indexes for table `general_templates`
--
ALTER TABLE `general_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `geo_zones`
--
ALTER TABLE `geo_zones`
  ADD PRIMARY KEY (`geo_zone_id`);

--
-- Indexes for table `infobox_configuration`
--
ALTER TABLE `infobox_configuration`
  ADD PRIMARY KEY (`infobox_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`languages_id`),
  ADD KEY `IDX_LANGUAGES_NAME` (`name`);

--
-- Indexes for table `main_pages`
--
ALTER TABLE `main_pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `main_page_description`
--
ALTER TABLE `main_page_description`
  ADD PRIMARY KEY (`page_id`,`language_id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`manufacturers_id`),
  ADD KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`);

--
-- Indexes for table `manufacturers_info`
--
ALTER TABLE `manufacturers_info`
  ADD PRIMARY KEY (`manufacturers_id`,`languages_id`);

--
-- Indexes for table `menu_category`
--
ALTER TABLE `menu_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_subcategory`
--
ALTER TABLE `menu_subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_tags`
--
ALTER TABLE `meta_tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`newsletters_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `orders_barcode`
--
ALTER TABLE `orders_barcode`
  ADD PRIMARY KEY (`barcode_id`),
  ADD UNIQUE KEY `barcode_id` (`barcode_id`),
  ADD KEY `barcode_id_2` (`barcode_id`);

--
-- Indexes for table `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`orders_products_id`);

--
-- Indexes for table `orders_products_download`
--
ALTER TABLE `orders_products_download`
  ADD PRIMARY KEY (`orders_products_download_id`);

--
-- Indexes for table `orders_status`
--
ALTER TABLE `orders_status`
  ADD PRIMARY KEY (`orders_status_id`,`language_id`),
  ADD KEY `idx_orders_status_name` (`orders_status_name`);

--
-- Indexes for table `orders_status_history`
--
ALTER TABLE `orders_status_history`
  ADD PRIMARY KEY (`orders_status_history_id`);

--
-- Indexes for table `orders_tickets`
--
ALTER TABLE `orders_tickets`
  ADD PRIMARY KEY (`orders_tickets_id`);

--
-- Indexes for table `orders_total`
--
ALTER TABLE `orders_total`
  ADD PRIMARY KEY (`orders_total_id`),
  ADD KEY `idx_orders_total_orders_id` (`orders_id`);

--
-- Indexes for table `payment_response`
--
ALTER TABLE `payment_response`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD UNIQUE KEY `order_id_2` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`products_id`),
  ADD KEY `idx_products_date_added` (`products_date_added`);

--
-- Indexes for table `products_attributes`
--
ALTER TABLE `products_attributes`
  ADD PRIMARY KEY (`products_attributes_id`);

--
-- Indexes for table `products_attributes_download`
--
ALTER TABLE `products_attributes_download`
  ADD PRIMARY KEY (`products_attributes_id`);

--
-- Indexes for table `products_description`
--
ALTER TABLE `products_description`
  ADD PRIMARY KEY (`products_id`,`language_id`),
  ADD KEY `products_name` (`products_name`);

--
-- Indexes for table `products_to_categories`
--
ALTER TABLE `products_to_categories`
  ADD PRIMARY KEY (`products_id`,`categories_id`);

--
-- Indexes for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD PRIMARY KEY (`links_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `row_status`
--
ALTER TABLE `row_status`
  ADD PRIMARY KEY (`row_status_id`);

--
-- Indexes for table `salemaker_sales`
--
ALTER TABLE `salemaker_sales`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `seatplan_events`
--
ALTER TABLE `seatplan_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sesskey`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`sources_id`),
  ADD KEY `IDX_SOURCES_NAME` (`sources_name`);

--
-- Indexes for table `sources_other`
--
ALTER TABLE `sources_other`
  ADD PRIMARY KEY (`sources_other_customers_id`);

--
-- Indexes for table `specials`
--
ALTER TABLE `specials`
  ADD PRIMARY KEY (`specials_id`);

--
-- Indexes for table `tax_class`
--
ALTER TABLE `tax_class`
  ADD PRIMARY KEY (`tax_class_id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`tax_rates_id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`template_id`),
  ADD KEY `IDX_TEMPLATE_NAME` (`template_name`);

--
-- Indexes for table `timezone_master`
--
ALTER TABLE `timezone_master`
  ADD PRIMARY KEY (`timezone_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_history`
--
ALTER TABLE `wallet_history`
  ADD PRIMARY KEY (`wallet_history_id`);

--
-- Indexes for table `wallet_messages_history`
--
ALTER TABLE `wallet_messages_history`
  ADD PRIMARY KEY (`wallet_history_id`);

--
-- Indexes for table `wallet_uploads`
--
ALTER TABLE `wallet_uploads`
  ADD PRIMARY KEY (`wallet_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- Indexes for table `zones_to_geo_zones`
--
ALTER TABLE `zones_to_geo_zones`
  ADD PRIMARY KEY (`association_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_book`
--
ALTER TABLE `address_book`
  MODIFY `address_book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `address_format`
--
ALTER TABLE `address_format`
  MODIFY `address_format_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_files`
--
ALTER TABLE `admin_files`
  MODIFY `admin_files_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1220;

--
-- AUTO_INCREMENT for table `admin_groups`
--
ALTER TABLE `admin_groups`
  MODIFY `admin_groups_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_menus`
--
ALTER TABLE `admin_menus`
  MODIFY `menu_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `configuration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1103;

--
-- AUTO_INCREMENT for table `configuration_group`
--
ALTER TABLE `configuration_group`
  MODIFY `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=930;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countries_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_discount_email`
--
ALTER TABLE `coupon_discount_email`
  MODIFY `discount_coupon_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_email_track`
--
ALTER TABLE `coupon_email_track`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_gv_queue`
--
ALTER TABLE `coupon_gv_queue`
  MODIFY `unique_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_redeem_track`
--
ALTER TABLE `coupon_redeem_track`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_season_queue`
--
ALTER TABLE `coupon_season_queue`
  MODIFY `season_queue_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_season_track`
--
ALTER TABLE `coupon_season_track`
  MODIFY `unique_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `currencies_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers_away_basket`
--
ALTER TABLE `customers_away_basket`
  MODIFY `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_basket`
--
ALTER TABLE `customers_basket`
  MODIFY `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers_groups`
--
ALTER TABLE `customers_groups`
  MODIFY `customers_groups_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers_info_fields`
--
ALTER TABLE `customers_info_fields`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `customers_temp_basket`
--
ALTER TABLE `customers_temp_basket`
  MODIFY `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_options`
--
ALTER TABLE `customer_options`
  MODIFY `options_id` tinyint(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_data`
--
ALTER TABLE `email_data`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_messages`
--
ALTER TABLE `email_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `featured`
--
ALTER TABLE `featured`
  MODIFY `featured_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_templates`
--
ALTER TABLE `general_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `geo_zones`
--
ALTER TABLE `geo_zones`
  MODIFY `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `infobox_configuration`
--
ALTER TABLE `infobox_configuration`
  MODIFY `infobox_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `languages_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `main_pages`
--
ALTER TABLE `main_pages`
  MODIFY `page_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_category`
--
ALTER TABLE `menu_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_subcategory`
--
ALTER TABLE `menu_subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `meta_tags`
--
ALTER TABLE `meta_tags`
  MODIFY `tag_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `newsletters_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders_barcode`
--
ALTER TABLE `orders_barcode`
  MODIFY `barcode_id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders_products`
--
ALTER TABLE `orders_products`
  MODIFY `orders_products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders_status_history`
--
ALTER TABLE `orders_status_history`
  MODIFY `orders_status_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders_tickets`
--
ALTER TABLE `orders_tickets`
  MODIFY `orders_tickets_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders_total`
--
ALTER TABLE `orders_total`
  MODIFY `orders_total_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `products_attributes`
--
ALTER TABLE `products_attributes`
  MODIFY `products_attributes_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_description`
--
ALTER TABLE `products_description`
  MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `quick_links`
--
ALTER TABLE `quick_links`
  MODIFY `links_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salemaker_sales`
--
ALTER TABLE `salemaker_sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seatplan_events`
--
ALTER TABLE `seatplan_events`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `sources_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sources_other`
--
ALTER TABLE `sources_other`
  MODIFY `sources_other_customers_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specials`
--
ALTER TABLE `specials`
  MODIFY `specials_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_class`
--
ALTER TABLE `tax_class`
  MODIFY `tax_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `tax_rates_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `timezone_master`
--
ALTER TABLE `timezone_master`
  MODIFY `timezone_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wallet_history`
--
ALTER TABLE `wallet_history`
  MODIFY `wallet_history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_messages_history`
--
ALTER TABLE `wallet_messages_history`
  MODIFY `wallet_history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_uploads`
--
ALTER TABLE `wallet_uploads`
  MODIFY `wallet_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4309;

--
-- AUTO_INCREMENT for table `zones_to_geo_zones`
--
ALTER TABLE `zones_to_geo_zones`
  MODIFY `association_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
