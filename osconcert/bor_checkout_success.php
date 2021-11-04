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
  if (!$FSESSION->is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
	//attempted access by non BoxOffice
	if(!$_SESSION['customer_country_id']==999 ){exit('..');}

	$order_id = tep_db_prepare_input($FREQUEST->getvalue('order_id','int',0));
	
	if(!is_numeric($order_id) || $order_id == 0)
	{
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
	}

  if ($FREQUEST->getvalue('action') == 'update') {}

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/bor_checkout_success.php');

  $breadcrumb->add(NAVBAR_TITLE_1);

    $NAVBAR_TITLE_2 = NAVBAR_TITLE_2; 
    $HEADING_TITLE = HEADING_TITLE; 
    $TEXT_SUCCESS = TEXT_SUCCESS; 
 
  $breadcrumb->add($NAVBAR_TITLE_2);
	
  $payment=tep_db_prepare_input($FREQUEST->getvalue('payment'));
  
  $products_array = array();
		$products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' order by products_model,products_id");
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

  $content = BOR_CONTENT_CHECKOUT_SUCCESS;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>