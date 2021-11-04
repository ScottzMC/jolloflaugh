<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
//@setlocale(LC_ALL, array('en_US.UTF-8', 'en_US.UTF8', 'enu_usa'));
@setlocale(LC_TIME,'en_GB.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
//define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B'); // this is used for strftime() //%A %d %B, %Y ??%a %D %b %Y
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', ''); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'mm/dd/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate
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

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS', 'dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE_NEW', STORE_NAME);

define('TEXT_DESIGN_MODE_ONLY','Use Design Mode or General Admission Mode only Admin>Categories');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');
define('HEADER_TITLE_MY_ACCOUNT', 'My Account');
define('HEADER_TITLE_CART_CONTENTS', 'Cart Contents');
define('HEADER_TITLE_CHECKOUT', 'Checkout');
define('HEADER_TITLE_TOP', 'Home');
define('HEADER_TITLE_CATALOG', 'Tickets');
define('HEADER_TITLE_LOGOFF', 'Log Off');
define('HEADER_TITLE_LOGIN', 'Log In');
define('HEADER_TITLE_SEARCH', 'Search');
define('HEADER_TITLE_SEARCH_AGAIN', 'Search Again');
define('HEADER_TITLE_EVENTS', 'Events');

// footer text in includes/footer.php
define('FOOTER_MESSAGE','All rights reserved.');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Ms.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Our Shows');

define('HEADING_FEATURED_CATEGORIES_BYDATE','Calendar');
// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Choose Region');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'What\'s New');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Search');
define('BOX_SEARCH_TEXT', 'Use keywords to search.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Specials');

//Theme Selector
define('BOX_HEADING_TEMPLATE_SELECT', 'Select Template');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Ticket Order');
define('BOX_SHOPPING_CART_EMPTY', '0 Seats');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Order History');

// best_sellers box text in includes/boxes/best_sellers.php
//define('BOX_HEADING_BESTSELLERS', 'Top Sellers');
//define('BOX_HEADING_BESTSELLERS_IN', 'Top Selling <br>&nbsp;&nbsp;');

define('BOX_HEADING_FEATURED', 'Featured Product');
// manufacturer box text
// define('BOX_HEADING_MANUFACTURER_INFO', '');
// define('BOX_MANUFACTURER_INFO_HOMEPAGE', '');
// define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', '');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Languages');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Currencies');

define('BOX_HEADING_LOGIN', 'Login');


// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Information');
define('BOX_INFORMATION_PRIVACY', 'Privacy Policy');
define('BOX_INFORMATION_CONDITIONS', 'Terms and Conditions');
define('BOX_INFORMATION_SHIPPING', 'Delivery Information');
define('BOX_INFORMATION_CONTACT', 'Contact Us');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');
define('CHECKOUT_BAR_PAYMENT', 'Payment Information');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');
define('CHECKOUT_BAR_FINISHED', 'Finished!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');

define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');

define('ENTRY_COMPANY', 'Company Name:');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', 'Please select your Gender.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1990)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1990)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');

define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'State/Province:');
define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Current Password:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW', 'New Password:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('FORM_REQUIRED_INFORMATION', '* Required information');
define('ENTER_PASSWORD_CONFIRMATION','Password Confirmation is required.');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Result Pages:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> new products)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> specials)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');
define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');
define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');
define('IMAGE_BUTTON_BACK', 'Back');
define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');
define('IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('IMAGE_BUTTON_REFUND', 'Refund');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');
define('IMAGE_BUTTON_PROCESS_ORDER', 'Processing...');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'More Events');
define('IMAGE_BUTTON_DELETE', 'Delete');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');
define('IMAGE_BUTTON_HISTORY', 'Order History');
define('IMAGE_BUTTON_LOGIN', 'Sign In');
define('IMAGE_BUTTON_IN_CART', 'Add to Cart');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');
define('IMAGE_BUTTON_SEARCH', 'Search');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_REMOVE', 'Remove');
define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');

define('SMALL_IMAGE_BUTTON_INVOICE', 'Invoice');

