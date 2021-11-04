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
  require(DIR_WS_CLASSES . 'order.php');	
	
	
// if the customer is not logged on, redirect them to the shopping cart page
  if (!$FSESSION->is_registered('customer_id')) 
  {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
	$order_id = tep_db_prepare_input($FREQUEST->getvalue('order_id','int',0));
	
	if(!is_numeric($order_id) || $order_id == 0)
	{
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
	}
	$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
	$customer_number = tep_db_fetch_array($customer_number_query);

	if ($customer_number['customers_id'] != $FSESSION->customer_id) 
	{
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
	}
	
  // if ($FREQUEST->getvalue('action') == 'update') 
  // {
    // $notify_string = 'action=notify&';
    // $notify = tep_db_prepare_input($FREQUEST->postvalue('notify'));
    // if (!is_array($notify)) $notify = array($notify);
    // for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      // $notify_string .= 'notify[]=' . $notify[$i] . '&';
    // }
    // if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

    // tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  // }
	tep_verify_coupons($order_id );
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);

  $breadcrumb->add($NAVBAR_TITLE_2);
	
  $payment=tep_db_prepare_input($FREQUEST->getvalue('payment'));
 
		
		$orders_query = tep_db_query("select * from " . TABLE_ORDERS . " where customers_id = '" . (int)$FSESSION->customer_id . "' order by date_purchased desc limit 1");
		$orders = tep_db_fetch_array($orders_query);
		
		$customers_name=$orders['customers_name'];
		$billing_name=$orders['billing_name'];
		//$orders['customers_email_address'];
		//$orders['payment_method'];
		//$orders['date_purchased'];
		//$orders['shipping_method'];
		
		$products_array = array();
		$products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_model,products_id");
		while ($products = tep_db_fetch_array($products_query)) {
		//cartzone
			$products_array[] = array(	'id' => $products['products_id'],
										'qty' => $products['products_quantity'],
										'seat' => $products['products_name'],
										'date' => $products['products_model'],
										'type' => $products['events_type'],
										'sku' => $products['products_sku'],
										'categories_name' => $products['categories_name'],
										'concert_date' => $products['concert_date'],
										'concert_venue' => $products['concert_venue'],
										'concert_time' => $products['concert_time'],
										'is_printable' => $products['is_printable'],
										);
		}

	//} 		/*CodeReadrAPI*/
	
	// $order_total_query=tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$orders['orders_id'] . "' and class='ot_total'");
	// $order_total_result=tep_db_fetch_array($order_total_query);
	// $order_total_amount=substr($order_total_result['value'],0, -2);

	
	// if (CR_ACTIVE=="True"){$order=$_GET['order_id'];
	// include("cr_dbadd.php");}
	
	$check_customer_query = tep_db_query("select guest_account from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);
	  $guest=$check_customer['guest_account'];
	  
	//unset PWA cookie
	if ($_COOKIE['customer_is_guest'])
	{
	setcookie ("customer_is_guest", "", time() - 2592000);
	}
	
  $content = CONTENT_CHECKOUT_SUCCESS;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  
  // if ((PWA_LOGOFF=='true')&&($guest==1)) {
	  
		// if(isset($_COOKIE['customer_is_guest'])){
   
		// $cart->reset(true);

		// $FSESSION->remove('customer_id');
		// $FSESSION->remove('customer_default_address_id');
		// $FSESSION->remove('customer_first_name');
		// $FSESSION->remove('customer_country_id');
		// $FSESSION->remove('customer_zone_id');
		// $FSESSION->remove('comments');
		// if ($FSESSION->is_registered("customer_auto_name"))
		// $FSESSION->remove('customer_auto_name');
		// //ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
		// $FSESSION->remove('gv_id');
		// $FSESSION->remove('cc_id');
		// //ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik

		// if ($FREQUEST->cookievalue('osCuser')) setcookie ("osCuser", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('osCpass')) setcookie ("osCpass", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('fe_typo_user')) setcookie ("fe_typo_user", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('be_typo_user')) setcookie ("be_typo_user", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('bbsessionhash')) setcookie ("bbsessionhash", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('PHPSESSID')) setcookie ("PHPSESSID", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('bbuserid')) setcookie ("bbuserid", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('bbpassword')) setcookie ("bbpassword", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// if ($FREQUEST->cookievalue('customer_is_guest')) setcookie ("customer_is_guest", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
		// session_destroy();

		// }//end customer is guest
  // }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  

?>
