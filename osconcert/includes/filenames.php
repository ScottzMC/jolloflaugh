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

  define('FILENAME_CUSTOMER_PDF','products_ticket.php');
  define('FILENAME_HOWTO', 'howto.php');
  define('FILENAME_SEARCH_EVENTS', 'search_events.php');
  define('FILENAME_FEATURED_CATEGORIES_BYDATE', 'featured_categories_bydate.php');
  //codereadr
  //define('FILENAME_CR_INDEX', 'cr_index.php');
  // define the content used in the project
  define('CONTENT_ACCOUNT', 'account');
  define('CONTENT_ACCOUNT_EDIT', 'account_edit');
  define('CONTENT_ACCOUNT_HISTORY', 'account_history');
  define('CONTENT_ACCOUNT_HISTORY_INFO', 'account_history_info');
  define('CONTENT_ACCOUNT_NEWSLETTERS', 'account_newsletters');
  define('CONTENT_ACCOUNT_NOTIFICATIONS', 'account_notifications');
  define('CONTENT_ACCOUNT_PASSWORD', 'account_password');
  define('CONTENT_ADDRESS_BOOK', 'address_book');
  define('CONTENT_ADDRESS_BOOK_PROCESS', 'address_book_process');
  define('CONTENT_ADVANCED_SEARCH', 'advanced_search');
  define('CONTENT_ADVANCED_SEARCH_RESULT', 'advanced_search_result');
  //define('CONTENT_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products');
  define('CONTENT_CHECKOUT_CONFIRMATION', 'checkout_confirmation');
  define('CONTENT_CHECKOUT_PAYMENT', 'checkout_payment');
  define('CONTENT_CHECKOUT_PAYMENT_ADDRESS', 'checkout_payment_address');
  define('CONTENT_CHECKOUT_SHIPPING', 'checkout_shipping');
  define('CONTENT_CHECKOUT_SHIPPING_ADDRESS', 'checkout_shipping_address');
  define('CONTENT_CHECKOUT_SUCCESS', 'checkout_success');
  define('BOR_CONTENT_CHECKOUT_SUCCESS', 'bor_checkout_success');
  define('CONTENT_CONTACT_US', 'contact_us'); 
  define('CONTENT_CONDITIONS', 'conditions');
  define('CONTENT_POPUP_CONDITIONS', 'popup_conditions');
  
  define('CONTENT_COOKIE_USAGE', 'cookie_usage');
  define('CONTENT_CREATE_ACCOUNT', 'create_account');
  define('CONTENT_CREATE_ACCOUNT_SUCCESS', 'create_account_success');
  define('CONTENT_INDEX_DEFAULT', 'index_default');
  define('CONTENT_INDEX_NESTED', 'index_nested');
  define('CONTENT_INDEX_PRODUCTS', 'index_products');
  define('CONTENT_LOGIN', 'login');
  define('CONTENT_LOGOFF', 'logoff');
  define('CONTENT_NEW_PRODUCTS', 'new_products');
  define('CONTENT_PASSWORD_FORGOTTEN', 'password_forgotten');
  define('CONTENT_POPUP_IMAGE', 'popup_image');
  define('CONTENT_POPUP_SEARCH_HELP', 'popup_search_help');
  define('CONTENT_PRIVACY', 'privacy');
  define('CONTENT_PRODUCT_INFO', 'product_info');
  define('CONTENT_PRODUCT_LISTING', 'product_listing');
  define('CONTENT_PRODUCTS_NEW', 'products_new');
  define('CONTENT_SHIPPING', 'shipping');
  define('CONTENT_SHOPPING_CART', 'shopping_cart');
  define('CONTENT_SPECIALS', 'specials');
  define('CONTENT_SSL_CHECK', 'ssl_check');
  define('CONTENT_UPCOMING_PRODUCTS', 'upcoming_products');
  define('CONTENT_CHECKOUT_PROCESS', 'checkout_process');
  define('CONTENT_INFORMATION_PAGE','information');
  define('CONTENT_GV_FAQ', 'gv_faq');
  define('CONTENT_GV_REDEEM', 'gv_redeem');
  define('CONTENT_GV_SEND', 'gv_send');
  define('CONTENT_DOWN_FOR_MAINTAINANCE', 'down_for_maintenance');
  define('CONTENT_ORDERS_PRINTABLE', 'printorder');
  define('CONTENT_FEATURED', 'featured');
  define('CONTENT_FEATURED_PRODUCTS', 'featured_products');
  define('CONTENT_FEATURED_CATEGORIES', 'featured_categories');
	// define('CONTENT_FORMDATA', 'formdata');
	// define('FILENAME_FORMDATA', 'formdata.php');
	// define('FILENAME_FORMDATA', CONTENT_FORMDATA . '.php');
  define('FILENAME_FEATURED', 'featured.php');
  define('FILENAME_FEATURED_PRODUCTS', 'featured_products.php'); // This is the featured products page
  define('FILENAME_FEATURED_CATEGORIES', 'featured_categories.php');//this is the featured categories 2014
