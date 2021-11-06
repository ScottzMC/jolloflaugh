<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
{
$hba='true';
}else{
$hba='none';	
}
if ($order->info['shipping_module']== 'willcall_willcall'){
$willcall='yes';	
}else{
$willcall='no';	
}
?>
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>
	<?php
	if ($messageStack->size('security') > 0) 
	{
	?>
	<div><?php echo $messageStack->output('security'); ?></div>
	<?php
	} 
	?>

	<div class="container">
    <div class="card mb-4 box-shadow">
    <div class="card-header">
	<?php
	if (sizeof($order->info['tax_groups']) > 1) 
	{
	?>
	<?php echo '<strong>' . HEADING_PRODUCTS . '</strong>&nbsp;
	<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">&nbsp;<span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
				 	
	<!--<b><?php //echo HEADING_TAX; ?></b>
	<b><?php //echo HEADING_TOTAL; ?></b>-->

	<?php
	} 
	else 
	{
	?>
	<?php echo '<strong>' . HEADING_PRODUCTS . '</strong>&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">
	<span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
<?php
	}
?>
	</div>

	
	<div class="card-body">
	<table width="100%" class="table-hover">
	<tbody>

<?php


	$dis_time_format="";	
	if(defined('TIME_FORMAT')) $dis_time_format=TIME_FORMAT;  
  
	//print_r ($order->products);
	for ($i=0, $n=count($order->products); $i<$n; $i++) 
	{ 
			$qty=$order->products[$i]['qty'];
			$name=$order->products[$i]['name'];
			$price=$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
			
			if ($order->products[$i]['sku'] == 6)
			{
			$qty = 1;
			}
		######################################################
		$id=tep_db_input($order->products[$i]['id']);
		require_once('includes/functions/categories_lookup.php');
		#####################################################
		$family= '';
        // call the new function
        $type = $products[$i]['product_type'];
        
        list($heading_name, $heading_venue,  $heading_date, $heading_time) = categories_lookup();

		if ($type=='F')
		{
		$family= '<small>'.FAMILY_TICKET.' x '.FAMILY_TICKET_QTY.'</small>';	
		}else
		{
		$family= '';	
		}
		######################################################		
     
		echo '          <tr>' . "\n" .
         '            <td style=" vertical-align: text-top;text-align:center" width="25%">
						<span class="">' . $qty . '&nbsp;x&nbsp;</span></td>' . "\n" . 
         '            <td width="65%" style=" vertical-align: text-top;">' . 
		 $order->products[$i]['name'] . ' <br>
		 <span class="smallText">' . 
		 $heading_name . '<br> ' .  
		 $heading_venue . ' ' .  
		 $heading_date . ' ' .  
		 $heading_time . ' <br>' . $family . '</span>';
		 
		if ($order->products[$i]['element_type']=="V")
		{
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
			if($order->products[$i]['others']['end_time']) 
			{
				$etime=date("h:i A",strtotime($order->products[$i]['others']['end_time']));
				if($dis_time_format!="") 
				{
					if($dis_time_format=='24')
						$etime=date("H:i",strtotime($order->products[$i]['others']['end_time']));
				}
			}
			echo '<br><small>&nbsp;	- '.TEXT_START_DATE.' : '. format_date($order->products[$i]['others']['start_date']) . '&nbsp;' . $stime . ' <br>&nbsp; - ' . TEXT_END_DATE .' : ' . format_date($order->products[$i]['others']['end_date']). '&nbsp;' . $etime . ' '.(($order->products[$i]['others']['resource_id'])? '<br>&nbsp; - '.tep_get_resource_name($order->products[$i]['others']['resource_id'],'resource_name').'('.$currencies->format(tep_add_tax($order->products[$i]['others']['resource_costs'],$order->products[$i]['tax'])).')':'').'</small>';
		} 
		if (STOCK_CHECK == 'true') 
		{
			echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
		}

	 	echo isset($order->products[$i]['discount_whole_text'])?'<br>' . $order->products[$i]['discount_whole_text']:'';

		echo '</td>' . "\n";
		if (sizeof($order->info['tax_groups']) > 1) 
			echo '<td class="ot" style="vertical-align: text-top;text-align:right">&nbsp;'.TEXT_INC.'&nbsp;' . tep_display_tax_value($order->products[$i]['tax']) . '% &nbsp;</td>' . "\n";
		echo '            <td style="vertical-align: text-top;">&nbsp;' . $price . '&nbsp;</td>' . "\n" .
			 '          </tr>' . "\n";
	}
