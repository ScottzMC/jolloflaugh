<?php
/*
file includes/languages/english/modules/payment/stripesca.php

 

  Some code copyright (c) 2003-2013 osCommerce Released under the GNU General Public License 
  Some code copyright 2014 osConcert. Released under the GPL Public Licence
*/
        // Check to ensure this file is included in Freeway!
        defined('_FEXEC') or die();
	
		
		//define ('MODULE_PAYMENT_STRIPESCA_DISPLAY_IMAGE','<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES .  'stripesca.png" width="150" height="45"');
		
		define('MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME', 'Stripe secure payments [SCA]');
		
		define('MODULE_PAYMENT_STRIPESCA_WAIT', 'Please wait whilst you are redirected to Stripe payments.');
		
		define('MODULE_PAYMENT_STRIPESCA_DESC', 'Tickets from '.STORE_OWNER.'');
		
		define('MODULE_PAYMENT_STRIPESCA_DESC_TITLE', 'Tickets from '.STORE_OWNER.'');
		
		define('MODULE_PAYMENT_STRIPESCA_GENERIC_ERROR', 'There was a problem contacting the payment server. Please try again.');
		
		define ('MODULE_PAYMENT_STRIPESCA_ERROR_TITLE', 'Stripe Payments Error:');
		
		define('MODULE_PAYMENT_STRIPESCA_REDIRECT_ERROR', 'Redirection failed from store. Order cancelled.');
		
		define ('MODULE_PAYMENT_STRIPESCA_ERROR_CANX_LABEL', ':: ');
		
		define ('MODULE_PAYMENT_STRIPESCA_CANCEL_ERROR', 'Payment cancelled at Stripe');
		
		define ('MODULE_PAYMENT_STRIPESCA_SUCCESS', 'Customer has returned after making successful payment');
		
		define ('MODULE_PAYMENT_STRIPESCA_UNCONFIRMED', 'Customer has returned payment not yet confirmed');
		
		define ('MODULE_PAYMENT_STRIPESCA_METADATA', STORE_NAME . ' order number: ');
		
		define ('MODULE_PAYMENT_STRIPESCA_WEBHOOK_SUCCESS', 'Webhook notifies payment captured');

?>