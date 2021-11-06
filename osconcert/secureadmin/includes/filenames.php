<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  

  Released under the GNU General Public License
  
  Freeway eCommerce from ZacWare
  http://www.openfreeway.org

Copyright 2007 ZacWare Pty. Ltd
*/
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

//cartzone concert update
  define('FILENAME_BACKUP_CATEGORIES', 'backup_categories.php');
  define('FILENAME_ADMIN_CLEAR_CACHE', 'clear_cache.php');
  define('FILENAME_PRODUCT_MANAGE', 'product_manage.php');

//Admin begin
  define('FILENAME_ADMIN_ACCOUNT', 'myaccount_update_account.php');
  define('FILENAME_ADMIN_FILES', 'admin_files.php');
  define('FILENAME_SHOP_ADMIN_MEMBERS', 'shop_admin_members.php');  
  define('FILENAME_FORBIDEN', 'forbiden.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_AUTOLOGIN', 'autologin.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'request_password_forgotten.php');
  define('FILENAME_DEFINE_MAINPAGE', 'cms_mainpage.php');
  define('FILENAME_EVENTS_TICKET', 'events_ticket.php');
  define('FILENAME_STATIC_PAGES', 'static_pages.php');
  define('FILENAME_DEFINE_INFORMATION_PAGE','define_information.php');
  define('FILENAME_STATIC_PAGES_UPDATE', 'static_pages_update.php');
  define('FILENAME_TEMPLATE_CONFIGURATION', 'shop_template_configuration.php');
  define('FILENAME_TEMPLATE_CONFIGURATION1', 'shop_template_configuration1.php');
  define('FILENAME_INFOBOX_CONFIGURATION', 'shop_infobox_configuration.php');
  define('FILENAME_SALEMAKER', 'sales_maker.php');
  define('FILENAME_SALEMAKER_INFO', 'products_salemaker_info.php');
  define('FILENAME_FEATURED', 'products_featured.php');
  define('FILENAME_PRODUCTS_OPTIONS','products_options.php');
  define('FILENAME_CREATE_ACCOUNT', 'customers_create.php');
  //define('FILENAME_CREATE_ACCOUNT_PROCESS', 'customers_create_account_process.php');
  //define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'customers_create_account_success.php');
  //define('FILENAME_CREATE_ORDER_PROCESS', 'customers_create_order_process.php');
  define('FILENAME_CREATE_ORDER', 'customers_order_create.php');
  define('FILENAME_EDIT_ORDERS', 'customers_edit_orders.php');
  define('FILENAME_BACKUP', 'shop_backup.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_CATEGORIES', 'products_create.php');
  define('FILENAME_COUPON_ADMIN','sales_coupon.php');
  define('FILENAME_COUPON_RESTRICT','coupon_restrict.php');
  define('FILENAME_GV_MAIL','sales_giftvoucher_mail.php');
  define('FILENAME_GV_QUEUE','sales_giftvoucher_queue.php');
  define('FILENAME_GV_SENT','sales_giftvoucher_sent.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'shop_countries.php');
  define('FILENAME_CURRENCIES', 'payment_currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DEFINE_LANGUAGE', 'shop_define_language.php');
  define('FILENAME_GEO_ZONES', 'shop_geo_zones.php');
  define('FILENAME_LANGUAGES', 'shop_languages.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'products_manufacturers.php');
  define('FILENAME_MODULES', 'modules.php');
  define('FILENAME_NEWSLETTERS', 'marketing_newsletters.php');
  define('FILENAME_ORDERS', 'customers_orders.php');
  define('FILENAME_ORDERS_INVOICE', 'customers_invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'customers_packingslip.php');
  define('FILENAME_ORDERS_STATUS', 'shop_orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REVIEWS', 'products_reviews.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'products_specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_GA_PRODUCTS_PURCHASED', 'ga_products_purchased.php');
  define('FILENAME_PRODUCTS_SALES', 'products_sales.php');
  define('FILENAME_PRODUCTS_SALES_NEW', 'products_sales_new.php');
  define('FILENAME_TAX_CLASSES', 'payment_tax_classes.php');
  define('FILENAME_TAX_RATES', 'payment_tax_rates.php');
  define('FILENAME_WHOS_ONLINE', 'shop_whos_online.php');
  define('FILENAME_ZONES', 'shop_zones.php');
  define('FILENAME_SHOP_ZONES','shop_zones.php');
  define('FILENAME_SHOP_LANGUAGES','shop_languages.php');
  define('FILENAME_PAYMENT_GENERAL_TEMPLATES','payment_general_templates.php');
  define('FILENAME_MARKETING_WALLET','marketing_wallet.php');
  define('FILENAME_MARKETING_EVENTS_MESSAGES','marketing_events_messages.php');
  define('FILENAME_CUSTOMERS_INFO_FIELDS','customers_info_fields.php');

// BOF: Order Editor
  define('FILENAME_ORDERS_EDIT', 'edit_orders.php');
  define('FILENAME_ORDERS_EDIT_ADD_PRODUCT', 'edit_orders_add_product.php');
  define('FILENAME_ORDERS_EDIT_AJAX', 'edit_orders_ajax.php');


  define('FILENAME_EVENTS_MESSAGES','marketing_events_messages.php');
  define('FILENAME_MAIL_TEMPLATES','mail_templates.php');
  define('FILENAME_EVENTS_MESSAGES_MAIL','events_messages_mail.php');
  define('FILENAME_SEND_EMAIL','send_email.php');

  define('FILENAME_EDIT_ORDERS_CONFIRM','customers_edit_orders_confirm.php');
  define('FILENAME_EDIT_ORDERS_SUCCESS','customers_edit_orders_success.php');

  define('FILENAME_EVENTS_SURVEYS','marketing_survey_events.php');
   define('FILENAME_LANGUAGE_ADMIN','language_admin.php');
  
define('FILENAME_ADMIN_GROUPS_FILE_PERMISSION','shop_admin_groups_file_permission.php');

  define('FILENAME_ACCOUNT','account.php');
  define('FILENAME_EDIT_ORDERS_PROCESS','customers_edit_orders_process.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');

  define('FILENAME_EMAIL_MESSAGES','marketing_email_messages.php');

  define('FILENAME_STATS_PRODUCTS_VIEWED', 'reports_products_viewed.php');

 
 

  
  //reports pages

  define('FILENAME_PRODUCTS_SALES','reports_sales_products.php');

 
define('FILENAME_CALL_CENTRE_ORDERS','reports_events_call_centre_orders.php');
define('FILENAME_INVENTORY','reports_products_inventory.php');

define('FILENAME_REFERRALS', 'marketing_survey_referrals.php'); //rmh referrals
define('FILENAME_STATS_REFERRAL_SOURCES', 'reports_sales_referral_sources.php'); //rmh referrals

define('FILENAME_GENERAL_TEMPLATES','payment_general_templates.php');
define('FILENAME_EVENTS_TICKET','events_ticket.php');


define('FILENAME_CUSTOMERS_GROUPS','customers_groups.php');
define('FILNAME_GROUP_DISCOUNT','events_group_discount.php');

define('FILENAME_WALLET_EMARKETING','marketing_wallet.php');
define('FILENAME_PREVIEW_TICKETS','events_preview_tickets.php');

define('FILENAME_WALLET_CONFIRMATION','customers_wallet_confirmation.php');
define('FILENAME_WALLET_PROCESS','customers_wallet_process.php');
define('FILENAME_WALLET_SUCCESS','customers_wallet_success.php');
define('FILENAME_REPORTS_WALLET','reports_wallet.php');
define('FILENAME_WALLET_PAYMENT','customers_payment_wallet.php');
define('FILENAME_CUSTOMER_OPTIONS','marketing_survey_customer_options.php');
define('FILENAME_META_TAGS','marketing_seo.php');
define('FILENAME_PRODUCTS_MESSAGE','marketing_products_message.php');

define('FILENAME_REPORT_INVOICE','reports_products_invoice.php');
define('FILENAME_REPORT_PICKPACK','reports_products_pickpack.php');
define('FILENAME_REPORT_PACKSLIP','reports_products_packslip.php');

define('FILENAME_CHECKOUT_PAYPALIPN','customers_checkout_paypalipn.php');
define('FILENAME_PAYPAL_NOTIFY','paypal_notify.php');

define('FILENAME_COMPAT_TEST','shop_compat_test.php');
define('FILENAME_INFORMATION_PAGES','cms_level_pages.php');
define('FILENAME_INFORMATION_PAGES_UPDATE','cms_level_pages_update.php');

define('FILENAME_ADDPRODUCTS','customers_order_addproducts.php');

define('FILENAME_REPORTS_DIRECT_DEPOSIT','reports_sales_direct_deposit.php');
define('FILENAME_SEARCH_LINKS','search_links.php');

define('FILENAME_REPORTS_CUSTOMER_DETAIL','reports_general_customer_detail.php');
define('FILENAME_REPORTS_GENERAL_CALL_CENTER_ORDERS','reports_general_call_center_orders.php');
define('FILENAME_SITE_SETTINGS','site_settings.php');
define('FILENAME_QUICK_LINKS','quick_links.php');
define('FILENAME_CUSTOMERS_MAINPAGE','customers_mainpage.php');
define('FILENAME_CONFIGURATION_MAINPAGE','configuration_mainpage.php');
define('FILENAME_PRODUCTS_MAINPAGE','products_mainpage.php');

define('FILENAME_CMS_HOMEPAGE','cms_homepage.php');
define('FILENAME_PRODUCTS_CREATE_AJAX','products_create_ajax.php');
define('FILENAME_REPORTS_MAINPAGE','reports_mainpage.php');
define('FILENAME_PRODUCTS_REVIEWS', 'products_reviews.php');

//for new order
define('FILENAME_CREATE_ORDER_NEW','create_order_new.php');
define('FILENAME_SHOPPING_CART_NEW','shopping_cart_new.php');
define('FILENAME_ADDPRODUCTS_NEW','addproducts_new.php');

define('FILENAME_CHECKOUT_SHIPPING','checkout_shipping.php');
define('FILENAME_CHECKOUT_SHIPPING_ADDRESS','checkout_shipping_address.php');
define('FILENAME_CHECKOUT_PAYMENT_NEW','checkout_payment_new.php');
define('FILENAME_CHECKOUT_PAYMENT_ADDRESS','checkout_payment_address.php');
define('FILENAME_CHECKOUT_CONFIRM_NEW','checkout_confirm_new.php');
define('FILENAME_CHECKOUT_PROCESS_NEW','customers_create_order_process.php');
define('FILENAME_CHECKOUT_SUCCESS','checkout_success.php');
define('FILENAME_CHECKOUT_PROCESS_FREE','checkout_process_free.php');

define('FILENAME_ORDERS_REFUND','customers_orders_refund.php');
define('FILENAME_PRODUCTS_REFUNDS','reports_refund_products.php');
define('FILENAME_CUSTOMERS_IMPORT','customers_import.php');
define('FILENAME_FILEMANAGER', 'filename.php');
define('FILENAME_SKYNET_ZONESETTINGS', 'skynet_zone_settings.php');
define('FILENAME_DISCOUNT_COUPONS','sales_discount_coupons.php');
define('FILENAME_REPORTS_SHIPPING','reports_shipping.php');
define('FILENAME_REPORTS_DISCOUNT_COUPON','reports_discount_coupon.php');
define('FILENAME_PRODUCTS_EXPORT', 'products_export.php');
define('FILENAME_PRODUCTS_IMPORT', 'products_import.php');
define('FILENAME_SHOP_SALE_ITEMS','shop_sale_items.php');


define('FILENAME_CUSTOMERS_EXTRA_INFO','customers_extra_info.php');
define('FILENAME_PDF_MAIL','pdf_mail.php');

define('FILENAME_SHOPPING_CART','shopping_cart.php');

?>
