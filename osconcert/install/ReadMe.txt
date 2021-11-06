www.osconcert.com

A new feature to unite the temporary and customer shopping baskets requires a database 'view' rather than a 'table'.

Therefore we need to create a 'view' with a 'security definer'.

Ther security definer is usually the database super user e.g. root @ localhost. This script below will update the current user.

You will find this user defined in you phpMyAdmin. 
e.g

DROP VIEW IF EXISTS `carts_united`;
CREATE ALGORITHM=UNDEFINED DEFINER=CURRENT_USER SQL SECURITY DEFINER VIEW `carts_united` AS select `cb`.`customers_basket_id` AS `customers_basket_id`,`cb`.`products_id` AS `products_id`,`cb`.`customers_id` AS `customers_id`,`cb`.`customers_basket_date_added` AS `customers_basket_date_added`,`cb`.`customers_basket_quantity` AS `customers_basket_quantity`,`cb`.`discount_id` AS `discount_id` from `customers_basket` `cb` union select `tb`.`customers_basket_id` AS `customers_basket_id`,`tb`.`products_id` AS `products_id`,`tb`.`customers_id` AS `customers_id`,`tb`.`customers_basket_date_added` AS `customers_basket_date_added`,`tb`.`customers_basket_quantity` AS `customers_basket_quantity`,`tb`.`discount_id` AS `discount_id` from `customers_temp_basket` `tb`;
