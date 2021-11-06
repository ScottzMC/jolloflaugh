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

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_subscription_newsletter,customers_reserve_newsletter,customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);
  if ($FREQUEST->postvalue('action') == 'process') {
	$sql_array=array();
	if(ACCOUNT_NEWSLETTER=='true'){
     if ($FREQUEST->postvalue('newsletter_general')!='' && is_numeric($FREQUEST->postvalue('newsletter_general'))){
   	   $sql_array["customers_newsletter"]="1";
     } else {
		$sql_array["customers_newsletter"]="0";
	 }
	}
	if(ACCOUNT_RESERVATION_NEWSLETTER=='true'){
	  if($FREQUEST->postvalue('newsletter_reserve')!='' && is_numeric($FREQUEST->postvalue('newsletter_reserve'))){
		$sql_array["customers_reserve_newsletter"]="1";
	  } else {
		$sql_array["customers_reserve_newsletter"]="0";
	  }
	}
	if(ACCOUNT_SUBSCRIPTION_NEWSLETTER=='true'){
	  	if ($FREQUEST->postvalue('subscription_newsletter')!='' && is_numeric($FREQUEST->postvalue('subscription_newsletter'))) {
		$sql_array["customers_subscription_newsletter"]="1";
	  } else {
		$sql_array["customers_subscription_newsletter"]="0";
	  }
	 }
	 if (count($sql_array)>0){
		 tep_db_perform(TABLE_CUSTOMERS,$sql_array,"update","customers_id='" . (int)$FSESSION->customer_id . "'");
	}

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

  $content = CONTENT_ACCOUNT_NEWSLETTERS;
  
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>