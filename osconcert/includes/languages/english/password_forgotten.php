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


define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Password Forgotten');

define('HEADING_TITLE', 'I\'ve Forgotten My Password!');

define('TEXT_MAIN', 'If you\'ve forgotten your password, enter your e-mail address below and we\'ll send you an e-mail message containing your new password reset instructions.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'Error: The E-Mail Address was not found in our records, please try again.');

define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - New Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A new password was requested from ' . $REMOTE_ADDR . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('SUCCESS_PASSWORD_SENT', 'Success: please check your email for a re-activation link');

?>