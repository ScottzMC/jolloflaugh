<?php
/*
   

	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();


  class wallet {
    var $code, $title, $description, $enabled;

// class constructor
	function __construct() {
		global $order;
		
		$this->code = 'wallet';
		$name = "Wallet Payment";
		$image = "";
		if(MODULE_PAYMENT_WALLET_DISPLAY_NAME != "MODULE_PAYMENT_WALLET_DISPLAY_NAME")$name = MODULE_PAYMENT_WALLET_DISPLAY_NAME;
		if(MODULE_PAYMENT_WALLET_IMAGE != "MODULE_PAYMENT_WALLET_IMAGE")$image = MODULE_PAYMENT_WALLET_IMAGE;
		if(DIR_WS_ADMIN != "DIR_WS_ADMIN" && DIR_WS_ADMIN != "")$path = "../";
		if($image != "" && file_exists($path . DIR_WS_IMAGES . $image)){
			$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . '" width="103" height="33">';
		}else{
			$image_array = array('.gif','.jpg','.jpeg','.png');
			$image_check = true;
			for($i=0;$i<sizeof($image_array);$i++){
				if($image_check && $image != "" && file_exists($path . DIR_WS_IMAGES . $image . $image_array[$i])){
					$image = '<img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image . $image_array[$i] . '" width="103" height="33">';
					$image_check = false;
				}
			}
			if($image_check)$image = "";
		}
		define('MODULE_PAYMENT_WALLET_TEXT_TITLE', $name . '&nbsp;&nbsp;' . $image);
		define('MODULE_PAYMENT_WALLET_TEXT_TEXT_TITLE', $name);
		$this->title = MODULE_PAYMENT_WALLET_TEXT_TITLE;
		$this->text_title = MODULE_PAYMENT_WALLET_TEXT_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_WALLET_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_WALLET_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_WALLET_STATUS == 'True') ? true : false);
		$this->barred=false;
		if ((int)MODULE_PAYMENT_WALLET_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_WALLET_ORDER_STATUS_ID;
		}
		
		if (is_object($order)) $this->update_status();
		
		$this->email_footer = MODULE_PAYMENT_WALLET_TEXT_EMAIL_FOOTER;
	}

// class methods
    function update_status() {
      global $order;
	  
  	  tep_check_module_status($this,MODULE_PAYMENT_WALLET_ZONE,trim(MODULE_PAYMENT_WALLET_EXCEPT_ZONE),trim(MODULE_PAYMENT_WALLET_EXCEPT_COUNTRY));	
  	  $this->barred=tep_check_payment_barred(trim(MODULE_PAYMENT_WALLET_EXCEPT_COUNTRY));
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
		global $order,$FSESSION;
	 
	 	// get the users wallet balance from the table
		$balance=tep_get_wallet_balance($FSESSION->customer_id);
	    if ($balance < $order->info["total"]){
	        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_WALLET_TEXT_ERROR), 'SSL'));
		}
	}

    function confirmation() {
      return array('title' => MODULE_PAYMENT_WALLET_TEXT_DESCRIPTION);
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WALLET_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Wallet Module', 'MODULE_PAYMENT_WALLET_STATUS', 'True', 'Do you want to accept Wallet payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Wallet  Sort order of display.', 'MODULE_PAYMENT_WALLET_SORT_ORDER', '15', 'Sort order of display. Lowest is displayed first.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Wallet Payment Zone', 'MODULE_PAYMENT_WALLET_ZONE', '2', 'If a zone is selected, only enable this payment method for that zone.', '6', '3', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Wallet Exclude these Countries', 'MODULE_PAYMENT_WALLET_EXCEPT_COUNTRY', '', 'If countries are selected, disable this payment method for that countries.', '6', '4', 'tep_cfg_pull_down_zone_except_countries(MODULE_PAYMENT_WALLET_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Wallet Exclude these Zones', 'MODULE_PAYMENT_WALLET_EXCEPT_ZONE', '', 'If a zone is selected, disable this payment method for that zone.', '6', '5', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Wallet Set Order Status', 'MODULE_PAYMENT_WALLET_ORDER_STATUS_ID', '1', 'Set the status of orders made with this payment module to this value', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Name', 'MODULE_PAYMENT_WALLET_DISPLAY_NAME', 'Wallet Payment', 'Set the Display name to payment module', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image', 'MODULE_PAYMENT_WALLET_IMAGE', 'wallet', 'Set the Image of payment module', '6', '8', 'tep_cfg_file_field(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_WALLET_STATUS', 'MODULE_PAYMENT_WALLET_ZONE', 'MODULE_PAYMENT_WALLET_EXCEPT_ZONE', 'MODULE_PAYMENT_WALLET_EXCEPT_COUNTRY', 'MODULE_PAYMENT_WALLET_ORDER_STATUS_ID', 'MODULE_PAYMENT_WALLET_SORT_ORDER', 'MODULE_PAYMENT_WALLET_PAYTO','MODULE_PAYMENT_WALLET_DISPLAY_NAME','MODULE_PAYMENT_WALLET_IMAGE');
    }
  }
?>
