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

// cartzone customer pdf tickets

define ('PDF_INVOICE','PDF eTickets For Printing');
define ('PDF_DOWNLOAD_LINK','To download and print your PDF eTickets, click <a title="Click here To download and print your PDF eTickets" href="%s"  target="_blank">here</a>');
define ('GET_ADOBE_READER','Get Adobe Reader');
define ('PRINT_PDF_TICKETS','Print PDF Tickets');
define ('HERE','HERE');
define ('GET_TICKETS_HERE','GET TICKETS HERE');
define ('NO_ETICKETS','E-Tickets not available for this order');

// end customer pdf invoice
define('NAVBAR_TITLE_1', 'My Account');
define('NAVBAR_TITLE_2', 'History');
define('NAVBAR_TITLE_3', 'Order #%s');

define('HEADING_TITLE', 'Order Information');

define('HEADING_ORDER_NUMBER', 'Order #%s');
define('HEADING_ORDER_DATE', 'Order Date:');
define('HEADING_ORDER_TOTAL', 'Order Total:');

define('HEADING_DELIVERY_ADDRESS', 'Delivery Address');
define('HEADING_SHIPPING_METHOD', 'Delivery method');
define('HEADING_PAYMENT_STATUS', 'Payment Status');

define('HEADING_PRODUCTS', 'Items');
define('HEADING_TAX', 'Tax');
define('HEADING_TOTAL', 'Total');
//define('HEADING_SKU','<b>SKU&nbsp;:&nbsp;</b>');

define('HEADING_BILLING_INFORMATION', 'Billing Information');
define('HEADING_BILLING_ADDRESS', 'Billing Address');
define('HEADING_PAYMENT_METHOD', 'Payment Method');

define('HEADING_ORDER_HISTORY', 'Order History');
define('HEADING_COMMENT', 'Comments');
define('TEXT_NO_COMMENTS_AVAILABLE', 'No comments available.');


define('TABLE_HEADING_DOWNLOAD_DATE', 'Download Expires on' );
define('TABLE_HEADING_DOWNLOAD_COUNT', ' downloads remaining');
define('HEADING_DOWNLOAD', 'Download links');
define('TEXT_START_DATE','Start Date');
define('TEXT_END_DATE','End Date');
define('TEXT_RESOURCE_NAME','Resource:');

define('HEADING_IP_ADDRESS','Customer IP address');

define('HEADING_ORDER_DETAILS','Order Details');
define('HEADING_PAYMENT_AMOUNT','Payment Details');
define('TEXT_REFERENCE_ID','Reference Id:');
define('TEXT_START_DATE','Start Date');
define('TEXT_END_DATE','End Date');

define("TEXT_1","cartZone Presents");
define("TEXT_2","osConcert");
define("TEXT_3","Seat name: ");
define("TEXT_4","Ticket ref: ");
define("TEXT_5","TICKETS: osConcert Box Office - OBO");
define("TEXT_6","Tel - 123 456-1122 www.cartzone.co.uk");
define("TEXT_CONDITIONS","Refundable only if event is cancelled");
define("TEXT_7","");

// added customer pdf invoice
define ('PDF_INVOICE','PDF Invoice');
define ('PDF_DOWNLOAD_LINK','If you would like to view / print a pdf version of this invoice, click <a title="Click here to view / print a pdf version of this invoice" href="%s">here</a>');
// end added customer pdf invoice
?>
