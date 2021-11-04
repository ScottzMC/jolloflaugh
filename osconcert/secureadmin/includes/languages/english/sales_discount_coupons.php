<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 
define('HEADING_TITLE','Discount Coupons');
define('TABLE_HEADING_COUPON_NAME','Coupon Name');
define('TABLE_HEADING_COUPON_CODE','Coupon Code');
define('TABLE_HEADING_COUPON_AMOUNT','Coupon Amount');
define('TABLE_HEADING_CREATE_DATE','Creation Date');
define('TABLE_HEADING_ORDER','Order ID');
define('TEXT_COUPON_NAME','Coupon Name');
define('TEXT_COUPON_CODE','Coupon Code');
define('TEXT_COUPON_AMOUNT','Coupon Amount');
define('TABLE_HEADING_CUSTOMER_NAME','Customer Name');
define('TABLE_HEADING_DATE','Date');
define('TABLE_HEADING_AMOUNT','Order Amount');
define('TABLE_HEADING_CUSTOMER_EMAIL','Email Address');
define('TEXT_HEADING_DISCOUNT_COUPON_CODE','Discount Coupon Code');
define('TEXT_NO_EMAIL_HISTORY','No EMail History Found');
define('TEXT_HEADING_DATE_SEND','Date Send');
define('TEXT_NO_CUSTOMERS','No Customers Found');
define('TEXT_NO_MAIL_CONTENT','No EMail Content Found');
define('TEXT_USERS_COUPON_EXCEEDS','The Users per coupon exceeds the limit');
define('TEXT_EMAIL_ALL_CUSTOMERS','Email Sent to All Customers');
define('TEXT_EMAIL_NEWS_LETTER','Email Send to all News Letter Customers');

define('TEXT_USES_PER_COUPON','Uses per Coupon');
define('TEXT_CUSTOMERS_USES_COUPON','Customer uses Coupon');
define('TEXT_EMPTY_DISCOUNT_COUPONS','No Discount Coupons found');

define("TEXT_CUSTOMER","Customer:");
define("TEXT_SUBJECT","Subject:");
define("TEXT_FROM","From:");
define("TEXT_MESSAGE","Message:");
define("TEXT_SELECT_CUSTOMER","Select Customer");
define("TEXT_ALL_CUSTOMERS","All Customers");
define("TEXT_NEWSLETTER_CUSTOMERS","To All Newsletter Subscribers");

define("TEXT_PAGE_SIZE","Page Size");
define("TEXT_TEMPLATE","Template");
define("TEXT_PAGE_FORMAT","Page Format");

define("TEXT_NONE","None");
define("IMAGE_DELETE_TEMPLATE","Delete the selected Template");
define("IMAGE_CHOOSE_TEMPLATE","Choose the selected Template");
define("IMAGE_INSERT_TEMPLATE","Insert a new template");
define("IMAGE_UPDATE_TEMPLATE","Update the selected Template");
define("IMAGE_DOWNLOAD","Download");
define("IMAGE_PRINT","Print");
define("NOTICE_EMAIL_SENT_TO","Notice: Email sent to: %s");
define("ERROR_NO_CUSTOMER_SELECTED","Error: No customer has been selected.");

define("TEXT_TITLE_P","Personal Details");
define("TEXT_TITLE_O","Order Details");
define("TEXT_TITLE_E","Event Details");
define("TEXT_TITLE_R","Refund Details");
define("TEXT_TITLE_V","Invite Details");
define("TEXT_TITLE_C","Store Details");

define("ERR_EMPTY_TEMPLATE_NAME","Template name cannot be blank");
define("ERR_TEMPLATE_NAME_EXISTS","Template name already exists");
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
//define("TEXT_EMAIL_BUTTON_TEXT","<p><HR><b><font color=\"red\">The Back Button has been DISABLE while HTML WYSIWG Editor is turned O");
define("TEXT_EMAIL_BUTTON_HTML","<p><HR><b><font color=\"red\">HTML is currently Disabled!</b></font><br><br>If you want to send HTML email");
define('HEADING_TITLE', 'Email Discount Coupon');

define('TEXT_GV_WORTH', 'The Gift Voucher is worth ');
define('TEXT_TO_REDEEM', 'To redeem this Gift Voucher, please click on the link below. Please also write down the redemption code');
define('TEXT_WHICH_IS', 'which is');
define('TEXT_IN_CASE', ' in case you have any problems.');
define('TEXT_OR_VISIT', 'or visit ');
define('TEXT_ENTER_CODE', ' and enter the code during the checkout process');

define ('TEXT_REDEEM_COUPON_MESSAGE_HEADER', 'You recently purchasd a Gift Voucher from our site, for security reasons, the amount of the Gift Voucher was not immediatley credited to you. The shop owner has now released this amount.');
define ('TEXT_REDEEM_COUPON_MESSAGE_AMOUNT', "\n\n" . 'The value of the Gift Voucher was %s');
define ('TEXT_REDEEM_COUPON_MESSAGE_BODY', "\n\n" . 'You can now visit our site, login and send the Gift Voucher amount to anyone you want.');
define ('TEXT_REDEEM_COUPON_MESSAGE_FOOTER', "\n\n");

define('TEXT_CUSTOMER_COUPON_EXCEEDS','Customer Exceed Coupon limit');
define('TEXT_MESSAGE_FORMAT','Email Format:');
define('TEXT_FORMAT_TEXT','Text Email');
define('TEXT_FORMAT_HTML','HTML Email');
define('TEXT_FORMAT_BOTH','Text and HTML Email');
define('TEXT_LOADING_DATA','Loading...');
define('TEXT_CREATING_PREVIEW','Creating Preview...');
define('TEXT_SENDING_MAIL','Sending Mail...');
define('TEXT_RECORDS','Discount Coupons');
define('TEXT_MERGE_FIELDS','Merge Fields');
?>