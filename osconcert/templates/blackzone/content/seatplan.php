<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<?php
/*
	AJAX Multiuser Seat Reservations
	for osConcert, Online Seat Booking
	by Martin Zeitler, Germany
*/
?>
<?php echo $sp->tep_renderPricesBar($cPath, $category['categories_name']); ?>

	<ul class="list-group list-group-horizontal">
	<?php
		if($_SESSION['customer_id'])
		{
			echo '	<li class="list-group-item" style="border: none"><span class="plan_own cube"></span>&nbsp;' . TEXT_YOUR_RESERVATIONS . '</li>';
			echo "	<li class=\"list-group-item\" style=\"border: none\"><span class='plan_reserved cube'></span>&nbsp;" .TEXT_FOREIGN_RESERVATIONS ."</li>";
		}
		else {
			echo "	<li class=\"list-group-item\" style=\"border: none\"><span class='plan_reserved cube'></span>&nbsp;" .TEXT_RESERVED_SEATS . "</li>";
		}
	?>
		<li class="list-group-item" style="border: none"><span class="plan_incart cube"></span>&nbsp;<?php echo TEXT_IN_BASKET;?></li>
		<li class="list-group-item" style="border: none"><span class="plan_locked cube"></span>&nbsp;<?php echo TEXT_OTHER_BASKETS;?></li>
		<li class="list-group-item" style="border: none"><div id="indicator"></div></li>
	</ul>
	<?php

	if (isset($_SESSION['box_office_refund'])) 
		{
	require_once(DIR_WS_MODULES  . 'triple_boxes.php');
		}
		else
		{
	?>
		<div style="height:50px">
			<div id="btnCheckOut" class="checkout" style="display:none;text-align:center">
			<?php echo '<a class="" title="' . IMAGE_BUTTON_CHECKOUT . '" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button_checkout('', IMAGE_BUTTON_CHECKOUT) . '</a>'; 
			?>
			</div>
		</div>
		<?php
		}

	//echo $plan_id;
	//show the sub category grid
	if((SHOW_SUBCATEGORIES=='yes')&&($plan_id!=3))
	{
	?>
	<div style="text-align:center;display:true"><h3><?php echo $category['categories_name']; ?></h3></div>
	<?php
	}
	?>

<?php 

if(SEAT_PLAN_CACHE=='true')
{	
$caching = true;
}
$expiry = 240*60;	/* cache-file expiry: 240 minutes */
$timeout = true;	/* true or false */

	if(isset($_SESSION['box_office_refund']) && $_SESSION['box_office_refund'] == 'yes' )
	{
	echo $sp->tep_renderSeatplanRefund($cPath);
	}
	elseif(isset($_SESSION['box_office_reservation']) && $_SESSION['box_office_reservation'] == 'yes' )
	{
	echo $sp->tep_renderSeatplanReservation($cPath);
}else
{
	/* if caching is enabled */
	if($caching)
	{
		/* setup cache filename */
		$path = DIR_FS_CATALOG.'/cache/';
		$cache = $path.'section'.$cPath.'.html';
		
		/* create the chache directory if required */
		if(!file_exists($path)){mkdir($path,0755);}

		/* check if the cached seat-plan exists and is not yet expired */
		if (file_exists($cache) && (time() - $expiry < filemtime($cache))){
			
			/* just output the cached seat-plan from file */
			echo "\n<!-- Cached on ".date('jS F Y @ H:i', filemtime($cache))." -->\n";
			
			/* and send the cached seatplan */
			include($cache);
		}
		else {
			/* the cache-file has expired (or doesn't exist) and needs to be re-generated */
			
			/* unlink cache-file if it exists */
			if(file_exists($cache)){unlink($cache);}
			
			/* enable output buffering */
			ob_start();
			if((DESIGN_MODE=='yes')&&($manufacturers_id>4 && $manufacturers_id<9))
			{
			echo $sp->tep_renderSeatplanCSS($cPath);
			echo $sp->tep_renderSeatplanDesign($cPath);	
			}else{
				if($plan_id<5){
				echo $sp->tep_renderSeatplan($cPath);
				}
			}
			
			/* and write the buffer into a file */
			$fp = fopen($cache,'w');
			fwrite($fp,ob_get_contents());
			fclose($fp);
			
			ob_end_flush();
		}
	}
	else {
		/* if caching is disabled */
		echo "\n<!-- Rendered on ".date('jS F Y @ H:i', time())." -->\n";
		if((DESIGN_MODE=='yes')&&($manufacturers_id>4 && $manufacturers_id<9))
		{
		echo $sp->tep_renderSeatplanCSS($cPath);
		echo $sp->tep_renderSeatplanDesign($cPath);	
		}else{
			if($plan_id<5){
		echo $sp->tep_renderSeatplan($cPath);
			}
		}
	}
}
?>
<?php
/* how to guide */

if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered('customer_id')){
	require_once(DIR_WS_INCLUDES.'login_enforced.php');
}
else {
	if(HOW_TO_GUIDE=='true'){
		require_once(DIR_WS_TEMPLATES.TEMPLATE_NAME.'/content/howto.php');
	}
}
if (SHOW_LOGIN == 'yes') 
{
include(DIR_FS_CATALOG.DIR_WS_MODULES. '/double_boxes.php');
}
?>