define('ICON_ARROW_RIGHT', 'more');
define('ICON_CART', 'In Cart');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warning');

define('TEXT_GREETING_PERSONAL', 'Welcome <span class="greetUser">%s</span>');
//define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s</span>');
//define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');
//define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sort products ');
define('TEXT_DESCENDINGLY', 'descendingly');
define('TEXT_ASCENDINGLY', 'ascendingly');
define('TEXT_BY', ' by ');


define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');
define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');
define('TEXT_REQUIRED', '<span class="errorText">Required</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</strong></font>');

define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/install. Please remove this directory for security reasons.');
/* 
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');

define('WARNING_LOG_DIRECTORY_WRITEABLE','Warning: I am not able to write to the log folder: ' . STORE_PAGE_PARSE_TIME_LOG . ' Please check the attribute and make it writable.');

define('WARNING_IMAGES_DIRECTORY_WRITEABLE', 'Warning: I am not able to write to the images folder: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/images. Please check the attribute and make it writable.');
define('WARNING_IMAGES_BIG_DIRECTORY_WRITEABLE', 'Warning: I am not able to write to the images folder: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/images/big. Please check the attribute and make it writable.');
define('WARNING_IMAGES_SMALL_DIRECTORY_WRITEABLE', 'Warning: I am not able to write to the images folder: ' . dirname($FREQUEST->servervalue('SCRIPT_FILENAME')) . '/images/small. Please check the attribute and make it writable.');
define('WARNING_CURRENCY', 'Warning: Language Currency is Missing. Language Currency value - %s.');

define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . $FSESSION->save_path . '. Sessions will not work until this directory is created.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . $FSESSION->save_path . '. Sessions will not work until the right user permissions are set.');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.'); */


define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid. Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid. Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s. If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.');

/////////////////////////////////////////////////////
if(USE_CINE=='yes'){
define('STAGE', 'SCREEN');
}else{
define('STAGE', 'STAGE');	
}

define('TEXT_CART_EMPTY', 'There Are No Tickets In Your Cart!');
define('TABLE_HEADING_PRODUCTS', 'Ticket(s)');
define('TEXT_DISCOUNT_ALERT2','Don\'t forget to check for discount. Some tickets have discount options');
define('EMAIL_TEXT_PRODUCTS', 'Tickets Ordered');
define('HEADING_RESERVED', 'Ticket Order');
define('HEADING_PRODUCTS', 'Tickets');


/* IMPORTANT Seatplan Strings */

//e.g Your ITEMS have been RESERVED on our system
define('ITEM','Ticket');
define('ITEMS','Tickets');
define('SOLD_OUT', 'SOLD OUT');
define('SOLD', 'SOLD');
define('FREE','FREE');
define('RESERVED','Reserved');
define('RESTOCKED','Refunded and Re-stocked');
define('SOLD_OUT_MESSAGE', 'Sorry! these items are SOLD OUT');

//Family Ticket text for checkout success page
define('SET_OF_FAMILY', 'Set of ');//e.g Set of 4 Family Tickets

//Extra Legend Show Ticket Type - if fields are empty no text will show
//NO NEED TO TRANSLATE THIS
define('YELLOW',' ');
define('BLUE',' ');
define('GREEN',' ');
define('FUCHSIA',' ');
define('RED',' ');
define('SKYBLUE',' ');
define('PALEGREEN',' ');
define('ORANGE',' ');
define('THISTLE',' ');
define('TEAL',' ');
define('SALMON',' ');

define('REMAINING','Available');
define('SHOW_DISABLED','<h2>Sorry, the event is over.</h2>');

define('PLEASE_LOGIN', 'Please login to get tickets');
//define('PLEASE_LOGIN', 'Seats for this show are only available to book by telephone.');
//define('CLEAR_CART', 'Do You Really Want To Empty Your Cart?');
//define('CLEAR_AJAX_CART', '<span class=\'button_links\'>Clear&nbsp;Cart</span>');
define('DISCOUNT_LINK', '<div class=\'discount_link\'>Click the ticket number link below for discount options</div>');
//cartzone

