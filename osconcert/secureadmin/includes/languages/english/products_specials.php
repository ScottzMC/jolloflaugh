<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Specials');

define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Products Price');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_SPECIALS_PRODUCT', 'Product:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Special Price:');
define('TEXT_SPECIALS_EXPIRES_DATE', 'Expiry Date:');
define('TEXT_SPECIALS_PRICE_TIP', '<b>Specials Notes:</b><ul><li>You can enter only a fixed price deduction</li><li>If you enter a new Price, the decimal separator must be a \'.\' (decimal-point), example: <b>49.99</b></li><li>Leave the expiry date empty for no expiration</li></ul>');

define('TEXT_SPECIALS_CUSTOMERS', 'Customer Special Price:');
define('TEXT_SPECIALS_GROUPS', 'Group Special Price:');

define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_NEW_PRICE', 'New Price:');
define('TEXT_INFO_ORIGINAL_PRICE', 'Original Price:');
define('TEXT_INFO_PERCENTAGE', 'Percentage:');
define('TEXT_INFO_EXPIRES_DATE', 'Expires At:');
define('TEXT_INFO_STATUS_CHANGE', 'Status Change:');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Delete Special');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure to delete the special products Price?');
define('TEXT_DELETE_INTRO', 'Are you sure to delete this special?');
define('TEXT_DELETE_SUCCESS','Special successfully deleted');
define('HEADING_TITLE_SEARCH','Search');
define('TEXT_NO_RECORDS','No Records Found');
define('TEXT_INSERTED_SUCCESSFULLY','Special product inserted successfully...');
define('ERR_EXPIRY_DATE_EMPTY','* Expires date should not empty.');
define('ERR_EXPIRE_DATE_LESSTHAN','* Expiry Date must be greater than current date.');

define('ERR_SPECIALS_PRICE_EMPTY','* Special Price should be greater than Zero.');
define('ERROR_NUMERIC_VALUE','* Special Price should be a numeric value');
define('HEADING_NEW_TITLE','New Setting');
define('TEXT_LOADING_DATA','Loading Data...');
define('TEXT_SPECIALS_GROUP','Special Group');
define('TEXT_SPECIALS_NOT_FOUND','No Products Special Found');
define('TEXT_SPECIAL_DELETE_SUCCESS','Product Special Deleted Successfully');
define('TEXT_SPECIAL_NOT_DELETED','Product Special can not be deleted');
define('TEXT_PRODUCTS_SPECIALS_NOT_FOUND','No Products Specials Found');
define('TEXT_RECORDS','Products Specials');
define('TEXT_PRD_DELETING','Deleting Products Specials....');
define('INFO_LOADING_PRODUCTS','Loading Data...');
define('INFO_LOADING_DATA','Loading Data...');
define('INFO_SEARCHING_DATA','Searching Data...');
define('ERR_EXPIRY_DATE','* Expires date should not be empty.');

?>