<?php
/*

	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.zac-ware.com/freeway 
	Copyright (c) 2007 ZacWare 
	
	Released under the GNU General Public License 
*/
defined('_FEXEC') or die();
define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Success');

// cartzone customer pdf tickets
define ('ORDER_LIST','Ticket Order');
define ('SEATS_RESERVED','Tickets Purchased');
define ('PDF_INVOICE','PDF Tickets For Printing');
define ('PDF_DOWNLOAD_LINK','To download and print your PDF eTickets, click <a class="small" title="Click here To download and print your PDF eTickets" href="%s"  target="_blank">here</a>');
define ('GET_ADOBE_READER','Get Adobe Reader');
define ('PRINT_PDF_TICKETS','Print PDF Tickets');
define ('HERE','HERE');
define ('GET_TICKETS_HERE','GET TICKETS HERE');
define ('GOTO','Go to');

define('INVOICE', 'Invoice #');
//checkout success messages here
define ('MESSAGE','<div>E-Tickets will be available in your <a href="' . tep_href_link(FILENAME_ACCOUNT) . '">account</a> Thank you</div>');
//if E-Ticket is disabled
define ('MESSAGE2','You can check your order details at your <a href="' . tep_href_link(FILENAME_ACCOUNT) . '">account</a>. Thank you.');
define ('MESSAGE3','Thank you');

define('HEADING_TITLE', 'Your ' . ITEM . '(s) Have Been ' . RESERVED . '');
define('HEADING_TITLE_REFUND', 'Your ' . ITEM . '(s) Have Been ' . RESTOCKED . '');
define('TEXT_SUCCESS', 'The order has been successfully processed! An email confirmation will be sent.');
define('TEXT_NOTIFY_PRODUCTS', 'Please tick the items below if you want to be notified of updates.');
define('TEXT_SEE_ORDERS', 'An order history for the customer can be found by going to <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Customers Account\'</a> page and by clicking on <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'History\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'Please direct any questions you have to the <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">store owner</a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Thanks for shopping with '.STORE_NAME.' online!');
define('TEXT_THANKS_FOR_SHOPPING2', 'To print multiple tickets on one page select <strong>pages per sheet</strong> on the print page prior to printing: ie 4 tickets 4 pages per sheet');
define('TABLE_HEADING_COMMENTS', 'Enter a comment for the order processed');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry date: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' downloads remaining');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');


define('TEXT_RECEIPT_NO','Receipt No: ');
define('TEXT_TRANSACTION_NO','Transaction No: ');
?>
