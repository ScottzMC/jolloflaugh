<?php
/*
	 
	
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
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
  $cart->reset(true);
  $FSESSION->remove('box_office_refund');
  
  if($_SESSION['customer_country_id']==999 ){

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/bor_listings.php');
  	  // delete old orders
	  
	  //get seatplan class
	  require(DIR_WS_CLASSES.'seatplan.php');
      $sp = new seatplan;
	  //cancel aged orders
	$time_now = date('Y-m-d H:i:s',getServerDate(false));

	  $bor_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " 
									where bor_datetime <= '" . $time_now . "' 
									AND bor_datetime > 0
									AND orders_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."'");
		if (tep_db_num_rows($bor_query) > 0 ) { 
		while ($bor_results = tep_db_fetch_array($bor_query)){
			$sp->canx_order($bor_results['orders_id'],TEXT_AUTO_RESTOCK);
		}
		}



  $content = 'bor_listings';
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  } else { exit(BOR_ERROR);}
?>
