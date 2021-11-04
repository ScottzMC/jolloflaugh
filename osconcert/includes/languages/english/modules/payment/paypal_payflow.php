<?php
/*
  $Id: paypal_payflow.php 1826 2008-01-22 15:43:01Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_PUBLIC_TITLE', 'Credit or Debit Card (Processed securely by PayPal)');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_TEXT_DESCRIPTION', 'PayPal PayFlow Pro');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER', 'Card Owner:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER_FIRSTNAME', 'Card Owner First Name:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_OWNER_LASTNAME', 'Card Owner Last Name:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_TYPE', 'Card Type:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_NUMBER', 'Card Number:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_VALID_FROM', 'Card Valid From Date:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_VALID_FROM_INFO', '(if available)');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_EXPIRES', 'Card Expiry Date:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_CVC', 'Card Security Code (CVV2):');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_ISSUE_NUMBER', 'Card Issue Number:');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_CARD_ISSUE_NUMBER_INFO', '(for Maestro and Solo cards only)');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_ALL_FIELDS_REQUIRED', 'Error: All payment information fields are required.');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_GENERAL', 'Error: A general problem has occurred with the transaction. Please try again.');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_CFG_ERROR', 'Error: Payment module configuration error. Please verify the login credentials.');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_ADDRESS', 'Error: A match of the Delivery Address City, State, and Postal Code failed. Please try again.');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_DECLINED', 'Error: This transaction has been declined. Please try again.');
  define('MODULE_PAYMENT_PAYPAL_PAYFLOW_ERROR_INVALID_CREDIT_CARD', 'Error: The provided credit card information is invalid. Please try again.');
?>
