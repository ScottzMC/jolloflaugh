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


class percent {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function __construct() {
      global $order;

      $this->code = 'percent';
      $this->title = MODULE_SHIPPING_PERCENT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_PERCENT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_PERCENT_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_PERCENT_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_PERCENT_STATUS == 'True') ? true : false);
	  
	  tep_check_shipping_module_status($this,MODULE_SHIPPING_PERCENT_ZONE,trim(MODULE_SHIPPING_PERCENT_EXCEPT_ZONE),trim(MODULE_SHIPPING_PERCENT_EXCEPT_COUNTRY));
    }

// class methods
    function quote($method = '') {
      global $order, $cart;
	  
	  if (MODULE_SHIPPING_PERCENT_STATUS == 'True') {
        $order_total = $cart->show_total();
      }
	  if ($order_total >= MODULE_SHIPPING_PERCENT_LESS_THEN) {
      $shipping_percent = $order_total * MODULE_SHIPPING_PERCENT_RATE;
	  }
	  else {
	  $shipping_percent = MODULE_SHIPPING_PERCENT_FLAT_USE;
	  }
	  
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_PERCENT_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_PERCENT_TEXT_WAY,
                                                     'cost' => $shipping_percent)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_PERCENT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Percent Shipping', 'MODULE_SHIPPING_PERCENT_STATUS', 'True', 'Do you want to offer percent rate shipping?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage Rate', 'MODULE_SHIPPING_PERCENT_RATE', '.18', 'The Percentage Rate all .01 to .99 for all orders using this shipping method.', '6', '2', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage A Flat Rate for orders under', 'MODULE_SHIPPING_PERCENT_LESS_THEN', '34.75', 'A Flat Rate for all orders that are under the amount shown.', '6', '3', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage A Flat Rate of', 'MODULE_SHIPPING_PERCENT_FLAT_USE', '6.50', 'A Flat Rate used for all orders.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Percentage Tax Class', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '5', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Percentage Shipping Zone', 'MODULE_SHIPPING_PERCENT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '6', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Percentage Except Shipping Country', 'MODULE_SHIPPING_PERCENT_EXCEPT_COUNTRY', '', 'If countries are selected, disable this shipping method for that countries.', '6', '7', 'tep_cfg_pull_down_zone_except_countries(MODULE_SHIPPING_PERCENT_ZONE,', 'tep_get_zone_except_country', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Percentage  Except Shipping Zone', 'MODULE_SHIPPING_PERCENT_EXCEPT_ZONE', '', 'If a zone is selected, disable this shipping method for that zone.', '6', '8', 'tep_cfg_pull_down_zone_classes(','tep_get_zone_class_title', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percentage Sort Order', 'MODULE_SHIPPING_PERCENT_SORT_ORDER', '10', 'Sort order of display.', '6', '9', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_PERCENT_STATUS', 'MODULE_SHIPPING_PERCENT_RATE', 'MODULE_SHIPPING_PERCENT_LESS_THEN', 'MODULE_SHIPPING_PERCENT_FLAT_USE', 'MODULE_SHIPPING_PERCENT_TAX_CLASS', 'MODULE_SHIPPING_PERCENT_ZONE', 'MODULE_SHIPPING_PERCENT_EXCEPT_ZONE', 'MODULE_SHIPPING_PERCENT_EXCEPT_COUNTRY', 'MODULE_SHIPPING_PERCENT_SORT_ORDER');
    }
  }
?>
