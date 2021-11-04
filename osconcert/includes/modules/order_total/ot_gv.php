<?php
/*
  $Id: ot_gv.php,v 1.37.3 2004/01/01 12:52:59 Strider Exp $
  $Id: ot_gv.php,v 1.4.2.12 2003/05/14 22:52:59 wilt Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/

// Check to ensure this file is included in Freeway!
defined('_FEXEC') or die();
  class ot_gv {
    var $title, $output;
    function __construct() {
      $this->code = 'ot_gv';
      $this->title = MODULE_ORDER_TOTAL_GV_TITLE;
      $this->header = MODULE_ORDER_TOTAL_GV_HEADER;
      $this->description = MODULE_ORDER_TOTAL_GV_DESCRIPTION;
      $this->user_prompt = MODULE_ORDER_TOTAL_GV_USER_PROMPT;
      $this->enabled = MODULE_ORDER_TOTAL_GV_STATUS;
      $this->sort_order = MODULE_ORDER_TOTAL_GV_SORT_ORDER;
      $this->include_shipping = MODULE_ORDER_TOTAL_GV_INC_SHIPPING;
      $this->include_tax = MODULE_ORDER_TOTAL_GV_INC_TAX;
      $this->calculate_tax = MODULE_ORDER_TOTAL_GV_CALC_TAX;
      $this->credit_tax = MODULE_ORDER_TOTAL_GV_CREDIT_TAX;
      $this->tax_class  = MODULE_ORDER_TOTAL_GV_TAX_CLASS;
      $this->show_redeem_box = MODULE_ORDER_TOTAL_GV_REDEEM_BOX;
      $this->credit_class = true;
      $this->checkbox = $this->user_prompt . '<input type="checkbox" onclick="submitFunction()" name="' . 'c' . $this->code . '">';
      $this->output = array();
    }
    function process() {
      global $order, $currencies, $cot_gv, $FSESSION, $_SESSION;
	 
	  {
         $od_amount = $this->calculate_credit($_SESSION['cot_season'],$_SESSION['customer_id']);
		
		
        $this->deduction = $od_amount;

        $order->info['total'] = $order->info['total'] - $od_amount;
        if ($od_amount > 0) 
		{
          $this->output[] = array('title' => $this->title . ':',
                                  'text' =>  $currencies->format($od_amount) . '',
                                  'value' => $od_amount*-1);
        }else{
				$_SESSION['cot_season'] = 0;
		}
      }
	  

    }

    function selection_test() {
      global $FSESSION;
      if ($this->user_has_gv_account($FSESSION->get("customer_id"))) {
        return true;
      } else {
        return false;
      }
    }
  function pre_confirmation_check($order_total) {
    global $FSESSION, $FREQUEST, $order;
	
	//kill sessions
		$FSESSION->remove('cot_season');
	//redo
	if($FREQUEST->postvalue('gv_redeem_amount') && $FREQUEST->postvalue('123') == '0' && $FREQUEST->postvalue('gv_redeem_amount') > '0'  && MODULE_ORDER_TOTAL_GV_STATUS == 'true'){
	   //set a session to the requested number of season tickets - may not be true - so we will check it later in the calculate_credit() function
	   $FSESSION->set('cot_season', $FREQUEST->postvalue('gv_redeem_amount'));
	   // calculate how much!
	 $deduction = $this->calculate_credit($_SESSION['cot_season'],$_SESSION['customer_id']);
	 return $deduction;
	}

  }

    function use_credit_amount() {
    global $cot_gv;
//      $_SESSION['cot_gv'] = false;     // old code - Strider
      $cot_gv = false;
      if ($this->selection_test()) {
        $output_string .=  '<td align="right" class="main">';
        $output_string .= '<b>' . $this->checkbox . '</b>' . '</td>' . "\n";
      }
      return $output_string;
    }
    function update_credit_account($i) {
      global $order, $customer_id, $insert_id, $REMOTE_ADDR;
      if (preg_match('/^GIFT/', addslashes($order->products[$i]['model']))) {
        $gv_order_amount = ($order->products[$i]['final_price'] * $order->products[$i]['qty']);
        if ($this->credit_tax=='true') $gv_order_amount = $gv_order_amount * (100 + $order->products[$i]['tax']) / 100;
		// $gv_order_amount += 0.001;
        $gv_order_amount = $gv_order_amount * 100 / 100;
        if (MODULE_ORDER_TOTAL_GV_QUEUE == 'false') 
		{
			// GV_QUEUE is true so release amount to account immediately
			$gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . (int)$customer_id . "'");
			$customer_gv = false;
			$total_gv_amount = 0;
			if ($gv_result = tep_db_fetch_array($gv_query)) {
			$total_gv_amount = $gv_result['amount'];
			$customer_gv = true;
			}
			$total_gv_amount = $total_gv_amount + $gv_order_amount;
			if ($customer_gv) 
			{
			$gv_update=tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $total_gv_amount . "' where customer_id = '" . (int)$customer_id . "'");
			} else 
			{
			$gv_insert=tep_db_query("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . $customer_id . "', '" . $total_gv_amount . "')");
			}
        } else 
		{
         // GV_QUEUE is true - so queue the gv for release by store owner
          $gv_insert=tep_db_query("insert into " . TABLE_COUPON_GV_QUEUE . " (customer_id, order_id, amount, date_created, ipaddr) values ('" . $customer_id . "', '" . $insert_id . "', '" . $gv_order_amount . "', NOW(), '" . $REMOTE_ADDR . "')");
        }
      }
    }
	  function credit_selection() { return false;}
	
    function season_credit_selection() {
	
      global $FSESSION, $currencies, $language, $cart;
	  
	  //kill sessions
		$FSESSION->remove('cot_season');
		
      $season_selection_string = '';
	  
	  $gv_amount_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" .(int)$FSESSION->customer_id . "'");		
//exit("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" .(int)$FSESSION->customer_id . "'");
$get_result=tep_db_fetch_array($gv_amount_query);


if($get_result['amount'] > 0 && MODULE_ORDER_TOTAL_GV_STATUS == 'true')
		 {
	 
	 // display ?
	 $display_choices = "none";
	 $box_is_checked = 0;
	 
	 if (MODULE_ORDER_TOTAL_GV_CHECKBOX == "Checked"){
		 $display_choices = '';
		 $box_is_checked = 1;
	 }
	 	//new code to trap season tickets in cart
	if ($cart->check_for_season_tickets() || $cart -> check_for_vouchers()){
	
	    $season_selection_string .= '<tr id="xxxxx">' . "\n";
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '  <td class="main">' . "\n";
        $image_submit = '';
		
		 $season_selection_string .= TEXT_GV_NOT_ALLOWED.'<br></td>';
        $season_selection_string .= ' <td align="right">' . $image_submit . '</td>';
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '</tr>' . "\n";
	
	}else{
	
        $season_selection_string .= '<tr id="xxxxx" style = "display:'.$display_choices.'">' . "\n";
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '  <td class="main">' . "\n";
        $image_submit = '';
		//new code 2020
        // (1) determine the default number of season tickets in the selection box
		if (MODULE_ORDER_TOTAL_GV_DEFAULT == 'Max available'){
			$cart_amount = $use_amount = $cart->count_contents();
			$season_amount =  $get_result['amount'];
			if ($season_amount < $cart_amount){
				$use_amount = $season_amount;
			}
		}else{
			$use_amount = MODULE_ORDER_TOTAL_GV_DEFAULT;
			$cart_amount =  $cart->count_contents();
			$season_amount =  $get_result['amount'];
		}
		// (2) display the selection box		
		$season_selection_string .= sprintf(TEXT_GV_CART_QUANTITY ,$cart_amount).'<br>' ."\n";
		$season_selection_string .= sprintf(TEXT_GV_SEASON_QUANTITY , $get_result['amount']).'<br>'  ."\n";		
        $season_selection_string .= TEXT_ENTER_GV_CODE .'<input type="text" class="form-control" style="width:90px;" id="gv_redeem_amount" name="gv_redeem_amount" value="' . $use_amount . '" size="2"></td>';
		$season_selection_string .= ' <td align="right">' . $image_submit . '</td>';
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '</tr>' . "\n";
		
		//(2a) cancellation checkbox
		$season_selection_string .= '<tr id="reset_page2" style = "display:none">' . "\n";
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '  <td class="main">' . "\n";
        $image_submit = '';
		
		 $season_selection_string .= '<b><span id="click_text3">'. TEXT_SEASON_CANCEL . '</span></b>&nbsp;&nbsp;&nbsp;
			'.tep_draw_checkbox_field("coupon3", "0",0,"onclick=javascript:startOver()").' <br>		 
		 </td>';
        $season_selection_string .= ' <td align="right">' . $image_submit . '</td>';
        $season_selection_string .= '  <td width="10"></td>';
        $season_selection_string .= '</tr>' . "\n";
		

		//(3) javascript to alert customer to season ticket use
		$season_selection_string .= "
				<script>
				window.onload=function() {
				isChecked = ".$box_is_checked.";
				document.getElementById('123').onclick = function() {
					// access properties using this keyword
					if ( this.checked ) {
						isChecked = 1;
					} else {
						isChecked = 0;
					}
					};
					document.getElementById('checkout_payment').onsubmit=function() {
					
					var couponIsChecked = 0;
					var redeemAmount = parseInt(document.getElementById('gv_redeem_amount').value);
					var cartAmount = parseInt(".$cart_amount.");
					var availAmount = parseInt(".$season_amount.");
					var checkWarnings = '';
					if(document.getElementById('valid_code_0')){couponIsChecked = 1;}
					
					//alert ((isChecked + '   ' +couponIsChecked +'   '+cartAmount +'  '+ redeemAmount +'  '+ availAmount +'  '+  redeemAmount));
					if (couponIsChecked == 0 ){
					
					if (isChecked == 0 ){
						//you have unused/are trying to use tickets but not selected box
						checkWarnings = '". TEXT_GV_POPUP_NOTUSE ."';
					}
					if ((cartAmount - redeemAmount > 0 ) && (availAmount > redeemAmount)){
						//you have unused season tickets
						checkWarnings = '". TEXT_GV_POPUP_NOTUSE_MAX ."';
					}
					
					
	
					}

				  
				  if ( checkWarnings !== ''){
					  if (confirm(checkWarnings + '". TEXT_GV_POPUP_CONTINUE ."')) {
						  // continue checkout
						} else {
						  // stay on the page
						  return false;
						}
				  }
				  }
				}
				</script>";
		
		
         }
      }
    return $season_selection_string;
    }
    function apply_credit() {
      global $order, $customer_id, $coupon_no, $cot_gv, $FSESSION, $insert_id;
      if ($FSESSION->is_registered("cot_season")){
        $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $FSESSION->get("customer_id") . "'");
        $gv_result = tep_db_fetch_array($gv_query);
        $gv_payment_amount = $FSESSION->get("cot_season");
        $gv_amount = $gv_result['amount'] - $gv_payment_amount;
        $gv_update = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $gv_amount . "' where customer_id = '" . $FSESSION->get("customer_id") . "'");
		
     tep_db_query("insert into " . TABLE_COUPON_SEASON_TRACK . " (quantity, customer_id, order_id) values ('" . tep_db_input($gv_payment_amount) . "',  '" . tep_db_input($FSESSION->customer_id) . "', '" . tep_db_input($insert_id) . "')");
   
		//$FSESSION->remove('cot_season'); // moved to checkout_process
      }
      return $gv_payment_amount;
    }
    function collect_posts() {
	return false;
	}
   
    function calculate_credit($amount, $my_id) {
      global $customer_id, $order, $_SESSION, $FSESSION;

//+++++++++++++++++++++++++++++++++ 
//creates an array from order products but breaks multiple quantities
//into single products
//then sorts by final price ascending n.b. that includes any tax
$season_choices = array();
for ($i = 0, $n = count($order->products); $i < $n; $i++) {
    for ($x = 0, $y = $order->products[$i]['qty']; $x < $y; $x++) {
        $new_item         = array(
            'qty' => 1,
            'model' => $order->products[$i]['model'],
            'name' => $order->products[$i]['name'],
            'price' => $order->products[$i]['final_price'],
            'final_price' => tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']),
            'tax_rate' => $order->products[$i]['tax']
        );
		if ($order->products[$i]['support_packs_type'] != 'X'){
       		 $season_choices[] = $new_item;}
    }
}



// sort by price asc
//uasort($season_choices, 'compare_price');

$discount        = 0;
// make sure there is still season tickets available - customer may attempt multiple checkouts simultaneously so we need to think about that
// the cot_season session holds the customers request so compare that with the database
$gv_amount_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $my_id ."'");
$get_result      = tep_db_fetch_array($gv_amount_query);

if ($get_result['amount'] < $FSESSION->get("cot_season")){
//reset the session value to that found in the database - not the number requested
// otherwise let the session value stand
  $FSESSION->set('cot_season', $get_result['amount']);
}





if ($FSESSION->get("cot_season") > 0) {
    for ($i = 0, $n = $FSESSION->get("cot_season"); $i < $n; $i++) {
        $discount = $discount + $season_choices[$i]['final_price'];
    }
}
	  

      return tep_round($discount,2);
    }
	
	function compare_price($a, $b)
{
    return strnatcmp($a['final_price'], $b['final_price']);
}
    function calculate_tax_deduction($amount, $od_amount, $method) {
      global $order;
      switch ($method) {
        case 'Standard':
        $ratio1 = tep_round($od_amount / $amount,2);
        $tod_amount = 0;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $tax_rate = tep_get_tax_rate_from_desc($key);
          $total_net += $tax_rate * $order->info['tax_groups'][$key];
        }
        if ($od_amount > $total_net) $od_amount = $total_net;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $tax_rate = tep_get_tax_rate_from_desc($key);
          $net = $tax_rate * $order->info['tax_groups'][$key];
          if ($net > 0) {
            $god_amount = $order->info['tax_groups'][$key] * $ratio1;
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
        $order->info['tax'] -= $tod_amount;
        $order->info['total'] -= $tod_amount;
        break;
        case 'Credit Note':
          $tax_rate = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_desc = tep_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tod_amount = $this->deduction / (100 + $tax_rate)* $tax_rate;
          $order->info['tax_groups'][$tax_desc] -= $tod_amount;
//          $order->info['total'] -= $tod_amount;   //// ????? Strider
        break;
        default:
      }
      return $tod_amount;
    }
    function user_has_gv_account($c_id) {
      $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $c_id . "'");
      if ($gv_result = tep_db_fetch_array($gv_query)) {
        if ($gv_result['amount']>0) {
          return true;
        }
      }
      return false;
    }
    function get_order_total() {
      global $order;
      $order_total = $order->info['total'];
      if ($this->include_tax == 'false') $order_total = $order_total - $order->info['tax'];
      if ($this->include_shipping == 'false') $order_total = $order_total - $order->info['shipping_cost'];
      return $order_total;
    }
    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_GV_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }
    function keys() {
      return array('MODULE_ORDER_TOTAL_GV_STATUS', 
	               'MODULE_ORDER_TOTAL_GV_SORT_ORDER',
				   'MODULE_ORDER_TOTAL_GV_CHECKBOX',
				   'MODULE_ORDER_TOTAL_GV_DEFAULT');
    }
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_GV_STATUS', 'true', 'Do you want to allow season ticket purchases?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default purchase amount', 'MODULE_ORDER_TOTAL_GV_DEFAULT', '0', 'Set default number of season tickets to use', '6', '5','tep_cfg_select_option(array(\'0\', \'1\', \'Max available\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use tickets checkbox', 'MODULE_ORDER_TOTAL_GV_CHECKBOX', 'Checked', 'Auto select season ticket checkbox', '6', '5','tep_cfg_select_option(array(\'Checked\', \'Unchecked\'), ', now())");
	  
	  
	  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_GV_SORT_ORDER', '5', 'Sort order of display.', '6', '12', now())");

    }
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
