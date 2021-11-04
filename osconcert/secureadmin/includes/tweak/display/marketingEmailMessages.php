<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	
	class marketingEmailMessages{
		var $pagination;
		var $splitResult;
		var $type;
		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='emsg';
		}
		
		function doSend_Mail()
		{
		global $FREQUEST,$jsData;
		
		$customers_email_address_post=$FREQUEST->postvalue('customers_email_address');
			
			
			switch ($customers_email_address_post) {
			case '***':
				$mail_query = tep_db_query("select customers_firstname,customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS);
				$mail_sent_to = TEXT_ALL_CUSTOMERS;
				break;
			case '**D':
				$mail_query = tep_db_query("select customers_firstname,customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
				$mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
				break;
			default:
				$customers_email_address = $customers_email_address_post;
				$mail_query = tep_db_query("select customers_firstname, customers_id, customers_lastname, customers_email_address, customers_password from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
				$mail_sent_to = $customers_email_address_post;
				break;
		}
		
		$from = $FREQUEST->postvalue('from');
		$subject = $FREQUEST->postvalue('subject');
		$message = $FREQUEST->postvalue('message_text');
        $chkpwd=$FREQUEST->postvalue('chkpwd');
		
		while ($mail = tep_db_fetch_array($mail_query)) {
			$message_detail=$message;
           	$mimemessage = new email(array('X-Mailer: osConcert'));
			//$mimemessage = new email(array('Content-Type: text/html; charset=ISO-8859-15'));
			$pos=strpos($message,"%%Password%%");

	if($chkpwd==true && $pos)
		{

        $pww=tep_rand(5);
		$password=tep_encrypt_password($pww);
		tep_db_query ("update " . TABLE_CUSTOMERS . " set customers_password = '". tep_db_input($password) ."' where customers_id = '" . tep_db_input($mail['customers_id']) . "'");
		}
		else
		{
        $pww="---- Hidden----";
		}
			$merge_details=array(	TEXT_FN=>$mail['customers_firstname'],
									TEXT_LN=>$mail['customers_lastname'],
									TEXT_SN=>STORE_NAME,
									TEXT_SM=>STORE_OWNER,
									TEXT_SE=>STORE_OWNER_EMAIL_ADDRESS,
									TEXT_LA=>tep_catalog_href_link(FILENAME_AUTOLOGIN,'email=' . $mail['customers_email_address'] . '&id=' . $mail['customers_password']),
									TEXT_EA=>$mail['customers_email_address'],
									TEXT_PW=>$pww
								);
								
								
			
			//while(list($key,$value)=each($merge_details))
//			$message_detail=str_replace("%%" . $key  . "%%",$value,$message_detail);
//			$message_detail=str_replace('�', " ", $message_detail);
//			
//			$message_text=strip_tags($message_detail,'<br>');
//			$message_text=str_replace('<br>',chr(13) . chr(10),$message_text);
//			$message_text=str_replace('<BR>',chr(13) . chr(10),$message_text);
			
			//cartzone fixed bug
			//FOREACH
			//while(list($key,$value)=each($merge_details))
			foreach($merge_details as $key => $value)
			
			$message_detail=str_replace("%%" . $key  . "%%",$value,$message_detail);
			$message_detail=str_replace('�', " ", $message_detail);
			$message_text=strip_tags($message_detail,'<br>');
			$message_text=str_replace('<br>',chr(13) . chr(10),$message_text);
			$message_text=str_replace('<BR>',chr(13) . chr(10),$message_text);
			$message_text= str_replace ( '%5C%22' , "", stripslashes($message_text));
			$message_detail= str_replace ( '%5C%22' , "", stripslashes($message_detail));
			$message_text= str_replace ( '%5C%22' , "", stripslashes($message_text));
			$message_detail= str_replace ( '%5C%22' , "", stripslashes($message_detail));
			$message_text= str_replace ( '%5C%22' , "", stripslashes($message_text));
			$message_detail= str_replace ( '%5C%22' , "", stripslashes($message_detail));

			   
			if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {
				$mimemessage->add_text($message_text);
			} else {
				$mimemessage->add_html($message_detail,$message_text);
			}
			$mimemessage->build_message();
	
			$mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
			
			
			$this->doGetMessageList('EM',0);
				$jsData->VARS["displayMessage"]=array('text'=>'mail sent to '.$mail_sent_to);
				tep_reset_seo_cache('messages');
				
		}
		}
		
		function doMailPreview()
		{
		
		global $FREQUEST,$jsData;
		
		$customers_email_address=$FREQUEST->postvalue('customers_email_address');
		
		$merge_details=array(TEXT_FN=>TEST_MAIL_FN,
                          TEXT_LN=>TEST_MAIL_LN,
			              TEXT_SN=>TEST_MAIL_SN,
    			          TEXT_SM=>TEST_MAIL_SM,
			              TEXT_SE=>TEST_MAIL_SE,
			              TEXT_LA=>TEST_MAIL_LA,
						  TEXT_EA=>TEST_MAIL_EA,
						  TEXT_PW=>TEST_MAIL_PWD
			             );
						 
	
		$from=STORE_OWNER_EMAIL_ADDRESS;
		$subject=$FREQUEST->postvalue('subject');
		$message_text=$FREQUEST->postvalue('message_text');
         $chkpwd=$FREQUEST->postvalue('chkpwd');
		//FOREACH
		//while(list($key,$value)=each($merge_details))
		foreach($merge_details as $key => $value) 
       		$message_text=str_replace("%%" . $key  . "%%",$value,$message_text);	
			
			
	   
		$msg_id=$FREQUEST->postvalue('msg_id');
		
		
		$delete_message='<p><span class="smallText">' . TEXT_MAIL_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_email_messages.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="customers_email_address" value="<?php echo tep_output_string($customers_email_address);?>"/>
				<input type="hidden" name="from" value="<?php echo tep_output_string($from);?>"/>
				<input type="hidden" name="subject" value="<?php echo tep_output_string($subject);?>"/>
                <input type="hidden" name="message_text" value="<?php echo tep_output_string($FREQUEST->postvalue('message_text'));?>"/>
				<!--cartzone fix this bug-->
                <!--<input type="hidden" name="message_text" value="<?php //echo tep_output_string($message_text);?>"/>-->
                <input type="hidden" name="chkpwd" value="<?php echo tep_output_string($chkpwd);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $message_type;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $msg_id;?>',type:'<?php echo $this->type;?>',get:'Send_Mail',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_send('button_send_email.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $msg_id;?>',type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo">
						
						
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
			
			<tr>
				<td width="10%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b><?php echo TEXT_CUSTOMER; ?></b></td>
				<td width="3%" align="left" style="overflow:hidden"  class="main"><?php echo $customers_email_address;?></td>
			</tr>
			<tr>
				<td width="5%" align="right" style="overflow:hidden" class="main"><b><?php echo TEXT_FROM; ?></b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main"><?php echo $from; ?> </td>
			</tr>
			<tr>
				<td width="5%" align="right" style="overflow:hidden" class="main"><b><?php echo TEXT_SUBJECT; ?></b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main"><?php echo $subject;?></td>
			</tr>
			<tr>
				<td width="5%" align="right" style="overflow:hidden" class="main"><b><?php echo TEXT_MESSAGE; ?></b></td>
                <!--cartzone fix bug-->
				<td width="10%"  align="left" style="overflow:hidden" class="main"><?php echo  str_replace ( '%5C%22' , "", stripslashes($message_text)  );?></td>
			</tr>
		</table>
						
						
						
						
						</td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		
		
		
		function doDelete(){
			global $FREQUEST,$jsData;
			$message_type=$FREQUEST->postvalue('message_type');
			
				tep_db_query("delete from " . TABLE_EMAIL_MESSAGES . " where message_type='" . tep_db_input($message_type) . "'");
				
				$this->doGetMessageList('EM',0);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_MESSAGE_DELETE_SUCCESS);
				tep_reset_seo_cache('messages');
			
			
		}
		
		function doDeleteEmailMessages(){
			global $FREQUEST,$jsData;

			$message_type=$FREQUEST->getvalue('rID');
			
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="customers_group.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="message_type" value="<?php echo tep_output_string($message_type);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $message_type;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $message_type;?>',type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $message_type;?>',type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doMessageDetails($message_type);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		
		function doGetMessageList($displayType,$typeId){
			global $jsData,$MESSAGE;
			$heading = '';
			switch($displayType){
				case 'EM':
					$heading = TEXT_HEADING_TITLE;
				break;
			}
?>			
	
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<tr class="dataTableHeadingRow">
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="main">  
									<b><?php echo $heading;?></b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?php
			for($i=0;$i<count($MESSAGE);$i++)
			{
			
				$template=getMessagesListTemplate();
				$rep_array=array(	"ID"=>$MESSAGE[$i]['id'],
									"TYPE"=>$this->type,
									"MESSAGE"=>$MESSAGE,
									"MODE"=>$MESSAGE[$i]['mode'],
									"NAME"=>$MESSAGE[$i]['text'],
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"DISPLAY_TYPE"=>$displayType,
									"UPDATE_RESULT"=>'doDisplayResult',
									"ALTERNATE_ROW_STYLE"=>($i%2==0?"listItemEven":"listItemOdd"),
									"ROW_CLICK_GET"=>'MessageDetails',
									"FIRST_MENU_DISPLAY"=>(($MESSAGE[$i]['text']==TEXT_TYPE_ACU)?"display:none":"")
								);
				echo mergeTemplate($rep_array,$template);
			}
?>
			</table>		
<?php
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
		}

		function doMessageUpdate()
		{
		
			global $FREQUEST,$jsData;
			
			
			$message_id=$FREQUEST->postvalue("message_id","int",-1);

			$insert=true;
			if ($message_id>0) $insert=false;
													
			$message_format_post=$FREQUEST->postvalue('message_format');
			$message_text_post=$FREQUEST->postvalue('message_text');
	
			$message_text=($message_format_post!='T'?$message_text_post:strip_tags($message_text_post,'<br>'));
			$sql_array=array(	'message_type'=>$FREQUEST->postvalue('message_type'),
								'message_subject'=>$FREQUEST->postvalue('message_subject'),
								'message_reply_to'=>$FREQUEST->postvalue('message_reply_to'),
								'message_text'=>tep_db_prepare_input($message_text),
								'message_format'=>$message_format_post);
			$message_type=$FREQUEST->postvalue('message_type');
			
			if ($insert)
			{
				tep_db_perform(TABLE_EMAIL_MESSAGES,$sql_array);
				$message_id=tep_db_insert_id();
			} else {
				tep_db_perform(TABLE_EMAIL_MESSAGES,$sql_array,'update',"message_id='" . $message_id . "'");
			}
			
				
				$jsData->VARS["prevAction"]=array('id'=>$message_type,'get'=>'MessageDetails','type'=>$this->type,'style'=>'boxRow');
				$this->doMessageDetails($FREQUEST->postvalue('message_type'),'EM');
				$jsData->VARS["updateMenu"]=",normal,";
			
			
		}
		function doMessageDetails($msg_id='',$display_type=''){
			global $FSESSION,$FREQUEST,$jsData,$currencies,$fields_type,$fields_details,$mes_array,$format_array;

			if($msg_id=='') $msg_id=$FREQUEST->getvalue("rID");
			
			if($display_type=='') $display_type=$FREQUEST->getvalue("dType");
			$mode=$FREQUEST->getvalue("mode");
				
			switch($display_type){
				case 'EM':
					$sql_query="SELECT message_id,message_reply_to,message_type,message_send,message_subject,message_text,message_format from " . TABLE_EMAIL_MESSAGES . " where message_type ='".tep_db_input($msg_id)."' ";
				break;
			}
			$message_query = tep_db_query($sql_query);
			
			if (($mode==1) || ($mode==4) || ($mode==5)){
			if(tep_db_num_rows($message_query)>0){
				$message_result = tep_db_fetch_array($message_query);
				
?>		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td>
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="3" cellspacing="0" width="100%">
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_FROM,STORE_OWNER,STORE_OWNER_EMAIL_ADDRESS)); ?></td>
								</tr>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_TO,TEST_MAIL_FN . ' ' . TEST_MAIL_LN,EVENTS_TEST_EMAIL_ADDRESS)); ?></td>
								</tr>
								<?php if($display_type !='SV' && $dtype !='SS' && $display_type !='SE'){?>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_REPLY_TO,$message_result['message_reply_to'])); ?></td>
								</tr>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_SUBJECT,$message_result['message_subject'])); ?></td>
								</tr>
								<?php }?>
							</table>
						</td>
					</tr>
					<tr>
						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
					<tr>
						<td class="smallText">
						<?php 
							
								$details=array();
								if ($message_result['message_format']=='T')
									$details['html_text']=strip_tags(tep_db_prepare_input($message_result['message_text']),'<br>');						
								else
									$details['html_text']=tep_db_prepare_input($message_result['message_text']);
								

								$replace_array=array();
								tep_merge_details($replace_array,"test_default");
								tep_replace_template($details,$replace_array);
							?>
								<div  style="width:100%;height:100px;overflow:auto"><?php echo $details['html_text'];?></div>

						</td>
					</tr>
				</table>
				</td>
				</tr>
				<tr><td>

				<?php 
				$jsData->VARS["updateMenu"]=",normal,";
				} else{
				?>

			<div class='main' align="center" style="height:100px; padding-top:40px;">No Details Found</div>
			<?php 	
				} }
				elseif($mode==2)
				{?>		
				<div align="right" style="vertical-align:top; padding:0 20 10 0px;"> 
				<?php 
				//echo '<a href="' . tep_href_link(FILENAME_MAIL,'selected_box=tools&customer=***') . '">' . tep_image('images/icons/mail.gif',IMAGE_SEND_ALL_CUSTOMERS) . "</a>";
				?>
				<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':'ACU','get':'Email','result':doDisplayResult,'style':'boxRow','type':'emsg','params':'rID=<?php echo $msg_id; ?>'});"><img src="<?php echo DIR_WS_IMAGES;?>icons/mail.gif" title="<?php echo IMAGE_SEND_ALL_CUSTOMERS; ?>" /></a>
				</div>
				
				<?php 
				$jsData->VARS["updateMenu"]=" ";
				}?>
	</td></tr>
