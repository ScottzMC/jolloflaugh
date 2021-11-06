<?php
/*
  Copyright (c) 2021 osConcert
*/

define('HEADING_TITLE', '');
define('ADDING_TITLE', 'Adding seat(s) to Order #%s');
define('CLICK_HERE','Click Here');
define('TEXT_PRICE','Price');
define('TEXT_QUANTITY','Qty');
define('TEXT_PRODUCTS_NAME','Tickets');
define('TICKET_LINK','Ticket Link');
define('TICKET_PRINTED','Ticket Printed');
define('ENTRY_UPDATE_TO_CC', '(Update to ' . ORDER_EDITOR_CREDIT_CARD . ' to view CC fields.)');
define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_NEW_STATUS', 'New status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_DELETE', 'Delete?');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'DATE ID');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_BASE_PRICE', 'Price<br>(base)');
define('TABLE_HEADING_UNIT_PRICE', 'Price<br>(excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Price<br>(incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total<br>(excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total<br>(incl.)');
define('TABLE_HEADING_OT_TOTALS', 'Order Totals:');
define('TABLE_HEADING_OT_VALUES', 'Value:');
define('TABLE_HEADING_SHIPPING_QUOTES', 'Shipping Quotes:');
define('TABLE_HEADING_NO_SHIPPING_QUOTES', 'There are no shipping quotes to display!');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer<br>Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('ENTRY_CUSTOMER', 'Customer');
define('ENTRY_NAME', 'Name:');
define('ENTRY_CITY_STATE', 'City, State:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address');
define('ENTRY_BILLING_ADDRESS', 'Billing Address');
define('ENTRY_PAYMENT_METHOD', 'Payment Method');
define('ENTRY_CREDIT_CARD_TYPE', 'Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TYPE_BELOW', 'Type below');

//the definition of ENTRY_TAX is important when dealing with certain tax components and scenarios
define('ENTRY_TAX', 'Tax');
//do not use a colon (:) in the defintion, ie 'VAT' is ok, but 'VAT:' is not

define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Send Comments:');
define('ENTRY_CURRENCY_TYPE', 'Currency');
define('ENTRY_CURRENCY_VALUE', 'Currency Value');

define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');
define('TEXT_NO_ORDER_PRODUCTS', 'This order contains no products');
define('TEXT_ADD_NEW_PRODUCT', 'Add products');
define('TEXT_PACKAGE_WEIGHT_COUNT', 'Package Weight: %s  |  Product Qty: %s');

define('TEXT_STEP_1', '<b>Step 1:</b>');
define('TEXT_STEP_2', '<b>Step 2:</b>');
define('TEXT_STEP_2_NONE', 'Sold out - no seats available');
define('TEXT_STEP_3', '<b>Step 3:</b>');
define('TEXT_STEP_4', '<b>Step 4:</b>');
define('TEXT_SELECT_CATEGORY', '- Choose an event -');
define('TEXT_PRODUCT_SEARCH', '<b>- OR enter a search term in the box below to see potential matches -</b>');
define('TEXT_ALL_CATEGORIES', 'All Events');
define('TEXT_SELECT_PRODUCT', '- Choose a Product -');
define('TEXT_BUTTON_SELECT_OPTIONS', 'Select These Options');
define('TEXT_BUTTON_SELECT_CATEGORY', 'Select This Category');
define('TEXT_BUTTON_SELECT_PRODUCT', 'Select This Product');
define('TEXT_SKIP_NO_OPTIONS', '<em>No Options - Skipped...</em>');
define('TEXT_QUANTITY', 'Quantity:');
define('TEXT_QTY', 'Qty');
define('TEXT_PRODUCTS_NAME', 'Product:');
define('TEXT_TICKET_NAME', 'Tickets');
define('TEXT_BUTTON_ADD_PRODUCT', 'Add to Order');
define('TEXT_CLOSE_POPUP', '<u>Close</u> [x]');
define('TEXT_ADD_PRODUCT_INSTRUCTIONS', 'Keep adding products until you are done.<br>Then close this tab/window, return to the main tab/window, and press the "update" button.');
define('TEXT_PRODUCT_NOT_FOUND', '<b>Product not found<b>');
define('TEXT_SHIPPING_SAME_AS_BILLING', 'Shipping same as billing address');
define('TEXT_BILLING_SAME_AS_CUSTOMER', 'Billing same as customer address');

