<?php 
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
    define('_FEXEC',1);
	require('includes/application_top.php');
	require(DIR_WS_INCLUDES.'/tweak/general.php');
	frequire('split_page_results_event.php',RCLA);
	frequire($FSESSION->language.'/marketing_wallet.php',RLANG);
 	require(DIR_WS_CLASSES . 'currencies.php');
 	$currencies = new currencies();
	 
	$MARKETING_WALLET=(new instance)->getTweakObject('display.marketingWallet');

	$message=array();
	$message=array(array('id'=>'WFU','text'=>TEXT_TYPE_WALLET_FUC,'mode'=>1),
					array('id'=>'WBW','text'=>TEXT_TYPE_WALLET_BW,'mode'=>1)
				);
	$fields_type['WFU']='P_W';
	$fields_type['WBW']='P_W';

	$mes_array=array('message_id'=>'',
					'message_type'=>'',
					'message_send'=>'B',
					'message_send1'=>'0',
					'message_subject'=>'',
					'message_reply_to'=>STORE_OWNER_EMAIL_ADDRESS,
					'message_text'=>'',
					'message_format'=>'');
	$format_array=array(array('id'=>'T','text'=>TEXT_FORMAT_TEXT),
						array('id'=>'H','text'=>TEXT_FORMAT_HTML),
						array('id'=>'B','text'=>TEXT_FORMAT_BOTH)
						);

	// customer details
	$fields_details['P']=array(array('id'=>'TITLE_P','text'=>TEXT_TITLE_P),
							array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
							array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
							array('id'=>'DF','text'=>'&nbsp;&nbsp;' . TEXT_DF),
							array('id'=>'EM','text'=>'&nbsp;&nbsp;' . TEXT_EM),
							array('id'=>'TN','text'=>'&nbsp;&nbsp;' . TEXT_TN),
							array('id'=>'FX','text'=>'&nbsp;&nbsp;' . TEXT_FX),
							array('id'=>'SA','text'=>'&nbsp;&nbsp;' . TEXT_SA),
							array('id'=>'CT','text'=>'&nbsp;&nbsp;' . TEXT_CT),
							array('id'=>'SU','text'=>'&nbsp;&nbsp;' . TEXT_SU),
							array('id'=>'ST','text'=>'&nbsp;&nbsp;' . TEXT_ST),
							array('id'=>'PC','text'=>'&nbsp;&nbsp;' . TEXT_PC),
							array('id'=>'CY','text'=>'&nbsp;&nbsp;' . TEXT_CY),
							array('id'=>'IV','text'=>'&nbsp;&nbsp;' . TEXT_IV),
							array('id'=>'RE','text'=>'&nbsp;&nbsp;' . TEXT_RE),
							array('id'=>'UN','text'=>'&nbsp;&nbsp;' . TEXT_UN));
	
	$fields_details['W']=array(array('id'=>'TITLE_W','text'=>TEXT_TITLE_W),
							array('id'=>'AD','text'=>'&nbsp;&nbsp;' . TEXT_WAD),
							array('id'=>'CB','text'=>'&nbsp;&nbsp;' . TEXT_WCB),
							array('id'=>'PT','text'=>'&nbsp;&nbsp;' . TEXT_PT),
							array('id'=>'DD','text'=>'&nbsp;&nbsp;' . TEXT_DD));
				
	
	$message_type_array=array();
	
	$format_array=array(array('id'=>'T','text'=>TEXT_FORMAT_TEXT),
						array('id'=>'H','text'=>TEXT_FORMAT_HTML),
						array('id'=>'B','text'=>TEXT_FORMAT_BOTH)
						);
	
	$message_type_array['WTR']=array(array('id'=>'WTR','text'=>TEXT_WTR));	
	
	checkAJAX('MARKETING_WALLET');	 
	
	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]=array('lastAction'=>false,'opened'=>array(),'locked'=>false,'NUlanguages'=>tep_get_languages(),'imgPath'=>DIR_WS_IMAGES,"menu"=>array(),'editorLoaded'=>false,'link'=>tep_href_link(FILENAME_MARKETING_WALLET),'NUeditorControls'=>array(),'searchMode'=>false,'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,'crypted'=>$ENCRYPTED,'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERR_INVALID_REPLY_TO"=>ERR_INVALID_REPLY_TO,
											"ERR_EMPTY_REPLY_TO"=>ERR_EMPTY_REPLY_TO,
											"ERR_EMPTY_SUBJECT"=>ERR_EMPTY_SUBJECT
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
<script language="javascript" src="includes/tweak/js/marketing_wallet.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/http.js"></script>
<script type="text/javascript" src="htmlarea/htmlarea.js"></script>
<?php
	require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
	textEditorLoadJS();
?>
<script type="text/javascript" src="includes/aim.js"></script>
<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
</head>
<body marginwidth="0"  marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="javascript:pageLoaded();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<!-- body_text //-->
	<tr height="20" id="messageBoard" style="display:none">
		<td id="messageBoardText"></td>
	</tr>
	<tr>
		<td class="main" id="markWallet-1message"></td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="main">
					<?php 
//					$service_array=$MARKETING_WALLET->tep_get_service_array_single();	
					?>
					</td>
				</tr>
				<tr>
					<td id="markWallettotalContentResult">
						<?php $MARKETING_WALLET->doItems();?>
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
	<form name="fileUpload" id="fileUpload" action="<?php echo FILENAME_MARKETING_WALLET; ?>?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
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