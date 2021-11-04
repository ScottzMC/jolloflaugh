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
	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_ACCOUNT_PASSWORD_FIRST);

  if ($FREQUEST->postvalue('action')!='' && ($FREQUEST->postvalue('action') == 'process')) {
    $password_new = tep_db_prepare_input($FREQUEST->postvalue('password_new'));
    $password_confirmation = tep_db_prepare_input($FREQUEST->postvalue('password_confirmation'));

    $error = false;

    if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password_first', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password_first', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_encrypt_password($password_new) . "',encryption_style='" . ENCRYPTION_STYLE . "' where customers_id = '" . (int)$FSESSION->customer_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified ='". $serverDate ."' where customers_info_id = '" . (int)$FSESSION->customer_id . "'");

        $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_PASSWORD_FIRST, '', 'SSL'));

  $content = CONTENT_ACCOUNT_PASSWORD_FIRST;
  $javascript = 'form_check.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
