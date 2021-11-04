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


  class ot_coupon {
    var $title, $output;
    function __construct() {
      $this->code = 'ot_coupon';
      $this->header = MODULE_ORDER_TOTAL_COUPON_HEADER;
      $this->title = MODULE_ORDER_TOTAL_COUPON_TITLE;
      $this->description = MODULE_ORDER_TOTAL_COUPON_DESCRIPTION;
      $this->user_prompt = '';
      $this->enabled =MODULE_ORDER_TOTAL_COUPON_STATUS ;
      $this->sort_order = MODULE_ORDER_TOTAL_COUPON_SORT_ORDER;
      $this->include_shipping = MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING;
      $this->include_tax = MODULE_ORDER_TOTAL_COUPON_INC_TAX;
      $this->calculate_tax = MODULE_ORDER_TOTAL_COUPON_CALC_TAX;
      $this->tax_class  = MODULE_ORDER_TOTAL_COUPON_TAX_CLASS;
      $this->credit_class = true;
      $this->output = array();
	  $this->expire_date="";
	  $this->uses_per_user="";
    }

  function process() {
    global $PHP_SELF, $order, $currencies,$FSESSION;
     $order_total=$this->get_order_total(true); 
     $od_amount = $this->calculate_credit($order_total);
	 $this->deduction = $od_amount;
	 	 		 
	 if (strtolower($this->calculate_tax) != 'none') 
		 $tod_amount = $this->calculate_tax_deduction($order_total, $this->deduction, $this->calculate_tax);
	 // if($this->deduction>$order_total)
	 // $this->deduction=$order_total;
 if($_SESSION['customer_country_id']==999)
 {

	if ($this->deduction > 0) {
      $order->info['total'] = $order->info['total'] - $this->deduction;
	  if($order->info['total']<0) $order->info['total']=0;
	  if($FSESSION->is_registered('email_redeem_code'))
	  	  
	  $email_coupon=MODULE_ORDER_TOTAL_EMAIL_COUPON_TITLE . ':' .  $FSESSION->email_redeem_code. ':';
	  $bo='Discount Applied';
       $this->output[] = array('title' => $bo .':' . $email_coupon,
                     'text' => '<b>' . $currencies->format($od_amount) . '</b>',
                     'value' =>$this->deduction * -1);
					 
    }
  }else
  {
	if ($this->deduction > 0) 
	{
      $order->info['total'] = $order->info['total'] - $this->deduction;
	  if($order->info['total']<0) $order->info['total']=0;
	  if($FSESSION->is_registered('email_redeem_code'))
	  	$email_coupon=MODULE_ORDER_TOTAL_EMAIL_COUPON_TITLE . ':' .  $FSESSION->email_redeem_code. ':';
       $this->output[] = array('title' => $this->title . ':' . $this->coupon_code .':' . $email_coupon,
                     'text' => '<b>' . $currencies->format($od_amount) . '</b>',
                     'value' =>$this->deduction * -1);
					 
    }  
	  
  }
  }

  function selection_test() {
    return false;
  }


  function pre_confirmation_check($order_total) {
    global $FSESSION;
    return $this->calculate_credit($order_total);
    }

  function use_credit_amount() {
    return $output_string;
  }

  function credit_selection() 
  {
      global $FSESSION, $order, $currencies;
        $selection_string = '<table border="0">';
		
		//// text entry fields
		// this code now passes data through to the coupon_code_ajax.php file which will render a visual 
		// display to the customer
		// it does not actually do anything other than that - the actual coupons are processed on the checkout_confirmation page
        $_SESSION['discount'] = 0;
		$_SESSION['coupon_codes'] = array();
		if($FSESSION->is_registered('email_redeem_code'))
 		{
			$FSESSION->remove('email_redeem_code');
		}
		
		for ($xi = 1; $xi <= MAX_MULTI_COUPONS; $xi++) 
		{
		//hide all but the first row
		if($xi == 1){ $display =  '';
		}
		else
					{$display = 'style = "display:none"';
					}
		//get next field number
				$next_field_number = $xi;
				$row_number = $xi - 1; //use this to build array
		        
			
				$selection_string .= '<tr id="show_r'.$row_number.'" '.$display.'>' . "\n";
				$selection_string .= '  <td class="main">' . "\n";

				
				$selection_string .= '  <span id="enter_field_'.$row_number.'">'.TEXT_ENTER_COUPON_CODE . tep_draw_input_field('gv_redeem_code['.$row_number.']','','id="gv_redeem_code'.$row_number.'"');
				
				$selection_string .= '<button style="margin-top:5px" class="btn btn-primary" type="button" onclick="showHint(document.getElementById(\'gv_redeem_code'.$row_number.'\').value, \'result_gv_redeem_code'.$row_number.'\',\'enter_field_'.$row_number.'\',\'tick_r'.$next_field_number.'\',\'gv_redeem_code'.$row_number.'\'); return false;">'.TEXT_COUPON_VALIDATE.'</button></span>';
				
				//$selection_string .= '<button style="margin-top:5px" type="button" onclick="showHint(document.getElementById(\'gv_redeem_code'.$row_number.'\').value, \'result_gv_redeem_code'.$row_number.'\',\'enter_field_'.$row_number.'\',\'tick_r'.$next_field_number.'\',\'gv_redeem_code'.$row_number.'\'); return false;">'.TEXT_COUPON_VALIDATE.'</button></span>';
				
				$selection_string .= '&nbsp;<span id = "result_gv_redeem_code'.$row_number.'"></span>';
				
				
				$selection_string .= '<br><span style="display:none" id = "tick_r'.$next_field_number.'" ><b>'.TEXT_REDEEM_ANOTHER_VOUCHER.'</b>&nbsp;<span>';
				$selection_string .=   tep_draw_checkbox_field($xi, '0',$show_coupon,'onclick=javascript:show_field("'.$next_field_number.'")');
				
				
				$selection_string .= '</td>';
				$selection_string .= '  <td align="right"></td>';
				$selection_string .= '  <td width="10"></td>';
				$selection_string .= '</tr>' . "\n";
		}//end individual lines

     $selection_string .= '</table>';
        return $selection_string;
    }


  function collect_posts() {
  
  // not used
  return false;
    global $FREQUEST, $FSESSION, $currencies, $cc_id,$order;
    if ($FREQUEST->postvalue('coupon')!='' && $FREQUEST->postvalue('gv_redeem_code')!='') {
// get some info from the coupon table
		$email_coupon=false;
      $coupon_query=tep_db_query("select coupon_id,coupon_code, coupon_amount, coupon_type, coupon_minimum_order,coupon_flag,
                                       uses_per_coupon, uses_per_user, restrict_to_products,
                                       restrict_to_categories from " . TABLE_COUPONS . "
                                       where coupon_code='". tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."'
                                       and coupon_active='Y'");
    	

		 if (tep_db_num_rows($coupon_query)>0 )  {
			$now_date = getServerDate();
			$date_query=tep_db_query("select coupon_start_date from " . TABLE_COUPONS . "
									where coupon_start_date <= '" . $now_date . "' and
									coupon_code='".tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."'");
	
			if (tep_db_num_rows($date_query)==0) {
			  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_STARTDATE_COUPON), 'SSL'));
			}
			$date_query=tep_db_query("select coupon_expire_date,uses_per_user from " . TABLE_COUPONS . "
									where coupon_expire_date >= '" . $now_date . "' and
									coupon_code='".tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."'");
			
			if (tep_db_num_rows($date_query)==0) {
			  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_FINISDATE_COUPON), 'SSL'));
			}else if(tep_db_num_rows($date_query)){
				$date_res=tep_db_fetch_array($date_query);
				$this->expire_date=format_date(date('Y-m-d',strtotime($date_res['coupon_expire_date'])));
				
			}
     	 } else {
	  		 $coupon_query=tep_db_query("select c.coupon_id, ce.discount_coupon_code as coupon_code,ce.amount as coupon_amount, c.coupon_type, c.coupon_minimum_order, c.coupon_flag ,
                                       c.uses_per_coupon, c.uses_per_user, c.restrict_to_products,
                                       c.restrict_to_categories from " . TABLE_COUPONS . " c , " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
                                       where ce.discount_coupon_code='".tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."' and ce.coupon_id=c.coupon_id  and ce.customer_id='" . tep_db_input($FSESSION->customer_id) . "' 
                                       and c.coupon_active='Y'");
			 if (tep_db_num_rows($coupon_query)>0 )  
			 {	
				$now_date = getServerDate();
				$date_query=tep_db_query("select c.coupon_start_date from " . TABLE_COUPONS . " c , " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
										where c.coupon_start_date <= '" . $now_date . "' and c.coupon_id=ce.coupon_id  and  ce.customer_id='" . tep_db_input($FSESSION->customer_id) . "' and 
										ce.discount_coupon_code='".tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."'");
		
				if (tep_db_num_rows($date_query)==0) {
				  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_STARTDATE_COUPON), 'SSL'));
				}
				$date_query=tep_db_query("select c.coupon_expire_date,c.uses_per_user from " . TABLE_COUPONS . " c , " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce 
										where c.coupon_expire_date >= '" . $now_date . "' and c.coupon_id=ce.coupon_id  and ce.customer_id='" . tep_db_input($FSESSION->customer_id) ."'  and 
										ce.discount_coupon_code='".tep_db_input($FREQUEST->postvalue('gv_redeem_code'))."'");
				
				if (tep_db_num_rows($date_query)==0) {
				  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_FINISDATE_COUPON), 'SSL'));
				} else if(tep_db_num_rows($date_query)){
					$date_res=tep_db_fetch_array($date_query);
					$this->expire_date=format_date(date('Y-m-d',strtotime($date_res['coupon_expire_date'])));
				}
				$email_coupon=true;
			 } else  
			 {
				  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_INVALID_REDEEM_COUPON), 'SSL'));
			 }
	 	}
		
		$coupon_result=tep_db_fetch_array($coupon_query);
		$coupon_count = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . "
                                          where coupon_id = '" . tep_db_input($coupon_result['coupon_id'])."'");
        $coupon_count_customer = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . "
                                                   where coupon_id = '" . $coupon_result['coupon_id']."' and
                                                   customer_id = '" . (int)$FSESSION->customer_id . "'");
			
        if (tep_db_num_rows($coupon_count)>=$coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0 ) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_USES_COUPON . $coupon_result['uses_per_coupon'] . TIMES ), 'SSL'));
        }
		 
        if (tep_db_num_rows($coupon_count_customer)>=$coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0 && $coupon_result['coupon_flag']=='U') {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_USES_USER_COUPON . $coupon_result['uses_per_user'] . TIMES ), 'SSL'));
        }
        if ($coupon_result['coupon_type']=='S') {
          $coupon_amount = $order->info['shipping_cost'];
        } else {
          $coupon_amount = $currencies->format($coupon_result['coupon_amount']) . ' ';
        }
        if ($coupon_result['type']=='P') $coupon_amount = $coupon_result['coupon_amount'] . '% ';
        if ($coupon_result['coupon_minimum_order']>0) $coupon_amount .= TEXT_ON_ORDERS .  $coupon_result['coupon_minimum_order'];
