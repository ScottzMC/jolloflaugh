<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
 // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
	class quickLinks
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='msr';
		}
		

		function doDelete(){
			global $FREQUEST,$jsData,$FSESSION;
			$Links_id=$FREQUEST->postvalue('Links_id','int',0);
			if ($Links_id>0){
				tep_db_query("delete from ".TABLE_QUICK_LINKS ." where links_id='".$Links_id."' and login_group_id='". $FSESSION->login_groups_id ."'");
				
				$this->doQuickLinks();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_DELETE_SUCCESS);
				tep_reset_seo_cache('referrals');
			} else {
				echo "Err:" . TEXT_REFERRALS_NOT_DELETED;
			}
			
		}
		
		function doDeleteQuickLinks(){
			global $FREQUEST,$jsData;
			$Links_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO. '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_survey_referrals_new.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="Links_id" value="<?php echo tep_output_string($Links_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $Links_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $Links_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $Links_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doQuickLinksInfo($Links_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doQuickLinksList($where='',$Links_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData,$actions;

	if(($FSESSION->delete_value=='delete') && ($FREQUEST->postvalue('quick_del')=='delete') && ($FREQUEST->postvalue('quick1')=='delete'))		
	{
	$FSESSION->set('detete_link_main','Delete');
	$temporary_query=tep_db_query('select links_id from '.TABLE_QUICK_LINKS.' where params="'.$FREQUEST->postvalue('params').'"');
	$temporary_array=tep_db_fetch_array($temporary_query);
	$temporary_id=$temporary_array['links_id'];
	$this->DeleteLink($temporary_id);
	}
	if(($actions=='insert') && ($FSESSION->actions_value=='insert') && ($FREQUEST->postvalue('quick')=='insert') && ($FREQUEST->postvalue('quick1')==''))
	{
			
	$this->doQuickLinksNew();
	}
			$page=$FREQUEST->getvalue('page','int',1);
			
			$query_split=false;
			
			 $links_sql = "select links_id,title,filename,params from " . TABLE_QUICK_LINKS . " where login_group_id='".$FSESSION->login_groups_id ."' order by sort_order" ;
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('REFERRALS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$links_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'QuickLinks','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_LINKS,'##PAGE_NO##') . "'})";
							}
			}
			$links_query=tep_db_query($links_sql);
			$found=false;
			if (tep_db_num_rows($links_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($links_result=tep_db_fetch_array($links_query)){
					$rep_array=array(	"ID"=>$links_result["links_id"],
										"TYPE"=>$this->type,
										'PARAMS'=>$links_result["params"],
										"NAME"=>$links_result["title"],
										"FILENAME"=>$links_result["filename"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'QuickLinksInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			elseif($actions!='insert')
			{
			echo '<div align="center">'.TEXT_EMPTY_LINKS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doQuickLinks(){
			global $FREQUEST,$jsData,$FSESSION;

			
?>
		<div class="main" id="msr-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						
						<tr>
							<td>
								<div align="center"><?php $this->doQuickLinksList();?></div>
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
			
			function doQuickLinksNew()
					{
					global $FREQUEST,$jsData,$FSESSION;
			?>
					
				
					<table border="0" width="100%" cellspacing="0" cellpadding="2" id='new' >
		 	  <tr>
		  		<td>
      			<?php echo tep_draw_form('quick_links_new', FILENAME_QUICK_LINKS, '' ,'post','onsubmit="javascript:new_datas(this);"');?>
				<div id="hide" style="display:block"> 
				<table border="0" cellpadding="5" cellspacing="0" width="100%" align="center" class='openContent_top' id="<?php echo $this->type;?>-1">
					<tr>
						<td class="main" align="center"><?php echo "<b>" . TEXT_INFO_HEADING_NEW_LINK . "<b>";?></td>
						<td align="right" width="40%">
						<span id="msr-1mnormal" style="">
						<a href="javascript:void(0)" onClick="javascript:doUpdateAction({'id':-1,'get':'QuickLinksUpdate','imgUpdate':true,'type':'msr','style':'boxRow','validate':new_datas,'uptForm':'quick_links_new','customUpdate':doUpdate,'result':doTotalResult,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><?php echo tep_image(DIR_WS_IMAGES . 'template/img_save_green.gif','Save')?></a>
						<img src="<?php echo DIR_WS_IMAGES;?>/template/img_bar.gif"/>
						<a href="javascript:void(0)" onClick="javascript:drop_data_cancel();"><?php echo tep_image(DIR_WS_IMAGES . 'template/img_close_blue.gif','Cancel')?></a>
						</span>
									
					   </td>
					  
					</tr>
					<tr>
					   <td class="main" width="40%" align="center"><?php echo TEXT_INFO_LINK_NAME	. '&nbsp;&nbsp;&nbsp;' . tep_draw_input_field('links_name','','size=35 maxlength=100');?></td>
						
					</tr>
					<tr>
					<td class="main" align="center" width="50%">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
					<tr><td id="params"><?php echo tep_draw_hidden_field('params',$FSESSION->params); ?></td></tr>
					<tr><td id="filename"><?php echo tep_draw_hidden_field('filename',$FSESSION->filename);?></td></tr>
					<tr><td id="links_name" class="main" width="6%"><?php echo tep_draw_hidden_field('links_name',$links_name); ?></td><td class="main" align="center" width="100%"></td></tr>
					<tr><td id="links_id"><?php echo tep_draw_hidden_field('links_id',$links_id);?></td></tr>
					</table></td>
					</tr>
					<tr height="20">
					</tr>
				</table>
				</div>  
				</form>
				</td>
				
		  	</tr> 
		 </table> 				 
				<?php }	
			
			function doQuickLinksEdit()
					{
					global $FREQUEST,$jsData,$FSESSION;
					$Links_id=$FREQUEST->getvalue("rID","int",0);
					$links_info=array();
				
				$links_info_query=tep_db_query("SELECT * from " . TABLE_QUICK_LINKS . " where links_id='" . (int)$Links_id . "' and login_group_id='" . $FSESSION->login_groups_id ."'");
				 if(tep_db_num_rows($links_info_query)>0) $links_info=tep_db_fetch_array($links_info_query);
				 $sInfo=new objectInfo($links_info);
				 $template=getInfoTemplate($Links_id);
				
				 echo tep_draw_form('quick_links',FILENAME_QUICK_LINKS, ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TEXT_INFO_LINK_NAME,
												"NAME"=>tep_draw_input_field('quick_link_title',$sInfo->title,'size=20 maxlength=30'),
												"TYPE"=>$this->type,
												"ID"=>$sInfo->links_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('links_id',$sInfo->links_id);
						echo tep_draw_hidden_field('filename',$sInfo->filename);
						echo tep_draw_hidden_field('params',$sInfo->params);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doQuickLinksUpdate()
			{
			global $FREQUEST,$jsData,$FSESSION;
			$Links_id=$FREQUEST->postvalue("links_id","int",-1);
		

			$insert=true;
			if ($Links_id>0) $insert=false;
													
			$title= $FREQUEST->postvalue('quick_link_title','string','');
			
			if ($insert){
			 $sql_data_array = array('title' => $title,
									'filename'=>$FSESSION->filename,
       							    'login_group_id' =>$FSESSION->login_groups_id,
									'params'=>$FSESSION->params
									);
				tep_db_perform(TABLE_QUICK_LINKS,$sql_data_array);
				$Links_id = tep_db_insert_id();

			if($FSESSION->is_registered('actions_value'))
			   	   $FSESSION->set('actions_value','');
			
			} else {
			 $sql_data_array = array('title' => $title,
									'filename'=>$filename,
       							    'login_group_id' =>$FSESSION->login_groups_id,
									'params'=>$params
									);
				tep_db_query('update ' .TABLE_QUICK_LINKS. ' set title="'. addslashes($title) .'" where links_id = "' . $Links_id . '"');
			}
			if ($insert) {
				$this->doQuickLinks();
			} else {
				$jsData->VARS["replace"]=array($this->type. $Links_id . "name"=>$title);
				$jsData->VARS["prevAction"]=array('id'=>$Links_id,'get'=>'QuickLinksInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doQuickLinksInfo($Links_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
				
			}
			
			function doQuickLinksInfo($Links_id=0){
			global $FREQUEST,$jsData,$FSESSION;

			if($Links_id <= 0)$Links_id=$FREQUEST->getvalue("rID","int",0);
			
			$links_query=tep_db_query("select filename,links_id from " . TABLE_QUICK_LINKS . " where login_group_id='".$FSESSION->login_groups_id ."' and links_id='" . tep_db_input($Links_id) . "' order by links_id desc ");
		 	
			if (tep_db_num_rows($links_query)>0){
				 $links_result=tep_db_fetch_array($links_query);
				$template=getInfoTemplate($Links_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_NAME"=>TEXT_INFO_FILENAME  ,
									"NAME"=> $links_result["filename"],
									"ID"=>$links_result["links_id"],
									);
				
				echo mergeTemplate($rep_array,$template);
				if($FSESSION->detete_link_main!='Delete')
				{
				$jsData->VARS["updateMenu"]=",normal,";
				}
				$FSESSION->set('detete_link_main','');
				$FSESSION->set('delete_value','');
			
			}
			else {
				echo 'Err:' . TEXT_REFERRALS_NOT_FOUND;
			}
			
			}
			
			function DeleteLink($Links_id)
	{
	$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_INTRO. '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_survey_referrals_new.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="Links_id" value="<?php echo tep_output_string($Links_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="E7EBFF">
					<tr>
						<td class="main" id="<?php echo $this->type . $Links_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $Links_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:drop_data_cancel();"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doQuickLinksInfo($Links_id);?></td>
					</tr>
				</table>
			</form>
<?php

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
									<td width="15" id="msr##ID##bullet">##STATUS##</td>
									<td width="49%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'QuickLinksEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteQuickLinks','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:doGotoAction({'id':##ID##,'filename':'##FILENAME##','params':'##PARAMS##','type':'##TYPE##','style':'boxRow','result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_move.gif" title="Goto"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'QuickLinksUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':QuickLinksValidate,'uptForm':'quick_links','customUpdate':doQuickLinksUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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