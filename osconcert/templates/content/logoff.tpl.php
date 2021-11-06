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
 
               	<?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/'.COMPANY_LOGO, HEADING_TITLE,'',''); ?>
                <div><?php echo TEXT_MAIN; ?></div>
				<?php echo '<a class="pull-right" href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . tep_template_image_button_basic('', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
