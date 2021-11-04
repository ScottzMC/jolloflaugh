<?php
/*
  Copyright (c) 2021 osConcert
*/

  define('HEADING_TITLE', 'Products Low Stock <span style="font-size: 11px;">(v1.1)</span>');
  define('HEADING_TITLE_SUB', 'Product Info and quantities can be manually updated here. To view/edit a Product click the Product Name or Model, or you can update quantities below.<br />NOTE: Upcoming Products when updated to a quantity above 0 will set a green status and remove the date expected.%s');
  define('HEADING_TITLE_SUB_VIRTUAL', ' Virtual/downloadable products are not reported as low stock, unless they are upcoming.');
  define('HEADING_TITLE_CAT', 'Category: ');
  define('HEADING_TITLE_SEARCH', 'Search (all products for name or model): ');
  define('TABLE_HEADING_NUMBER', 'No.');
  define('TABLE_HEADING_CATEGORY', 'Category');
  define('TABLE_HEADING_PRODUCTS', 'Product Name');
  define('TABLE_HEADING_PRODUCTS_ID', 'Product id');
  define('TABLE_HEADING_MODEL', 'Product Model');
  define('TABLE_HEADING_VIRTUAL', 'Virtual');
  define('TABLE_HEADING_PRODUCTS_EXPECTED', 'Upcoming Products');
  define('TABLE_HEADING_QTY_LEFT', 'Qty. Available');
  define('TABLE_HEADING_STATUS', 'Status');
  define('TABLE_HEADING_QTY_UPDATE', 'Update Qty.');
  define('TABLE_HEADING_NOTIFY_CUST', 'NC*');
  define('TABLE_HEADING_NOTIFY_CUST_TITLE', 'Notify Customers subscribed to Product Notifications (see below)');
  define('TEXT_HEADING_ALL_QTY_UPDATE', 'Update quantities individually%s.');
  define('TEXT_HEADING_ALL_QTY_UPDATE_SUB', ' or use the options at the bottom of the page to update all at once');
  define('TEXT_HEADING_ALL_QTY_UPDATE2', 'Update all at once %s. Choose the quantity update method and other options as required.');
  define('TEXT_HEADING_SEARCH_RESULTS', 'Search results for "%s"');
  define('TEXT_HEADING_ALL_CATEGORIES', 'In all Categories');
  define('TEXT_HEADING_CATEGORY', 'In the "%s" Category <span style="font-weight: normal; font-size: 11px;">(including any sub-categories)</span>');
  define('TEXT_ALL_CATEGORIES', 'in all categories');
  define('TEXT_CURRENT_CATEGORY', 'in current category only');
  define('TEXT_QTY', 'Qty:');
  define('TEXT_QTY_UPDATE_ADD', 'Add this amount to current quantities');
  define('TEXT_QTY_UPDATE_TOTAL', 'Over-ride current quantities to this amount');
  define('TEXT_QTY_UPDATE_UPCOMING', 'Include Upcoming Products');
  define('TEXT_QTY_UPDATE_ALL_PRODUCTS', 'Tick box to update all Products%s(leave blank and only low stock products will be updated)');
  define('TEXT_QTY_UPDATE_LOW_STOCK_PRODUCTS', '(NOTE: Only low stock products will be updated. Click the "%s" button above to choose to update all products)');
  define('TEXT_UPDATE_ALL_COMPLETE', 'quantity update complete: %s products updated');
  define('TEXT_NOT_UPDATE_VIRTUAL', 'NOTE: Virtual/downloadable products are not updated here. Use individual update buttons if needed.');
  define('TEXT_SHOWING_ALL_PRODUCTS', 'Showing all Products');
  define('TEXT_SHOWING_LOW_STOCK_PRODUCTS', 'Showing only low stock Products');
  define('TEXT_PAGE_LIST_OPTION', 'Products per page');
  define('TEXT_LEGEND_HEADING', 'Legend:');
  define('TEXT_LEGEND_LOW_QTY', 'Low qty Products');
  define('TEXT_LEGEND_OK_QTY', 'OK qty Products');
  define('TEXT_UPDATE_ALSO_CONFIRM_MESSAGE', 'You will have to Confirm here after clicking Update All.');
  define('TEXT_UPDATE_RESULTS_MESSAGE', 'After confirming, update results will be displayed here.');
  define('TEXT_EDIT', 'Edit');
  define('TEXT_NOTIFY_CUSTOMERS', ' - If updating individual product quantities (to over 0), tick to notify Account Customers of new stock for that product. (i.e. Customers subscribed to Product Notifications.)<br />NOTE: The "tick box" to notify customers only appears where the current quantity for a product is 0 or less.');
  define('TEXT_NOTIFY_CUSTOMERS_ALL_PRODUCTS', 'Notify Account Customers subscribed to Product Notifications<br />(for products whose quantities will increase to greater than 0)');
  define('TEXT_YES', 'yes');
  define('TEXT_CUSTOMERS_NOTIFIED', '%s e-mail notifications sent to Customers');

  define('IMAGE_UPDATE_ALL', 'Update all');
  define('IMAGE_CONFIRM_UPDATE_ALL', 'Confirm update all');
  define('IMAGE_SHOW_ALL_PRODUCTS', 'Show all Products');
  define('IMAGE_SHOW_LOW_STOCK_PRODUCTS', 'Show only low stock Products');
?>
