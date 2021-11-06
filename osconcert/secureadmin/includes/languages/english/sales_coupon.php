<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TOP_BAR_TITLE', 'Statistics');
define('HEADING_TITLE', 'Discount Coupons');
define('HEADING_TITLE_STATUS', 'Status : ');
define('TEXT_CUSTOMER', 'Customer:');
define('TEXT_COUPON', 'Coupon Name');
define('COUPON_ORDER', 'Coupon Order');
define('TEXT_COUPON_ALL', 'All Coupons');
define('TEXT_COUPON_ACTIVE', 'Active Coupons');
define('TEXT_COUPON_INACTIVE', 'Inactive Coupons');
define('TEXT_SUBJECT', 'Subject:');
define('TEXT_FROM', 'From:');
define('TEXT_FREE_SHIPPING', 'Free Shipping');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECT_CUSTOMER', 'Select Customer');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('TEXT_NEWSLETTER_CUSTOMERS', 'To All Newsletter Subscribers');
define('TEXT_CONFIRM_DELETE', 'Are you sure you want to delete this Coupon?');


/////////////////////////////////////////////////////////
///sales_coupon.php SEND MESSAGE HERE
define('TEXT_TO_REDEEM', 'You can redeem this coupon during checkout. Just enter the code in the box provided, and click on the redeem button.<br>');
define('TEXT_IN_CASE', ' in case you have any problems.<br> ');
define('TEXT_VOUCHER_IS', 'The coupon code is ');
define('TEXT_REMEMBER', '<br>Don\'t lose the coupon code, make sure to keep the code safe so you can benefit from this special offer.<br>');
define('TEXT_VISIT', 'when you visit ' . HTTP_SERVER . DIR_WS_CATALOG);
define('TEXT_ENTER_CODE', ' and enter the code ');
//////////////////////////////////////////////////////////
define('TEXT_EMAIL_BUTTON_HTML', '<p><HR><b><font color="red">HTML is currently Disabled!</b></font><br><br>If you want to edit the message. It resides in the language file admin/includes/languages/english/sales_coupon.php<br>');

define('TABLE_HEADING_ACTION', 'Action');

define('CUSTOMER_ID', 'Customer id');
define('CUSTOMER_NAME', 'Customer Name');
define('REDEEM_DATE', 'Date Redeemed');
define('IP_ADDRESS', 'IP Address');

define('TEXT_REDEMPTIONS', 'Redemptions');
define('TEXT_REDEMPTIONS_TOTAL', 'In Total');
define('TEXT_REDEMPTIONS_CUSTOMER', 'For this Customer');
define('TEXT_NO_FREE_SHIPPING', 'No Free Shipping');

define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Error: No customer has been selected.');
define('ERROR_NO_COUPON_AMOUNT','Error: No coupon amount is given');
define('COUPON_NAME', 'Coupon Name');
//define('COUPON_VALUE', 'Coupon Value');
define('COUPON_AMOUNT', 'Coupon Amount');
define('COUPON_CODE', 'Coupon Code');
define('COUPON_STARTDATE', 'Start Date');
define('COUPON_FINISHDATE', 'End Date');
define('COUPON_FREE_SHIP', 'Free Shipping');
define('COUPON_DESC', 'Coupon Description');
define('COUPON_MIN_ORDER', 'Coupon Minimum Order');
define('COUPON_USES_COUPON', 'Uses per Coupon');
define('COUPON_USES_USER', 'Uses per Customer');
define('COUPON_PRODUCTS', 'Valid Product List');
define('COUPON_CATEGORIES', 'Valid Product Categories List');

define('TEXT_COUPON_TAX_CLASS', 'TaxClass');
define('TEXT_COUPON_PRICE_NET','Coupon Price(Net)');
define('TEXT_COUPON_PRICE_GROSS','Coupon Price(Gross)');

