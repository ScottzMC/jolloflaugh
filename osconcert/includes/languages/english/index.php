<?php
/*

	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Released under the GNU General Public License 

*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();



//define('TEXT_MAIN', '');//ALREADY_DEFINED
//define('TABLE_HEADING_NEW_PRODUCTS', 'New Products For %s');

//define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Upcoming Products');
//define('TABLE_HEADING_DATE_EXPECTED', 'Date Expected');
define('TABLE_HEADING_DEFAULT_SPECIALS', 'Specials For %s');

//if ( ($category_depth == 'products') || ($FREQUEST->getvalue('manufacturers_id')!='') ) {
  define('HEADING_TITLE', 'Products');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Model');
  define('TABLE_HEADING_PRODUCTS', 'Product Name');
  define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
  define('TABLE_HEADING_QUANTITY', 'Quantity');
  define('TABLE_HEADING_PRICE', 'Price');
  define('TABLE_HEADING_WEIGHT', 'Weight');
  define('TABLE_HEADING_BUY_NOW', 'Buy Now');
  //define('TEXT_NO_PRODUCTS', 'There are no products to list in this category.');
  define('TEXT_NO_PRODUCTS2', 'There is no product available from this manufacturer.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Number of Products: ');
  define('TEXT_SHOW', '<b>Show:</b>');
  define('TEXT_BUY', 'Buy 1 \'');
  define('TEXT_NOW', '\' now');
  define('TEXT_ALL_CATEGORIES', 'All Categories');
  define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');
//} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Welcome to the ' . STORE_NAME .' Ticketing Online');
//} elseif ($category_depth == 'nested') {
  //define('HEADING_TITLE', 'Categories');
//}

// BOF: Lango added for Featured Products
  define('TABLE_HEADING_FEATURED_PRODUCTS', 'Featured Products');
  define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', 'Featured Products in %s'); 
 // define('TEXT_HEADING_SIMILAR_PRODUCTS','Similar Products');
  
  
 
define('TEXT_NO_FEATURED_PRODUCTS','No Featured Products');
define('TEXT_NO_UPCOMING_PRODUCTS','No Upcoming Products');
//define('TEXT_NO_SIMILAR_PRODUCTS','No Similar Products');
// EOF: Lango added for Featured Products

?>
