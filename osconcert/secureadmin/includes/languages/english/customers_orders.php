<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('TEXT_IMPORTANT','<span class="red">IMPORTANT:</span> Use Box Office at the front-end to create orders or directly adjust orders Admin>Orders>Edit');
define('HEADING_TITLE', 'Orders');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_PURCHASER', 'Purchaser');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_CATEGORIES', 'Show Category');
define('TABLE_HEADING_SHOW_NAME', 'Show');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Date ID');
define('TABLE_HEADING_PRODUCTS_SKU', '');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('TABLE_HEADING_USER_ADDED', 'User');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');
define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_SHIPPING_DATE','Shipping Date:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');
define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Method:');
define('TEXT_INFO_PAYMENT_DETAILS','Payment Details:');
define('TEXT_INFO_RECEIPT_NO','Receipt No: ');
define('TEXT_INFO_TRANSACTION_NO','Transaction No: ');
define('TEXT_INFO_REFERENCE_ID','Reference ID:');
define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');
define('TEXT_REFUNDABLE_AMOUNT', 'Refundable Amount:');
define('TEXT_REFUND', 'Refund');
define('TEXT_RESTOCK', 'Restock');
define('TEXT_EDIT', 'Edit');
define('TEXT_DELETE', 'Delete');
define('TEXT_INVOICE', 'Invoice');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");
define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');
define('IMAGE_TICKET','Ticket');
define('TEXT_OSTART_DATE','Start Date:');
define('TEXT_OEND_DATE','End Date: ');
define('TEXT_OSTART_TIME','Start Time: ');
define('TEXT_OEND_TIME','End Time: ');
define('TEXT_ORESOURCE_NAME','<b>Resource:</b> ');
define('TEXT_OQUANTITY','<b>Quantity</b> ');

define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('ERROR_NULL_DATE','Shipping Date is Required');
define('ERROR_SHIPPING_DATE','Shipping Date must be Greater than Or Equal to Order Date');
define('ERROR_DATE_INVALID','Invalid Date');
define('ERROR_EMPTY_SHIPPING_DATE','Delivery date should not be empty');
define('ERROR_ORDER_ID','Order Id is required');
define('ERROR_ORDER_INTEGER','Order Id must be integer');

define('TEXT_DELIVERED_ID','3');
define('TEXT_DELIVERED','Delivered');
define('TEXT_NO_ORDERS_DETAILS_FOUND','No Order Details Found');
define('TEXT_TOTAL_PENDING_AMOUNT','Pending Amount:');
define('TEXT_DETAILS','Details');

define('HEADING_IP_ADDRESS','Customer Ip Address ');
define('TEXT_FULL_REFUND','Full Refund');
define('TEXT_PARTIAL_REFUND','Partial Refund');
define('TEXT_AMOUNT','Amount');
define('TEXT_PERCENT','Percent');
define('JS_ERROR_R_AMOUNT','Amount should be numeric');
define('JS_ERROR_R_PERCENT','Amount should be within 100');
define('TEXT_ORDERS_STATUS','Order Status: ');
define('TEXT_ORDERS_TOTAL','Order Total: ');

define('TABLE_HEADING_ORDERS_ID','Order id');
//March 2014 changes
define('TEXT_SEARCH','Search for:&nbsp;');
define('TEXT_SEARCH_IN','in:&nbsp;');
define('TEXT_SEARCH_IN_ALL','Please select');
//end
define('TEXT_LOADING_DATA','Loading...');
define('TEXT_RECORDS','Orders');
define('TEXT_UNDEFINED_METHOD','Undefined Method');
define('TEXT_DELETE_INTRO','Are you sure you want to delete this order?');
define('ORD_DELETING','Deleting Order...');
define('TEXT_ORDERS_DELETE_SUCCESS','Order deleted successfully.');
define('INFO_FILTERING_DATA','Filtering orders...');
define('INFO_SEARCHING_DATA','Searching...');
define('ERROR_EMPTY_AMOUNT','You must give your suggested amount');
define('ERROR_EMPTY_RESTOCK_CHOICE','Please select one of the restock item below');

define('ERR_CHOICE_EMPTY','You should select the Refund type');
define('ERR_AMOUNT_EMPTY','Invalid Refund Amount');
define('ERR_REFUND_AMOUNT','Refund Amount should less than Refundable Amount');
define('ERR_PERCENTAGE_VALUE','Percentage Amount should less than 100');
define('TEXT_ORDER_AMOUNT' , 'Order Amount: ');
define('TEXT_REFUND_TYPE','Refund Type: ');
define('TEXT_REFUND_AMOUNT','Refund Amount: ');
define('TEXT_FULLY','Full Refund');
define('TEXT_PARTIALLY','Partial Refund');
define('TEXT_REFUND_ALREADY','Order amount already refunded ! ');
//for the search orders
define('TEXT_REF_ID','Reference ID');
define('TEXT_BILLING_NAME','Billing Name');
define('TEXT_PRODUCTS_ID','Products ID');
define('TEXT_DATE_ID','Date ID');
?>