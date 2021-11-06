<?php
/*
	osCommerce, Open Source E-Commerce Solutions 
	http://www.oscommerce.com 
	
	Copyright (c) 2003 osCommerce 
	
	 
	
	Freeway eCommerce 
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	osConcert, Online Seat Booking 
  	https://www.osconcert.com

  	Copyright (c) 2020 osConcert

	Released under the GNU General Public License
*/


// Set flag that this is a parent file
    define( '_FEXEC', 1 );
   
  require('includes/application_top.php');
//test for enforced coupon
//if (ENFORCED_COUPON == 'yes'  && $FREQUEST->postvalue('gv_redeem_code')=="") {
if (ENFORCED_COUPON == 'yes'  &&  count($_SESSION['coupon_codes']) == 0) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_INVALID_REDEEM_COUPON), 'SSL'));
          exit();
    }
//end test

  require(DIR_WS_LANGUAGES . $FSESSION->language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
  require(DIR_WS_CLASSES . 'order_total.php');

  require(DIR_WS_CLASSES . 'order.php');
 // if($FREQUEST->getvalue('load'] && $FREQUEST->getvalue('load'] == 'load'){
	//  require('checkout_payment_process.php');
	//  return;
 // }

  require(DIR_WS_CLASSES . 'payment.php');
 
 //if (isset($FREQUEST->postvalue('coupon'])) $cc = $FREQUEST->postvalue('coupon'];
 //$FSESSION->remove['couponcode'];
 if($FREQUEST->getvalue('do_action')=='get_counpon_details'){
 	$FREQUEST->setvalue('coupon',$FREQUEST->getvalue('coupon'),'POST');
 	$FREQUEST->setvalue('gv_redeem_code',$FREQUEST->getvalue('gv_redeem_code'),'POST');
	$FREQUEST->setvalue('cot_gv',$FREQUEST->getvalue('cot_gv'),'POST');
	if ($FREQUEST->postvalue('cot_gv')) $FSESSION->set('cot_gv',$FREQUEST->postvalue('cot_gv'));
 	if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
    $payment_modules = new payment($payment);
  //ICW ADDED FOR CREDIT CLASS SYSTEM
   $order = new order;
    
  //$payment_modules->update_status();
  //ICW ADDED FOR CREDIT CLASS SYSTEM
   $order_total_modules = new order_total;
  //ICW ADDED FOR CREDIT CLASS SYSTEM

  $order_total_modules->collect_posts_forajax();
  
  //ICW ADDED FOR CREDIT CLASS SYSTEM
 	$order_total_modules->process();
	//print_r($GLOBALS);
	
  	if(sizeof($GLOBALS['ot_coupon'])){
  		$coupon=$GLOBALS['ot_coupon'];
  		$error="";
  		$error=$FSESSION->coupon_error;
  		if($coupon->code=='ot_coupon' && $error=="" && $coupon->output[0]['text']){
   			//cartzone dont
   			echo '<table width="90%" style="padding:3px;text-align:center" class="formArea">' .
   					'<tr><td></td>' .
   					'   <td class="eventTitle">'.TEXT_REDEEM_CODE . (($FSESSION->email_redeem_code)?$FSESSION->email_redeem_code: $coupon->coupon_code).'</td>' .
   					'   <td class="eventTitle">'.TEXT_VALUE. $coupon->output[0]['text'].'</td>' .
   					'</tr>' . 
   					'<tr><td></td>' .
   					'   <td class="eventTitle">'.TEXT_VALID_UTIL. $coupon->expire_date.'</td>' .
   					'   <td class="eventTitle">'.(($coupon->uses_per_user!=0)?TEXT_USES_REMAINING .$coupon->uses_per_user:'').'</td>' .
   					'<td rowspan="2" valign="middle" align="right">'.tep_template_image_button_dont('button_dont_redeem.gif',IMAGE_DONT_REDEEM_VOUCHER,'style="cursor:pointer;cursor:hand" onclick="javascript:submitFunction(1);"').'</td>'.
   					'</tr>' .   					
   					'</table>';
	    } else {
	    	echo '<table width="90%" align="center"><tr align="center"><td align="left" class="smalltext"><span class="errortext">'.$error.'</span></td></tr></table>';
	    }
  	} 
	exit;
  }

// if the customer is not logged on, redirect them to the login page
  if (!$FSESSION->is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  
  if($FREQUEST->postvalue('comments_chkbox')){
  	
  }else {  
  	$FREQUEST->setvalue('comments',"",'POST');
  	$FSESSION->remove('comments');
  }
// Add Extra Fields begin
  if($FREQUEST->postvalue('field_1')!=''){
  	if($FSESSION->is_registered('field_1'))
			$FSESSION->remove('field_1');
	$FSESSION->set('field_1',true);
  }  
  if($FREQUEST->postvalue('field_2')!=''){
  	if($FSESSION->is_registered('field_2'))
			$FSESSION->remove('field_2');
	$FSESSION->set('field_2',true);
  }  
  if($FREQUEST->postvalue('field_3')!=''){
  	if($FSESSION->is_registered('field_3'))
			$FSESSION->remove('field_3');
	$FSESSION->set('field_3',true);
  }  
  if($FREQUEST->postvalue('field_4')!=''){
  	if($FSESSION->is_registered('field_4'))
			$FSESSION->remove('field_4');
	$FSESSION->set('field_4',true);
  }
  if($FREQUEST->postvalue('other')!=''){
  	if($FSESSION->is_registered('other'))
			$FSESSION->remove('other');
	$FSESSION->set('other',true);
  }
  //Add Extra Fields end
//for coupon  
  if($FREQUEST->postvalue('coupon')!=''){
  	if($FSESSION->is_registered('coupon'))
			$FSESSION->remove('coupon');
	$FSESSION->set('coupon',true);
  } 
  if($FREQUEST->postvalue('gv_redeem_code')){
  	if($FSESSION->is_registered('gv_redeem_code'))
			 	$FSESSION->remove('gv_redeem_code');
	$FSESSION->set('gv_redeem_code',$FREQUEST->postvalue('gv_redeem_code'));
  }
 // if($FSESSION->is_registered('email_redeem_code'))
  //	$FSESSION->remove('email_redeem_code');
	
// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && $FSESSION->is_registered('cartID')) {
    if ($cart->cartID != $FSESSION->cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }
// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!$FSESSION->is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }
  $shipping=&$FSESSION->getobject("shipping");
  //$payment=$FREQUEST->postvalue('payment');
  //$FSESSION->set('payment',$payment);
  if ($FREQUEST->postvalue('payment')) {$payment = $FREQUEST->postvalue('payment');
                                        $FSESSION->set('payment',$payment);}
										
	$comments=$FREQUEST->postvalue('comments');
	if (tep_not_null($comments)) {
	  $FSESSION->set('comments',$comments);
  	}
	$other=$FREQUEST->postvalue('other');
	if (tep_not_null($other)) {
	  $FSESSION->set('other',$other);
  	}
	//Add Extra Fields begin
	$field_1=$FREQUEST->postvalue('field_1');
	/*if (tep_not_null($field_1))*/ {
	  $FSESSION->set('field_1',$field_1);
  	}  
	$field_2=$FREQUEST->postvalue('field_2');
	/*if (tep_not_null($field_2))*/ {
	  $FSESSION->set('field_2',$field_2);
  	}
	$field_3=$FREQUEST->postvalue('field_3');
	/*if (tep_not_null($field_3))*/ {
	  $FSESSION->set('field_3',$field_3);
  	}
	$field_4=$FREQUEST->postvalue('field_4');
	/*if (tep_not_null($field_4))*/ {
	  $FSESSION->set('field_4',$field_4);
  	}
	//Add Extra Fields end
//moved for Free
 $order = new order;
   $order_total_modules = new order_total; 
    $order_total_modules->collect_posts();
	  $order_total_modules->pre_confirmation_check();
// load the selected payment module
  
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
//2012 changes by Graeme
		if($_SESSION['credit_covers']=='valid'){//enough credit to cover the purchase
       		$FSESSION->remove('credit_covers');//kill the session
			if(	MODULE_PAYMENT_FREE_STATUS=='True'){//the Free Payment Module is installed
				$payment='free';//hijack the chosen payment method
				$FSESSION->set('payment',$payment);
				$order->info['payment_method']=MODULE_PAYMENT_FREE_DISPLAY_NAME;
				}
			
			}
//end changes
  $payment_modules = new payment($payment);
  
//ICW ADDED FOR CREDIT CLASS SYSTEM
//   $order = new order;
 tep_check_country_differ();
  $payment_modules->update_status();
//ICW ADDED FOR CREDIT CLASS SYSTEM
//  $order_total_modules = new order_total; 
//ICW ADDED FOR CREDIT CLASS SYSTEM

// $order_total_modules->collect_posts();
  
  
//ICW ADDED FOR CREDIT CLASS SYSTEM
 // $order_total_modules->pre_confirmation_check();

// ICW CREDIT CLASS Amended Line
//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($GLOBALS[$FSESSION->payment])) && (!$credit_covers) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }
  if (is_array($payment_modules->modules)) {
    $credit_covers=false;
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);
//ICW Credit class amendment Lines below repositioned
//  require(DIR_WS_CLASSES . 'order_total.php');
//  $order_total_modules = new order_total;
// Stock Check
  $any_out_of_stock = false;
  $matc = false;//T&C
  if (STOCK_CHECK == 'true') {
  //July 2013 clear the ga_cat_id sessions if they exist
  		include_once('includes/functions/ga_tickets.php');
		ga_kill_sessions();
  //end July 2013
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
	{
	//T&C 
	 if ($order->products[$i]['model']=='TAC'){
	 	$matc = true;
	}
	//end
     if ($order->products[$i]['element_type']=='P'){
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
     } 


	//grab the result for all products as a session
	    $ga_check= ga_check($order->products[$i]['id'],$order->products[$i]['qty'],$order->products[$i]['product_type']);
	//	print_r($prod_type);
		
    }	

	//July 2013 end of product loop - check for the cumulative totals
	  foreach ($_SESSION as $key => $value) {
 	   if (substr($key, 0, 9) == "ga_cat_id") {      
      
	  //get the cat_id
	$ga_cat_id = substr($key, 10); // returns cat_id
	  $category_ga_query = tep_db_query("select categories_GA,categories_quantity_remaining from " . TABLE_CATEGORIES . " where categories_id = '" . $ga_cat_id . "' limit 1");
				 if (tep_db_num_rows($category_ga_query)) { 
				  $category_ga = tep_db_fetch_array($category_ga_query);
					if($category_ga['categories_GA']==1 || $category_ga["categories_GA"]==2){//this is a GA category
	  					$quantity_left_ga=($category_ga['categories_quantity_remaining'])-$value;
						 if($quantity_left_ga<0){									
									   // $messageStack->add_session('header', 'Please check your order', 'error');
										$_SESSION['ga_cat_id_'.$ga_cat_id]=$quantity_left_ga;//set it to the negative value
										$any_out_of_stock = true;
									   			 }else{//unset the session
												 $FSESSION->remove('ga_cat_id_'.$ga_cat_id);
												 }
	  
    										}
											} 
	   
   											 }
											}
	// end July 2013
	
		  if($FSESSION->is_registered('coupon_exist')) $FSESSION->remove('coupon_exist'); 
		  if($FSESSION->is_registered('coupon_exist_equal_amount')) $FSESSION->remove('coupon_exist_equal_amount');
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }
  //$messageStack->add('security', HEADING_TRANSACTION_SECURITY . '&nbsp;<br>&nbsp;&nbsp;&nbsp;'.HEADING_TRANSACTION_ENSURE . $_SERVER['REMOTE_ADDR'], 'warning');

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

  $content = CONTENT_CHECKOUT_CONFIRMATION;

  //require('includes/http.js');
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
