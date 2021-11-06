SET sql_mode = 'ALLOW_INVALID_DATES';

UPDATE `zones` SET `zone_name` = 'Baden-Württemberg' WHERE `zones`.`zone_id` = 1329; 
UPDATE `admin_menus` SET `parent_id` = '9' WHERE `admin_menus`.`menu_id` = 408; 
UPDATE `admin_menus` SET `parent_id` = '9' WHERE `admin_menus`.`menu_id` = 207; 
UPDATE `admin_menus` SET `parent_id` = '4' WHERE `admin_menus`.`menu_id` = 85; 

UPDATE `configuration` SET `configuration_group_id` = '923' WHERE `configuration`.`configuration_id` = 334; 

ALTER TABLE `categories` ADD `categories_image_3` VARCHAR(64) NOT NULL AFTER `concert_date_unix`; 
ALTER TABLE `categories` ADD `categories_image_4` VARCHAR(64) NOT NULL AFTER `categories_image_3`; 

UPDATE `configuration` SET `configuration_value` = '1000' WHERE `configuration`.`configuration_id` = 37; 
UPDATE `configuration` SET `configuration_value` = 'jpeg,jpg,png,gif' WHERE `configuration`.`configuration_id` = 213; 

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1053, 'Design Stage Name', 'DESIGN_STAGE_NAME', 'Stage', 'Design Stage Name Overlay', 923, 2, '2020-05-12 04:06:02', NULL, NULL, NULL),
(1054, 'Design Snap', 'DESIGN_SNAP', '2', 'Design Snap for mouse movements', 923, 4, '2020-05-12 04:06:02', NULL, NULL, 'tep_cfg_select_option(array(\"1\",\"2\",\"3\",\"4\",\"5\", \"10\", \"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"50\"),');
UPDATE `configuration` SET `configuration_value` = 'yes' WHERE `configuration`.`configuration_id` = 1049; 

INSERT INTO `admin_files` (`admin_files_id`, `admin_files_name`, `admin_files_is_boxes`, `admin_files_to_boxes`, `admin_groups_id`, `admin_files_type`, `admin_files_help_id`, `admin_new_group_id`) VALUES (1219, 'orders_only_data.php', '0', '0', '1', 'D', 'D', NULL);

UPDATE `configuration` SET `configuration_key` = 'INSTAGRAM_ID',`configuration_title` = 'Instagram ID' WHERE `configuration`.`configuration_id` = 225; 
UPDATE `admin_menus` SET `menu_pos` = '1' WHERE `admin_menus`.`menu_id` = 11; 

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1055, 'Set Conditions at Payment', 'PAYMENT_SET_TERMS', 'no', 'If you require a strict reminder and checkbox with terms or conditions AFTER selecting payment method and before moving to the confirmation page. Possible to remind customers about a COVID pass for example ', 917, 150, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1056, 'Ticket Background from left', 'BG_IMAGE_LEFT', '0', 'Adjustment to move the ticket background image from the left side', 920, 26, '2010-01-01', '2010-01-01', NULL, NULL),
(1057, 'Ticket Background from top', 'BG_IMAGE_TOP', '0', 'Adjustment to move the ticket background image from the uppermost top', 920, 26, '2010-01-01', '2010-01-01', NULL, NULL),
(1058, 'Ticket Background Width', 'BG_IMAGE_WIDTH', '180', 'Width of your ticket background image, 180 is full width default settings', 920, 24, '2010-01-01', '2010-01-01', NULL, NULL),
(1059, 'Ticket Background Height', 'BG_IMAGE_HEIGHT', '70', 'Height of your ticket background image, 70 is full height default  settings', 920, 25, '2010-01-01', '2010-01-01', NULL, NULL);

UPDATE `admin_menus` SET `filename` = 'barcodes_export.php' WHERE `admin_menus`.`menu_id` = 424; 
UPDATE `admin_files` SET `admin_files_name` = 'barcodes_export.php' WHERE `admin_files`.`admin_files_id` = 1209; 

DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 281;

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1060, 'Display Featured Categories on HOMEPAGE', 'SHOW_MAINPAGE_FEATURED_CATEGORIES', 'true', 'Show Featured Categories on Home Page or disable here', 19, 20, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),');

