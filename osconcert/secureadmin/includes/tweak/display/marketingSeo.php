<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class marketingSeo
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='mso';
		}
		
		function doDelete(){
			global $FREQUEST,$jsData;
			$seo_id=$FREQUEST->postvalue('seo_id','int',0);
			$info=$FREQUEST->postvalue('info');
			tep_db_query("DELETE from " . TABLE_META_TAGS . " where filename='" . str_replace(".txt","",$info) . "'");
			
			$this->doMarketingSeoList();
			
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_DELETE_SUCCESS);
			tep_reset_seo_cache('marketingSeo');
							
		}
		
		function doDeleteMarketingSeo(){
			global $FREQUEST,$jsData;
			$seo_id=$FREQUEST->getvalue('pID','int',0);
			$info=$FREQUEST->getvalue('info');
			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_seo.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="info" value="<?php echo tep_output_string($info);?>"/>
				<input type="hidden" name="seo_id" value="<?php echo tep_output_string($seo_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $seo_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $seo_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $seo_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doMarketingSeoInfo($seo_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doMarketingSeoList($products_id=0){
			global $FSESSION,$FREQUEST,$jsData,$files;
			
			$template=getListTemplate();
			
			for ($i=0, $n=sizeof($files); $i<$n; $i++) {
			$rep_array=array(			"ID"=>$i,
										"TYPE"=>'mso',
										"STATUS"=>'',
										"FILE"=>$files[$i],
										"NAME"=>$files[$i],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($i%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'MarketingSeoInfo',
										"FIRST_MENU_DISPLAY"=>""
										);?>
										
			
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td><?php echo mergeTemplate($rep_array,$template); ?>
				</td>
			</tr>
		</Table><?php 
				
			}
		}
		
		function doMarketingSeo(){
			global $FREQUEST,$jsData;
					
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"PRODUCTS_NAME"=>HEADING_NEW_TITLE,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'MarketingSeoEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="mso-1message"></div>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<tr>
					<td><?php echo mergeTemplate($rep_array,$template); ?>
					</td>
				</tr>
				<tr>
					<td>
					<div align="center"><?php $this->doMarketingSeoList();?></div>
					</td>
				</tr>	
			</table>
			<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }
				
			 	
			}
			function doMarketingSeoEdit()
				{
					global $FREQUEST,$jsData;
					$seo_id=$FREQUEST->getvalue("pID","int",0);
				$info=$FREQUEST->getvalue('info');
					
				$filename=str_replace(".txt","",$info);
				
				$fetch_query=tep_db_query("SELECT title,description,keywords,tag_id from " . TABLE_META_TAGS . " where filename='" . tep_db_input($filename) . "'");
		
		if (tep_db_num_rows($fetch_query)){
			$result=tep_db_fetch_array($fetch_query);
			$result["new"]="N";
			}
		else{
		switch($filename){
			case "product_info":
			case "index_products":
			case "index_products_all":
			case "index_products":
			default:
				$file_splt=preg_split("/_/",$filename);
				$check_part=strtoupper($file_splt[0]);
				if (defined("TEXT_" . $check_part)){
					$mes_text=constant("TEXT_" .$check_part);
					$title=$mes_text . " : " . STORE_NAME;
					//$description=STORE_NAME . " : " . $mes_text . " - %%Categories_Name%%";
					//$keywords=" %%Categories_Name%% " . $mes_text;
				} else {
					$filename=str_replace("_"," ",$filename);
					$title=$filename . " " . STORE_NAME;
					//$description=STORE_NAME . " : " . $mes_text . " - %%Categories_Name%%";
					//$keywords=" %%Categories_Name%%" . $mes_text;
				}
		}
		$result=array(	"title"=>$title,
						"description"=>$description,
						"keywords"=>$keywords,
						"tag_id"=>$tag_id,
						"new"=>"Y"
						);
						}
						
//		return $result;
		

				 $template=getMarketingSeoInfoTemplate($group_id);
				
				 echo tep_draw_form('marketing_seo','marketing_seo.php', ' ' ,'post','');
					 $rep_array=array(			"TEXT_LAST_MODIFIED"=>'<b>'.''.'</b>',
												"TEXT_TAG_ID"=>'<b>Tag ID</b>',
												"TEXT_TITLE"=>'<b>'.TEXT_INFO_TITLE.'</b>',
												"TEXT_KEYWORDS"=>'<b>'.TEXT_INFO_KEYWORDS.'</b>',
												"TEXT_DESCRIPTION"=>'<b>'.TEXT_INFO_DESCRIPTION.'</b>',
												//"TEXT_DESCRIPTION"=>'<b>'.TEXT_TAG.'</b>',
												"LAST_MODIFIED"=>tep_draw_pull_down_menu('select_list',get_fields_array($info),'','size=10, onDblClick="javascript:AddField()"'),
												"TAG_ID"=>tep_draw_input_field('tag_id',$result["tag_id"],'size=100 maxlength=255 onFocus="javascript:setCurrent(this);" onClick="javascript:select_focus();"'),
												"TITLE"=>tep_draw_input_field('title',$result["title"],'size=100 maxlength=255 onFocus="javascript:setCurrent(this);" onClick="javascript:select_focus();"'),
												"KEYWORDS"=>tep_draw_textarea_field('keywords',"wrap",100,10,$result["keywords"],'onFocus="javascript:setCurrent(this);" onClick="javascript:select_focus();"'),
												"DESCRIPTION"=>tep_draw_textarea_field('description',"wrap",100,10,$result["description"],'onFocus="javascript:setCurrent(this);" onClick="javascript:select_focus();"'),
												"ID"=>$cInfo->customers_groups_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('info',$info);
						echo tep_draw_hidden_field('seo_id',$seo_id);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doMarketingSeoUpdate()
			{
			global $FREQUEST,$jsData,$SERVER_DATE;
			$info=$FREQUEST->postvalue('info');
			
			$sql_array["tag_id"]=tep_db_prepare_input($FREQUEST->postvalue("tag_id"));
			$sql_array["title"]=tep_db_prepare_input($FREQUEST->postvalue("title"));
			$sql_array["description"]=tep_db_prepare_input($FREQUEST->postvalue("description"));
			$sql_array["keywords"]=tep_db_prepare_input($FREQUEST->postvalue("keywords"));
			$sql_array["date_modified"]=$SERVER_DATE;
			
			$mode_sql = tep_db_query("select * from " . TABLE_META_TAGS . " where filename='" . tep_db_input(str_replace(".txt","",$info)) . "'");
			$count = tep_db_num_rows($mode_sql);
			if($count > 0) $mode = "N";
			else  $mode = "Y";
			
			if ($info) {
			if ($mode=="Y"){
				$sql_array["filename"]=str_replace(".txt","",$info);
				tep_db_perform(TABLE_META_TAGS,$sql_array);
			} else {
				tep_db_perform(TABLE_META_TAGS,$sql_array,"update","filename='" . tep_db_input(str_replace(".txt","",$info)) . "'");
			}
			
			
				$jsData->VARS["prevAction"]=array('id'=>$seo_id,'get'=>'MarketingSeoInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doMarketingSeoInfo($seo_id);
				$jsData->VARS["updateMenu"]=",normal,";
			}
				
			}

			function doMarketingSeoInfo(){
			global $FREQUEST,$jsData,$FSESSION;

			$seo_id=$FREQUEST->getvalue("pID");
			
				if($FREQUEST->getvalue("info"))
					$info=$FREQUEST->getvalue("info");
				else
					$info=$FREQUEST->postvalue("info");
					
			$fetch_query=tep_db_query("SELECT date_modified,tag_id,title,keywords,description from " . TABLE_META_TAGS . " where filename='" . tep_db_input(str_replace(".txt","",$info)) ."'");
			if (tep_db_num_rows($fetch_query)>0){
			 $fetch_result = tep_db_fetch_array($fetch_query);
			    $date_modified=format_date($fetch_result["date_modified"]);
				$tag_id=$fetch_result["tag_id"];
		  		$title=$fetch_result["title"];
		  		$keywords=$fetch_result["keywords"];
		  		$description=$fetch_result["description"];
				
				}
				else
				{
				$date_modified="none";
				$keywords="none";	
				$description="none";
				$title=$info;
				$tag_id="none";
				
				}
			
			$template=getMarketingSeoInfoTemplate($info);
			$rep_array=array(		"TYPE"=>$this->type,
									"ID"=>$seo_id,
									"TEXT_LAST_MODIFIED"=>'<b>'.TEXT_LAST_MODIFIED.'</b>',
									"TEXT_TAG_ID"=>'<b>Tag ID</b>',
									"TEXT_TITLE"=>'<b>'.TEXT_INFO_TITLE.'</b>',
									"TEXT_KEYWORDS"=>'<b>'.TEXT_INFO_KEYWORDS.'</b>',
									"TEXT_DESCRIPTION"=>'<b>'.TEXT_INFO_DESCRIPTION.'</b>',
									"LAST_MODIFIED"=>$date_modified,
									"TAG_ID"=>$tag_id,
									"TITLE"=>$title,
									"KEYWORDS"=>$keywords,
									"DESCRIPTION"=>$description
									);
			echo mergeTemplate($rep_array,$template);
			$jsData->VARS["updateMenu"]=",normal,";
			}
			
		
		}
		function getListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="mso##ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="0" id="mso##ID##sort" class="boxRowMenu">
										<span style="##FIRST_MENU_DISPLAY##"></span>
									</td>
									<td width="15" id="mso##ID##bullet">##STATUS##</td>
									<td class="main" onclick="javascript:doDisplayAction({'id':##ID##,'get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'mso','params':'pID=##ID##&info=##FILE##'});" id="mso##ID##title">##NAME##</td>
									<td id="mso##ID##menu" align="right" class="boxRowMenu">
										<span id="mso##ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'MarketingSeoEdit','result':doDisplayResult,'style':'boxRow','type':'mso','params':'pID=##ID##&info=##FILE##','backupMenu':true});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doDisplayAction({'id':##ID##,'get':'DeleteMarketingSeo','result':doDisplayResult,'style':'boxRow','type':'mso','params':'pID=##ID##&info=##FILE##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="mso##ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onclick="javascript:return doUpdateAction({'id':##ID##,'get':'MarketingSeoUpdate','imgUpdate':true,'type':'mso','style':'boxRow','validate':MarketingSeoValidate,'uptForm':'marketing_seo','customUpdate':doMarketingSeoUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onclick="javascript:return doCancelAction({'id':##ID##,'get':'ProductEdit','type':'mso','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif" title="Cancel"/></a>
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
	function getMarketingSeoInfoTemplate(){
		ob_start();
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td valign="top" width="75"></td>
				<td valign="top" width="300">
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<!--<tr>
							<td class="main" width="100" nowrap="nowrap">##TEXT_LAST_MODIFIED##</td>
							<td class="main" width="200">##LAST_MODIFIED##</td>
						</tr>-->
						<tr>
							<td class="main" >##TEXT_TAG_ID##</td>
							<td class="main" >##TAG_ID##</td>
						</tr>
						<tr>
							<td class="main" >##TEXT_TITLE##</td>
							<td class="main" >##TITLE##</td>
						</tr>
						<tr>
							<td class="main" >##TEXT_KEYWORDS##</td>
							<td class="main" >##KEYWORDS##</td>
						</tr>
						<tr>
							
							<td class="main" align="left" width="10%">##TEXT_DESCRIPTION##</td>
							<td class="main" align="left" width="200">##DESCRIPTION##</td>
						</tr>
											
					</table>
				</td>
				
			</tr>
			<tr height="10">
				<td>&nbsp;<input name="text_selected" id="text_selected" type="hidden" value="false"></td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	function tep_cmp($a, $b) {
	  return strcmp( $a, $b);
	}
	function get_fields_array($filename){
		$result=array();
		$filename=str_replace(".txt","",$filename);
		switch($filename){
			case "product_info":
			case "index_products":
			case "index_products_all":
			case "index_products":
			case "index_nested":
				$result[]=array('id'=>"Category_Name","text"=>"Category_Name");
				$result[]=array('id'=>"Category_Description","text"=>"Category_Description");
				$result[]=array('id'=>"Categories_Name","text"=>"Categories_Name");
				break;
			default:
				$result[]=array('id'=>"Categories_Name","text"=>"Categories_Name");
		}
		return $result;
	}
	
	
?>