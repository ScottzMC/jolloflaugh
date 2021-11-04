<?php
	/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
	*/
	
	// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	require('includes/application_top.php');
	require('includes/classes/http_client.php');
	// BOF: WebMakers.com Added: Downloads Controller - Free Shipping
	// Reset $shipping if free shipping is on and weight is not 0
	if (tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') && $cart->show_weight()!=0) 
	{
		$FSESSION->remove('shipping');
	}

	// EOF: WebMakers.com Added: Downloads Controller - Free Shipping
	// if the customer is not logged on, redirect them to the login page
	if (!$FSESSION->is_registered('customer_id')) 
	{
		$navigation->set_snapshot();
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	}
	if(tep_check_is_blocked_customer())
	{
		tep_redirect(tep_href_link(FILENAME_ACCOUNT,'isblocked=yes'));
	}
	if(tep_check_is_suspended_customer())
	{
		tep_redirect(tep_href_link(FILENAME_ACCOUNT,'suspended=yes'));
	} 
	// if there is nothing in the customers cart, redirect them to the shopping cart page
	//2014 - refunds
		if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))
		{
		include_once('includes/functions/ga_tickets.php');
		  ga_refund_cart_check();
		  tep_redirect(tep_href_link('checkout_process_refund.php', '', 'SSL'));
		  exit();
		  }
	//2017 bor
		elseif($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] ))
		{
			include_once('includes/functions/ga_tickets.php');
			  ga_refund_cart_check();
			  tep_redirect(tep_href_link('checkout_process_reservation.php', '', 'SSL'));
			  exit();	
	}elseif($cart->count_contents() < 1) 
	{
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
	}
	
	// Graeme 2012 Max_in_cart
	//It may well be that a customer can get add max to cart and then login adding this to a previously saved cart thus 'beating' the limit
//	so we add a little test here for that and, if too many tickets bounce them to the shopping cart page with a message
	if ( is_numeric(MAX_IN_CART_AMOUNT) && (MAX_IN_CART_AMOUNT>0) ) 
	{//if we have a numeric value >0
	if(($cart->count_contents() > MAX_IN_CART_AMOUNT )) {// > than limit quantity
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error_message='.MAX_IN_CART_TEXT));
		}
		}
	// Validate Cart for checkout
	$valid_to_checkout= true;

	$cart->get_products(true);

	// EOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller
	// if no shipping destination address was selected, use the customers own address as default
	if (!$FSESSION->is_registered('sendto')) 
	{
		$FSESSION->set('sendto',$FSESSION->get('customer_default_address_id','int',0));
	} else 
	{
		// verify the selected shipping address
		$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$FSESSION->get('customer_id','int',0) . "' and address_book_id = '" . (int)$FSESSION->get('sendto','int',0) . "'");
		$check_address = tep_db_fetch_array($check_address_query);
		if ($check_address['total'] != '1') {
			$FSESSION->set('sendto',$FSESSION->get('customer_default_address_id','int',0));
			if ($FSESSION->is_registered('shipping')) $FSESSION->remove('shipping');
			}
		}
		$cartID = $cart->cartID;
		//if (!$FSESSION->is_registered('cartID')) $FSESSION->set('cartID',$cartID);
		$FSESSION->set('cartID',$cartID);
		//2014 - refunds sh643d n6t get th5s far th64gh
		if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))
		{
		  tep_redirect(tep_href_link('checkout_process_refund.php', '', 'SSL'));
		  exit();
		}
		//2017 bor
		if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] ))
		{
		  tep_redirect(tep_href_link('checkout_process_reservation.php', '', 'SSL'));
		  exit();
		}
		if (NO_CHECKOUT_ZERO_PRICE=="1" && $cart->is_free_checkout())
		{
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS_FREE, '', 'SSL'));
		exit();
	}

	require(DIR_WS_CLASSES . 'order.php');
	$order = new order;
	// register a random ID in the session to check throughout the checkout procedure
	// against alterations in the shopping cart contents
	
	// if the order contains only virtual products, forward the customer to the billing page as
	// a shipping address is not needed
	// ICW CREDIT CLASS GV AMENDE LINE BELOW
	//  if ($order->content_type == 'virtual') {
	if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) 
	{
		$FSESSION->set('shipping',false);
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));	//by push
	}
	tep_check_country_differ();

	$total_weight = $cart->show_weight();
	$total_count = $cart->count_contents();

	// load all enabled shipping modules
	require(DIR_WS_CLASSES . 'shipping.php');
	$shipping_modules = new shipping;
	$quotes_available = $shipping_modules->quote();
	if(sizeof($quotes_available)<=0){
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
	}

	if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) 
	{
		$pass = false;
		switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
			case 'national':
				if ($order->delivery['country_id'] == STORE_COUNTRY) {
					$pass = true;
				}
			break;
			case 'international':
				if ($order->delivery['country_id'] != STORE_COUNTRY) {
					$pass = true;
				}
			break;
			case 'both':
				$pass = true;
			break;
		}

		$FSESSION->set('free_shipping',false);
		if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
			$FSESSION->set('free_shipping',true);
			include(DIR_WS_LANGUAGES . $FSESSION->language . '/modules/order_total/ot_shipping.php');
		}
		} else {
			$FSESSION->set('free_shipping',false);
		}
		// process the selected shipping method
		if ($FREQUEST->postvalue('action')== 'process')  
		{
			if (tep_not_null($FREQUEST->postvalue('comments'))) 
			{
				$comments = tep_db_prepare_input($FREQUEST->postvalue('comments'));
				$FSESSION->set('comments',$comments);
			}else {
				$FSESSION->remove('comments');
			}
			//$FSESSION->set('shipping',true);
			if (!$FSESSION->is_registered('shipping')) $FSESSION->set('shipping','');
			if ( (tep_count_shipping_modules() > 0) || ($FSESSION->free_shipping == true) ) { 
				if ( ($FREQUEST->postvalue('shipping')!='') && (strpos($FREQUEST->postvalue('shipping'), '_')) ) {
					$shipping=$FREQUEST->postvalue('shipping');
					list($module, $method) = explode('_', $shipping);
					if ( is_object($$module) || ($shipping == 'free_free') ) {
						if ($shipping == 'free_free') {
							$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
							$quote[0]['methods'][0]['cost'] = '0';
						} else {
							$quote = $shipping_modules->quote($method, $module);
						}
						if (isset($quote['error'])) {
							$shipping='';
						} else {
							if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
								$shipping = array('id' => $shipping,
												'title' => (($FSESSION->free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
												'cost' => $quote[0]['methods'][0]['cost']);
								$FSESSION->set('shipping',$shipping);
								tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
							}
						}
					} else {
						$FSESSION->remove('shipping');
					}
				} else {
					$messageStack->add('checkout_shipping','No shipping method selected for your order');
				}
			} else {
				$FSESSION->set('shipping',false);
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			}    
		}

		// get all available shipping quotes
		$quotes = $shipping_modules->quote();
		// if no shipping method has been selected, automatically select the cheapest method.
		// if the modules status was changed when none were available, to save on implementing
		// a javascript force-selection method, also automatically select the cheapest shipping
		// method if more than one module is now enabled
		if (!$FSESSION->is_registered('shipping') && $FSESSION->get('shipping') == false && tep_count_shipping_modules() > 1) {
			$shipping=$shipping_modules->cheapest();
			$FSESSION->set('shipping',$shipping);
		}
		$shipping=$FSESSION->getobject('shipping');

		require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_SHIPPING);

		$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
		$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
		$content = CONTENT_CHECKOUT_SHIPPING;
		$javascript = $content . '.js';
		
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
		
		require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