?>
	</tbody>
	</table>
	
	<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
				</div>
				<div class="col-md-6">
				<table width="100%">
				<?php
					if (MODULE_ORDER_TOTAL_INSTALLED) 
					{
					$order_total_modules->process();
					echo $order_total_modules->output();
					}
				?>
				</table>
				</div>
			</div>
    </div>
	</div>
	<div class="card-deck text-left">
    <?php
    if ($FSESSION->get('sendto') != false) 
	{
      ?>
		<?php if (HIDE_DELIVERY_ADDRESS == 'no')
		{
			if ($order->info['shipping_method']) 
			{
		?>
			<div class="card mb-4 box-shadow">
				<div class="card-header">
				<?php echo '<strong>' . HEADING_DELIVERY_ADDRESS . '</strong>&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
				</div>
				<div class="card-body">
				<?php //echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); 

				$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_customer_email as customer_email, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$FSESSION->get('billto') . "'");
						
						$addresses = tep_db_fetch_array($addresses_query);
						$format_id = tep_get_address_format_id($addresses['country_id']);
						echo tep_address_format($format_id, $addresses, true, '<br>', ', ');
						//box office address tweak 
						if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
						{
						 $_SESSION['billto_array'] = $_SESSION['sendto_array']= $addresses; 
						}
				?>
				</div>
			</div>
			  <?php
			}
		}
	}
    ?>
    </div>
	
	<?php
	if ($order->info['shipping_method']) 
	{
	?>
    <div class="card box-shadow">

		<div class="card-header">
		<?php echo '<strong>' . HEADING_SHIPPING_METHOD . '</strong>&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING) . '">
		<span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
		</div>
		<div class="card-body">
		<?php echo $order->info['shipping_method']; ?>
		</div>
	</div>
	<?php
	}
	?>
	</div>
	
	<div class="container" style="padding:0">
  <div class="row no-gutters">
		<div class="col-md-6">
		<div class="card box-shadow">
		<div class="card-header">
		<?php echo '<strong>' . HEADING_BILLING_ADDRESS . '</strong>&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS) . '"><span style="display:'.$hba.'" class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
		</div>
        <div class="card-body"><?php
				 	$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_customer_email as customer_email, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$FSESSION->get('billto') . "'");
					
					$addresses = tep_db_fetch_array($addresses_query);
					$format_id = tep_get_address_format_id($addresses['country_id']);
					echo tep_address_format($format_id, $addresses, true, '<br>', ', ');
					//box office address tweak 
					if (($_SESSION['BoxOffice']== 999)or($_SESSION['customer_country_id']==999))
					{
					
					$_SESSION['billto_array'] = $_SESSION['sendto_array']= $addresses; 
					}
					?>
        <?php //echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
        </div>
    </div>
		</div>
		<div class="col-md-6">
		 <div class="card box-shadow">
        <div class="card-header">
		<?php echo '<strong>' . HEADING_PAYMENT_METHOD . '</strong>&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT) . '">
		<span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
		</div>
        <div class="card-body">
          <?php //echo $order->info['payment_method']; 
		  
			if ($order->info['payment_method']=="moneyorder")
			{
			echo MODULE_PAYMENT_MONEYORDER_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="reservation")
			{
			echo MODULE_PAYMENT_RESERVATION_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="boxoffice")
			{
			echo MODULE_PAYMENT_BOXOFFICE_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="bor")
			{
			echo MODULE_PAYMENT_BOR_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="cod")
			{
			echo MODULE_PAYMENT_COD_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="free")
			{
			echo MODULE_PAYMENT_FREE_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="stripesca")
			{
			echo MODULE_PAYMENT_STRIPESCA_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="stripepay")
			{
			echo MODULE_PAYMENT_STRIPEPAY_DISPLAY_NAME;
			//echo "<br>";
			//echo MODULE_PAYMENT_STRIPEPAY_DISPLAY_IMAGE;
			}elseif ($order->info['payment_method']=="bank_transfer")
			{
		  
			echo MODULE_PAYMENT_BANK_TRANSFER_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="paypal_api")
			{
			echo MODULE_PAYMENT_PAYPAL_API_DISPLAY_NAME;
			}elseif ($order->info['payment_method']=="wallet")
			{
			echo MODULE_PAYMENT_WALLET_DISPLAY_NAME;
			}else
			{
			echo $order->info['payment_method']; 
			}
		?>
        </div>
    </div>
		</div>
	</div>
