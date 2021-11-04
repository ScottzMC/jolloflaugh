<?php
/*			AJAX Cart for osConcert			*/
/*	2011 by Martin Zeitler, Germany	*/

defined('_FEXEC') or die();
if(!defined('BOX_HEADING_SHOPPING_CART')){
	define('BOX_HEADING_SHOPPING_CART', 'Shopping Cart');
}
//define('PLEASE_LOGIN', 'Please login to select seats');
/* when login is enforced but not active ... */
if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered('customer_id')){
		echo '<div style="margin-right: auto; margin-left: auto"><span><a href="' . tep_href_link(FILENAME_LOGIN) . '" class="btn btn-info btn-lg ">' . PLEASE_LOGIN . '</a></span></div>';
	}
else {
	require_once 'includes/classes/ajax_cart.php';
	$ajaxCart = new ajaxCart;
	?>
 <?php // start of floating cart
 // next line allows float only on show pages - you can add in others like product_info if needed
 // used really to exclude the checkout pages etc etc
 if(isset($cPath) && $cPath >0){ 
 ?>
<style>
.roving-cart {
	z-index:1000;
	position: fixed;
	width: <?php echo BOX_WIDTH_LEFT; ?>px;
	margin:2px;
	left:10px;
    bottom:50px;
	/*float:left;*/
}
</style>

<?php 
}
      //end of roving cart section 
      // add class 'fixed-cart' to <div> below
	  if(!isset($_SESSION[‘mobile’])){
    require_once 'includes/classes/mobile_detect.php';
   $detect = new Mobile_Detect;
					if ( $detect->isMobile()){
						$fixedCart="";
						}else{
						$fixedCart="fixed-cart";
					}
   } //end
?>
	<div class="card box-shadow <?php echo $fixedCart; ?>">
	<div class="card-header">
	<?php 
	echo '<strong>';
	echo BOX_HEADING_SHOPPING_CART;
	echo '</strong>';
?>
    <span id="cart-hide">[-]</span>
    </div> 
	<div id="ajax_status" style="margin-top:10px;width:100%"></div> 
	<div class="cart-content" style="margin-top:12px;">
			<?php
				echo $ajaxCart->getCart('html');
			?></div>
            
            <?php //box office refund
			if($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_refund'] ))
			{
				?>
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
				
            	<div id="btnCheckOut">
				<?php echo '<a onclick="return confirmation()" title="' . IMAGE_BUTTON_REFUND . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_REFUND) . '</a>'; ?>
			</div>
            <?php 
			}//bor - cancelled due to inability to handle multiple orders
			elseif($_SESSION['customer_country_id']==999 && isset($_SESSION['box_office_reservation'] ))
			{
			?>

            <?php }else{?>

			<div id="btnCheckOut">
				<?php echo '<a title="' . IMAGE_BUTTON_CHECKOUT . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>'; ?>
			</div>
            <?php } ?>
	       <br class="clearfloat">
		   </div>
		<?php
		}
		?>