<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
       if($FSESSION->is_registered("account"))
	   {
			$messageStack->add('account', $FSESSION->get('account'));
	   $FSESSION->remove('account');}

		$isblocked=$FREQUEST->getvalue('isblocked');
		$suspended=$FREQUEST->getvalue('suspended');
		if($isblocked=='yes')
			$messageStack->add('account', ERROR_BLOCKED_CUSTOMER);
		if($suspended=='yes')
			$messageStack->add('account', ERROR_SUSPENDED_CUSTOMER);
				
     ?>
	<?php
	if ($messageStack->size('account') > 0) 
	{
	?>
	<div><?php echo $messageStack->output('account'); ?></div>
	<?php
	}
	?>
	<div class="container-fluid">
	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>

	<?php
	$gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $FSESSION->get("customer_id") . "'");
		$gv_count =tep_db_num_rows($gv_query);
		if ($gv_count > 0 )
		{
        $gv_result = tep_db_fetch_array($gv_query);
        $gv_amount = $gv_result['amount'] ;
		}
	?>

	<?php if($gv_amount > 0 && MODULE_ORDER_TOTAL_GV_STATUS == 'true') 
	{  
	?>
		
<div><b><?php echo MY_ACCOUNT_SEASON; ?></b></div>

					<?php
				
					$wallet_upload_query=tep_db_query("select wu.payment_method,o.orders_status_name,wu.amount,c.customers_firstname,c.customers_lastname,c.customers_id from " . TABLE_WALLET_UPLOADS . " wu,  "  . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_STATUS . " o  where c.customers_id=" . (int)$FSESSION->customer_id . " and wu.customers_id=c.customers_id and o.orders_status_id=wu.payment_status order by payment_date desc limit 3 ");

					$wallet_count=tep_db_num_rows($wallet_upload_query);
					while($wallet_upload=tep_db_fetch_array($wallet_upload_query))
					{
					?>					
					<table width="100%" cellpadding="2">
						<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" >
						<td width="20%" class="main"><?php echo $wallet_upload['customers_firstname'] . ' ' . $wallet_upload['customers_lastname'] ; ?></td>
						<td width="15%" class="main"><?php echo format_date($wallet_upload['payment_date']) ; ?></td>
						<td width="35%" class="main"><?php echo $wallet_upload['payment_method'] ; ?></td>
						<td width="15%" class="main"><?php echo $wallet_upload['orders_status_name'] ; ?></td>
						<td width="20%" class="main" align="right"><?php echo$currencies->format($wallet_upload['amount']) ; ?></td>
						</tr>
					</table>
					<?php
					}
					?>
	
				 <table cellpadding="2" width="100%">

					<tr>
						<td class="main"><?php 
								echo sprintf(MY_ACCOUNT_SEASON_TEXT, $gv_amount);
						?>
						</td>
					</tr>
				</table>	

	
	   <?php }?>

			<table cellspacing="2" cellpadding="2">
				<tr>
					<td width="6"></td>
					<td class="main"><b><?php echo MY_ACCOUNT_CURRENT_PURCHASES; ?></b></td>
				</tr>
			</table>
	

						<table width="100%" cellpadding="2">
							<tr>
								<td width="5"></td>
								<td width="50"><?php 
								echo tep_image(DIR_WS_IMAGES . 'account_orders.gif'); ?></td>
								<td width="5"></td>
								<td>
								<table width="100%" cellpadding="2">
								
								<?php  
								$amount=0;
								$products_query = tep_db_query("select distinct o.date_purchased, op.products_type,op.products_name,o.orders_id,op.final_price ,op.products_tax,op.categories_name,op.concert_venue,op.concert_date,op.concert_time,op.products_quantity from " .
								TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_id=op.orders_id  order by o.date_purchased desc limit 3");
								if(tep_db_num_rows($products_query)>0){
								while($products=tep_db_fetch_array($products_query))
								{
									if($products['products_type']=='P') 
									{
									$type=TEXT_PRODUCT;
									}
								?>
								<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $products['orders_id'].'&return=ma', 'SSL'); ?>'">
								<td class="main" valign="top">&nbsp;&nbsp;<?php echo $type; ?></td>
								<td class="main" valign="top" width="80"></td>
								<td class="main" valign="top"><?php echo $products["products_name"].'&nbsp;&nbsp;'.$products["categories_name"];?></td>
								<td class="main" valign="top" align="right"><?php echo $currencies->format(tep_add_tax($products["final_price"],$products['products_tax'])* $products["products_quantity"]); ?></td>
								<td class="main" valign="top" align="right"><?php echo '<a class="btn" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $products['orders_id'].'&return=ma', 'SSL') . '">' . tep_template_image_button_basic('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?>
								</td>
							</tr>		
							<?php 
							 } 
							}
							
							?>
							<tr>
							<td class="main" colspan="6"><?php echo ((tep_db_num_rows($products_query)==0)?TEXT_NO_ITEMS:'<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" name="subscribe"><u>' . SHOW_ALL_ORDERS . '</u></a>');?>
							</td>
							</tr>	
							</table>	
							</td>
							</tr>
						</table>
