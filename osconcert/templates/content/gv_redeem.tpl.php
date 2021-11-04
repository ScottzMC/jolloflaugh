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
    <table width="100%">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><h3><?php echo HEADING_TITLE; ?></h3></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
	if(!isset($hide_row_top)) { 
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php } ?>	  
      <tr>
        <td><table width="100%" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
<?php
// if we get here then either the url gv_no was not set or it was invalid
// so output a message.
  $message = sprintf(TEXT_VALID_GV, $currencies->format($coupon['coupon_amount']));
  if ($error) {
    $message = TEXT_INVALID_GV;
  }
?>
          <tr>
            <td class="main"><?php echo $message; ?></td>
          </tr>
          <tr>
            <td align="right"><br><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