define('TEXT_DISCOUNT_ALERT','Click on the ticket icon and check for discount');
//added discount message
define('DISCOUNT_MESSAGE', ' <b>PLEASE NOTE:</b> Concessions are available');
//define('DISCOUNT_MESSAGE2', '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . HEADER_VIEW_CART . '</a>');
define('DISCOUNT_MESSAGE3', 'Click on the item link below');
define('TEXT_DISCOUNT_APPLIED','<b><font class="sale_discount_applied">Discount applied</font></b>');
define('TEXT_ORIGINAL_AMOUNT','<b><font class="sale_discount_applied">Original Amount</b></font>');
define('TEXT_DISCOUNT','<b><font  class="sale_discount_applied">Discount</font></b>');
define('TEXT_DISCOUNT_ALERT3','(Discounts available)');
//define('TEXT_DISCOUNT_ALERT4','Discounts can be applied at the checkout');
define('TEXT_DISCOUNT_ALERT4','Click on the ticket link for discount options');
define('TEXT_QUANTITY', 'Quantity');
define('TEXT_TYPE', 'Ticket Type');

//for the pop up live discount
define('SP_NOTHANKYOU','No thank you');

define('WELCOME_CUSTOMER', ' <a href="%s">My Account</a>');
define('WELCOME_GUEST','Welcome <a href="%s">Guest</a>');

define('BOX_LOGINBOX_EMAIL','Username');
define('BOX_LOGINBOX_PASSWORD','Password');

//add new fields options IMPORTANT if these fields are empty...the empty fields will be omitted. (Apart from email/field_4)
define('NEW_FIELDS_HEADING','Details');
define('FIELD_1','Name');
define('FIELD_1_VALIDATE', '1');//0 = no validation
define('FIELD_2','Address');
define('FIELD_2_VALIDATE', '1');//0 = no validation
define('FIELD_3','Contact');
define('FIELD_3_VALIDATE', '1');//0 = no validation
define('FIELD_4','Email');
define('FIELD_4_VALIDATE', '1');//0 = no validation
define('FIELD_5','Other Comments');
define('PLEASE_ENTER_YOUR','Please enter your ');
define('TEXT_FIELD_REQUIRED','*');
//comments/drop down text area
//define('FIELD_5', 'Pick up Option');
define('FIELD_5_VALIDATE', '1');//0 = no validation

define('SP_EXPIRY','Your cart expires in');
define('SP_EXPIRED','Your cart has timed out.');
define('SP_EMPTIED','Your cart has been cleared.');
define('SP_EMPTY','Your cart has been cleared.<br/><br/>Please <a href=\'#\'>reload</a> the page in order to continue.');
define('SP_CLEARED','Your cart has been cleared due to inactivity.<br/><br/>Please reload the page in order to continue.');
define('SP_TOOSLOW','Sorry - someone else was faster.');

// define('SP_TICKETLIMIT','Sorry - you may only purchase '. CUSTOMER_TICKET_LIMIT .' tickets per show.');
 define('SP_TOOMANY','Sorry - you may only purchase '. MAX_IN_CART_AMOUNT .' tickets per order.');
 define('SP_DISCOUNT','Sorry - you may only purchase '. MAX_IN_CART_AMOUNT .' tickets per order, however we have applied any discounts selected.');
// define('SP_GA_TICKET_LIMIT','Sorry - you may only purchase '. CUSTOMER_TICKET_LIMIT .' tickets per show. The quantity added to your basket has been adjusted');
// define('CHECKOUT_TICKET_LIMIT','You may only purchase '. CUSTOMER_TICKET_LIMIT .' tickets per show.');
//define('TRANSACTION','Transaction is in progress');

//for prices bar /includes/modules/osconcert.php
define('TEXT_YOUR_RESERVATIONS', ' Your Reservations');
define('TEXT_FOREIGN_RESERVATIONS', ' Others Reservations');
define('TEXT_RESERVED_SEATS', ' Reserved Seats');
define('TEXT_IN_BASKET', ' In Basket');
define('TEXT_OTHER_BASKETS', ' Other Baskets');
define('TEXT_LEGEND', 'Legend');


