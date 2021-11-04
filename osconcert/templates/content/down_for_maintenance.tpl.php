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
        <td><h3><?php echo HEADING_TITLE; ?></h3></td>
      </tr>


  
      <tr>
        <td><br><table width="100%" cellpadding="2">

          <tr>
            <td class="main"><?php echo DOWN_FOR_MAINTENANCE_TEXT_INFORMATION; ?></td>
          </tr>
		<?php if (DISPLAY_MAINTENANCE_TIME == 'true') { ?>
          <tr>
            <td class="main"><?php echo TEXT_MAINTENANCE_ON_AT_TIME . TEXT_DATE_TIME; ?></td>
          </tr>
		 <?php
		  } 
		  if (DISPLAY_MAINTENANCE_PERIOD == 'true') { ?>
		  <tr>
            <td class="main"><?php echo TEXT_MAINTENANCE_PERIOD . TEXT_MAINTENANCE_PERIOD_TIME; ?></td>
          </tr>
		  <?php } ?>

        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<br><br>' . '<a href="' . tep_href_link(FILENAME_DEFAULT) . '"><div style="float:right">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</div></a>'; ?></td>
      </tr>
      <tr>
       <td>&nbsp;</td>
     </tr>
    </table>
