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


define('NAVBAR_TITLE', 'Gift Voucher FAQ');
define('HEADING_TITLE', 'Gift Voucher FAQ');

define('TEXT_INFORMATION', '<a name="Top"></a>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=1','NONSSL').'">Purchasing Gift Vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=2','NONSSL').'">How to give away the voucher</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=3','NONSSL').'">Redeeming Gift Vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=4','NONSSL').'">When problems occur</a><br><br>
');
switch ($FREQUEST->getvalue('faq_item')) {
  case '1':
define('SUB_HEADING_TITLE','Purchasing Gift Vouchers.');
define('SUB_HEADING_TEXT','Gift Vouchers are purchased just like any other item in our store. You can 
  pay for them using the store\'s standard payment method(s).<br><br>
  However at the Gift Voucher product info page it is for you to choose the Gift Voucher amount in the store currency.<br><br>
  When you have decided the price...click the Add to Cart button and checkout.<br><br>
  Please be aware you are only allowed to buy ONE Gift Voucher in one session.<br><br>
Please note: The voucher price is untaxed. It is like a loan for the gift recipient with us (so you cannot submit it to the tax office. The tax is only due when the ticket is purchased.)
  ');
  break;
  case '2':
define('SUB_HEADING_TITLE','Give away the voucher.');
define('SUB_HEADING_TEXT','You can pass on the voucher as you like. Ultimately, it only consists of the ASCII code (letters and numbers) on the PDF that was sent to you after the payment process has been completed.<br><br>
You can now simply forward the PDF to the recipient, or print it out and hand it over or - if you don\'t like our design - design a document yourself, even handwritten!<br><br>
Ultimately, it is only important that the code is contained exactly, including all upper and lower case and special characters.
As the voucher is only valid once, please treat your voucher code confidentially! If it - by whoever - has been used once, the voucher is considered redeemed and is no longer valid. Since you are responsible for the confidential handling, we do not assume any guarantee in such a case.');  
  break;
  case '3':
  define('SUB_HEADING_TITLE','Redeeming Gift Vouchers.');
  define('SUB_HEADING_TEXT','The recipient can choose any performance in our ticket shop and - if still available - pay for any seats with the code. (To do this, however, he / she must have an account with us and use it or create a new one!) When selecting the payment method, she / he must then simply select the option “Redeem voucher”.<br><br>
If the value of the order is exactly the same as the voucher amount you specified, the voucher will be redeemed in full and the recipient does not have to pay anything. That\'s the way it is meant. It is therefore advisable to select the amount according to our ticket prices.<br><br>
If the recipient chooses more expensive or more tickets, he will be asked to pay the transfer after entering the voucher.<br><br>
If the recipient chooses cheaper or fewer tickets, the rest of the tickets are forfeited.<br><br>
You can also use the voucher to pay at the box office.<br><br>
If the voucher is not redeemed in the specified season, it will also expire. <br><br>
Please note that the voucher is not a ticket! It also does not guarantee a seat. (Of course, the ticket purchased with it does).
');
  break;
  case '4':
  define('SUB_HEADING_TITLE','When problems occur.');
  define('SUB_HEADING_TEXT','For any queries regarding the Gift Voucher System, please contact the store 
  by email at '. STORE_OWNER_EMAIL_ADDRESS . '. Please make sure you give 
  as much information as possible in the email. ');
  break;
  default:
  define('SUB_HEADING_TITLE','');
  define('SUB_HEADING_TEXT','Please choose from one of the questions above.');

  }
?>