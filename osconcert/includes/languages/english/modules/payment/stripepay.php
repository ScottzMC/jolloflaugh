<?php
/*
file includes/languages/english/modules/payment/stripepay.php

 

  Some code copyright (c) 2003-2013 osCommerce Released under the GNU General Public License 
  Some code copyright 2014 osConcert. Released under the GPL Public Licence
*/
        // Check to ensure this file is included in osConcert!
        defined('_FEXEC') or die();
		//define('MODULE_PAYMENT_STRIPEPAY_TEXT_TITLE', 'Stripe Secure Payments');
		
		//define ('MODULE_PAYMENT_STRIPEPAY_DISPLAY_IMAGE','<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES .  'stripepay.png" width="150" height="45">');
		
		define('MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME', 'Stripe secure payments');
		define('MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_OWNER', 'Card Owner');
		define('MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_NUMBER', 'Credit Card Number');
		define('MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_EXPIRES', 'Expiry');
		define('MODULE_PAYMENT_STRIPEPAY_CREDIT_CARD_CVC', 'CVV/CVC number');
		define('MODULE_PAYMENT_STRIPEPAY_ERROR_TITLE', 'Stripe Payment Error');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_DESCRIPTION', '');		
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_CVV_FAILED', 'CVV/CVC number check failed at Stripe Payments');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_CVV_UNCHECKED', 'CVV/CVC number recorded as unchecked at Stripe');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_AVS_FAILED', 'Address Line 1 check failed at Stripe Payments');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_ZIP_FAILED', 'ZIP/postcode check failed at Stripe Payments');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_AVS_UNCHECKED', 'Address Line 1 check recorded as unchecked at Stripe');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_ZIP_UNCHECKED', 'ZIP/postcode check recorded as unchecked at Stripe');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST', 'You have previously placed an order with us using Stripe.');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_CARD', 'Would you like to use your');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_NUMBER', 'card ending in');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_PAY', 'held on record at Stripe to pay for this order?');
        define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_UNTICK', 'Untick to use a different card');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PREV_CUST_TICK', 'Tick to use stored card');
		define('MODULE_PAYMENT_STRIPEPAY_CREDIT_TEXT_CARD_SAVE', 'Tick to save your card details with Stripe for future payments at this site');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_PUBLISHABLE_KEY_MISSING','Unable to continue - no valid Stripe Publishable Key found');
		define('MODULE_PAYMENT_STRIPEPAY_TEXT_POPUP_WORDING','Pay: {{amount}}');
?>