//others if needed
define('HEADER_SEARCH','Search');
define('HEADER_ADVANCED_SEARCH','Advanced Search');
define('HEADER_SHOPPING_CART','Ticket Order');
define('HEADER_VIEW_CART','View Order');
define('HEADER_ACCOUNT','My Account');
define('HEADER_CHECKOUT','CHECKOUT Here');
define('HEADING_FEATURED_PRODUCTS','Featured Products');
define('HEADING_FEATURED_CATEGORIES','Events');
//define('HEADING_NEW_PRODUCTS','New Products');
define('HEADING_SEE_ALL','See All');
define('HEADING_FAQ','FAQ');
define('HEADING_INFORMATION','Information');
define('TEXT_LOGIN','Login');
define('TEXT_LOGOFF','Logoff');
define('TEXT_NEW_CUSTOMER','New customer?');
define('TEXT_SIGN_UP','Sign up');

//product info
define('TEXT_PRICE','Price');

// discountprice
define('PRICES_LOGGED_IN_TEXT','Log in for prices!');
//new added 14-08-17
define('ENTRY_CUSTOMER_EMAIL', 'Customer E-Mail:');
define('ENTRY_CUSTOMER_EMAIL_TEXT', '');

define('TEXT_NO_PRODUCTS','No Products Found');
define('IMAGE_BUTTON_ACCOUNT', 'Account');
define('IMAGE_BUTTON_PRINT_ORDER', 'Order printable');
define('IMAGE_PDF_INVOICE', 'Print as PDF');
define('IMAGE_BUTTON_SOLD', 'Sold Out');
define('IMAGE_WALLET_ADD_FUNDS','Add Funds');
define('IMAGE_BUTTON_RESET', 'Reset');

// Down For Maintenance
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'NOTICE: This website will be down for maintenance on: ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'NOTICE: the website is currently Down For Maintenance to the public');

