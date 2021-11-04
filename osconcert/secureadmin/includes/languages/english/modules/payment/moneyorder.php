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

	//define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', "Cash/Cheque");
	//define('MODULE_PAYMENT_MONEYORDER_TEXT_TEXT_TITLE', "Cash/Cheque");
	define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', (defined('MODULE_PAYMENT_MONEYORDER_PAYTO')?'Make Payable To:&nbsp;' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br><br>Send To:<br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . 'Your tickets will not be released until we receive payment.':'Cheque/Money Order'));
	define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Make Payable To: ". MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\nSend To:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Your tickets will not be released until we receive payment.');
	define('MODULE_PAYMENT_MONEYORDER_INFO','Money Order Message');
?>