</div>
<?php

	$form_action=$GLOBALS[$FSESSION->payment];
	if ($form_action->form_action_url!='' && (!$FSESSION->is_registered('cc_id') || $order->info['total']>0)) 
	{
		$form_action_url = $form_action->form_action_url;
	} else 
	{
		$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS,'', 'SSL');
	}
	echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
	
  if (is_array($payment_modules->modules)) 
  {
    if ($confirmation = $payment_modules->confirmation()) 
	{
    $FSESSION->set('payment_info',$confirmation['title']);
?>
		<hr>
		<h2 class="h3"><?php echo HEADING_PAYMENT_INFORMATION; ?></h2>
		<div class="row">
<?php
		if (tep_not_null($confirmation['title'])) 
		{
			echo '<div class="col-sm-6">';
			//echo '<div class="alert text-danger">';
			echo '<div class="alert payment-info">';
			echo $confirmation['title'];
			echo '</div>';
			echo '</div>';
		}
?>
<?php
		if (isset($confirmation['fields'])) 
		{
			echo '<div class="col-sm-6">';
			echo '  <div class="alert payment-info">';
			$fields = '';
			for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) 
			{
			  $fields .= $confirmation['fields'][$i]['title'] . ' ' . $confirmation['fields'][$i]['field'] . '<br>';
			}
			if (strlen($fields) > 4) echo substr($fields,0,-4);
			echo '  </div>';
			echo '</div>';
		}
?>
  </div>
  <div class="clearfix"></div>
	  
<?php 
    }
  }	
?>
	<!--comments-->
	<?php
	if (tep_not_null($comments)) 
	{
	echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>
	
	<!--<h2 class="h3"><?php //echo '<strong>' . HEADING_ORDER_COMMENTS . '</strong>' . tep_draw_button(TEXT_EDIT, 'fa fa-edit', tep_href_link('checkout_payment.php', '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></h2>-->
	<blockquote>
	<?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
	</blockquote>
	<?php
	}
	?>


<!--Add Extra Fields begin-->
	<?php // start of extra fields
	//only display title if at least one of the fields holds data
	if (tep_not_null($field_1) || tep_not_null($field_2) || tep_not_null($field_3) || tep_not_null($field_4) || tep_not_null($field_5) || tep_not_null($field_6) || tep_not_null($field_7) || tep_not_null($field_8) || tep_not_null($field_9) || tep_not_null($field_10) || tep_not_null($field_11) || tep_not_null($field_12) || tep_not_null($field_13) || tep_not_null($field_14) || tep_not_null($field_15) || tep_not_null($field_16)|| tep_not_null($field_17)|| tep_not_null($field_18)|| tep_not_null($field_19)|| tep_not_null($field_20)) {
	?>
	<?php echo '<b>'.NEW_FIELDS_HEADING.'</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
	<?php } //end of title display - now display the lines that hold data ?>

  
	<table width="100%" cellpadding="2">
	<?php
	if (tep_not_null($field_1) ){?>
	<tr>
	<td width="10%" class="main" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_1; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_1'])) . tep_draw_hidden_field('field_1', $order->info['field_1']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_2) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_2; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_2'])) . tep_draw_hidden_field('field_2', $order->info['field_2']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_3) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_3; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_3'])) . tep_draw_hidden_field('field_3', $order->info['field_3']); ?></td>
	</tr> 
	<?php
	}
	if (tep_not_null($field_4) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_4; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_4'])) . tep_draw_hidden_field('field_4', $order->info['field_4']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($other) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_5; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['other'])) . tep_draw_hidden_field('other', $order->info['other']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_6) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_6; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_6'])) . tep_draw_hidden_field('field_6', $order->info['field_6']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_7) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_7; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_7'])) . tep_draw_hidden_field('field_7', $order->info['field_7']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_8) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_8; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_8'])) . tep_draw_hidden_field('field_8', $order->info['field_8']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_9) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_9; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_9'])) . tep_draw_hidden_field('field_9', $order->info['field_9']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_10) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_10; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_10'])) . tep_draw_hidden_field('field_10', $order->info['field_10']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_11) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_11; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_11'])) . tep_draw_hidden_field('field_11', $order->info['field_11']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_12) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_12; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_12'])) . tep_draw_hidden_field('field_12', $order->info['field_12']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_13) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_13; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_13'])) . tep_draw_hidden_field('field_13', $order->info['field_13']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_14) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_14; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_14'])) . tep_draw_hidden_field('field_14', $order->info['field_14']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_15) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_15; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_15'])) . tep_draw_hidden_field('field_15', $order->info['field_15']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_16) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_16; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_16'])) . tep_draw_hidden_field('field_16', $order->info['field_16']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_17) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_17; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_17'])) . tep_draw_hidden_field('field_17', $order->info['field_17']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_18) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_18; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_18'])) . tep_draw_hidden_field('field_18', $order->info['field_18']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_19) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_19; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_19'])) . tep_draw_hidden_field('field_19', $order->info['field_19']); ?></td>
	</tr>
	<?php
	}
	if (tep_not_null($field_20) ){?>
	<tr>
	<td width="10%" class="main" colspan="2" nowrap>&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo FIELD_20; ?></b>&nbsp;</td>
	<td class="main">&nbsp;<?php echo nl2br(tep_output_string_protected($order->info['field_20'])) . tep_draw_hidden_field('field_20', $order->info['field_20']); ?></td>
	</tr>
	<?php
	}
