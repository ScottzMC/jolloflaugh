SET sql_mode = 'ALLOW_INVALID_DATES';

DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 942;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 943;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 944;
DELETE FROM `configuration` WHERE `configuration`.`configuration_id` = 945;

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
(1032, 'Livestream & Video', 'VIDEO', 'false', 'Livestream & Video', 928, 1, '2020-08-12 03:33:56', NULL, NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
(1033, 'Account Livestream  Title', 'LIVESTREAM_TITLE', 'Livestream and Video', 'Heading  Title  for the  user  Account', 928, 2, '2020-05-12 04:06:02', NULL, NULL, NULL),
(1034, 'User Account Message Yes Livestream', 'ACCOUNT_MESSAGE_YES', 'Join the livestream...', 'Message in User Account when Livestream is Active', 928, 3, '2020-08-12 03:07:58', NULL, NULL, NULL),
(1035, 'Date ID of Livestream', 'LIVESTREAM_DATE_ID', '', 'Date ID of Livestream Event', 928, 4, '2020-08-12 03:48:16', NULL, NULL, NULL),
(1036, 'Alt. Channel URL', 'CHANNEL_LINK', '', 'Full Channel URL with https', 928, 5, '2020-08-12 03:52:22', NULL, NULL, NULL),
(1037, 'Alt. Livestream URL', 'LIVESTREAM_URL', 'https://www.osconcert.com/livestream', 'Full Livestream URL with https', 928, 6, '2020-08-12 03:17:45', NULL, NULL, NULL),
(1038, 'YouTube Channnel ID ', 'YOUTUBE_CHANNEL_ID', '', 'YouTube Channel ID', 928, 7, '2020-08-12 03:29:06', NULL, NULL, NULL),
(1039, 'You Tube  Livestream URL', 'YOUTUBE_LINK', 'https://www.youtube.com', 'Livestream URL Complete', 928, 8, '2020-08-12 02:51:53', NULL, NULL, NULL),
(1040, 'User Account Message No Livestream', 'ACCOUNT_MESSAGE_NO', 'Sorry No Livestream for this order', 'Message in User Account when Livestream is NOT Active', 928, 9, '2020-08-12 03:07:11', NULL, NULL, NULL);

UPDATE `customers_info_fields` SET `display_page` = 'C,E,B1,B2,A' WHERE `customers_info_fields`.`info_id` = 14; 

UPDATE `template` SET `include_column_left` = 'no' WHERE `template`.`template_id` = 3; 
UPDATE `template` SET `include_column_left` = 'no' WHERE `template`.`template_id` = 2;
UPDATE `template` SET `include_column_right` = 'no' WHERE `template`.`template_id` = 4; 
UPDATE `customers_info_fields` SET `active` = 'N' WHERE `customers_info_fields`.`info_id` = 2; 
UPDATE `customers_info_fields` SET `active` = 'N' WHERE `customers_info_fields`.`info_id` = 11; 
UPDATE `customers_info_fields` SET `active` = 'N' WHERE `customers_info_fields`.`info_id` = 13; 
UPDATE `customers_info_fields` SET `active` = 'N' WHERE `customers_info_fields`.`info_id` = 19; 
UPDATE `customers_info_fields` SET `active` = 'N' WHERE `customers_info_fields`.`info_id` = 20; 

UPDATE `configuration` SET `configuration_value` = 'yes' WHERE `configuration`.`configuration_id` = 951; 

UPDATE `configuration` SET `configuration_title` = 'Show Disabled Events With A Message' WHERE `configuration`.`configuration_id` = 273; 
UPDATE `configuration` SET `configuration_description` = 'Show a message \'Event Disabled Message\' when an EVENT is disabled in Concert Details' WHERE `configuration`.`configuration_id` = 273; 


INSERT INTO `sql_queries` VALUES('osconcert_v9_0_2.sql',NOW());

INSERT INTO `sql_queries` VALUES('osconcert_v9_0_2.sql',NOW());