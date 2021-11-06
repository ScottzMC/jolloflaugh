<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
  if($FREQUEST->postvalue('comments_chkbox')){
  }else {  
  	$FREQUEST->setvalue('comments',"",'POST');
  	$FSESSION->remove('comments');
  }
//for coupon  
  if($FREQUEST->postvalue('coupon')!=''){
  	if(!$FSESSION->is_registered('coupon'))
		$FSESSION->set('coupon',true);
	else
		$FSESSION->remove('coupon');
  } 
  if($FREQUEST->postvalue('gv_redeem_code')!=''){
  	if(!$FSESSION->is_registered('gv_redeem_code'))
		$FSESSION->set('gv_redeem_code',$FREQUEST->postvalue('gv_redeem_code'));
	else
	 	$FSESSION->remove('gv_redeem_code');
  }
// if no shipping method has been selected, redirect the customer to the shipping method selection page
	$shipping=&$FSESSION->getobject("shipping");
  if ($shipping!=false && $FSESSION->free_shipping!=true && !is_object($shipping) && !is_array($shipping) ) {
    echo 'shipping_method_error||' . TEXT_CHOOSE_SHIPPING_METHOD . '{}';
	$confirm_error=true;
  }

  //if (!$FSESSION->is_registered('payment')) $FSESSIOM->set('payment','');
  $payment=$FREQUEST->postvalue('payment');
  if ($FREQUEST->postvalue('payment')!='') $FSESSION->set('payment',$payment);
   
	if($FSESSION->is_registered('comments')) {
		$FSESSION->remove('comments');
	}	

	$comments=$FREQUEST->postvalue('comments');
	/*if (tep_not_null($comments)) {
	  if (!$FSESSION->is_registered('comments')) $FSESSIOM->set('comments','');
  	}*/
  $FSESSION->set('comments',$comments);

// load the selected payment module
  
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($payment);
//ICW ADDED FOR CREDIT CLASS SYSTEM
    $order = new order;
	 $order->billing=$FSESSION->getobject("billto_array");
	 $order->delivery=$FSESSION->getobject("sendto_array");

 tep_check_country_differ();
  $payment_modules->update_status();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules = new order_total;
//ICW ADDED FOR CREDIT CLASS SYSTEM

 $order_total_modules->collect_posts();
   
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->pre_confirmation_check();


// ICW CREDIT CLASS Amended Line
//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($GLOBALS[$FSESSION->payment])) && (!$credit_covers) ) {
    echo "payment_error||" . ERROR_NO_PAYMENT_MODULE_SELECTED;
	$confirm_error=true;
  }
  if($confirm_error)
  	exit;
  if (is_array($payment_modules->modules)) {
    $credit_covers=false;
    $payment_modules->pre_confirmation_check();
  }

  $shipping_modules = new shipping($shipping);
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
     if ($order->products[$i]['element_type']=='P'){
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
     } 
	 if(isset($order->products[$i]['attributes'])){
	 	$attribute_stock_check="";
	reset($order->products[$i]['attributes']);
	while (list($option, $value) = each($order->products[$i]['attributes'])) {
		$attrb_ids.=$value["option_id"] . "{".$value["value_id"]."}-";
	}
	$attrb_ids=substr($attrb_ids,0,-1);
	reset($order->products[$i]['attributes']);
			$attribute_stock_check=tep_check_attribute_stock($order->products[$i]['id'],$attrb_ids,$order->products[$i]['qty']);
			if(tep_not_null($attribute_stock_check)){
				$any_out_of_stock=true;
			}
		}	
    }	
	if($FSESSION->is_registered('coupon_exist')) $FSESSION->remove('coupon_exist'); 
	if($FSESSION->is_registered('coupon_exist_equal_amount')) $FSESSION->remove('coupon_exist_equal_amount');
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      	echo "redirect_page||" .FILENAME_SHOPPING_CART;
		exit;
    }
  }
  //$messageStack->add('security', HEADING_TRANSACTION_SECURITY . '&nbsp;<br>&nbsp;&nbsp;&nbsp;'.HEADING_TRANSACTION_ENSURE . $_SERVER['REMOTE_ADDR'], 'warning');
  echo 'payment_method_display||' . $order->info['payment_method'] . '{}';
  echo 'checkout_confirm_content||';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
 // if ($messageStack->size('security') > 0) {
