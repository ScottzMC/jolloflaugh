<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Currencies');

define('TEXT_IMPORTANT','<span class="red">IMPORTANT:</span> Please return and set a DEFAULT currency. If you see only 0 (zero) at the front-end');

define('TABLE_HEADING_CURRENCY_NAME', 'Currency');
define('TABLE_HEADING_CURRENCY_CODES', 'Code');
define('TABLE_HEADING_CURRENCY_VALUE', 'Value');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_CURRENCY_TITLE', 'Title');
define('TEXT_INFO_CURRENCY_CODE', 'Code');
define('TEXT_INFO_CURRENCY_SYMBOL_LEFT', 'Symbol Left');
define('TEXT_INFO_CURRENCY_SYMBOL_RIGHT', 'Symbol Right');
define('TEXT_INFO_CURRENCY_DECIMAL_POINT', 'Decimal Point');
define('TEXT_INFO_CURRENCY_THOUSANDS_POINT', 'Thousands Point');
define('TEXT_INFO_CURRENCY_DECIMAL_PLACES', 'Decimal Places');
define('TEXT_INFO_CURRENCY_LAST_UPDATED', 'Last Updated:');
define('TEXT_INFO_CURRENCY_VALUE', 'Value');
define('TEXT_INFO_CURRENCY_EXAMPLE', 'Example Output:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new currency with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this currency?');
define('TEXT_INFO_HEADING_NEW_CURRENCY', 'New Currency');
define('TEXT_INFO_HEADING_EDIT_CURRENCY', 'Edit Currency');
define('TEXT_INFO_HEADING_DELETE_CURRENCY', 'Delete Currency');
define('TEXT_INFO_SET_AS_DEFAULT', TEXT_SET_DEFAULT . ' (requires a manual update of currency values)');
define('TEXT_INFO_CURRENCY_UPDATED', 'The exchange rate for %s (%s) was updated successfully via %s.');

define('ERROR_REMOVE_DEFAULT_CURRENCY', 'Error: The default currency can not be removed. Please set another currency as default, and try again.');


define('TEXT_CURRENCY_DELETE_SUCCESS', 'Currency Deleted Successfully...');
define('TEXT_CURRENCY_INSERT_SUCCESS', 'Currency Inserted Successfully...');

define('ERR_EMPTY_CURRENCY_NAME','* Currency Name cannot be empty');
define('ERR_EMPTY_CURRENCY_CODE','* Currency Code cannot be empty');
define('ERR_INVALID_CURRENCY_VALUE','* Currency Value must be greater than 0');
define('TEXT_CURRENCY_UPDATE_SUCCESS', 'Currency Updated Successfully...');
define('TEXT_EMPTY_CURRENCY', 'No Currency Found');
define('ERR_INVALID_CURRENCY','* Currency Value should be a Number');

define('ERROR_REMOVE_DEFAULT_CURR','The default currency cannot be removed. Please set another currency as default, and try again.');
define('INFO_LOADING_PRODUCTS','Loading...');
define('HEADING_TITLE_SEARCH','Search');
define('TEXT_RECORDS','Currencies');
define('ERROR_TITLE_EMPTY','Title Cannot be Empty');
define('ERROR_CODE_EMPTY','Code cannot be Empty');
define('ERROR_SYMBOL_LEFT','Symbol Left cannot be Empty');
define('ERROR_DECIMAL_POINT','Decimal point cannot be Empty');
define('ERROR_THOUSANDS_POINT','Thousands point cannot be Empty');
define('ERROR_DECIMAL_PLACES','Decimal places cannot be Empty');
define('ERROR_CURRENCY_VALUE','Currency Value cannot be Empty');
define('TEXT_EMPTY_GROUPS','No Currency Found');
define('ERROR_NUMERIC_VALUE','Currency Value must be an numeric');
?>
