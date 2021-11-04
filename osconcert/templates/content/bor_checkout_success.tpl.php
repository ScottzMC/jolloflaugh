<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
	defined('_FEXEC') or die();
	require(DIR_WS_INCLUDES.'http.js'); ?>
	<?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?>
	<?php //box office refund 
	//we need an extra /form tag here so that the box office links work
	if($_SESSION['box_office_refund'])
	{
	echo '</form>';
	$FSESSION->remove('box_office_refund');
	}
?>
	<div class="section-header">
	<h2><?php 
	echo HEADING_TITLE;
	?></h2>
	</div>

	<?php
	if ($messageStack->size('checkout_success') > 0) 
	{
	?>
	<div><?php echo $messageStack->output('checkout_success');?></div>
	<?php 
	}   
	?>

	<div><?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'.COMPANY_LOGO, HEADING_TITLE,'',''); ?></div>

			<?php
					
					if (sizeof($products_array)>0)
					{
						//echo '<h2>' . SEATS_RESERVED . '</h2><br>';
						$products_displayed = array();
						$order_is_printable = 0;
						for ($i=0, $n=sizeof($products_array); $i<$n; $i++) 
						{
							if (!in_array($products_array[$i]['id'], $products_displayed)) 
							{
								//sort a family ticket
								if(!defined('SET_OF_FAMILY'))
								{
								define('SET_OF_FAMILY', 'Set of ');
								}	
								if($products_array[$i]['type']=='F'){
								//$qty_times=SET_OF_FAMILY .$products_array[$i]['qty'] . ' ';
								$qty_times=SET_OF_FAMILY . FAMILY_TICKET_QTY. ' ';
								}else{
								$qty_times=$products_array[$i]['qty'] . ' x ';
								}
								if ($_GET['type'] == 'canx'){ $qty_times = '';}
						
							echo
							$qty_times . 
							$products_array[$i]['seat'] . ' [ ' . 
							$products_array[$i]['date'] . '] <b>' . 
							$products_array[$i]['categories_name'] . '</b> - ' . 
							$products_array[$i]['concert_date'] . '<br>' ;
							
							}
						$order_is_printable = $order_is_printable + $products_array[$i]['is_printable'];
						}
					}

					?>
					<?php //echo $TEXT_SUCCESS;

					echo '<h1>' . INVOICE . $FREQUEST->getvalue('order_id') .'</h1>';

			$pdfdelivered_query = tep_db_query("select osh.orders_status_history_id, osh.orders_status_id from " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '". (int)$FREQUEST->getvalue('order_id') . "' order by orders_status_history_id desc limit 1");
			$pdfdelivered_status = tep_db_fetch_array($pdfdelivered_query);

			if ($pdfdelivered_status['orders_status_id'] == 3 && DISPLAY_PDF_DELIVERED_ONLY == 'true')
			{
			echo '<a target="_blank" href="' . tep_href_link('pdfinvoice.php', 'oID=' . $FREQUEST->getvalue('order_id') , 'SSL') . '">' . tep_template_image_button_basic('', IMAGE_BUTTON_PRINT_ORDER) . '</a>';
			}
			?>
			<br class="clearfloat">
		<?php 
			$order_status_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . (int)$FREQUEST->getvalue('order_id') . "'");
			$order_status = tep_db_fetch_array($order_status_query); 	
			if(($order_status['orders_status'])>1)
			{
			if (DOWNLOAD_ENABLED == 'true') 
				include(DIR_WS_MODULES . 'downloads.php'); 
			}
        ?>

				<?php 
				$target=" target='_blank'";
				$delivered_query = tep_db_query("select max(osh.date_added) as los, osh.orders_status_history_id, osh.orders_status_id from " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '". (int)$FREQUEST->getvalue('order_id') . "' group by osh.orders_status_id,osh.orders_status_history_id order by orders_status_history_id desc limit 1");
			$delivered_status = tep_db_fetch_array($delivered_query);

		if (E_TICKETS == 'true')
		{
			 if (($order_is_printable > 0) &&($delivered_status['orders_status_id'] == E_TICKET_STATUS)&&(DISPLAY_PDF_DELIVERED_ONLY == 'true'))
			 {
				?>
			
				<div align="center">
				<p><br>
				<input type="button" class="btn btn-primary btn-lg" value="<?php echo GET_TICKETS_HERE; ?>"
				onclick="window.open('<?php echo tep_href_link(FILENAME_CUSTOMER_PDF, 'oID=' . $FREQUEST->getvalue('order_id').'&customer_id='.$customer_number['customers_id'] , 'SSL'); ?>')">
				<br>
				<?php 
				echo tep_image(DIR_WS_IMAGES . 'pdf.gif', PRINT_PDF_TICKETS,'','','style="vertical-align:middle"');
				echo GET_ADOBE_READER; ?> <a href="https://get.adobe.com/reader/" target="_blank"><?php echo HERE; ?>
				</a></p>
				</div>

	<?php   
			} 
		}
	?>
			
			<div>
			</div>
			
			<div>
			</div>
            
			<?php
			//CCGV FOR ORDER_TOTAL CREDIT SYSTEM - Start Addition
			$gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='" . (int)$customer_id . "'");
			if ($gv_result=tep_db_fetch_array($gv_query)) 
			{
				if ($gv_result['amount'] > 0) 
				{
				?>
			<div>
			<?php echo GV_HAS_VOUCHERA; echo tep_href_link(FILENAME_GV_SEND); echo GV_HAS_VOUCHERB; ?>
			</div>
			<?php
				}
			}
			//CCGV ADDED FOR ORDER_TOTAL CREDIT SYSTEM - End Addition
			?>

	  <div class="bs-stepper">
            <div class="bs-stepper-header">
              <div class="step" data-target="#delivery">
                <button type="button" class="btn step-trigger"  disabled="disabled">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_DELIVERY; ?></span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#payment">
                <button type="button" class="btn step-trigger"  disabled="disabled">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label"><?php echo CHECKOUT_BAR_PAYMENT; ?></span>
                </button>
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