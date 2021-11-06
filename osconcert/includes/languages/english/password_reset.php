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


define('NAVBAR_TITLE_1', 'Password change');
define('NAVBAR_TITLE_2', 'Reset');

define('HEADING_TITLE', 'Reset Password');

define('MY_EMAIL_TITLE', 'My Email');

define('MY_PASSWORD_TITLE', 'Reset Password');
define('ERROR_TOKEN','Sorry - there is an error with the token. Please request another <a href="'.tep_href_link(FILENAME_PASSWORD_FORGOTTEN).'">here</a>.');
define('SUCCESS_PASSWORD_UPDATED', 'Your password has been successfully updated.');
define('ERROR_CURRENT_PASSWORD_NOT_MATCHING', 'There was an error in changing your password - please check your email address is correct.');
define('ERROR_TIMEOUT', 'Sorry - the link has expired. Please request another <a href="'.tep_href_link(FILENAME_PASSWORD_FORGOTTEN).'">here</a>.');
?>
