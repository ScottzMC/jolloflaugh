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
include(DIR_WS_INCLUDES.'javascript/http.js');
include(DIR_WS_INCLUDES.'password_strength.js.php');
include(DIR_WS_INCLUDES.'javascript/customer_account.js');

echo tep_draw_form('account', tep_href_link(FILENAME_ACCOUNT_EDIT_NEW, '', 'SSL'),'POST','enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process');
?>

<input type="hidden" value="<?php echo $customerAccount->maxImageSize;?>">

<div class="section-header">
<h2><?php echo HEADING_TITLE; ?></h2>
</div>

      <p><?php if(!$FSESSION->is_registered('customer_id')) echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></p>
<?php
  if ($messageStack->size('account') > 0) { 
?>
      <div><?php echo $messageStack->output('account'); ?></div>
<?php
  }
?>

		<table class="account">
		<?php
			for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++)
			{
				$fieldDesc=&$fieldsDesc[$icnt];
				if (!isset($ACCOUNT[$fieldDesc['uniquename']])){
					$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
				} 
				echo '<tr><td class="main">';
				if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename']))
				{
					$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
				} else {
					$customerAccount->commonInput($fieldDesc);	
				}
				echo '</td></tr>';
			}
		?>

		<tr>
			<td align="right"><div style="float:right"><?php echo tep_template_image_button_val('', IMAGE_BUTTON_CONTINUE); ?></div></td>
		</tr>
		</table><br>

</form>
