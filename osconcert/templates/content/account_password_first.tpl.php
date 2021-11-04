    <?php echo tep_draw_form('account_password_first', tep_href_link(FILENAME_ACCOUNT_PASSWORD_FIRST, '', 'SSL'), 'post', 'onSubmit="return check_form(account_password_first);"') . tep_draw_hidden_field('action', 'process'); ?>
	
	<table class="main-table">
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
?>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('account_password_first') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account_password_first'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table width="100%" cellpadding="2">
          <tr>
            <td><table width="100%" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo MY_PASSWORD_TITLE; ?></b></td>
                <td class="required" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table cellspacing="2" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_NEW; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_new') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table width="100%" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a class="button_links" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '"><div style="float:right">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</div></a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form>

