<?php
/*
  Copyright (c) 2021 osConcert
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die(); 

define('HEADING_TITLE', 'Orders Status');

define('TABLE_HEADING_ORDERS_STATUS', 'Orders Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Orders Status:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new orders status with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order status?');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', 'New Orders Status');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', 'Edit Orders Status');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', 'Delete Orders Status');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'The default order status can not be removed. Please set another order status as default, and try again.');
define('ERROR_STATUS_USED_IN_ORDERS', 'This order status is currently used in orders.');
define('ERROR_STATUS_USED_IN_HISTORY', 'This order status is currently used in the order status history.');
define('ERR_STATUS_NAME','Orders Status Name should not be empty for all languages');
define('TEXT_HEADING_NEW_ORDERS_STATUS','New Orders Status');
define('TEXT_NO_ORDERS_STATUS','No Orders Status Found');


define('HEADING_NEW_TITLE','New Orders Status');
define('TEXT_RECORDS','orders status');
define('TEXT_LOADING_DATA','Loading data...');
define('TEXT_ORDER_STATUS_DELETE_SUCCESS','Order Status Deleted Successfully');
define('INFO_LOADING_ORDER_STATUS','Loading Order Status');
?>