?>
	</table><!--Add Extra Fields end-->

  <?php
	if (is_array($payment_modules->modules)) 
	{
		$val = $payment_modules->process_button();
		$values = '';
		for($i=0;$i<strlen($val);$i++)
		{
			$values .= ord($val{$i}) . ",";
		}
		echo "<script language='javascript'>";
		echo " var hidden_values='" . $values . "';";
		echo "</script>";
	}
	echo '<div id="values"></div>';
	  
	if (!$USER_BARRED) 
	{ 
	 ?>
	 	  
	<style>
	.confirm{float:right;/* position:relative;top:-400px; */}
	@media (max-width: 480px) {
	.confirm{float:right;/* position:relative;top:0px; */}
	}
	</style>
		<?php
		if (ADD_CONDITIONS == 'true') 
		{
		echo "<div id='confirm_button' class='confirm'>" . tep_template_image_confirm('', IMAGE_BUTTON_CONFIRM_ORDER, '  data-loading-text="'.IMAGE_BUTTON_PROCESS_ORDER.'" id="checkout_button"  disabled="disabled"') . '</div>';
		//  Bootstrap enable/disable the button on checkbox
		?>



	<!--Add Conditions-->
			<br class="clearfloat">
			<div class="confirm">
			<b><?php echo CONDITION_AGREEMENT; ?></b>
			<?php echo tep_draw_checkbox_field('agree','true', false, 'id="checkout_agree"'); ?></div>
			<br class="clearfloat">
			<div class="confirm"><a class="button" data-bs-toggle="modal" data-bs-target="#myTandC"><?php echo CONDITIONS; ?></a></div>

<!-- Modal -->
<div class="modal" tabindex="-1" id="myTandC">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="tandc_modal">
      <div class="modal-header">
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      	<div class="modal-body">
		<h2><?php echo CONDITIONS; ?></h2>
		<?php   
		$content_query=tep_db_query("select description from main_page_description where page_name='TandC'");
		$content_result = tep_db_fetch_array($content_query);
		if($content_result)
		{
		echo $content_result['description'];
		}else
		{
		?>
		<p><strong><?php echo HEADING_TITLE ?></strong></p>
		<p>
		<?php echo TEXT_POPUP_CONDITIONS ?></p>
		<p>
		<?php echo TEXT_POPUP_CONDITIONS_GUIDE ?></p>
		<?php 
		}
		?>
		</div>
      <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo TEXT_CLOSE ?></button>
      </div>
    </div>
  </div>
</div>
	<!-- eof Add Conditions-->
	<?php  
		}else
			{
			// normal button added id here
			echo "<span id='confirm_button' class='confirm' style='display:true;'>" . tep_template_image_confirm('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER, '  data-loading-text="'.IMAGE_BUTTON_PROCESS_ORDER.'" id="checkout_button"') . '</span>';
			}
	} //eof user barred
	?>

	  <div class="clearfix"></div>
	  
	  <div class="bs-stepper">
            <div class="bs-stepper-header">
              <div class="step" data-target="#delivery">
			   <a href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#payment">
			  <a href="<?php echo tep_href_link('checkout_payment.php', '', 'SSL'); ?>">
                <button type="button" class="btn step-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
				</a>
              </div>
              <div class="line"></div>
              <div class="step active" data-target="#confirm">
                <button type="button" class="btn step-trigger" disabled="disabled">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span>
                </button>
              </div>
            </div>
          </div>


	</form>
	</div>
	<script>
	$(document).ready(function() {
	if(typeof(hidden_values)!='undefined'){
		hidden_values = hidden_values.split(",");
		var content = ''; 
		if(hidden_values.length>1){
		
		   for(i=0;i<hidden_values.length;i++){
			  content += String.fromCharCode(hidden_values[i]);
			  } 
		}
		//document.getElementById('values').innerHTML = '1'+content;
		$("form[name=checkout_confirmation]").prepend(content);
		document.getElementById('confirm_button').style.display = '';
	}
			
	});
	</script>