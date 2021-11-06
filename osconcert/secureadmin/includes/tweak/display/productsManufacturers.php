<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	// Set flag that this is a parent file
	defined('_FEXEC') or die();
	class productsManufacturers{
		var $pagination;
		var $splitResult;
		var $type;
		function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type = 'prdmfc';
		}
		function doManufacturersList(){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			$orderBy=" order by m.manufacturers_id desc";
			$query_split=false;
			$manufacturer_sql = "select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m,".TABLE_MANUFACTURERS_INFO." mi where m.manufacturers_id=mi.manufacturers_id and mi.languages_id='".(int)$FSESSION->languages_id."' $orderBy";
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('PRDMFC');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$manufacturer_sql);
				if ($query_split->queryRows > 0){ 
					$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'GetManufacturers','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_DATA,'##PAGE_NO##') . "'})";
				}
			}
			$manufacturer_query=tep_db_query($manufacturer_sql);
			
			$found=false;
			if (tep_db_num_rows($manufacturer_query)>0) $found=true;
			
			if($found){
				$template=getListTemplate();
				$icnt=1;
				while($manufacturer_result=tep_db_fetch_array($manufacturer_query)){
					$rep_array=array(	"ID"=>$manufacturer_result["manufacturers_id"],
										"TYPE"=>$this->type,
										"NAME"=>$manufacturer_result["manufacturers_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"UPDATE_DATA"=>TEXT_UPDATE_DATA,
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
					echo mergeTemplate($rep_array,$template);
					$icnt++;
				}
				if (!isset($jsData->VARS["Page"])){
					$jsData->VARS["NUclearType"][]=$this->type;
				} 
			}else{
				echo TEXT_MANUFACTURER_NOT_FOUND;
			}	
			return $found;			
		}
		function doUpdateManufacturer(){
			global $FSESSION,$FREQUEST,$LANGUAGES,$jsData,$SERVER_DATE_TIME;
			$ID=$FREQUEST->postvalue("manufacturers_id","int",-1);
			$insert=true;
			if ($ID>0) $insert=false;

			$sql_array=array();

			$manufacturers_name=$FREQUEST->postvalue('manufacturers_name');
			$manufacturers_image=$FREQUEST->postvalue('manufacturers_image');

			$sql_array = array(  'manufacturers_name' => tep_db_prepare_input($manufacturers_name),
								'manufacturers_image' =>tep_db_prepare_input($manufacturers_image)
							 );
			if ($insert){
				$sql_array["date_added"]=$SERVER_DATE_TIME;
				tep_db_perform(TABLE_MANUFACTURERS,$sql_array);
				$ID=tep_db_insert_id();
			} else {
				$sql_array["last_modified"]=$SERVER_DATE_TIME;
				tep_db_perform(TABLE_MANUFACTURERS,$sql_array,"update","manufacturers_id=$ID");
			}
			
			$manufacturers_url=&$FREQUEST->getRefValue("manufacturers_url","POST");

			for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
				$lang_id=$LANGUAGES[$icnt]["id"];
				$sql_array=array("manufacturers_url"=>tep_db_prepare_input($manufacturers_url[$lang_id]));
				$info_insert=true;
				if (!$insert){
					$check_query=tep_db_query("SELECT manufacturers_id from " . TABLE_MANUFACTURERS . " where manufacturers_id=$ID");
					if (tep_db_num_rows($check_query)>0) $info_insert=false;
				}
				if ($info_insert){
					$sql_array["manufacturers_id"]=$ID;
					$sql_array["languages_id"]=$lang_id;
					tep_db_perform(TABLE_MANUFACTURERS_INFO,$sql_array);
				} else {
					tep_db_perform(TABLE_MANUFACTURERS_INFO,$sql_array,"update","manufacturers_id=$ID and languages_id=$lang_id");
				}
			}

			if ($insert) {
				$this->doGetManufacturers();
			} else {
				$jsData->VARS["replace"]=array($this->type . $ID . "name"=>$manufacturers_name);
				$jsData->VARS["prevAction"]=array('id'=>$ID,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($ID);
				$jsData->VARS["updateMenu"]=",normal,";
			}
		}
		function doEditManufacturer(){
			global $FREQUEST,$FSESSION,$LANGUAGES,$CAT_TREE,$jsData;
			$languages=&$LANGUAGES;
			$manufacturer_id=$FREQUEST->getvalue('rID','int',0);

			for ($icnt=0,$n=count($LANGUAGES);$icnt<$n;$icnt++){
				$manufacturers_url[$LANGUAGES[$icnt]['id']]='';
			}

			if ($manufacturer_id<=0) $mode="new";
			if ($manufacturer_id>0){
				$manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, m.date_added, m.last_modified, mi.languages_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m,".TABLE_MANUFACTURERS_INFO." mi where  m.manufacturers_id=mi.manufacturers_id and mi.languages_id='".(int)$FSESSION->languages_id."' and m.manufacturers_id='".(int)$manufacturer_id."' order by m.manufacturers_name asc");
				$manufacturer_result=tep_db_fetch_array($manufacturer_query);
				while($manufacturers_result=tep_db_fetch_array($manufacturer_query)){
					$temp_lang=$manufacturers_result["languages_id"];
					$manufacturer_url[$temp_lang]=$manufacturers_result["manufacturers_url"];
					$manufacturer_name=$manufacturers_result["manufacturers_name"];
					$manufacturer_image=$manufacturers_result["manufacturers_image"];
				}
			}else {
				$manufacturer_result=array('manufacturers_id'=>0,'manufacturers_name'=>'','manufacturers_url'=>'','manufacturers_image'=>'');
			}

			$manufacturer=new objectInfo($manufacturer_result);
			$jsData->VARS["updateMenu"]=",update,";
			$display_mode_html=' style="display:none"';
?>
			<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="manufacturerSubmit" id="manufacturerSubmit">
				<input type="hidden" name="manufacturers_id" value="<?php echo tep_output_string($manufacturer_id);?>"/>
			  	<table border="0" cellspacing="0" cellpadding="3" width="100%">
					<tr>
						<td  colspan="2" class='inner_title'><?php echo (($manufacturer_id<=0)?TEXT_HEADING_NEW_MANUFACTURER:TEXT_HEADING_EDIT_MANUFACTURER);?></td>
					</tr>
					<tr>
						<td colspan="2" class='main'><?php echo TEXT_EDIT_INTRO;?></td>
					</tr>
					<tr>
						<td class='main' width='25%'><?php echo TEXT_MANUFACTURERS_NAME;?></td>
						<td class='main' align='left' width='75%'><?php echo tep_draw_input_field('manufacturers_name', $manufacturer->manufacturers_name);?></td>
					</tr>
					<tr>
						<td class='main' width='25%'><?php echo TEXT_MANUFACTURERS_IMAGE;?></td>
						<td class='main' width='75%'>						
							<div id="manufacturers_image_file_container">
							<?php echo tep_draw_file_field('manufacturers_image_file') .tep_draw_hidden_field('manufacturers_image',$manufacturer->manufacturers_image). '<br>' . $manufacturer->manufacturers_image;?>
							</div>	
						</td>
					</tr>
					<?php 	  		                                       
						for ($i=0, $n=sizeof($LANGUAGES); $i<$n; $i++) {        
							$edit.= '<tr><td class="main" width="25%">'.TEXT_MANUFACTURERS_URL.'</td>';
							$edit.=	'<td class="main" width="75%">';        
							$edit.= tep_image(DIR_WS_CATALOG_LANGUAGES . $LANGUAGES[$i]['directory'] . '/images/' . $LANGUAGES[$i]['image'], $LANGUAGES[$i]['name']).'&nbsp;';
							$edit.= tep_draw_input_field('manufacturers_url[' . $LANGUAGES[$i]['id'] . ']', tep_get_manufacturer_url($manufacturer->manufacturers_id, $LANGUAGES[$i]['id']));
							$edit.= '</td></tr>';
						}	         
						echo $edit;
					?>      
					<tr>
						<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					</tr>
				<tr>
					<td class="main" id="<?php echo $this->type . $manfucaturer_id;?>message"></td>
				</tr>
				</table>
			</form>
<?php			
		}
		function doDelete(){
			global $FREQUEST,$jsData;
			$manufacturer_id=$FREQUEST->postvalue('manufacturer_id','int',0);
			if ($manufacturer_id>0){
				$manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturer_id . "'");
				$manufacturer = tep_db_fetch_array($manufacturer_query);
				$image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];
				if (file_exists($image_location)) @unlink($image_location);
				tep_db_query("DELETE from " . TABLE_MANUFACTURERS . " where manufacturers_id='".(int)$manufacturer_id."'");
				tep_db_query("DELETE from " . TABLE_MANUFACTURERS_INFO. " where manufacturers_id='".(int)$manufacturer_id."' and languages_id='".(int)$FSESSION->languages_id."'");
				$this->doGetManufacturers();
				//$jsData->VARS["deleteRow"]=array("id"=>$manufacturer_id,"type"=>$this->type);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_MANUFACTURE_DELETE_SUCCESS);
				tep_reset_seo_cache('location');
			} else {
				echo "Err:" . TEXT_MANUFACTURER_NOT_DELETED;
			}
		}
		function doDeleteManufacturer(){
			global $FREQUEST,$jsData,$FSESSION;
			$manufacturer_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="products_manufacturers.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="manufacturer_id" value="<?php echo tep_output_string($manufacturer_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $manufacturer_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $manufacturer_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $manufacturer_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doInfo($item_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doGetManufacturers(){
			global $FREQUEST,$jsData;
			$template=getListTemplate();
			$rep_array=array(	"TYPE"=>$this->type,
								"ID"=>-1,
								"NAME"=>TEXT_HEADING_NEW_MANUFACTURER,
								"DETAILS"=>'',
								"CONTACT_PERSON"=>'',
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
								"UPDATE_RESULT"=>'doTotalResult',
								"UPDATE_DATA"=>TEXT_UPDATE_DATA,
								"ROW_CLICK_GET"=>'EditManufacturer',
								"FIRST_MENU_DISPLAY"=>"display:none"
							);

?>
			<div class="main" id="prd-1message"></div>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<div><?php 	echo mergeTemplate($rep_array,$template); ?></div>
				<div align="center"><?php $this->doManufacturersList();?></div>
			</table>	
			<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }
		}
		function doInfo($manufacturer_id=0){
			global $FSESSION,$FREQUEST,$jsData,$manufacturer_result ;
			if ($manufacturer_id<=0) $manufacturer_id=$FREQUEST->getvalue("rID","int",0);
			$manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name,m.manufacturers_image,m.date_added,m.last_modified,mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m,".TABLE_MANUFACTURERS_INFO." mi where m.manufacturers_id=mi.manufacturers_id and m.manufacturers_id='".(int)$manufacturer_id."' and mi.languages_id='".(int)$FSESSION->languages_id."' order by m.manufacturers_name asc");
			if (tep_db_num_rows($manufacturer_query)>0){
				$manufacturer_result=tep_db_fetch_array($manufacturer_query);
				$template=getInfoTemplate($manufacturer_id);
				$rep_array=array(	"MFC_DATE_ADDED_KEY"=>((format_date($manufacturer_result["date_added"])=='')?(""):(TEXT_INFO_DATE_ADDED)),
									"MFC_DATE_ADDED_DATA"=>((format_date($manufacturer_result["date_added"])=='')?(""):(format_date($manufacturer_result["date_added"]))),
									"MFC_DATE_MODIFIED_KEY"=>((format_date($manufacturer_result["last_modified"])=='')?(""):(TEXT_LAST_MODIFIED)),
									"MFC_DATE_MODIFIED_DATA"=>((format_date($manufacturer_result["last_modified"])=='')?(""):(format_date($manufacturer_result["last_modified"]))),
									"MFC_URL_KEY"=>(($manufacturer_result["manufacturers_url"]!='')?(TEXT_URL):''),
									"MFC_COLON"=>(($manufacturer_result["manufacturers_url"]!='')?(':'):''),
									"MFC_URL_DATA"=>(($manufacturer_result["manufacturers_url"]!='')?($manufacturer_result["manufacturers_url"]):''),
									"MFC_IMAGE_WIDTH"=>SMALL_IMAGE_WIDTH,
									"MFC_IMAGE"=>tep_display_image($manufacturer_result["manufacturers_image"],$manufacturer_result["manufacturers_image"],SMALL_IMAGE_WIDTH,'','',true),
									"UPDATE_RESULT"=>'doDisplayResult'

								);
				echo mergeTemplate($rep_array,$template);
				$jsData->VARS["updateMenu"]=",normal,";
			} else {
				echo 'Err:' . TEXT_MANUFACTURER_NOT_FOUND;
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
									<td width="1%" id="##TYPE####ID##bullet">##STATUS##</td>
									<td width="35%"class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##" >
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'EditManufacturer','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="<?php echo IMAGE_EDIT_MANUFACTURER;?>"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'DeleteManufacturer','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="<?php echo IMAGE_DELETE_MANUFACTURER;?>"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'UpdateManufacturer','type':'##TYPE##','imgUpdate':true,'style':'boxRow','validate':manufacturerValidate,'uptForm':'manufacturerSubmit','customUpdate':doManufacturerUpdate,'result':##UPDATE_RESULT##,'message1':'##UPDATE_DATA##'});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
     global $manufacturer_result;
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50">&nbsp;</td>
				<td valign="top" width="##MFC_IMAGE_WIDTH##"><div style="width:100%;height:100px;overflow:hidden">##MFC_IMAGE##</div></td>
				<td width="50">&nbsp;</td>
				<td>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="main">##MFC_URL_KEY##&nbsp;&nbsp;</td><td>##MFC_COLON##</td>
							<td class="main">&nbsp;&nbsp;##MFC_URL_DATA##</td>
						</tr>
						<tr>
							<td class="main">##MFC_DATE_ADDED_KEY##&nbsp;&nbsp;</td><td>:</td>
							<td class="main">&nbsp;&nbsp;##MFC_DATE_ADDED_DATA##</td>
						</tr>
						<tr> <?php //echo $manufacturer_result["last_modified"];
                                if($manufacturer_result["last_modified"] != '0000-00-00 00:00:00') {  ?>
                            <td class="main">##MFC_DATE_MODIFIED_KEY##&nbsp;&nbsp;</td><td>:</td>
							<td class="main">&nbsp;&nbsp;##MFC_DATE_MODIFIED_DATA##</td>
                           <?php } ?>
						</tr>
					</table>
				</td>
			</tr>
			<tr height="10">
				<td>&nbsp;</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
?>