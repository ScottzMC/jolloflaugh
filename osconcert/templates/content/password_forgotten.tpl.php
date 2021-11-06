<?php 
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); 
	?>
<?php
  if ($messageStack->size('password_forgotten') > 0) 
  {
?>
      <div><?php echo $messageStack->output('password_forgotten'); ?></div>
<?php
  }
?>
<div class="container-fluid" style="padding:20px 0">
	<div class="row">
		<div class="col-md-12">
		<?php echo TEXT_MAIN; ?>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-2">
		<?php echo '<b>' . ENTRY_EMAIL_ADDRESS . '</b>';  ?>
		</div>
		<div class="col-md-10">
		<?php echo tep_draw_input_field('email_address');  ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
		
		</div>
		<div class="col-md-4">
		<div class="pull-right">
		<?php echo tep_template_image_submit('', IMAGE_BUTTON_CONTINUE); ?></div>
		</div>
	</div>
		<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
		<?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_BACK) . '</a>'; ?>
		</div>
		<div class="col-md-4">
		
		</div>
	</div>
</div>
	 </form>