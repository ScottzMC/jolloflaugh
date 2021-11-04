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


////
// Sets the status of a special product
  function tep_set_specials_status($specials_id, $status) {
    return tep_db_query("update " . TABLE_SPECIALS . " set status = '" . tep_db_input($status) . "', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  }

////
// Auto expire products on special
  function tep_expire_specials() {
    $specials_query = tep_db_query("select specials_id from " . TABLE_SPECIALS . " where status = '1' and now() >= expires_date and expires_date > 0");
    if (tep_db_num_rows($specials_query)) {
      while ($specials = tep_db_fetch_array($specials_query)) {
        tep_set_specials_status($specials['specials_id'], '0');
      }
    }
  }
?>