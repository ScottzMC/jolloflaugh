<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'en_US.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_FORMAT_SPIFFYCAL', 'MM/dd/yyyy');  //Use only 'dd', 'MM' and 'yyyy' here in any order

//make your own
define('OSCONCERT_FORMAT', DATE_FORMAT_LONG);
////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'osConcert');

//osConcert Help Messages (not really required to be translated)
define('OSCONCERT_MESSAGE_PRODUCTS', '<b>Category Editor:</b> Edit Perfomance Details and Upload Category/Ticket Images Here<br><br>This is also the best place to go if you want to make simple edits to the existing seat plan <b>products/seat cells</b>. This is also the place to start to create <b>General Admission</b> events. You can find tutorials to help in the Shop settings><a href="help_manuals.php">Help Manuals</a>. NOTE: Use <a href="backup_categories.php">Alternative Edit Categories</a> for speedier and more simple edits like status changes or for more flexibility try <a href="products_data.php">Edit Products Data</a> <br><br>For <b>Row Status</b> Edits Seat Products must be activated for this feature \'products.manufacturers_id\' must have a category ID. (Rows are protected) See osConcert FAQ');
define('OSCONCERT_MESSAGE_AEC', 'This is the best place to go if you want to make simple edits to the existing seat plan products/seat cells, especially if you want to quickly locate and set the status of any seat cells. Instock-Out-of-stock-Hide. See <a href="help_manuals.php">Help Manuals</a>');
define('OSCONCERT_MESSAGE_PD', '<h4>Simply click White Buttons to hide the seat as a Social Distancing measure and Green Buttons to enable seats at the seat plan. Also try \'Edit Products Data\' for easier ways to edit multiple products values. <a href="products_data.php">Edit Products Data</a></h4>');
define('OSCONCERT_MESSAGE_PDATA', '<h5>Set Products (Seats) ready for reservation</h5><h6>Quantity=1 :: Status=1 :: Fixed=1 (can be RESET) Color Code (red,yellow,green,blue,fuchsia,thistle,orange,teal,salmon,palegreen,skyblue)</h6><h6>Disable products: Quantity=0 :: Status=0 (Reserved) Status=3 (Blanked for SD) Status=2 (Hidden) :: Fixed=0 (no reset)</h6>');
define('PRODUCTS_DATA', '<h5>Data from the TABLE products and products_description</h5><h6></h6>');
define('PRODUCTS_ONLY_DATA', '<h5>Data from the TABLE products</h5><h6></h6>');
define('BARCODE_DATA', '<h5>Data from the TABLE orders_barcode</h5><h6></h6>');
define('ORDERS_DATA', '<h5>Data from the TABLE orders</h5><h6></h6>');
define('CATEGORIES_DATA', '<h5>Data from the TABLE categories and categories_description</h5><h6></h6>');
define('DESIGN_DATA', '<h5>Data from the TABLE products relating to the design mode</h5><h6></h6>');
define('OSCONCERT_MESSAGE_ORDERS', '<h4>Use Box Office at the front-end to create orders or directly adjust orders Admin>Orders>Edit</h4>');
define('WARNING_DATE_ID_MISSING','There are active products WITHOUT a Date ID: Please update your products in Admin>Concert Details to ensure correct reporting!');

define('SERVER_TIME_IS', 'For your information the current server time is: ');
define('HEADER_TITLE_ACCOUNT', 'My Account');
define('HEADER_TITLE_LOGOFF', 'Logoff');


//define('TEXT_PRICE','Price');
define('TEXT_QUANTITY','Qty');
define('TEXT_PRODUCTS_NAME','Tickets');
//CodeReadr barcoded ticket link
define('CR_TICKET_LINK','<b>Collect Your Tickets Here: </b>');
// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');
define('BOX_HEADING_BOXES_LANG','Language Edit');

//Not required to be translated
define('NEW_FIELDS_HEADING','Details');
define('FIELD_1','Name: ');
define('FIELD_2','Address: ');
define('FIELD_3','Contact: ');
define('FIELD_4','Email: ');
define('FIELD_5','Other: ');

define('TEXT_IP_ADDRESS','Customer IP Address');