?>
<tr><td></td></tr>
      <tr bgcolor="#D5EFF9">
        <td class="main"><?php echo HEADING_TRANSACTION_SECURITY . '&nbsp;<br>&nbsp;&nbsp;&nbsp;'.HEADING_TRANSACTION_ENSURE . $_SERVER['REMOTE_ADDR'];//echo $messageStack->output('security'); ?></td>
      </tr>

<?php
 // } ?>
<tr><td>
</td>
</tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
<?php
  if ($FSESSION->sendto != false) {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');?></td>
              </tr> 
			  
<?php
    if ($order->info['shipping_method']) {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($FSESSION->sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php

//Store Membership subscription On the purchase confirmation page, remove the option to (edit) this type of subscription.
 //$subs_query=tep_db_query("select sc.subscription_categories_id as cat_id,s.subscription_id as id,s.subscription_type from " .TABLE_SUBSCRIPTIONS ." s," . TABLE_SUBSCRIPTION_TO_SUBSCRIPTION_CATEGORIES . " sc where s.subscription_id=sc.subscription_id and  s.subscription_type='M'");
				// $row=tep_db_fetch_array($subs_query);
				// $subscription_id=$row['id'];
				// $subscription_type=$row['subscription_type'];
				//echo $subscription_id;
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>

				 <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
				
				 	
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>

				 <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>


                  </tr>
<?php
  }
  $dis_time_format="";	
  if(defined('TIME_FORMAT')) $dis_time_format=TIME_FORMAT;  
  for ($i=0, $n=count($order->products); $i<$n; $i++) { 

					$qty=$order->products[$i]['qty'];
					
					
     
    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $qty . '&nbsp;</td>' . "\n" .
         '            <td class="main" width="70%"  valign="top">' . $order->products[$i]['name'] ;
	 if ($order->products[$i]['element_type']=="V"){
		if($order->products[$i]['others']['end_date'] && $order->products[$i]['others']['end_time']){	
			$str_time=strtotime($order->products[$i]['others']['end_date'] .' '.$order->products[$i]['others']['end_time'])+60;
			$order->products[$i]['others']['end_date']=date('Y-m-d',$str_time);
			$order->products[$i]['others']['end_time']=date('H:i:s',$str_time); 
		} 
		$stime="";
		$etime="";
		if($order->products[$i]['others']['start_time']) {
			$stime=date("h:i A",strtotime($order->products[$i]['others']['start_time']));
			if($dis_time_format!="") {
				if($dis_time_format=='24')
					$stime=date("H:i",strtotime($order->products[$i]['others']['start_time']));
			}
		}
		if($order->products[$i]['others']['end_time']) {
			$etime=date("h:i A",strtotime($order->products[$i]['others']['end_time']));
			if($dis_time_format!="") {
				if($dis_time_format=='24')
					$etime=date("H:i",strtotime($order->products[$i]['others']['end_time']));
			}
		}
		//if($order->products[$i]['others']['resource_costs'] && $order->products[$i]['tax']) $order->products[$i]['others']['resource_costs']=tep_add_tax($order->products[$i]['others']['resource_costs'],$order->products[$i]['tax']);
		echo '<br><small>&nbsp;	- '.TEXT_START_DATE.' : '. format_date($order->products[$i]['others']['start_date']) . '&nbsp;' . $stime . ' <br>&nbsp; - ' . TEXT_END_DATE .' : ' . format_date($order->products[$i]['others']['end_date']). '&nbsp;' . $etime . ' '.(($order->products[$i]['others']['resource_id'])? '<br>&nbsp; - '.tep_get_resource_name($order->products[$i]['others']['resource_id'],'resource_name').'('.$currencies->format(tep_add_tax($order->products[$i]['others']['resource_costs'],$order->products[$i]['tax'])).')':'').'</small>';
	 } 
    if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

   if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
			reset($order->products[$i]['attributes']);
			$attribute_id="";
			for($k=0,$att_max=sizeof($order->products[$i]['attributes']);$k<$att_max;$k++) {
				$att_row=$order->products[$i]['attributes'][$k];
				if($attribute_id!="") $attribute_id.="-";
				$attribute_id.=$att_row['option_id'] . "{" . $att_row['value_id'] . "}";
			}
	      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
	        if($order->products[$i]['element_type']=="V" && $order->products[$i]['tax']) $order->products[$i]['attributes'][$j]['price']=tep_add_tax($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax']); 
	     	if($order->products[$i]['attributes'][$j]['option'] && $order->products[$i]['attributes'][$j]['value'])  echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '('.$currencies->format(tep_add_tax($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'])).')</i></small></nobr>';
		//    if($order->products[$i]['attributes'][$j]['option'] && $order->products[$i]['attributes'][$j]['value'])  echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '('.$currencies->format($order->products[$i]['attributes'][$j]['price']).')</i></small></nobr>';
		   }
	      if (STOCK_CHECK == 'true' && $order->products[$i]['element_type']=="P") {
			 echo tep_check_attribute_stock($order->products[$i]['id'],$attribute_id,$order->products[$i]['quantity']);
		  }
	}
	 if ($order->products[$i]['element_type']=="P"){
	 	echo isset($order->products[$i]['discount_whole_text'])?'<br>' . $order->products[$i]['discount_whole_text']:'';
	 }


	$model = ""; 	

	if($order->products[$i]['sku']!="")echo "<br>" . HEADING_SKU . $order->products[$i]['sku']; 
    echo '</td>' . "\n";
    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
    echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="30%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['payment_method']; ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="2">
							<?php
							  if (MODULE_ORDER_TOTAL_INSTALLED) {
								$order_total_modules->process();
								echo $order_total_modules->output();
							  }
							?>
						</table>
					</td>
				</tr>
				<tr><td>&nbsp;&nbsp;</td></tr>
				<tr>
        			<td align="right">
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
          					<tr>
          					  <td align="right" class="main">
								<?php
									$form_action=$GLOBALS[$FSESSION->payment];
									if ($form_action->form_action_url!='' && (!$FSESSION->is_registered('cc_id') || $order->info['total']>0)) {
										$form_action_url = $form_action->form_action_url;
									} else {
										$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS,'', 'SSL');
									}
									echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
								
									if (is_array($payment_modules->modules)) {
										$val = $payment_modules->process_button();
										$values = '';
										for($i=0;$i<strlen($val);$i++){
											$values .= ord($val{$i}) . ",";
										}
										
										echo "<script language='javascript'>";
										echo " var hidden_values='" . $values . "';";
										echo "</script>";
									}
								  echo '<div id="values"></div>';
								  if (!$USER_BARRED) {
									 echo "<span id='confirm_button' style='display:none;'>" . tep_template_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</span>';
								  }
								?>
								</form>
          					  </td>
        					 </tr>
      					  </table>
						 </td>
     				 </tr>
        	</table>
			</td>
		</tr>
		
		
		</table>
		</td>
      </tr>
<?php
// BOF: Lango modified for print order mod

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
     $FSESSION->set('payment_info',$confirmation['title']);
	  
// EOF: Lango modified for print order mod
?>

   <!--	<tr>
	 <td class="main"><?php echo '<b>' . HEADING_TRANSACTION_SECURITY . '</b>'?></td>
	</tr>
	<tr><td class="main"><?php echo '&nbsp;&nbsp;&nbsp;'.HEADING_TRANSACTION_ENSURE . $_SERVER['REMOTE_ADDR']; //$order->info['ip_address'] ;?></td></tr> -->

      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
     
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php 
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
              
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
               
				<?php if(($confirmation['fields'][$i]['title']=='Credit Card Number:') ){
						$pinformation= 'XXXX XXXX XXXX &nbsp;'. substr($confirmation['fields'][$i]['field'],-4) ;
						if($FSESSION->is_registered('ccno')) {
							$FSESSION->remove('ccno');
						}
						$FSESSION->set('ccno',$pinformation);
					}
					else $pinformation= $confirmation['fields'][$i]['field']; ?>
				<td class="main"><?php echo $pinformation;?></td>
              </tr>			  
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
?>

<?php
  if (tep_not_null($FSESSION->comments)) {
  ?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b>'; ?></td>
      </tr>

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
  }
?>
  
      

    </table>
<?php echo '{}confirm_hidden_values||' . $values;?>
