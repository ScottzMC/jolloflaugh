<?php 
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
    define('_FEXEC',1);
	define('FPDF_FONTPATH','tfpdf/font/');

	require('includes/application_top.php');
	if(defined('CHECK_CON')==false){
		define('CHECK_CON','');
	}
	require_once(DIR_FS_CATALOG_MODULES . "barcode/image.php");
	require(DIR_WS_INCLUDES.'/tweak/general.php');
	require('tfpdf/tfpdf.php');
	frequire('split_page_results_event.php',RCLA);
	frequire($FSESSION->language.'/payment_general_templates.php',RLANG);
	$PAYMENT_GENERAL_TEMPLATES=(new instance)->getTweakObject('display.paymentGeneralTemplates');
	$PAYMENT_GENERAL_TEMPLATES->pagination=true;

		$template_array=array(array("id"=>"ICD",'text'=>TEXT_ICD),
						  array("id"=>"ICDS",'text'=>TEXT_ICDS));				
//cartzone one ticket template
	if(($FREQUEST->getvalue('type') && (substr($FREQUEST->getvalue('type'),0,3)=='TIC')) || ($FREQUEST->postvalue('type') && (substr($FREQUEST->postvalue('type'),0,3)=='TIC'))){
		$template_array=array(array("id"=>"TIC",'text'=>TEXT_TIC));

						  /*array("id"=>"TICS",'text'=>TEXT_TICS)	*/
	}
						  
	$merge_fields=array(array('id'=>'FN','text'=>TEXT_FN),
						array('id'=>'LN','text'=>TEXT_LN),
						array('id'=>'EN','text'=>TEXT_EN),
						array('id'=>'EL','text'=>TEXT_EL),
						array('id'=>'SI','text'=>TEXT_SI),
						array('id'=>'EI','text'=>TEXT_EI)
						);
	$position_array=array(	array('id'=>'L','text'=>TEXT_ALIGN_LEFT),
							array('id'=>'R','text'=>TEXT_ALIGN_RIGHT),
					);
	$font_array=array(	array('id'=>'L','text'=>20),
							array('id'=>'M','text'=>18),
							array('id'=>'S','text'=>16),
					);
		  
	checkAJAX('PAYMENT_GENERAL_TEMPLATES');	 

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));


	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>$LANGUAGES,'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'link'=>tep_href_link(FILENAME_PAYMENT_GENERAL_TEMPLATES),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true,'extraParams'=>array());

	$jsData->VARS["page"]["template"]=array("ERROR_EVENT_DETAILS_CONTENT"=>ERROR_EVENT_DETAILS_CONTENT,
											"ERROR_SHOP_LOGO"=>ERROR_SHOP_LOGO,
											"ERROR_FREEWAY_LOGO"=>ERROR_FREEWAY_LOGO,
											"ERROR_MEMBER_LOGO"=>ERROR_MEMBER_LOGO,
											"ERROR_EVENT_CONDITION_CONTENT"=>ERROR_EVENT_CONDITION_CONTENT,
											"ERROR_TEMPLATE_HEIGHT"=>ERROR_TEMPLATE_HEIGHT,
											"ERROR_TEMPLATE_WIDTH"=>ERROR_TEMPLATE_WIDTH,
											"ERROR_EVENT_DETAILS_CONTENT"=>ERROR_EVENT_DETAILS_CONTENT,
											"ERROR_CUSTOMER_DETAILS_CONTENT"=>ERROR_CUSTOMER_DETAILS_CONTENT,
											"ERROR_SERVICE_CONDITION_LARGE"=>ERROR_SERVICE_CONDITION_LARGE,
											"ERROR_SERVICE_CONDITION_CONTENT"=>ERROR_SERVICE_CONDITION_CONTENT,
											"ERROR_EVENT_CONDITION_LARGE"=>ERROR_EVENT_CONDITION_LARGE
									);

	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update");
	
	tep_get_last_access_file();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/tweak/js/payment_general_templates.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
		<?php
		if(SHOW_OSCONCERT_HELP=='yes')
		{
		?>
		<div class="osconcert_message"><?php echo TEXT_IMPORTANT; ?></div>
		<?php
		}
		?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText"></td>
	</tr>
	<tr>
		<td class="main" id="cug-1message"></td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td id="cugtotalContentResult">
						<?php $PAYMENT_GENERAL_TEMPLATES->doItems();?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="display:none">
		<td id="ajaxLoadInfo"><div style="padding:5px 0px 5px 20px" class="main"><?php echo TEXT_LOADING . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div></td>
	</tr>
	<tr>
		<td id="ajaxLoadImage" style="display:none">
			<?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?>
		</td>
	</tr>
	<form name="fileUpload" id="fileUpload" action="<?php echo FILENAME_PAYMENT_GENERAL_TEMPLATES; ?>?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
		<input type="hidden" name="image_list" id="image_list" value="">
		<input type="hidden" name="image_resize" value="1">
	</form>
	<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
</table>
<!-- body_text_eof //-->
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>