define('TEXT_NO_PRODUCTS_FOUND','No Products Found');
define('TEXT_REDEEM','Redeem your Discount Coupons:');
define('TEXT_DISCOUNT_COUPONS','Discount Coupons');
define('DISCOUNT_COUPONS','Discount Coupon Redeem');



define('ENTRY_CUSTOMER_OCCUPATION','Occupation: ');
define('ENTRY_CUSTOMER_INTEREST','Interest: ');
define('ENTRY_CUSTOMER_OCCUPATION_TEXT','<font color="#AABBDD">required</font></small>');
define('ENTRY_CUSTOMER_INTEREST_TEXT','<font color="#AABBDD">required</font></small>');

define('IMAGE_ADD','Add');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid.<br>Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s <br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.');
define('TEXT_ADMINISTRATOR_ENTRY','Top Administrator');
define('TEXT_INSTRUCTOR_ENTRY','Instructor');
define('TEXT_CALLMANAGER_ENTRY','Call Centre Manager');
define('TEXT_CALLSTAFF_ENTRY','Call Centre Staff');
define('TEXT_PRODUCTMANAGER_ENTRY','Product Manager');
define('TEXT_RESERVATIONMANAGER_ENTRY','Reservation Manager');
define('TEXT_SELECT_PAYMENT_METHOD','Select Payment method');
define('TEXT_ENTER_PAYMENT_INFORMATION','This is only the payment available');

define('TEXT_CURRENT_STATUS','Current Status');
define('TEXT_MODIFY_STATUS','Modify Status');
define('TEXT_CREATE_ORDER','Create order');

define('TEXT_NO_OFFSET','No Offset');
define('TEXT_OFFSET_HOURS','Offset hours');
define('TEXT_NO_LIMIT','No Limit');
define('TEXT_MES_START','Start time');
define('TEXT_MES_END','End time');
define('TEXT_ENABLED','Enabled');
define('TEXT_DISABLED','Disabled');
define('TITLE_PLEASE_SELECT','Please Select');

define('REPORT_NEXT_BUTTON','Next');
define('REPORT_PREV_BUTTON','Prev');
define('REPORT_NO_RESULTS','No Results found');

define('INVOICE_TAX', 'Total Tax: ') ;
define('INVOICE_SHIPPING', 'Shipping: ');
define('INVOICE_SUBTOTAL', 'SubTotal: ');
define('INVOICE_TOTAL', 'Total: ');
define('INVOICE_TOTAL_AMOUNT','Total: ');


define('JS_EVENT_DOB', '* The \'Date of Birth\' entry must be in the format: ' . format_date('1970-05-21') . '.\n');
define('JS_SECOND_EMAIL_ADDRESS', '* The Second \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_SECOND_TELEPHONE', '* The Second \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_MOBILE', '* The \'Mobile Number\' must have only numeric characters. (eg.0415981853) \n');
$format=array('d-m-Y'=>'dd-mm-yyyy',
			  'm-d-Y'=>'mm-dd-yyyy',
			  'Y-m-d'=>'yyyy-mm-dd');


define('DOB_EVENT_FORMAT_STRING', $format[EVENTS_DATE_FORMAT]);
define('ORDERS_EDIT_SUCCESS','Success: Order updated');
define('BOX_CUSTOMERS_REFERRALS', 'Customer Referral'); //rmh referrals
define('BOX_REPORTS_REFERRAL_SOURCES', 'Referral Sources'); //rmh referrals
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> referral sources)'); //rmh referrals
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_OPTIONS_OCCUPATION', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customer occupations)'); 
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_OPTIONS_INTEREST', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customer interests)'); 

define('BOX_GENERAL_TITLE','<b>General</b>');
define('BOX_PRODUCTS_TITLE','<b>Products</b>');
define('BOX_WALLET_TITLE','<B>Wallet</B>');
define('TEXT_REFERRAL_SOURCE','Referral Source');
define('HOW_DID_U_HEAR','How did you hear about us:');
define('TEXT_OTHER','(if "Other" please specify)');
define('PULL_DOWN_DEFAULT','Please Select');
define('TEXT_REFERRAL_OTHER','Other');
define('ENTRY_SOURCE_ERROR', 'Please select how you first heard about us.');
define('ENTRY_SOURCE_OTHER_ERROR', 'Please enter how you first heard about us.');
define('ENTRY_CUSTOMER_OCCUPATION_ERROR', 'Please select Occupation.');
define('ENTRY_CUSTOMER_INTEREST_ERROR', 'Please select Interest.');
define('ENTRY_SUSPEND_DATE_ERROR','Please select Valid Suspend date');
define('ENTRY_RESUME_DATE_ERROR','Please select Valid Resume date');
define('SUSPEND_DATE_EXCEED_RESUME_DATE_ERROR','Suspend date Should not Exceed Resume date');

