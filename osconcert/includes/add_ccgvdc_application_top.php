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

  // define('FILENAME_GV_FAQ', 'gv_faq.php');
  // define('FILENAME_GV_REDEEM', 'gv_redeem.php');
  // define('FILENAME_GV_REDEEM_PROCESS', 'gv_redeem_process.php');
  // define('FILENAME_GV_SEND', 'gv_send.php');
  // define('FILENAME_GV_SEND_PROCESS', 'gv_send_process.php');
  // define('FILENAME_PRODUCT_LISTING_COL', 'product_listing_col.php');
  // define('FILENAME_POPUP_COUPON_HELP', 'popup_coupon_help.php');

  if (!defined('TABLE_COUPON_GV_CUSTOMER')) 
	  define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');

////
// Create a Coupon Code. length may be between 1 and 16 Characters
// $salt needs some thought.

  function create_coupon_code($salt="secret", $length = SECURITY_CODE_LENGTH) {
    $ccid = md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    srand((double)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
      $id1=substr($ccid, $random_start,$length);        
      $query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . tep_db_input($id1) . "'");    
      if (tep_db_num_rows($query) == 0) $good_result = 1;
    }
    return $id1;
  }
////
// Update the Customers GV account
  function tep_gv_account_update($customer_id, $gv_id) {
    $customer_gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . tep_db_input($customer_id) . "'");
    $coupon_gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($gv_id) . "'");
    $coupon_gv = tep_db_fetch_array($coupon_gv_query);
    if (tep_db_num_rows($customer_gv_query) > 0) {
      $customer_gv = tep_db_fetch_array($customer_gv_query);
      $new_gv_amount = $customer_gv['amount'] + $coupon_gv['coupon_amount'];
      $gv_query = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "' where customer_id = '" . tep_db_input($customer_id) . "'");
    } else {
      $gv_query = tep_db_query("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . tep_db_input($customer_id) . "', '" . tep_db_input($coupon_gv['coupon_amount']) . "')");
    }
  }
////
// Get tax rate from tax description
  function tep_get_tax_rate_from_desc($tax_desc) {
    $tax_query = tep_db_query("select tax_rate from " . TABLE_TAX_RATES . " where tax_description = '" . tep_db_input($tax_desc) . "'");
    $tax = tep_db_fetch_array($tax_query);
    return $tax['tax_rate'];
  }
?>