<?php
defined('_FEXEC') or die();
	 include(DIR_WS_INCLUDES.'general.js');
	  include(DIR_WS_INCLUDES.'password_strength.js.php');
	 echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onSubmit="return check_form(account_password);"') . tep_draw_hidden_field('action', 'process'); ?>


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

<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>

<?php
  if ($messageStack->size('account_password') > 0) 
  {
?>
      <div><?php echo $messageStack->output('account_password'); ?></div>

<?php
  }
?>
    

			
			<table width="100%" cellpadding="2">
              <tr>
                <td class="main"><strong><?php echo MY_PASSWORD_TITLE; ?></strong></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table>
			

				
				<table cellspacing="2" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CURRENT; ?></td>
                    <td class="main">
					<div style="float:right;"><?php echo tep_draw_password_field('password_current') . '</div>
					<div style="float:">&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '&nbsp;</span></div>': ''); ?></td>
                  </tr>

                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_NEW; ?></td>
                   <td class="main"><div style="float:right;"><?php echo tep_draw_password_field('password_new','','onKeyUp=javascript:do_pwd_check(this);') . '</div><div style="float:left;">&nbsp;' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '&nbsp;</span></div>': ''); ?></td>
				   <td id='pwd_str' class="inputRequirement"></td>
				  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><div style="float:right;"><?php echo tep_draw_password_field('password_confirmation') . '</div><div style="float:left;">&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '&nbsp;</span></div>': ''); ?></td>
                  </tr>
                </table>
				

			<table width="100%" cellpadding="2">
              <tr>
 
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button_basic('', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('', IMAGE_BUTTON_CONTINUE); ?></td>

              </tr>
            </table>
			
	</form>