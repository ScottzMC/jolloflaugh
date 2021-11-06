<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
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

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_PASSWORD_FORGOTTEN);

	$action=$FREQUEST->getvalue('action');
  if ($action == 'process') {
    $email_address = $FREQUEST->postvalue('email_address');
    $check_customer_query = tep_db_query("select customers_gender,customers_email_address,customers_firstname,customers_lastname, customers_password,customers_telephone,customers_id from " . TABLE_CUSTOMERS .  "  where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);
//new code - create token and url
      $token = sha1(uniqid(rand(99999,9999999), true));
	  $url   = tep_href_link(FILENAME_PASSWORD_RESET,'token='.$token);
	  
      //$new_password =tep_href_link(FILENAME_PASSWORD_RESET,'token='.$token);
      //$crypted_password = tep_encrypt_password($new_password);

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_timestamp = '" . tep_db_input($_SERVER["REQUEST_TIME"]) . "', customers_token='" . $token . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");

      //send mails about forgot password
      $merge_details=array();
	  $send_details=array();
	  $merge_details[TEXT_FN]=$check_customer['customers_firstname'];
	  $merge_details[TEXT_LN]=$check_customer['customers_lastname'];
	  $merge_details[TEXT_LE]=2;// <----- change this is you want to expand/reduce time avaialable
	  $merge_details[TEXT_LP]=$url;
	  $merge_details[TEXT_SM]=STORE_NAME;
	  $merge_details[TEXT_SN]=STORE_OWNER;
	  $merge_details[TEXT_SE]=STORE_OWNER_EMAIL_ADDRESS;

      if (ACCOUNT_GENDER == 'true') {
         if ($check_customer['customers_gender'] == 'm') {
           $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MR, $check_customer['customers_lastname']);
         } else {
           $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_MS, $check_customer['customers_lastname']);
         }
      } else {
        $merge_details[TEXT_GR] = sprintf(EMAIL_GREET_NONE, $check_customer['customers_firstname']);
      }

     $send_details[0]['to_name']=$check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'];
	 $send_details[0]['to_email']=$email_address;
	 $send_details[0]['from_name']=STORE_OWNER;
	 $send_details[0]['from_email']=STORE_OWNER_EMAIL_ADDRESS;

     tep_send_default_email('CUX',$merge_details,$send_details);

      $messageStack->add_session('login', SUCCESS_PASSWORD_SENT, 'success');

      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

  $content = CONTENT_PASSWORD_FORGOTTEN;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
