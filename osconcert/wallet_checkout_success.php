<?php
/*
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	http://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/



// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');

	// if the customer is not logged on, redirect them to the shopping cart page
	if (!$FSESSION->is_registered('customer_id')) {
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	}

  $insert_id=$FREQUEST->getvalue('insert_id','int',0);
  
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_WALLET_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1,tep_href_link(FILENAME_ACCOUNT));
  $breadcrumb->add(NAVBAR_TITLE_2);

  $content = CONTENT_WALLET_CHECKOUT_SUCCESS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
