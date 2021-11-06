<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Manage Customers');
define('HEADING_CUSTOMERS','Customers');
define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_LAST_MODIFIED', 'Last Modified');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created');
define('TEXT_CUSTOMER_NAME','Customer Name:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Number of Reviews:');

//GDPR May 25th 2018
define('TEXT_DELETE_INTRO', 'This customer has no orders in the store. Are you sure you want to delete this customer?');

define('TEXT_DELETE_REVIEWS', 'Delete %s review(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Customer');
define('TYPE_BELOW', 'Type below');
define('PLEASE_SELECT', 'Select One');
define('IMAGE_ID_CARD','ID Card');
define('TEXT_SUSPEND_DATE','Suspend From: ');
define('TEXT_RESUME_DATE','Resume From: ');
define('TABLE_HEADING_BLOCKED_STATUS','Blocked Status');
define('ENTRY_BLOCKED_CUSTOMER','Block this customer');
define('TEXT_SHOW_ALL','ShowAll');
define('TEXT_CUSTOMER_INFO','Info');
define('TEXT_CUSTOMER_ACTIONS','New Action');
define('TEXT_ORDERS','Orders');
define('TEXT_EDIT_CUSTOMER','Edit'); 
define('TEXT_ORDERS','orders');
define('TEXT_EMAIL','Email Address');
define('TEXT_LETTERS','Letters');
define('TEXT_ID_CARDS','ID Cards');
define('TEXT_CREATE_ORDER','Create Order');
define('TEXT_CREATE_CUSTOMER','Create Customer');
define('TEXT_DATE_PURCHASED','Date Purchased:');
define('TEXT_PAYMENT_METHOD','Payment Method:');
define('TEXT_PRODUCT_NAME','Product:');
define('TEXT_TOTAL','Total:');
define('TEXT_NO_RECORD_FOUND','No Details Found');
define('TEXT_WALLET_BALANCE','Wallet Balance');
define('TEXT_WALLET','Wallet');
define('TEXT_UPLOAD_FUNDS','Upload Funds');
define('TEXT_MORE_ORDERS','More Orders...');
define('TEXT_OSCONCERT_MESSAGE', 'BOX OFFICE: Create Box Office users here. Select Country=Box Office ...they will appear as red color in the list of customers below. <br>To show all Box Office Users <span class="red">SEARCH= 999</span> in the search box above');
define('TEXT_CLIENTS', 'Customers');
define('TEXT_GROUPS', 'Groups');
define('BOX_CLIENTS', 'Create Customer');
define('BOX_ORDERS', 'Create Order');
define('TEXT_FIRST_NAME','First Name: ');
define('TEXT_LAST_NAME','Last Name: ');
define('TEXT_EMAIL_ADDRESS','Email Address: ');
define('TEXT_SEARCH','Search');
define('TEXT_DELETE','Delete');
define('TEXT_RECORDS','Customers');
define('TEXT_UNDEFINED_METHOD','Undefined Method');
define('TEXT_CUSTOMER_NOT_DELETED','Customer Not Deleted');
define('TEXT_DELETE_SUCCESS','Customer Deleted Successfully'); 
define('TEXT_CUSTOMER_DETAIL_NOT_FOUND','Customer Detail Not Found');
define('TEXT_LOADING_DATA','Loading data...'); 
define('TEXT_EMPTY','Field Is Empty');
define('TEXT_NEW_PASSWORD','Enter your new password');
define('TEXT_CHANGE_PASSWORD','Change password');
define('HEADING_UPLOADS','Wallet Uploads');
define('HEADING_UPLOAD_CONFIRMATION', 'Upload Confirmation');

define('HEADING_SUCCESS', 'The Upload Has Been Processed!');

define('TEXT_UPLOAD_SUCCESS', 'The upload to wallet has been successfully processed! You will receive an email confirming your wallet upload.');
define('TEXT_CREDIT','The Balance of your account will be credited with %s When we receive your funds.');
define('TEXT_WALLET_UPLOAD_BALANCE','Balance amount in your Wallet : %s');

define('JS_SUBJECT_ERROR','Subject is required');
define('JS_EMAIL_ADDRESS_ERROR','Please Select the Customers email address');
define('JS_PAYMENT_ERROR','Please select a Payment method to upload funds to Wallet');
define('JS_WALLET_AMOUNT','Wallet Amount is invalid');

define('TEXT_NO_PAYMENT_SELECTION','No Payment Selection Available');

define('ENTRY_VIP_CUSTOMER', 'VIP Customer');

//GDPR May 25th 2018
//define('TEXT_INFO_CHILD_COUNT','It is not possible to delete a user while they have outstanding orders or upcoming reservations.');
define('TEXT_INFO_CHILD_COUNT','This customer has ');
define('TEXT_INFO_CHILD_LAST', ' orders in the store. The most recent one was on: ');
define('TEXT_INFO_CHILD_YESNO', 'Delete all orders? ');

define('TEXT_GDPR_EXPORT', 'Export GDPR Personal Data to CSV');


//CGDiscountSpecials start
define('ENTRY_CUSTOMERS_DISCOUNT', 'Customer Discount Rate:');
define('ENTRY_CUSTOMERS_GROUPS_NAME', 'Group:');
//CGDiscountSpecials end
define('TEXT_CURRENT_WALLET_BALANCE','Wallet Balance: ');
define('TEXT_WALLET','Wallet');

define('IMAGE_ICON_STATUS_BLOCKED_LIGHT','Set Blocked');
define('IMAGE_ICON_STATUS_UNBLOCKED_LIGHT','Set Unblocked');
define('IMAGE_ICON_STATUS_BLOCKED','Blocked Status');
define('IMAGE_ICON_STATUS_UNBLOCKED','Unblocked Status');

define('TEXT_TITLE_C','Customer Account');
define('TEXT_MERGE_FIELDS','Merge Fields');
define('TEXT_CREATE_PWD','Create Password');
?>
