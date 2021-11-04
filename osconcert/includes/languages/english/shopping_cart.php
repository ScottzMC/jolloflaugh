<?php
/*

	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.zac-ware.com/freeway 
	Copyright (c) 2007 ZacWare 
	
	Released under the GNU General Public License
*/
defined('_FEXEC') or die();
//cartzone
//define('IMAGE_BUTTON_CLEAR_CART', 'Clear');

define('NAVBAR_TITLE', 'My Ticket Order');
define('HEADING_TITLE', 'What Items Are In My Order?');
define('TABLE_HEADING_REMOVE', 'Update/Remove');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_MODEL', 'Ticket Number');
//define('TABLE_HEADING_PRODUCTS', 'Item(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'There Are No Items In Your Cart!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total');

//define('TEXT_DISCOUNT_ALERT2','Don\'t forget to check for discount. Some items have discount options');
//define('TEXT_DISCOUNT_ALERT','Click on the ticket icon and check for discount');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Items marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' do not exist.<br>Please remove items marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Items marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' do not exist.<br>Please remove items marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you');
define('TEXT_TYPE','Type:');
define('TEXT_START_DATE','Start Date');
define('TEXT_END_DATE','End Date');
define('TEXT_SHOPPING_CART','Ticket Order');
define('TEXT_SKU','');

//added Aug 2012 sakwoya@sakwoya.co.uk (Graeme Tyson)
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_GA','***');
define('OUT_OF_STOCK_CANT_CHECKOUT_GA', 'Tickets marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK_GA . ' are not available in the quantity you desire.<br>Please adjust the quantity that you wish to order ');
define('OUT_OF_STOCK_CANT_CHECKOUT_GA_TRAILER', ' tickets available.');
define('OUT_OF_STOCK_CAN_CHECKOUT_GA', 'Tickets marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK_GA . ' are not available in the quantity you desire.<br>Please adjust the quantity that you wish to order');
define('OUT_OF_STOCK_CANT_CHECKOUT_GA_MASTER','There are limited tickets left for a show you are trying to book. Please adjust the quantities that you wish to order');
define ('STOCK_CHANGE_LEFT', 'There are only ');
define ('STOCK_CHANGE_RIGHT', ' tickets remaining for this show');


?>