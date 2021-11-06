<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	require(DIR_WS_INCLUDES."http.js"); 
	//echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post','onSubmit="return checkvalidate();"');
	
	if ($FSESSION->get('payment') == 'free'){
		$FSESSION->remove('payment');
	}
	echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post','onSubmit="return checkvalidate();" id = "checkout_payment"');
	 if(DISCOUNT_CHECKOUT_MESSAGE !='')
	 {
	?>
	<div class="alert alert-primary" role="alert"><h2 style="text-align:center;margin-bottom:0"><?php echo DISCOUNT_CHECKOUT_MESSAGE; ?></h2></div>
	<?php
	}

	if (HIDE_BILLING_ADDRESS == 'no')
	{
	?>
	<!--do something?-->
	<?php 
	} 
	?>
	<?php $serverDate = date('Y-m-d H:i:s',getServerDate(false)); ?>
		<div class="section-header">
		<h2><?php echo HEADING_TITLE; ?></h2>
		</div>
	<?php
	if (HIDE_BILLING_ADDRESS == 'no')
	{
	//do something?
	} else 
	{
		if (HIDE_BILLING_ADDRESS == 'no')
		{ 
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\"><tr>";
		echo "<td class=\"main\"><b>";
		echo TABLE_HEADING_BILLING_ADDRESS; 
		echo "</b></td></tr></table>";
		}
	}
	if ($FREQUEST->getvalue('payment_error')!='' && is_object(${$FREQUEST->getvalue('payment_error')}) && ($error = ${$FREQUEST->getvalue('payment_error')}->get_error())) 
	{
	?>
		<div><?php echo '<span class="alert alert-info">' . tep_output_string_protected($error['title']) . '</span>'; ?></div>
		<div class="alert alert-info"><?php echo $error['error']; ?></div>
		<?php
		if ($messageStack->size('checkout_payment') > 0) 
		{
		echo $messageStack->output('checkout_payment');
		} 
	}
	//comment out if using customer shipping
	if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
	{ 
	?>
		<!--Billing Address Table-->
		<table width="100%" cellpadding="2" style="display:<?php //hide billing
		if (HIDE_BILLING_ADDRESS == 'no')
		{
		echo "true"; 
		} else {
		echo "none";
		}
		?>">
		<tr>
		<td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><div style="float:right">' . tep_template_image_button('', IMAGE_BUTTON_CHANGE_ADDRESS) . '</div></a>'; ?>
		</td>
		<td align="right" width="50%" valign="top">
			<table cellpadding="2">
			<tr>
			<td class="main" align="center" valign="top">
			<b><?php echo TITLE_BILLING_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?>
			</td>
			 
			<td class="main" valign="top"><?php
			// echo tep_address_label($FSESSION->customer_id, $FSESSION->billto, true, ' ', '<br>');
			$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_customer_email as customer_email, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$FSESSION->get('billto') . "'");

			$addresses = tep_db_fetch_array($addresses_query);
			$format_id = tep_get_address_format_id($addresses['country_id']);

			if($addresses['country_id'] == NULL)
			{
			$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_customer_email as customer_email, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$FSESSION->get('billto') . "'");

			$addresses = tep_db_fetch_array($addresses_query);
			$format_id = tep_get_address_format_id($addresses['country_id']);
			}

			if($addresses!==NULL)
			{
			$addresses = array_map('ucfirst',$addresses);
			$addresses['customer_email'] = strtolower($addresses['customer_email']);
			echo tep_address_format($format_id, $addresses, true, '<br>', ' ');
			}
			?>
			</td>
			</tr>
			</table>
			</td>
		</tr>
		</table><!--EOF Billing Address Table-->
		<?php
		} //eof BO Billing Address
		?>

		<h4><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h4>

		<table width="100%" cellpadding="2">
			<?php 
			$selection = $payment_modules->selection();
			if (sizeof($selection) > 1) 
			{
			?>
			<tr>

			<td class="main" width="75%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
			<td class="main" width="25%" valign="top" align="right">
			<b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?>
			</td>

			</tr>
			<?php
			} else 
			{
			?>
			<tr>
			<td class="main" width="100%" colspan="2">
			<?php  
			if(sizeof($selection)>0)
			{
				echo TEXT_ENTER_PAYMENT_INFORMATION; 
			}else{
				echo TEXT_NO_PAYMENT_SELECTION;
			}
			?>
			</td>
			</tr>
			<?php
			}
			$flag='B';
			$radio_buttons = 0;
			$m = count($selection[0]['fields']);
			$cnt = 0;
			$barred=0;
			for ($i=0, $n=sizeof($selection); $i<$n; $i++) 
			{
			?>
			<Tr>
				<TD colspan="2">
				
				<TABLE width="100%" cellpadding="2">
				<?php
				if (!$selection[$i]['barred'])
				{
				if (($selection[$i]['id'] == $FSESSION->get('payment')) || ($n == 1)) 
				{
					echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
				}else
				{ 
				   echo '<tr id="defaultunSelected" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
				}
				?>
						<td width="10"></td>
						<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
							<table>
							<tr><td>
							<?php for($j=0;$j<$m;$j++){
								//FOREACH
							//while(list($k,$v) = each($selection[0]["fields"]["$j"])){
								foreach($selection[0]["fields"]["$j"] as $k => $v)
								{
								  if($cnt%2==0)
									 //echo '<tr> ';
									//echo  '<td class="main">'.$v . '</td>';
									$cnt++;
								} 
							} ?>
							</td></tr>
							</table>
						</td>
						<td class="main" align="right">
						<?php $single_payment=false;
							if (sizeof($selection)>1) {
							  echo tep_draw_radio_field('payment', $selection[$i]['id'],((($selection[$i]['id'] == $FSESSION->get('payment')) || ($n == 1))?true:''));
							}else 
							{
							  $single_payment=true;
							  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
							}
						?>
						</td>
						<td width="10"></td>
						</tr>
						<?php
							if (isset($selection[$i]['error'])) 
							{
						?>
						<tr>
							<td width="10"></td>
							<td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
							<td width="10"></td>
						</tr>
						<?php
							} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) 
							{
							?>	
							<tr <?php if(!$single_payment){?>id="<?php echo $selection[$i]['id'];?>" <?php echo (($FSESSION->get('payment')!=$selection[$i]['id'])?'style="display:none"':'');}?>>
							<td width="10"></td>
							<td colspan="4">
							<table cellpadding="2">
								<?php
									  for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) 
									  {
								?>
									  <tr>
										<td width="10"></td>
										<td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
										<td></td>
										<td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
										<td width="10"></td>
									  </tr>
								<?php
									  }
								?>
							</table>
							</td>
							<td width="10"></td>
							</tr>
							<?php
							} // fields,error
						} else 
						{ // barred
							echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ',\'', $selection[$i]['id'] . '\')">' . "\n";
							?>
							<td width="10"></td>
							<td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b>
								<table>
								<?php for($j=0;$j<$m;$j++)
								{
								//while(list($k,$v) = each($selection[0]["fields"]["$j"]))
								foreach($selection[0]["fields"]["$j"] as $k => $v)	
									{
									if($cnt%2==0)
									//echo '<tr> ';
									//echo  '<td class="main">'.$v . '</td>';
									$cnt++;
									} 
								} 
								?>
								</table>
							</td>
							<td class="main" align="right">
							<?php $single_payment=false;
								if (sizeof($selection) > 1) 
								{
								echo tep_draw_radio_field('payment', $selection[$i]['id'],false,' disabled="disabled" ');
								} else {
								$single_payment++;
								}
							?>
							</td>
							<td width="10"></td>
						</tr>
						<tr>
							<td width="10"></td>
							<td colspan="4" style="padding-left:10px">
							<table>
								<tr>
									<td class="smallText"><b><font color="red"><?php echo TEXT_PAYMENT_BARRED;?></font></b>
									</td>
								</tr>
								<tr>
									<td class="smallText"><?php echo sprintf(TEXT_PAYMENT_BARRED_REASON,tep_get_country_title($IP_COUNTRY));?>
									</td>
								</tr>
							</table>
							</td>
						</tr>	
						<?php 
							$barred++;
						} // barred
						?>
				</Table>
				</td>
			</tr>
			<?php
			$radio_buttons++;
			}
			if ($barred==count($selection)) $payment_barred=true;
			?>
	 </table><br>
	<script>	
	<?php /* following jscript function ADDED FOR CCGV */ ?>
	var submitter = null;
	function submitFunction() 
	{
	submitter = 1;
	}
	<?php /* END OF ADDED FOR CCGV */ ?>
	previousPaymentSelected="<?php echo $FSESSION->get('payment');?>";
	if(!previousPaymentSelected && document.getElementById("defaultunSelected")) selectRowEffect(document.getElementById("defaultunSelected"),0,"<?php echo $selection[0]['id'];?>");
	function doComment(chk) { 
		if(document.getElementById("show_comments") && chk.checked)
			document.getElementById("show_comments").style.display="";
		else if(document.getElementById("show_comments") && !chk.checked)
			document.getElementById("show_comments").style.display="none";
	}
	</script>
	<?php  
	$display_season_tickets = false;
	
	$gv_amount_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" .(int)$FSESSION->customer_id . "'");		
	$get_result=tep_db_fetch_array($gv_amount_query);
		if($get_result['amount'] > 0 && MODULE_ORDER_TOTAL_GV_STATUS == 'true')
		{
		$display_season_tickets = true;
		}
		
		$coupon_check_qry = tep_db_query("select * from " . TABLE_COUPONS . " where '". $serverDate ."' <= coupon_expire_date and '". $serverDate ."' >= coupon_start_date and coupon_active='Y' ");
		if(tep_db_num_rows($coupon_check_qry) && MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
		{ 
		$display_coupons = true;
		}
		
		//we now have two variables:
		// if $display_season_tickets == true the customer has season tickets and we should offer those first (as we cannot check whether the customer is entitled to coupons on an individual basis
		$coupon_display_style = '';
		$coupon_show_style    = 'none';
		if($display_season_tickets == true and $display_coupons == true)
		{
		//hide coupons
		$coupon_display_style = 'none';
		$coupon_show_style ='';
		}
		
		if($display_season_tickets == true)
		{ 	
			?>
			<h4 id ="seasonTitle" style = "display:"><?php echo TEXT_SEASON_TICKET;?></h4> 
			<?php					
				
	$use_season_tickets_checkbox = " onchange=javascript:hideSeasonUnchecked();";
	$show_id_entry = 'none';
		if (MODULE_ORDER_TOTAL_GV_CHECKBOX == "Checked"){
			$use_season_tickets_checkbox = "checked . ' onchange=javascript:hideSeason();'" ;
			$show_id_entry = '';
		}
	if($display_coupons == true && $cart -> check_for_vouchers() == false)
	{
	?>
		<table width="100%" cellpadding="2">
		<tr id="select" style = "display:">
			<td class="main">&nbsp;&nbsp;&nbsp;<b><span id="click_text"><?php echo TEXT_REDEEM_YOUR_SEASON_TICKET;?></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo  tep_draw_checkbox_field('123', '0',$show_coupon,' id = "123" ' . $use_season_tickets_checkbox );?>
			
		</td>
		</tr>
		<tr id="choose" style="display:<?php echo $coupon_show_style;?>">
		<td class="main">&nbsp;&nbsp;&nbsp;<b><span id="click_text"><?php echo TEXT_USE_COUPON_INSTEAD;?></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  tep_draw_checkbox_field('234', '0',$show_coupon,' id = "234" onchange="javascript:doStuff()"');?> 
		</td>	
		</tr>
		<?php 
	}else
	{
		?>
		<table>
		<tr id="select" style="display:">
		<td class="main">&nbsp;&nbsp;&nbsp;<b><span id="click_text"><?php echo TEXT_REDEEM_YOUR_SEASON_TICKET;?></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  tep_draw_checkbox_field('123', '0', $show_coupon, 'id="123" ' . $use_season_tickets_checkbox . ' onchange="javascript:showHide(\'entry\')"; ');?>
		</td>
		</tr>
		<?php 
	}
	?>	
		<table>
		<tr id="entry" style="display:<?php echo $show_id_entry;?>">
			<td class="main">
				<table width="100%">			  
				  <tr>
					<td>
					<div id="error_span"></div>
					<div class="smalltext" align="center" style="height:100;width:100%;"></div>                         
					<?php 
					echo $order_total_modules->season_credit_selection(); ?>
					 </td>
				  </tr>
				</table>
			</td>
		</tr>
		</table><!-- eof main-table-->
		<?php 	   
		}//end season ticket
		?>
<?php #### display discount ####
echo $order_total_modules->display_before_comments;
?>
	<?php  
	if($display_coupons == 'true' && $cart -> check_for_vouchers() == false)
	{
	?>
		<br>
		<table width="100%" style="display:<?php echo $coupon_display_style;?>" id="coupon_table">
		<tr>
		<td>
		<h4><?php echo TEXT_GIFT_VOUCHER;?></h4> 
		</td>
		</tr>
		<?php
		if (ENFORCED_COUPON == 'yes')
		{
		$show_coupon=true; 
		?>
		<tr id="" style="display:none;">
			<td class="main">&nbsp;&nbsp;&nbsp;<b><?php echo TEXT_REDEEM_YOUR_VOUCHER;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo  tep_draw_checkbox_field('coupon', '0',$show_coupon,' onclick="javascript:hide_credit()"');?> 
			</td>
		</tr>	
		<?php 
		}else
		{ 
		$show_coupon=false;
		?>
		<tr id="click_text1">
			<td class="main">&nbsp;&nbsp;&nbsp;<b><span id="click_text"><?php echo TEXT_REDEEM_YOUR_VOUCHER;?></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo  tep_draw_checkbox_field('coupon', '0',$show_coupon,' onclick="javascript:hide_credit()"');?> 
			</td>
		</tr>
		<?php } ?>
		<tr id="reset_page" style = "display:none">
			<td class="main">&nbsp;&nbsp;<b><span id="click_text"><?php echo TEXT_COUPON_CANCEL;?></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo  tep_draw_checkbox_field('coupon2', '0',0,'onclick=javascript:startOver()');?> </td>
		</tr>
		<tr>
		<td>
		<table width="100%">			  
		  <tr>
			<td>
			<div id="error_span"></div>
		<div id="credit_result" class="smalltext" align="center" style="display:none;height:100;width:100%;"></div>                         
		<div id="credit" style="display:none;"><?php echo $order_total_modules->credit_selection(); ?></div>	
			 </td>
		  </tr>
		</table>
		</td>
		</tr>
	</table>

	<?php 
	} 
	?>
	<!-- Box Office Discount -->
	<?php
	if(MODULE_ORDER_TOTAL_BOFR_STATUS == 'true' && $_SESSION['customer_country_id']==999)
	{
		//kill sessions - they should not be live here but just in case
		$FSESSION->remove('bofr');
		$FSESSION->remove('bofr_title');
		//catch error return
		if(isset($_GET['bofr']))
		{
		$bofr_display = '';
		$bofr_error = $_GET['bofr_error'];
		}
		else
		{
		$bofr_display = 'display:none';
		$bofr_error   = '';
		}
		?>
		<!--BOFR stuff-->
				<table width="100%">
				
				<tr>
				<td class="main">
				<h4><?php echo MODULE_ORDER_TOTAL_BOFR_TITLE;?></h4> 
				<table width="100%">			  
					  <tr>
						<td>
						<div id="error_span"></div>
				<div id="bofr_error" class="smalltext messageStackError" align="left" style="<?php echo $bofr_display ?>;width:100%;margin-bottom:10px;"><?php echo $bofr_error; ?>
				</div>     
				<div id="bofr_title">
				<table>
					<tr>
						<td>
						<label><?php echo MODULE_ORDER_TOTAL_BOFR_TITLE_DESCRIPTION;?></label>
						<input type="text" name="bofr_title" id="bofr_title_input" />
						</td>
					</tr>
					<tr>
						<td>
						<label><?php echo MODULE_ORDER_TOTAL_BOFR_DESCRIPTION;?></label>
						<input type="decimal" name="bofr" id="bofr_input" />
						<span> <?php echo ORDER_TOTAL_IS; ?> <?php echo $currencies->format($order->info['total']);?></span>
						</td>
					</tr>
				</table>
				</div>	
						</td>
					  </tr>
					</table>
				</td>
				</tr>
				</table>

	<?php 
	} 
	?>
	<?php 
	if (EXTRA_FIELDS == 'yes')
	{
	include(DIR_FS_CATALOG.DIR_WS_TEMPLATES. '/extra_fields1.php'); 
	}
	?>
		<script>
		if(document.checkout_payment.coupon && document.checkout_payment.coupon.checked==true && document.getElementById("credit").style.display=='none'){
		hide_credit();
		}
		</script>
<?php #### display discount and other modules
      #### just add function display_before_comments() to each order total module you want to say something
 echo $order_total_modules->display_before_comments();
?>
		<div style="display:true">
		<br>
			 <h4><?php echo TABLE_HEADING_COMMENTS;?></h4>
			<?php 
				//Get comments up
				if ($FSESSION->is_registered('comments')) 
				{
				$comments=$_SESSION['comments'];
				}
				//end
				echo TABLE_HEADING_COMMENTS_HERE .tep_draw_checkbox_field('comments_chkbox', '1',($comments)?true:false,'onclick=javascript:doComment(this)');
			?>
		</div>
	<?php
		if($comments!="")
		{
			$comment_style="style='display:block'";
		}else{ 
			$comment_style="style='display:none'";
		}		
	?>
	<div <?php echo $comment_style; ?> id="show_comments">
		<table width="100%">
			<tr>
				<td><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_textarea_field('comments', 'soft', '60', '5',$_SESSION['comments']); ?>
				</td>
			</tr>
		</table>
	</div>
	<br>


	
	<!--#######################################################################-->
	<?php 
	if(PAYMENT_SET_TERMS=='no'){
	?>
	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8"><strong><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</strong>&nbsp;&nbsp;' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?>
		</div>
		<div class="col-md-2" style="padding:15px;text-align:center">
		</div>
		<div class="col-md-2" style="padding:15px;text-align:center"><?php if (!$USER_BARRED && !$payment_barred) echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
		</div>
	</div>
	</div>
	<?php 
	}else{
	?>
	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8"><div style="margin:10px;padding:10px;font=size:16px;font-weight:bold;background-color:yellow;color:red;border:red 2px dotted;border-radius:10px"><span style=""><?php echo TEXT_CONFIRM_CONDITIONS . '</span></div>'; ?>
		</div>
		<div class="col-md-2" style="padding:15px;text-align:center"><input type="checkbox" name="checkbox" value="check"  />
		</div>
		<div class="col-md-2" style="padding:15px;text-align:center"><input type="submit" name="email_submit" value="<?php echo IMAGE_BUTTON_CONTINUE;?>" class="btn btn-primary" onclick="if(!this.form.checkbox.checked){alert('<?php echo TEXT_CONFIRM_CONDITIONS;?>');return false}"  />
		</div>
	</div>
	</div>
	<?php 
	}
	?>
	<!--#######################################################################-->
	
		<div class="clearfix"></div>
		<div class="bs-stepper">
            <div class="bs-stepper-header">
			<div class="cart" data-target="#cart">
			   <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle"><i class="bi-cart3"></i></span>
                  <span class="bs-stepper-label"><?php echo HEADER_TITLE_CART_CONTENTS; ?></span>
                </button>
				</a>
              </div>
              <div class="step" data-target="#delivery">
			   <a href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step active" data-target="#payment">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#confirm">
                <button type="button" class="btn step-trigger" disabled="disabled">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span>
                </button>
              </div>
            </div>
          </div>
</form>