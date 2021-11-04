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


  function tep_update_whos_online($title='') {
    global $FSESSION, $categories;
	


    if ($FSESSION->is_registered('customer_id')) {
      $wo_customer_id = $FSESSION->customer_id;

      $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = $FSESSION->ID;
    $wo_ip_address = getenv('REMOTE_ADDR');
	
#####################################################
#    include page title
#####################################################
	if ($title==''){
	
    $wo_last_page_url = getenv('REQUEST_URI'); 
	$find_pos = strpos($wo_last_page_url,'?');
	if(tep_not_null($find_pos)){
		$wo_last_page_url = substr($wo_last_page_url,0,$find_pos);
	}
	if(strpos($wo_last_page_url, 'seatplan_ajax') == true){
		return false;
	}
	}else{
		$wo_last_page_url = $title;
	}
#################### end #############################
    
    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

    //$stored_customer_query = tep_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . tep_db_input($wo_session_id) . "'");
	$stored_customer_query = tep_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . tep_db_input($wo_session_id) . "' and ip_address = '".$wo_ip_address."'");
    $stored_customer = tep_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
      tep_db_query("update " . TABLE_WHOS_ONLINE . " set  time_last_click = '" . tep_db_input($current_time) . "', last_page_url = '" . tep_db_input($wo_last_page_url) . "', customer_id = '" . (int)$wo_customer_id . "', full_name = '" . tep_db_input($wo_full_name) . "' where session_id = '" . tep_db_input($wo_session_id) . "'");
    } else {
      tep_db_query("insert into " . TABLE_WHOS_ONLINE . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . (int)$wo_customer_id . "', '" . tep_db_input($wo_full_name) . "', '" . tep_db_input($wo_session_id) . "', '" . tep_db_input($wo_ip_address) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($wo_last_page_url) . "')");
    }
  }
?>
