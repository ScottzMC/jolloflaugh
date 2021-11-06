<?php
/*

	 
	
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Released under the GNU General Public License 
*/


// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


define('NAVBAR_TITLE_1', 'Wallet Upload');

define('HEADING_TITLE', 'Payment Information');

define('TABLE_HEADING_BILLING_ADDRESS', 'Wallet Funds Upload ');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');
define('TITLE_BILLING_ADDRESS', 'Upload to:');
 define('TEXT_WALLET_UPLOADS', '<b>Amount:</b>');


define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please select the preferred payment method to use on this wallet.');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');
define('TABLE_HEADING_CREDIT','Credits Available');
define('TABLE_HEADING_COMMENTS', 'Add Comments About Your Upload');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Wallet Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to confirm this upload.');

define('TITLE_PLEASE_NOTE', 'Please Note :');
define('REFERENCE_DESCRIPTION','You must add this reference ID when you make your deposit');
define('HEADING_ACCOUNT_NAME','Account Name : ');
define('HEADING_ACCOUNT_NO','Account Number : ');
define('HEADING_BSB_NUMBER','BSB Number : ');
define('HEADING_REFERENCE_ID','Your Reference ID : ');
define('TITLE_BANK_DETAILS','<small> <b>' . HEADING_ACCOUNT_NAME .'</b>'  .MODULE_PAYMENT_BANK_ACCOUNT_ACCNAM . '<br> <b>' .HEADING_ACCOUNT_NO.'</b>' . MODULE_PAYMENT_BANK_ACCOUNT_ACCNUM . '<br> <b>' .HEADING_BSB_NUMBER.'</b>' . MODULE_PAYMENT_BANK_ACCOUNT_BSB . '</small>'); 
?>
