<?php
/*

	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.zac-ware.com/freeway 
	Copyright (c) 2007 ZacWare 
	
	Released under the GNU General Public License 
	
	43
*/
defined('_FEXEC') or die();
define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Success');

// cartzone customer pdf tickets
define ('ORDER_LIST','Ticket Order');
if ($_GET['type'] == 'canx'){
define ('SEATS_RESERVED','Tickets Restocked');
	}else{
define ('SEATS_RESERVED','Tickets Reserved');}
define ('PDF_INVOICE','PDF Tickets For Printing');
define ('PDF_DOWNLOAD_LINK','To download and print your PDF eTickets, click <a class="small" title="Click here To download and print your PDF eTickets" href="%s"  target="_blank">here</a>');
define ('GET_ADOBE_READER','Get Adobe Reader');
define ('PRINT_PDF_TICKETS','Print PDF Tickets');
define ('HERE','HERE');
define ('GET_TICKETS_HERE','GET TICKETS HERE');
define ('GOTO','Go to');
define('INVOICE', 'Invoice #');
//checkout success messages here
define ('MESSAGE','<div>E-Tickets will be available in your <a href="' . tep_href_link(FILENAME_ACCOUNT) . '">account</a> when payment is confirmed by Admin. Thank you.</div>');
//if E-Ticket is disabled
define ('MESSAGE2','You can check your order details at your <a href="' . tep_href_link(FILENAME_ACCOUNT) . '">account</a>. Thank you.');
define ('MESSAGE3','Thank you');
if ($_GET['type'] == 'canx'){
define('HEADING_TITLE', 'Your ' . ITEM . '(s) Have Been Cancelled On Our System');
	}else{
define('HEADING_TITLE', 'Your ' . ITEM . '(s) Have Been ' . RESERVED . '');
define('TEXT_SUCCESS', 'The order has been successfully processed!');
}


?>