<!-- end current -->
 <!-- Start for wallet payment is installed-->
      <?php if(tep_payment_installed('wallet.php')) 
	  { ?>

		
		<table width="100%" cellpadding="2">
		  <tr>
		  		<td width="10"></td>
            <td class="main"><b><?php echo MY_ACCOUNT_WALLET; ?></b></td>
          </tr>
        </table>

			<table width="100%" cellpadding="2">
              <tr>
                <td width="5"></td>
                <td width="50"><?php echo tep_image(DIR_WS_IMAGES . 'account_wallet.gif'); ?></td>
              <td width="5"></td> 
				<td>
				
				<table width="100%" cellpadding="2">
                  <tr>
                    <td class="main">
						<?php
					
						$wallet_upload_query=tep_db_query("select wu.payment_method,o.orders_status_name,wu.amount,c.customers_firstname,c.customers_lastname,c.customers_id from " . TABLE_WALLET_UPLOADS . " wu,  "  . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_STATUS . " o  where c.customers_id=" . (int)$FSESSION->customer_id . " and wu.customers_id=c.customers_id and o.orders_status_id=wu.payment_status order by payment_date desc limit 3 ");

						$wallet_count=tep_db_num_rows($wallet_upload_query);
						while($wallet_upload=tep_db_fetch_array($wallet_upload_query))
						{
						?>					
						<table width="100%" cellpadding="2">
							<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" >
							<td width="20%" class="main"><?php echo $wallet_upload['customers_firstname'] . ' ' . $wallet_upload['customers_lastname'] ; ?></td>
							<td width="15%" class="main"><?php echo format_date($wallet_upload['payment_date']) ; ?></td>
							<td width="35%" class="main"><?php echo $wallet_upload['payment_method'] ; ?></td>
							<td width="15%" class="main"><?php echo $wallet_upload['orders_status_name'] ; ?></td>
							<td width="20%" class="main" align="right"><?php echo$currencies->format($wallet_upload['amount']) ; ?></td>
							</tr>
						</table>
						<?php
						}
						?>
					</td>
                  </tr>
				   	<tr>
					<table cellpadding="2" width="100%">
					 	<tr>
							<td class="main" width="30%">
								<?php 
									if ($wallet_count>0) {
										echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_WALLET_UPLOADS, '', 'SSL') . '">' .  MY_ACCOUNT_SHOW_ALL_UPLOADS . '</a>'; 
									}
								?>
							</td>
						</tr>
					    	<td class="main"><?php echo  tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_WALLET_CHECKOUT_PAYMENT, '', 'SSL') . '">' . MY_ACCOUNT_WALLET_UPLOADS . '</a>'; ?></td>
						</tr>
						<tr>
							<td class="main"><?php 
							$balance=tep_get_wallet_balance($FSESSION->customer_id);
							$pending_balance=tep_get_pending_wallet_balance($FSESSION->customer_id);
							if ($balance>0){
								echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') .  " " .sprintf(TEXT_CURRENT_WALLET_BALANCE,$currencies->format($balance));
							} else {
								echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') .'&nbsp;'. TEXT_WALLET_EMPTY;
							}	
							if ($pending_balance>0){
								echo ' ('.TEXT_PENDING_WALLET_BALANCE.' '.$currencies->format($pending_balance).')';
							}
							?>
							</td>
						</tr>
					</table>	
                  </tr>
                </table>
				
				</td>
              </tr>
            </table>
			
	   <?php 
	   }
	   ?>
	  <!-- End for wallet payment is installed-->
	 
	<!-- order-->
	<?php
	if (tep_count_customer_orders() > 0) 
	{
	?>
	
			<table cellpadding="2">
				<tr>
					<td width="3"></td>
					<td class="main"><b><?php echo MY_ORDERS_TITLE; ?></b></td>
				</tr>
			</table>


						<table width="100%" cellpadding="2">
							<tr>
								<td width="50" align="middle"><?php echo tep_image(DIR_WS_IMAGES . 'account_orders.gif'); ?></td>
								<td width="5"></td>
								<td>
									<table width="100%" cellpadding="2">
									<?php
									$orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, "  .  TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$FSESSION->customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$FSESSION->languages_id . "' order by orders_id desc limit 3");
									while ($orders = tep_db_fetch_array($orders_query)) 
									{
										if (tep_not_null($orders['delivery_name'])) 
										{
											$order_name = $orders['delivery_name'];
											$order_country = $orders['delivery_country'];
										} else 
										{
											$order_name = $orders['billing_name'];
											$order_country = $orders['billing_country'];
										}
									?>
							
							<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'].'&return=hinfo', 'SSL'); ?>'">
										<td class="main" valign="top" width="80">&nbsp;&nbsp;<?php echo format_date($orders['date_purchased']); ?></td>
										<td class="main" valign="top"><?php echo '#' . $orders['orders_id']; ?></td>
										<td class="main" valign="top"><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
										<td class="main" valign="top"><?php echo $orders['orders_status_name']; ?></td>
										<td class="main" valign="top" align="right"><?php echo $orders['order_total']; ?></td>
										<td class="main" valign="top" align="right"><?php echo '<a class="btn" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'].'&return=hinfo', 'SSL') . '">' . tep_template_image_button_basic('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
									</tr> 
									<?php
									}
									?>
									<tr>
										<td class="main" colspan="6"><?php 
										echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '"><u>' . OVERVIEW_SHOW_ALL_ORDERS . '</u></a>'; ?></td>
									</tr>
									</table>
							</td>
						</tr>
					</table>
	<?php
	}
	?>
<!-- end order -->
		<table width="100%" cellpadding="2">
         <tr>
          	<td width="3"></td>
          	<td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
		</tr>
        </table>

			
			<table width="100%" cellpadding="2">
              <tr>
              	 <td width="5"></td>
                <td width="50"><?php echo tep_image(DIR_WS_IMAGES . 'account_personal.gif'); ?></td>
                <td width="5"></td>
                <td>
				
				<table width="100%" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></td>
                  </tr>
				 <?php if (HIDE_DATA_PROTECT=='false'){
					  
					  ?>
				<tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link('account_data.php', '', 'SSL') . '">' . MY_ACCOUNT_DATA . '</a>'; ?></td>
                  </tr>
				 <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link('account_close.php', '', 'SSL') . '"><span class="btn btn-primary btn-lg">' . MY_ACCOUNT_CLOSE . '</span></a>'; ?></td>
					<?php
				  }
					?>
								  
                </table>
				<table>
				<?php  if (ACCOUNT_NEWSLETTER=='true') {
				?>
                  <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></td>
                  </tr>
				  <?php } ?>
                  <tr>
				</table>
				
				</td>
              </tr>
            </table>
	
		</div><br>