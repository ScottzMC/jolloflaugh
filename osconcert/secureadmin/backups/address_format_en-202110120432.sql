# osConcert: Thanks to: osCommerce, Open Source E-Commerce Solutions
# http://www.oscommerce.com
#
# Database Backup For osConcert
# Copyright (c) 2021 osConcert
#
# Database: en
# Database Server: 127.0.0.1
#
# Backup Date: 10/12/2021 04:32:34
# Backed up tables: address_format

drop table if exists `address_format`;
CREATE TABLE `address_format` (
  `address_format_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_format` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address_summary` varchar(48) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`address_format_id`)
);

insert into `address_format` (`address_format_id`, `address_format`, `address_summary`) values ('1','$firstname $lastname$cr$streets$cr$city$cr$postcode$cr$statecomma$country$cr$customer_email','$city $postcode $state/$country'),
('2','$firstname $lastname$cr$streets$cr$city$cr$postcode$cr$statecomma$country$cr$customer_email','$city $postcode $state/$country'),
('3','$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country$cr$customer_email','$state / $country'),
('4','$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country$cr$customer_email','$postcode / $country'),
('5','$firstname $lastname$cr$streets$cr$postcode $city$cr$country$cr$customer_email','$postcode $city / $country');

DROP VIEW IF EXISTS `carts_united`;
CREATE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `carts_united` AS select `cb`.`customers_basket_id` AS `customers_basket_id`,`cb`.`products_id` AS `products_id`,`cb`.`customers_id` AS `customers_id`,`cb`.`customers_basket_date_added` AS `customers_basket_date_added`,`cb`.`customers_basket_quantity` AS `customers_basket_quantity`,`cb`.`discount_id` AS `discount_id` from `customers_basket` `cb` union select `tb`.`customers_basket_id` AS `customers_basket_id`,`tb`.`products_id` AS `products_id`,`tb`.`customers_id` AS `customers_id`,`tb`.`customers_basket_date_added` AS `customers_basket_date_added`,`tb`.`customers_basket_quantity` AS `customers_basket_quantity`,`tb`.`discount_id` AS `discount_id` from `customers_temp_basket` `tb`;