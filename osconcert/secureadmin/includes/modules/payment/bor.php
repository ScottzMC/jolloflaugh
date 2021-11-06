<?php
/*
   

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class bor {
    var $code, $title, $description, $enabled;

// class constructor
	function __construct() {
		global $order;
		
		$this->code = 'bor';
		$name = "Box Office Reservations";
		$image = "";
		if(MODULE_PAYMENT_BOR_DISPLAY_NAME != "MODULE_PAYMENT_BOR_DISPLAY_NAME")$name = MODULE_PAYMENT_BOR_DISPLAY_NAME;
		if(MODULE_PAYMENT_BOR_IMAGE != "MODULE_PAYMENT_BOR_IMAGE")$image = MODULE_PAYMENT_BOR_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image)){
			$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . '" height="33">';
		}else{
			$image_array = array('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = $path;
		}
		define('MODULE_PAYMENT_BOR_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_BOR_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_BOR_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_BOR_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_BOR_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_BOR_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_BOR_STATUS == 'True') ? true : false);
		$this->barred=false;
		if ((int)MODULE_PAYMENT_BOR_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_BOR_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
	}

// class methods
    function update_status() {
      global $order;
	  
	  tep_check_module_status($this,MODULE_PAYMENT_BOR_ZONE,trim(MODULE_PAYMENT_BOR_EXCEPT_ZONE),trim(MODULE_PAYMENT_BOR_EXCEPT_COUNTRY));
	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_BOR_EXCEPT_COUNTRY));
	  
	  // delete old orders
	  
	    $time_now = date('Y-m-d H:i:s',getServerDate(false));
   
		  $bor_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " 
										where bor_datetime <= '" . $time_now . "' 
										AND bor_datetime > 0
										AND orders_status = '".MODULE_PAYMENT_BOR_ORDER_STATUS_ID."'");
			if (tep_db_num_rows($bor_query) > 0 ) { 
			while ($bor_results = tep_db_fetch_array($bor_query)){
				$this->canx_order($bor_results['orders_id'],TEXT_ORDER_RESTOCKED);
			}
			}
	  
	  
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
	  				'barred'=>$this->barred,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
	
	  		if(($_SESSION['customer_country_id']==999) && (DIRECT_CHECKOUT=='true')){
		
		 //tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));
		 tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS_FREE, '', 'SSL'));
			}
        return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
	  global $FSESSION;
		   if ($FSESSION->is_registered('bor_new_order')) {
	   	   $FSESSION->remove('bor_new_order');
			   }
      return false;
    }

    function after_process() {
	  global $insert_id, $FSESSION;
	 
	 $bor_datetime = date('Y-m-d H:i:s',getServerDate(false));
	 
	 // skip if we are confirming an order
	   if ($FSESSION->is_registered('bor_new_order')) {
	   	   $FSESSION->remove('bor_new_order');
			return false;
			exit();
	   }
	
	   //now let's update the orders/orders products tables
	   //but only if MODULE_PAYMENT_BOR_TIME >0 otherwise db will record
	   //value of 0000-00-00 00-00-00
	if (MODULE_PAYMENT_BOR_TIME > 0){
	   $random_id = mt_rand(100000, 999999);
	   $time = floatval(MODULE_PAYMENT_BOR_TIME);
	   $bor_interval = MODULE_PAYMENT_BOR_TIME * 24 * 60 * 60 ;
        // update the orders table
        tep_db_query("update " . TABLE_ORDERS . " set 
							   bor_random_id = '" . $random_id . "',
							   bor_expiry    ='" . $time . "',
							   bor_datetime  = '".$bor_datetime."' + INTERVAL ".$bor_interval." SECOND 
    								where
						       orders_id = '" . (int) $insert_id . "' ");
		//update the orders_products table 
		tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set 
							   bor_random_id = '" . $random_id . "',
							   bor_expiry='" . $time . "',
							   bor_datetime  = '".$bor_datetime."' + INTERVAL ".$bor_interval." SECOND 
    								where
						       orders_id = '" . (int) $insert_id . "' ");
        //update the order comments with the id
				$sql_data_array = array(
						'orders_id' => (int) $insert_id,
						'orders_status_id' => MODULE_PAYMENT_BOR_ORDER_STATUS_ID,
						'date_added' => $bor_datetime,
						'customer_notified' => 0,
						'comments' => "Order pin = $random_id"
					);
					tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	}
	  
	  
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_BOR_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
		
		## TODO - consider function to handle the next set of queries
		## duplicate columns in order & order products table mean less server load when
		## displaying seatplans on front end of the store.
		
		//check for (and add if missing) random_id column
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS. " where field = 'bor_random_id'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS. " add bor_random_id varchar (20)");
		}
		
		// now check for the order expiry
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS. " where field = 'bor_expiry'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS. " add bor_expiry decimal (10,4)");
		}
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS. " where field = 'bor_datetime'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS. " add bor_datetime datetime");
		}
		
		//check for (and add if missing) random_id column
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS_PRODUCTS. " where field = 'bor_random_id'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS. " add bor_random_id varchar (20)");
		}
		
		// now check for the order expiry
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS_PRODUCTS. " where field = 'bor_expiry'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS. " add bor_expiry decimal (10,4)");
		}
		
		$r = tep_db_num_rows(tep_db_query("SHOW columns from ".TABLE_ORDERS_PRODUCTS. " where field = 'bor_datetime'")); 
		if ($r ==0 ){
		  tep_db_query("alter table ".TABLE_ORDERS_PRODUCTS. " add bor_datetime datetime");
		}
		
		
	    //  add in a new order status 'Reserved'
        $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Reserved' limit 1");
        if (tep_db_num_rows($check_query) < 1) {
            $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status       = tep_db_fetch_array($status_query);
            $status_id    = $status['status_id'] + 1;
            $languages    = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Reserved')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check     = tep_db_fetch_array($check_query);
            $status_id = $check['orders_status_id'];
        }
		//  add in a new order status 'Cancelled::Reserved'
        $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Cancelled::Reserved' limit 1");
        if (tep_db_num_rows($check_query) < 1) {
            $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
            $status       = tep_db_fetch_array($status_query);
            $status_id1   = $status['status_id'] + 1;
            $languages    = tep_get_languages();
            foreach ($languages as $lang) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id1 . "', '" . $lang['id'] . "', 'Cancelled::Reserved')");
            } //$languages as $lang
        } //tep_db_num_rows($check_query) < 1
        else {
            $check     = tep_db_fetch_array($check_query);
            $status_id1 = $check['orders_status_id'];
        }
		
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_PAYMENT_BOR_STATUS', 'True', 'Do you want to allow Box Office Reservations', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values (' Payment Zone', 'MODULE_PAYMENT_BOR_ZONE', '4', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values (' Exclude these Countries', 'MODULE_PAYMENT_BOR_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '3', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_BOR_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values (' Exclude these Zones', 'MODULE_PAYMENT_BOR_EXCEPT_ZONE', '2', 'If a zone is selected, disable this payment method for that zone.', '6', '4', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values (' Sort order of  display.', 'MODULE_PAYMENT_BOR_SORT_ORDER', '4', 'Sort order of display. Lowest is displayed first.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values (' Set Order Status', 'MODULE_PAYMENT_BOR_ORDER_STATUS_ID', '" . $status_id . "', 'Set the initial status of orders made with this payment module to this value', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values (' Set Cancelled Order Status', 'MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID', '" . $status_id1. "', 'Set the status of  cancelled orders to this value', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values (' Set Confirmed Order Status', 'MODULE_PAYMENT_BOR_ORDER_MANUAL_STATUS_ID', '3', 'Set the status of  confirmed orders - Delivered allows downloads', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_BOR_DISPLAY_NAME', 'Box Office Reservations', 'Set the Display name to payment module', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_BOR_IMAGE', '', 'Set the Image of payment module', '6', '8', 'tep_cfg_file_field(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Box Office Reservation Time (Days)', 'MODULE_PAYMENT_BOR_TIME', '2', 'Time in days before restocking - will accept decimal values', '6', '8', now())");
   
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_BOR_STATUS', 'MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID', 'MODULE_PAYMENT_BOR_ZONE', 'MODULE_PAYMENT_BOR_EXCEPT_ZONE', 'MODULE_PAYMENT_BOR_EXCEPT_COUNTRY', 'MODULE_PAYMENT_BOR_ORDER_STATUS_ID', 'MODULE_PAYMENT_BOR_SORT_ORDER','MODULE_PAYMENT_BOR_ORDER_MANUAL_STATUS_ID','MODULE_PAYMENT_BOR_DISPLAY_NAME', 'MODULE_PAYMENT_BOR_TIME','MODULE_PAYMENT_BOR_IMAGE');
    }
	
function canx_order($order_id, $note='')
{
    global $FSESSION;

    // if we have the order_id then do stuff
    if (tep_not_null($order_id)) {
        //grab customers name from order
        $cust_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
        if (tep_db_num_rows($cust_query) ) {
            $cust_query_result = tep_db_fetch_array($cust_query);
            $cust_name = $cust_query_result['customers_name'];
        }
        //change the order status
        $sql_data_array = array('orders_status' => MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID,
        'customers_name'=> 'Reservations-cancelled::'.$cust_name)
        ;
        tep_db_perform(TABLE_ORDERS, $sql_data_array, "update", "orders_id='".$order_id."'");
        
        $sql_data_array = array('orders_id' => $order_id,
        'orders_status_id' => MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID,
        'date_added' => date('Y-m-d H:i:s',getServerDate(false)),
        'customer_notified' => 0,
        'comments' => $note );
        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        
		include_once('includes/functions/ga_tickets.php');
        
        //reset the products quantity and status
		//n.b. that in the orders_products table that the products_type field does not reflect the products_type field in
		//the products table - you need to use events_type
		
        $order_query = tep_db_query("select products_id, products_quantity, events_type from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id. "'");
        while ($order = tep_db_fetch_array($order_query)) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . ", products_status='1' where products_id = '" . (int)$order['products_id'] . "'");
			//tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status = '".MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID."' where products_id = '" . (int)$order['products_id'] . "'");
			tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set orders_products_status = '".MODULE_PAYMENT_BOR_ORDER_CANX_STATUS_ID."' where products_id = '" . (int)$order['products_id'] . "' AND orders_id = '".$order_id."'");

		if (function_exists('ga_check_process_restock')){
			ga_check_process_restock((int)$order['products_id'], $order['products_quantity'], $order['events_type']);	
			}													

        }

        //give the order total a value of 0.00
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $order_id. "'");
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_quantity = '0' where orders_id = '" . $order_id . "'");
        tep_db_query("insert into " . TABLE_ORDERS_TOTAL. " (orders_id, title, text, value, class, sort_order) values ('" . $order_id . "', 'Total', '0.00', '0','ot_total', '99')");
		
       return false;
    }
}

  }
?>