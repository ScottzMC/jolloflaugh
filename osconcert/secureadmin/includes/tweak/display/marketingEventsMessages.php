<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	define('EVENTS_CONDITION_STATUS'," and (se.events_status>=1) and date_format(se.events_date_available,'%Y-%m-%d')<='".getServerDate()."'");
	global $currencies;
	require(DIR_WS_CLASSES . 'currencies.php');			



	$currencies = new currencies();

	class marketingEventsMessages{
		var $pagination;
		var $splitResult;
		var $type;
		
		var $myVar;
		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='cug';
			
			$this->myVar='';
		}
		
		function doSearchGroup(){
			global $FREQUEST,$jsData;
			$search=$FREQUEST->getvalue('search');
			$search_db=tep_db_input($search);
			?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="main">
						<b><?php echo TEXT_SEARCH_RESULTS;?></b>
					</td>
				</tr>
				<tr height="10">
					<td class="main">
					</td>
				</tr>
				<tr>
					<td>
					<table border="0" cellpadding="2" cellspacing="0" width="100%" id="catTable">
						<?php 
						$found=$this->doList(" where customers_groups_name like'%".$search_db."%'",0,$search);
						if (!$found){
						?>
						<tr>
							<td class="main">
								<?php echo TEXT_NO_RECORDS_FOUND;?>
							</td>
						</tr>			
						<?php 
							if ($this->splitResult->queryRows>0){
								echo $this->splitResult->pgLinksCombo();
							}
						} 
						?>
					</table>
					</td>
				</tr>
				<tr height="10">
					<td class="main">
					</td>
				</tr>
				<tr>
					<td class="main">
					<a href="javascript:void(0);" onClick="javascript:doSearchGroup('reset');"><?php echo tep_image_button('button_reset.gif',IMAGE_RESET);?></a>
					</td>
				</tr>
			</table>
<?php	$jsData->VARS["NUclearType"]=$this->type;
		}
		function doDelete(){
			global $FREQUEST,$jsData;
			
			$events_id=$FREQUEST->postvalue('type_id');
				$eve_mess_id=$FREQUEST->postvalue('eve_mess_id');
				
				$FREQUEST->setvalue('service_id',$events_id,'GET');
				
				
				
				
			
			
			if ($eve_mess_id>0){
				tep_db_query("DELETE from " . TABLE_EVENTS_MESSAGES . " where events_message_id='".$eve_mess_id."'");				
				
				if ($last_flag==1 && $page>1){
					$page=$page-1;
					$FREQUEST->setvalue('page',$page,'GET');
				}
		
				$this->doItems();
				
				tep_reset_seo_cache('customers');
			} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
			}
			
		}
		
		function doDeleteGroups(){
			global $FREQUEST,$jsData;
			$message_type=$FREQUEST->getvalue('rID');
			$type_id=$FREQUEST->getvalue('sID');
			$content_id=$FREQUEST->getvalue('cID');
			$service_array=array();
			$sql="select events_message_id  from " . TABLE_EVENTS_MESSAGES . " where events_id='" . tep_db_input($type_id) . "' and message_type='". tep_db_input($message_type) ."'";
			
				
				$sql_query=tep_db_query($sql);
				$customers_info=array();
				if(tep_db_num_rows($sql_query)>0){ 
				
				
				$service_array=tep_db_fetch_array($sql_query);
				}
				
				
				
				$eve_mess_id=(int)$service_array['events_message_id'];
				
					if(tep_db_num_rows($sql_query)>0) 
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_CONFIRM  . '</span>';
		else
			$delete_message='<p><span class="smallText">' . TEXT_CUSTOMER_GROUPS_NOT_DELETED . '</span>';	
			

			
?>
			<form  name="cugDeleteSubmit" id="cugDeleteSubmit" action="<?php echo FILENAME_MARKETING_EVENTS_MESSAGES; ?>" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="eve_mess_id" value="<?php echo tep_output_string($eve_mess_id);?>"/>
				<input type="hidden" name="message_type" value="<?php echo tep_output_string($message_type);?>"/>
				<input type="hidden" name="type_id" value="<?php echo tep_output_string($type_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="cug<?php echo $message_type;?>message">
						</td>
					</tr>
					<tr>
						<td class="main">
						<?php echo $delete_message;?>
						</td>
					</tr>
					<tr height="40">
						<td class="main" style="vertical-align:bottom">
							
							<?php if(tep_db_num_rows($sql_query)>0){ ?>
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $eve_mess_id;?>',type:'cug',get:'Delete',result:doLocationResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,'params':''})"><?php echo tep_image_button('button_delete.gif');?></a>&nbsp;
							<?php }?>
							
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $content_id;?>',type:'cug',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}

		function doList($where='',$group_id=0,$search=''){
		
			global $FSESSION,$FREQUEST,$jsData,$currencies,$message_service,$service_array;
			
			
			$type_id=(($FREQUEST->getvalue('service_id')!='')?($FREQUEST->getvalue('service_id')):$service_array[0]['id']);
			
			$button_text='<div style="padding-top:10px;"><a href="javascript: copy_message('.$type_id.',\'S\');">' . tep_image_button('button_copy_messages.gif',IMAGE_COPY_MESSAGES) . "</a></div>";

			$page=$FREQUEST->getvalue('page','int',1);
			if ($search!=''){
				$orderBy="order by customers_groups_id";
			} else {
				$orderBy="order by customers_groups_id";
			}
			$query_split=false;
			

    	if (($FREQUEST->getvalue('search')!='') && tep_not_null($FREQUEST->getvalue('search')))  $search = " where  (service_location_name like '%" . tep_db_input($FREQUEST->getvalue('search')) . "%' OR (service_location_contact_person like '%".tep_db_input($FREQUEST->getvalue('search'))."%') )";  
	 	$customers_groups_sql="select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3,country_code, address_format_id from " . TABLE_COUNTRIES . " order by countries_name";			
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMER');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$customers_groups_sql);
						if ($query_split->queryRows > 0){ 
							if ($search!=''){
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
							} else {	
								$query_split->pageLink="doPageAction({'id':-1,'type':'cug','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_PRODUCTS,'##PAGE_NO##') . "'})";
							}
						}
			}
			$customers_groups_query=tep_db_query($customers_groups_sql);
			$found=false;	
				
		
		
		
		
			
			for($icnt=0,$limit=sizeof($message_service);$icnt<$limit;$icnt++){ 
			
			if($icnt==$limit-1 ||$icnt==$limit-2 ){
					$template=getListTemplate('mail');
				}
				else if($icnt==$limit-3 )
				$template=getListTemplate('Inv');
				else
				$template=getListTemplate();		
			
					$disp_class=($disp_class=="dataTableRowOdd")?"dataTableRowEven":"dataTableRowOdd";
						$rep_array=array(	"ID"=>$message_service[$icnt]['id'],
											"MKT_ID"=>$message_service[$icnt]['id'],
											"TYPE"=>$this->type,
											"S_ID"=>$type_id,
											"NAME"=>$message_service[$icnt]['text'],
											"DISCOUNT"=>$customers_groups_result["country_code"],
											"IMAGE_PATH"=>DIR_WS_IMAGES,
											"STATUS"=>'',
											"UPDATE_RESULT"=>'doDisplayResult',
											"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
											"ROW_CLICK_GET"=>((($icnt==$limit-1)||($icnt==$limit-2))?'Edit':'MessageDetails'),
											//"ROW_CLICK_GET"=>if(($icnt!=$limit-1) && ($icnt!=$limit-2)) 'MessageDetails',
											"FIRST_MENU_DISPLAY"=>""
										);
					echo mergeTemplate($rep_array,$template); 
						
					?>
						<td class="dataTableContent"><?php echo $message_array[$icnt]['text']; ?></td>
					</tr>
					<tr  class="openContent">
						<td height=25  id="<?php echo $message_array[$icnt]['id']; ?>" style="display:none">
							<?php echo tep_image(DIR_WS_IMAGES . '24-1.gif') ;?>
						</td>
					</tr>
					<?php echo tep_draw_hidden_field('message_type',$message_array[$icnt]['id']); 
			 }
			/* if (!isset($jsData->VARS["Page"])){
			$jsData->VARS["NUclearType"][]=$this->type;
		} */
		
			return $found;			
		}
			function doMailing(){
				global $FREQUEST,$messageStack;
			
			
						$from = $FREQUEST->postvalue('message_from');
						$subject = $FREQUEST->postvalue('message_subject');
						$message = $FREQUEST->postvalue('message_text');
						$events_id=$FREQUEST->postvalue('events_id');
						
							
							
							$type=($FREQUEST->postvalue('message_type')=='MEV'?'R':'W');
						$mimemessage = new email(array('X-Mailer: osConcert'));
						
							$message_text=strip_tags($message,'<br>');
							$message_text=str_replace('<br>',chr(13) . chr(10),$message_text);
							$message_text=str_replace('<BR>',chr(13) . chr(10),$message_text);
			
							if (HTML_AREA_WYSIWYG_DISABLE_EMAIL == 'Disable') {
								$mimemessage->add_text($message_text);
							} else {
								$mimemessage->add_html($message,$message_text);
							}
							$mimemessage->build_message();
							
							$sql_quer="SELECT c.customers_id,c.customers_email_address,
														c.customers_firstname,c.customers_lastname,op.products_id from " . 
														TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . 
														" o where op.orders_id=o.orders_id and o.customers_id=c.customers_id 
														and c.customers_subscription_newsletter='1' 
														 group by o.customers_id";
								
							
							$customer_query=tep_db_query($sql_quer);
							
							
						

							if(tep_db_num_rows($customer_query)>0){
							while($mail=tep_db_fetch_array($customer_query)){
								if ($mail['customers_email_address']!=""){
									$mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], STORE_OWNER, $from, $subject);

									$str_status='success';
									
								
								} else{
									$str_status='Mail sending failed';
								}
							}
							} else{
									$str_status='No Event holders to send mail!';
							}
							
							
					
						
			
					if($str_status=='success'){
						//$messageStack->add_session(TEXT_SEND_MAILS,'success');
						 echo '<div class="main" style="text-align:center; padding-top:30px; padding-bottom:30px;">'.TEXT_SEND_MAILS .'</div>';
						
					} else{
						echo '<div class="main" style="text-align:center; padding-top:30px; padding-bottom:30px;">'.$str_status.'</div>';
					}
					echo " @sep@{'updateMenu':',normal,'}";
			
			}
	function doShowMail(){
	}

		function tep_get_service_array_single($first=''){
			global $FSESSION;
			$service_array=array();
			
			
			
			
			
			$service_query=tep_db_query("SELECT e.events_id,sed.events_name from " . TABLE_EVENTS . " e, " . TABLE_EVENTS_DESCRIPTION . " sed where e.events_id=sed.events_id and language_id='" . (int)($FSESSION->languages_id) . "' " . EVENTS_CONDITION_STATUS . " order by sed.events_name");
			
			while($service_result=tep_db_fetch_array($service_query)){
				$service_array[]=array('id'=>$service_result['events_id'],'text'=>$service_result['events_name']);
			}
			return $service_array;
		} 
		 function doSubscriptionType(){
			global $FREQUEST;
			$subscription_id=$FREQUEST->getvalue('sID','int',0);
			$message_type=$FREQUEST->getvalue('message_type','string','');
			$message_send=$FREQUEST->getvalue('message_send','string','');
			
			if(($subscription_id!=0) && ($message_type!='')){
				$sql_quer="update ".TABLE_EVENTS_MESSAGES." set message_send='".$message_send."' where events_id='".$subscription_id."' and message_type='".$message_type."'";
				
				tep_db_query($sql_quer);
			
			}
		}
		function doItems(){
			global $FREQUEST,$jsData,$FSESSION,$eve;
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
								
									"NAME"=>IMAGE_NEW_MESSAGE,
									"DISCOUNT"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'Edit',
									"FIRST_MENU_DISPLAY"=>'display:none'
								);
								
			$service_id=$FREQUEST->getvalue('service_id','int',-1);	
			
			 $service_id=(($service_id>0)?($service_id):$this->myVar);
			
			if($service_id==-1){
			$show_header='Messages of All Events';
			
			} 
			
			else{
			$service_query=tep_db_query('select events_name from '.TABLE_EVENTS_DESCRIPTION.' where events_id='.$service_id);
			if($service_array=tep_db_fetch_array($service_query))
				$show_header='Messages of '.$service_array['events_name'];
				$eve=$service_array['events_name'];
			}
			define('TEXT_HEADING_MESSAGE_TYPE',$show_header);
