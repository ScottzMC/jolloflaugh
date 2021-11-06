<?php
/*			AJAX Cart for osConcert	*/
/*	2011 by Martin Zeitler, Germany	*/
/*									*/

defined('_FEXEC') or die();

	require_once(DIR_WS_CLASSES . 'ajax_cart.php');
	$ajaxCart = new ajaxCart;
	?>

			<div class="">
			<?php
				echo $ajaxCart->getCart('html');
			?>
            
            <?php //box office refund
				if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))
				{	?>
					<script type="text/javascript">
							<!--
							function confirmation() {
								var answer = confirm("<?php echo BO_REFUND; ?>")
								if (answer){
									return true
								}
								else{
									return false
								}
							}
							//-->
							</script>
					<div><br>
					<?php 
					echo '<a onclick="return confirmation()" title="' . IMAGE_BUTTON_REFUND . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_REFUND) . '</a>'; 
					?>
					</div>
					<?php 	
				}//bor - cancelled due to inability to handle multiple orders
				elseif($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] ))
				{
					?>
				
					<?php 
					}//////////////////////////////////////////////////////////////////////
					?>
		</div>
