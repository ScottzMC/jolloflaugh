<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class marketingSurveyCustomerOptions
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='msc';
		}
		

		function doDelete(){
			global $FREQUEST,$jsData;
			$options_id=$FREQUEST->postvalue('options_id','int',0);
			$action_type=$FREQUEST->postvalue('action_type');

			if ($options_id>0){
				tep_db_query("delete from " . TABLE_CUSTOMER_OPTIONS . " where options_id = '" . (int)$options_id . "'");
				
				$this->doCustomerOptions();
				$jsData->VARS["displayMessage"]=array('text'=>(($action_type=='I')?TEXT_INTERESTS_DELETE_SUCCESS:TEXT_OCCUPATIONS_DELETE_SUCCESS));
				tep_reset_seo_cache('customeroptions');
			} else {
				echo "Err:" . TEXT_CUSTOMER_OPTIONS_NOT_DELETED;
			}
			
		}
		
		function doDeleteCustomerOptions(){
			global $FREQUEST,$jsData;
			$options_id=$FREQUEST->getvalue('rID','int',0);
			$action_type=$FREQUEST->getvalue('typename');
			$delete_message='<p><span class="smallText">' .(($action_type=='I')?TEXT_DELETE_INTRO_INTEREST:TEXT_DELETE_INTRO). '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="marketing_survey_customer_options_new.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="options_id" value="<?php echo tep_output_string($options_id);?>"/>
				<input type="hidden" name="action_type" value="<?php echo tep_output_string($action_type);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $options_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $options_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $options_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php echo $this->doCustomerOptionsInfo($options_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		function doCustomerOptionsList($where='',$options_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			
			if($FREQUEST->getvalue('type'))
			$typename=$FREQUEST->getvalue('type');
			elseif($FREQUEST->postvalue('action_type'))
			$typename=$FREQUEST->postvalue('action_type');
			else
			$typename=$FREQUEST->getvalue('typename');
		
			if($typename=='I')
			define('TEXT_RECORDS','Customer Interests');
			else
			define('TEXT_RECORDS','Customer Occupations');
			
			$query_split=false;
			 $sources_query_sql = "select options_id, options_name from " . TABLE_CUSTOMER_OPTIONS . " where options_type='" . tep_db_input($typename) . "'  order by options_id desc";

			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('CUSTOMEROPTIONS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$sources_query_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'typename':'".$typename."','type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'CustomerOptions','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf((($typename=='I')?INFO_LOADING_INTERESTS:INFO_LOADING_OCCUPATIONS),'##PAGE_NO##') . "'})";
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
					$rep_array=array(	"ID"=>$sources_query_result["options_id"],
										"MAIN"=>$typename,
										"TYPE"=>$this->type,
										"NAME"=>$sources_query_result["options_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'CustomerOptionsInfo',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			else if($search=='')
			{
			echo '<div align="center">'.TEXT_EMPTY_OPTIONS.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		
		function doCustomerOptions(){
			global $FREQUEST,$jsData;
			
			if($FREQUEST->getvalue('type'))
			$typename=$FREQUEST->getvalue('type');
			elseif($FREQUEST->postvalue('action_type'))
			$typename=$FREQUEST->postvalue('action_type');
			else
			$typename=$FREQUEST->getvalue('typename');
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"MAIN"=>$typename,
									"ID"=>-1,
									"NAME"=>(($typename=='I')?HEADING_NEW_TITLE_INTERESTS:HEADING_NEW_TITLE_OCCUPATIONS),
									"DISCOUNT"=>'',
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'CustomerOptionsEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
		<div class="main" id="msc-1message"></div>
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
								<div align="center"><?php $this->doCustomerOptionsList();?></div>
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
			function doCustomerOptionsEdit()
					{
					global $FREQUEST,$jsData;
					$typename=$FREQUEST->getvalue('typename');

					$options_id=$FREQUEST->getvalue("rID","int",0);
					$options_info=array();
				
				 $options_info_query=tep_db_query("SELECT * from " . TABLE_CUSTOMER_OPTIONS . " where options_id='" . (int)$options_id . "' and options_type='" . tep_db_input($typename) . "'");
				 
				 if(tep_db_num_rows($options_info_query)>0) $options_info=tep_db_fetch_array($options_info_query);
				 $sInfo=new objectInfo($options_info);
				 $template=getInfoTemplate($options_id);
				
				 echo tep_draw_form('Customer_Options',FILENAME_CUSTOMER_OPTIONS, ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>(($typename=='I')?TEXT_CUSTOMER_INTEREST_NAME:TEXT_CUSTOMER_OCCUPATION_NAME),
												"NAME"=>tep_draw_input_field('options_name',$sInfo->options_name,'size=15 maxlength=30'),
												"TYPE"=>$this->type,
												"ID"=>$sInfo->options_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('options_id',$sInfo->options_id);

						echo tep_draw_hidden_field('action_type',$typename);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doCustomerOptionsUpdate()
			{
			global $FREQUEST,$jsData;
			$options_id=$FREQUEST->postvalue("options_id","int",-1);
			$type=$FREQUEST->postvalue('action_type');

			$insert=true;
			if ($options_id>0) $insert=false;
													
			$options_name=$FREQUEST->postvalue('options_name');
			$sql_data = array('options_name' => tep_db_prepare_input($options_name),
				   			  'options_type' => tep_db_prepare_input($type));
			
			if ($insert){
				tep_db_perform(TABLE_CUSTOMER_OPTIONS, $sql_data);
		        $options_id=tep_db_insert_id();
			} else {
				tep_db_perform(TABLE_CUSTOMER_OPTIONS, $sql_data, 'update', "options_id = '" . (int)$options_id . "'");
			}
			if ($insert) {
				$this->doCustomerOptions();
			} else {
				$jsData->VARS["replace"]=array($this->type. $options_id . "name"=>$options_name);
				$jsData->VARS["prevAction"]=array('id'=>$options_id,'get'=>'CustomerOptionsInfo','type'=>$this->type,'style'=>'boxRow');
				$this->doCustomerOptionsInfo($options_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
				
			}
			
			function doCustomerOptionsInfo($options_id=0){
			global $FREQUEST,$jsData;
			
			if($FREQUEST->getvalue('typename'))
			$typename=$FREQUEST->getvalue('typename');
			else
			$typename=$FREQUEST->postvalue('action_type');
			
			if($options_id <= 0)$options_id=$FREQUEST->getvalue("rID","int",0);
			$fetch_query=tep_db_query("select options_id, options_name from " . TABLE_CUSTOMER_OPTIONS . " where options_id='" . (int)$options_id . "' and options_type='" . tep_db_input($typename) . "' order by options_id desc");
			if (tep_db_num_rows($fetch_query)>0){
				 $fetch_result=tep_db_fetch_array($fetch_query);
				$template=getInfoTemplate($options_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_NAME"=>(($typename=='I')?TEXT_CUSTOMER_INTEREST_NAME:TEXT_CUSTOMER_OCCUPATION_NAME) ,
									"NAME"=> $fetch_result["options_name"],
									"ID"=>$fetch_result["options_id"],
									);
				
				echo mergeTemplate($rep_array,$template);
				
				$jsData->VARS["updateMenu"]=",normal,";
			}
			else {
				echo 'Err:' . TEXT_CUSTOMER_OPTIONS_NOT_FOUND;
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
									<td width="15" id="msc##ID##bullet">##STATUS##</td>
									<td width="49%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&typename=##MAIN##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CustomerOptionsEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&typename=##MAIN##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteCustomerOptions','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&typename=##MAIN##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CustomerOptionsUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':CustomerOptionsValidate,'uptForm':'Customer_Options','customUpdate':doCustomerOptionsUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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