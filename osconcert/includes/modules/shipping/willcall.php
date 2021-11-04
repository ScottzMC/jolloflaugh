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
  class willcall {
    var $code, $title, $description, $icon, $enabled;
// class constructor
    function __construct() {
      global $order;
      $this->code = 'willcall';
      $this->title = MODULE_SHIPPING_WILLCALL_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_WILLCALL_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_WILLCALL_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_WILLCALL_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_WILLCALL_STATUS == 'True') ? true : false);
	  
	  tep_check_shipping_module_status($this,MODULE_SHIPPING_WILLCALL_ZONE,trim(MODULE_SHIPPING_WILLCALL_EXCEPT_ZONE),trim(MODULE_SHIPPING_WILLCALL_EXCEPT_COUNTRY));
    }
// class methods
    function quote($method = '') {
      global $order;
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_WILLCALL_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_WILLCALL_TEXT_WAY,
                                                     'cost' => MODULE_SHIPPING_WILLCALL_COST)));
      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);
	  
      return $this->quotes;
    }
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_WILLCALL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Will Call', 'MODULE_SHIPPING_WILLCALL_STATUS', 'True', 'Do you want to offer Will Call?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Will Call Cost', 'MODULE_SHIPPING_WILLCALL_COST', '0.00', 'Is there a cost for orders using this method??.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Will Call Tax Class', 'MODULE_SHIPPING_WILLCALL_TAX_CLASS', '0', 'Use the following tax class on the fee.', '6', '3', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Will Call Zone', 'MODULE_SHIPPING_WILLCALL_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '4', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Will Call Except Shipping Country', 'MODULE_SHIPPING_WILLCALL_EXCEPT_COUNTRY', '', 'If countries are selected, disable this shipping method for that countries.', '6', '5', 'tep_cfg_pull_down_zone_except_countries(MODULE_SHIPPING_WILLCALL_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Will Call Except Shipping Zone', 'MODULE_SHIPPING_WILLCALL_EXCEPT_ZONE', '', 'If a zone is selected, disable this shipping method for that zone.', '6', '6', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Will Call Sort Order', 'MODULE_SHIPPING_WILLCALL_SORT_ORDER', '7', 'Sort order of display.', '6', '7', now())");
    }
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    function keys() {
      return array('MODULE_SHIPPING_WILLCALL_STATUS', 'MODULE_SHIPPING_WILLCALL_COST', 'MODULE_SHIPPING_WILLCALL_TAX_CLASS', 'MODULE_SHIPPING_WILLCALL_ZONE', 'MODULE_SHIPPING_WILLCALL_EXCEPT_ZONE', 'MODULE_SHIPPING_WILLCALL_EXCEPT_COUNTRY', 'MODULE_SHIPPING_WILLCALL_SORT_ORDER');
    }
  }
?>