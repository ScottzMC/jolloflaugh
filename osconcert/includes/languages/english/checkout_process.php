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

define('CHARSET', 'utf-8');

define('EMAIL_TEXT_SUBJECT', 'Order Process');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_PRODUCTS', 'Tickets');//
define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
define('EMAIL_TEXT_SERVICE_FEE', 'Service Fee: ');
define('EMAIL_TEXT_TAX', 'Tax:        ');
define('EMAIL_TEXT_SHIPPING', 'Postage: ');
define('EMAIL_TEXT_TOTAL', 'Total:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');
define('BOX_OFFICE_PAYMENT', 'Box Office Reservation');
define('FREE_CHECKOUT', 'Free Checkout');
define('FREE_CHECKOUT_EVENT', 'Free Checkout Event');
//for the email order link
define('CLICK_HERE','Click Here');
if (E_TICKETS=='true') {
define('TICKET_LINK_TEXT','<b>Collect Your PDF Concert E-Tickets Here: </b>');
}else{
define('TICKET_LINK_TEXT','<b>Detailed Invoice: </b>');
}
define('TEXT_WITH_THANKS','With Thanks ');
define('TEXT_DEAR','Dear ');
define('TEXT_MAIL_ORDER_NUMBER','Order Number');
define('TEXT_PAYMENT_METHOD','Payment Method');
define('TEXT_ADDRESS','Address');
define('TEXT_TELEPHONE','Telephone');
define('TEXT_EMAIL','Email');
define('TEXT_PAYMENT_DETAILS','Payment Details');
define('TEXT_DELIVERY_DETAILS','Delivery Details');
define('TEXT_TICKETS','Tickets');
define('TEXT_THANKS_PURCHASE','Thank you for your purchase. The details of your order are below:');
//CodeReadr barcoded ticket link
define('CR_TICKET_LINK','<b>Collect Your Tickets Here: </b>');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'via');
define('TEXT_QUANTITY','Qty');//
define('TEXT_PRICE','Price');//
define('TEXT_PRODUCTS_NAME','Ticket');
define('TEXT_ATTRIBUTES','Attributes');
define('TABLE_HEADING_DOWNLOAD_DATE','Expiry Date : ');
define('TABLE_HEADING_DOWNLOAD_COUNT',' Downloads remaining');
define('TEXT_DOWNLOAD_LINK','<b>Download Link</b>');
define('TEXT_ORDER_COMMENTS','Order Comments: ');
define('TEXT_GA_CHECK_ORDER','Please check your order');

define('TEXT_GIFT_VOUCHER','You have purchased a Gift Voucher with code: ');
define('TEXT_GIFT_VOUCHER_NOT',' It will be available for use once your payment has cleared.')
?>