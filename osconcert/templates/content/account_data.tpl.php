<?php
/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare

	Released under the GNU General Public License 
*/	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	 include(DIR_WS_INCLUDES.'general.js');
		  include(DIR_WS_INCLUDES.'password_strength.js.php');
	 echo tep_draw_form('account_data', tep_href_link('account_data.php', '', 'SSL'), 'post', 'onSubmit="return check_form(account_data);"') . tep_draw_hidden_field('action', 'process'); ?>
	 

<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>

<?php
  if ($messageStack->size('account_data') > 0) 
  {
?>
      <div><?php echo $messageStack->output('account_data'); ?></div>

<?php
  }
?>
      <table width="100%" cellpadding="2">
          <tr>
            <td><table width="100%" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo MY_PASSWORD_TITLE; ?></b></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table cellspacing="2" cellpadding="2">
				           
				  <tr>
                    <td class="main" colspan = "2"><?php echo PAGE_TEXT; ?></td>
                 </tr>
				
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CURRENT; ?></td>
                    <td class="main"><div style="float:left;"><?php echo tep_draw_password_field('password_current') . '</div><div style="float:">&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '&nbsp;</span></div>': ''); ?></td>
                  </tr>

                </table></td>
              </tr>
            </table></td>
          </tr>
        </table>
		

			
			<table width="100%" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table>
</form>