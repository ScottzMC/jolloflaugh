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
  require(DIR_WS_CLASSES . "object_info.php");  

	// if the customer is not logged on, redirect them to the login page
	if (!$FSESSION->is_registered('customer_id')) {
	    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_WALLET_CHECKOUT_PAYMENT));
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
	
	$payment=tep_db_prepare_input($FREQUEST->postvalue('payment'));
	 $FSESSION->set('payment',$payment);
	
	 $FSESSION->set('wallet_amount',$FREQUEST->postvalue('wallet_amount'));
	
	/*if (!$FSESSION->is_registered('comments')) $FSESSION->set('comments','');*/
	if (tep_not_null($FREQUEST->postvalue('comments'))) {
		$FSESSION->set('comments',tep_db_prepare_input($FREQUEST->postvalue('comments')));
	}

	if($FSESSION->wallet_amount=="" || ((float)$FSESSION->wallet_amount<=0))  {
		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, 'error_message=' .urlencode(ERROR_NO_PAYMENT_MODULE_UPLOADS), 'SSL'));
	}

	// load the selected payment module
	require(DIR_WS_CLASSES . 'payment.php');
	$payment_modules = new payment($payment);
  
	//$payment_modules->update_status();
		
	if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($GLOBALS[$FSESSION->payment]))){// && (!$credit_covers) ) {
		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, 'error_message=' .urlencode(ERROR_UPLOAD_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
	}
   
	if (is_array($payment_modules->modules)) {
		$payment_modules->pre_confirmation_check();
	}

	require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_WALLET_CHECKOUT_CONFIRMATION);
	
	$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL'));
	$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_WALLET_CHECKOUT_CONFIRMATION, '', 'SSL'));
	
	$content = CONTENT_WALLET_CHECKOUT_CONFIRMATION;
	
	require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
	
	require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
