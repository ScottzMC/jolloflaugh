<?php
/*
  $Id: create_customer_pdf,v 1.1 2007/07/25 clefty (osc forum id chris23)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
*/
define('STORE_NAME_ADDRESS2','address');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('ENTRY_SOLD_TO', 'Invoice to:');
define('ENTRY_SHIP_TO', 'Deliver to:');
define('ENTRY_BILL_TO', 'Billed to:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');

define('PRINT_INVOICE_HEADING', 'Tax Invoice');

define('PRINT_INVOICE_TITLE', 'Invoice number: ');
define('PRINT_INVOICE_ORDERNR', 'Order number: ');
define('PRINT_INVOICE_DATE', 'Date of Order: ');

define ('PDF_META_TITLE','Your Invoice');
define ('PDF_META_SUBJECT','PDF copy of your invoice number: ');

define ('PDF_INV_QTY_CELL','Qty');
define ('PDF_INV_WEB','Web: ');
define ('PDF_INV_EMAIL',''); 
define ('PDF_INV_CUSTOMER_REF','Customer reference: ');
define ('PDF_INV_COMMENTS','Order Comments');

?>