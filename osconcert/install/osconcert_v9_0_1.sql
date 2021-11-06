SET sql_mode = 'ALLOW_INVALID_DATES';

INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES
(427, 7, '1', 427, 'configuration.php', 'gID=928', 'P');

INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES
(427, 'Video', 1);

INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES ('428', '102', '1', '11', 'products_sales_new.php', '', 'P');
INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES ('429', '102', '1', '11', 'products_sales.php', '', 'P');
INSERT INTO `admin_menus` (`menu_id`, `parent_id`, `admin_groups_id`, `menu_pos`, `filename`, `params`, `menu_item_type`) VALUES ('430', '102', '1', '11', 'stats_sales_csv.php', '', 'P');

INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES ('428', 'osConcert SALES', '1');
INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES ('429', 'product GA SALES', '1');
INSERT INTO `admin_menus_description` (`menu_id`, `menu_text`, `language_id`) VALUES ('430', 'Sales CSV', '1');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(932, 'Allow product expiry', 'ALLOW_PRODUCT_EXPIRY', 'no', 'Allowed to set expiry time for individual GA products', 19, 20, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(934, 'Use buttons for Featured Categories', 'FEATURED_CATEGORY_BUTTONS', 'no', 'Use buttons instead of images for Featured Categories', 19, 21, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(935, 'Show Featured Categories', 'SHOW_FEATURED_CATEGORIES', 'false', 'Show Featured Categories under all SHOWS', 154, 47, '2020-06-17 12:25:58', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),');

#ALTER TABLE `categories`
 # DROP `coords`,
  #DROP `date_expires`,
  #DROP `date_commences`;
#ALTER TABLE `categories_description` DROP `concert_date_unix`;
#ALTER TABLE `categories` ADD `concert_date_unix` BIGINT(20) NOT NULL AFTER `categories_shipping`;

UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 257;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 916;

UPDATE `configuration_group` SET `configuration_group_title` = 'Category Products' WHERE `configuration_group`.`configuration_group_id` = 19;
UPDATE `configuration_group` SET `configuration_group_description` = 'Category Products settings' WHERE `configuration_group`.`configuration_group_id` = 19;
UPDATE `configuration_group` SET `configuration_access_key` = 'Products Advanced Category Products' WHERE `configuration_group`.`configuration_group_id` = 19;
UPDATE `admin_menus_description` SET `menu_text` = 'Category Products' WHERE `admin_menus_description`.`menu_id` = 416;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 915;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 272;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 282;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 247;
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 260;

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(936, 'Allow Box Office Blocker', 'BOX_OFFICE_BLOCKING', 'no', 'Allow Box Office Agents to Block Out Seats for Social Distancing purposes. ONLY WORKS WITH SEAT PLAN INTEGRATION NOT DESIGN MODE', 923, 19, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');



INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(938, 'Disallow Email Receipts for Box Office Agents', 'NO_BOXOFFICE_EMAIL', 'yes', 'Disallow Email Receipts for Box Office Agents', 923, 21, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(939, 'QR Text Separator', 'SEP', ';', 'QR Text Information Separator character', 920, 111, '2016-09-15 12:25:47', '2010-01-01', NULL, NULL);

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(940, 'Hide Search Events', 'HIDE_SEARCH_EVENTS', 'yes', 'Hide Search Events links in navigation', 19, 19, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`, `configuration_access_key`) VALUES (NULL, 'Video', 'Video', '0', '1', 'Marketing Video');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(946, 'Fill DATE-ID with Date and Time', 'AUTOFILL_DATEID', 'yes', 'Auto-Fill DATE-ID with Date and Time fields', 922, 14, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(947, 'Show the product expiry', 'SHOW_PRODUCT_EXPIRY', 'no', 'Show the product expiry date GA listings', 19, 24, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(948, 'Show EVENTS DATE FORMAT', 'SHOW_EVENTS_DATE_FORMAT', 'yes', 'Show EVENTS_DATE_FORMAT e.g d-mY in Concert Dates', 19, 25, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(949, 'Use CINE settings', 'USE_CINE', 'no', 'Using some CINEMA set up', 19, 30, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(950, 'Use CIRCLE buttons', 'USE_CIRCLE_BUTTONS', 'no', 'Using circle buttons in nested categories', 19, 31, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(951, 'Use buttons for nested categories', 'NESTED_CATEGORY_BUTTONS', 'no', 'Using buttons in nested categories', 19, 32, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(952, 'Show 7Day Navigation Tabs', 'NAVIGATE_7DAY_TABS', 'no', 'Show 7Day Navigation Tabs. Code needs to be set in templates/calendar_tabs.php', 19, 28, '2020-07-14 09:55:15', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(953, 'Allow full category expiry', 'ALLOW_CATEGORY_EXPIRY', 'no', 'Allow full category de-activation with expiry of categories', 19, 29, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),'),
(954, 'Display Product Description', 'PRODUCT_LIST_DESCRIPTION', '0', 'Do you want to display the Product Description?', 8, 12, '2010-01-01', '2010-01-01', NULL, NULL);

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(955, 'Low Stock Email', 'ADMIN_LOW_STOCK_EMAIL_SENT', '0', 'Send Low Stock Email To Administrator', '9', '100', NULL, NULL, NULL, NULL),
(956, 'Enable Low Stock Alert for GA products', 'ENABLE_LOW_STOCK_ALERT', 'false', 'Enable Low Stock Alert for GA products', 9, 99, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"true\", \"false\"),');

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(957, 'Show Data Tools', 'SHOW_ADMIN_DATATOOLS', 'no', 'Show Data Editing Tools for experienced Admins ONLY', 19, 61, '2010-01-01', '2010-01-01', NULL, 'tep_cfg_select_option(array(\"yes\", \"no\"),');

UPDATE `configuration` SET `configuration_title` = 'Homepage Show Featured Categories' WHERE `configuration`.`configuration_id` = 227;

UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 219; 
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 227; 
UPDATE `configuration` SET `configuration_group_id` = '19' WHERE `configuration`.`configuration_id` = 935; 


UPDATE `admin_menus` SET `menu_item_type` = 'X' WHERE `admin_menus`.`menu_id` = 39; 


INSERT INTO `sql_queries` VALUES('osconcert_v9_0_1.sql',NOW());

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_1.sql',NOW());