</Table></td></tr>
</table>
				
<?php			
		
			}
			
			
			function doEmail()
			{
			 global $FREQUEST,$jsData;
			 
			 
			 $jsData->VARS['doFunc']=array('type'=>'emsg','data'=>'doProductEditor');
			 
			  $customers = array();
    		  $customers =array( array('id' => '', 'text' => TEXT_SELECT_CUSTOMER),
   			 				       array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS),
							       array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS));
   
			$mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
		   
			while($customers_values = tep_db_fetch_array($mail_query)) {
			
			$customers[] = array('id' => $customers_values['customers_email_address'],
								 'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
			}
		  
			 
			 $fields_details=array(array('id'=>'TITLE_C','text'=>TEXT_TITLE_C),
                               array('id'=>'FN','text'=>'&nbsp;&nbsp;' . TEXT_FN),
                               array('id'=>'LN','text'=>'&nbsp;&nbsp;' . TEXT_LN),
			    			   array('id'=>'SN','text'=>'&nbsp;&nbsp;' . TEXT_SN),
    						   array('id'=>'SM','text'=>'&nbsp;&nbsp;' . TEXT_SM),
			   			       array('id'=>'SE','text'=>'&nbsp;&nbsp;' . TEXT_SE),
			      			  // array('id'=>'LA','text'=>'&nbsp;&nbsp;' . TEXT_LA),
							  // array('id'=>'EA','text'=>'&nbsp;&nbsp;' . TEXT_EA),
							  // array('id'=>'PW','text'=>'&nbsp;&nbsp;'. TEXT_PW)
			       );
				   echo tep_draw_form('insert_message','marketing_email_messages.php');
				?>
				<input type="hidden" name="msg_id"  value="<?php echo tep_output_string($FREQUEST->getvalue('rID')); ?>" />
				<table border="0" width="100%">
				<tr><td height="30"></td></tr>
				<tr>
                	<td class="main" height="30" width="100"><?php echo TEXT_CUSTOMER; ?></td>
                	<td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, ($FREQUEST->getvalue('customer')));?></td>
             	</tr>
              	
              	<tr>
                	<td class="main" height="30" width="100"><?php echo TEXT_FROM; ?></td>
                	<td><?php echo tep_draw_input_field('from', STORE_OWNER_EMAIL_ADDRESS,'size=40'); ?></td>
              	</tr>
              	<tr>
                	<td class="main" height="30" width="100"><?php echo TEXT_SUBJECT; ?></td>
                	<td><?php echo tep_draw_input_field('subject','','size=40'); ?></td>
              	</tr>
              	<tr>
                	<td valign="top" class="main" width="100"> <?php echo TEXT_MESSAGE; ?></td>
                	<td><table border=0 cellspacing="0" cellpadding="0" style="background:ButtonFace;"><tr><td><?php echo tep_draw_textarea_field('message_text', 'soft', '120', '34','','id="message"'); ?></td>
				    <td  style="background:ButtonFace;" valign=bottom align=center>
          <?php 
		       echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';
		       echo tep_draw_pull_down_menu('fields',$fields_details,'','style="height:' .((strpos($FREQUEST->servervalue('HTTP_USER_AGENT'),"MSIE")>0)?'224':'241') .'" size=15 ondblClick="AddField()"');
		  ?></td></tr></table></td></tr>
		  <tr>
			  <td colspan='2'><?php echo tep_draw_checkbox_field('chkpwd','','','','onClick="javascript:pwd(this.checked);"') . '&nbsp;' . TEXT_CREATE_PWD;?></td>
			</tr>
			<tr>
                <td colspan="2" align="right">
                 <?php 
				 	   /*if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Enable'){ 
				 			echo tep_image_submit('button_send_email.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
	                   } else {
    			            echo tep_image_submit('button_send_email.gif', IMAGE_SEND_EMAIL); 
					   }*/
				?>
                </td>
                
                
              </tr>
		  
		  
		  </table>
		  
		  </form>

			
		<?php	$jsData->VARS['updateMenu']=',mail,';}


