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

echo tep_draw_form('account', tep_href_link(FILENAME_CREATE_ACCOUNT_NEW, '', 'SSL'),'POST',' enctype="multipart/form-data" ') . tep_draw_hidden_field('action', 'process');

if(SEATPLAN_LOGIN_ENFORCED=='true' && !$FSESSION->is_registered['customer_id'])

$sign_up_style="display:none";
else 
$sign_up_style="display:true";

if(HIDE_SIGNUP=='false')

$sign_up_style="display:true";
else
$sign_up_style="display:none";
?>
<!--<div <?php //echo $sign_up_style; ?>>-->

	<?php
	// PWA BOF
	if (!isset($HTTP_GET_VARS['guest']) && !isset($HTTP_POST_VARS['guest']))
	{
	?>
	<div class="section-header">
	<h2><?php echo HEADING_TITLE; ?></h2>
	</div>
	<?php 
	}
	else
	{ 
	?>
	<div class="section-header">
	<h2><?php echo HEADING_TITLE_PWA; ?></h2>
	</div>
	<?php 
	} 
	// PWA EOF 
	?>

	<?php //suppress TEXT_ORIGIN_LOGIN if no accounts permitted
	if(!isset($_COOKIE['customer_is_guest']))
	{ 
	?>
	<?php 
	} 
		//print_r ($messageStack);
		if ($messageStack->size('account') > 0) 
		{ 
		echo $messageStack->output('account'); 
		}
		?>
	<div class="alert alert-warning">
	<?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?><span class="text-danger pull-right text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
	</div>

	<div style="<?php echo $sign_up_style; ?>;border: solid black 0px;">
			<?php 
		   // print_r($fieldsDesc);
		   $icount=0;
			 $space='';
				for ($icnt=0,$n=count($fieldsDesc);$icnt<$n;$icnt++)
				{
					
					$fieldDesc=&$fieldsDesc[$icnt];
					$nextFieldDesc=&$fieldsDesc[$icnt+1];
					//If PWA only show 'required fields' therefore a shorter form	
					if ((PURCHASE_WITHOUT_ACCOUNT =='yes') && (isset($_GET['guest']) || isset($_POST['guest']))) 
					{
						if(($fieldDesc['required']=='Y') )
						{
							if (!isset($ACCOUNT[$fieldDesc['uniquename']]))
							{
								$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
							}
							if($fieldDesc['input_type']=='L') 
							{
								$row_id="title_row";
							}
							else {
								$row_id="rec_row";
							}

							if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename']))
							{
								$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
							} 
							else 
							{
								$customerAccount->commonInput($fieldDesc);
							}

						}
						elseif ((PURCHASE_WITHOUT_ACCOUNT =='no') && (PURCHASE_NO_ACCOUNT=='no')&& (!isset($_GET['guest']) || !isset($_POST['guest']))) 
						{
							
							if (!isset($ACCOUNT[$fieldDesc['uniquename']]))
							{
								$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
							}
							if($fieldDesc['input_type']=='L') 
							{
								$row_id="title_row";
							}
							else {
								$row_id="rec_row";
							}

							if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename']))
							{
								$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
							} else {
								$customerAccount->commonInput($fieldDesc);
							}
						}
					} 
					else
					{
						//if no PWA we show the original form
						
						if (!isset($ACCOUNT[$fieldDesc['uniquename']]))
						{
							$ACCOUNT[$fieldDesc['uniquename']]=$fieldDesc['default_value'];
						}
						if($fieldDesc['input_type']=='L') 
						{
							$row_id="title_row";
						}
						else 
						{
							$row_id="rec_row";
						}

						if (method_exists($customerAccount,"edit__" . $fieldDesc['uniquename']))
						{
							$customerAccount->{"edit__" . $fieldDesc['uniquename']}($fieldDesc);
						} 
						else 
						{
							$customerAccount->commonInput($fieldDesc);
						}
					}
				}
	?>
	</div>
	<br class="clearboth">
	<div class="pull-right"><?php echo tep_template_image_button_val('', IMAGE_BUTTON_CONTINUE,' onClick="javascript:validateForm();"'); ?></div>
	<br><br><br>
	</form>
