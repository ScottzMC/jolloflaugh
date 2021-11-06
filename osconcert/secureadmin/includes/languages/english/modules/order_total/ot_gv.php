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


  define('TEXT_GV_CART_QUANTITY', 'You currently have %s items in your cart');
  define('TEXT_GV_SEASON_QUANTITY', 'You also have a season ticket balance of %s ticket(s)');
  define('TEXT_ENTER_GV_CODE', 'How many do you wish to use?&nbsp;&nbsp;');
  define('MODULE_ORDER_TOTAL_GV_TITLE', 'Season ticket purchase');
  define('TEXT_GV_SEASON_NA', 'Season Ticket <br>No valid products in cart.&nbsp;');
  define('MODULE_ORDER_TOTAL_GV_TITLE','Season tickets');
  
  define('TEXT_GV_NOT_ALLOWED','You cannot use your existing season ticket(s) to purchase more season tickets.');
  
  #################################
  # popup text in payment_confirmation page
  # use of /n to force new lines
  # OK and CANCEL wording within the popup are browser 
  # controlled and therefore may differ between users
  #################################
  
  define('TEXT_GV_POPUP_CONTINUE','Click OK to continue checkout or \nCANCEL to return to the page and edit your selections.');
  define('TEXT_GV_POPUP_NOTUSE','You have season tickets available but have not selected the option to use them.\n');
  define('TEXT_GV_POPUP_NOTUSE_MAX','You have season tickets available but have not used the maximum amount possible.\n');
  
  define('TEXT_SEASON_CANCEL',' CHECKBOX to Cancel season ticket use/start over.');
?>