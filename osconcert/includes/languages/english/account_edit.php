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

define('NAVBAR_TITLE', 'Create an Account');

define('HEADING_TITLE', 'My Account Information');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTE:</b></font></small> If you already have an account with us, please login at the <a href="%s"><u>login page</u></a>.');
define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' );
define('EMAIL_GREET_MS', 'Dear Ms. %s, ');
define('EMAIL_GREET_NONE', 'Dear %s ');
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
//define('EMAIL_GV_INCENTIVE_HEADER', 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
//define('EMAIL_GV_REDEEM', 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase');
//define('EMAIL_GV_LINK', 'or by following this link ');
//define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" .
                                     //   '  below are details of a Discount Coupon created just for you' . "\n\n");
//define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" .
                             //  'after making a purchase');
define('TEXT_REFERRAL_OTHER','Other');
define('TEXT_FIELD_REQUIRED','*');
define('SUCCESS_ACCOUNT_UPDATED', 'Your account has been successfully updated.');
?>