define('BOX_CUSTOMERS_GROUPS', 'Customer Groups');
define('BOX_WALLET_EMARKETING','Wallet E-Marketing');
define('WALLET_TYPE_CURRENCY','Currency');
define('WALLET_TYPE_PERCENTAGE','Percentage');
define('BOX_HEADING_DEFINEMAINPAGE','Define Mainpage');
define('BOX_HEADING_INFORMATION_PAGE','Define Information');
define('ENTRY_USERNAME', 'Username:');
define('ENTRY_USERNAME_ERROR', '&nbsp;<span class="errorText">User Name  must contain a minimum of ' . ENTRY_USERNAME_MIN_LENGTH . ' characters.</span>');
define('ENTRY_USERNAME_ERROR_EXISTS', '&nbsp;<span class="errorText">User Name already exists</span>');
define('ENTRY_USERNAME_TEXT', ' <small><font color="#AABBDD">required</font></small>');
define('JS_USERNAME', '* The \'User Name\' entry must have at least ' . ENTRY_USERNAME_MIN_LENGTH . ' characters.\n');

define('TEXT_MAINPAGE_TYPE','Mainpage Type:');
define('TEXT_MAINPAGE_CATEGORIES','Categories:');
define('TEXT_HTMLAREA','HtmlArea');
define('TEXT_PRODUCTS','Product');
define('TEXT_ITEM_TYPE','Item Type');
define('TEXT_PRODUCT','Product');
define('BOX_HEADING_CUSTOMER_OCCUPATION','Customer Occupation');
define('BOX_HEADING_CUSTOMER_INTEREST','Customer Interest');
define('ERROR_FILE_NOT_WRITEABLE','%s is not writable. Please give permission to write');

// entry for Programm Version
define('PROGRAM_VERSION','v9');
define('TEXT_DEFAULT','Default');
define('BOX_META_TAGS','SEO');
define('TEXT_INDEX','#');
define('TEXT_CLIENT','Client');
define('TEXT_SALE_DATE','Sale Date');
define('TEXT_UNIT_PRICE','Unit Price');
define('TEXT_SUBTOTAL','Sub Total');
define('TEXT_START_DATE','Start Date');
define('TEXT_END_DATE','End Date');
define('TEXT_SOLD','Sold');
define('TEXT_PRODUCT','Product');
define('TEXT_SUMMARY','Summary');
define('TEXT_YES','YES');
define('TEXT_TOTAL','Total');
define('TEXT_NO_RESULTS','<font color="red">No Result Found</font>');
define('TEXT_BANK_TRANSFER','Bank Transfer');
define('TEXT_SUB_TOTAL','Sub Total');
define('TEXT_WORLD_ZONES','World Zones');

define('TEXT_ALL_PRODUCTS','All Products');

define('ERR_IMAGE_UPLOAD_TYPE','Please only upload files that end in types:.gif,.jpg,.jpeg,.png');
define('IMAGE_DOES_NOT_EXIST','Image does not exists');
define('JS_EMAIL_CONFIRM_ADDRESS', '* The \'Email address\' entry must have match the Confirm E-Mail Address.\n');
define('ENTRY_CONFIRM_EMAIL_ADDRESS_ERROR', '<span class="errorText">The \'Email address\' entry must have match the Confirm E-Mail Address</span>');
define('JS_SECONDEMAIL_CONFIRM_ADDRESS', '* The \'Second E-Mail Address\' entry must have match the Confirm Second E-Mail Address.\n');
define('ENTRY_CONFIRM_EMAIL_ADDRESS', 'Confirm E-Mail:');
define('ENTRY_SECOND_EMAIL_ADDRESS_MATCH_ERROR','<span class="errorText">Second email address doest match second Confirm email address</span>');
define('ENTRY_SECOND_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The second email address doesn\'t appear to be valid!</span>');

