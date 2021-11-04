<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'SaleMaker');

define('TABLE_HEADING_SALE_NAME', 'SaleName');
define('TABLE_HEADING_SALE_DEDUCTION', 'Deduction');
define('TABLE_HEADING_SALE_DATE_START', 'Startdate');
define('TABLE_HEADING_SALE_DATE_END', 'Enddate');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_SALEMAKER_NAME', 'SaleName:');
define('TEXT_SALEMAKER_DEDUCTION', 'Deduction:');
define('TEXT_SALEMAKER_DEDUCTION_TYPE', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type:&nbsp;&nbsp;');
define('TEXT_SALEMAKER_PRICERANGE_FROM', 'Products Price range:');
define('TEXT_SALEMAKER_PRICERANGE_TO', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
define('TEXT_SALEMAKER_SPECIALS_CONDITION', 'If a product is a Special:');
define('TEXT_SALEMAKER_DATE_START', 'Start Date:');
define('TEXT_SALEMAKER_DATE_END', 'End Date:');
define('TEXT_SALEMAKER_CATEGORIES', '<b>Or</b> check the categories to which this sale applies:');
//define('TEXT_SALEMAKER_POPUP', '<a href="javascript:session_win();"><span class="errorText"><b>Click here for Salemaker Usage Tips.</b></span></a>');
define('TEXT_SALEMAKER_IMMEDIATELY', 'Immediately');
define('TEXT_SALEMAKER_NEVER', 'Never');
define('TEXT_SALEMAKER_ENTIRE_CATALOG', 'Check this box if you want the sale to be applied to <b>all products</b>:');
define('TEXT_SALEMAKER_TOP', 'Entire catalog');

define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_DATE_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_STATUS_CHANGE', 'Last Status Change:');
define('TEXT_INFO_SPECIALS_CONDITION', 'Specials Condition:');
define('TEXT_INFO_DEDUCTION', 'Deduction:');
define('TEXT_INFO_PRICERANGE_FROM', 'Price range:');
define('TEXT_INFO_PRICERANGE_TO', ' to ');
define('TEXT_INFO_DATE_START', 'Starts:');
define('TEXT_INFO_DATE_END', 'Expires:');

define('SPECIALS_CONDITION_DROPDOWN_0', 'Ignore Specials Price');
define('SPECIALS_CONDITION_DROPDOWN_1', 'Ignore SaleCondition');
define('SPECIALS_CONDITION_DROPDOWN_2', 'Apply SaleDeduction to Specials Price');

define('DEDUCTION_TYPE_DROPDOWN_0', 'Deduct amount');
define('DEDUCTION_TYPE_DROPDOWN_1', 'Percent');
define('DEDUCTION_TYPE_DROPDOWN_2', 'New Price');

define('TEXT_INFO_HEADING_COPY_SALE', 'Copy Sale');
define('TEXT_INFO_COPY_INTRO', 'Enter a name for the copy of<br>&nbsp;&nbsp;"%s"');

define('TEXT_INFO_HEADING_DELETE_SALE', 'Delete Sale');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to permanently delete this sale?');
?>