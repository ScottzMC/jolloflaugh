SET sql_mode = 'ALLOW_INVALID_DATES';

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1046, 'UNIX expire date and time', 'EXPIRE_DATE_TIME', 'no', 'When you want to expire categories by date and time e.g when using session time 1pm =1300', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

UPDATE `configuration` SET `set_function` = 'tep_cfg_select_option(array(\"yes\", \"yes_and_hide_expired\", \"no\"),' WHERE `configuration`.`configuration_id` = 953; 

INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES
(432, 46, '1', 5, 'configuration.php', 'gID=929', 'P');

INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES
(432, 'Event Expiry', 1);

INSERT INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`, `configuration_access_key`) VALUES (NULL, 'Events Expiry', 'Events Expiry', '0', '1', 'Products Advanced Events Expiry'); 

UPDATE `configuration` SET `configuration_description` = 'Allow full category de-activation with expiry of categories with optional choice to hide de-activated categories' WHERE `configuration`.`configuration_id` = 953; 
UPDATE `configuration` SET `configuration_description` = 'How many hours after midnight categories expire in relation to your TimeZone. For DATE expiry only and not DATE AND TIME' WHERE `configuration`.`configuration_id` = 1042; 
UPDATE `configuration` SET `configuration_title` = 'Categories Hour of expiry' WHERE `configuration`.`configuration_id` = 1042; 


INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1047, 'BO Design Screen size', 'SCREEN_EDIT', 'desktop', 'If you have to design on a smaller screen try this with ipad setting', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'desktop\', \'ipad\'),'),
(1048, 'Allow BO Design', 'ALLOW_BO_DESIGN', 'no', 'Allow a Box Office User to edit the seat plan design', 923, 15, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1049, 'Activate Design Mode', 'DESIGN_MODE', 'no', 'Activate Design Mode so that BO can edit and display the Front End Design', 923, 14, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1050, 'Allow BO Refund', 'ALLOW_BO_REFUND', 'no', 'Allow a Box Office User to make Front End Visual Refunds with BO Refund feature', 923, 15, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),'),
(1051, 'Enable Design Grid', 'SET_GRID_BACKGROUND', 'no', 'Enable basic 20/20 Background Grid', 923, 15, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

UPDATE `configuration` SET `configuration_group_id` = '929' WHERE `configuration`.`configuration_id` = 932; 
UPDATE `configuration` SET `configuration_group_id` = '929' WHERE `configuration`.`configuration_id` = 947; 
UPDATE `configuration` SET `configuration_group_id` = '929' WHERE `configuration`.`configuration_id` = 953; 
UPDATE `configuration` SET `configuration_group_id` = '929' WHERE `configuration`.`configuration_id` = 1042;
UPDATE `configuration` SET `configuration_group_id` = '929' WHERE `configuration`.`configuration_id` = 1046;  

ALTER TABLE `categories` CHANGE `plan_id` `plan_id` INT(1) NOT NULL; 
ALTER TABLE `products_description` CHANGE `products_number` `products_number` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL; 
ALTER TABLE `products` ADD `products_x` INT(11) NOT NULL AFTER `products_season`; 
ALTER TABLE `products` ADD `products_y` INT(11) NOT NULL AFTER `products_x`; 
ALTER TABLE `products` ADD `products_w` INT(11) NOT NULL AFTER `products_y`; 
ALTER TABLE `products` ADD `products_h` INT(11) NOT NULL AFTER `products_w`; 
ALTER TABLE `products` ADD `products_r` INT(11) NOT NULL AFTER `products_h`; 
ALTER TABLE `products` ADD `products_sx` INT(11) NOT NULL AFTER `products_r`; 

INSERT INTO `admin_files` (`admin_files_id`, `admin_files_name`, `admin_files_is_boxes`, `admin_files_to_boxes`, `admin_groups_id`, `admin_files_type`, `admin_files_help_id`, `admin_new_group_id`) VALUES (NULL, 'design_data.php', '0', '0', '1', 'D', 'D', NULL); 

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1052, 'Map URL for Contact Us Page', 'CONTACT_MAP_URL', '', 'Map URL like Google Map', 917, 2, '2020-05-12 04:06:02', NULL, NULL, NULL);

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_4.sql',NOW());

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_4.sql',NOW());