define('TEXT_NO_PRODUCTS_FOUND','No Products Found');
define('JS_SECOND_EMAIL_ADDRESS_UNIQUE','* The second email address must be unique\n');
define('TEXT_IMAGE_DOES_NOT_EXIST','Image Does Not Exist');

define('TEXT_NO_RECORDS_FOUND','No Records Found');
define('TEXT_TAX','Tax');
define('TEXT_SHIPPING','Shipping');

define('ERROR_NO_INVALID_REDEEM_COUPON', 'This is not a valid redeem code.');
define('ERROR_INVALID_STARTDATE_COUPON', 'This coupon is not available yet');
define('ERROR_INVALID_FINISDATE_COUPON', 'This coupon has expired');
define('ERROR_INVALID_USES_COUPON', 'This coupon could only be used ');  
define('ERROR_ALREADY_ORDER_USES_COUPON','This coupon has already used in this order');
define('ERROR_LESSTHAN_COUPON_PRICE','The value of your Gift Voucher exceeds the cost of your order. If you proceed, Gift Voucher will be used in full and you will not gain the full benefit of your gift voucher');
define('ERROR_LOW_ORDERTOTAL','Order Value is less than Coupon minimum Order');
define('TIMES', ' time(s).');
define('ERROR_INVALID_USES_USER_COUPON', 'You have used the coupon the maximum number of times allowed per customer.'); 
define('REDEEMED_COUPON', 'a coupon worth ');  
define('REDEEMED_MIN_ORDER', 'on orders over ');  
define('REDEEMED_RESTRICTIONS', ' [Product-Category restrictions apply]');  
define('TEXT_ENTER_COUPON_CODE', 'Enter Redeem Code&nbsp;&nbsp;');
define('MODULE_ORDER_TOTAL_COUPON_TITLE','Discount Coupons');
define('ERROR_COUPON_PRICE_TOTAL','Coupon amount and ordertotal is equal ');
define('ERROR_INVALID_ITEM','Coupon Applied To this Item Is Invalid');
define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> links)');

define('ERR_PWD_EMPTY','* Password should not be empty.\n');
define('ERR_PWD_ALPHANUMERIC','* Password allows alphanumeric only.\n');
define('ERR_PWD_ALPHA_SYMBOLS','* Password allows alphanumeric + symbols only.\n');
define('ERR_PWD_DICTIONARY_WORDS','* Password allows alphanumeric + symbols + dictionary words.\n');
define('TEXT_JUMP_TO','Jump To');

define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');

define('TEXT_UPDATED_AMOUNT','Updated Amount');

// constants for use in tep_prev_next_display function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Admin');
define('HEADER_TITLE_ONLINE_CATALOG', 'Front End');
define('HEADER_TITLE_ADMINISTRATION', 'Admin');
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Define MainPage');
define('BOX_CATALOG_DEFINE_INFORMATION_PAGE','Define Information');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a Price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new Price for this product needs to be set\n');

define('JS_GENDER', ' The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', " The \'First Name\' entry must have at least " . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', " The \'Last Name\' entry must have at least " . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', " The \'Date of Birth\' entry must be in the format:".''. 'xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', " The \'E-Mail Address\' entry must have at least " . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', " The \'Street Address\' entry must have at least " . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', " The \'Post Code\' entry must have at least " . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', " The \'City\' entry must have at least " . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', ' The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', " The \'State\' entry must be selected from the list for this country.");
define('JS_COUNTRY', " The \'Country\' value must be chosen".''.'\n');
define('JS_TELEPHONE', "The \'Telephone Number\' entry must have at least " . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', ' The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ALERT_EMAIL_FORMAT',     '- Email address format is invalid! \n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');


define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company:');
define('ENTRY_COMPANY_ERROR', '');

define('ENTRY_BILLING_EMAIL', 'Customers Email:');
define('ENTRY_DELIVERY_EMAIL', 'Customers Email:');

define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Address 2:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State/Province:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');

// images
// define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
 define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_CONTINUE', 'Continue');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_FREIGHT','Freight');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_BOOKING', 'New Booking');
define('IMAGE_NEW_SALE', 'New Sale');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_CREATE_ORDER','Create Order');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SEARCH_DETAILS', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPLOAD', 'Upload');
define('IMAGE_WALLET_UPLOAD','Upload Funds');

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_PAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Pages)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_BARCODES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> barcodes)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
//define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_SALES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> sales)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Discount)');