//        if (!$FSESSION->is_registered('cc_id')) tep_session_register('cc_id');

        $_SESSION['cc_id'] = $coupon_result['coupon_id'];	 
		if($email_coupon) { 
			if($FSESSION->is_registered('email_redeem_code'))
				$FSESSION->remove('email_redeem_code');
				$FSESSION->set('email_redeem_code' ,$coupon_result['coupon_code']);
		}	else $FSESSION->remove('email_redeem_code');
      if ($FREQUEST->postvalue('submit_redeem_coupon_x') && !$FREQUEST->postvalue('gv_redeem_code')) tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_REDEEM_CODE), 'SSL'));
    }
	else if($FREQUEST->postvalue('coupon')!='' && implode($FREQUEST->postvalue('gv_redeem_code'))=="") {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_INVALID_REDEEM_COUPON), 'SSL'));
	}	
	else{
		$FSESSION->remove('cc_id');
	} 

	if($order->info['subtotal']<$coupon_result['coupon_minimum_order'] ){
		  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_LOW_ORDER_TOTAL), 'SSL'));
	}
	
	if($coupon_result['coupon_amount']>$order->info['subtotal']){ 
		  if(!$FSESSION->is_registered('coupon_exist')){
		  $FSESSION->set('coupon_exist',1);
		  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_LESSTHAN_COUPON_PRICE), 'SSL'));
		}  
	}

	
  } 
  
