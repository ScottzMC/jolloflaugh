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


define('NAVBAR_TITLE_1', 'Advanced Search');
define('NAVBAR_TITLE_2', 'Search Results');

define('HEADING_TITLE_1', 'Advanced Search');
define('HEADING_TITLE_2', 'Items meeting the search criteria');

define('HEADING_SEARCH_CRITERIA', 'Search Criteria');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Search Descriptions');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Include Subcategories');
define('ENTRY_MANUFACTURERS', 'Manufacturers:');
define('ENTRY_PRICE_FROM', 'Price From:');
define('ENTRY_PRICE_TO', 'Price To:');
define('ENTRY_DATE_FROM', 'Date From:');
define('ENTRY_DATE_TO', 'Date To:');

define('TEXT_SEARCH_HELP_LINK', 'Search Help ');

define('TEXT_ALL_CATEGORIES', 'All Categories');
//define('TEXT_SERVICES','Services');
//define('TEXT_EVENTS','Events');
//define('TEXT_SUBSCRIPTIONS','Subscriptions');
define('TEXT_PRODUCTS_1','Products');

define('HEADING_SEARCH_HELP', 'Search Help');
define('TEXT_SEARCH_HELP', 'Keywords may be separated by AND and/or OR statements for greater control of the search results.<br><br>For example, <u>Microsoft AND mouse</u> would generate a result set that contain both words. However, for <u>mouse OR keyboard</u>, the result set returned would contain both or either words.<br><br>Exact matches can be searched for by enclosing keywords in double-quotes.<br><br>For example, <u>"notebook computer"</u> would generate a result set which match the exact string.<br><br>Brackets can be used for further control on the result set.<br><br>For example, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_WEIGHT', 'Weight');
define('TABLE_HEADING_BUY_NOW', 'Buy Now');
//define('TABLE_HEADING_EVENTS','Events');
//define('TABLE_HEADING_SUBSCRIPTIONS','Subscription Name');
define('TEXT_NO_ITEMS','There is no Item that matches the search criteria.');
define('TEXT_NO_PRODUCTS', 'There is no product that matches the search criteria.');
//define('TEXT_NO_EVENTS', 'There is no event that matches the search criteria.');
//define('TEXT_NO_SUBSCRIPTIONS', 'There is no subscription that matches the search criteria.');

define('ERROR_AT_LEAST_ONE_INPUT', 'At least one of the fields in the search form must be entered.');
define('ERROR_INVALID_FROM_DATE', 'Invalid From Date.');
define('ERROR_INVALID_TO_DATE', 'Invalid To Date.');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'To Date must be greater than or equal to From Date.');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Price From must be a number.');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Price To must be a number.');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Price To must be greater than or equal to Price From.');
define('ERROR_INVALID_KEYWORDS', 'Invalid keywords.');
define('TEXT_DISPLAY_ITEMS','Display %s to %s Items ( of %s)');
?>
