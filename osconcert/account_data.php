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
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/account_data.php');

  if ($FREQUEST->postvalue('action')!='' && ($FREQUEST->postvalue('action') == 'process')) {
    $password_current = tep_db_prepare_input($FREQUEST->postvalue('password_current'));
  //  $password_new = tep_db_prepare_input($FREQUEST->postvalue('password_new'));
  //  $password_confirmation = tep_db_prepare_input($FREQUEST->postvalue('password_confirmation'));

    $error = false;

    if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_data', ENTRY_PASSWORD_CURRENT_ERROR);
    } 

    if ($error == false) {
      $check_customer_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (tep_validate_password($password_current, $check_customer['customers_password'],$check_customer["encryption_style"])) {        
        
		 tep_db_query("update " . TABLE_CUSTOMERS . " set idcards_printed = 'Y' where customers_id = '" . (int)$FSESSION->customer_id . "'");
		
		tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'GDPR PERSONAL DATA Request', EMAIL_TEXT, $check_customer['customers_firstname']. " " .$check_customer['customers_lastname'], $check_customer['customers_email_address']);
		tep_mail($check_customer['customers_firstname']. " " .$check_customer['customers_lastname'],$check_customer['customers_email_address'], 'GDPR PERSONAL DATA Request', EMAIL_TEXT2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS );
	     
        $FSESSION->set('account', R_HEADING_TITLE);
        tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      } else {
        $error = true;
        $messageStack->add('account_data', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));

  $content = 'account_data';
  $javascript = '';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