define('TEXT_NO_RECORDS_FOUND','No Records Found');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Payment->Currencies');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully. (For image uploads, please refresh the browser to reload the image.) ');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');

define('TEXT_DISCOUNT_APPLIED','<b><font color="#54388C">Discount applied</font></b>');
define('TEXT_ORIGINAL_AMOUNT','<b><font color="#54388C">Original Amount</b></font>');
define('TEXT_DISCOUNT','<b><font color="#54388C">Discount</font></b>');

define('IMAGE_BUTTON_PRINT', 'Print');
define('IMAGE_BUTTON_TICKET', 'eTicket');
define('BOX_CATALOG_FEATURED', 'Featured Products');

define('INVOICE_SHIPPING', 'Post and Packing: ');
define('INVOICE_SUBTOTAL', 'SubTotal: ');
define('INVOICE_TOTAL', 'Total: ');
define('INVOICE_TOTAL_AMOUNT','Total: ');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');

// define('ENTRY_CUSTOMERS_ID', 'ID:');
// define('ENTRY_CUSTOMERS_ID_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_COMPANY', 'Company Name:');
// define('ENTRY_COMPANY_ERROR', '');
// define('ENTRY_COMPANY_TEXT', '');
// define('ENTRY_GENDER', 'Gender:');
// define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_FIRST_NAME', 'First Name:');
// define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_LAST_NAME', 'Last Name:');
// define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
// define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(eg. 05/21/1970)</font></small>');
// define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(eg. 05/21/1970) <font color="#AABBDD">required</font></small>');
// define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
// define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">Your email address doesn\'t appear to be valid!</font></small>');
// define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">email address already exists!</font></small>');
// define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_SECOND_EMAIL_ADDRESS_TEXT', '');
// define('ENTRY_STREET_ADDRESS', 'Street Address:');
// define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_SUBURB', 'Address 2');
// define('ENTRY_SUBURB_ERROR', '');
// define('ENTRY_SUBURB_TEXT', '');
// define('ENTRY_POST_CODE', 'Post Code:');
// define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_CITY', 'Suburb:');
// define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_STATE', 'State/Province:');
// define('ENTRY_STATE_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
// define('ENTRY_STATE_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_COUNTRY', 'Country:');
// define('ENTRY_COUNTRY_ERROR', '');
// define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
// define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_NEWSLETTER', 'Newsletter:');
// define('ENTRY_NEWSLETTER_TEXT', '');
// define('ENTRY_NEWSLETTER_YES', 'Subscribed');
// define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
// define('ENTRY_NEWSLETTER_ERROR', '');
// define('ENTRY_PASSWORD', 'Password:');
// define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
// define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars</font></small>');
// define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// define('ENTRY_PASSWORD_STRENGTH_ERROR', '&nbsp;<small><font color="#FF0000">Password strength is poor</font></small>');
// define('PASSWORD_HIDDEN', '--HIDDEN--');

// define('TABLE_HEADING_CREDIT','Credits Available');
// define('IMAGE_REDEEM_VOUCHER', 'Redeem');

define('IMG_EXPORT_PDF','Export to Pdf');
define('IMG_EXPORT_EXCEL','Export to Excel');

define('TEXT_NO_RESULTS_FOUND','No Results Found');
define('WARNING_IMAGE_DIRECTORY_WRITEABLE', 'Warning: I am not able to write to the images folder: %s. Please check the attribute and make it writable.');

/* seatplan caching disabled notice */
define('WARNING_SEATPLAN_CACHE_DISABLED','Seatplan caching is currently disabled - this is recommended for editing/testing purposes ONLY.');

/* live logging enabled notice */
define('WARNING_SEATPLAN_LOGGING_ENABLED','Live logging is currently enabled - this is recommended for testing/debugging purposes ONLY.');

