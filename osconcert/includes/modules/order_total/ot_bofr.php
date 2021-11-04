<?php
/*
  $Id: ot_bofr.php,
  
  box office manual discounr

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Adapted for osConcert, October 2010 Graeme Tyson, sakwoya@sakwoya.co.uk
*/

// Check to ensure this file is included in Freeway
defined('_FEXEC') or die();


  class ot_bofr {
    var $title, $output;

   function __construct() {
    	global  $FSESSION;
      $this->code = 'ot_bofr';
	  	      if ($FSESSION->is_registered("bofr_title")){
		      $this->title  = $_SESSION['bofr_title'];
			      }else{
              $this->title = MODULE_ORDER_TOTAL_BOFR_TITLE;}
       $this->description = MODULE_ORDER_TOTAL_BOFR_DESCRIPTION;
       $this->enabled = ((MODULE_ORDER_TOTAL_BOFR_STATUS == 'true') ? true : false);
       $this->sort_order = MODULE_ORDER_TOTAL_BOFR_SORT_ORDER;
	   $this->credit_class = true;
    //  $this->include_shipping = MODULE_ORDER_TOTAL_BOFR_INC_SHIPPING;
      $this->include_tax = MODULE_ORDER_TOTAL_BOFR_INC_TAX;
      $this->calculate_tax = MODULE_ORDER_TOTAL_BOFR_CALC_TAX;//recalculate tax
      $this->output = array();
    }

    function process() {
      global $FREQUEST, $order, $currencies, $ot_subtotal;
	      global $FSESSION;
	      if ($FSESSION->is_registered("bofr")){
          $od_amount =  $_SESSION['bofr'];
		  $tod_amount = 0;
     if ($this->calculate_tax == 'true') {$tod_amount = $this->calculate_tax_effect($od_amount);}

      if ($od_amount > 0) {

        $this->deduction = $od_amount+$tod_amount;
        $this->output[] = array('title' => $this->title.':',
                                'text' => '-'.$currencies->format($od_amount),
                                'value' => $od_amount*-1);
        $order->info['total'] -= $this->deduction;
        $order->info['tax'] -= $tod_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) {$order->info['subtotal'] -= $this->deduction;}
      }
	  }
    }

	
	// check that the bofr amount < order total and return the discount
	function pre_confirmation_check($order_total) {
		 
    global $FSESSION, $FREQUEST, $order, $payment;
	
	//kill bofr sessions
		$FSESSION->remove('bofr');
		$FSESSION->remove('bofr_title');
		if(is_numeric($FREQUEST->postvalue('bofr'))){
     	//redo the session
	if($FREQUEST->postvalue('bofr') && is_numeric($FREQUEST->postvalue('bofr')) && $FREQUEST->postvalue('bofr') > 0  && MODULE_ORDER_TOTAL_BOFR_STATUS == 'true' && $_SESSION['customer_country_id']==999){//box office only
	   $FSESSION->set('bofr', $FREQUEST->postvalue('bofr'));
	   //setup a title session
	   if(tep_not_null($FREQUEST->postvalue('bofr_title'))){
	       $FSESSION->set('bofr_title', $FREQUEST->postvalue('bofr_title'));
	   }
	   }
	   }
	   elseif ($FREQUEST->postvalue('bofr') == ''){}
	   
	   else{
	       $error = MODULE_ORDER_TOTAL_BOFR_ERROR_NUMERIC.$FREQUEST->postvalue('bofr');
	   	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'bofr=' .$error. '&bofr_error='.$error, 'SSL'));	
	   }
	
	// check the amount
	if($FREQUEST->postvalue('bofr') > $order_total){
	   $error = MODULE_ORDER_TOTAL_BOFR_ERROR.$FREQUEST->postvalue('bofr');
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'bofr=' .$error. '&bofr_error='.$error, 'SSL'));	
	}
	 
	 return $deduction;
	}

  
	
	
	
	function collect_posts() {
		return false;
	}
	function update_credit_account($i) {
		return false;
	}
	function collect_posts_forajax() {
		return false;
	}
	 function apply_credit() {
	      global $FSESSION;
	      if ($FSESSION->is_registered("bofr")){
		      $result = $_SESSION['bofr'];
			  $FSESSION->remove('bofr');
			  $FSESSION->remove('bofr_title');
              return $result;
			   }else{
			  return false;
			  }
    }
    function calculate_tax_effect($od_amount) { //$od_amount == discount amount
      global $order;
      {
        $tod_amount = 0;// total tax
        reset($order->info['tax_groups']);
		//exit(var_dump($order->info['tax_groups']));
		//2017 this function does not return the tax as tep_get_tax_rate is for a product!
		//FOREACH
        //while (list($key, $value) = each($order->info['tax_groups'])) {
		foreach($order->info['tax_groups'] as $key => $value)
		{
		//calculate the % deduction on the order total
	      $percent = $od_amount/$order->info['total'];
          $god_amount = 0;// current tax group total
          //$tax_rate = tep_get_tax_rate($key); //does not work
         // $net = ($tax_rate * $order->info['tax_groups'][$key]);
          if ($percent > 0) {
            $god_amount = ($percent)*$value;
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
      }

      return $tod_amount;
    }



    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_BOFR_STATUS'");
        $this->check = mysqli_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_BOFR_STATUS', 'MODULE_ORDER_TOTAL_BOFR_SORT_ORDER',  'MODULE_ORDER_TOTAL_BOFR_INC_TAX', 'MODULE_ORDER_TOTAL_BOFR_CALC_TAX');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Box Office Manual Discount', 'MODULE_ORDER_TOTAL_BOFR_STATUS', 'true', 'Do you want to enable the Box Office discount module?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_BOFR_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");



      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_ORDER_TOTAL_BOFR_INC_SHIPPING', 'false', 'Include Shipping in calculation', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_BOFR_INC_TAX', 'false', 'Include Tax in calculation.', '6', '7','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Calculate Tax', 'MODULE_ORDER_TOTAL_BOFR_CALC_TAX', 'true', 'Re-calculate Tax on discounted amount.', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>