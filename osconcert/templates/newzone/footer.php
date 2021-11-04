<?php
/*
	osConcert Seat Booking Sofware
	Copyright (c) 2007-2021 https://www.osconcert.com

Released under the GNU General Public License
*/

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

// Hide footer.php if not to show in maintenance mode

if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') 
{
?>
<!-- Copyright section of the footer-->
	<footer id="footer">
	<div class="container">
	<div class="copyright">
	<?php if (HIDE_FOOTER_INFO =='no') 
	{
	?>
	<!--info links below content-->   
	<div class="pull-right">&nbsp;<?php echo '<a href="'.tep_href_link(FILENAME_DEFAULT,'stcPath=2').'">'.BOX_INFORMATION_PRIVACY.'</a>';?> &nbsp;:: &nbsp;<?php echo '<a href="'.tep_href_link(FILENAME_DEFAULT,'stcPath=3').'">'.BOX_INFORMATION_CONDITIONS.'</a>';?> &nbsp;:: &nbsp;<?php echo '<a href="' .BOX_INFORMATION_CONTACT_LINK. '">'.BOX_INFORMATION_CONTACT.'</a>';?> &nbsp;:: &nbsp; <?php echo '<a href="'.tep_href_link(FILENAME_ACCOUNT).'">'.HEADER_ACCOUNT.'</a>';?></div>
	<!--eof info links below content-->
	<?php } else { ?>
	<div class="pull-right">&nbsp;<?php echo '<a href="'.tep_href_link(FILENAME_CONTACT_US).'">'.BOX_INFORMATION_CONTACT.'</a>';?>&nbsp;</div>
	<?php } ?>
	<br class="clearfloat"><br>
	<!-- 
	osConcert is open source software and you are free to remove the copyright links below if you want, but its generally accepted practice to make a small donation. Retaining our copyright links below will also qualify you for fast free online support.
	Please donate via PayPal at https://www.paypal.me/osconcert
	//-->	
	<?php echo "Copyright &copy; 2007-".date('Y'); ?> <a href="/"><?php echo STORE_NAME; ?></a>. <?php echo FOOTER_MESSAGE; ?><br />
	<a href="https://www.osconcert.com" target="_blank"><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/images/osc1.png" alt="<?php echo STORE_NAME; ?>" title="<?php echo STORE_NAME; ?>" /></a> Powered by <a href="https://www.osconcert.com" target="_blank">osConcert</a>.
	<!--alternatively-->
	<!--Powered </a> by <a href="https://www.osconcert.com" target="_blank">osConcert</a>.-->

	<!-- 
	osConcert is open source software and you are free to remove the copyright links below if you want, but its generally accepted practice to make a small donation. Retaining our copyright links below will also qualify you for fast free online support.
	Please donate via PayPal at https://www.paypal.me/osconcert
	//-->
	</div>
	<div class="credits">
	<!--Powered by <a href="https://www.osconcert.com" target="_blank">osConcert</a>.-->
	</div>
	</div>
	</footer><!-- #footer -->
<?php 
} 
?>