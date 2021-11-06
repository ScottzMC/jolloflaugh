<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
defined('_FEXEC') or die();
class marketingProductsMessage
{
	var $pagination;
	var $splitResult;
	var $type;

	function __construct() {
	$this->pagination=false;
	$this->splitResult=false;
	$this->type='markSupport';
	}
	function doItems(){
		global $FREQUEST,$jsData;
	?>
	<div class="main" id="markSupport-lmessage"></div>
	<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
		<tr><td><?php 	echo mergeTemplate($rep_array,$template); ?></td></tr>
		<tr>
			<td><table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
					<tr class="dataTableHeadingRow">
						<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main" width="60%"><b><?php echo TEXT_MESSAGE_TYPE;?></b></td>
								<td width="40%">&nbsp;</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><div align="center"><?php $this->doList();?></div></td>
					</tr>	
				</Table>
			</td>
		</tr>
	</table>
	<?php 
	}
	function doList($coupon_id=0){
		global $FREQUEST,$jsData;
		$page=$FREQUEST->getvalue('page','int',1);
		$query_split=false;
	 	$message=array();
		
		
		//v
		$message=array(
				array('id'=>'PRD','text'=>TEXT_TYPE_PRD,'mode'=>1),
				//cartzone take out a bunch of unused messages
				array('id'=>'PRS','text'=>TEXT_TYPE_PRS,'mode'=>1),
				//array('id'=>'PST','text'=>TEXT_TYPE_PST,'mode'=>1),
				//array('id'=>'PRS','text'=>TEXT_TYPE_PRS,'mode'=>1),
				//array('id'=>'PRE','text'=>TEXT_TYPE_PRE,'mode'=>1),
				);
		//v
		/*$message=array(
				array('id'=>'SPP','text'=>TEXT_TYPE_SPP,'mode'=>1),
				array('id'=>'LCA','text'=>TEXT_TYPE_LCA,'mode'=>1),
				array('id'=>'RRE','text'=>TEXT_TYPE_RRE,'mode'=>1),
				array('id'=>'TCE','text'=>TEXT_TYPE_TCE,'mode'=>1)
				);*/
		$template=getListTemplate();
		for($i=0;$i<sizeof($message);$i++) {	
			$rep_array=array("ID"=>$message[$i]['id'],
							"TYPE"=>$this->type,
							"NAME"=>$message[$i]['text'],
							"IMAGE_PATH"=>DIR_WS_IMAGES,
							"STATUS"=>'',
							"UPDATE_RESULT"=>'doDisplayResult',
							"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
							"ROW_CLICK_GET"=>'Info',
							"FIRST_MENU_DISPLAY"=>""
							);
			echo mergeTemplate($rep_array,$template);
		}	
	}
	function doInfo($msg_id=''){
		global $FREQUEST,$jsData,$message,$message_type_array;
		if($msg_id=="") $msg_id=$FREQUEST->getvalue("rID");
		/*$message=array(
				array('id'=>'SPP','text'=>TEXT_TYPE_SPP,'mode'=>1),
				array('id'=>'LCA','text'=>TEXT_TYPE_LCA,'mode'=>1),
				array('id'=>'RRE','text'=>TEXT_TYPE_RRE,'mode'=>1),
				array('id'=>'TCE','text'=>TEXT_TYPE_TCE,'mode'=>1)
				);*/
				//v
				$message=array(
				array('id'=>'PRD','text'=>TEXT_TYPE_PRD,'mode'=>1),
				array('id'=>'REM','text'=>TEXT_TYPE_REM,'mode'=>4),
				array('id'=>'PST','text'=>TEXT_TYPE_PST,'mode'=>1),
				array('id'=>'PRS','text'=>TEXT_TYPE_PRS,'mode'=>1),
				array('id'=>'PRE','text'=>TEXT_TYPE_PRE,'mode'=>1),
				);
				//v
		for($icnt=0;$icnt<sizeof($message);$icnt++)
		{
			if($message[$icnt]['id']==$msg_id)
			{
				tep_get_message_details($message[$icnt],$message_type_array[$msg_id],$fields_type[$msg_id]);
				break;
			}
		}
		$jsData->VARS["updateMenu"]=",normal,";
	}	
	function doEdit() {
		global $FREQUEST,$jsData;
		$msg_id=$FREQUEST->getvalue("rID");
		$jsData->VARS['doFunc']=array('type'=>'markSupport','data'=>'doEmailEditor');
		//v
		$fields_type['PRD']='U_D_B_O';

	$format_array=array(array('id'=>'T','text'=>TEXT_FORMAT_TEXT),
						array('id'=>'H','text'=>TEXT_FORMAT_HTML),
						array('id'=>'B','text'=>TEXT_FORMAT_BOTH));
	
	$fields_details['C']=array(
							array('id'=>'TITLE_C','text'=>TEXT_TITLE_C),	
							array('id'=>'CF','text'=>'&nbsp;&nbsp;' . CUST_CF),
							array('id'=>'CL','text'=>'&nbsp;&nbsp;' . CUST_CL),
							array('id'=>'NO','text'=>'&nbsp;&nbsp;' . ORDR_NO),
							array('id'=>'OP','text'=>'&nbsp;&nbsp;' . ORDR_OP),
							array('id'=>'OL','text'=>'&nbsp;&nbsp;' . ORDR_OL),
							array('id'=>'PD','text'=>'&nbsp;&nbsp;' . ORDR_PD),
							array('id'=>'PS','text'=>'&nbsp;&nbsp;' . TEXT_P_STATUS),
							array('id'=>'OC','text'=>'&nbsp;&nbsp;' . TEXT_OC),
							array('id'=>'CM','text'=>'&nbsp;&nbsp;' . CUST_CM),
							array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
							array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
							array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
							//array('id'=>'AL','text'=>'&nbsp;&nbsp;' . TEXT_AL),
							array('id'=>'CT','text'=>'&nbsp;&nbsp;' . CUST_CT),
							array('id'=>'CP','text'=>'&nbsp;&nbsp;' . CUST_CP),
							array('id'=>'CC','text'=>'&nbsp;&nbsp;' . CUST_CC),
							array('id'=>'CS','text'=>'&nbsp;&nbsp;' . CUST_CS),
							array('id'=>'CE','text'=>'&nbsp;&nbsp;' . CUST_CE),
							array('id'=>'CU','text'=>'&nbsp;&nbsp;' . CUST_CU),
							array('id'=>'CO','text'=>'&nbsp;&nbsp;' . CUST_CO),
							array('id'=>'CA','text'=>'&nbsp;&nbsp;' . CUST_CA),
							array('id'=>'PN','text'=>'&nbsp;&nbsp;' . TEXT_PN),
							array('id'=>'PP','text'=>'&nbsp;&nbsp;' . TEXT_PP),
							array('id'=>'SP','text'=>'&nbsp;&nbsp;' . TEXT_SP),
							array('id'=>'PQ','text'=>'&nbsp;&nbsp;' . TEXT_P_Q),
							);				
	$fields_details['T']=array(
							array('id'=>'TITLE_T','text'=>TEXT_TITLE_T),
							array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
							array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
							array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
							);
											
	$fields_details['P']=array(
							array('id'=>'TITLE_P','text'=>TEXT_TITLE_P),
							array('id'=>'PN','text'=>'&nbsp;&nbsp;' . TEXT_PN),
							array('id'=>'PP','text'=>'&nbsp;&nbsp;' . TEXT_PP),
							array('id'=>'PA','text'=>'&nbsp;&nbsp;' . TEXT_PA),
							array('id'=>'MO','text'=>'&nbsp;&nbsp;' . TEXT_P_M),
							array('id'=>'DA','text'=>'&nbsp;&nbsp;' . TEXT_P_DA),
							array('id'=>'PW','text'=>'&nbsp;&nbsp;' . TEXT_P_W),
							array('id'=>'UP','text'=>'&nbsp;&nbsp;' . TEXT_P_A_UP),
							array('id'=>'PU','text'=>'&nbsp;&nbsp;' . TEXT_P_U),
							array('id'=>'PQ','text'=>'&nbsp;&nbsp;' . TEXT_P_Q),
							array('id'=>'TITLE_U','text'=>TEXT_TITLE_U),
							array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
							array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
							array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
							);
	$fields_details['S']=array(
							array('id'=>'TITLE_P','text'=>TEXT_TITLE_P),
							array('id'=>'PD','text'=>'&nbsp;&nbsp;' . ORDR_PD),
							//array('id'=>'PN','text'=>'&nbsp;&nbsp;' . TEXT_PN),
							//array('id'=>'PP','text'=>'&nbsp;&nbsp;' . TEXT_PP),
							//array('id'=>'PQ','text'=>'&nbsp;&nbsp;' . TEXT_P_Q),
							array('id'=>'PS','text'=>'&nbsp;&nbsp;' . TEXT_P_STATUS),
							);						
	$fields_details['O']=array(
							array('id'=>'TITLE_O','text'=>TEXT_TITLE_O),
							array('id'=>'NO','text'=>'&nbsp;&nbsp;' . ORDR_NO),
							array('id'=>'OP','text'=>'&nbsp;&nbsp;' . ORDR_OP),
							array('id'=>'OL','text'=>'&nbsp;&nbsp;' . ORDR_OL),
							//array('id'=>'PO','text'=>'&nbsp;&nbsp;' . ORDR_PO),
							array('id'=>'OM','text'=>'&nbsp;&nbsp;' . ORDR_OM),
							//array('id'=>'OT','text'=>'&nbsp;&nbsp;' . ORDR_OT),
							array('id'=>'PM','text'=>'&nbsp;&nbsp;' . ORDR_PM),
							array('id'=>'DD','text'=>'&nbsp;&nbsp;' . ORDR_DD),
							array('id'=>'DL','text'=>'&nbsp;&nbsp;' . TEXT_DL),
							//array('id'=>'PF','text'=>'&nbsp;&nbsp;' . ORDR_PF),
							//array('id'=>'PD','text'=>'&nbsp;&nbsp;' . TEXT_PDF)
							);
	$fields_details['U']=array(
							array('id'=>'TITLE_U','text'=>TEXT_TITLE_U),
							array('id'=>'CF','text'=>'&nbsp;&nbsp;' . CUST_CF),
							array('id'=>'CL','text'=>'&nbsp;&nbsp;' . CUST_CL),
							array('id'=>'CM','text'=>'&nbsp;&nbsp;' . CUST_CM),
							array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
							array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
							array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
							//array('id'=>'AL','text'=>'&nbsp;&nbsp;' . TEXT_AL),
							array('id'=>'CT','text'=>'&nbsp;&nbsp;' . CUST_CT),
							array('id'=>'CP','text'=>'&nbsp;&nbsp;' . CUST_CP),
							array('id'=>'CC','text'=>'&nbsp;&nbsp;' . CUST_CC),
							array('id'=>'CS','text'=>'&nbsp;&nbsp;' . CUST_CS),
							array('id'=>'CE','text'=>'&nbsp;&nbsp;' . CUST_CE),
							array('id'=>'CU','text'=>'&nbsp;&nbsp;' . CUST_CU),
							array('id'=>'CO','text'=>'&nbsp;&nbsp;' . CUST_CO),
							array('id'=>'CA','text'=>'&nbsp;&nbsp;' . CUST_CA),
							);
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
	$fields_details['I']=array(array('id'=>'TITLE_I','text'=>TEXT_TITLE_O),
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
							array('id'=>'AD','text'=>'&nbsp;&nbsp;' . STORE_AD)
							);
		
		
		

	 	 echo tep_draw_form('insert_message','marketing_products_message.php');
		 echo tep_draw_hidden_field('message_id',$msg_id);
						
	 ?>	
 	 <table>				  
	<?php
 		$mes_array=array('message_id'=>'', 'message_type'=>'', 'message_send'=>'', 'message_subject'=>'', 'message_reply_to'=>STORE_OWNER_EMAIL_ADDRESS, 'message_text'=>'', 'message_format'=>'');
		if($msg_id!="") {
			$email_sql="Select * from " . TABLE_EMAIL_MESSAGES . " where message_type='" . $msg_id . "'";
			$email_query=tep_db_query($email_sql);
			$mes_array=tep_db_fetch_array($email_query);
		}	
		if(is_array($mes_array)) $mesInfo=new objectInfo($mes_array);
		$fields_type=$fields_type[$msg_id];
		$list_add=explode("_",$fields_type);
		$array_result=array();
		for ($icnt=0;$icnt<sizeof($list_add);$icnt++){
			$array_result=array_merge($array_result,$fields_details[$list_add[$icnt]]);
		}
		$type_array=array(array('id'=>'D','text'=>TEXT_DAYS),array('id'=>'H','text'=>TEXT_HOURS));
		$register_array=array(array('id'=>'B','text'=>TEXT_BEFORE_REGISTER),array('id'=>'A','text'=>TEXT_AFTER_REGISTER));
		$mes_send_string='<span class="smallText" id="send_days_hours">'.tep_draw_input_field('message_send',substr($mesInfo->message_send,1),"size=5").'</span><span style="display:none" class="smallText" id="send_days_hours1">'.tep_draw_input_field('message_send1',substr($mesInfo->message_send1,1),"size=5") .'</span>&nbsp;'.tep_draw_pull_down_menu('type_send',$type_array,'','onchange="javascript:do_action();"').'<span id="send_type" class="smallText"> '. tep_draw_pull_down_menu('send_email_date',$register_array,$send_email_date).'</span><span id="send_type1" style="display:none"  class="smallText">&nbsp;' .tep_draw_pull_down_menu('send_email_time',$register_array,$send_email_time). '</span>';
		?>
		<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
		<tr>
			<td>													
			<table width=100% border="0" cellspacing="5" cellpadding="2">
				<tr>
					<td class="smallText" nowrap><?php echo TEXT_MESSAGE_SUBJECT; ?>
					<td class="smallText"><?php echo tep_draw_input_field('message_subject',$mesInfo->message_subject,'size=40',true); ?></td>
				</tr>
				<tr>
					<td class="smallText" nowrap><?php echo TEXT_MESSAGE_REPLY_TO; ?>
					<td class="smallText"><?php echo tep_draw_input_field('message_reply_to',$mesInfo->message_reply_to,'size=40',true); ?></td>
				</tr>
				<tr><td class="smallText" valign=top nowrap><?php echo TEXT_MESSAGE_TEXT ; ?></td>
					<td class="smallText" colspan="2">														
						<table border="0" cellspacing="0" cellpadding="0" valign="top">
							<tr>
								<td valign="top" class="smallText">
								<?php
									//print_r($mesInfo);
									echo $display_count_text;
									echo tep_draw_textarea_field('message_text','soft','120','34',$mesInfo->message_text,'id="message_text" ' .$text_params) . "</span>"; 
								?>
								</td>
								<td style="background:ButtonFace;" valign="bottom" align="center">
								<?php 
									echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';
									echo tep_draw_pull_down_menu('fields',$array_result,'','style="height:' .((strpos($FREQUEST->servervalue('HTTP_USER_AGENT'),"MSIE")>0)?'225':'241').'" size=15 ondblClick="AddField()"');
								?>
								</td>									
							</tr>
						</table>													
					</td>
				</tr>
				<tr>
					<td class="smallText"><?php echo TEXT_MESSAGE_FORMAT; ?>
					<td class="smallText"><?php echo tep_draw_pull_down_menu('message_format',$format_array,$mesInfo->message_format); ?></td>
				</tr>
			</table></td>
		</tr>
	</table>
</form>		
<?php
	$jsData->VARS["updateMenu"]=",update,";
	}
	function doUpdate() {
		global $FREQUEST,$jsData;
		$msg_id=$FREQUEST->postvalue('message_id');
		$insert=true;
		if($msg_id!="") {
			$count_query=tep_db_query("Select * from " . TABLE_EMAIL_MESSAGES . " where message_type='" . $msg_id . "'");
			if(tep_db_num_rows($count_query)>0) { 
				$insert=false;
			}	
			else {
				$insert=true;	
			}	
		}	
					
				
		$message_format_post=$FREQUEST->postvalue('message_format');
		$message_text_post=$FREQUEST->postvalue('message_text');
		$message_text=($message_format_post!='T'?$message_text_post:strip_tags($message_text_post,'<br>'));
		$sql_array=array('message_type'=>$FREQUEST->postvalue('message_id'),
						'message_subject'=>$FREQUEST->postvalue('message_subject'),
						'message_reply_to'=>$FREQUEST->postvalue('message_reply_to'),
						'message_text'=>tep_db_prepare_input($message_text),
						'message_format'=>$message_format_post);
			if($insert) 
			{	
				tep_db_perform(TABLE_EMAIL_MESSAGES,$sql_array);
			} else 
			{
				tep_db_perform(TABLE_EMAIL_MESSAGES,$sql_array,'update','message_type="' . $msg_id . '"');
			}
			$jsData->VARS["updateMenu"]=",normal,";
			$jsData->VARS["prevAction"]=array('id'=>$msg_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
			$this->doInfo($msg_id);
	}
	function doTestMailSendDisplay(){
		global $FREQUEST,$jsData,$FSESSION;
		$msg_id=$FREQUEST->getvalue('rID');
		$test_message='';
		
		$email_query=tep_db_query("Select * from " . TABLE_EMAIL_MESSAGES . " where message_type='" . $msg_id . "'");
		if(tep_db_num_rows($email_query)>0) 
			$test_message='<p><span class="smallText">' . TEXT_TEST_MAIL_INTRO . '</span>';
		else
			$test_message='<p><span class="smallText">' . TEXT_NO_TEST_MAIL_INTRO . '</span>';
	?>
		<form  name="markSupportTestSubmit" id="markSupportTestSubmit" action="marketing_support_pack.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="message_id" value="<?php echo tep_output_string($msg_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%" height="500">
			<tr>
				<td class="main" id="markSupport<?php echo $msg_id;?>message"></td>
			</tr>
			<tr>
				<td class="main"><?php echo $test_message;?></td>
			</tr>
			<tr height="40">
				<td class="main" style="vertical-align:bottom">
					<p>
					<?php if(tep_db_num_rows($email_query)>0) { ?>
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $msg_id;?>',type:'markSupport',get:'TestMailSend',result:doDisplayResult,message:page.template['PRD_DELETING'],'uptForm':'markSupportTestSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_send('button_send.gif');?></a>&nbsp;
					<?php } ?>
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $msg_id;?>',type:'markSupport',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
				</td>
			</tr>
			<tr>
				<td><hr/></td>
			</tr>
			<tr>
				<td valign="top" class="mainpageInfo"><?php echo $this->doInfo($msg_id);?></td>
			</tr>
		</table>
		</form>
		
<?php	$jsData->VARS["updateMenu"]="";
	}
	function doTestMailSend(){
		global $FREQUEST,$jsData,$LANGUAGES;
		$msg_id=$FREQUEST->postvalue('message_id');
		
		if ($msg_id!=""){
			tep_send_default_test_email($msg_id);
			$this->doInfo($msg_id);
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_TEST_MAIL_SENT_SUCCESS);
		} else {
			echo "Err:" . TEXT_TEST_MAIL_NOT_SENT;
		}
		
	}
	function doEmailDeleteDisplay(){
		global $FREQUEST,$jsData,$FSESSION;
		$msg_id=$FREQUEST->getvalue('rID');
		$delete_message='';
		$email_query=tep_db_query("Select * from " . TABLE_EMAIL_MESSAGES . " where message_type='" . $msg_id . "'");
		if(tep_db_num_rows($email_query)>0) 
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_PAGE_INTRO . '</span>';
		else
			$delete_message='<p><span class="smallText">' . TEXT_NO_DELETE_INTRO . '</span>';
	?>
		<form  name="markSupportDeleteSubmit" id="markSupportDeleteSubmit" action="marketing_support_pack.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="message_id" value="<?php echo tep_output_string($msg_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="main" id="markSupport<?php echo $msg_id;?>message">
				</td>
			</tr>
			<tr>
				<td class="main">
				<?php echo $delete_message;?>
				</td>
			</tr>
			<tr height="40">
				<td class="main" style="vertical-align:bottom">
					<p>
					<?php if(tep_db_num_rows($email_query)>0) { ?>
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $msg_id;?>',type:'markSupport',get:'EmailDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'markSupportDeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
					<?php } ?>
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $msg_id;?>',type:'markSupport',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
				</td>
			</tr>
			<tr>
				<td><hr/></td>
			</tr>
			<tr>
				<td valign="top" class="mainpageInfo"><?php echo $this->doInfo($msg_id);?></td>
			</tr>
		</table>
		</form>
<?php	$jsData->VARS["updateMenu"]="";
	}
	function doEmailDelete(){
		global $FREQUEST,$jsData,$LANGUAGES;
		$msg_id=$FREQUEST->postvalue('message_id');
		if ($msg_id!=""){
			tep_db_query("DELETE from " . TABLE_EMAIL_MESSAGES . " where message_type='" . $msg_id . "'");
			$this->doItems();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_EMAIL_DELETE_SUCCESS);
		} else {
			echo "Err:" . TEXT_EMAIL_NOT_DELETED;
		}
		
	}
}
function getListTemplate(){
	ob_start();
	getTemplateRowTop();
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="28%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
					<td width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
						<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'EmailDeleteDisplay','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':'##ID##','get':'TestMailSendDisplay','result':doDisplayResult,'style':'boxRow','type':'markSupport','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Test Mail"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						</span>
						<span id="##TYPE####ID##mupdate" style="display:none">
						<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':'##ID##','get':'Update','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':validateForm,'uptForm':'insert_message',extraFunc:textEditorRemove,'customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
						<img src="##IMAGE_PATH##template/img_bar.gif"/>
						<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'markSupport',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
						</span>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
<?php
	getTemplateRowBottom();
	$contents=ob_get_contents();
	ob_end_clean();
	return $contents;
}
function tep_get_message_details($message_array,$message_type_array,$fields_type)
{
	global $mes_array,$format_array,$fields_details,$alert_message,$FREQUEST;
	$sql_query="SELECT message_id,message_reply_to,message_type,message_send,message_subject,message_text,message_format from " . TABLE_EMAIL_MESSAGES . " where 1=1";
	$message_type_query=tep_db_query($sql_query . " and message_type='" . tep_db_input($message_array['id']) . "'");
	
	$result_cnt=tep_db_num_rows($message_type_query);
	$result=tep_db_fetch_array($message_type_query);
	
	if ($result_cnt>0){
		$mesInfo=new objectInfo($result);
	?>
	<table cellpadding="2" cellspacing="0" width="100%">
<?php 		if(is_array($message_type_array))
			{
				echo tep_draw_form('message_type','marketing_products_message.php');
				echo tep_draw_hidden_field('id',$result['message_id']);
				echo tep_draw_hidden_field('msg_type',$result['message_type']);
				echo tep_draw_pull_down_menu('message_send',$message_type_array,$result['message_send'],' onchange="javascript:do_change_send(\'' . $type_id . '\');"');
				echo '</form>';
			}
?>	<tr>
		<Td colspan="2"><table width="100%" border="0">
			<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
			<tr><td>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_FROM,STORE_OWNER,STORE_OWNER_EMAIL_ADDRESS)); ?></td>
					</tr>
					<tr>
						<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_TO,TEST_MAIL_FN . ' ' . TEST_MAIL_LN,EVENTS_TEST_EMAIL_ADDRESS)); ?></td>
					</tr>

					<tr>
						<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_REPLY_TO,$result['message_reply_to'])); ?></td>
					</tr>
					<tr>
						<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_SUBJECT,$result['message_subject'])); ?></td>
					</tr>

					<tr>
						<td class="smallText">
					<?php 
							$details=array();
							if ($result['message_format']=='T')
								$details['html_text']=strip_tags(tep_db_prepare_input($result['message_text']),'<br>');						
							else
								$details['html_text']=tep_db_prepare_input($result['message_text']);
							// get display fields;
	
							$replace_array=array();
							tep_merge_details($replace_array,"test_default");
							tep_replace_template($details,$replace_array);
						?>
							<div  style="width:100%;height:500px;overflow:auto"><?php echo $details['html_text'];?></div>
						</td>
					</tr>
				</table>
			</td></tr>
		</table></Td>
	</tr>
</table>
	<?php 	
	}
	else { ?>
	<table cellpadding="2" cellspacing="0" width="100%">
		<Tr>
			<td class="main">
				<table cellpadding="2" cellspacing="0" width="100%">
					<Tr>
						<td class="main" align="center"><?php echo NO_DETAILS_FOUND; ?></td>
					</Tr>
				</table>
			</td>
		</Tr>
	</table>
 <?php 		
	}
}	
?>