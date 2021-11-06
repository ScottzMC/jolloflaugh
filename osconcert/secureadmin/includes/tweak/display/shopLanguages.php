<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	global $currencies;
	require(DIR_WS_CLASSES . 'currencies.php');			
	$currencies = new currencies();

	class shopLanguages{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='cug';
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
						$found=$this->doList(" where name like'%".$search_db."%'",0,$search);
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
<?php
		$jsData->VARS["NUclearType"]=$this->type;
		}
		function doDelete(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->postvalue('group_id','int',0);
			$last_flag=$FREQUEST->postvalue('lflag','int',0);
			$page=$FREQUEST->postvalue('page','int',0);
			
			if ($group_id>0){
				tep_db_query("DELETE from " . TABLE_LANGUAGES . " where languages_id=$group_id");				
				
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
			$group_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="<?php echo FILENAME_SHOP_LANGUAGES; ?>" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="group_id" value="<?php echo tep_output_string($group_id);?>"/>
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $group_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doLocationResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $group_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
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
			global $FSESSION,$FREQUEST,$jsData,$currencies;

			$page=$FREQUEST->getvalue('page','int',1);
	    	if (($FREQUEST->getvalue('search')!='') && tep_not_null($FREQUEST->getvalue('search')))  $searches = " where  (name like '%" . tep_db_input($FREQUEST->getvalue('search')) . "%' OR (code like '%".tep_db_input($FREQUEST->getvalue('search'))."%') )"; 
//	    	if (($FREQUEST->getvalue('search')!='') && tep_not_null($FREQUEST->getvalue('search')))  $searches = " where  (name like '%" . tep_db_input($FREQUEST->getvalue('search')) . "%') )";
			if ($search!=''){
				$orderBy=$searches . " order by name";
			} else {
				$orderBy="order by name";
			}
			$query_split=false;
		 	$customers_groups_sql="select * from " . TABLE_LANGUAGES . " " .$orderBy;
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
			if (tep_db_num_rows($customers_groups_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($customers_groups_result=tep_db_fetch_array($customers_groups_query)){
					/*
					if($customers_groups_result['code']==DEFAULT_LANGUAGE){
						$lang_name='<b>'.$customers_groups_result["name"].'&nbsp;<span style="font-size:10">(default)</span></b>';
					} else{
						$lang_name=$customers_groups_result["name"];
					}
					*/
					if(DEFAULT_LANGUAGE==$customers_groups_result["code"]){
					$lang_name=$customers_groups_result["name"].'&nbsp;<span style="font-size:10">(default)</span>';
					$currency_class='default_currency';
					} else{
					$lang_name=$customers_groups_result["name"];
					$currency_class='normal_currency';
					}
					$rep_array=array(	"ID"=>$customers_groups_result["languages_id"],
										"TYPE"=>$this->type,
										"NAME"=>$lang_name,
										"SORT_ORDER"=>$sort_order,
										"NAME"=>'<div id="'.$currency_class.'" class="'.$currency_class.'">'.$lang_name.'</div>',
										"DISCOUNT"=>$customers_groups_result["code"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doLanguageResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
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
									"SORT_ORDER"=>$sort_order,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'Edit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="cug-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td><?php 	echo mergeTemplate($rep_array,$template); ?>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr  >
										<td class="main" width="50%"><b><?php echo  TABLE_HEADING_LANGUAGE_NAME;?></b></td>
										<td class="main"><b><?php echo  TABLE_HEADING_LANGUAGE_CODE;?></b></td>
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
				global $FREQUEST,$jsData,$currencies;
				$sh_country_id=$FREQUEST->getvalue("rID","int",0);
				$customers_info=array();
				$group_id=$sh_country_id;
				$customers_info_query=tep_db_query("select * from " . TABLE_LANGUAGES . " where languages_id='" . (int)$sh_country_id . "' order by name");
				
				if(tep_db_num_rows($customers_info_query)>0) $customers_info=tep_db_fetch_array($customers_info_query);
				$cInfo=new objectInfo($customers_info);	?>
				<!--			started			-->		
				<table border="0" cellpadding="4" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<form action="<?php echo FILENAME_SHOP_LANGUAGES; ?>" method="post" name="customer_groups" enctype="multipart/form-data" id="customer_groups">
								<input type="hidden" name="service_resource_id" value="<?php echo tep_output_string($service_resource_id);?>"/>
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" class="productEditCol">
												<tr id="productPanelGENERALview">
														<td colspan="2" class="main" align="center">
															<table border="0" cellpadding="4" cellspacing="0">
																<div class="hLineGray"></div>
																<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
																</tr>
																<tr>
																	<td style="padding-left:50px;">
																		<table border="0" cellpadding="4" cellspacing="0">
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_LANGUAGE_NAME; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('name',$cInfo->name,'size=30 maxlength=50').tep_draw_hidden_field('service_location_id',$location_id); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_LANGUAGE_IMAGE; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('image',$cInfo->image,'size=30 maxlength=50'); ?></td>
																			</tr>
																		</table>
																	</td>
																	<td>
																		<table border="0" cellpadding="4" cellspacing="0">
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_LANGUAGE_CODE.tep_draw_hidden_field('languages_id',$sh_country_id); ?></td>
																				<td class="main"><?php echo tep_draw_input_field('code',$cInfo->code,'size=30 maxlength=2').tep_draw_hidden_field('service_location_id',$location_id); ?></td>
																			</tr>
																			<tr>
																				<td class="main"><?php echo TEXT_INFO_LANGUAGE_DIRECTORY; ?></td>
																				<td class="main"><?php echo tep_draw_input_field('directory',$cInfo->directory,'size=30 maxlength=50'); ?></td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<tr>
																<td>
																	<table border="0" cellpadding="4" cellspacing="0">
																			<tr>
																				<td class="main">SORT ORDER</td>
																				<td class="main"><?php echo tep_draw_input_field('sort_order',$cInfo->sort_order,'size=30 maxlength=50'); ?></td>
																			</tr>
																		</table>
																</td>
															</tr>
																<?php if($cInfo->code!=DEFAULT_LANGUAGE)
																{ ?>
																<tr>
																<td class="main" style="padding-left:50px;">
																<input type="checkbox" name="set_default" id="set_default" value="" />&nbsp;
																Set as default language
																</td>
																<td>&nbsp;
																
																</td>
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
			$language_referal_id=$FREQUEST->postvalue("languages_id","int",-1);

			$name=$FREQUEST->postvalue('name');
			$code=$FREQUEST->postvalue('code');
			$directory=$FREQUEST->postvalue('directory');
			$image=$FREQUEST->postvalue('image');
			$sort_order=$FREQUEST->postvalue('sort_order');
			$default=$FREQUEST->postvalue('set_default','string','');
						
			if((DEFAULT_LANGUAGE==$default) || (DEFAULT_LANGUAGE==$code)){
				$currency_name=$title.'&nbsp;<span style="font-size:10">(default)</span>';
				$currency_class='default_currency';
			} else{
				$currency_name=$title;
				$currency_class='normal_currency';
			}
			$title_div='<div id="'.$currency_class.'" class="'.$currency_class.'">'.$name.'</div>';
						
			$field_customers_groups_discount=$customers_groups_discount_sign.$customers_groups_discount;
				$sql_data = array(  'name'=>$name,
									'code'=>$code,
									'directory'=>$directory,
									'image'=>$image,
									'sort_order'=>$sort_order
								 );				

			if ($language_referal_id>0){
				tep_db_perform(TABLE_LANGUAGES, $sql_data, 'update', "languages_id = '" .$language_referal_id . "'");
			} else {
				tep_db_perform(TABLE_LANGUAGES,$sql_data);
				$group_id=tep_db_insert_id();
			}
			if ($language_referal_id>0) {
				$jsData->VARS["replace"]=array($this->type. $language_referal_id . "name"=>$title_div,$this->type . $language_referal_id . "discount"=>$code);
				$jsData->VARS["prevAction"]=array('id'=>$language_referal_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($country_referal_id);
				$jsData->VARS["updateMenu"]=",normal,";
			} else {
				$this->doItems();
			}
			if($default!=''){
				$default_array=array('configuration_value'=>$default);
				tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='".$default."' where configuration_key='DEFAULT_LANGUAGE'");
				echo "@sep@".$this->type. $language_referal_id . "name"."@sep@".$name;
			} else{
				echo "@sep@@sep@";
			}
			}
			
			function doInfo($sh_country_id=0){
			global $FREQUEST,$jsData,$FSESSION,$currencies;

			if($sh_country_id <= 0)$sh_country_id=$FREQUEST->postvalue("languages_id","int",0);
			if($sh_country_id <= 0)$sh_country_id=$FREQUEST->getvalue("rID","int",0);
			$customers_groups_query = tep_db_query("select * from " . TABLE_LANGUAGES . " where languages_id='" . (int)$sh_country_id . "' order by name");

			if (tep_db_num_rows($customers_groups_query)>0){
				$customers_groups_result=tep_db_fetch_array($customers_groups_query);
				$template=getInfoTemplate($location_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_EQUIPMENT"=>TEXT_INFO_LANGUAGE_NAME,
									"EQUIPMENT"=> $customers_groups_result["name"],
									"ENT_CONTACT"=>TEXT_INFO_LANGUAGE_CODE,
									"CONTACT"=>$customers_groups_result["code"],
									"ENT_SEATS"=>TEXT_LOCATION_MAX_SEATS,
									"SEATS"=>$customers_groups_result["image"],
									"SORT_ORDER"=> $customers_groups_result["sort_order"],
									"ID"=>$customers_groups_result["countries_id"],
									);
				
				echo mergeTemplate($rep_array,$template);
				
				$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
			}
			
			}			
		
		}function getListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="15" id="cug##ID##bullet">##STATUS##</td>
									<td width="50%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td width="40%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##discount">##DISCOUNT##</td>
									<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'customer_groups','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
		ob_start();
?><table border="0" cellpadding="0" cellspacing="0" width="50%">
			<tr>
				<td valign="top" width="20" ><div style="width:100%;height:100px;overflow:hidden"></div></td>
			<td valign="middle" width="80%">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr>
				<td valign="top" width="20%" align="left" nowrap="nowrap" class="main">##ENT_EQUIPMENT##</td><td width="5%">:</td>
				<td valign="top" width="25%" align="left" class="main">##EQUIPMENT##</td>
			</tr>
			<tr>
				<td valign="top" width="20%" align="left" class="main">##ENT_CONTACT##</td><td width="5%">:</td>
				<td valign="top" width="10%"  align="left" class="main">##CONTACT##</td>
			</tr>
			<tr>
				<td valign="top" width="20%" align="left" class="main">##SORT_ORDER##</td><td width="5%">:</td>
				<td valign="top" width="10%"  align="left" class="main"></td>
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