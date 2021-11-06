<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class marketingSurveyReferrals
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
			global $FREQUEST,$jsData;
			$referrals_id=$FREQUEST->postvalue('referrals_id','int',0);
			if ($referrals_id>0){
				tep_db_query("delete from " . TABLE_SOURCES . " where sources_id = '" .(int)$referrals_id . "'");
				
				$this->doReferrals();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_REFERRALS_DELETE_SUCCESS);
				tep_reset_seo_cache('referrals');
			} else {
				echo "Err:" . TEXT_REFERRALS_NOT_DELETED;
			}
			
		}
		
		function doDeleteReferrals(){
			global $FREQUEST,$jsData;
			$referrals_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_survey_referrals_new.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="referrals_id" value="<?php echo tep_output_string($referrals_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $referrals_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $referrals_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $referrals_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doReferralsInfo($referrals_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doReferralsList($where='',$referrals_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			
			$query_split=false;
			
			$sources_query_sql = "select sources_id, sources_name from " . TABLE_SOURCES . " order by sources_id desc";
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('REFERRALS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$sources_query_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Referrals','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_REFERRALS,'##PAGE_NO##') . "'})";
							}
			}
			$sources_query=tep_db_query($sources_query_sql);
			$found=false;
			if (tep_db_num_rows($sources_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($sources_query_result=tep_db_fetch_array($sources_query)){
					$rep_array=array(	"ID"=>$sources_query_result["sources_id"],
										"TYPE"=>$this->type,
										"NAME"=>$sources_query_result["sources_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'ReferralsInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			else if($search=='')
			{
			echo '<div align="center">'.TEXT_NO_REFERRALS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doReferrals(){
			global $FREQUEST,$jsData;
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"NAME"=>HEADING_NEW_TITLE,
									"DISCOUNT"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'EditReferrals',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
		<div class="main" id="msr-1message"></div>
		<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
			<tr>
				<td><?php 	echo mergeTemplate($rep_array,$template); ?>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						
						<tr>
							<td>
								<div align="center"><?php $this->doReferralsList();?></div>
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
			function doEditReferrals()
					{
					global $FREQUEST,$jsData;
					$referrals_id=$FREQUEST->getvalue("rID","int",0);
					$sources_info=array();
				
				 $sources_query= tep_db_query("select sources_id, sources_name from " . TABLE_SOURCES . " where sources_id='" . (int)$referrals_id . "'order by sources_name");
				 if(tep_db_num_rows($sources_query)>0) $sources_info=tep_db_fetch_array($sources_query);
				 $sInfo=new objectInfo($sources_info);
				 $template=getInfoTemplate($referrals_id);
				
				 echo tep_draw_form('referrals',FILENAME_REFERRALS, ' ' ,'post','id="referrals"');
					 $rep_array=array(			"ENT_NAME"=>TEXT_REFERRALS_NAME,
												"NAME"=>tep_draw_input_field('sources_name',$sInfo->sources_name,'size=15 maxlength=30'),
												"TYPE"=>$this->type,
												"ID"=>$sInfo->sources_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('sources_id',$sInfo->sources_id);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doUpdateReferrals()
			{
			global $FREQUEST,$jsData;
			$referrals_id=$FREQUEST->postvalue("sources_id","int",-1);
		
			$insert=true;
			if ($referrals_id>0) $insert=false;
													
			$sources_name = tep_db_prepare_input($FREQUEST->postvalue('sources_name'));
       	    $sql_data_array = array('sources_name' => $sources_name);

			if ($insert){
				tep_db_perform(TABLE_SOURCES, $sql_data_array);
		        $referrals_id=tep_db_insert_id();
			} else {
				tep_db_perform(TABLE_SOURCES, $sql_data_array, 'update', "sources_id = '" . $referrals_id . "'");
			}
			if ($insert) {
				$this->doReferrals();
			} else {
				$jsData->VARS["replace"]=array($this->type. $referrals_id . "name"=>tep_db_input($sources_name));
				$jsData->VARS["prevAction"]=array('id'=>$referrals_id,'get'=>'ReferralsInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doReferralsInfo($referrals_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
				
			}
			
			function doReferralsInfo($referrals_id=0){
			global $FREQUEST,$jsData;
			
			if($referrals_id <= 0)$referrals_id=$FREQUEST->getvalue("rID","int",0);
			
			 $sources_query= tep_db_query("select sources_id, sources_name from " . TABLE_SOURCES . " where sources_id='" . (int)$referrals_id . "'order by sources_name");
		 	
			if (tep_db_num_rows($sources_query)>0){
				 $sources_result=tep_db_fetch_array($sources_query);
				$template=getInfoTemplate($referrals_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_NAME"=>TEXT_REFERRALS_NAME  ,
									"NAME"=> $sources_result["sources_name"],
									"ID"=>$sources_result["sources_id"],
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
									<td width="15" id="msr##ID##bullet">##STATUS##</td>
									<td width="49%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'EditReferrals','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteReferrals','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'UpdateReferrals','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':ReferralsValidate,'uptForm':'referrals','customUpdate':doReferralsUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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