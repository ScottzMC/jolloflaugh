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


  class ot_service_fee {
    var $title, $output;

    function __construct() {
      $this->code = 'ot_service_fee';
      $this->title = MODULE_ORDER_TOTAL_SERVICE_FEE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_SERVICE_FEE_DESCRIPTION;
       $this->enabled = ((MODULE_ORDER_TOTAL_SERVICE_FEE_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_SERVICE_FEE_SORT_ORDER;
      $this->include_shipping = MODULE_ORDER_TOTAL_SERVICE_FEE_INC_SHIPPING;
      $this->include_tax = MODULE_ORDER_TOTAL_SERVICE_FEE_INC_TAX;
      $this->calculate_tax = MODULE_ORDER_TOTAL_SERVICE_FEE_CALC_TAX;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $ot_subtotal;

      $od_amount = $this->calculate_discount($this->get_order_total());
      if ($this->calculate_tax == 'true') $tod_amount = $this->calculate_tax_effect($od_amount);

      if ($od_amount > 0) {
          
           $tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_SERVICE_FEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_SERVICE_FEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);  
          
        if (MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE == 'percentage') $title_ext = sprintf(MODULE_ORDER_TOTAL_SERVICE_FEE_PERCENTAGE_TEXT_EXTENSION ,MODULE_ORDER_TOTAL_SERVICE_FEE_RATES);
          
		if (MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE == 'flat rate') $title_ext = sprintf(MODULE_ORDER_TOTAL_SERVICE_FEE_FLAT_RATE_TEXT_EXTENSION ,($this->count_cart_contents_with_fee() .' x '.$currencies->format(MODULE_ORDER_TOTAL_SERVICE_FEE_RATES + tep_calculate_tax(MODULE_ORDER_TOTAL_SERVICE_FEE_RATES, $tax)) ));
          
        $this->deduction = $od_amount+$tod_amount;
        #################################################
        # from ot_loworderfee


          $order->info['tax'] += tep_calculate_tax($this->deduction, $tax);
          $order->info['tax_groups']["$tax_description"] += tep_calculate_tax( $this->deduction, $tax);
          $order->info['total'] += $this->deduction + tep_calculate_tax($this->deduction, $tax);
		  $this->output[] = array('title' => sprintf(MODULE_ORDER_TOTAL_SERVICE_FEE_FORMATED_TITLE, $title_ext),
                                  'text' => $currencies->format(tep_add_tax($this->deduction, $tax), true, $order->info['currency'], $order->info['currency_value']),
                                  'net_value'=> $this->deduction,
                                  'value' =>$this->deduction + tep_calculate_tax($this->deduction, $tax));
        
          ##################################################
       // $this->output[] = array('title' => sprintf(MODULE_ORDER_TOTAL_SERVICE_FEE_FORMATED_TITLE, $title_ext),
       //                         'text' => sprintf(MODULE_ORDER_TOTAL_SERVICE_FEE_FORMATED_TEXT, $currencies->format($od_amount)),
       //                         'value' => $od_amount);
       // $order->info['total'] += $this->deduction;
       // $order->info['tax'] += $tod_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) $order->info['subtotal'] += $this->deduction;
      }
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
    function calculate_discount($amount) {
      global $qty_discount, $order_total_array;

      $od_amount = 0;
      if ((MODULE_ORDER_TOTAL_SERVICE_FEE_DISABLE_WITH_COUPON == 'true') && (isset($_SESSION['cc_id']))){ return $od_amount;}
	  //2016 Box Office  exemption
	  if(MODULE_ORDER_TOTAL_SERVICE_FEE_BOX_OFFICE == 'true' && $_SESSION['customer_country_id']==999 ){return $od_amount;
	  }
	  //2016 stop using $_SESSION['cart']->count_contents()
      //$qty_discount = $this->calculate_rate($_SESSION['cart']->count_contents());
	  $qty_discount = $this->calculate_rate($this->count_cart_contents_with_fee());
	

########## 2019 #############
// percentage - skip if set to zero
    if (MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE == 'percentage' && MODULE_ORDER_TOTAL_SERVICE_FEE_RATES > 0 ) {
		 $od_amount = round((($amount*10)/10)*(MODULE_ORDER_TOTAL_SERVICE_FEE_RATES/100), 2);		
	}
// flat rate
    if (MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE == 'flat rate' && MODULE_ORDER_TOTAL_SERVICE_FEE_RATES > 0 && $qty_discount > 0) {
		  $od_amount = round((($qty_discount*10)/10), 2);		
	}
      return $od_amount;
    }
//new function 2016


function count_cart_contents_with_fee(){
   global $cart;
        $total_items = 0;
     
			$prod_array = explode(',',($cart->get_product_id_list(true)));
			
	foreach($prod_array as $key=>$value)
	{
				### $value is the product_id 
			 //get the array of categories for the product
			 $prod_path_array = explode('_', tep_get_product_path($value));
			 //get the excluded categories
			 $excl_path_array = explode(',', MODULE_ORDER_TOTAL_SERVICE_FEE_EXEMPT_CAT);
			 
		if(!array_intersect($prod_path_array,$excl_path_array))
		{
			//not in an excluded category so check for element_type = B
			
		$query=tep_db_query("SELECT product_type from " . TABLE_PRODUCTS . " where products_id=" .(int)$value);
		$result=tep_db_fetch_array($query);
		if ($result["product_type"]!="B") 
		{
		$total_items = $total_items + $cart->get_quantity($value);
		}
		
		//var_dump($result["product_type"]);
		}
	};
// echo $total_items;
  
return $total_items;
}
    function calculate_rate($order_qty) {
/*      $discount_rate = split("[:,]" , MODULE_ORDER_TOTAL_SERVICE_FEE_RATES);
      $size = sizeof($discount_rate);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($order_qty >= $discount_rate[$i]) {
          $qty_discount = $discount_rate[$i+1];
        }
      }
*/
	//exit(MODULE_ORDER_TOTAL_SERVICE_FEE_RATES);
	
	$qty_discount = MODULE_ORDER_TOTAL_SERVICE_FEE_RATES * $order_qty;
	
      return $qty_discount;
    }

    function calculate_tax_effect($od_amount) {
      global $order;

      if (MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE == 'percentage') {
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_SERVICE_FEE_STATUS'");
        $this->check = mysqli_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_SERVICE_FEE_STATUS', 'MODULE_ORDER_TOTAL_SERVICE_FEE_SORT_ORDER', 'MODULE_ORDER_TOTAL_SERVICE_FEE_DISABLE_WITH_COUPON', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATES', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_SHIPPING', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_TAX', 'MODULE_ORDER_TOTAL_SERVICE_FEE_CALC_TAX','MODULE_ORDER_TOTAL_SERVICE_FEE_BOX_OFFICE','MODULE_ORDER_TOTAL_SERVICE_FEE_EXEMPT_CAT','MODULE_ORDER_TOTAL_SERVICE_FEE_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Service Fee', 'MODULE_ORDER_TOTAL_SERVICE_FEE_STATUS', 'true', 'Do you want to enable the service fee module?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_SERVICE_FEE_SORT_ORDER', '10', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable If Coupon Used', 'MODULE_ORDER_TOTAL_SERVICE_FEE_DISABLE_WITH_COUPON', 'true', 'Do you want to disable the service fee module if a discount coupon is being used by the user?', '6', '3','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Fee Rate Type', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATE_TYPE', 'flat rate', 'Choose the type of fee - percentage or flat rate', '6', '4','tep_cfg_select_option(array(\'percentage\', \'flat rate\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Booking fee', 'MODULE_ORDER_TOTAL_SERVICE_FEE_RATES', '0.75', 'Fee per ticket', '6', '5', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_SHIPPING', 'false', 'Include Shipping in calculation', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_SERVICE_FEE_INC_TAX', 'false', 'Include Tax in calculation.', '6', '7','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Calculate Tax', 'MODULE_ORDER_TOTAL_SERVICE_FEE_CALC_TAX', 'true', 'Re-calculate Tax on fee amount.', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  //2016
	        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Exempt categories', 'MODULE_ORDER_TOTAL_SERVICE_FEE_EXEMPT_CAT', '', 'A comma delimited list of categories that you DO NOT wish the fee to be charged on e.g. 34,78,99 If you list a parent category all children will also be excluded', '6', '10', now())");
			      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Exempt Box Office sales?', 'MODULE_ORDER_TOTAL_SERVICE_FEE_BOX_OFFICE', 'true', '', '6', '20','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
        
              tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_SERVICE_FEE_TAX_CLASS', '0', 'Use the following tax class on the booking fee.', '6', '7', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>