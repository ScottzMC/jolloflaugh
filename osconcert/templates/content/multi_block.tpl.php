<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

	if ($messageStack->size('multi_block') > 0) 
	{
	?>
	<div><?php echo $messageStack->output('multi_block'); ?></div>
	<?php
	}
	?>
	<div class="section-header">
		<h2><?php echo HEADING_TITLE; ?></h2>
	</div>
<?php 
#######################################
#     STEP 1 - SELECT SHOWS  code is
#     in the file ./multi_block.php
#######################################

?>
<table width="100%" cellpadding="2">
         <tr>
          	<td class="main">
			<b>
			<?php 
			echo HEADING_TITLE; ?></b>
			<?php 
			load_category_list();
		  
		    echo $categories_content;
		  
		  ?></td>
		</tr>
        </table>
	
	<div class="step1a" style="display:none">
		  <?php echo TEXT_BO_RENDERING; ?>
	</div>
	
	<div class="step2" style="display:none">
		  <?php //echo $sp->tep_renderSeatplanBlock(1);?>
	</div>
	
	<div class="step2a" style="display:none">
		  <?php echo TEXT_BO_UPDATING; ?>
	</div>
	
	<br><br>
