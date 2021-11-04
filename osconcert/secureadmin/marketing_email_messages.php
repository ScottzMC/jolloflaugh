<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	define('_FEXEC',1);
	require('includes/application_top.php');

	require(DIR_WS_INCLUDES . '/tweak/general.php');
	frequire($FSESSION->language.'/marketing_email_messages.php',RLANG);
	frequire('currencies.php',RCLA);
	$currencies = new currencies();

	$LANGUAGES=tep_get_languages();
	
	$mes_array=array('message_id'=>'',
		'message_type'=>'',
		'message_send'=>'',
		'message_subject'=>'',
		'message_reply_to'=>STORE_OWNER_EMAIL_ADDRESS,
		'message_text'=>'',
		'message_format'=>'');
		
	$format_array=array(array('id'=>'T','text'=>TEXT_FORMAT_TEXT),
			array('id'=>'H','text'=>TEXT_FORMAT_HTML),
			array('id'=>'B','text'=>TEXT_FORMAT_BOTH));
	//Customer Account
	$fields_details['C']=array(
				array('id'=>'TITLE_C','text'=>TEXT_TITLE_C),
				array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
				array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
				array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'OL','text'=>'&nbsp;&nbsp;' . TEXT_OL),
				array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
				array('id'=>'LE','text'=>'&nbsp;&nbsp;' . TEXT_LE),
				array('id'=>'LP','text'=>'&nbsp;&nbsp;' . TEXT_LP),
				array('id'=>'SP','text'=>'&nbsp;&nbsp;' . TEXT_SP),
				array('id'=>'FB','text'=>'&nbsp;&nbsp;' . TEXT_FB),
				array('id'=>'OP','text'=>'&nbsp;&nbsp;' . TEXT_OP),
				array('id'=>'SL','text'=>'&nbsp;&nbsp;' . TEXT_SL),
				array('id'=>'LA','text'=>'&nbsp;&nbsp;' . TEXT_LA),
				array('id'=>'AU','text'=>'&nbsp;&nbsp;' . TEXT_AU)
				);
	//These are the placeholders for the Admin User Manipulation/Admin Password
	$fields_details['A']=array(
				array('id'=>'TITLE_A','text'=>TEXT_TITLE_A),
				array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
				array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
				array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
				array('id'=>'LE','text'=>'&nbsp;&nbsp;' . TEXT_LE),
				array('id'=>'LP','text'=>'&nbsp;&nbsp;' . TEXT_LP),
				
				array('id'=>'AL','text'=>'&nbsp;&nbsp;' . TEXT_AL)
				);
	//Order Details
	$fields_details['O']=array(
				array('id'=>'TITLE_O','text'=>TEXT_TITLE_O),
				array('id'=>'NO','text'=>'&nbsp;&nbsp;' . ORDR_NO),
				array('id'=>'OP','text'=>'&nbsp;&nbsp;' . ORDR_OP),
				array('id'=>'OL','text'=>'&nbsp;&nbsp;' . ORDR_OL),
				array('id'=>'PO','text'=>'&nbsp;&nbsp;' . ORDR_PO),
				array('id'=>'OM','text'=>'&nbsp;&nbsp;' . ORDR_OM),
				array('id'=>'OT','text'=>'&nbsp;&nbsp;' . ORDR_OT),
				array('id'=>'PM','text'=>'&nbsp;&nbsp;' . ORDR_PM),
				array('id'=>'DD','text'=>'&nbsp;&nbsp;' . ORDR_DD),
				array('id'=>'PF','text'=>'&nbsp;&nbsp;' . ORDR_PF),
				);
	//Personal Details
	$fields_details['U']=array(
				array('id'=>'TITLE_U','text'=>TEXT_TITLE_U),
				array('id'=>'CF','text'=>'&nbsp;&nbsp;' . CUST_CF),
				array('id'=>'CL','text'=>'&nbsp;&nbsp;' . CUST_CL),
				array('id'=>'CM','text'=>'&nbsp;&nbsp;' . CUST_CM),
				array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
                array('id'=>'P','text'=>'&nbsp;&nbsp;' . TEXT_PW),
				array('id'=>'AL','text'=>'&nbsp;&nbsp;' . TEXT_AL),
				array('id'=>'CT','text'=>'&nbsp;&nbsp;' . CUST_CT),
				array('id'=>'CP','text'=>'&nbsp;&nbsp;' . CUST_CP),
				array('id'=>'CC','text'=>'&nbsp;&nbsp;' . CUST_CC),
				array('id'=>'CS','text'=>'&nbsp;&nbsp;' . CUST_CS),
				array('id'=>'CE','text'=>'&nbsp;&nbsp;' . CUST_CE),
				array('id'=>'CU','text'=>'&nbsp;&nbsp;' . CUST_CU),
				array('id'=>'CO','text'=>'&nbsp;&nbsp;' . CUST_CO),
				array('id'=>'CA','text'=>'&nbsp;&nbsp;' . CUST_CA),
				);
	//Billing
	$fields_details['B']=array(
				array('id'=>'TITLE_B','text'=>TEXT_TITLE_B),
				array('id'=>'NA','text'=>'&nbsp;&nbsp;' . BILL_NA),
				array('id'=>'CM','text'=>'&nbsp;&nbsp;' . BILL_CM),
				array('id'=>'CT','text'=>'&nbsp;&nbsp;' . BILL_CT),
				array('id'=>'CP','text'=>'&nbsp;&nbsp;' . BILL_CP),
				array('id'=>'CC','text'=>'&nbsp;&nbsp;' . BILL_CC),
				array('id'=>'CS','text'=>'&nbsp;&nbsp;' . BILL_CS),
				array('id'=>'CE','text'=>'&nbsp;&nbsp;' . BILL_CE),
				array('id'=>'CU','text'=>'&nbsp;&nbsp;' . BILL_CU),
				);
	//Delivery
	$fields_details['D']=array(
				array('id'=>'TITLE_D','text'=>TEXT_TITLE_D),
				array('id'=>'NA','text'=>'&nbsp;&nbsp;' . DELI_NA),
				array('id'=>'CM','text'=>'&nbsp;&nbsp;' . DELI_CM),
				array('id'=>'CT','text'=>'&nbsp;&nbsp;' . DELI_CT),
				array('id'=>'CP','text'=>'&nbsp;&nbsp;' . DELI_CP),
				array('id'=>'CC','text'=>'&nbsp;&nbsp;' . DELI_CC),
				array('id'=>'CS','text'=>'&nbsp;&nbsp;' . DELI_CS),
				array('id'=>'CE','text'=>'&nbsp;&nbsp;' . DELI_CE),
				array('id'=>'CU','text'=>'&nbsp;&nbsp;' . DELI_CU),
				);
	//Store Details
	$fields_details['T']=array(
				array('id'=>'TITLE_T','text'=>TEXT_TITLE_T),
				array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
				array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
				array('id'=>'AD','text'=>'&nbsp;&nbsp;' . TEXT_AD),
				array('id'=>'AU','text'=>'&nbsp;&nbsp;' . TEXT_AU),
				array('id'=>'AP','text'=>'&nbsp;&nbsp;' . TEXT_AP),
				array('id'=>'FL','text'=>'&nbsp;&nbsp;' . TEXT_FL),
				array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
				array('id'=>'AL','text'=>'&nbsp;&nbsp;' . TEXT_AL),
				);

	//Invoice Details
	$fields_details['I']=array(array('id'=>'TITLE_I','text'=>TEXT_TITLE_I),
				array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
				array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
				array('id'=>'NO','text'=>'&nbsp;&nbsp;' . TEXT_NO),
				array('id'=>'OP','text'=>'&nbsp;&nbsp;' . TEXT_OP),
				array('id'=>'OL','text'=>'&nbsp;&nbsp;' . TEXT_OL),
				array('id'=>'OL','text'=>'&nbsp;&nbsp;' . TEXT_OM),
				array('id'=>'PO','text'=>'&nbsp;&nbsp;' . TEXT_PO),
				array('id'=>'PM','text'=>'&nbsp;&nbsp;' . TEXT_PM),
				array('id'=>'DT','text'=>'&nbsp;&nbsp;' . PAYMT_DT),
				array('id'=>'BA','text'=>'&nbsp;&nbsp;' . ORDR_BA),
				array('id'=>'SA','text'=>'&nbsp;&nbsp;' . ORDR_SA),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'AD','text'=>'&nbsp;&nbsp;' . STORE_AD),
				array('id'=>'IP','text'=>'&nbsp;&nbsp;' . LOG_IP)
				);
				
				//print_r($fields_details);
	//Customer Account	
	$fields_details['P']=array(array('id'=>'TITLE_P','text'=>TEXT_TITLE_C),
				array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
				array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
				array('id'=>'SA','text'=>'&nbsp;&nbsp;' . TEXT_SA),
				array('id'=>'TN','text'=>'&nbsp;&nbsp;' . CUST_CO),
				array('id'=>'EM','text'=>'&nbsp;&nbsp;' . TEXT_EA),
				array('id'=>'SU','text'=>'&nbsp;&nbsp;' . TEXT_PC),
				array('id'=>'SU','text'=>'&nbsp;&nbsp;' . CUST_CE),
				array('id'=>'SU','text'=>'&nbsp;&nbsp;' . TEXT_CT),
				array('id'=>'SU','text'=>'&nbsp;&nbsp;' . TEXT_CY),
				array('id'=>'TITLE_O','text'=>TEXT_TITLE_O),
				array('id'=>'PF','text'=>'&nbsp;&nbsp;' . TEXT_PF),
				array('id'=>'NO','text'=>'&nbsp;&nbsp;' . TEXT_NO), 
				array('id'=>'OP','text'=>'&nbsp;&nbsp;' . TEXT_OP),
				array('id'=>'OL','text'=>'&nbsp;&nbsp;' . TEXT_OM),
				array('id'=>'PO','text'=>'&nbsp;&nbsp;' . TEXT_PO),
				array('id'=>'PM','text'=>'&nbsp;&nbsp;' . TEXT_PM),
				array('id'=>'TITLE_O','text'=>TEXT_STORE_DETAILS),
				array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
				array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
				array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
				array('id'=>'CM','text'=>'&nbsp;&nbsp;' . CUST_CM),
				array('id'=>'AD','text'=>'&nbsp;&nbsp;' . STORE_AD) 
				);
	//These are the placeholders for the contact us template
	$fields_details['N']=array(array('id'=>'TITLE_N','text'=>TEXT_TITLE_N),
		array('id'=>'T1','text'=>'&nbsp;&nbsp;' . TEXT_TN1),
		array('id'=>'T2','text'=>'&nbsp;&nbsp;' . TEXT_TN2),
		array('id'=>'T3','text'=>'&nbsp;&nbsp;' . TEXT_TN3),
		array('id'=>'B1','text'=>'&nbsp;&nbsp;' . TEXT_BT1),
		array('id'=>'B2','text'=>'&nbsp;&nbsp;' . TEXT_BT2),
		array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
		array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
		array('id'=>'TM','text'=>'&nbsp;&nbsp;' . TEXT_TM)
	);
			//These affect all templates
			$fields_type['AUT']='A'; //osConcert Registration Confirmation
			$fields_type['AUR']='A'; //Admin Password
			$fields_type['CUS']='C'; //osConcert Account Confirmation
			$fields_type['CUX']='C'; //osConcert Forgotten Password
			$fields_type['APV']='C'; //Account Under Review
			$fields_type['ADM']='C'; //Account Review Notification
			$fields_type['OSU']='C'; //osConcert Order Status Update
			$fields_type['OAR']='C'; //Order Amount Refunded

			$fields_type['ACU']='U';
			$fields_type['MIV']='I'; //Order Invoice
			
			$fields_type['TEM']='U_D_B_O';
			$fields_type['PSP']='P_B_D';
			$fields_type['CON']='N'; //Contact Us
			
	$MESSAGE=array();
	$MESSAGE=array(
				array('id'=>'AUT','text'=>TEXT_TYPE_AUT,'mode'=>1),
				array('id'=>'AUR','text'=>TEXT_TYPE_AUR,'mode'=>1),
				array('id'=>'CUS','text'=>TEXT_TYPE_CUS,'mode'=>1),
				array('id'=>'CUX','text'=>TEXT_TYPE_CUX,'mode'=>1),
				array('id'=>'CUR','text'=>TEXT_TYPE_CUR,'mode'=>1),
				array('id'=>'APV','text'=>TEXT_TYPE_APV,'mode'=>1),
				array('id'=>'ADM','text'=>TEXT_TYPE_ADM,'mode'=>1),
				array('id'=>'OSU','text'=>TEXT_TYPE_OSU,'mode'=>1),
				array('id'=>'OAR','text'=>TEXT_TYPE_OAR,'mode'=>1), 
				
				 array('id'=>'ACU','text'=>TEXT_TYPE_ACU,'mode'=>2),
				// array('id'=>'MIV','text'=>TEXT_TYPE_MIV,'mode'=>5),
				
				array('id'=>'CON','text'=>TEXT_TYPE_CON,'mode'=>5)
				);
					

	$EMAILMSG=(new instance)->getTweakObject('display.marketingEmailMessages');
	$EMAILMSG->pagination=false;
	checkAJAX('EMAILMSG');

	$FSESSION->set("AJX_ENCRYPT_KEY",rand(1,10));
	$jsData->VARS["page"]=array('lastAction'=>false,
								'opened'=>array(),
								'locked'=>false,
								'NUlanguages'=>$LANGUAGES,
								'imgPath'=>DIR_WS_IMAGES,
								"menu"=>array(),
								'editorLoaded'=>false,
								'link'=>tep_href_link('marketing_email_messages.php'),
								'NUeditorControls'=>array(),
								'searchMode'=>false,
								'AJX_KEY'=>$FSESSION->AJX_ENCRYPT_KEY,
								'crypted'=>$ENCRYPTED,
								'alterRows'=>true);
	$jsData->VARS["page"]["template"]=array("ERR_EMPTY_SUBJECT"=>ERR_EMPTY_SUBJECT,
											"ERR_EMPTY_REPLY_TO"=>ERR_EMPTY_REPLY_TO,
											"ERROR_CUSTOMER_ADDRESS"=>ERROR_CUSTOMER_ADDRESS,
											"TEXT_LOADING"=>TEXT_LOADING_DATA,
											"UPDATE_IMAGE"=>TEXT_UPDATE_IMAGE,
											"UPDATE_DATA"=>TEXT_UPDATE_DATA,
											"ERR_INVALID_REPLY_TO"=>ERR_INVALID_REPLY_TO,
											"UPDATE_ORDER"=>TEXT_UPDATE_ORDER,
											"PRD_DELETING"=>TEXT_PRD_DELETING,
											"ERR_EMPTY_TITLE"=>ERR_EMPTY_TITLE);
	$jsData->VARS["page"]["NUmenuGroups"]=array("normal","update","mail");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html <?php echo HTML_PARAMS; ?>>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
			<title><?php echo TITLE; ?></title>
			<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
			<script language="javascript" src="includes/general.js"></script>
			<script language="javascript" src="includes/http.js"></script>
			<?php
				require(DIR_WS_INCLUDES . 'tweak/' . HTML_EDITOR . '.php');
				textEditorLoadJS();
			?>
			<script type="text/javascript" src="includes/aim.js"></script>
			<script type="text/javascript" src="includes/tweak/js/ajax.js"></script>
			<script type="text/javascript" src="includes/tweak/js/marketing_email_messages.js"></script>
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
				<tr>
					<td >
						<tr height="20" id="messageBoard" style="display:none">
							<td id="messageBoardText">
								</td>
						</tr>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td id="emsgtotalContentResult">
									<?php $EMAILMSG->doGetMessageList('EM',0);?>
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
				<form name="fileUpload" id="fileUpload" action="marketing_email_messages.php?AJX_CMD=GL_ImageUpload" method="post" enctype="multipart/form-data" style="visibility:hidden;">
			<input type="hidden" name="image_list" id="image_list" value="">
			<input type="hidden" name="image_resize" value="1">	
			</form>
				<div class="ajxMessageWindow" id="ajxLoad" style="display:none;width:400px;height:70px;"><span id="ajxLoadMessage">Loading...</span><br><?php echo tep_image(DIR_WS_IMAGES . 'layout/ajax_load.gif');?></div>
			</table>	
			<!-- body_eof //-->
		
			<!-- footer //-->
			<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
			<!-- footer_eof //-->
		</body>
	</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
