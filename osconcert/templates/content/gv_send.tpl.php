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
        <td width="100%"><table width="100%">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'osconcert.png', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
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
<?php
	}
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($FREQUEST->getvalue('action') == 'process') {
?>
     	
	<tr>
        <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?><br><br><?php echo 'gv '.$id1; ?></td>
      </tr>
      <tr>
        <td align="right"><br><a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
      </tr>
<?php
  }  
  if ($FREQUEST->getvalue('action') == 'send' && !$error) {
    // validate entries
      $gv_amount = (double) $gv_amount;
      $gv_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$FSESSION->customer_id . "'");
      $gv_result = tep_db_fetch_array($gv_query);
      $send_name = $gv_result['customers_firstname'] . ' ' . $gv_result['customers_lastname'];
?>
      <tr>
        <td><form action="<?php echo tep_href_link(FILENAME_GV_SEND, 'action=process', 'NONSSL'); ?>" method="post"><table width="100%" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf(MAIN_MESSAGE, $currencies->format($FREQUEST->postvalue('amount','float')), $FREQUEST->postvalue('to_name'), $FREQUEST->postvalue('email'), $FREQUEST->postvalue('to_name'), $currencies->format($FREQUEST->postvalue('amount','float')), $send_name); ?></td>
          </tr>
<?php
      if ($FREQUEST->postvalue('message')) {
?>
           <tr>
            <td class="main"><?php echo sprintf(PERSONAL_MESSAGE, $gv_result['customers_firstname']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo stripslashes($FREQUEST->postvalue('message')); ?></td>
          </tr>
<?php
      }

      echo tep_draw_hidden_field('send_name', $send_name) . tep_draw_hidden_field('to_name', $FREQUEST->postvalue('to_name')) . tep_draw_hidden_field('email', $FREQUEST->postvalue('email')) . tep_draw_hidden_field('amount', $gv_amount) . tep_draw_hidden_field('message', $FREQUEST->postvalue('message'));
?>
          <tr>
            <td class="main"><?php echo tep_template_image_submit('button_back.gif', IMAGE_BUTTON_BACK, 'name=back') . '</a>'; ?></td>
            <td align="right"><br><?php echo tep_template_image_submit('button_send.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></form></td>
      </tr>
<?php
  } elseif ($FREQUEST->getvalue('action')=='' || $error) {
?>
      <tr>
        <td class="main"><?php echo HEADING_TEXT; ?></td>
      </tr>
	  
      <tr>
        <td><form action="<?php echo tep_href_link(FILENAME_GV_SEND, 'action=send', 'NONSSL'); ?>" method="post"><table width="100%" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_NAME; ?><br><?php echo tep_draw_input_field('to_name', $FREQUEST->postvalue('to_name'));?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL; ?><br><?php echo tep_draw_input_field('email', $FREQUEST->postvalue('email')); if ($error) echo $error_email; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AMOUNT; ?><br><?php echo tep_draw_input_field('amount', $FREQUEST->postvalue('amount'), '', '', false); if ($error) echo $error_amount; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_MESSAGE; ?><br><?php echo tep_draw_textarea_field('message', 'soft', 50, 15, stripslashes($FREQUEST->postvalue('message'))); ?></td>
          </tr>
        </table>
        <table width="100%" cellpadding="2">
          <tr>
	
<?php
    $back = sizeof($navigation->path)-2;
?>
            <td class="main"><?php echo '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></form></td>
      </tr>
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
	
    </table></td>

