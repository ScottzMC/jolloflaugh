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

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if ($FREQUEST->getvalue('action') == 'send') {
    $name = tep_db_prepare_input($FREQUEST->postvalue('name'));
    $email_address = tep_db_prepare_input($FREQUEST->postvalue('email'));
    $enquiry = tep_db_prepare_input($FREQUEST->postvalue('enquiry'));

    if (tep_validate_email($email_address)) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success', 'SSL'));
    } else {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US), 'SSL');

  $content = CONTENT_CONTACT_US;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>