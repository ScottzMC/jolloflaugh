<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
	define( '_FEXEC', 1 );
	
  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $FSESSION->get('language') . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);
  		//Block accounts that are not visitor
 // $check_customer_query = tep_db_query("select a.*,c.* from " . TABLE_CUSTOMERS ." c, " . TABLE_ADDRESS_BOOK . " a  where c.customers_id='" . $FSESSION->customer_id . "' and c.customers_id=a.customers_id");
//      $check_customer = tep_db_fetch_array($check_customer_query);
//	  $a=$check_customer["entry_company"];
//		$findme   = 'Visitor';
//		$pos = strpos($a, $findme);
//
//		if ($pos === false) {
//		//echo "The string '$findme' was not found in the string '$a'.";
//		tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set is_blocked='Y' where customers_id=" . (int)$check_customer["customers_id"]);
//		tep_redirect(tep_href_link(FILENAME_LOGOFF));
//		}
//		else {
//		//echo "The string '$findme' was found in the string '$a',";		
//		} //end Block accounts that are not visitor

 //if ($FSESSION->is_registered('customer_is_guest')){
 if(isset($_COOKIE['customer_is_guest']))
 {
 tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));
 }
 
  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array($FSESSION->NAME)), $navigation->snapshot['mode']);
	
    $post_array=$navigation->snapshot['post'];
	if(!is_array($post_array)) { 
		$post_array=array();
	}
    $navigation->clear_snapshot();

	$submit_string="<html><head></head><body><form name='frm_login_post' id='frm_login_post' method='post' action='".$origin_href."'>";
		//while(list($name,$value)=each($post_array)) 
		foreach($post_array as $name => $value)
		$submit_string.="<input type='hidden' name='".$name."' id='".$name."' value='".$value."'>";												
	$submit_string.="</form></body></html>";
	//echo $submit_string;
  } else {
    $origin_href = tep_href_link(FILENAME_DEFAULT);
  }
	if(ADMIN_SIGNUP_NOTIFICATION=='true'){
	$send_details=array();	
	//build merge details
	$merge_details=array();
	
	define("TEXT_SM","STORE_NAME");
	define("TEXT_SN","STORE_OWNER");
	define("TEXT_SE","STORE_OWNER_EMAIL_ADDRESS");
	$merge_details[TEXT_SM]=STORE_NAME;
	$merge_details[TEXT_SN]=STORE_OWNER;
	$merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;
	$send_details[0]['from_name']=STORE_OWNER;
	$send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;
	$send_details[0]['to_email']=STORE_OWNER_EMAIL_ADDRESS;
	
	tep_send_default_email("ADM",$merge_details,$send_details);
	}


  $content = CONTENT_CREATE_ACCOUNT_SUCCESS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
