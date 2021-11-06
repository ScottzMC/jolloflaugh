<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>
<div class="container">
	<div class="card mb-4 box-shadow">
		<div class="card-header"><strong>
		<?php echo sprintf(HEADING_ORDER_NUMBER, $FREQUEST->getvalue('order_id')) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?>  
		</strong>
		</div>
		<div class="card-body">
        <table width="100%" cellspacing="0" cellpadding="2" class="table-hover">
		<?php
		if (sizeof($order->info['tax_groups']) > 1) 
		{
		?>
		<tr>
			<td colspan="2"><strong><?php echo HEADING_PRODUCTS; ?></strong></td>
			<td align="right"><strong><?php echo HEADING_TAX; ?></strong></td>
			<td align="right"><strong><?php echo HEADING_TOTAL; ?></strong></td>
		</tr>
		<?php
		} else 
		{
		?>
		<tr>
			<td colspan="2"><strong><?php echo HEADING_PRODUCTS; ?></strong></td>
			<td align="right"><strong><?php echo HEADING_TOTAL; ?></strong></td>
		</tr>
		<?php
		}
		$products_name="";
		$products = array();
		$products_names_p="";
		$pre_qty="";
		$pre_name="";
		$qty_p = "";
		$pre_sign="";
		$first_event = true;
		$first_event_id = 0;
		$jj=0;
		$order_is_printable = 0;
		for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) 
		{
			$order_is_printable = $order_is_printable + $order->products[$i]['is_printable'];
			$products_names_p="";
			$qty_p ="";
			$sign_p="";
			
			$products_names_p = $order->products[$i]['name'];
			$qty_p=$order->products[$i]['qty'];
			$sign_p="X";
			$rax_total=tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'];
			$sku = $order->products[$i]['sku'];
			$event_id = 0;

			$products[] = array( "qty" => $qty_p,
							 "sign"  => $sign_p,
							 "id" => $order->products[$i]['id'],
							 "products_type" => $order->products[$i]['element_type'],
							 "events_type" => $order->products[$i]['events_type'],
							 "p_name"	 => $products_names_p,
							 "event_id" => $event_id,
							 "sku" => $sku,
							 "categories_name" => $heading_name,
							 "concert_venue" => $heading_venue,
							 "concert_date" => $heading_date,
							 "concert_time" => $heading_time,
							 "row_total"=>$rax_total
						   );
	    }
		$dis_time_format="";	
		if(defined('TIME_FORMAT'))
		{
			$dis_time_format=TIME_FORMAT;
		}
		for ($i = 0, $n = sizeof($products); $i < $n; $i++) 
		{
	   		if($products[$i]['products_type']=="P")
			{
				$row_total= $products[$i]['row_total'];			
			}else if($products[$i]['products_type']=="V")
			{
			    $row_total= $products[$i]['row_total'];
			}
			$discount=(isset($order->products[$i]['discount_whole_text']) && $order->products[$i]['discount_whole_text']!='')?'<br>' . $order->products[$i]['discount_whole_text']:'';
			$event_id = $products[$i]['event_id'];
			$heading_name = $order->products[$i]['categories_name'];
			$heading_venue = $order->products[$i]['concert_venue'];
			$heading_date = $order->products[$i]['concert_date'];
			$heading_time = $order->products[$i]['concert_time'];
			$events_type = $order->products[$i]['events_type'];//product_type
			
			if ($events_type=='F')
			{
			$family= '<small>'.FAMILY_TICKET.' x '.FAMILY_TICKET_QTY.'</small>';
			}else
			{
			$family= '';	
			}
			
			echo '          <tr>' . "\n" .
			'            <td class="main" align="right" valign="top" width="30">' . 
			$products[$i]['qty'] . "&nbsp;&nbsp;" . $products[$i]['sign'] .'&nbsp;&nbsp;</td>' . "\n" .
			'            <td class="main" valign="top">' .
			$heading_name . ' &nbsp;&nbsp; ' . 
			$products[$i]['p_name'] . '  ' .
			$heading_date  . ' ' .
			$heading_venue . ' ' .
			$heading_time .' <br>'. $family . 
			$discount;
			

			
			if($order->products[$i]['sku']!="")
			{
			echo '</td>' . "\n";
			}
			if (sizeof($order->info['tax_groups']) > 1) 
			{
			echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
			}
			echo '<td class="main" align="right" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;' . $currencies->format($row_total, true, $order->info['currency'], $order->info['currency_value']) . '</td></tr>' . "\n" ;
		}
		?>
		</table>
		</div>
		
			<table width="100%" cellpadding="2" cellspacing="15">
			<?php
			for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) 
			{
			echo '              <tr>' . "\n" .
			'                <td class="main" align="right" width="80%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
			'                <td class="main" align="right" width="20%">' . $order->totals[$i]['text'] . '&nbsp;&nbsp;</td>' . "\n" .
			'              </tr>' . "\n";
			} 
			?>
			</table>
		<div class="card-header">
		<span class="pull-right hidden-xs"><?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?></span>
		<?php
		// if(EVENTS_DATE_FORMAT=='d-m-Y')
		//{
		// $new_date = utf8_encode(strftime('%A %d %B, %Y', strtotime($order->info['date_purchased'])));
		// }else{
		// $new_date = utf8_encode(strftime('%A %B %d, %Y', strtotime($order->info['date_purchased'])));
		// }
		$date_purchased = strtotime($order->info['date_purchased']);
		$new_date= utf8_encode(strftime((DATE_FORMAT_LONG), $date_purchased));
		echo HEADING_ORDER_DATE . ' ' . $new_date; 
		?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
	<div class="container">
	<div class="card-deck mb-3 text-left">
	<?php
    if ($order->delivery != false)
	{
      ?>
        <div class="card mb-4 box-shadow">
			<div class="card-header">
			<h4 class="my-0 font-weight-normal"><?php echo HEADING_DELIVERY_ADDRESS; ?></h4>
			</div>
			<div class="card-body">
			<?php 
			if(($order->delivery['format_id'])>0)
			{
			?>
			<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?>
			<?php } else { ?>
			<?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, ' ', '<br />'); ?>
			<?php		 } ?>		
			</div>
		</div>
	<?php
	}
	?>
        <div class="card mb-4 box-shadow">
			<div class="card-header">
			<h4 class="my-0 font-weight-normal"><?php echo HEADING_BILLING_ADDRESS; ?></h4>
			</div>
			<div class="card-body">
			 <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
			</div>
        </div>
		<?php
		if (tep_not_null($order->info['shipping_method'])) 
		{
		?>
		<div class="card mb-4 box-shadow">
		<div class="card-header">
		<h4 class="my-0 font-weight-normal"><?php echo HEADING_SHIPPING_METHOD; ?></h4>
		</div>
		<div class="card-body">
		<?php echo $order->info['shipping_method']; ?>
		</div>
		</div>
		<?php
		}
		?>
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal"><?php echo HEADING_PAYMENT_METHOD; ?></h4>
          </div>
          <div class="card-body">
             <?php echo $order->info['payment_method']; ?>
          </div>
        </div>
      </div>
	  </div>
  <hr>
  <!-- start pdf //-->
    <?php 
		$target=" target='_blank'";
		$delivered_query = tep_db_query("select osh.orders_status_history_id, osh.orders_status_id from " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '". (int)$FREQUEST->getvalue('order_id') . "' order by orders_status_history_id desc limit 1");
		$delivered_status = tep_db_fetch_array($delivered_query);
	if (E_TICKETS == 'true')
	{
		if (($order_is_printable > 0) && ($delivered_status['orders_status_id'] == E_TICKET_STATUS) && 
		(DISPLAY_PDF_DELIVERED_ONLY=='true'))
		{
			?>
			<div class="container">
			<div class="row">
			  <div class="col-lg-7 text-center text-lg-left">
				<h3 class="cta-title"><?php echo PDF_INVOICE; ?></h3>
				<p class="cta-text"><?php echo tep_image(DIR_WS_IMAGES . 'pdf.gif', PRINT_PDF_TICKETS,'','','') . sprintf(PDF_DOWNLOAD_LINK,
				tep_href_link(FILENAME_CUSTOMER_PDF, 'oID=' . $FREQUEST->getvalue('order_id') , 'SSL')); ?>
				</p>	
			  </div>
			  <div class="col-lg-4 cta-btn-container text-center">
				<form>
					<input
						type="button"
						class="btn btn-primary btn-lg"
						onclick="location.href='<?php echo tep_href_link(FILENAME_CUSTOMER_PDF, 'oID=' . $FREQUEST->getvalue('order_id') , 'SSL'); ?>'"
						value="<?php echo GET_TICKETS_HERE; ?>"
						style="font-size: 18px; color:white; padding: 10px;">
					<br><?php echo GET_ADOBE_READER; ?>
					<p class="cta-text"><a href="https://get.adobe.com/reader/" target="_blank"><?php echo HERE; ?></a></p>
				</form>
			  </div>
			</div>
			</div>
		<?php 
		} else 
		{
		echo "<div><p class='red'>" . NO_ETICKETS . "</p></div>";
		}  
	}
		?>
	<!-- end pdf //-->
	<hr>
	
	
	    <?php 

		$livestream_query = tep_db_query("select products_model from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '". (int)$FREQUEST->getvalue('order_id') . "' order by orders_products_id desc limit 1");
		$livestream_status = tep_db_fetch_array($livestream_query);
	if (VIDEO == 'true')
	{
	if (($order_is_printable >= 0) && ($livestream_status['products_model']==LIVESTREAM_DATE_ID))
		{
			?>
			<div class="container">
			<div class="row">
			  <div class="col-lg-7 text-center text-lg-left">
				<h3 class="cta-title"><?php  echo LIVESTREAM_TITLE; ?></h3>
				<?php  echo ACCOUNT_MESSAGE_YES; ?>
				<br><br>
			  </div>
			  <div class="col-lg-5 cta-btn-container text-center">
			  <?php
			  //Alt. Livestream...no  channel  no  show
				if(CHANNEL_LINK !=''){
				echo '<div style="padding:5px"><p>';
				echo '<a class="venobox btn btn-primary btn-lg" data-autoplay="true" data-vbtype="video" href="'.LIVESTREAM_URL.'">Live Stream</a>';
				//if(CHANNEL_LINK !=''){
				echo '&nbsp;<a class="venobox btn btn-primary btn-lg" data-autoplay="true" data-vbtype="video" href="'.CHANNEL_LINK.'">Channel</a>';
				//}
				echo '</p></div>';
				}
			  //YouTube...no  channel  no  show
				if(YOUTUBE_CHANNEL_ID !='')
				{
				echo '<div style="padding:5px"><p>';
				echo '<a class="venobox btn btn-primary btn-lg" data-autoplay="true" data-vbtype="video" href="https://www.youtube.com/embed/live_stream?channel='.YOUTUBE_CHANNEL_ID.'">YouTube Live Stream</a>';
				//if(YOUTUBE_LINK !=''){
				echo '&nbsp;<a class="venobox btn btn-primary btn-lg" data-autoplay="true" data-vbtype="video" href="'.YOUTUBE_LINK.'">YouTube</a>';
				//}
				echo '</p></div>';
				}
			?>
			  </div>
			</div>
			</div>
		<?php 
		} else 
		{
		echo "<div><p class='red'>".ACCOUNT_MESSAGE_NO."</p></div>";
		}  
	}
		?>
			
			<?php	
			//echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/live_stream?channel='.CHANNEL_ID.'" frameborder="0" allowfullscreen></iframe>';
			//echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.YOUTUBE_LINK.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
			
				?>
		<div class="clearfix"></div>
			<div class="card mb-4 box-shadow">
				<div class="card-header">
				<h4><?php echo HEADING_ORDER_HISTORY; ?></h4>
				</div>
						 <!-- <table colspan="3" width="100%" cellspacing="2" cellpadding="2">
							<tr>
								<td class="main">
									<b><?php //echo HEADING_ORDER_DATE; ?></b>
								</td>
								<td class="main">
									<b><?php //echo HEADING_PAYMENT_STATUS; ?></b>
								</td>
								<td class="main">
									<b><?php //echo NEW_FIELDS_HEADING; ?></b>
								</td>
							</tr>-->		
<div class="card-body">
    <ul class="timeline list-unstyled">
	<?php
  $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments, osh.field_1, osh.field_2, osh.field_3, osh.field_4, osh.other from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . $FREQUEST->getvalue('order_id','int','0') . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$FSESSION->languages_id . "' order by osh.date_added");
	while ($statuses = tep_db_fetch_array($statuses_query)) 
	{
		if (EXTRA_FIELDS == 'yes')
		{
		echo '              <table><tr>' . "\n" .
			 '                <td class="main" valign="top" width="25%">' . format_date($statuses['date_added']) . '</td>' . "\n" .
			 '                <td class="main" valign="top" width="25%">' . $statuses['orders_status_name'] . '</td>' . "\n" .
			 '                <td class="main" valign="top">'
			  . (empty($statuses['comments']) ? '&nbsp;' : '' . HEADING_COMMENT . ':&nbsp;'.nl2br(tep_output_string($statuses['comments']))) . '<br>'
			  . (empty($statuses['field_1']) ? '&nbsp;' : '' . FIELD_1 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_1']))) .'<br>'
			  . (empty($statuses['field_2']) ? '&nbsp;' : '' . FIELD_2 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_2']))) .'<br>'
			  . (empty($statuses['field_3']) ? '&nbsp;' : '' . FIELD_3 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_3']))) .'<br>'
			  . (empty($statuses['field_4']) ? '&nbsp;' : '' . FIELD_4 . ':&nbsp;'.nl2br(tep_output_string($statuses['field_4']))) .'<br>'
			  . (empty($statuses['other']) ? '&nbsp;' : '' . FIELD_5 . ':&nbsp;'.nl2br(tep_output_string($statuses['other']))) . '<br></td>' . "\n" .
			 '              </tr></table>' . "\n";
		}else
		{
		echo '<li>';
			echo '  <div class="timeline-badge"><i class="fa fa-check-square"></i></div>';
			echo '  <div class="timeline-panel">';
			echo '    <div class="timeline-heading">';
			echo '      <p class="pull-right"><small class="text-muted"><i class="fa fa-clock-o"></i> ' . tep_date_short($statuses['date_added']) . '</small></p><h2 class="h3 timeline-title">' . $statuses['orders_status_name'] . '</h2>';
			echo '    </div>';
			echo '    <div class="timeline-body">';
			echo '      <p>' . (empty($statuses['comments']) ? '&nbsp;' : '<blockquote>' . nl2br(tep_output_string_protected($statuses['comments'])) . '</blockquote>') . '</p>';
			echo '    </div>';
			echo '  </div>';
			echo '</li>';
		}
	}
	?>    
	</ul>
</div>
</div>
<div class="clearfix"></div>




<?php
  $order_status_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . $FREQUEST->getvalue('order_id','int','0') . "'");
  $order_status = tep_db_fetch_array($order_status_query); 	
  if(($order_status['orders_status'])>1){
	  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php');
  }	  
?>

	<div class="buttonSet">
	<?php //echo tep_draw_button(IMAGE_BUTTON_BACK, 'fa fa-angle-left', tep_href_link('account_history_info.php', tep_get_all_get_params(array('order_id')), 'SSL')); ?>

	<?php echo '<a href="' . tep_href_link((FILENAME_ACCOUNT), tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
	</div>
	<div class="clearfix"></div>
	<div class="container">
	<div class="row">
	<div class="col-lg-9 text-center text-lg-left">
	</div>
	
	<div>
		<!-- start invoice pdf //-->
<?php

	$pdfdelivered_query = tep_db_query("select osh.orders_status_history_id, osh.orders_status_id from " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '". (int)$FREQUEST->getvalue('order_id') . "' order by orders_status_history_id desc limit 1");
		$pdfdelivered_status = tep_db_fetch_array($pdfdelivered_query);
    
    if ($pdfdelivered_status['orders_status_id'] == 3 && DISPLAY_PDF_DELIVERED_ONLY == 'true' ){
	echo '<a class="pull-right" target="_blank" href="' . tep_href_link('pdfinvoice.php', 'oID=' . $FREQUEST->getvalue('order_id') , 'SSL') . '">' . tep_template_image_button_basic('', IMAGE_BUTTON_PRINT_ORDER) . '</a>';
   }
?>
<!-- end pdf //-->
  </div>
	</div>
	</div>
<b>&nbsp;<?php if($order->info['ip_address']!='')echo HEADING_IP_ADDRESS; ?></b>
<?php echo $order->info['ip_address'];?>