define('IMAGE_ADD_NEW_OT', 'Insert new custom order total after this one');
define('IMAGE_REMOVE_NEW_OT', 'Remove this order total component');
define('IMAGE_NEW_ORDER_EMAIL', 'Send new order confirmation email');

define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('PLEASE_SELECT', 'Please Select');

//Order Update Notification osConcert
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Your order has been updated');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Thank you so much for your order.' . "\n" . 'We confirm your payment has been made. Please click on the link below and log into your account. From there you will be able to retrieve and print your e-tickets.' . "\n" . 'New status: %s' . "\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n%s\n");
//eof

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order %s does not exist.');
define('ERROR_NO_ORDER_SELECTED', 'You have not selected an order to edit, or the order ID variable has not been set.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('SUCCESS_EMAIL_SENT', 'Completed: The order was updated and an email with the new information was sent.');

//the hints
define('HINT_UPDATE_TO_CC', 'Set payment method to ' . ORDER_EDITOR_CREDIT_CARD . ' and the other fields will be displayed automatically.  CC fields are hidden if any other payment method is selected.  The name of the payment method that, when selected, will display the CC fields is configurable in the Order Editor area of the Configuration section of the Administration panel.');
define('HINT_UPDATE_CURRENCY', 'Changing the currency will cause the shipping quotes and order totals to recalculate and reload.');
define('HINT_SHIPPING_ADDRESS', 'If you change the shipping state, postcode, or country you will be given the option of whether or not to recalculate the totals and reload the shipping quotes.');
define('HINT_TOTALS', 'Feel free to give discounts by adding negative values. Subtotal, tax total, and grand total fields are not editable. When adding in custom order total components via AJAX make sure you enter the title first or the code will not recognize the entry (ie, a component with a blank title is deleted from the order).');
define('HINT_PRESS_UPDATE', 'Please click on "Update" to save all changes.');
define('HINT_BASE_PRICE', 'Price (base) is the products price before products attributes (ie, the catalog price of the item)');
define('HINT_PRICE_EXCL', 'Price (excl) is the base price plus any product attributes prices that may exist');
define('HINT_PRICE_INCL', 'Price (incl) is Price (excl) times tax');
define('HINT_TOTAL_EXCL', 'Total (excl) is Price (excl) times qty');
define('HINT_TOTAL_INCL', 'Total (incl) is Price (excl) times tax and qty');
//end hints

//new order confirmation email- this is a separate email from order status update
define('ENTRY_SEND_NEW_ORDER_CONFIRMATION', 'New order confirmation:');
define('EMAIL_TEXT_DATE_MODIFIED', 'Date Modified:');
define('EMAIL_TEXT_PRODUCTS', 'Products');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');
// If you want to include extra payment information, enter text below (use <br> for line breaks):
//define('EMAIL_TEXT_PAYMENT_INFO', ''); //why would this be useful???
// If you want to include footer text, enter text below (use <br> for line breaks):
define('EMAIL_TEXT_FOOTER', '');
//end email

//add-on for downloads
define('ENTRY_DOWNLOAD_COUNT', 'Download #');
define('ENTRY_DOWNLOAD_FILENAME', 'Filename');
define('ENTRY_DOWNLOAD_MAXDAYS', 'Expiry days');
define('ENTRY_DOWNLOAD_MAXCOUNT', 'Downloads remaining');
 define('EDIT_ORDERS_TOO_MANY','You tried to order too many products - please adjust the amount');

?>
