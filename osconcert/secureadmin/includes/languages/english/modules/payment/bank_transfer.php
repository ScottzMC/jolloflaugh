<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Bank Transfer Module edited by osconcert.com
	
  Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	define('MODULE_PAYMENT_BANK_TRANSFER_TEXT_DESCRIPTION','Bank Transfer Payment<br><br>Thanks for your order which will be delivered immediately we receive payment in the above account.');
	
	//THIS IS AT THE CHECKOUT CONFIRMATION PAGE remove <!-- abc --> to include Account No
	// define('MODULE_PAYMENT_BANK_TRANSFER_TEXT_EMAIL_FOOTER', "<div class=\"bank-transfer-text\">Please use the following details to transfer your total order value:<br>
	// <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>
	// <td><strong>Account Name: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_ACCNAM . "</td></tr><tr>
	// <td><strong>Bank Name: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_BANKNAM . "</td></tr><tr>
	// <td><strong>Sort Code.: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_BSB . "</td></tr><tr>
	// <td><strong>Account No.: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_ACCNUM . "</td></tr><tr>
	// <td><strong>IBAN: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_IBAN . "</td></tr></table>
	// <strong>Your Reference:</strong><br>Please specify your name and day of the event.
	// <br><b>NOTE:</b> Please transfer the amount within 3 days. When we receive payment we will send an E-Mail with your eTicket.</div>");
	
	define('MODULE_PAYMENT_BANK_TRANSFER_TEXT_EMAIL_FOOTER', "<div class=\"bank-transfer-text\">Bank Transfer Details will be sent by email for a successful reservation.</div>");
		
	define('HEADING_REFERENCE',' The IBAN and your reference ID will be displayed on this website after you have completed your booking and will also be sent to you by email.');
	define('TEXT_REFERENCEID',' Please use this reference when making your deposit. <br>Reference Id ');
	define('MODULE_PAYMENT_BANK_TRANSFER_BANK_DETAILS','<small> 
	<b>Account Name: </b>'  .MODULE_PAYMENT_BANK_TRANSFER_ACCNAM . '<br> 
	<b>Bank Name: </b>' . MODULE_PAYMENT_BANK_TRANSFER_BANKNAM . '<br>
	<b>Sort Code: </b>' . MODULE_PAYMENT_BANK_TRANSFER_BSB . '<br>
	<b>Account No: </b>' . MODULE_PAYMENT_BANK_TRANSFER_ACCNUM . ' </small>
	<b>IBAN: </b>' . MODULE_PAYMENT_BANK_TRANSFER_ACCNUM . '<br>');
	
	//THIS IS THE MESSAGE IN THE EMAIL TEMPLATE
	define('MODULE_BANK_TRANSFER_INFO', "<div class=\"bank-transfer-text\">Please use the following details to transfer your total order value:<br>
	<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>
	<td><strong>Account Name: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_ACCNAM . "</td></tr><tr>
	<td><strong>Bank Name: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_BANKNAM . "</td></tr><tr>
	<td><strong>Sort Code.: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_BSB . "</td></tr><tr>
	<td><strong>Account No.: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_ACCNUM . "</td></tr><tr>
	<td><strong>IBAN: </strong></td><td>&nbsp;&nbsp;" . MODULE_PAYMENT_BANK_TRANSFER_IBAN . "</td></tr></table>
	<strong>Your Reference:</strong><br>Please specify your name and day of the event.
	<br><b>NOTE:</b> Please transfer the amount within 3 days. When we receive payment we will send an E-Mail with your eTicket.</div>");
	
	//Or construct a more sophisticated message
	//define("MODULE_BANK_TRANSFER_INFO","<table cellpadding='2' border='0' cellspacing='1'><tr><td style='color:#000000;font-size:10px;font-weight:normal;'>Please use the following details to transfer your total order value:<br>Account Name: " . MODULE_PAYMENT_BANK_TRANSFER_ACCNAM . "<br>Sort Code: " . MODULE_PAYMENT_BANK_TRANSFER_SWIFT . "<br>Bank Name:    " . MODULE_PAYMENT_BANK_TRANSFER_BANKNAM . "<br>Account No.:  " . MODULE_PAYMENT_BANK_TRANSFER_ACCNUM . "<br>Your Reference ID: %s (as per registration)</td></tr><tr><td style='color:#ff0000;font-size:11px;font-weight:bold;'>VERY IMPORTANT PLEASE NOTE</td></tr><tr><td style='color:#000000;font-size:11px;font-weight:normal;'><b>Bank Transfer Payments:</b> Concert Tickets and appropriate postage should be paid in full within 3 days upon making the reservation before tickets can be sent.<br><br><b>Note:</b><br><ol><li>When making a bank transfer, please put your initials and surname as reference / description.</li><li>We will only dispatch your tickets upon receipt of payment.</li><li>None receipt of payment within 3 days; your booking will be cancelled automatically.</li><li>Tickets are non-refundable and non-exchangeable.</li></ol></td></tr></table>");
?>