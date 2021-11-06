<?php
/*
//Graeme Tyson, sakwoya@sakwoya.co.uk,  Feb 2012 for osConcert
//Freepayment moduel to be instigated when discount coupons equal order Total
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class free {
    var $code, $title, $description, $enabled;

// class constructor
	function __construct() {
		global $order;
		
		$this->code = 'free';
		$name = "Free Payment";
		$image = "";
		if(MODULE_PAYMENT_FREE_DISPLAY_NAME != "MODULE_PAYMENT_FREE_DISPLAY_NAME")$name = MODULE_PAYMENT_FREE_DISPLAY_NAME;
		if(MODULE_PAYMENT_FREE_IMAGE != "MODULE_PAYMENT_FREE_IMAGE")$image = MODULE_PAYMENT_FREE_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image)){
			$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . '" width="103" height="33">';
		}else{
			$image_array = array('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = "";
		}
		define('MODULE_PAYMENT_FREE_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image . "");
		define('MODULE_PAYMENT_FREE_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_FREE_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_FREE_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_FREE_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_FREE_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_FREE_STATUS == 'True') ? true : false);
		$this->barred=false;
		if ((int)MODULE_PAYMENT_FREE_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_FREE_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
	}

// class methods
    function update_status() {
      global $order;
	  
	  tep_check_module_status($this,MODULE_PAYMENT_FREE_ZONE,trim(MODULE_PAYMENT_FREE_EXCEPT_ZONE),trim(MODULE_PAYMENT_FREE_EXCEPT_COUNTRY));
	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_FREE_EXCEPT_COUNTRY));
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return false;
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_FREE_TEXT_DESCRIPTION);
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_FREE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_PAYMENT_FREE_STATUS', 'True', 'Do you want enable this module - it is designed to allow downloading of complementary tickets?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_FREE_ZONE', '2', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Countries', 'MODULE_PAYMENT_FREE_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '0', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_FREE_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Exclude these Zones', 'MODULE_PAYMENT_FREE_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '0', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of  display.', 'MODULE_PAYMENT_FREE_SORT_ORDER', '4', 'Sort order . Ignored by this module', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_FREE_ORDER_STATUS_ID', '3', 'Set the status of orders made with this payment module to this value. Delivered=allow download.', '3', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_FREE_DISPLAY_NAME', 'Free payment', 'Set the Display name to payment module', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_FREE_IMAGE', '', 'Set the Image of payment module', '6', '8', 'tep_cfg_file_field(', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_FREE_STATUS', 'MODULE_PAYMENT_FREE_ZONE', 'MODULE_PAYMENT_FREE_EXCEPT_ZONE', 'MODULE_PAYMENT_FREE_EXCEPT_COUNTRY', 'MODULE_PAYMENT_FREE_ORDER_STATUS_ID', 'MODULE_PAYMENT_FREE_SORT_ORDER','MODULE_PAYMENT_FREE_DISPLAY_NAME','MODULE_PAYMENT_FREE_IMAGE');
    }
  }
?>