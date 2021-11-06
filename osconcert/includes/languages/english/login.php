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


define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', 'New Customer');
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', '<span style="white-space: pre-wrap;">By creating an account at '.STORE_NAME. ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.</span>');
define('TEXT_NOTE','Please Note: </font>');
define('TEXT_NOTE_INFO','If you do not have an account please create one to take advantage of our free trial');

define('ENTRY_USERNAME', 'Email/Username:');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer.');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');
define('TEXT_LOGIN_ERROR_USER', 'Error: No match for Username and/or Password.');
define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');

define('HEADING_GUEST', 'Skip creating an account.');
// PWA BOF
define('TEXT_GUEST_INTRODUCTION', '<b>Do you want to go straight to the checkout process?</b><br><br>Would you like to check out without creating a customer account? Please note that all of our services will not be available to customers that do not wish to create an account. Also, you cannot view the status of your order, and each time you shop with us you will have to re-enter all of your data.<br><br>Creating an account is free. If you still wish to continue to checkout please click the continue button. Account access will be available for this session only.');
// PWA BOF
define('SEATPLAN_LOGIN_ENFORCED_LOAD_TEXT','Sorry - server is busy');
define('SEATPLAN_LOGIN_ENFORCED_LOAD_DESC', 'Due to server being too busy new logins are not currently permitted. Wait a moment or two then click below to try again');
?>