// define the filenames used in the project
  //define('FILENAME_ACCOUNT_PASSWORD_FIRST', CONTENT_ACCOUNT_PASSWORD_FIRST . '.php');
  define('FILENAME_ACCOUNT', CONTENT_ACCOUNT . '.php');
  define('FILENAME_ACCOUNT_EDIT', CONTENT_ACCOUNT_EDIT . '.php');
  define('FILENAME_ACCOUNT_HISTORY', CONTENT_ACCOUNT_HISTORY . '.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', CONTENT_ACCOUNT_HISTORY_INFO . '.php');
  define('FILENAME_ACCOUNT_NEWSLETTERS', CONTENT_ACCOUNT_NEWSLETTERS . '.php');
  define('FILENAME_ACCOUNT_NOTIFICATIONS', CONTENT_ACCOUNT_NOTIFICATIONS . '.php');
  define('FILENAME_ACCOUNT_PASSWORD', CONTENT_ACCOUNT_PASSWORD . '.php');
  define('FILENAME_DOWN_FOR_MAINTAINANCE', CONTENT_DOWN_FOR_MAINTAINANCE . '.php');
  define('FILENAME_ADDRESS_BOOK', CONTENT_ADDRESS_BOOK . '.php');
  define('FILENAME_ADDRESS_BOOK_PROCESS', CONTENT_ADDRESS_BOOK_PROCESS . '.php');
  //define('FILENAME_ADVANCED_SEARCH', CONTENT_ADVANCED_SEARCH . '.php');
  //define('FILENAME_ADVANCED_SEARCH_RESULT', CONTENT_ADVANCED_SEARCH_RESULT . '.php');
 // define('FILENAME_ALSO_PURCHASED_PRODUCTS', CONTENT_ALSO_PURCHASED_PRODUCTS . '.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', CONTENT_CHECKOUT_CONFIRMATION . '.php');
  define('FILENAME_CHECKOUT_PAYMENT', CONTENT_CHECKOUT_PAYMENT . '.php');
  define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', CONTENT_CHECKOUT_PAYMENT_ADDRESS . '.php');
  define('FILENAME_CHECKOUT_PROCESS', CONTENT_CHECKOUT_PROCESS . '.php');
  define('FILENAME_CHECKOUT_SHIPPING', CONTENT_CHECKOUT_SHIPPING . '.php');
  define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', CONTENT_CHECKOUT_SHIPPING_ADDRESS . '.php');
  define('FILENAME_CHECKOUT_SUCCESS', CONTENT_CHECKOUT_SUCCESS . '.php');
  define('FILENAME_CONTACT_US', CONTENT_CONTACT_US . '.php');
  define('FILENAME_CONDITIONS', CONTENT_CONDITIONS . '.php');
  define('FILENAME_COOKIE_USAGE', CONTENT_COOKIE_USAGE . '.php');
  define('FILENAME_CREATE_ACCOUNT', CONTENT_CREATE_ACCOUNT . '.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', CONTENT_CREATE_ACCOUNT_SUCCESS . '.php');
  define('FILENAME_DEFAULT', 'index.php');
  define('CONTENT_DOWNLOAD','download');
  define('FILENAME_DOWNLOAD', CONTENT_DOWNLOAD . '.php');
  define('FILENAME_LOGIN', CONTENT_LOGIN . '.php');
  define('FILENAME_AUTOLOGIN', 'autologin.php');
  define('FILENAME_LOGOFF', CONTENT_LOGOFF . '.php');
  define('FILENAME_NEW_PRODUCTS', CONTENT_NEW_PRODUCTS . '.php');
  define('FILENAME_PASSWORD_FORGOTTEN', CONTENT_PASSWORD_FORGOTTEN . '.php');
  define('FILENAME_POPUP_IMAGE', CONTENT_POPUP_IMAGE . '.php');
  define('FILENAME_POPUP_SEARCH_HELP', CONTENT_POPUP_SEARCH_HELP . '.php');
  define('FILENAME_PRIVACY', CONTENT_PRIVACY . '.php');
  define('FILENAME_PRODUCT_INFO', CONTENT_PRODUCT_INFO . '.php');
  define('FILENAME_PRODUCT_LISTING', CONTENT_PRODUCT_LISTING . '.php');
  define('FILENAME_REDIRECT', 'redirect.php');
  define('FILENAME_SHIPPING', CONTENT_SHIPPING . '.php');
  define('FILENAME_SHOPPING_CART', CONTENT_SHOPPING_CART . '.php');
  define('FILENAME_SPECIALS', CONTENT_SPECIALS . '.php');
  define('FILENAME_SSL_CHECK', CONTENT_SSL_CHECK . '.php');
  define('FILENAME_UPCOMING_PRODUCTS', CONTENT_UPCOMING_PRODUCTS . '.php');
  define('FILENAME_INFORMATION_PAGE', CONTENT_INFORMATION_PAGE . '.php');
  define('TABLE_SOURCES', 'sources'); //rmh referrals
  define('TABLE_SOURCES_OTHER', 'sources_other'); //rmh referrals
  define('FILENAME_PRODUCT_LISTING_COL', 'product_listing_col.php');
  define('FILENAME_GV_FAQ', CONTENT_GV_FAQ . '.php');
  define('FILENAME_DEFINE_MAINPAGE', 'mainpage.php');
  define('FILENAME_ORDERS_PRINTABLE', CONTENT_ORDERS_PRINTABLE . '.php');
  define('TEMPLATENAME_BOX', 'box.tpl.php');  
  define('TEMPLATENAME_POPUP', 'popup.tpl.php');
  define('TEMPLATENAME_STATIC', 'static.tpl.php');
  define('TEMPLATENAME_MAIN_PAGE', 'main_page.tpl.php');
  define('CONTENT_CREATE_ACCOUNT_NEW', 'create_account');
  define('FILENAME_CREATE_ACCOUNT_NEW', CONTENT_CREATE_ACCOUNT_NEW . '.php');
  define('CONTENT_ACCOUNT_EDIT_NEW', 'account_edit');
  define('FILENAME_ACCOUNT_EDIT_NEW', CONTENT_ACCOUNT_EDIT_NEW . '.php');
  define('CONTENT_ADDRESS_BOOK_PROCESS_NEW', 'address_book_process');
  define('FILENAME_ADDRESS_BOOK_PROCESS_NEW', CONTENT_ADDRESS_BOOK_PROCESS_NEW . '.php');
  //define('CONTENT_CREATE_ACCOUNT_PWA', 'create_account_new_pwa');
  //define('FILENAME_CREATE_ACCOUNT_PWA', CONTENT_CREATE_ACCOUNT . '.php');
  //for the pop up terms and conditions
  define('FILENAME_POPUP_CONDITIONS', CONTENT_POPUP_CONDITIONS . '.php');
    //added for WorldPay
  //define('FILENAME_WPCALLBACK', 'wpcallback.php');
  //define('CONTENT_WPCALLBACK','wpcallback');
    // time limited password change

	
  define('FILENAME_PASSWORD_RESET','password_reset.php');
  define('CONTENT_PASSWORD_RESET','password_reset');
	define('FILENAME_EVENTS_TICKET','products_ticket.php');
  define('CONTENT_ACCOUNT_PASSWORD_FIRST', 'account_password_first');
  define('FILENAME_ACCOUNT_PASSWORD_FIRST', CONTENT_ACCOUNT_PASSWORD_FIRST . '.php');
  define('FILENAME_EVENTS_MESSAGES_MAIL','events_messages_mail.php');
  //static
  define('CONTENT_INDEX_STATIC', 'index_static');
  define('FILENAME_INDEX_STATIC', CONTENT_INDEX_STATIC . '.php');
  define('FILENAME_CHECKOUT_PROCESS_FREE','checkout_process_free.php');
