<?php
/*
  $Id: shopping_cart.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License


  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );

  require("includes/application_top.php");
  //require("includes/classes/message_stack.php");

if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] )){
$messageStack->add_session('header', 'Refund mode: please edit the cart using the left hand column', 'error');

tep_redirect(tep_href_link('index.php'));
exit();
}
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_SHOPPING_CART);
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));

// Validate Cart for checkout

  $valid_to_checkout= true;
  $cart->get_products(true);
  if (!$valid_to_checkout) {
//    $messageStack->add_session('header', 'Please update your order ...', 'error');
//    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  $content = CONTENT_SHOPPING_CART;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
