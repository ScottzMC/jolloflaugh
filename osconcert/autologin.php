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

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_AUTOLOGIN);


    $email_address = $FREQUEST->getvalue('email');
    $password =$FREQUEST->getvalue('id');
    $serverDate = date('Y-m-d H:i:s',getServerDate(false));
// Check if email exists
    
	
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_password='" . tep_db_input($password) . "'");

	if (tep_db_num_rows($check_customer_query)<=0) {
  	  $messageStack->add('login', TEXT_LOGIN_ERROR);
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
      $check_customer = tep_db_fetch_array($check_customer_query);


        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        $FSESSION->set('customer_id',$customer_id);
        $FSESSION->set('customer_default_address_id',$customer_default_address_id);
        $FSESSION->set('customer_first_name',$customer_first_name);
        $FSESSION->set('customer_country_id',$customer_country_id);
        $FSESSION->set('customer_zone_id',$customer_zone_id);

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon ='". $serverDate ."', customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$FSESSION->customer_id . "'");

// restore cart contents
        $cart->restore_contents();

        tep_redirect(tep_href_link(FILENAME_ACCOUNT));
?>