UPDATE `configuration` SET `configuration_title` = 'Display Featured Categories under Event Pages' WHERE `configuration`.`configuration_id` = 935; 
UPDATE `configuration` SET `configuration_title` = 'Display Featured Categories Page' WHERE `configuration`.`configuration_id` = 227; 
UPDATE `configuration` SET `configuration_description` = 'Display Featured Categories Page from Menu' WHERE `configuration`.`configuration_id` = 227; 
UPDATE `configuration` SET `sort_order` = '1' WHERE `configuration`.`configuration_id` = 227; 
UPDATE `configuration` SET `sort_order` = '2' WHERE `configuration`.`configuration_id` = 935; 
UPDATE `configuration` SET `sort_order` = '3' WHERE `configuration`.`configuration_id` = 1127; 

DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 7;
UPDATE `configuration` SET `configuration_description` = 'Using some CINEMA set up (for CINE project by DEV)' WHERE `configuration`.`configuration_id` = 949; 

UPDATE `configuration` SET `configuration_description` = 'Enable More Menu Listing -Venue,Date,Time. ONLY for Left Column Categories InfoBox' WHERE `configuration`.`configuration_id` = 247; 

UPDATE `configuration` SET `configuration_description` = 'Enable Subcategory count to calculate remaining seats available or count of reserved seats in the category. For Seat Plans with Sub Categories' WHERE `configuration`.`configuration_id` = 257; 

UPDATE `configuration` SET `configuration_description` = 'Add extra fields at checkout payment.Configurable in includes/languages/english.php' WHERE `configuration`.`configuration_id` = 253; 

UPDATE `configuration` SET `configuration_value` = '1' WHERE `configuration`.`configuration_id` = 954; 

UPDATE `configuration` SET `configuration_description` = 'Set the maximum number of tickets per order. Zero = unlimited. Do NOT use with GIFT voucher feature.' WHERE `configuration`.`configuration_id` = 254; 

INSERT INTO `email_messages` (`message_id`, `message_send`, `message_subject`, `message_reply_to`, `message_text`, `message_format`, `message_type`) VALUES
(11, '', 'Contact Us', 'contactus', '<section id=\"contact\">\r\n<div class=\"container\">\r\n        <div class=\"form\">\r\n            <div class=\"row\">\r\n              <div class=\"form-group col-md-6\">\r\n                %%Name Text Box%%\r\n              </div>\r\n              <div class=\"form-group col-md-6 mt-3 mt-md-0\">\r\n                 %%Email Text Box%%\r\n              </div>\r\n            </div>\r\n            <div class=\"form-group mt-3\">\r\n              %%Comments Text Box%%\r\n            </div>\r\n            <div class=\"text-center\">%%Continue Button%%</div>\r\n        </div>\r\n      </div>\r\n    </section><!-- End Contact Section -->\r\n </form>\r\n', '', 'HCF');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1061, 'Display Contact Form on HOMEPAGE', 'SHOW_MAINPAGE_CONTACT_FORM', 'false', 'Show Contact Form at the bottom of the HOMEPAGE', 917, 20, '2010-01-01', '2020-01-01', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),');

ALTER TABLE `categories` ADD `bg_height` INT(11) NOT NULL AFTER `categories_image_4`; 
ALTER TABLE `products` CHANGE `products_sx` `products_sx` DECIMAL(8,2) NOT NULL DEFAULT '0.00'; 
ALTER TABLE `products` ADD `products_sy` DECIMAL(8,2) NOT NULL AFTER `products_sx`; 
ALTER TABLE `products` CHANGE `products_sy` `products_sy` DECIMAL(8,2) NOT NULL DEFAULT '0.00'; 

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1062, 'Set ALL Price Headings', 'ALL_INVOICE_HEADINGS', 'yes', 'If you want only the products price itemized in the totals, remove the sub-pricing totals here.', 927, 150, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1032;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1033;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1034;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1035;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1036;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1037;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1038;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1039;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 1040;
DELETE FROM `admin_menus_description` WHERE `admin_menus_description`.`menu_id` = 427;
DELETE FROM `admin_menus` WHERE `admin_menus`.`menu_id` = 427;
DELETE FROM `configuration_group` WHERE `configuration_group`.`configuration_group_id` = 928;

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_5.sql',NOW());

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_5.sql',NOW());