//How to info below seat plan
define ('HOW_TO_RESERVE','<strong>How to reserve seats:</strong>');
define ('ONE','1. Click on the seats you require');
define ('TWO','2. Seats will be added to the cart');
define ('THREE','3. <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . HEADER_VIEW_CART . '</a> or <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING,'','SSL') . '">' . HEADER_CHECKOUT . '</a>');
define ('FOUR', '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT) . '">' . HEADER_TITLE_CREATE_ACCOUNT . '</a>');
define ('LOGIN_ENFORCED','You need to login first in order to reserve seats.');
//define ('LOGIN_ENFORCED','<span class="btn btn-primary btn-lg">You need to login first in order to reserve seats.</span>');
define('LOGIN_BOX_LOGOFF', 'Logoff');

define('BOX_TEXT_MOVE_TO_CART', 'Move to Cart');
define('BOX_TEXT_DELETE', 'Delete');

define('MAX_IN_CART_TEXT','You may only purchase a maximum of '.MAX_IN_CART_AMOUNT.' tickets per order');
define('TEXT_GA_REMAIN_LEFT', 'There are ');
define('TEXT_GA_REMAIN_RIGHT', ' tickets remaining for this event. ');
define('TEXT_GA_REMAIN_TICKETS', '');
define('TEXT_GA_REMAIN_HOWEVER', 'However you may buy the unrestricted tickets as shown ');
define('TEXT_GA_CART_HEADING', '<strong>There are limited tickets left. Please adjust your order.</strong> <br />');
//GA Master Quantity
define('TEXT_GA_CART_CENTRE', ' has only <strong>');
define('TEXT_GA_CART_RIGHT', ' tickets </strong>remaining.<br /> ');
//Discounts
define('TEXT_DISCOUNT_AVAILABLE_POPUP', 'Discounts are available for: ');
// box text in includes/boxes/wallet.php
define('BOX_WALLET_TEXT','Log in to see your wallet balance');
define('BOX_WALLET_BALANCE','Your Wallet Balance is ');
define('MY_ACCOUNT_WALLET','My Wallet');
define('MY_ACCOUNT_WALLET_UPLOADS','Upload Funds');
define('MY_ACCOUNT_WALLET_HISTORY','Wallet History');
define('MY_ACCOUNT_SHOW_ALL_UPLOADS','Show all wallet uploads');
define('TEXT_WALLET_EMPTY','Your wallet is empty');
// modified for wallet payment -start
define('TEXT_CURRENT_WALLET_BALANCE','Available balance  in your Wallet : %s');
define('TEXT_WALLET','Wallet');
define('ERROR_NO_PAYMENT_MODULE_UPLOADS','Wallet Upload Amount should not empty.');
define('TEXT_PENDING_WALLET_BALANCE','Pending');
define('ERROR_UPLOAD_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your upload.');
// modified for wallet payment -end
define('ERROR_BLOCKED_CUSTOMER',"Your Account had been blocked. Please contact the Adminstrator.");
define('ERROR_SUSPENDED_CUSTOMER',"Your Account had been suspended temporarily.Please contact the Adminstrator.");

define('TEXT_DISCOUNT_MOUSEOVER','Discounted seat, mouseover for special price');
define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');
define('TEXT_LOGIN_ERROR_USER', 'Error: No match for Username and/or Password.');
define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');

define('GA_NIL', 'There are no longer any more of these seats available');

define('ORDER_TOTAL_IS', 'Order total is:');
//qpb

define ('TEXT_QPB_HERE','here');
define ('TEXT_QPB_QUANTITY','Quantity discounts are available, click ');
define ('TEXT_QPB_INFO', ' for more information');
//define FACEBOOK SHARE BUTTON 
define ('FACEBOOK_SHARE', ' <div class="fb-share-button" data-href="' . FB_URL . '" data-layout="' . FB_DATA_LAYOUT . '" data-size="' . FB_DATA_SIZE . '" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . FB_URL .'&amp;src=sdkpreparse">Share</a></div>');

//ajax payment address
define('TABLE_HEADING_EDIT_PAYMENT_ADDRESS', 'Edit Address');
define('TEXT_CREATE_EDIT_PAYMENT_ADDRESS', 'Save changes below for the new payment address'); 
define('EDIT_ADDRESS', 'Edit Address');
define('SEARCH_ADDRESS', 'Search address');
define('NOT_FOUND', 'Not found!');

define('REFUND_RESTOCK','Refund and restock these seats?');
define('BOX_OFFICE_USER','BOX OFFICE USER');
define('BOX_OFFICE_NOTE','BOX OFFICE USER has changed address');

define('TEXT_LEGEND_REFUND', 'Box Office User: click for refund mode');
define('TEXT_LEGEND_REFUND_CANCEL', 'Box Office User: cancel refund mode');
define('BOX_OFFICE_REFUND', 'Box Office Refund');
define('TEXT_QUANTITY_REFUND', 'Number to refund');
define('REFUNDED_ORDER','refunded order');
define('REFERS','refers');
define('REFUNDED','REFUNDED');
define('BO_ORDER_NUMBER','Reservation Order');
define('BO_REFUND_TYPE_ORDER','is a refund type order - you may not make any variations.');
define('BO_SEARCH','SEARCH');
define('BO_FIND_SEATS','To find seats to refund please go to refund mode by clicking above');
define('BO_NOT_PLACED','was not placed by Box Office/not found - you may not refund');

define('SEASON_TICKET_PURCHASE',' [Season Ticket Purchase] ');
//bor
define ('HEADER_VIEW_BOR','View Reservations');
define('TEXT_LEGEND_RESERVATION', 'Box Office User: click for reservations');
define('TEXT_LEGEND_RESERVATION_CANCEL', 'Box Office User: cancel reservation mode');



define('TEXT_NO_ORDERS','No order appears to have been selected?');
define('TEXT_NO_TICKETS','No tickets appear to have been selected?');
define('TEXT_DISPARITY','Error - disparity in number of tickets.');
define('TEXT_NOT_PERMIT','Order status will not permit this action');
define('TEXT_MANUAL_RESTOCK','All tickets manually restocked by ');
define('TEXT_AUTO_RESTOCK','Order automatically restocked');
define('TEXT_ORDER_CONFIRMED','All tickets on this order confirmed. Order originated by: ');
define('ORDER_ORIGINATED_BY','Order originated by: ');
define('RESERVATIONS_CANCELLED','Reservations-cancelled::');
define('BOR_SELECT','Select');
define('BO_REFUND','Refund and restock these seats?');
define('BO_LOCATE','Locate');
define('BOR_ORDER','Order #');
define('BOR_ORDER_PIN','Order PIN');
define('BOR_EXPIRY','Expiry');
define('BOR_TICKETS','Tickets');
define('TEXT_NO_ORDER_COMMENTS','NO ORDER COMMENTS');
define('BOR_CONFIRM_RESERVATIONS','Confirm reservations on these seats?');
define('BOR_CONFIRM','Confirm');
define('BOR_RESTOCK','Restock');
define('BOR_ERROR','There has been an error.');

define('INVOICE_SHIPPING', 'Post and Packing: ');

//GDPR May 25th 2018
define('TEXT_COOKIES','We use cookies to track usage and preferences. <a href="index.php?stcPath=2">Learn more</a>');//message
define('TEXT_DISABLE_COOKIES','Disable Cookies');//declineText
define('TEXT_UNDERSTAND','I understand');//acceptText
define('ENTRY_PASSWORD_STRENGTH_ERROR', '&nbsp;<small><font color="#FF0000">Password strength is poor</font></small>');

define('TEXT_INC','Inc.');
define('TEXT_TAX_PLUS',' Tax: +');
define('TEXT_ORDER_NUMBER','Order number:');

define('TEXT_NO_FEATURED_CATEGORIES','EVENTS HAVE EXPIRED');//No Featured Categories

define('TEXT_TICKETS_REMAINING','There are tickets remaining for this event.');
define('TEXT_PLEASE_ADD_TO_CART','Please select QUANTITY and click ADD to Cart button below');
define('SEARCH_EVENTS', 'Search Events');
define('TEXT_ALL_EVENTS', 'All Events');
define('FAMILY_TICKET', 'Family Ticket');
define('FREE_CHECKOUT','Free Checkout');
define('TEXT_FROM','From ');
define('TEXT_TO',' to ');
define('TEXT_BO_BLOCKER', 'Box Office Blocker');
define('TEXT_BUTTON_NEXT', 'Next');
define('TEXT_BLOCK','Lock ALL');
define('TEXT_UNBLOCK','Unlock ALL');
define('TEXT_PRODUCT_EXPIRES', 'Product expires:');

define("TEXT_KILOGRAMS","Kilograms");
define("TEXT_OUNCES","Ounces");
define("TEXT_KG","KG");

define("TEXT_LOW_STOCK_ID","Low Stock ID");
define("TEXT_LOW_STOCK_ALERT","Low Stock Alert");

define("TEXT_SELECT_TICKETS","Select Tickets");
define("TEXT_CLOSE","Close");
define("NO_ORDER","Error No Order");
define("LEGEND_DISCOUNT_MESSAGE","Legend Message");
define('TEXT_LEGEND_DRAG', 'Box Office User: click to redesign seating');
define('TEXT_LEGEND_DRAG_CANCEL', 'Box Office User: cancel design mode');
define("DESIGN_NOTE","Desktop Screen Edit");


define('HEADING_UNDER_REVIEW','Your Account is under review.');
define('TEXT_UNDER_REVIEW','Please be patient. We need to approve new registrations and we will review your registration to purchase from this store as soon as possible. An email will be sent shortly.');

define('READ_MORE','For Full Details...Read More');

define('HEADING_CONTACT', 'Contact Us');
define('TEXT_NAME','Your Name');
define('TEXT_MESSAGE','Your Message');
define('TEXT_EMAIL','Your Email');
define('TEXT_SUCCESS', 'Your enquiry has been successfully sent to the Store Owner.');
define('EMAIL_SUBJECT', 'Enquiry from ' . STORE_NAME);
define('IMAGE_BUTTON_SEND', 'Send Message');
?>