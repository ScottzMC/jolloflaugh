<?php
/*
   

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class cod {
    var $code, $title, $description, $enabled;

// class constructor
	function __construct() {
		global $order;
		
		$this->code = 'cod';
		$name = "Cash on Delivery";
		$image = "";
		if(MODULE_PAYMENT_COD_DISPLAY_NAME != "MODULE_PAYMENT_COD_DISPLAY_NAME")$name = MODULE_PAYMENT_COD_DISPLAY_NAME;
		if(MODULE_PAYMENT_COD_IMAGE != "MODULE_PAYMENT_COD_IMAGE")$image = MODULE_PAYMENT_COD_IMAGE;
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
		define('MODULE_PAYMENT_COD_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_COD_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_COD_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_COD_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_COD_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_COD_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_COD_STATUS == 'True') ? true : false);
		$this->barred=false;
		if ((int)MODULE_PAYMENT_COD_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_COD_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
	}

// class methods
    function update_status() {
      global $order;
	  
	  tep_check_module_status($this,MODULE_PAYMENT_COD_ZONE,trim(MODULE_PAYMENT_COD_EXCEPT_ZONE),trim(MODULE_PAYMENT_COD_EXCEPT_COUNTRY));
	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_COD_EXCEPT_COUNTRY));
	  
	  ###### new code for customer groups
	  ###### if a customer gets here after initially creating an account
	  ###### there is no value for $_SESSION['customers_groups_id'] 
	  ###### they will be in the default group
	  ###### so get the group manually if it is missing
	          
	    global $FSESSION;
		
		$this->enabled=false;
		
		if (!$FSESSION->is_registered("customers_groups_id")){
			$check_customer_query = tep_db_query("select customers_groups_id from " . TABLE_CUSTOMERS . " where customers_id = '".$_SESSION['customer_id']."'");
        if (!tep_db_num_rows($check_customer_query)) { 
            	} else {
			$check_customer = tep_db_fetch_array($check_customer_query);
			$FSESSION->set('customers_groups_id',$check_customer['customers_groups_id']);
		}
		}
			$current_customer_group = $_SESSION['customers_groups_id'];
		
	  if (tep_not_null($current_customer_group) && tep_not_null(MODULE_PAYMENT_COD_GROUPS)){
		
		$groups_array = array_map('trim', explode(',', MODULE_PAYMENT_COD_GROUPS));
		
		if(in_array($current_customer_group, $groups_array)){
			$this->enabled = true;
		}
	    
	  }
	  
	  ###### end customer group code

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
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_COD_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
	  //customer groups
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Restrict to customer groups.', 'MODULE_PAYMENT_COD_GROUPS', '4', 'Comma separated list of customer group numbers.', '6', '5', now())");
	  //
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('COD Enable Cash On Delivery Module', 'MODULE_PAYMENT_COD_STATUS', 'True', 'Do you want to accept Cash On Delevery payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('COD Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '3', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('COD Exclude these Countries', 'MODULE_PAYMENT_COD_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '3', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_COD_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('COD Exclude these Zones', 'MODULE_PAYMENT_COD_EXCEPT_ZONE', '2', 'If a zone is selected, disable this payment method for that zone.', '6', '4', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('COD Sort order of  display.', 'MODULE_PAYMENT_COD_SORT_ORDER', '4', 'Sort order of COD display. Lowest is displayed first.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('COD Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '4', 'Set the status of orders made with this payment module to this value', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_COD_DISPLAY_NAME', 'Cash on Delivery', 'Set the Display name to payment module', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_COD_IMAGE', 'cod', 'Set the Image of payment module', '6', '8', 'tep_cfg_file_field(', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_COD_STATUS', 'MODULE_PAYMENT_COD_ZONE', 'MODULE_PAYMENT_COD_EXCEPT_ZONE', 'MODULE_PAYMENT_COD_EXCEPT_COUNTRY', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', 'MODULE_PAYMENT_COD_SORT_ORDER','MODULE_PAYMENT_COD_DISPLAY_NAME','MODULE_PAYMENT_COD_IMAGE', 'MODULE_PAYMENT_COD_GROUPS');
    }
  }
?>