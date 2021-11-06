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

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);
  //logoff kill carts_united
   $cart->reset(true);

  $FSESSION->remove('customer_id');
  $FSESSION->remove('customer_default_address_id');
  $FSESSION->remove('customer_first_name');
  $FSESSION->remove('customer_country_id');
  $FSESSION->remove('customer_zone_id');
  $FSESSION->remove('comments');
	if ($FSESSION->is_registered("customer_auto_name"))
		$FSESSION->remove('customer_auto_name');
//ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
  $FSESSION->remove('gv_id');
  $FSESSION->remove('cc_id');
//ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
//  $cart->reset();

  if ($FREQUEST->cookievalue('osCuser')) setcookie ("osCuser", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('osCpass')) setcookie ("osCpass", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('fe_typo_user')) setcookie ("fe_typo_user", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('be_typo_user')) setcookie ("be_typo_user", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('bbsessionhash')) setcookie ("bbsessionhash", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('PHPSESSID')) setcookie ("PHPSESSID", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('bbuserid')) setcookie ("bbuserid", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);
  if ($FREQUEST->cookievalue('bbpassword')) setcookie ("bbpassword", "", time() - 60*60*24*30,$cookie_path,$cookie_domain);

  session_destroy();
  $content = CONTENT_LOGOFF;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
