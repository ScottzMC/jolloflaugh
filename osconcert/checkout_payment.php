<?php	/*	osCommerce, Open Source E-Commerce Solutions 	http://www.oscommerce.com 		Copyright (c) 2003 osCommerce 		Freeway eCommerce 	http://www.openfreeway.org	Copyright (c) 2007 ZacWare		osConcert, Online Seat Booking   	https://www.osconcert.com  	Copyright (c) 2009/2010/2011 osConcert	Released under the GNU General Public License	*/		// Set flag that this is a parent file	define( '_FEXEC', 1 );	require('includes/application_top.php');			if($_SESSION['customer_country_id']==999 && isset($_SESSION['draggable'] ))		{									$FSESSION->remove('draggable');						//tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));		}		/*========== Added by Ivijan-Stefan <creativform@gmail.com> 13.05.2017. ============*/	if($FSESSION->get("customer_country_id") == 999)	{		$addresses_query = tep_db_query("SELECT entry_country_id AS country_id FROM " . TABLE_ADDRESS_BOOK . " WHERE address_book_id = '" . (int)$FSESSION->get('billto') . "'");		$addresses = tep_db_fetch_array($addresses_query);		if($addresses['country_id'] == NULL)		{			$FSESSION->set('billto',(int)$FSESSION->get('sendto'));		}	}	else	{		$FSESSION->set('billto',(int)$FSESSION->get('sendto'));	}/*==================================================================================*/	// modified for wallet payment -start	if ($FSESSION->is_registered("payment_page") && $FSESSION->get('payment_page')=="wallet" && ($FREQUEST->getvalue('error')!='' || $FREQUEST->getvalue('payment_error')!='')){		tep_redirect(tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT,tep_get_all_input_params() .'&validID=' . $FSESSION->get('checkID'),'SSL'));	}	// modified for wallet payment -end	//error count start	//if($FREQUEST->postvaluecomments'] && $FSESSION->is_registered('comments')) 	if($FREQUEST->getvalue('error')!='' || $FREQUEST->getvalue('payment_error')!=''){		$error_count=(int)$FSESSION->get('error_count');				$FSESSION->set('error_count',$error_count+1);		$exp_count=300;				if(defined('CHECKOUT_FAIL_RETRY')) 	$exp_count=(int)CHECKOUT_FAIL_RETRY; 				if($FSESSION->get('error_count')>$exp_count){			//tep_session_recreate();			$FSESSION->remove('customer_id');			tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));		}	} 	//error count end 	// if the customer is not logged on, redirect them to the login page	if (!$FSESSION->is_registered('customer_id')) {		$navigation->set_snapshot();		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));	}	// if we have been here before and are coming back get rid of the credit covers variable	$FSESSION->remove('credit_covers');//CCGV    tep_verify_coupons(); 	// if there is nothing in the customers cart, redirect them to the shopping cart page	// skip for box off refund and reservations 	if(($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] )) || $_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] )){		//skip - do nothing		}elseif ($cart->count_contents() < 1) {		  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));	}	// if no shipping method has been selected, redirect the customer to the shipping method selection page	if (!$FSESSION->is_registered('shipping')) {		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));	}		// avoid hack attempts during the checkout procedure by checking the internal cartID	if (isset($cart->cartID) && $FSESSION->is_registered('cartID')) {		if ($cart->cartID != $FSESSION->get('cartID')) {			tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));		}	}	$cartID = $cart->cartID;   	//if (!$FSESSION->is_registered('cartID')) $FSESSION->set('cartID',$cartID);	$FSESSION->set('cartID',$cartID); 	if (NO_CHECKOUT_ZERO_PRICE=="1" && $cart->is_free_checkout()) 		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS_FREE, '', 'SSL'));   	// Stock Check	if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {		$products = $cart->get_products();		for ($i=0, $n=sizeof($products); $i<$n; $i++) {			if ($products[$i]['element_type']=='P'){				if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {					tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));					break;				}			}		}	}		// modified for wallet payment -start	/*if (!$FSESSION->is_registered("payment_page")){	$FSESSION->set('payment_page','default');	}*/		$FSESSION->set('payment_page','default');	// modified for wallet payment -end	// if no billing destination address was selected, use the customers own address as default	if (!$FSESSION->is_registered('billto')) 	{		$FSESSION->set('billto',$FSESSION->customer_default_address_id);	} else {		// verify the selected billing address		$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->customer_id . "' and address_book_id = '" . (int)$FSESSION->billto . "'");		$check_address = tep_db_fetch_array($check_address_query);		if ($check_address['total'] != '1') 		{		//	$FSESSION->set('billto',$FSESSION->customer_default_address_id);			if ($FSESSION->is_registered('payment')) $FSESSION->remove('payment');		}	}		if ($FSESSION->get('shipping')==false){		$FSESSION->set('sendto',$FSESSION->billto);	}	require(DIR_WS_CLASSES . 'order.php');	$order = new order;	tep_check_country_differ(true);	if(tep_not_null($FREQUEST->postvalue('comments')))	$FSESSION->set('comments',$FREQUEST->postvalue('comments'));				//Box Office - if a customer gets here with a billing country set as Box Office then the zone id is 999 - this must be a valid Box Office operator as that billing country may not be selected from the front end customer account creation page so		//2014 - refunds		if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))		{		  tep_redirect(tep_href_link('checkout_process_refund.php', '', 'SSL'));		  exit();		}		elseif($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] ))		{		  tep_redirect(tep_href_link('checkout_process_reservation.php', '', 'SSL'));		  exit();		}		//		if($_SESSION['customer_country_id']==999)		{		//if ($order->billing['country']['id'] == 999){				$FSESSION->set('BoxOffice','999');		if(DIRECT_CHECKOUT=='true')		{		 tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS_FREE, '', 'SSL'));		}		//end BoxOffice		}					// if (!$FSESSION->is_registered('order_timestamp')) tep_session_register('order_timestamp');		$order_timestamp=time();	//$referenceID= substr(strtolower($order->customer['firstname']),0,3) . substr(strtolower($order->customer['lastname']),0,3) . $order_timestamp ;	//$referenceID= $order_timestamp ;	$referenceID= substr(strtolower($order->customer['username']),0,5) . $order_timestamp ;	if (!$FSESSION->is_registered('referenceID')) $FSESSION->set('referenceID',$referenceID);			$total_weight = $cart->show_weight();	$total_count = $cart->count_contents();	//NOT IN USE	//$total_count = $cart->count_contents_virtual(); //ICW ADDED FOR CREDIT CLASS SYSTEM		// load all enabled payment modules	require(DIR_WS_CLASSES . 'payment.php');	$payment_modules = new payment;	//if ($FSESSION->is_registered('payment')) $FSESSION->remove('payment');		require(DIR_WS_CLASSES . 'order_total.php'); // CCGV    $order_total_modules = new order_total; // CCGV	/*	if ($order->billing['country']['id'] == 999){		if(DIRECT_CHECKOUT=='true'){			// skip if only 1 payment method available			if (tep_count_payment_modules() == 1) {			  tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));			}		}	}*/	// skip if only 1 payment method available/installed (in development)	if(SKIP_PAYMENT_METHOD=='true')	{	if (tep_count_payment_modules() == 1) 	{	// here we need to set the payment session	// 	$payment_to_use ='';	$modules_array = preg_split('/;/', MODULE_PAYMENT_INSTALLED);    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) 	{      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));      if (is_object($GLOBALS[$class])) 	  {        if ($GLOBALS[$class]->enabled) 		{          $payment_to_use .= $class;        }      }    }	    $FSESSION->set('payment',$payment_to_use );		     tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));	} 	}	require(DIR_WS_LANGUAGES . $FSESSION->get('language') . '/' . FILENAME_CHECKOUT_PAYMENT);		$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));	$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));		$content = CONTENT_CHECKOUT_PAYMENT;	$javascript ='checkout_payment.js.php';		require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);		require(DIR_WS_INCLUDES . 'application_bottom.php');?>