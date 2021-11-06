<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class shopOrdersStatus
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='sos';
		}
		

		function doDelete(){
			global $FREQUEST,$jsData;
			$status_id=$FREQUEST->postvalue('status_id','int',0);
			if ($status_id>0){
				tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" .tep_db_input($status_id) . "'");
				
				$this->doshopOrdersStatus();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_ORDER_STATUS_DELETE_SUCCESS);
				tep_reset_seo_cache('referrals');
			} else {
				echo "Err:" . TEXT_REFERRALS_NOT_DELETED;
			}
			
		}
		
		function doDeleteShopOrdersStatus(){
			global $FREQUEST,$jsData;
			$status_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' .((DEFAULT_ORDERS_STATUS_ID!=$status_id)?TEXT_INFO_DELETE_INTRO:ERROR_REMOVE_DEFAULT_ORDER_STATUS) . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="shop_orders_status.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="status_id" value="<?php echo tep_output_string($status_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $status_id;?>message">
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
							<?php if(DEFAULT_ORDERS_STATUS_ID!=$status_id){ ?>
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $status_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<?php } ?>
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $status_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doShopOrdersStatusInfo($status_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doShopOrdersStatusList($where='',$status_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			
			$query_split=false;
			
			$orders_status_sql = "select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' order by orders_status_id desc";
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('REFERRALS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$orders_status_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'shopOrdersStatus','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_ORDER_STATUS,'##PAGE_NO##') . "'})";
							}
			}
			$orders_status_query=tep_db_query($orders_status_sql);
			$found=false;
			if (tep_db_num_rows($orders_status_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($orders_status_result=tep_db_fetch_array($orders_status_query)){
			
			if (DEFAULT_ORDERS_STATUS_ID == $orders_status_result['orders_status_id']) {
			 $orders_status_result["orders_status_name"]=$orders_status_result['orders_status_name'] . ' (' . TEXT_DEFAULT . ')' ;
			} 
					$rep_array=array(	"ID"=>$orders_status_result["orders_status_id"],
										"TYPE"=>$this->type,
										"NAME"=>$orders_status_result["orders_status_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'ShopOrdersStatusInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			else if($search=='')
			{
			echo '<div align="center">'.TEXT_NO_ORDERS_STATUS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doshopOrdersStatus(){
			global $FREQUEST,$jsData;
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"NAME"=>HEADING_NEW_TITLE,
									"DISCOUNT"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'ShopOrdersStatusEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
		<div class="main" id="sos-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td><?php 	echo mergeTemplate($rep_array,$template); ?>
				</td>
			</tr>
			<tr>
				<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr  >
										<td class="main" width="47%">
										<b><?php echo  TABLE_HEADING_ORDERS_STATUS;?></b>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div align="center"><?php $this->doShopOrdersStatusList();?></div>
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
			function doShopOrdersStatusEdit()
					{
					global $FREQUEST,$jsData,$FSESSION,$languages;
					$status_id=$FREQUEST->getvalue("rID","int",0);
					$orders_info=array();
				
				 $orders_status_query= tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" .(int)$FSESSION->languages_id . "' and orders_status_id='" . tep_db_input($status_id) . "'");
				 if(tep_db_num_rows($orders_status_query)>0) $orders_info=tep_db_fetch_array($orders_status_query);
				 $sInfo=new objectInfo($orders_info);
				 
				 for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
							$result.= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']', tep_get_orders_status_name($sInfo->orders_status_id, $languages[$i]['id'])) .'<br>';
							
						  }	
			  	if (DEFAULT_ORDERS_STATUS_ID != $sInfo->orders_status_id) $result.= '<tr><td class="main" colspan="2">' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT . '</td></tr>';
					$result.='<tr><td width="40" valign="top" align="center">&nbsp;</td><td>' . tep_draw_separator('pixel_trans.gif','10','10') . '</td></tr>';
					
				 $template=getInfoTemplate($status_id);
				
				 echo tep_draw_form('status',FILENAME_ORDERS_STATUS, ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TEXT_INFO_ORDERS_STATUS_NAME,
												"NAME"=>$result,
												"TYPE"=>$this->type,
												"ID"=>$sInfo->orders_status_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('status_id',$sInfo->orders_status_id);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doShopOrdersStatusUpdate()
			{
			global $FREQUEST,$jsData,$languages,$FSESSION;
			$status_id=$FREQUEST->postvalue("status_id","int",-1);
			$insert=true;
			if ($status_id>0) $insert=false;
			
					for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
						$orders_status_name_array = $FREQUEST->postvalue('orders_status_name');
						$language_id = $languages[$i]['id'];
						$sql_data_array = array('orders_status_name' => tep_db_prepare_input($orders_status_name_array[$language_id]));
								if ($insert){
									if(empty($status_new_id)){
									$next_id_query = tep_db_query("select max(orders_status_id) as orders_status_id from " . TABLE_ORDERS_STATUS . "");
									$next_id = tep_db_fetch_array($next_id_query);
									$status_new_id = $next_id['orders_status_id'] + 1;
									}
								$sql_data_array['orders_status_id']=$status_new_id;
								$sql_data_array['language_id'] = $language_id;
								if($language_id==$FSESSION->languages_id)
								$ids=$status_new_id;
								tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array);
								$status_id=tep_db_insert_id();
								$status_id=$status_new_id;
								} else {
							tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array, 'update', "orders_status_id = '" . (int)$status_id . "' and language_id='" . (int)$language_id . "'");
						}}
			
				$default=$FREQUEST->postvalue('default');
						if (($default!='') && ($default == 'on')) {
							tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($status_id) . "' where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
							$orders_status_name_array[$language_id]=tep_db_input($orders_status_name_array[$language_id]) . ' (' . TEXT_DEFAULT . ')' ;
								if(DEFAULT_ORDERS_STATUS_ID==$status_id)
								$jsData->VARS["replace"]=array($this->type. $status_id . "name"=>$orders_status_name_array[$language_id] );
								else
								{
								$orders_status_sql = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and orders_status_id='".DEFAULT_ORDERS_STATUS_ID."'");
								$array=tep_db_fetch_array($orders_status_sql);
								$jsData->VARS["replace"]=array($this->type. $status_id . "name"=>$orders_status_name_array[$language_id],$this->type.DEFAULT_ORDERS_STATUS_ID."name"=>tep_db_input($array['orders_status_name']));
								}	
						}
					else
					{
						if(DEFAULT_ORDERS_STATUS_ID==$status_id)
						$jsData->VARS["replace"]=array($this->type. $status_id . "name"=>tep_db_input($orders_status_name_array[$language_id]). ' (' . TEXT_DEFAULT . ')' );
						else
						$jsData->VARS["replace"]=array($this->type. $status_id . "name"=>tep_db_input($orders_status_name_array[$language_id]));
					}
		
		if ($insert) {
			$this->doshopOrdersStatus();
		} else {
			$jsData->VARS["prevAction"]=array('id'=>$status_id,'get'=>'ShopOrdersStatusInfo','type'=>$this->type,'style'=>'boxRow');
			$this->doShopOrdersStatusInfo($status_id);
			$jsData->VARS["updateMenu"]=",normal,";
		}
	
			
			
				
			}
			
			function doShopOrdersStatusInfo($status_id=0){
			global $FREQUEST,$jsData,$FSESSION;
			
			if($status_id <= 0)$status_id=$FREQUEST->getvalue("rID","int",0);
			
			$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$FSESSION->languages_id . "' and orders_status_id='" . tep_db_input($status_id) . "'");
		 	
			if (tep_db_num_rows($orders_status_query)>0){
				 $orders_status_result=tep_db_fetch_array($orders_status_query);
				$template=getInfoTemplate($status_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_NAME"=>TEXT_INFO_ORDERS_STATUS_NAME ,
									"NAME"=> $orders_status_result["orders_status_name"],
									"ID"=>$orders_status_result["orders_status_id"],
									);
				
				echo mergeTemplate($rep_array,$template);
				
				$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_REFERRALS_NOT_FOUND;
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
									<td width="15" id="sos##ID##bullet">##STATUS##</td>
									<td width="49%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">(##ID##) ##NAME##</td>
									<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'ShopOrdersStatusEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteShopOrdersStatus','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'ShopOrdersStatusUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':shopOrdersStatusValidate,'uptForm':'status','customUpdate':doshopOrdersStatusUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
?>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<div class="hLineGray"></div>
			<tr>
				<td width="50%" height="60" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_NAME##</b></td>
				<td width="50%" height="60" align="left" style="overflow:hidden"  class="main">##NAME##</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	
	?>