//this needs to be redone so that it can handle the SESSION
function calculate_credit($amount) {
	global $FSESSION, $order, $cc_id;
	//$cc_id = $_SESSION['cc_id'];
	$i = 0;
	$plus = '';
	$discount = 0;
	$this->coupon_code = '';
	foreach($_SESSION['coupon_codes'] as $key => $value){

    if ($i > 0){$plus = ' + ';}
    $cc_id = $value;
	//if (!$cc_id) return $discount;
	if(!$FSESSION->email_redeem_code) {
		$coupon_query = tep_db_query("select coupon_amount ,coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($value) . "'");
	$coupon_result = tep_db_fetch_array($coupon_query);
	//comment out below to hide coupon code from checkout process
	$this->coupon_code = $this->coupon_code . $plus. $coupon_result['coupon_code'];
	} else	 {
		$coupon_query = tep_db_query("select c.coupon_code as code,ce.amount as coupon_amount,ce.discount_coupon_code as coupon_code from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DISCOUNT_EMAIL . " ce where ce.coupon_id=c.coupon_id and ce.coupon_id = '" . tep_db_input($value)  .  "' and ce.customer_id='" . tep_db_input($FSESSION->customer_id) . "' and ce.discount_coupon_code='" . tep_db_input($FSESSION->email_redeem_code) . "'");
		$coupon_result = tep_db_fetch_array($coupon_query);
		$this->coupon_code = $this->coupon_code . $plus. $coupon_result['code'];
	}	

	if (tep_db_num_rows($coupon_query)<=0) { break;//return $discount;
	}
	
	//$coupon_result = tep_db_fetch_array($coupon_query);
	//$this->coupon_code = $coupon_result['coupon_code'];
	//print_r($coupon_result);
	$coupon_get = tep_db_query("select coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type from " . TABLE_COUPONS ." where coupon_code = '". tep_db_input($coupon_result['coupon_code']) . "'");
	$get_result = tep_db_fetch_array($coupon_get);
		
	//$c_deduct = $get_result['coupon_amount'];
	$c_deduct = $coupon_result['coupon_amount'];
	
	// if coupon type S convert so that shipping amount is subtracted	
	if ($get_result['coupon_type']=='S') $c_deduct = $order->info['shipping_cost'];
	if ($get_result['coupon_type']=='P') $c_deduct = substr($coupon_result['coupon_amount'],0,-1);
	//if ($get_result['coupon_type']=='P') $c_deduct = substr($get_result['coupon_amount'],0,-1);
	if($c_deduct=='')
	{
	 $error=ERROR_INVALID_FREE_SHIPPING_COUPON; break;
	}

	// check if order_total > coupon minimum order
	if ($get_result['coupon_minimum_order'] > $this->get_order_total(true)){break;}// return $discount;
	
	if ($get_result['coupon_type']!='P'){
		$discount += $c_deduct;
	} else {
		$discount += $amount*$c_deduct/100;
	}
	if($error) $FSESSION->set('coupon_error',$error);
	$i++;
	}//exit();
	return $discount;
  }

  function calculate_tax_deduction($amount, $od_amount, $method) {
    global $FSESSION, $order, $cc_id, $cart;
    $cc_id = $_SESSION['cc_id'];
	if(!$FSESSION->email_redeem_code)
   		$coupon_query = tep_db_query("select coupon_amount,coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . tep_db_input($cc_id) . "'");
	 else 
	 	$coupon_query = tep_db_query("select amount as coupon_amount,discount_coupon_code as coupon_code from " . TABLE_COUPONS_DISCOUNT_EMAIL . " where coupon_id = '" . tep_db_input($cc_id) .  "' and customer_id='" . tep_db_input($FSESSION->customer_id) . "' and discount_coupon_code='" . tep_db_input($FSESSION->email_redeem_code) . "'");
		
    if (tep_db_num_rows($coupon_query) !=0 ) {
      $coupon_result = tep_db_fetch_array($coupon_query);
      $coupon_get = tep_db_query("select coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type from " . TABLE_COUPONS . " where coupon_code = '". tep_db_input($coupon_result['coupon_code']) . "'");
	  $get_result = tep_db_fetch_array($coupon_get);
      if ($get_result['coupon_type'] != 'S') {
      if ($get_result['restrict_to_products'] || $get_result['restrict_to_categories']) {
        // What to do here.
        // Loop through all products and build a list of all product_ids, price, tax class
        // at the same time create total net amount.
        // then
        // for percentage discounts. simply reduce tax group per product by discount percentage
        // or
        // for fixed payment amount
        // calculate ratio based on total net
        // for each product reduce tax group per product by ratio amount.
        $products = $cart->get_products();
        for ($i=0; $i<sizeof($products); $i++) {
          $t_prid = tep_get_prid($products[$i]['id']);
          $cc_query = tep_db_query("select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . tep_db_input($t_prid) . "'");
          $cc_result = tep_db_fetch_array($cc_query);
          $valid_product = false;
          if ($get_result['restrict_to_products']) {
            $pr_ids = preg_split("/[,]/", $get_result['restrict_to_products']);
            for ($p = 0; $p < sizeof($pr_ids); $p++) {
              if ($pr_ids[$p] == $t_prid) $valid_product = true;
            }
          }
          if ($get_result['restrict_to_categories']) {
            $cat_ids = preg_split("/[,]/", $get_result['restrict_to_categories']);
            for ($c = 0; $c < sizeof($cat_ids); $c++) {
              $cat_query = tep_db_query("select products_id from products_to_categories where products_id = '" . tep_db_input($products_id) . "' and categories_id = '" . tep_db_input($cat_ids[$i]) . "'");
              if (tep_db_num_rows($cat_query) !=0 ) $valid_product = true;
            }
          }
          if ($valid_product) {
            $valid_array[] = array('product_id' => $t_prid,
                                 'products_price' => $products[$i]['final_price'] * $products[$i]['quantity'],
                                 'products_tax_class' => $cc_result['products_tax_class_id']);
            $total_price += $products[$i]['final_price'] * $products[$i]['quantity'];
          }
        }
        if ($valid_product) {
        if ($get_result['coupon_type'] == 'P') {
        //  $ratio = $get_result['coupon_amount']/100;
		  $ratio = $coupon_result['coupon_amount']/100;
        } else {
          $ratio = $od_amount / $total_price;
        }
        if ($get_result['coupon_type'] == 'S') $ratio = 1;
          if ($method=='Credit Note') {
            $tax_rate = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tax_desc = tep_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            if ($get_result['coupon_type'] == 'P') {
              $tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
            } else {
              $tod_amount = $order->info['tax_groups'][$tax_desc] * $od_amount/100;
            }
            $order->info['tax_groups'][$tax_desc] -= $tod_amount;
            $order->info['total'] -= $tod_amount;
          } else {
            for ($p=0; $p<sizeof($valid_array); $p++) {
              $tax_rate = tep_get_tax_rate($valid_array[$p]['products_tax_class'], $order->delivery['country']['id'], $order->delivery['zone_id']);
              $tax_desc = tep_get_tax_description($valid_array[$p]['products_tax_class'], $order->delivery['country']['id'], $order->delivery['zone_id']);
              if ($tax_rate > 0) {
                $tod_amount[$tax_desc] += ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
                $order->info['tax_groups'][$tax_desc] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
                $order->info['total'] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
              }
            }
          }
        }
      } else {
        if ($get_result['coupon_type'] =='F') {
          $tod_amount = 0;
          if ($method=='Credit Note') {
            $tax_rate = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tax_desc = tep_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
            $order->info['tax_groups'][$tax_desc] -= $tod_amount;
          } else {
		  	if($amount>0)
	            $ratio1 = $od_amount/$amount;
			if(isset($order->info['tax_groups'])) {
				reset($order->info['tax_groups']);
				//FOREACH
        //while (list($key, $value) = each($order->info['tax_groups'])) {
		foreach($order->info['tax_groups'] as $key => $value)
		{
				  $tax_rate = tep_get_tax_rate_from_desc($key);
				  $net = $tax_rate * $order->info['tax_groups'][$key];
				  if ($net>0) {
					$god_amount = $order->info['tax_groups'][$key] * $ratio1;
				//	$tod_amount += $god_amount;
					$order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
				  }
				}
			}	
          }
          $order->info['total'] -= $tod_amount;
        }
        if ($get_result['coupon_type'] =='P') {
          $tod_amount=0;
          if ($method=='Credit Note') {
            $tax_desc = tep_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
            $tod_amount = $order->info['tax_groups'][$tax_desc] * $od_amount/100;
            $order->info['tax_groups'][$tax_desc] -= $tod_amount;
          } else {
		  	if(isset($order->info['tax_groups'])) {
				reset($order->info['tax_groups']);
				//FOREACH
        //while (list($key, $value) = each($order->info['tax_groups'])) {
		foreach($order->info['tax_groups'] as $key => $value)
		{
				  $god_amount=0;
				  $tax_rate = tep_get_tax_rate_from_desc($key);
				  $net = $tax_rate * $order->info['tax_groups'][$key];
				  if ($net>0) {
				  	$god_amount = $order->info['tax_groups'][$key] * $coupon_result['coupon_amount']/100;
					$god_amount = $order->info['tax_groups'][$key] * $get_result['coupon_amount']/100;
					$tod_amount += $god_amount;
					$order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
				  }
				}
			}	
          }
          $order->info['tax'] -= $tod_amount;
        }
      }
    }
    }
    return $tod_amount;
  }

function update_credit_account($i) {
  return false;
 }

 function apply_credit() {
   global $insert_id, $FSESSION, $REMOTE_ADDR, $cc_id;
   	foreach($_SESSION['coupon_codes'] as $key => $value){
  

   if($FSESSION->email_redeem_code=='')
   		{$FSESSION->set('email_redeem_code',0);}
   if ($this->deduction !=0) {
     tep_db_query("insert into " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, redeem_date, redeem_ip, customer_id, order_id,email_redeem_id) values ('" . tep_db_input($value) . "', now(), '" . $REMOTE_ADDR . "', '" . tep_db_input($FSESSION->customer_id) . "', '" . tep_db_input($insert_id) . "','" . tep_db_input($FSESSION->email_redeem_code) . "')");
   }
   }
   $FSESSION->remove('coupon_codes');
   $FSESSION->remove('email_redeem_code');
 }

	function get_order_total($include_tax) {
    	global  $order, $cart, $FSESSION, $cc_id;
		require_once(DIR_WS_FUNCTIONS. 'ga_tickets.php');
		//$cc_id = $_SESSION['cc_id'];
		// get stored total(altered for deposit)
		$order_total = $order->info['total'];

		// Check if gift voucher is in cart and adjust total
		$products = $cart->get_products();
		for ($i=0; $i<sizeof($products); $i++) {
            if ($products[$i]['element_type']!="P") continue;
			$t_prid = tep_get_prid($products[$i]['id']);
			$gv_query = tep_db_query("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . tep_db_input($t_prid) . "'");
			$gv_result = tep_db_fetch_array($gv_query);

			if (preg_match('/^GIFT/', addslashes($gv_result['products_model']))) 
			{
				$qty = $cart->get_quantity($t_prid);
				$products_tax = tep_get_tax_rate($gv_result['products_tax_class_id']);
				if ($this->include_tax =='false') {
				   $gv_amount = $gv_result['products_price'] * $qty;
				} else {
				  $gv_amount = ($gv_result['products_price'] + tep_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
				}
				$order_total=$order_total - $gv_amount;
			}
		}
		
		if ($this->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
		if ($this->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
		
		// OK thats fine for global coupons but what about restricted coupons
		// where you can only redeem against certain products/categories.
		// and I though this was going to be easy !!!
		foreach($_SESSION['coupon_codes'] as $key => $value){ 

   		$coupon_query=tep_db_query("select coupon_code  from " . TABLE_COUPONS . " where coupon_id='".tep_db_input($value)."'");
		if (tep_db_num_rows($coupon_query) !=0) { 
			$coupon_result=tep_db_fetch_array($coupon_query);
			$coupon_get=tep_db_query("select restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='".tep_db_input($coupon_result['coupon_code'])."' and (restrict_to_products!='' or restrict_to_categories!='')");
			$get_result=tep_db_fetch_array($coupon_get);
			$in_cat = true;
			if ($get_result['restrict_to_categories']) {
				
				$cat_ids = preg_split("/[,]/", $get_result['restrict_to_categories']);
				for ($i = 0; $i <count($cat_ids); $i++) {
					for ($j=0; $j<count($products); $j++) {
						if ($products[$j]['element_type']!='P') continue;
						
						// new code
						
						$ga_path_array = array();
						$ga_path_array = explode('_', ga_get_product_path($products[$j]['id']));
						
						if (in_array($cat_ids[$i],$ga_path_array)){  
							$total_price += $this->get_product_price($products[$j]['id'],$products[$j]['quantity'],$include_tax);	//	echo 'total'.$j.' '. $total_price .'<br>';			
						}

					}
				}
			}
			
			if ($get_result['restrict_to_products']) {
				$pr_ids = preg_split("/[,]/", $get_result['restrict_to_products']);
				for ($i = 0; $i < count($pr_ids); $i++) {
					for ($j = 0; $j<count($products); $j++) {
						if ($products[$j]['element_type']!='P') continue;
						if ($products[$j]['id'] == $pr_ids[$i]) {
							$total_price += $this->get_product_price($products[$j]['id'],$products[$j]['quantity'],$include_tax);
						//	$order->products[$j]['coupon_apply']=1;
						}
					}
				}
			}
			
			if ($this->include_shipping == 'true') $total_price += $order->info['shipping_cost'];
			if(tep_db_num_rows($coupon_get)>0) {
				$order_total = $total_price; 
			 }
		}
		}//echo 'total'.$j.' '. $order_total .'<br>';	
		return $order_total;
  }

function get_product_price($product_id,$qty,$include_tax) {
    global $cart, $order;
    $products_id = tep_get_prid($product_id);
     $product_query = tep_db_query("select products_id, products_price, products_tax_class_id,products_price_break,products_weight from " . TABLE_PRODUCTS . " where products_id='" . (int)$product_id . "'");
    if ($product = tep_db_fetch_array($product_query)) {
      $prid = $product['products_id'];

      $products_tax = tep_get_tax_rate($product['products_tax_class_id']);
      $products_price = $cart->get_product_price($products_id,$product,(int)$cart->contents[$products_id]['discount_id']);
      if ($this->include_tax == 'true' && $include_tax) {
        $total_price += ($products_price + tep_calculate_tax($products_price, $products_tax)) * $qty;
      } else {
        $total_price += $products_price * $qty;
      }

    }
    //if ($this->include_shipping == 'true') $total_price += $order->info['shipping_cost'];
	return $total_price;
}

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_COUPON_STATUS', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', 'Do you want to display the Discount Coupon value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '60', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'true', 'Include Shipping in calculation', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'true', 'Include Tax in calculation.', '6', '4','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Re-calculate Tax', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'None', 'Re-Calculate Tax', '6', '5','tep_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS', '0', 'Use the following tax class when treating Discount Coupon as Credit Note.', '6', '6', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
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
    
    function collect_posts_forajax() {}
	}

?>