<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License
*/
	defined('_FEXEC') or die();
	class paymentTaxClasses
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=true;
		$this->splitResult=false;
		$this->type='tax';
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
						$found=$this->doList(" where tax_class_title like'%".$search_db."%'",0,$search);
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
			if ($group_id>0){
				tep_db_query("DELETE from " . TABLE_TAX_CLASS . " where tax_class_id = $group_id");
				
				$this->doItems();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_GROUP_DELETE_SUCCESS);
				tep_reset_seo_cache('taxclass');
			} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
			}
			
		}
		
		function doDeleteGroups(){
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="payment_tax_classes.php" method="post" enctype="application/x-www-form-urlencoded">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $group_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
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
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
				
			if ($search!=''){
				$orderBy="order by tax_class_id";
			} else {
				$orderBy="order by tax_class_id";
			}
			$query_split=false;
			$payment_tax_class_sql= "select tax_class_id, tax_class_title, tax_class_description from " . TABLE_TAX_CLASS ." $where $orderBy ";
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('TAXCLASS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$payment_tax_class_sql);
						if ($query_split->queryRows > 0){ 
							if ($search!=''){
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
							} else {	
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_GROUPS,'##PAGE_NO##') . "'})";
							}
						}
			}
			$payment_tax_class_query = tep_db_query($payment_tax_class_sql);
			$found=false;
			if (tep_db_num_rows($payment_tax_class_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($payment_tax_class_result=tep_db_fetch_array($payment_tax_class_query)){
					$rep_array=array(	"ID"=>$payment_tax_class_result["tax_class_id"],
										"TYPE"=>$this->type,
										"TITLE"=>$payment_tax_class_result["tax_class_title"],
										"DESCRIPTION"=>$payment_tax_class_result["tax_class_description"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			else if($search=='')
			{
			echo '<div class="main" align="center">'.TEXT_EMPTY_GROUPS.'</div>';
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
									"TITLE"=>TEXT_INFO_HEADING_NEW_TAX_CLASS,
									"DESCRIPTION"=>'',
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
				<td><?php 	echo mergeTemplate($rep_array,$template); ?>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%">
						<tr class="dataTableHeadingRow">
							<!--<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr  >
										<td class="main" width="44%">-->
										<!--<b>--><?php /*?><?php echo  TABLE_HEADING_TAX_CLASSES;?><?php */?><!--</b>-->
										<!--</td>
										
									</tr>
								</table>
							</td>-->
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
			function doEdit()
					{
					global $FREQUEST,$jsData;
					$group_id=$FREQUEST->getvalue("rID","int",0);
					$tax_class_info=array();
				 $tax_class_info_query=tep_db_query("SELECT * from " . TABLE_TAX_CLASS . " where tax_class_id='" . $group_id . "'");
				 if(tep_db_num_rows($tax_class_info_query)>0) $tax_class_info=tep_db_fetch_array($tax_class_info_query);
				 $cInfo=new objectInfo($tax_class_info);
				 
				 $discount_sign_array=array(array('id'=>'+','text'=>'+'),
											array('id'=>'-','text'=>'-')	 );
				 $template=getInfoTemplate($group_id);
					 
				 $dis_sign=strstr($cInfo->customers_groups_discount,"-")? '-':'+';
				 echo tep_draw_form('payment_taxclass','payment_taxclass.php', ' ' ,'post','id="payment_taxclass"');
					 $rep_array=array(			"ENT_TITLE"=>TEXT_INFO_CLASS_TITLE ,
												"TITLE"=>tep_draw_input_field('tax_class_title',$cInfo->tax_class_title,'size=30 maxlength=28'),
												"TYPE"=>$this->type,
												"ENT_DESCRIPTION"=>TEXT_INFO_CLASS_DESCRIPTION,
												"DESCRIPTION"=>tep_draw_textarea_field('tax_class_description', 'soft', '50', '5',$cInfo->tax_class_description,'id="tax_class_description"'),
									//tep_draw_input_field('tax_class_description',$cInfo->tax_class_description,'size=40 maxlength=100'),
									//tep_draw_pull_down_menu('customers_groups_discount_sign',$discount_sign_array,$dis_sign,' size ="" style="width:40"').tep_draw_separator('pixel_trans.gif',5,20).tep_draw_input_field('tax_class_description', ((substr($cInfo->customers_groups_discount,0,1)=='-') ? substr($cInfo->tax_class_description,1,strlen($cInfo->tax_class_description)) : substr($cInfo->tax_class_description,0,strlen($cInfo->customers_groups_discount))) , 'size=8 maxlength="8"', false),
												"ID"=>$cInfo->tax_class_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('tax_class_id',$cInfo->tax_class_id);
						echo mergeTemplate($rep_array,$template);
						
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
					}	
			
			function doUpdate()
			{
			global $FREQUEST,$jsData;
			$group_id=$FREQUEST->postvalue("tax_class_id","int",-1);
			
			$insert=true;
			if ($group_id>0) $insert=false;
													
			$tax_class_title=$FREQUEST->postvalue('tax_class_title');
			$tax_class_description=$FREQUEST->postvalue('tax_class_description');
			//$customers_groups_discount_sign=$FREQUEST->postvalue('customers_groups_discount_sign');
						
			//$field_customers_groups_discount=$customers_groups_discount_sign.$customers_groups_discount;
				$sql_data = array(  'tax_class_title' =>tep_db_prepare_input($tax_class_title),
									'tax_class_description' =>tep_db_prepare_input($tax_class_description)
								 );	
			
			if ($insert){
				tep_db_perform(TABLE_TAX_CLASS,$sql_data);
				$group_id=tep_db_insert_id();
			} else {
				tep_db_perform(TABLE_TAX_CLASS, $sql_data, 'update', "tax_class_id = '" .$group_id . "'");
			}
			if ($insert) {
				$this->doItems();
			} else {
				$jsData->VARS["replace"]=array($this->type. $group_id . "title"=>$tax_class_title,$this->type . $group_id . "description"=>tep_db_prepare_input($tax_class_description));
				$jsData->VARS["prevAction"]=array('id'=>$group_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($group_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
				
			}
			
			function doInfo($group_id=0){
			global $FREQUEST,$jsData;
			
			if($group_id <= 0)$group_id=$FREQUEST->getvalue("rID","int",0);
							
			$tax_class_query = tep_db_query("select tax_class_id, tax_class_title, tax_class_description from " . TABLE_TAX_CLASS . ' where tax_class_id='.$group_id.' order by tax_class_id desc');
			if (tep_db_num_rows($tax_class_query)>0){
				$tax_class_result=tep_db_fetch_array($tax_class_query);
				$template=getInfoTemplate($group_id);
			
				$rep_array=array(	"TYPE"=>$this->type,
									"ENT_TITLE"=>TEXT_INFO_CLASS_TITLE  ,
									"TITLE"=> $tax_class_result["tax_class_title"],
									"ENT_DESCRIPTION"=>TEXT_INFO_CLASS_DESCRIPTION,
									"DESCRIPTION"=>$tax_class_result["tax_class_description"],
									"ID"=>$tax_class_result["tax_class_id"],
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
									<td width="15" id="tax##ID##bullet">##STATUS##</td>
									<td width="55%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##title">##TITLE##</td>
<!--    								<td width="55%"class="main" align="left" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##description">##DESCRIPTION##</td>
-->									<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'payment_taxclass','taxclassUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doCancelAction({'id':##ID##,'get':'Edit','type':'##TYPE##','style':'boxRow'});"><img src="##IMAGE_PATH##template/img_close_blue.gif"/></a>
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
		<table border="0" cellpadding="4" cellspacing="0" width="90%" align="center">
			<div class="hLineGray"></div>
<!--			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden">##HEAD_NAME##</div></td>-->
			
			<tr>
				<td width="10%" align="left" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_TITLE##</b></td>
				<td width="3%" align="left" style="overflow:hidden"  class="main">(##ID##) ##TITLE##</td><br /><br />
				<tr><td width="50" align="left" style="overflow:hidden" class="main" valign="top"><b>##ENT_DESCRIPTION##</b></td>
				<td width="75%" align="left" style="overflow:hidden" class="main">##DESCRIPTION##</td></tr>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}

?>