//Updated 2015
define('TEXT_ADD_NO_PRODUCT','Don\'t Add New Product');
define('TEXT_PRODUCTS_NOT_AVAILABLE','Products Are Not Available');
define('CACHE_CLEARED','Cache Cleared');
define('ORDER_UPDATE_SUCCESS','Order Updated Successfully');
define('TEXT_CUSTOMER_NAME','Customer Name');
define('TEXT_SHOW_NAME','Show Name');
define('TEXT_SEAT_NUMBER','Seat Number');
define('DELETE_ALL_ORDERS','Delete multiple orders?');
define('DELETE_WITH_BACKUP','Create backup files?');
define('DELETE_WITH_BACKUP_YES','Yes');
define('DELETE_WITH_BACKUP_NO','No');
######################################################
define('DELETE_ALL_ORDERS_KEYWORD','YES');
######################################################
define ('TEXT_DELETE_ORDERS_WAIT', '<strong>------ Deleting Orders. Please Wait. -------</strong>');
define ('TEXT_UNDO_DELETE_ORDERS_WAIT', '<strong>------ Undoing Delete Orders. Please Wait. -------</strong>');
define ('TEXT_DELETE_ORDERS_TYPE', 'Type of deletion');
define ('TEXT_DELETE_ORDERS_TO_FROM', 'All/to/from order number');
define ('TEXT_DELETE_ORDERS_PASS', 'Keyword');
define('TEXT_DELETE_ALL_ORDERS','All orders');
define('TEXT_ALL_ORDERS_DELETED','<strong>Orders were deleted to undo this type the keyword in the box  &amp; click \'Restore\'</strong>');
define('TEXT_DELETE_UP_TO_ORDERS','UP to AND including order number');
define('TEXT_DELETE_FROM_ORDERS','FROM AND including order number ');
define('TEXT_ABOUT_DELETE_ALL','You are about to delete ALL orders. Click OK to do so. ');
define('TEXT_ABOUT_DELETE_ALL_TO','Click OK to delete orders up to, and including #: ');
define('TEXT_ABOUT_DELETE_ALL_FROM','Click OK to delete orders from #: ');
define('REFUND_NOT_AVAILABLE','');
define('REFUND_AMOUNT','Refund is not available for Pending orders');
define('PARTIALLY_REFUNDED_ON','Partially Refunded On');
define('FULLY_REFUNDED_ON','Fully Refunded On');
define('SELECT_RESTOCK','Please select one of the restock item below.');
define('TEXT_DETAILS','Details');
define('TEXT_REFUND','Refund');
define('TEXT_RESTOCK','Restock');
define('FILE_NOT_FOUND','File Not Found');
define('ORDER_COMMENTS','Order Comments: ');
define('EMAIL_SENT','Email Sent');
define('EDITING_ORDERS','Editing Orders ');
define('GA_ARE_YOU_SURE','Are you sure? This will wipe all Master Quantity data?');
define('SHOPPING_CART','Shopping Cart');
define('TEXT_ERROR','Error');
define('TEXT_NO_CHANGE','No change found in this order so can\'t continue');
define('ORDERED_TOTAL','Ordered Total');
define('CANNOT_REFUND','Cannot Refund. Because the Order Amount is');
define('ORDER_AMOUNT_REFUNDED','Order Amount Refunded');
define('ORDER_AMOUNT_REFUND','Order Amount Refund');
define('ORDER_REFUND','Order Refund');
define('REFUNDABLE_AMOUNT','Refundable Amount');
define('DETAILS','Details');
define('BACKORDER','Backorder');
define('REFUNDED','Refunded');
define('CURRENT_SERVER_TIME_IS','For your information the current server time is');
define('CATEGORY_ID','Category ID');
define('PLEASE_SELECT','Please select');
define('PLUS_RESERVED_SEATING','plus reserved seating');
define('TEXT_ONLY','Only');
define('SECTION_ID','Section ID');
define('SEAT_NUMBER_TEXT','Seat Number (for RESERVED SEATING)');