// modified for wallet payment -start  
  define('CONTENT_WALLET_CHECKOUT_PAYMENT', 'wallet_checkout_payment');
  define('CONTENT_WALLET_CHECKOUT_CONFIRMATION', 'wallet_checkout_confirmation');
  define('CONTENT_WALLET_CHECKOUT_PROCESS','wallet_checkout_process');
  define('CONTENT_WALLET_UPLOADS','wallet_uploads');
  define('CONTENT_WALLET_CHECKOUT_SUCCESS','wallet_checkout_success');
  define('FILENAME_WALLET_CHECKOUT_PAYMENT', CONTENT_WALLET_CHECKOUT_PAYMENT . '.php');
  define('FILENAME_WALLET_CHECKOUT_CONFIRMATION', CONTENT_WALLET_CHECKOUT_CONFIRMATION . '.php');
  define('FILENAME_WALLET_CHECKOUT_PROCESS', CONTENT_WALLET_CHECKOUT_PROCESS . '.php');
  define('FILENAME_WALLET_UPLOADS', CONTENT_WALLET_UPLOADS . '.php');
  define('FILENAME_WALLET_CHECKOUT_SUCCESS', CONTENT_WALLET_CHECKOUT_SUCCESS . '.php');

 define('CONTENT_CHECKOUT_SINGLE','checkout_single');
 define('FILENAME_CHECKOUT_SINGLE',CONTENT_CHECKOUT_SINGLE . '.php');
 define('CONTENT_PDF_MAIL','pdf_mail');
 define('FILENAME_PDF_MAIL',CONTENT_PDF_MAIL.'.php');
 
?>