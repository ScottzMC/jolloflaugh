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
//based on the password change file in account 
// Set flag that this is a parent file
	define( '_FEXEC', 1 );


  require('includes/application_top.php');


//	$serverDate = date('Y-m-d H:i:s',getServerDate(false));
// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_PASSWORD_RESET);

// retrieve token
if (isset($_GET["token"]) && preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
    $token = $_GET["token"];


  if ($FREQUEST->postvalue('action')!='' && ($FREQUEST->postvalue('action') == 'process')) {
    $email_address = tep_db_prepare_input($FREQUEST->postvalue('email_address'));
    $password_new = tep_db_prepare_input($FREQUEST->postvalue('password_new'));
    $password_confirmation = tep_db_prepare_input($FREQUEST->postvalue('password_confirmation'));

    $error = false;

    if (strlen($email_address) < 3) {
      $error = true;

      $messageStack->add('password_reset', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('password_reset', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) 
	{
      $error = true;

      $messageStack->add('password_reset', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) 
	{
    	//form data has something in each field - now check the token
      $check_customer_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_email_address= '" . tep_db_input($email_address ) . "'");
	  if (tep_db_num_rows($check_customer_query))
	  {
	  	
		  $check_customer = tep_db_fetch_array($check_customer_query);
		  
		  //check for the time elapsed - manually edit this next line to increase the hours
		  $hours_to_use=2;
		  
          $delta = 60*60*$hours_to_use;//hours as seconds
 
				// Check to see if link has expired
				if ($_SERVER["REQUEST_TIME"] - $check_customer['customers_timestamp']> $delta) {
				     $error = true;
                     $messageStack->add('password_reset', ERROR_TIMEOUT);
					  tep_db_query("update " . TABLE_CUSTOMERS . " set  customers_token='', customers_timestamp= '0' where customers_email_address= '" . tep_db_input($email_address ) . "'");
                     }
				//check the email
				//not needed ??
	  
    if ($error == false) 
	{
       if($check_customer['customers_token']==$_GET['token'])
	   {
                 tep_db_query("update " . TABLE_CUSTOMERS . " set  customers_token='', customers_timestamp= '0', customers_password = '" .           tep_encrypt_password($password_new) . "' where customers_email_address= '" . tep_db_input($email_address ) . "'");


        $messageStack->add_session('login', SUCCESS_PASSWORD_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
		exit();
        } 
        else 
		{
        $error = true;
        $messageStack->add('password_reset', ERROR_TOKEN);
      }
	}
    }
 else {
        $error = true;
        $messageStack->add('password_reset', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
  }
  }

}

else {
    $messageStack->add('password_reset', ERROR_TOKEN);
}
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_RESET, '', 'SSL'));

  $content = CONTENT_PASSWORD_RESET;
  $javascript = 'form_check.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
