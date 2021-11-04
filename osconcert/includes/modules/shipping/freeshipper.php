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



  class freeshipper {
    var $code, $title, $description, $icon, $enabled;

// BOF: WebMakers.com Added: Free Payments and Shipping
// class constructor
    function __construct() {
      global $order, $cart;
      $this->code = 'freeshipper';
      $this->title = MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FREESHIPPER_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_FREESHIPPER_SORT_ORDER;
    //  $this->icon = DIR_WS_ICONS . 'shipping_free_shipper.jpg';
      $this->tax_class = MODULE_SHIPPING_FREESHIPPER_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') ? true : false);

	  tep_check_shipping_module_status($this,MODULE_SHIPPING_FREESHIPPER_ZONE,trim(MODULE_SHIPPING_FREESHIPPER_EXCEPT_ZONE),trim(MODULE_SHIPPING_FREESHIPPER_EXCEPT_COUNTRY));
	  
    }

// class methods
    function quote($method = '') {
      global $order;

      // $this->quotes = array('id' => $this->code,
                            // 'module' => MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE,
                            // 'methods' => array(array('id' => $this->code,
                                                     // 'title' => '<FONT COLOR=FF0000><B>' . MODULE_SHIPPING_FREESHIPPER_TEXT_WAY . '</B></FONT>',
                                                     // 'cost' => SHIPPING_HANDLING + MODULE_SHIPPING_FREESHIPPER_COST)));
	$this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => '' . MODULE_SHIPPING_FREESHIPPER_TEXT_WAY . '',
                                                     'cost' => SHIPPING_HANDLING + MODULE_SHIPPING_FREESHIPPER_COST)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREESHIPPER_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) values ('Enable Free Shipping', 'MODULE_SHIPPING_FREESHIPPER_STATUS', 'True', 'Do you want to offer FREE shipping?', '6', '1', now(),'tep_cfg_select_option(array(\'True\', \'False\'),')");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Free Shipping Cost', 'MODULE_SHIPPING_FREESHIPPER_COST', '0.00', 'What is the Shipping cost? The Handling fee will also be added.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('FREESHIPPER Tax Class', 'MODULE_SHIPPING_FREESHIPPER_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '3', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('FREESHIPPER Shipping Zone', 'MODULE_SHIPPING_FREESHIPPER_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '4', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('FREESHIPPER Except Shipping Country', 'MODULE_SHIPPING_FREESHIPPER_EXCEPT_COUNTRY', '', 'If countries are selected, disable this shipping method for that countries.', '6', '5', 'tep_cfg_pull_down_zone_except_countries(MODULE_SHIPPING_FREESHIPPER_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('FREESHIPPER Except Shipping Zone', 'MODULE_SHIPPING_FREESHIPPER_EXCEPT_ZONE', '', 'If a zone is selected, disable this shipping method for that zone.', '6', '6', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FREESHIPPER Sort Order', 'MODULE_SHIPPING_FREESHIPPER_SORT_ORDER', '8', 'Sort order of display.', '6', '7', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_SHIPPING_FREESHIPPER_STATUS', 'MODULE_SHIPPING_FREESHIPPER_COST', 'MODULE_SHIPPING_FREESHIPPER_TAX_CLASS', 'MODULE_SHIPPING_FREESHIPPER_ZONE', 'MODULE_SHIPPING_FREESHIPPER_EXCEPT_ZONE', 'MODULE_SHIPPING_FREESHIPPER_EXCEPT_COUNTRY', 'MODULE_SHIPPING_FREESHIPPER_SORT_ORDER');
    }
  }
?>