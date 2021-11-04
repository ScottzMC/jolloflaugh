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

  if (!$FSESSION->is_registered('customer_id')) {
    $navigation->set_snapshot();
	$return=($FREQUEST->getvalue("R")!=''?"R=1":'');
    tep_redirect(tep_href_link(FILENAME_LOGIN, $return, 'SSL'));
  }

  if ($FREQUEST->getvalue('order_id')=="" || ($FREQUEST->getvalue('order_id')!='' && !is_numeric($FREQUEST->getvalue('order_id')))) {
   	tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
	}
  
  $customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$FREQUEST->getvalue('order_id') . "'");
  $customer_info = tep_db_fetch_array($customer_info_query);
  if ($customer_info['customers_id'] != $FSESSION->customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $FREQUEST->getvalue('order_id')), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $FREQUEST->getvalue('order_id'), 'SSL'));
 
/* $order_query = tep_db_query("select * from orders where orders_id=18");
 $order = tep_db_fetch_array($order_query);*/
  require(DIR_WS_CLASSES . 'order.php');
 
  $order = new order($FREQUEST->getvalue('order_id'));
	
  $content = CONTENT_ACCOUNT_HISTORY_INFO;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