define('VOUCHER_NUMBER_USED', 'Number Used');
define('DATE_CREATED', 'Date Created');
define('DATE_MODIFIED', 'Date Modified');
define('TEXT_HEADING_NEW_COUPON', 'Create New Coupon');
define('TEXT_NEW_INTRO', 'Please fill out the following information for the new coupon.<br>');
define('COUPON_USES_ORDER_HELP','Number of times a user can use the coupon on each order, leave blank for no limit');
define('COUPON_USES_ORDER','Uses per order');
define('COUPON_NAME_HELP', 'A short name for the coupon');
define('COUPON_AMOUNT_HELP', 'The value of the discount for the coupon, either fixed or add a % on the end for a percentage discount.');
define('COUPON_CODE_HELP', 'You must enter your own coupon code here.');
define('COUPON_STARTDATE_HELP', 'The date the coupon will be valid from');
define('COUPON_FINISHDATE_HELP', 'The date the coupon expires');
define('COUPON_FREE_SHIP_HELP', 'The coupon gives free shipping on an order. Note. This overrides the coupon_amount figure but respects the minimum order value');
define('COUPON_DESC_HELP', 'A description of the coupon for the customer');
define('COUPON_MIN_ORDER_HELP', 'The minimum order value before the coupon is valid');
define('COUPON_USES_COUPON_HELP', 'The maximum number of times the coupon can be used, leave blank if you want no limit.');
define('COUPON_USES_USER_HELP', 'Number of times a user can use the coupon, leave blank for no limit.');
define('COUPON_PRODUCTS_HELP', 'A comma separated list of product_ids that this coupon can be used with. Leave blank for no restrictions.');
define('COUPON_CATEGORIES_HELP', 'A comma separated list of cpaths that this coupon can be used with, leave blank for no restrictions.');

define('TEXT_ERROR_PRICE','Enter valid Price');

define('TEXT_COUPON_ALL_APPLY','Apply coupon ');
define('TEXT_COUPON_ALL_APPLY_HELP','It will be selected coupon can be applied to all categories of products,events,subscriptions and services');
define('TEXT_COUPON_SELECTED_ONLY','Apply  coupon to selected list');
define('TEXT_COUPON_SELECTED_ONLY_HELP','It will be selected coupon can be applied to selected categories only');
define('ERROR_SELECT','Must select one category');
define('ERROR_SELECT_ITEMS','Must select one item');
define('ERROR_DUBLICATE','Already Selected');
define('ERROR_AVILABLE_LIST_EMPTY','Avilable list is empty');
define('ERROR_SELECTED_LIST_EMPTY','Selected list is empty');
define('TEXT_NO_LIMIT','No Limit');
define('ERROR_NO_SELECT','Must select Categories or List');

define('TEXT_DELETE_SUCCESS','successfully deleted');
define('TEXT_EMAIL_SENT','E-mail(s) sent successfully');
define('TEXT_EMAIL_NOT_SENT','E-mail(s) not sent');
define('ERROR_USES_PER_USER','Uses Per Customer does not exceed Uses Per Coupon');
define('ERROR_COUPON_CODE_EXISTS','Coupon Code already exists,Please select another One');
define('TEXT_ERROR_COUPON','Enter valid Coupon code');
define('COUPON_BUTTON_PREVIEW','Preview');
define('COUPON_BUTTON_CONFIRM','Confirm');

define('HEADING_NEW_TITLE','New Discount Coupon');
define('TEXT_LOADING_DATA','Loading Data');
define('VALID_COUPON_NAME','Coupon Name Required');
define('VALID_COUPON_CODE','Coupon Code Required');
define('VALID_MIN_ORDER','Coupon Minimum Order Required');
define('COUPON_AMOUNT_REQUIRED','Coupon Amount Required');
define('TEXT_COUPON_DELETE_SUCCESS','Coupon Deleted Successfully');
define('TEXT_RECORDS','Coupons');
define('SELECT_CUSTOMERS','Please select a Customer or Newsletter Group from the drop down list to proceed');
define('SELECT_MESSAGE','Email Message Required');
define('SELECT_SUBJECT','Email Subject Required');
define('TEXT_SEND_EMAIL_SUCCESS','Email Sent Successfully');
define('TEXT_EMPTY_COUPONS','No Records Found');
define('MIN_ORDER_MUST_BE_NUMERIC','Minimum Order must be a Numeric value');
?>