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

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	define ('MODULE_PAYMENT_PAYPAL_API_TEXT_CONFIRMATION','You are about to be forwarded to PayPal  secure server to make payment: <br>- you must return to the store to complete your order after agreeing payment.<br>- if you wish to cancel your payment or change your order  please use the cancel button provided at PayPal and wait to be returned to the store.<br>Please do <b>not</b> use your browser back button.');
	define('MODULE_PAYMENT_PAYPALPLUS_TEXT_DESCRIPTION', 'Paypal+ ');
	define('MODULE_PAYMENT_PAYPALPLUS_TEXT_CURL', 'cURL Enabled');
	define('MODULE_PAYMENT_PAYPAL_API_INFO', 'Paypal Message');
	define('MODULE_PAYMENT_PAYPAL_API_GENERAL_ERROR', 'unable to process payment - your account has not been debited.');
	define('MODULE_PAYMENT_PAYPAL_API_POST_ERROR', 'Access token not seen post order');
	define('MODULE_PAYMENT_PAYPAL_API_GET_ERROR', 'Unable to obtain Approval URL at PayPal');
    define('MODULE_PAYMENT_PAYPAL_API_CANCEL', 'Payment cancelled at PayPal');
	define('MODULE_PAYMENT_PAYPAL_API_ERROR_TITLE', 'PayPal Payment Error');
	define('MODULE_PAYMENT_PAYPAL_API_CANX_FAILED','Your attempt to cancel payment has not succeeded, please contact us.');
    define('MODULE_PAYMENT_PAYPAL_API_VAL_ERROR',' Please verify your Zip and City match.');
	define('MODULE_PAYMENT_PAYPAL_API_TEXT_NO_ORDER','Unable to process your order. Please contact the store owner. ');
    define('MODULE_PAYMENT_PAYPAL_API_PRICE_DIFF','PayPal rounding adjustment ');
?>