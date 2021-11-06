<?php
/*
  $Id: ot_qty_discount.php,v 1.4 2004-08-22 dreamscape Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Adapted for osConcert, October 2010 Graeme Tyson, sakwoya@sakwoya.co.uk
*/

// Check to ensure this file is included in Freeway
defined('_FEXEC') or die();


  class ot_qty_discount {
    var $title, $output;

    function __construct() {
      $this->code = 'ot_qty_discount';
      $this->title = MODULE_ORDER_TOTAL_DISCOUNT_TITLE;
      $this->description = MODULE_ORDER_TOTAL_DISCOUNT_DESCRIPTION;
       $this->enabled = ((MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER;
      $this->include_shipping = MODULE_ORDER_TOTAL_DISCOUNT_INC_SHIPPING;
      $this->include_tax = MODULE_ORDER_TOTAL_DISCOUNT_INC_TAX;
	  $this->credit_class = true;
      $this->calculate_tax = MODULE_ORDER_TOTAL_DISCOUNT_CALC_TAX;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $ot_subtotal;

      $od_amount = $this->calculate_discount($this->get_order_total());
      if ($this->calculate_tax == 'true') $tod_amount = $this->calculate_tax_effect($od_amount);

      if ($od_amount > 0) {
        if (MODULE_ORDER_TOTAL_DISCOUNT_RATE_TYPE == 'percentage') $title_ext = sprintf(MODULE_ORDER_TOTAL_DISCOUNT_PERCENTAGE_TEXT_EXTENSION ,$this->calculate_rate($_SESSION['cart']->count_contents()));
        $this->deduction = $od_amount+$tod_amount;
        $this->output[] = array('title' => sprintf(MODULE_ORDER_TOTAL_DISCOUNT_FORMATED_TITLE, $title_ext),
                                'text' => sprintf(MODULE_ORDER_TOTAL_DISCOUNT_FORMATED_TEXT, $currencies->format($od_amount)),
                                'value' => $od_amount*-1);
        $order->info['total'] -= $this->deduction;
        $order->info['tax'] -= $tod_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) $order->info['subtotal'] -= $this->deduction;
      }
    }

    function calculate_discount($amount) {
      global $qty_discount, $order_total_array;

      $od_amount = 0;
      if ((MODULE_ORDER_TOTAL_DISCOUNT_DISABLE_WITH_COUPON == 'true') && (isset($_SESSION['cc_id']))) return $od_amount;

      $qty_discount = $this->calculate_rate($_SESSION['cart']->count_contents());
      if ($qty_discount > 0) {
        if (MODULE_ORDER_TOTAL_DISCOUNT_RATE_TYPE == 'percentage') {
          $od_amount = round((($amount*10)/10)*($qty_discount/100), 2);
        } else {
          $od_amount = round((($qty_discount*10)/10), 2);
        }
      }

      return $od_amount;
    }

    function collect_posts() {
	return false;
	}
	function pre_confirmation_check() {
	return false;
	}
	function update_credit_account() {
	return false;
	}
	function apply_credit() {
	return false;
	}
   
    function calculate_rate($order_qty) {
      $discount_rate = preg_split("/[:,]/" , MODULE_ORDER_TOTAL_DISCOUNT_RATES);
      $size = sizeof($discount_rate);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($order_qty >= $discount_rate[$i]) {
          $qty_discount = $discount_rate[$i+1];
        }
      }

      return $qty_discount;
    }

    function calculate_tax_effect($od_amount) {
      global $order;

      if (MODULE_ORDER_TOTAL_DISCOUNT_RATE_TYPE == 'percentage') {
        $tod_amount = 0;
        reset($order->info['tax_groups']);
		//FOREACH
        //while (list($key, $value) = each($order->info['tax_groups'])) {
		foreach($order->info['tax_groups'] as $key => $value)
		{
          $god_amount = 0;
          $tax_rate = tep_get_tax_rate($key);
          $net = ($tax_rate * $order->info['tax_groups'][$key]);
          if ($net > 0) {
            $god_amount = $this->calculate_discount($order->info['tax_groups'][$key]);
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
      } else {
        $tod_amount = 0;
        reset($order->info['tax_groups']);
        //FOREACH
        //while (list($key, $value) = each($order->info['tax_groups'])) {
		foreach($order->info['tax_groups'] as $key => $value)
		{
          $god_amount = 0;
          $tax_rate = tep_get_tax_rate($key);
          $net = ($tax_rate * $order->info['tax_groups'][$key]);
          if ($net>0) {
            $god_amount = ($tax_rate/100)*$od_amount;
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
      }

      return $tod_amount;
    }

    function get_order_total() {
      global $order;

      $order_total = $order->info['total'];
      if ($this->include_tax == 'false') $order_total = ($order_total - $order->info['tax']);
      if ($this->include_shipping == 'false') $order_total = ($order_total - $order->info['shipping_cost']);
      return $order_total;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_DISCOUNT_STATUS'");
        $this->check = mysqli_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_DISCOUNT_STATUS', 'MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER', 'MODULE_ORDER_TOTAL_DISCOUNT_DISABLE_WITH_COUPON', 'MODULE_ORDER_TOTAL_DISCOUNT_RATE_TYPE', 'MODULE_ORDER_TOTAL_DISCOUNT_RATES', 'MODULE_ORDER_TOTAL_DISCOUNT_INC_SHIPPING', 'MODULE_ORDER_TOTAL_DISCOUNT_INC_TAX', 'MODULE_ORDER_TOTAL_DISCOUNT_CALC_TAX');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Quantity Discount', 'MODULE_ORDER_TOTAL_DISCOUNT_STATUS', 'true', 'Do you want to enable the quantity discount module?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable If Coupon Used', 'MODULE_ORDER_TOTAL_DISCOUNT_DISABLE_WITH_COUPON', 'true', 'Do you want to disable the quantity discount module if a discount coupon is being used by the user?', '6', '3','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Discount Rate Type', 'MODULE_ORDER_TOTAL_DISCOUNT_RATE_TYPE', 'percentage', 'Choose the type of discount rate - percentage or flat rate', '6', '4','tep_cfg_select_option(array(\'percentage\', \'flat rate\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Discount Rates', 'MODULE_ORDER_TOTAL_DISCOUNT_RATES', '10:5,20:10', 'The discount is based on the total number of items.  Example: 10:5,20:10.. 10 or more items get a 5% or $5 discount; 20 or more items receive a 10% or $10 discount; depending on the rate type.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_ORDER_TOTAL_DISCOUNT_INC_SHIPPING', 'false', 'Include Shipping in calculation', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_DISCOUNT_INC_TAX', 'false', 'Include Tax in calculation.', '6', '7','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Calculate Tax', 'MODULE_ORDER_TOTAL_DISCOUNT_CALC_TAX', 'true', 'Re-calculate Tax on discounted amount.', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>