function doEmailMessagesEdit()
{
global $FSESSION,$FREQUEST,$jsData,$currencies,$fields_type,$fields_details,$mes_array,$format_array;

$msg_id=$FREQUEST->getvalue("rID");
$jsData->VARS['doFunc']=array('type'=>'emsg','data'=>'doProductEditor');

$sql_query=tep_db_query("SELECT message_id,message_reply_to,message_type,message_send,message_subject,message_text,message_format from " . TABLE_EMAIL_MESSAGES . " where message_type ='".tep_db_input($msg_id)."' ");
$sql_array=tep_db_fetch_array($sql_query);

				if(tep_db_num_rows($sql_query)>0)
				$mesInfo=new objectInfo($sql_array);
				else
					$mesInfo=new objectInfo($mes_array);
			
				?>

			  <?php  echo tep_draw_form('insert_message',FILENAME_EMAIL_MESSAGES); ?>			   			    			 
				  <table>				  
					<?php 

						$list_add=explode("_",$fields_type[$msg_id]);
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
							<?php echo tep_draw_hidden_field('message_type',$msg_id); 
								echo tep_draw_hidden_field('action_type',''); 
								if($display_type!="SE" && $display_type!="SV" && $display_type!="SS")
								{
									if($mesInfo->message_type=='MIV'){?>
										<tr>
											<td class="smallText"><?php echo TEXT_INVOICE_TITLE; ?>
											<td class="smallText"><?php echo tep_draw_input_field('message_subject',$mesInfo->message_subject,'size=40'); ?></td>
										</tr>
										<tr>
											<td class="smallText" valign=top><?php echo TEXT_INVOICE_TEXT; 
											echo tep_draw_hidden_field('message_reply_to','invoice');
									  } else if($mesInfo->message_type=="CON"){ ?>
										<tr>
											<td class="smallText"><?php echo ($mesInfo->message_type=="CON"?TEXT_CONTACT_US_TITLE:TEXT_LETTER_TITLE); ?>
											<td class="smallText"><?php echo tep_draw_input_field('message_subject',$mesInfo->message_subject,'size=40'); ?></td>
										</tr>
										<tr>
											<td class="smallText" valign=top><?php echo ($mesInfo->message_type=="CON"?TEXT_CONTACT_US_TEXT:TEXT_LETTER_TEXT); 
											echo tep_draw_hidden_field('message_reply_to','contactus');
									 }
									 else if($mesInfo->message_type=='TEM' || $mesInfo->message_type=='PSP')
									 {?>
										<tr>
											<td class="smallText"><?php echo ($mesInfo->message_type=="PSP"?TEXT_PACKING_SLIP_TITLE:TEXT_LETTER_TITLE); ?>
											<td class="smallText"><?php echo tep_draw_input_field('message_subject',$mesInfo->message_subject,'size=40'); ?></td>
										</tr>
										<tr>
											<td class="smallText" valign=top><?php echo ($mesInfo->message_type=="PSP"?TEXT_PACKING_SLIP_TEXT:TEXT_LETTER_TEXT); 
											echo tep_draw_hidden_field('message_reply_to','packing_slip');
									}else{?>
										<tr>
											<td class="smallText" nowrap><?php echo TEXT_MESSAGE_SUBJECT; ?>
											<td class="smallText"><?php echo tep_draw_input_field('message_subject',$mesInfo->message_subject,'size=40',true); ?></td>
										</tr>
										<tr>
											<td class="smallText" nowrap><?php echo TEXT_MESSAGE_REPLY_TO; ?>
											<td class="smallText"><?php echo tep_draw_input_field('message_reply_to',$mesInfo->message_reply_to,'size=40',true); ?></td>
										</tr>
										<tr><td class="smallText" valign=top nowrap><?php echo TEXT_MESSAGE_TEXT .'</td>'; 
									} 
							}
							else
							{							
								$display_count_text='<font color="#FF0000"><div id="strcount"></div></font><b>' . TEXT_MESSAGE_TEXT . '</b>' . INFO_MESSAGE_TEXT .  '<br>';
								$text_params='onkeyup="countkeywords(this)"';
							}?>
							<td class="smallText" colspan="2">														
							<table border=0 cellspacing=0	 cellpadding="0" valign=top>
								<tr>
									<td valign=top class="smallText">
										<?php
											
											echo $display_count_text;
											echo tep_draw_textarea_field('message_text', 'soft', '120', '34', $mesInfo->message_text). '<br><br>';
											
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
							<?php  if(($mesInfo->message_type!='MIV' && $mesInfo->message_type!='TEM' && $mesInfo->message_type!='PSP' && $mesInfo->message_type!='CON') && ($display_type!='SE' && $display_type!='SV' && $display_type!='SS')){ ?>
							<tr>
								<td class="smallText"><?php echo TEXT_MESSAGE_FORMAT; ?>
								<td class="smallText"><?php echo tep_draw_pull_down_menu('message_format',$format_array,$mesInfo->message_format); ?></td>
							</tr>
							<?php }
							
				$message_type=substr($mesInfo->message_type,0,2);?>

						</table>												
						</td>
					</tr>
				   <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
				   </table>	
   				<input type="hidden" name="message_id" value="<?php echo $mesInfo->message_id; ?>" />			 
				  </form>
				</div>							  
			  </td>			
		  </tr>		 
<?php $jsData->VARS["updateMenu"]=",update,";
	$display_mode_html=' style="display:none"';
}
}

	function getMessagesListTemplate(){
		ob_start();
		getTemplateRowTop();	?>	
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
			<tr>
				<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="75%"class="main" onclick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&dType=##DISPLAY_TYPE##&mode=##MODE##'});" id="##TYPE####ID##title">##NAME##</td>
						<td  width="25%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
							<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
							<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':'##ID##','get':'EmailMessagesEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&dType=##DISPLAY_TYPE##&mode=##MODE##'});"><img src="##IMAGE_PATH##template/edit_blue.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteEmailMessages','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&dType=##DISPLAY_TYPE##&mode=##MODE##'});"><img src="##IMAGE_PATH##template/delete_blue.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							</span>
							<span id="##TYPE####ID##mupdate" style="display:none">
							<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':'##ID##','get':'UpdateMessage','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':validateForm,'uptForm':'insert_message',extraFunc:textEditorRemove,'customUpdate':doMessageUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':'##ID##','get':'EditMessage','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif"/></a>
							</span>
							<span id="##TYPE####ID##mmail" style="display:none">
							<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':'##ID##','get':'MailPreview','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':mailvalidate,'uptForm':'insert_message',extraFunc:textEditorRemove,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/mail.gif" height="12" title="<?php echo IMAGE_SEND_EMAIL;?>"/></a>
							<img src="##IMAGE_PATH##template/img_bar.gif"/>
							<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':'##ID##','get':'EditMessage','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif"/></a>
							</span>

						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>	
<?php	getTemplateRowBottom();
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}	
?>