define('DOWNLOADABLE','Downloadable');
define('PHYSICAL','Physical');
define('QUICK_ORDER_UPDATE','Quick Order Update (Delivered)');
define('QUICK_ORDER_UPDATE_EMAIL','Quick Order Update/Email (Delivered)');
define('TEXT_NEW_COUPON_DETAILS','New coupon details');
define('EXPORT_CUSTOMERS','Export Customers');
define('EXPORT_ORDERS','Export Orders');
define('USER','User: ');
define('ARE_YOU_SURE','Are you sure you want to clear the seat plan cache?');
define('TEXT_ACCOUNT_SETTINGS','Account Settings');
define('TEXT_CLEAR_CACHE','Clear Seat Plan Cache');
define('SIGNOUT','Signout');
define('HELP','Help');
define('ADD_QUICK_LINKS','Add to Quick Links');
define('DELETE_QUICK_LINKS','Delete from Quick Links');
define('FRONT_END','Front End');
define('OSCONCERT_SETTINGS','osConcert Settings');
define('TEXT_CONFIG_LINKS','Quick Configuration Links');
define('TOOLS','Tools');
define('SUPPORT','Support');
define('SUPPORT_DESC','osConcert Knowledge Base and Support');
define('HELP_MANUALS','Help Manuals');

define('TEXT_IMPORTANT_MANAGE','IMPORTANT!');
define('TEXT_ADVANCED','for advanced users only.');
define('TEXT_PRODUCT_MANAGE','Product Manager');
define('TEXT_SECTION_ID','Section ID:');
define('TEXT_USUALLY','(Usually a SHOW category or a sub-category)');
define('TEXT_ROW_ID','Row ID:');
define('TEXT_THE_ROW','(The Row/Sub Category ID that contains the products)');
define('TEXT_PRODUCT_PRICE','Product Price:');
define('TEXT_WITHOUT_CURRENCY','(without currency symbol)');
define('TEXT_COLOR','Color:');
define('TEXT_PRODUCTS_QUANTITY','Products Quantity:');
define('TEXT_PRODUCTS_STATUS','Product Status:');
define('TEXT_ACTIVE','( 1: Active and 0: InActive )');
define('TEXT_SAVE','Save');
define('TEXT_PRODUCT_MANAGER_MULTI','Product Manager (Multiple Rows)');

define('MAILERLITE','<a href="http://www.mailerlite.com/a/xfpketezmg"><img border="0" title="MailerLite Email Marketing for Small Business" alt="MailerLite Email Marketing for Small Business" src="http://affiliate.mailerlite.com/images/banners/mailerlite750x100.gif" width="750" height="100" /></a>');
define('MAILERLITE_SUB','All the Email Marketing features you really need!');
define('MAILERLITE_TEXT','Build beautiful email newsletters with our drag-and-drop Newsletter Editor. Fast and simple!');

define('TEXT_MASTER_QUANTITY','Master Quantity (Your reference)');
define('TEXT_AT_LEAST','Please select at least one order!');
define('TEXT_HELP','Help Manuals');
define('TABLE_HEADING_PM','Payment Method');

//new enable shipping weight
define('TEXT_ALL_TICKETS_SHIPPING','Shipping/Delivery weight? ');
define('SHIPPING','Shipping weight (0.1) enabled for all tickets in this category<br>(Warning: SHIPPING method must be enabled)<br>(Admin>Products>Shipping)');
define('NO_SHIPPING','Shipping weight not enabled(0)');
//GDPR May 25th 2018
define('GDPR_REQUEST_DETAILS','Here is your data request. '.STORE_OWNER.'.');
define('GDPR_REQUEST_SUBJECT','Your '.STORE_OWNER.'. GDPR Data Request');

define('TEXT_ORDER','Order #');
define('TEXT_ORDER_DATE','Order Date & Time');
define('TEXT_NO_REPORT_FOUND','No Report Found');
define('TEXT_APPLY_COUPON','Apply Coupon');
define('TEXT_TO_ALL_CATEGORIES','To All Categories');

define('TEXT_INC','Incl.');
define('TEXT_TAX_PLUS',' Tax: +');
define('LOG_PATH','admin/logs');

define('QUICK_ORDER_UPDATE','Quick Order Update (Delivered)');
define('QUICK_ORDER_UPDATE_EMAIL','Quick Order Update/Email (Delivered)');

define('TEXT_SELECT_PAYMENT_ZONE','Select Payment Zone');
define('TEXT_DEFAULT_ZONE','Default Zone=1');

define('TEXT_RESERVED_SEATING_OR','Reserved Seating or not?');

define('TEXT_TICKET_DISCOUNT',' Discount/Tax: ');
define('TEXT_CANNOT_REFUND','Cannot Refund. Because the Order Amount is: ');
define('TEXT_CANNOT_REFUND','Kann nicht zurückerstattet werden, da der Bestellbetrag: ');
?>