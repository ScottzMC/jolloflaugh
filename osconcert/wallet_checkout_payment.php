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
	// if the customer is not logged on, redirect them to the login page
	if (!$FSESSION->is_registered('customer_id')) {
		$navigation->set_snapshot();
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}

	/*if (!$FSESSION->is_registered('payment_page')){
		$FSESSION->set('payment_page','wallet');
	}*/
	 $FSESSION->set('payment_page','wallet');

	// check for payment already exits
	
	if (($FSESSION->is_registered('payment') && $FSESSION->payment=="wallet")){
		$FSESSION->remove("payment");
	}
	/*if (!$FSESSION->is_registered('billto')) {
		$FSESSION->set('billto',$FSESSION->customer_default_address_id);
	}*/$FSESSION->set('billto',$FSESSION->customer_default_address_id);
	if (!$FSESSION->is_registered('wallet_timestamp')) $FSESSION->set('wallet_timestamp','');	
	// load all enabled payment modules
	require(DIR_WS_CLASSES . 'payment.php');
	$payment_modules = new payment;
	//$payment_modules->update_status();
	
	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_WALLET_CHECKOUT_PAYMENT);
	$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL'));

	
	$content = CONTENT_WALLET_CHECKOUT_PAYMENT;
	$javascript= $content . '.js.php';
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>