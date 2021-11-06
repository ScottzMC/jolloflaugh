<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	
	define("TICKET_TEXT_1","Text 1");
	define("TICKET_TEXT_2","Text 2");
	define("TICKET_TEXT_3","Text 3");
	define("TICKET_TEXT_4","Text 4");
	define("TICKET_TEXT_5","Text 5");
	define("TICKET_TEXT_6","Text 6");
	define("TICKET_TEXT_CONDITIONS","Text Conditions");
	define("TICKET_TEXT_7","Text 7");
	
	define("TEXT_CODE","Code");
	define("TEXT_PDA","Time Available");
	define("TEXT_PN","Products Name");
	define("TEXT_PDS","Products Description");
	define("TEXT_OID","Order ID");
	define("TEXT_CPM","Concert ID");
	define("TEXT_RI","Ref ID");
	define("TEXT_PI","Prd ID");
	define("TEXT_DS","Coupon");
	define("TEXT_GAT","GA ID");//GA run
	//concert headings
	define("TEXT_CHN","Concert Name");
	define("TEXT_CCV","Concert Venue");
	define("TEXT_CCD","Concert Date");
	define("TEXT_CCT","Concert Time");
	//add prices
	define("TEXT_CP1","Concert Price");
	define("TEXT_CP2","Symbol");
	define("TEXT_CDT","Discount Type");
	define("TEXT_CST","Season Ticket");
	define("TEXT_BN","Billing Name");
	define("TEXT_CUN","Customers Name");
	define("TEXT_CEA","Customers Email");
	//Customers Extra Info>New Field
	define("TEXT_CEI","New Field");
	//payment method
	define("TEXT_PAY","Payment");
	define("TEXT_PD","Payment Date");
	define("TEXT_DATE","Server Date");
	// Add Manufacturers Name
	define("TEXT_MN","Manu Name");
	//Spacing
	define("SPACE_25","SPACE 25");
	define("SPACE_20","SPACE 20");
	define("SPACE_15","SPACE 15");
	define("SPACE_10","SPACE 10");
	define("SPACE_5","SPACE 5");
	
	global $currencies;
	require(DIR_WS_CLASSES . 'currencies.php');			
	$currencies = new currencies();

	class paymentGeneralTemplates{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='cug';
		}
		

		function doDelete(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->postvalue('group_id','string','');
			$last_flag=$FREQUEST->postvalue('lflag','int',0);
			$page=$FREQUEST->postvalue('page','int',0);

			if ($group_id){
				tep_db_query("DELETE from " . TABLE_GENERAL_TEMPLATES . " where template_type='$group_id'");				
				
				if ($last_flag==1 && $page>1){
					$page=$page-1;
					$FREQUEST->setvalue('page',$page,'GET');
				}

				$this->doItems();
				$jsData->VARS["deleteRow"]=array("id"=>$group_id,"type"=>$this->type);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_GROUP_DELETE_SUCCESS);
				tep_reset_seo_cache('customers');
			} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
			}
		}
		
		function doDeleteGroups(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->getvalue('rID','string','');
			$delete_id=$FREQUEST->getvalue('ID');

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>'; ?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="<?php echo FILENAME_PAYMENT_GENERAL_TEMPLATES; ?>" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="group_id" value="<?php echo tep_output_string($group_id);?>"/>
				<input type="hidden" name="type" value="<?php echo tep_output_string($group_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $group_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $delete_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doLocationResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelDelete({id:<?php echo $delete_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($group_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doList($where='',$group_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData,$currencies,$template_array;

			if($FREQUEST->getvalue('type')!='TIC'){
				
			}
			$page=$FREQUEST->getvalue('page','int',1);
			if ($search!=''){ 
				$orderBy="order by customers_groups_id";
			} else {
				$orderBy="order by customers_groups_id";
			}
			$query_split=false;
			

    	if (($FREQUEST->getvalue('search')!='') && tep_not_null($FREQUEST->getvalue('search')))  $search = " where  (service_location_name like '%" . tep_db_input($FREQUEST->getvalue('search')) . "%' OR (service_location_contact_person like '%".tep_db_input($FREQUEST->getvalue('search'))."%') )";  
	 	$customers_groups_sql="select * from " . TABLE_LANGUAGES . " order by name";			

			$customers_groups_query=tep_db_query($customers_groups_sql);
			$found=false;
			if (tep_db_num_rows($customers_groups_query)>0) $found=true;
			if($found){
				$template=getListTemplate();
				$icnt=1;	
				for ($icnt=0;$icnt<count($template_array);$icnt++){
					 $type=$template_array[$icnt]['id'];
	
					$rep_array=array(	"ID"=>$icnt,
										"TYPE"=>$this->type,
										"NAME"=>$template_array[$icnt]['text'],
										"ID_TYPE"=>$template_array[$icnt]['id'],
										"DISCOUNT"=>$customers_groups_result["code"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doPaymentResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
					echo mergeTemplate($rep_array,$template);
				} 
			}
			else if($search=='')
			{
			echo '<div align="center">'.TEXT_EMPTY_GROUPS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doItems(){
			global $FREQUEST,$jsData;

			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"NAME"=>TEXT_INFO_HEADING_NEW_LANGUAGE,
									"DISCOUNT"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'Edit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="cug-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr  >
										<td class="main" width="50%">
										<b><?php echo  TEXT_TEMPLATE_NAME;?></b>
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
					</Table>
				</td>
			</tr>
		</table>
		<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }

			 	
			}
			function doEdit(){
				global $FREQUEST,$jsData,$currencies,$position_array;
				$sh_country_id=$FREQUEST->getvalue("rID","string",'');
				$customers_info=array();
				$group_id=$sh_country_id;
				$template_type=$sh_country_id;
				$template_query=tep_db_query("SELECT template_id,template_type,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='" . tep_db_input($template_type) . "'");

				if(tep_db_num_rows($template_query)>0){ 
				$template_info=tep_db_fetch_array($template_query);
				if(is_array($template_info))
					$tInfo=new objectInfo($template_info);
				}
				//starts here
				$teDetails=array(	'template_width'=>'18.00',
									'template_height'=>'7.00',
									'shop_logo_image'=>'',
									'shop_logo_position'=>'L',
									'member_logo_image'=>'member_logo.png',
									'member_logo_position'=>'L',
									'event_details_content'=>'',
									'event_details_position'=>'R',
									'sponsor_logo_image'=>"sponsor_logo.png",
									'sponsor_logo_position'=>'R',
									'event_condition_content'=>'',
									'event_condition_position'=>'L',
									'bar_image_position'=>'R'
									);
									
				if ($template_type=="ICD") $teDetails["event_condition_content"]="";
				
				
				if (TICKET_TEMPLATE==1){
					$template_type='TIC';
				} if (TICKET_TEMPLATE==2){
					$template_type='TIC2';
				} if (TICKET_TEMPLATE==3){
					$template_type='TIC3';
				} if (TICKET_TEMPLATE==4){
					$template_type='TIC4';
				}  if (TICKET_TEMPLATE==5){
					$template_type='TIC5';
				}if (TICKET_TEMPLATE==6){
					$template_type='TIC6';
				}if (TICKET_TEMPLATE==7){
					$template_type='TIC7';
				}

				//$template_query=tep_db_query("SELECT template_type,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='" . tep_db_input($template_type) . "'");
				$template_query=tep_db_query("SELECT template_type,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='" . $template_type . "'");
				if (tep_db_num_rows($template_query)>0){
					$template_result=tep_db_fetch_array($template_query);					
					$template_splt=preg_split("/{}/",$template_result["template_content"]);

					for ($icnt=0;$icnt<count($template_splt);$icnt=$icnt+2){
						$key=$template_splt[$icnt];
						$teDetails[$key]=$template_splt[$icnt+1];
					}
					$teDetails["template_width"]=$template_result["template_width"];
					$teDetails["template_height"]=$template_result["template_height"];
					$teInfo=new objectInfo($teDetails);

				} else $teInfo=new objectInfo($teDetails);					
				$merge_fields=array(array('id'=>'FN','text'=>TEXT_FN),
									array('id'=>'LN','text'=>TEXT_LN),
									array('id'=>'CUN','text'=>TEXT_CUN),
									array('id'=>'OID','text'=>TEXT_OID),
									array('id'=>'CPM','text'=>TEXT_CPM),
									array('id'=>'CEA','text'=>TEXT_CEA),
									array('id'=>'GAT','text'=>TEXT_GAT),
									array('id'=>'CHN','text'=>TEXT_CHN),
									array('id'=>'CCV','text'=>TEXT_CCV),
									array('id'=>'CCD','text'=>TEXT_CCD),
									array('id'=>'CDT','text'=>TEXT_CDT),
									array('id'=>'CCT','text'=>TEXT_CCT),
									array('id'=>'CP1','text'=>TEXT_CP1),
									array('id'=>'CP2','text'=>TEXT_CP2),
									array('id'=>'CDT','text'=>TEXT_CDT),
									array('id'=>'BN','text'=>TEXT_BN),
									array('id'=>'PN','text'=>TEXT_PN),
									array('id'=>'PAY','text'=>TEXT_PAY),
									array('id'=>'PD','text'=>TEXT_PD),
									array('id'=>'RI','text'=>TEXT_RI),
									array('id'=>'PI','text'=>TEXT_PI),
									array('id'=>'DS','text'=>TEXT_DS),
									array('id'=>'SD','text'=>TEXT_SD),
									array('id'=>'CEI','text'=>TEXT_CEI),
									array('id'=>'MN','text'=>TEXT_MN),
									array('id'=>'25','text'=>TEXT_25),
									array('id'=>'20','text'=>TEXT_20),
									array('id'=>'15','text'=>TEXT_15),
									array('id'=>'10','text'=>TEXT_10),
									array('id'=>'5','text'=>TEXT_5),
									array('id'=>'BUN','text'=>TEXT_BUN),
									);
				$position_array=array(	array('id'=>'L','text'=>TEXT_ALIGN_LEFT),
										array('id'=>'R','text'=>TEXT_ALIGN_RIGHT),
								);
				//ends here

			?>
				 
				<!--			started			-->		
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<form action="<?php echo FILENAME_PAYMENT_GENERAL_TEMPLATES; ?>" method="post" name="customer_groups" enctype="multipart/form-data" id="customer_groups">
								<input type="hidden" name="service_resource_id" value="<?php echo tep_output_string($service_resource_id);?>"/>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
												<tr id="productPanelGENERALview">
													<td colspan="2" class="main" align="left" style="padding-left:50px; padding-bottom:50px;">
														<table border="0" cellpadding="4" cellspacing="0">
															<div class="hLineGray"></div>
															<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
															</tr>
															<tr>
																<td><?php
																	if(tep_db_num_rows($template_query)<=0){ ?>
																	<input type="hidden" name="action_type" id="action_type" value="create_template" />
																	<?php } else{	?>
																	<input type="hidden" name="action_type" id="action_type" value="update_template" />
																	<?php } ?>
																	<input type="hidden" name="template_type" id="template_type" value="<?php echo $template_type; ?>" />
																	<input type="hidden" id="template_id" name="template_id" value="<?php echo (($tInfo->template_id>0)?($tInfo->template_id):'0'); ?>" />
																	<table border="0" cellpadding="4" cellspacing="0">
																	<tr>
																		<td class="main" colspan="2">
																		<strong><?php echo TEXT_SIZE; ?></strong></td>
																	</tr>
																	<tr>
																		<td class="main" width="25%"><?php echo TEXT_TEMPLATE_WIDTH; ?></td>
																		<td class="main"><?php echo tep_draw_input_field('template_width',number_format($teInfo->template_width,2),'size="10"').'&nbsp;'.TEXT_DIMENSION; ?></td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo TEXT_TEMPLATE_HEIGHT; ?></td>
																		<td class="main"><?php echo tep_draw_input_field('template_height',number_format($teInfo->template_height,2),'size="10"').'&nbsp;'.TEXT_DIMENSION; ?></td>
																	</tr>
																	<?php 
																	if(substr($FREQUEST->getvalue('rID'),0,3)=='ICD')
																	{
																	?>
																	
																	<?php } 
																	
																	else{	?>
																	<tr>
																		<td class="main" colspan="2"><?php echo TEXT_SHOP_LOGO; //added by cartzone ?></td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo TEXT_IMAGE; ?></td>
																		<td class="main"><?php echo tep_draw_input_field('shop_logo_image',$teInfo->shop_logo_image,'size=30 maxlength=50'); ?></td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo TEXT_POSITION; ?></td>
																		<td class="main"><?php echo tep_draw_pull_down_menu('shop_logo_position',$position_array,$teInfo->shop_logo_position,' style="width:75;" maxlength=50'); ?></td>
																	</tr>
																	<?php } ?>
																	
																	<tr style="display:none">
																		<td class="main"><?php //echo TEXT_IMAGE; ?></td>
																		<td class="main"><?php echo tep_draw_input_field('sponsor_logo_image',$teInfo->sponsor_logo_image,'size=30 maxlength=50').tep_draw_hidden_field('service_location_id',$location_id); ?></td>
																	</tr>
																	<tr style="display:none">
																		<td class="main"><?php echo TEXT_POSITION; ?></td>
																		<td class="main"><?php echo tep_draw_pull_down_menu('sponsor_logo_position',$position_array,$teInfo->sponsor_logo_position,' style="width:75;" maxlength=50'); ?></td>
																	</tr>
																	<!--<tr>
																		<td class="main" colspan="2">
																		<strong><?php //echo TEXT_BAR_CODE_IMAGE; ?></strong></td>
																	</tr>
																	<tr>
																		<td class="main"><?php //echo TEXT_POSITION; ?></td>
																		<td class="main"><?php //echo tep_draw_pull_down_menu('bar_image_position',$position_array,$teInfo->bar_image_position,' style="width:75;" maxlength=50'); ?></td>
																	</tr>-->

																	<?php if (substr($template_type,0,3)=="ICD") { ?>
														
																	<?php } else { ?>
																	<tr>
																		<td class="main" colspan="2"><?php echo '<b>'.(($template_type=="TICS")?(TEXT_SERVICE_CONDITION):(TEXT_EVENT_CONDITION)).'</b>'; ?></td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo (($template_type=="TICS")?(TEXT_SERVICE_CONDITION):(TEXT_EVENT_CONDITION)); ?></td>
																		<td class="main"><?php echo tep_draw_textarea_field('event_condition_content','',60,6,$teInfo->event_condition_content); ?></td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo TEXT_POSITION; ?></td>
																		<td class="main"><?php echo tep_draw_pull_down_menu('event_condition_position',$position_array,$teInfo->event_condition_position,' style="width:75;" maxlength=50'); ?></td>
																	</tr>

																	<tr>
																		<td  class="main" colspan="2"><b><?php echo (($template_type=="TICS")?(TEXT_SERVICE_DETAILS):(TEXT_EVENT_DETAILS));?></b></td>
																	</tr>
																	<tr>
						<Td class="main" valign="top" width="100"><?php echo TEXT_CONTENT; ?></Td>
						<Td class="main" valign="top"><?php echo tep_draw_textarea_field('event_details_content','',80,25,$teInfo->event_details_content,' style="height:250;"');
						echo tep_draw_pull_down_menu('merge_fields',$merge_fields,'','size=6 style="height:250" onDblClick="javascript:AddField(\'event_details_content\');"'); 
						?>
						</Td>
																	</tr>
																	<tr>
																		<td class="main"><?php echo TEXT_POSITION;?></td>
																		<td class="main" colspan="2"><?php echo tep_draw_pull_down_menu('event_details_position',$position_array,$teInfo->event_details_position,' style="width:75;"');?></td>
																	</tr>
                                                                    	<tr>
																		<td class="main"><?php echo TEXT_OSCONCERT;?></td>
																		<td class="main" colspan="2"><?php include('ticket_guide.php'); ?></td>
																	</tr>
																	<tr>
																		<td class="main" style="display:none"><?php echo TEXT_INFO_LANGUAGE_DIRECTORY; ?></td>
																		<td class="main" style="display:none"><?php echo tep_draw_input_field('directory',$cInfo->directory,'size=30 maxlength=50',''); ?></td>
																	</tr>
																<?php } ?>
														</table>
													</td>
												</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
				<!--			ended			-->
<?php					echo tep_draw_hidden_field('country_id',$sh_country_id);
				//		echo mergeTemplate($rep_array,$template);
						
					//	echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	


			
			function doUpdate(){
				global $FREQUEST,$jsData,$currencies;
				$language_referal_id=$FREQUEST->postvalue("template_id","int",-1);
				//started
				$template_type=$FREQUEST->postvalue('template_type');
			$sql_array=array(	"template_width"=>$FREQUEST->postvalue('template_width'),
								"template_height"=>$FREQUEST->postvalue('template_height'));
			 $tem_query=tep_db_query("select template_type from ".TABLE_GENERAL_TEMPLATES." where template_type='".tep_db_input($template_type)."'");
			if(!$tem_query) $action='create_template';  									
			//if (substr($template_type,0,3)=="ICD") {
				//$content.="member_logo_image{}" . $FREQUEST->postvalue('member_logo_image');
				//$content.="{}member_logo_position{}" . $FREQUEST->postvalue('member_logo_position');
			//} else {
				$content.="shop_logo_image{}" . $FREQUEST->postvalue('shop_logo_image');
				$content.="{}shop_logo_position{}" . $FREQUEST->postvalue('shop_logo_position');
			//}
			//if (substr($template_type,0,3)=="ICD") {
				//$content.="{}customer_details_content{}" . $FREQUEST->postvalue('customer_details_content');
				//$content.="{}customer_details_large{}" . $FREQUEST->postvalue('customer_details_large');
			//} else {
				$content.="{}event_details_content{}" . $FREQUEST->postvalue('event_details_content');
				$content.="{}event_details_position{}" . $FREQUEST->postvalue('event_details_position');
			//}
			$content.="{}sponsor_logo_image{}" . $FREQUEST->postvalue('sponsor_logo_image');
			$content.="{}sponsor_logo_position{}" . $FREQUEST->postvalue('sponsor_logo_position');
			$content.="{}event_condition_content{}" . $FREQUEST->postvalue('event_condition_content');
			$content.="{}event_condition_position{}" . $FREQUEST->postvalue('event_condition_position');
			//if ($template_type=="ICD") {
				//$content.="{}event_condition_large{}" . $FREQUEST->postvalue('event_condition_large');
			//}
			$content.="{}bar_image_position{}" . $FREQUEST->postvalue('bar_image_position');

			$sql_array["template_content"]=$content;

			if ($FREQUEST->postvalue('action_type')=="create_template"){
				$sql_array["template_type"]=$template_type;
				tep_db_perform(TABLE_GENERAL_TEMPLATES,$sql_array);
			} 
			else tep_db_perform(TABLE_GENERAL_TEMPLATES,$sql_array,"update","template_type='" . tep_db_input($template_type) . "'");
				//ends

				if ($FREQUEST->postvalue('action_type')!="create_template") {
				//	$jsData->VARS["replace"]=array($this->type. $language_referal_id . "name"=>$name,$this->type . $language_referal_id . "discount"=>$code);
				//	$jsData->VARS["prevAction"]=array('id'=>$language_referal_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
					$this->doInfo($country_referal_id);
				//	$jsData->VARS["updateMenu"]=",normal,";
				} else {
					$country_referal_id=tep_db_insert_id();
					$this->doInfo($country_referal_id);
				}
				
			}
			
		function doInfo($sh_country_id=0){
				global $FREQUEST,$jsData,$currencies,$position_array;
				$sh_country_id=$FREQUEST->getvalue("rID","string",'');
				if($sh_country_id==''){
					$sh_country_id=$FREQUEST->postvalue('template_type');
				}

				$customers_info=array();
				$group_id=$sh_country_id;
				$template_type=$sh_country_id;
				$template_query=tep_db_query("SELECT template_id,template_type,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='" . $template_type . "'");

		//		if (tep_db_num_rows($template_query)>0){
					$customers_groups_result=tep_db_fetch_array($template_query);
					$template=getInfoTemplate($location_id);
				//	$icnt=$FREQUEST->getvalue('ID','int','');

					if(($sh_country_id=='TIC') || ($sh_country_id=='TICS')){
						if($sh_country_id=='TIC')
							$icnt=0;
						else
							$icnt=1;
						$template_array=array(array("id"=>"TIC",'text'=>TEXT_TIC),
											  array("id"=>"TICS",'text'=>TEXT_TICS));
						$template_name=$template_array[$icnt]['text'];
					} else{
						// if($sh_country_id=='ICD')
						// {
							// $icnt=0;
						// }else{
							$icnt=1;
						//}
						$template_array=array(array("id"=>"ICD",'text'=>TEXT_ICD),
										  array("id"=>"ICDS",'text'=>TEXT_ICDS));			
						$template_name=$template_array[$icnt]['text'];
						
					}
					$rep_array=array(	"TYPE"=>$this->type,
										"ENT_EQUIPMENT"=>TEXT_TEMPLATE_NAME,
										"EQUIPMENT"=> $template_name,
										"ENT_CONTACT"=>'',
										"CONTACT"=>'',
										"ENT_SEATS"=>TEXT_LOCATION_MAX_SEATS,
										"SEATS"=>$customers_groups_result["image"],
										"ID"=>$customers_groups_result["countries_id"],
										);
					
					echo mergeTemplate($rep_array,$template);
					
					$jsData->VARS["updateMenu"]=",normal,";
		//		} else{
		//			echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
		//			echo "SELECT template_id,template_type,template_width,template_height,template_content from " . TABLE_GENERAL_TEMPLATES . " where template_type='" . tep_db_input($template_type) . "'";
		//		}
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
									<td width="15" id="cug##ID##bullet">##STATUS##</td>
									<td width="50%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID_TYPE##&ID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td width="35%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID_TYPE##&ID=##ID##'});" id="##TYPE####ID##discount">##DISCOUNT##</td>
									<td  width="15%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID_TYPE##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayPayment({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID_TYPE##&ID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
									
										
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Close"/></a>
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
	function getInfoTemplate(){
		ob_start();	?>
	<table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="20" >
			<div style="width:100%;height:100px;overflow:hidden"></div></td>
			<td valign="middle" width="80%">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr>
				<td valign="top" width="40%" align="left" nowrap="nowrap" class="main">##ENT_EQUIPMENT##</td><td width="5%">:</td>
				<td valign="top" width="55%" align="left" class="main">##EQUIPMENT##</td>
			</tr>
			<tr>
				<td valign="top" width="40%" align="left" class="main">##ENT_CONTACT##</td><td width="5%">&nbsp;</td>
				<td valign="top" width="55%"  align="left" class="main">##CONTACT##</td>
			</tr>
		</table>
</td>
				
			</tr>
			
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
?>