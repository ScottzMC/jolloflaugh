SET sql_mode = 'ALLOW_INVALID_DATES';

INSERT INTO `admin_files` (`admin_files_id`, `admin_files_name`, `admin_files_is_boxes`, `admin_files_to_boxes`, `admin_groups_id`, `admin_files_type`, `admin_files_help_id`, `admin_new_group_id`) VALUES
(1216, 'event_report.php', 0, 0, '1', 'D', 'D', 28);
INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES
(431, 8, '1', 413, 'event_report.php', '', 'P');
INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES
(431, 'Active Event Report', 1);

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1041, 'Show osConcert Help Messages', 'SHOW_OSCONCERT_HELP', 'yes', 'Show osConcert Help Messages around the admin', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1042, 'Hour of expiry', 'TIME_CATEGORIES_EXPIRE', '21', 'How many hours after midnight categories expire in relation to your TimeZone.', 19, 29, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"6\",\"7\",\"8\",\"9\",\"10\", \"11\", \"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1043, 'Featured Categories Orderby', 'FEATURED_CATEGORIES_ORDERBY', 'concert_date_unix', 'Featured Categories Orderby Date or Sort Order', 19, 80, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'concert_date_unix\', \'sort_order\'),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1044, 'Use Pop Up Modal', 'USE_POPUP', 'no', 'Use Pop Up Modal for Alternative Shopping Projects Only', 917, 51, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');

UPDATE `customers_info_fields` SET `default_value` = 'captcha' WHERE `customers_info_fields`.`info_id` = 32; 
UPDATE `customers_info_fields_description` SET `error_text` = 'Security code must not be empty##Invalid Security code' WHERE `customers_info_fields_description`.`info_id` = 32; 

UPDATE `configuration` SET `configuration_description` = 'GA Multiple Quantities or BUY NOW buttons' WHERE `configuration`.`configuration_id` = 557; 

UPDATE `configuration` SET `configuration_title` = 'GA Enable Multiple Quantity Selection' WHERE `configuration`.`configuration_id` = 557; 

UPDATE `configuration` SET `configuration_title` = 'GA Display Cart After Adding Product' WHERE `configuration`.`configuration_id` = 209; 

UPDATE `configuration` SET `configuration_description` = 'Display the shopping cart after adding a product (or go direct to checkout)' WHERE `configuration`.`configuration_id` = 209; 

UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 209; 
UPDATE `configuration` SET `configuration_value` = '0' WHERE `configuration`.`configuration_id` = 954; 

DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 272;


INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1045, 'Show Orders Products Status (admin check) ', 'SHOW_ORDERS_PRODUCTS_STATUS', 'no', 'Admin check orders products status - Edit>Order', 917, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'yes\', \'no\'),');


INSERT INTO `sql_queries` VALUES('osconcert_v9_0_3.sql',NOW());

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_3.sql',NOW());