?>
			<div class="main" id="cug-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td style="padding:10 0 20 0; color:#697FD4; font-weight:bold; font-size:18px;" class="main"><?php echo TEXT_HEADING_MESSAGE_TYPE; ?>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr  >
										<td class="main" width="50%">
										<b><?php echo  TABLE_HEADING_NAME;?></b>
										</td>
										<td class="main">&nbsp;
										
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
							
								<div align="center"><?php $this->doList();?></div>
							</td>
							</tr>	
							<?php
							$template=getListTemplate('copy_operation');
							$rep_array=array(	"TYPE"=>$this->type,
												"ID"=>-1,
												"NAME"=>'<img name="copy_messages" id="copy_messages" src="includes/languages/english/images/buttons/button_copy_messages.gif" />',
												"CODE"=>'',
												"VALUE"=>'',
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"STATUS"=>'',
												"MKT_ID"=>'',
												"S_ID"=>$service_id,
												"DISCOUNT"=>'',
												"UPDATE_RESULT"=>'doMessageResult',
												"ROW_CLICK_GET"=>'CopyMessages',
												"ALTERNATE_ROW_STYLE"=>'listItemOdd',
												"FIRST_MENU_DISPLAY"=>""
											);
							?>
						<tr style="padding-top:20px; height:25px;">
							<td><?php 	echo mergeTemplate($rep_array,$template); ?>
							</td>
						</tr>
					</Table>
				</td>
			</tr>
		
	</table>
	</td>
	<td style="display:none">
	<?php if($service_array['events_name']!='') echo '@sep@'.(($service_array['events_name']!='')?$service_array['events_name']:'All Events');?>
	
	
		<?php

	

}
	
		
function doEdit(){
	global $FREQUEST,$jsData,$currencies,$format_array,$message_send_array,$fields_type,$fields_details,$message_type_array,$FSESSION;
	
	
	$sh_country_id=$FREQUEST->getvalue("rID","string",0);
	$group_id=$sh_country_id;
	$type_id=(($FREQUEST->getvalue('sID')!='')?($FREQUEST->getvalue('sID')):'');
	$jsData->VARS['doFunc']=array('type'=>'cug','data'=>'doEmailEditor');
	echo tep_draw_form('customer_groups','marketing_events_messages.php');
	echo tep_draw_hidden_field('message_type',$group_id);
	echo tep_draw_hidden_field('events_id',$type_id);
	$customers_arr=array('events_message_id'=>'', 'message_send'=>'', 'message_subject'=>'', 'message_reply_to'=>STORE_OWNER_EMAIL_ADDRESS, 'message_text'=>'', 'message_format'=>'','events_id'=>'','message_type'=>'');	
	if($group_id!="" && $type_id!="") {
		$sql="SELECT * from " . TABLE_EVENTS_MESSAGES . " where events_id='" . tep_db_input($type_id) . "' and message_type='". tep_db_input($group_id) ."'";
		$sql_query=tep_db_query($sql);
		$customers_arr=tep_db_fetch_array($sql_query);
		
	}
			
				if(is_array($customers_arr)) $cInfo=new objectInfo($customers_arr);	
				
				$list_add=explode("_",$fields_type[$group_id]);
				$array_result=array();
				for ($icnt=0;$icnt<sizeof($list_add);$icnt++){
					if(is_array($fields_details[$list_add[$icnt]]))
						$array_result=array_merge($array_result,$fields_details[$list_add[$icnt]]);
				}
				
				
				
				?>
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					
					<tr>
						<td valign="top">
							<!--<form action="<?php //echo FILENAME_MARKETING_EVENTS_MESSAGES; ?>" method="post" name="customer_groups" enctype="multipart/form-data" id="customer_groups">-->
								<input type="hidden" name="service_resource_id" value="<?php echo tep_output_string($service_resource_id);?>"/>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
										<td valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
								    
												<tr id="productPanelGENERALview">
														
														<td colspan="2" class="main">
															<table border="0" cellpadding="4" cellspacing="0" width="100%">
																<div class="hLineGray"></div>
																<tr> <td class="smallText"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
																</tr>
																<tr>
																	
																	<td>
																		<table border="0" cellpadding="4" cellspacing="0" style="padding-bottom:50px;">
																				<?php if($FREQUEST->getvalue("rID")=='MEV' || $FREQUEST->getvalue("rID")=='MEW'){ ?>
																			<tr>
																				<td class="smallText" ><?php echo TEXT_FROM; ?></td>
																				<td class="smallText"><?php echo tep_draw_input_field('mail_from',$FSESSION->get("login_email"),'size=40 maxlength=50'); ?></td>
																			</tr>
																			
																			<tr>
																				<td class="smallText" ><?php echo TEXT_MESSAGE_REPLY_TO; ?></td>
																				<?php if($FREQUEST->getvalue("rID")=='MEV'){?>
																				<td class="smallText"><?php echo TEXT_RESERVATION_HOLDERS; ?></td>
																				<?php }if($FREQUEST->getvalue("rID")=='MEW'){?>
																				<td class="smallText"><?php echo TEXT_WAITING_LIST_HOLDERS; ?></td>
																				<?php }?>
																			</tr>
																			<tr>
																				<td class="smallText" ><?php echo TEXT_MESSAGE_SUBJECT; ?></td>
																				<td class="smallText"><?php echo tep_draw_input_field('message_subject',$cInfo->message_subject,'size=40 maxlength=50').tep_draw_hidden_field('service_location_id',$location_id); ?></td>
																			</tr>
																			<?php }else{?>
																			
																			
																			<tr>
																				<td class="smallText" ><?php echo TEXT_MESSAGE_SUBJECT; ?></td>
																				<td class="smallText"><?php echo tep_draw_input_field('message_subject',$cInfo->message_subject,'size=40',true) ?></td>
																			</tr>
																			<tr>
																				<td class="smallText"><?php echo TEXT_MESSAGE_REPLY_TO; ?></td>
																				<td class="smallText"><?php echo tep_draw_input_field('message_reply_to',$cInfo->message_reply_to,'size=40',true); ?></td>
																			</tr>
																			<?php }?>
																			<tr>
																			<td class="smallText" valign=top nowrap><?php echo TEXT_MESSAGE_TEXT ; ?></td>
																			<td class="smallText" colspan="2">
																			<table border=0 cellspacing=0	 cellpadding="0" valign=top>
																			<tr>
																			<td valign=top class="smallText" >
																			<?php
																			
																				
																				echo $display_count_text;
																				
																				echo tep_draw_textarea_field('message_text','soft','80','24',$cInfo->message_text,'id="message_text" ' .$text_params) . "</span>";
																			?>
																			</td>
																			
																			
																			<td style="background:ButtonFace;" valign="bottom" align="center">
																			
																			<?php if($FREQUEST->getvalue("rID")!='MEV' && $FREQUEST->getvalue("rID")!='MEW'){?>
																				
																					
																					<?php		
																					echo "<div class='main'><b>" . TEXT_MERGE_FIELDS . '</b></div><br>';		
																					
																				echo tep_draw_pull_down_menu('fields',$array_result,'','style="height:' .((strpos($FREQUEST->servervalue('HTTP_USER_AGENT'),"MSIE")>0)?'225':'241').'" size=15 ondblClick="AddField()"');
																			}?>
																			
																			</td>									
																			</tr>
																			</table>	
																																		
																			</td>
																			</tr>
																			<?php 
																				
																					if($FREQUEST->getvalue("rID")!='MEV' && $FREQUEST->getvalue("rID")!='MEW'){?>
																			<tr>
																				<td class="smallText" ><?php echo TEXT_MESSAGE_FORMAT; ?></td>
																				<td class="smallText" ><?php echo tep_draw_pull_down_menu('message_format',$format_array,$cInfo->message_format); ?></td>
																				
																	
																			</tr>
																			<?php }?>
																			<?php if($group_id=='RCF' || $group_id=='WCF'){ ?>
																			<tr>
																				<td class="smallText" ></td>
																				<td class="smallText" ><?php echo tep_draw_pull_down_menu('message_send',$message_send_array,$cInfo->message_send);?></td>
																			</tr>
																			<?php }  if($group_id!='RCF' && $group_id!='WCF'){ ?>
																			<tr>
																				<td class="smallText" colspan="2"><?php 
																				
																				echo tep_draw_hidden_field('message_send',$cInfo->message_send);
																				?>
																																							
																				</td>
																			</tr>	
																<?php }?>
																		    
																			
																			 </tr>
																		</table>
																	</td>
															
															
															</tr>
														</table>
													</td>
												
												
												</tr>
										</table>
									
								</td>
							
							</tr>
						</table>
						</form>
			
			
			<?php	
					
					$jsData->VARS["updateMenu"]=",update,";
					
			}	
			function doUpdate(){
				global $FREQUEST,$jsData,$currencies;
				
				
				$events_id=$FREQUEST->postvalue('events_id');
				$message_type=$FREQUEST->postvalue('message_type');
							
				
				$mes_array=array();				

				$insert=true;
		if($events_id!="" && $message_type!="" ) {
			$sql1="SELECT events_message_id from " . TABLE_EVENTS_MESSAGES . " where events_id='" . tep_db_input($events_id) . "' and message_type='". tep_db_input($message_type) ."'";
			
			$count_query=tep_db_query($sql1);
			
			
			
			if(tep_db_num_rows($count_query)>0) { 
				
			$mes_array=tep_db_fetch_array($count_query);
			
			if(is_array($mes_array)) $cInfo1=new objectInfo($mes_array);	
				
				$events_message_id=$cInfo1->events_message_id;
			
			
			
			$insert=false;
			}	
			else {
				$insert=true;	
			}	
		}	
				
		$message_format_post=$FREQUEST->postvalue('message_format');
		$message_text_post=$FREQUEST->postvalue('message_text');
		$message_send=$FREQUEST->postvalue('message_send');
		
		$message_text=($message_format_post!='T'?$message_text_post:strip_tags($message_text_post,'<br>'));
		
		$sql_array=array('message_type'=>$message_type,
						'message_subject'=>$FREQUEST->postvalue('message_subject'),
						'message_send'=>$FREQUEST->postvalue('message_send'),
						'message_reply_to'=>$FREQUEST->postvalue('message_reply_to'),
						'message_text'=>tep_db_prepare_input($message_text),
						'message_format'=>$message_format_post,
						'events_id'=>$events_id
						);
				
			
			
				if($insert) {	
			tep_db_perform(TABLE_EVENTS_MESSAGES,$sql_array);
		} else {
			tep_db_perform(TABLE_EVENTS_MESSAGES,$sql_array,'update','events_message_id="' . $events_message_id . '"');
		}
				
				
																				
						
		$jsData->VARS["prevAction"]=array('id'=>$message_type,'get'=>'MessageDetails','type'=>$this->type,'style'=>'boxRow');
		$this->doMessageDetails();															
		$jsData->VARS["updateMenu"]=",normal,";
		

					
			}
			
			function doMessageDetails(){
			
				global $FREQUEST,$currencies,$message_service,$message_type_array,$fields_type,$arr,$jsData;
	
				$msg_id=$FREQUEST->getvalue('rID');
				$events_id=$FREQUEST->getvalue('sID');
								
				
				
				$events_id=(($events_id=='')?$FREQUEST->postvalue('events_id'):$events_id);
				$msg_id=(($msg_id=='')?$FREQUEST->postvalue('message_type'):$msg_id);
					for($icnt=0;$icnt<sizeof($message_service);$icnt++){	
						if($message_service[$icnt]['id']==$msg_id){	
							
							 tep_get_message_details($message_service[$icnt],$message_type_array[$msg_id],$fields_type[$msg_id],'E',$events_id);
							break;
						}
					}
					$jsData->VARS["updateMenu"]=",normal,";
					

			}
			
			function doCopy(){
			
				global $FREQUEST,$jsData;

				$selected_messages=$FREQUEST->postvalue('selected_messages');
				$selected_services=$FREQUEST->postvalue('selected_services');
				$service_keys=explode(',',$selected_services);
				$selected_messages_str=str_replace(",","','",$selected_messages);
				
				$source_service=$source_service=$FREQUEST->postvalue('source_service');
				$message_collection=array();
				$source_arr=array();

				for($i=0; $i<count($service_keys); $i++){
					$sq_query="select * from " . TABLE_EVENTS_MESSAGES . " where events_id=".$source_service." and message_type in ('".$selected_messages_str."')";
					
					$source_query=tep_db_query($sq_query);
					
					while($source_arr=tep_db_fetch_array($source_query)){
					
					$check_query=tep_db_query("select message_text from " . TABLE_EVENTS_MESSAGES . " where events_id=".$service_keys[$i]." and message_type='".$source_arr['message_type']."'");
						$message_collection=array('message_type'=>$source_arr['message_type'],
												  'message_text'=>$source_arr['message_text'],
												  'message_subject'=>$source_arr['message_subject'],
												  'message_send'=>$source_arr['message_send'],
												  'message_reply_to'=>$source_arr['message_reply_to'],
												  'message_format'=>$source_arr['message_format'],
												  'events_id'=>$service_keys[$i],
												 );
					if(tep_db_num_rows($check_query)>0){
						tep_db_perform(TABLE_EVENTS_MESSAGES,$message_collection,'update','events_id='.$service_keys[$i].' and message_type="'.$source_arr['message_type'].'"');
					} else{
						tep_db_perform(TABLE_EVENTS_MESSAGES,$message_collection);
					}
					}
				}
				echo " @sep@{'updateMenu':',normal,'}";
			
				
			}
			function doCopyMessages(){
				global $FREQUEST,$jsData,$currencies,$format_array,$fields_type,$fields_details,$message_service,$eve;
				$sh_country_id=$FREQUEST->getvalue("rID","string",0);
					$value=$FREQUEST->getvalue("vAL","string",'All Events');
					
				$customers_info=array();
				$jsData->VARS["page"]["editorControlers"]='message_text[0]';
				$group_id=$sh_country_id;
		       	$register_array=array(array('id'=>'B','text'=>TEXT_BEFORE_BOOKING),array('id'=>'A','text'=>TEXT_AFTER_BOOKING));
				$type_array=array(array('id'=>'D','text'=>TEXT_DAYS),array('id'=>'H','text'=>TEXT_HOURS));
				$type_id=(($FREQUEST->getvalue('sID')!='')?($FREQUEST->getvalue('sID')):'');
				$sql="SELECT * from " . TABLE_EVENTS_MESSAGES . " where events_id='" . tep_db_input($type_id) . "' and message_type='" . $sh_country_id . "'";
				$sql_query=tep_db_query($sql);
				$customers_info=array();
				if(tep_db_num_rows($sql_query)>0){ 
				$customers_info=tep_db_fetch_array($sql_query);
			}
				$subscription_query=tep_db_query("select events_name from ".TABLE_EVENTS_DESCRIPTION." where events_id=".$type_id);
				if(tep_db_num_rows($subscription_query)>0){
					if($subscription_arr=tep_db_fetch_array($subscription_query)) $services_greet="Copy Messages - ".$subscription_arr['events_name'];
				} else{
					 $services_greet="Copy Messages - All Events";
				}
				$cInfo=new objectInfo($customers_info); ?>
			<!--	<td style="padding-bottom:10px; padding-right:20px;">		-->
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<form action="<?php echo FILENAME_MARKETING_EVENTS_MESSAGES; ?>" method="post" name="customer_groups" enctype="multipart/form-data" id="customer_groups">
								<input type="hidden" name="sID" id="sID" value="<?php echo tep_output_string($type_id);?>" />
								<input type="hidden" name="service_resource_id" value="<?php echo tep_output_string($service_resource_id);?>"/>
								<table border="0" cellpadding="0" cellspacing="5" width="100%" height="150">
									<tr>
									<td colspan="4" style="padding-left:50px; height:30px; font-size:17px; font-weight:bold; color:#697FD4;" class="main">
									<div>
										<div>
										<div><?php echo $services_greet; ?></div>
										</div>
										<div style="float:right; margin-top:-20px;">
										<!--<span style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'<?php //echo $this->type; ?>','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="<?php // echo DIR_WS_IMAGES; ?>/template/img_save_green.gif" title="Save"/></a>
										<img src="<?php //echo DIR_WS_IMAGES; ?>/template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doCopyAction({'get':'Edit','type':'<?php //echo $this->type; ?>','style':'boxRow'});"><img src="<?php //echo DIR_WS_IMAGES; ?>/template/img_close_blue.gif" title="Cancel"/></a>
										</span>	-->				
										</div>
										</div>
									</td>
									</tr>
									<tr>
										<td width="5%">&nbsp;</td>
										<td valign="top" width="35%" style="background:#FFFFFF;" class="main">
											Select Messages
										</td>
										<td valign="top" width="55%" style="background:#FFFFFF;" class="main">
											Select Events
										</td>
										<td width="5%">&nbsp;</td>
									</tr>
									<tr>
										<td width="5%">&nbsp;</td>
										<td valign="top" width="35%" class="main">
										<div style="background:#FFFFFF; border:solid 1px #E3E9F2; height:200px; overflow:auto;">
										<?php 
											$len=(int)sizeof($message_service);
											//echo 'sdfsdf'.$len;
											 $check_status=0;
											 $copy_status="success";
											for($icnt=0;$icnt<sizeof($message_service);$icnt++){
												$check_query=tep_db_query("select * from ".TABLE_EVENTS_MESSAGES." where events_id=".$type_id." and message_type='".$message_service[$icnt]['id']."'");
												if($type_arr=tep_db_fetch_array($check_query)){
													$styles='style="color:#0000FF;"';
													$selecting_status='onClick="javascript:choose_messages(this);"';
												} else{
													$check_status++;
													$styles='style="color:#ADADAD;"';
													$selecting_status=' disabled="disabled"';			
												}
												echo '<div><label '.$styles.' for="message_'.$message_service[$icnt]['id'].'">';
												echo tep_draw_checkbox_field('message_'.$message_service[$icnt]['id'],$message_service[$icnt]['id'],'','',$selecting_status).'&nbsp;';
												echo $message_service[$icnt]['text'];
												echo '</label></div>';										
											}
											if($len==$check_status)
											 $copy_status="failed";
											else
											 $copy_status="success";
											echo tep_draw_hidden_field('copy_status',$copy_status);
											echo tep_draw_hidden_field('selected_messages','');
											echo tep_draw_hidden_field('selected_services','');
											echo tep_draw_hidden_field('source_service',$type_id);

										?>
										</div>
										</td>
										<td valign="top" width="55%" class="main">
											<div style="background:#FFFFFF; border:solid 1px #E3E9F2; height:200px; overflow:auto;">
											<?php
											$service_array=$this->tep_get_service_array_single();
											for($icnt=0;$icnt<sizeof($service_array);$icnt++){
												if($service_array[$icnt]['id']!=$type_id){
												if(($service_array[$icnt]['id']!=-1) || ($service_array[$icnt]['id']!=-1)){
												echo '<div>';
												echo tep_draw_checkbox_field('service_'.$service_array[$icnt]['id'],$service_array[$icnt]['id'],'','','onClick="javascript:choose_services(this);"').'&nbsp;';
												echo $service_array[$icnt]['text'];
												echo '</div>';
												}
												}
											}

											?>
											</div>
										</td>
										<td width="5%">&nbsp;</td>
									</tr>
								</table>
							</form>
						</td>
					</tr>
				</table>
			<!--	</td>	-->
			<?php	echo tep_draw_hidden_field('country_id',$sh_country_id);
					
					
					$jsData->VARS["updateMenu"]=",update,";
					
					
				 
			}	
			
			function doInfo($sh_country_id=0){
				global $FREQUEST,$jsData,$FSESSION,$currencies;
				
				if($sh_country_id <= 0){
					$sh_country_id=$FREQUEST->getvalue("events_id","int",'');
					$type=$FREQUEST->getvalue("message_type","string",'');
					
					$sh_country_id=(($sh_country_id=='')?($FREQUEST->postvalue("events_id","int",'')):'');
					$type=(($type=='')?($FREQUEST->postvalue("message_type","string",'')):'');
					$info_where=" events_id='" . tep_db_input($sh_country_id) . "' and message_type='".$type."'";
				} else{
					$info_where=" events_message_id='" . tep_db_input($sh_country_id) . "'";
				}
				
				$sql="SELECT * from " . TABLE_EVENTS_MESSAGES . " where ".$info_where;

				$customers_groups_query = tep_db_query($sql);
	
				if (tep_db_num_rows($customers_groups_query)>0){
					$customers_groups_result=tep_db_fetch_array($customers_groups_query);
					$template=getInfoTemplate($location_id);
				
					$rep_array=array(	"TYPE"=>$this->type,
										"ENT_EQUIPMENT"=>TEXT_EQUIPMENT_NAME  ,
										"EQUIPMENT"=> $customers_groups_result["message_text"],
										"ID"=>$customers_groups_result["service_location_id"]
										);
					
					echo mergeTemplate($rep_array,$template);
					
					$jsData->VARS["updateMenu"]=",normal,";
				}
				else {
					echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
				}
			}			
		
		
		
		
		function doTestMailSendDisplay(){
		global $FREQUEST,$jsData,$FSESSION;
		$msg_id=$FREQUEST->getvalue('rID');
		$events_id=$FREQUEST->getvalue('sID');
		$FSESSION->set('sID',$events_id);
		$id=$FREQUEST->getvalue('cID');

		$test_message='';
		
		$email_query=tep_db_query("Select * from " . TABLE_EVENTS_MESSAGES . " where message_type='".$msg_id."' and events_id='".$events_id."'");
		
		if(tep_db_num_rows($email_query)>0) 
			$test_message='<p><span class="smallText">' . TEXT_TEST_MAIL_INTRO . '</span>';
		else
			$test_message='<p><span class="smallText">' . TEXT_NO_TEST_MAIL_INTRO . '</span>';
	?>
		<form  name="cugTestSubmit" id="cugTestSubmit" action="marketing_events_message.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="message_id" value="<?php echo tep_output_string($msg_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="main" id="cug<?php echo $id;?>message"></td>
			</tr>
			<tr>
				<td class="main"><?php echo $test_message;?></td>
			</tr>
			<tr height="40">
				<td class="main" style="vertical-align:bottom">
					<p>
					<?php if(tep_db_num_rows($email_query)>0) { ?>
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $id;?>',type:'cug',get:'TestMailSend',result:doDisplayResult,message:page.template['PRD_DELETING'],'uptForm':'cugTestSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button('button_send.gif');?></a>&nbsp;
					<?php } ?>
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $id;?>',type:'cug',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button('button_cancel.gif');?></a>
				</td>
			</tr>
			<tr>
				<td><hr/></td>
			</tr>
			<tr>
				<td valign="top" class="mainpageInfo"><?php echo $this->doMessageDetails();?></td>
			</tr>
		</table>
		</form>
<?php	$jsData->VARS["updateMenu"]="";
	}
	function doTestMailSend(){
		global $FREQUEST,$jsData,$LANGUAGES,$FSESSION;
		$msg_id=$FREQUEST->postvalue('message_id');
		
		if ($msg_id!=""){
			tep_send_event_test_email($msg_id,$FSESSION->sID);
		//	tep_send_default_test_email($msg_id);
			$this->doMessageDetails();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_TEST_MAIL_SENT_SUCCESS);
		} else {
			echo "Err:" . TEXT_TEST_MAIL_NOT_SENT;
		}
		
	}
	function doEmailDeleteDisplay(){
		global $FREQUEST,$jsData,$FSESSION;
		$msg_id=$FREQUEST->getvalue('rID');
		$delete_message='';
		$email_query=tep_db_query("Select * from " . TABLE_EVENTS_MESSAGES . " where message_type='PRD'");
		if(tep_db_num_rows($email_query)>0) 
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_PAGE_INTRO . '</span>';
		else
			$delete_message='<p><span class="smallText">' . TEXT_NO_DELETE_INTRO . '</span>';
	?>
		<form  name="cugDeleteSubmit" id="cugDeleteSubmit" action="marketing_events_messages.php" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="message_id" value="<?php echo tep_output_string($msg_id);?>"/>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="main" id="cug<?php echo $msg_id;?>message">
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
					<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:'<?php echo $msg_id;?>',type:'cug',get:'EmailDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'cugDeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
					<?php } ?>
					<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:'<?php echo $msg_id;?>',type:'cug',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
				</td>
			</tr>
			<tr>
				<td><hr/></td>
			</tr>
			<tr>
				<td valign="top" class="mainpageInfo"><?php echo $this->doMessageDetails();?></td>
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

		}//classend
		
		function getListTemplate($copy=''){
		ob_start();
		getTemplateRowTop();	
		if($copy==''){
		
		
		?>
					
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="15" id="cug##ID##bullet">##STATUS##</td>
									<td width="50%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td width="35%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##discount">##DISCOUNT##</td>
									<td  width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##&cID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'TestMailSendDisplay','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##&cID=##ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Test Mail"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>-->
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':'##ID##','get':'Update','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups',extraFunc:textEditorRemove,'customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<!--<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>-->
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
										</span>
									</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
<?php	} 

else if($copy=='Inv'){	?>
				<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="15" id="cug##ID##bullet">##STATUS##</td>
									<td width="50%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td width="35%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##discount">##DISCOUNT##</td>
									<td  width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##&cID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>-->
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':'##ID##','get':'Update','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups',extraFunc:textEditorRemove,'customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<!--<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>-->
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
										</span>
									</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
<?php }
else if($copy=='mail'){	?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
				<tr>
					<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="15" id="cug##ID##bullet">##STATUS##</td>
							<td width="50%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##name">##NAME##</td>
							<td width="40%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':##UPDATE_RESULT##,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});" id="##TYPE####ID##discount">##DISCOUNT##</td>
							<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
								<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
								<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});"><img src="##IMAGE_PATH##template/mail.gif" title="Mail"/></a>
								</span>
								<span id="##TYPE####ID##mupdate" style="display:none">
								<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Mailing','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':mailValidate,'uptForm':'customer_groups','customUpdate':doMailUpdate,'result':doMailingResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/mail.gif" height="12" title="Send Mail"/></a>-->
								<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':'##ID##','get':'Mailing','imgUpdate':false,'type':'##TYPE##','style':'boxRow','validate':mailValidate,'uptForm':'customer_groups',extraFunc:textEditorRemove,'customUpdate':doMailUpdate,'result':doMailingResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Send Mail"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<!--<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>-->
								<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':'##ID##','get':'Edit','type':'##TYPE##',extraFunc:textEditorRemove,'style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
								</span>
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
<?php	} else{	?>
				<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
					<tr>
						<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="15" id="cug##ID##bullet">##STATUS##</td>
								<td >
								<span id="##TYPE####ID##mnormal">
								<a href="javascript:void(0)" onClick="return doDisplayAction({'id':'##ID##','get':'CopyMessages','result':doCopyResults,'style':'boxRow','type':'##TYPE##','params':'rID=##MKT_ID##&sID=##S_ID##'});">##NAME##</a>
								</span>
								</td>
								<td id="##TYPE####ID##menu" align="right">
								<span id="##TYPE####ID##mupdate" style="display:none;">
								<!--<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Copy','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':copy_validate,'uptForm':'customer_groups','customUpdate':doMessageUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>-->
									<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':'##ID##','get':'Copy','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':copy_validate,'uptForm':'customer_groups','customUpdate':doMessageUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
								<img src="##IMAGE_PATH##template/img_bar.gif"/>
								<!--<a href="javascript:void(0)" onClick="return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>-->
								<a href="javascript:void(0)" onClick="javascript:return doCancelledAction({'id':'##ID##','get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
								</span>
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
<?php	}
		getTemplateRowBottom();

		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function getInfoTemplate(){
		ob_start();	?>
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="20" ><div style="width:100%;height:100px;overflow:hidden"></div></td>
				<td valign="top" width="80%">
					<table border="0" cellpadding="4" cellspacing="0" width="100%">
						<div class="hLineGray"></div>
						<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
						<tr>
							<td valign="top" width="25%" align="left" class="main">##EQUIPMENT##</td>
						</tr>
						<tr>
							<td valign="top" align="left" class="main" colspan="2">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
<?php	$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	 
	
	 
 function tep_get_message_details($message_array,$message_type_array,$fields_type,$display_type,$type_id)
{
	global $mes_array,$format_array,$fields_details,$submit_pagename,$alert_message,$FREQUEST,$messages_subscription,$result;

	
	
	$sql_query="select events_message_id as message_id,message_type,message_send,message_subject,message_reply_to,message_text,message_format from " . TABLE_EVENTS_MESSAGES . " where events_id='" . tep_db_input($type_id) . "' and  message_type='" . tep_db_input($message_array['id']) . "'" ;
	
		
		$message_type_query=tep_db_query($sql_query);
		
		
	$result_cnt=tep_db_num_rows($message_type_query);
	
	if ($result_cnt>0){
		$result=tep_db_fetch_array($message_type_query);
		if(is_array($result)) $mesInfo=new objectInfo($result);
		$new_action="update_message";	
	} else{
			
			
		if(is_array($mes_array)) $mesInfo=new objectInfo($mes_array);
		$mesInfo->message_type=$message_array['id'];
		
		$mesInfo->message_id='';
		
		$new_action="insert_message";
	}
	if ($result_cnt>0){
	
	?>
	<Table width="100%" cellpadding="0" border="0" cellspacing="0">
	<tr><td ><Table width=100% align="center" border="0" cellpadding="0" cellspacing="0">
	<?php if($alert_message!=""){?>
		<tr><td class="smallText" style="color:#003399" ><?php echo tep_image('images/icons/success.gif') . ' ' . $alert_message;?></td></tr>
		<tr><td height="10"></td></tr>
	<?php }
	
	if ($message_array['mode']==1 || $message_array['mode']==4 || $message_array['mode']==5 ){
		if ($result_cnt<=0){?>
		  <tr>
			<td  colspan="2" style="color:#000000;margin-left:10px;">&nbsp;</td>
			<td align="center" colspan="2">&nbsp;</td>
			<td id="show_edit" <?php if ($result_cnt>=1){?>style="display:none"<?php }?>>&nbsp;</td>
		  </tr>
		<?php }else{?>
		<tr >
			<td width="350">&nbsp;</td>
			<td align="right" nowrap="nowrap" style="padding-right:20px;">
			<?php  
				if(is_array($message_type_array))
				{
				
					
					echo tep_draw_form('message_type',FILENAME_MARKETING_EVENTS_MESSAGES);
					echo tep_draw_hidden_field('id',$result['message_id']);
					echo tep_draw_hidden_field('msg_type',$result['message_type']);
					
					echo tep_draw_pull_down_menu('message_send',$message_type_array,$result['message_send'],' onchange="javascript:doChangeType({\'selObj\':this,\'params\':\'sID='.$type_id.'&message_type='.$result['message_type'].'\',\'get\':\'SubscriptionType\',\'result\':\'subscriptionResult\',\'style\':\'boxRow\'});"');
					
					if ($message_array['id']=='RSR' && ($display_type=='SE' || $display_type=='SV') ) echo '&nbsp;' . TEXT_INFO_START_REMINDER;
					echo '</form>';
					
				}

					echo '</td>	</tr></table>';?>
			</td>
			<td width="250" align="right"><span id="show_edit" style="visibility:hidden">
			<?php
				echo '<a href="javascript:do_submit(\'' . $new_action  . '\',\'' . $mesInfo->message_id . '\',\''  . $type_id . '\')">' . tep_image('images/icons/edit_green.gif','Save') . "</a>&nbsp;";
				echo '&nbsp;<a href="javascript:close_edit();">' . tep_image('images/icons/cancel.gif','Close') . "</a>";
			?></span>
			</td>
		 </tr>
		 </table>
		 <div style="display:none" class="main" id="edit_message"></div>
		 <div style="display:none" class="main" id="delete_message"></div>
		<tr  id="show_read_only">
				<Td colspan="3">
					<table width="100%" border="0">
					<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
					<tr>
						<td>
							<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_FROM,STORE_OWNER,STORE_OWNER_EMAIL_ADDRESS)); ?></td>
								</tr>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_TO,TEST_MAIL_FN . ' ' . TEST_MAIL_LN,EVENTS_TEST_EMAIL_ADDRESS)); ?></td>
								</tr>
								<?php if($display_type!='SV' && $display_type!='SS' && $display_type!='SE'){?>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_REPLY_TO,$result['message_reply_to'])); ?></td>
								</tr>
								<tr>
									<td class="smallText"><?php echo tep_db_prepare_input(sprintf(TEXT_MAIL_SUBJECT,$result['message_subject'])); ?></td>
								</tr>
								<?php }?>
								<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
								<tr>
									<td class="smallText">
									<?php 
										if($display_type!='P' && $display_type!='EM')
										{
											if ($result['message_format']=='T'){
												$message_content=strip_tags(tep_db_prepare_input($result['message_text']),'<br>');
											} else{
												$message_content=tep_db_prepare_input($result['message_text']);
											}
										?>
											<div  style="width:800px;height:100px;overflow:auto"><?php echo tep_replace_test_mail_content($message_content);?></div>						
									<?php }else{
											$details=array();
											if ($result['message_format']=='T')
												$details['html_text']=strip_tags(tep_db_prepare_input($result['message_text']),'<br>');						
											else
												$details['html_text']=tep_db_prepare_input($result['message_text']);
											

											$replace_array=array();
											tep_merge_details($replace_array,"test_default");
											
											tep_replace_template($details,$replace_array);
											
										
																			
										?>
										
										 
											<div  style="width:100%;height:100px;overflow:auto"><?php echo $details['html_text'];?></div>

									<?php }?>
									</td>
								</tr>
							</table>
		 <?php 
}
}
			$jsData->VARS["updateMenu"]=",normal,";	 
} else{	?>
	<div style="padding:50"><?php echo 'No details Found';	?></div>
<?php
		$jsData->VARS["updateMenu"]=",normal,";
}
}
?>