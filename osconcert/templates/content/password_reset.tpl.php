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
<?php
	 include(DIR_WS_INCLUDES.'general.js');
		  include(DIR_WS_INCLUDES.'password_strength.js.php');
	 echo tep_draw_form('password_reset', tep_href_link(FILENAME_PASSWORD_RESET, 'token='.$token, 'SSL'), 'post', 'onSubmit="return check_form(password_reset);"') . tep_draw_hidden_field('action', 'process'); ?>
	 
  <script>
 function do_pwd_check(obj)
 {
 	pwd=obj.value;
	if(pwd.length==0)
	  document.getElementById("pwd_str").innerHTML='';
	else if(pwd.length<<?php echo ENTRY_PASSWORD_MIN_LENGTH;?>)
		document.getElementById("pwd_str").innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else if(!check_password_strength(pwd))
		document.getElementById("pwd_str").innerHTML='<?php echo ENTRY_PASSWORD_STRENGTH_ERROR;?>';
	else
		document.getElementById("pwd_str").innerHTML='';
 }
</script>
<h3><?php echo HEADING_TITLE; ?></h3>
<?php
  if ($messageStack->size('password_reset') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('password_reset'); ?></td>
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
                    <td class="main"><?php echo MY_EMAIL_TITLE ?></td>
                    <td class="main"><?php echo tep_draw_input_field('email_address') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_NEW; ?></td>
                   <td class="main"><?php echo tep_draw_password_field('password_new','','onKeyUp=javascript:do_pwd_check(this);') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?></td><td id='pwd_str' class="inputRequirement" style="font-color:red;font-size:11"></td>
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
        <td><table width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table width="100%" cellpadding="2">
              <tr>
                <td width="10"></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button_basic('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form>