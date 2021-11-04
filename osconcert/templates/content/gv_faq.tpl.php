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
    <table class="main-table">
      <tr>
        <td>	
		<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>
	</td>
      </tr>
      <tr>
        <td>
		<table width="100%" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
        </table>
		</td>
      </tr>
      <tr>
        <td class="main"><b><?php echo SUB_HEADING_TITLE; ?></b></td>
      </tr>
      <tr>
       <td class="main"><?php echo SUB_HEADING_TEXT; ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table><br><br>
