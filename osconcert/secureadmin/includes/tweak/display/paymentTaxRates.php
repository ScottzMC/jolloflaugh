<?php
	/*
	Freeway eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Released under the GNU General Public License
	*/
	defined('_FEXEC') or die();
	class paymentTaxRates
	{
		var $pagination;
		var $splitResult;
		var $type;
		
	function __construct() {
			$this->pagination=false;
			$this->splitResult=false;
			$this->type='ptra';
		}
	
	function doOptionSort() 
		{
			global $FREQUEST,$jsData;
			$mode=$FREQUEST->getvalue("mode","string","down");
			$option_id=$FREQUEST->getvalue("rID","int",0);
			
			$option_query=tep_db_query("select tax_priority, tax_rates_id from " . TABLE_TAX_RATES ." where tax_rates_id='" . $option_id . "'");
			echo "select tax_priority, tax_rates_id from " . TABLE_TAX_RATES ." where tax_rates_id='" . $option_id . "'";
			if(tep_db_num_rows($option_query)<=0)
			{
				echo "Err:"  . ERROR_IN_ERROR_IN_DATABASE_TRY_AGAINDATABASE_TRY_AGAIN;
				return;
			}
			$option_result=tep_db_fetch_array($option_query);
			$current_order=(int)$option_result["tax_priority"];
			$position = $FREQUEST->getvalue('rID','int',0);
			//echo $position;
			//echo $current_order;
			
				if ($mode=="up")
				{
					$option_sort_query=tep_db_query("select tax_priority, tax_rates_id from ".TABLE_TAX_RATES." where tax_priority<$current_order order by tax_priority desc limit 1");
				}
				 else if($mode=='down')
				{
					$option_sort_query=tep_db_query("select tax_priority, tax_rates_id from ". TABLE_TAX_RATES ." where tax_priority>$current_order order by tax_priority limit 1");
				}
			//echo $option_sort_sql;
			//$option_sort_query=tep_db_query($option_sort_sql);
				if(tep_db_num_rows($option_sort_query)<=0)
				{
					echo "NOTRUNNED";
					return;
				}
			$option_sort_result=tep_db_fetch_array($option_sort_query);
			$prev_order=$option_sort_result['tax_priority'];
			tep_db_query("UPDATE " . TABLE_TAX_RATES . " set tax_priority='" . $current_order ."' where tax_rates_id='" . (int)$option_sort_result['tax_rates_id'] . "'");
			tep_db_query("UPDATE " . TABLE_TAX_RATES . " set tax_priority='" . $prev_order . "' where tax_rates_id=$option_id");
			echo "SUCCESS";
			$jsData->VARS['moveRow']=array('mode'=>$mode,'destID'=>$option_sort_result['tax_rates_id']);
		}
	
	function doSearchGroup()
		{
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
			if (!$found)
				{
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
	function doDelete()
	{
		global $FREQUEST,$jsData;
		$group_id=$FREQUEST->postvalue('group_id','int',0);
		if ($group_id>0)
		{
			tep_db_query("DELETE from " . TABLE_TAX_RATES . " where tax_rates_id=$group_id");
		
			$this->doItems();
			$jsData->VARS["displayMessage"]=array('text'=>TEXT_TAX_RATE_DELETE_SUCCESS);
			tep_reset_seo_cache('taxrates');
		} 
		else 
		{
			echo "Err:" . TEXT_TAX_RATE_NOT_DELETED;
		}
		
	}
	
	function doDeleteGroups()
	{
		global $FREQUEST,$jsData;
		$group_id=$FREQUEST->getvalue('rID','int',0);
	
		$delete_message='<p><span class="smallText">' . TEXT_DELETE_INTRO . '</span>';
	?>
		<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="payment_tax_rates.php" method="post" enctype="application/x-www-form-urlencoded">
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
	function doList($where='',$group_id=0,$search='')
	{
		global $FSESSION,$FREQUEST,$jsData;
		$page=$FREQUEST->getvalue('page','int',1);
		
		$query_split=false;
		$tax_rates_sql= "select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id order by r.tax_priority";
			if ($this->pagination)
			{
				$query_split=$this->splitResult = (new instance)->getSplitResult('TAXRATES');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$tax_rates_sql);
				if ($query_split->queryRows > 0)
					{ 
						if ($search!='')
						{
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','get':'SearchGroup','result':doTotalResult,params:'search=". urlencode($search) . "&page='+##PAGE_NO##,'message':'" . INFO_SEARCHING_DATA . "'})";
						}
						else
						{	
							$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'Items','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(TEXT_LOADING_DATA,'##PAGE_NO##') . "'})";
						}
					}
			}
			$tax_rates_query=tep_db_query($tax_rates_sql);
			$found=false;
			if (tep_db_num_rows($tax_rates_query)>0) $found=true;
			if($found)
			{
				$template=getListTemplate();
				$icnt=1;
				while($tax_rates_result=tep_db_fetch_array($tax_rates_query))
				{
					$rep_array=array(	"ID"=>$tax_rates_result["tax_rates_id"],
										"TYPE"=>$this->type,
										"TITLE"=>$tax_rates_result["tax_class_title"],
										"ZONE"=>$tax_rates_result["geo_zone_name"],
										"RATES"=>round($tax_rates_result["tax_rate"],2)."%",
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/icon_active.gif'),
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'Info',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}
			}
			else if($search=='')
			{
				echo '<div align="center">'.'<br><br>'.TEXT_EMPTY_GROUPS.'</div>';
			}
			if (!isset($jsData->VARS["Page"]))
			{
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
				return $found;			
			}
			
	function doItems()
	{
		global $FREQUEST,$jsData;
		
		$template=getListTemplate();
		$rep_array=array(	"TYPE"=>$this->type,
							"ID"=>-1,
							"NAME"=>'',
							"TITLE"=>HEADING_NEW_TITLE,
							"ZONE"=>'',
							"RATES"=>'',
							"IMAGE_PATH"=>DIR_WS_IMAGES,
							"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
							"UPDATE_RESULT"=>'doTotalResult',
							"ROW_CLICK_GET"=>'Edit',
							"FIRST_MENU_DISPLAY"=>"display:none"
						);
	
	?>
	<div class="main" id="ptra-1message"></div>
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
										<td class="main" width="8%">
										</td>
										<td class="main" width="36%">
										<b><?php echo  TABLE_HEADING_TAX_CLASS_TITLE;?></b>
										</td>
										<td class="main" width="36%">
										<b><?php echo  TABLE_HEADING_ZONE;?></b>
										</td>
										<td class="main" width="25%">
										<b><?php echo  TABLE_HEADING_TAX_RATE;?></b>
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
	<?php
	 }
	
	}
	function doEdit()
	{
		global $FREQUEST,$jsData;
		$group_id=$FREQUEST->getvalue("rID","int",0);
		$taxrates_info=array();
		$taxrates_info_query=tep_db_query("select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id and r.tax_rates_id='$group_id' order by r.tax_priority");
		if(tep_db_num_rows($taxrates_info_query)>0)
		$taxrates_info=tep_db_fetch_array($taxrates_info_query);
		$cInfo=new objectInfo($taxrates_info);
		
		$template=getInfoTemplate($group_id);
		echo tep_draw_form('payment_tax_rates','payment_tax_rates.php', ' ' ,'post','id="payment_tax_rates"');
		$rep_array=array(			"ENT_TITLE"=>TEXT_INFO_CLASS_TITLE,
									"TITLE"=>tep_tax_classes_pull_down('name="tax_class_id" style="font-size:10px"', $cInfo->tax_class_id),
									"TYPE"=>$this->type,
									"ENT_ZONE"=>TEXT_INFO_ZONE_NAME,
									"ZONE"=>tep_geo_zones_pull_down('name="tax_zone_id" style="font-size:10px"', $cInfo->geo_zone_id),
									"ENT_RATES"=>TEXT_INFO_TAX_RATE,
									"RATES"=>tep_draw_input_field('tax_rate',round($cInfo->tax_rate,2),'size=5 maxlength=5',false,'text',false),
									"ENT_DESCRIPTION"=>TEXT_INFO_RATE_DESCRIPTION,
									"DESCRIPTION"=>tep_draw_textarea_field('tax_description', 'soft', '50', '5',$cInfo->tax_description,'id="tax_description"'),
									"ID"=>$cInfo->customers_groups_id,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"FIRST_MENU_DISPLAY"=>""
						);
	echo tep_draw_hidden_field('tax_rates_id',$cInfo->tax_rates_id);
	echo mergeTemplate($rep_array,$template);
	
	echo '</form>';
	$jsData->VARS["updateMenu"]=",update,";
	$display_mode_html=' style="display:none"';
	
	}	
	
	function doUpdate()
	{
	global $FREQUEST,$jsData;
	$tax_rates_id=$FREQUEST->postvalue("tax_rates_id","int",-1);
	
	$insert=true;
	if ($tax_rates_id>0) $insert=false;
	
		$tax_class_id=$FREQUEST->postvalue('tax_class_id');
		$tax_zone_id=$FREQUEST->postvalue('tax_zone_id');
		$tax_rate=$FREQUEST->postvalue('tax_rate');
		$tax_description=tep_db_prepare_input($FREQUEST->postvalue('tax_description'));
		$sql_data = array('tax_class_id'=>$tax_class_id, 
							'tax_zone_id'=>$tax_zone_id,
							'tax_rate' =>$tax_rate,
							'tax_description' =>$tax_description
						);	
						
	if ($insert){
	tep_db_perform(TABLE_TAX_RATES,$sql_data);
	$tax_rates_id=tep_db_insert_id();
	} else {
	tep_db_perform(TABLE_TAX_RATES, $sql_data, 'update', "tax_rates_id = '" .$tax_rates_id . "'");
	}
	$tax_class_query = tep_db_query("select  r.tax_rates_id, tc.tax_class_title, z.geo_zone_name, r.tax_rate, r.tax_description, r.tax_priority from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id");
	if(tep_db_num_rows($tax_class_query)>0)
	{
	   while($tax_class=tep_db_fetch_array($tax_class_query))
	 	{
			if($tax_class['tax_rates_id']==$tax_rates_id)
				{
					$class_title=$tax_class['tax_class_title'];
					$zone_name=$tax_class['geo_zone_name'];
					$tax_rate=$tax_class['tax_rate'];
				}		
		}
	}
	if ($insert) {
	$this->doItems();
	} else {
	$jsData->VARS["replace"]=array($this->type. $tax_rates_id . "title"=>$class_title,$this->type . $tax_rates_id . "zone"=>$zone_name,$this->type . $tax_rates_id . "rates"=>round($tax_rate,2)."%");
	$jsData->VARS["prevAction"]=array('id'=>$tax_rates_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
	$this->doInfo($tax_rates_id);
	$jsData->VARS["updateMenu"]=",normal,";
	}
	}
	
	function doInfo($group_id=0)
	{
		global $FREQUEST,$jsData;
		
		if($group_id <= 0) 
		$group_id=$FREQUEST->getvalue("rID","int",0);
		$taxrates_info_query = tep_db_query("select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id and r.tax_rates_id='$group_id' order by r.tax_priority");
		//echo "select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id and r.tax_rates_id='$group_id' order by r.tax_priority";
		if (tep_db_num_rows($taxrates_info_query)>0)
		{
			$tax_rates_result=tep_db_fetch_array($taxrates_info_query);
			$template=getInfoTemplate($group_id);
			$rInfo=new objectInfo($tax_rates_result);
			//print_r($rInfo);
			$rep_array=array(	"TYPE"=>$this->type,
								"ENT_TITLE"=>TEXT_INFO_CLASS_TITLE,
								"TITLE"=>$tax_rates_result["tax_class_title"],
								"TYPE"=>$this->type,
								"ENT_ZONE"=>TEXT_INFO_ZONE_NAME,
								"ZONE"=>$tax_rates_result["geo_zone_name"],
								"ENT_RATES"=>TEXT_INFO_TAX_RATE,
								"RATES"=>round($tax_rates_result["tax_rate"],2),
								"ENT_DESCRIPTION"=>TEXT_INFO_RATE_DESCRIPTION,
								"DESCRIPTION"=>$tax_rates_result["tax_description"],
								"ID"=>$tax_rates_result["tax_rates_id"],
								);
			
			echo mergeTemplate($rep_array,$template);
			
			$jsData->VARS["updateMenu"]=",normal,";
		}
		else 
		{
			echo 'Err:' . TEXT_LOCATION_NOT_FOUND;
		}
	}			
		
	}
	function getListTemplate()
	{
		ob_start();
		getTemplateRowTop();
		?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
		<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td width="15" id="ptra##ID##bullet" align="left">##STATUS##</td>
		<!-- <td id="##TYPE####ID##menu" align="left" class="boxRowMenu">
		<span style="##FIRST_MENU_DISPLAY##">
		<img src="images/template/icon_active.gif"/>
		</span>
		</td> -->
		<td  width="7%" id="##TYPE####ID##sort" align="center" class="boxRowMenu">
		<span style="##FIRST_MENU_DISPLAY##">
		<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'OptionSort','result':doSimpleResult,mode:'up',type:'##TYPE##',params:'rID=##ID##&mode=up',validate:sortOptionValidate,'style':'boxLevel1'})">
		<img src="##IMAGE_PATH##template/img_arrow_up.gif" title="Up" align="absmiddle"/></a>
		<a href="javascript:void(0);" onClick="javascript:doSimpleAction({'id':##ID##,'get':'OptionSort','result':doSimpleResult,mode:'down',type:'##TYPE##',params:'rID=##ID##&mode=down',validate:sortOptionValidate,'style':'boxLevel1'})">
		<img src="##IMAGE_PATH##template/img_arrow_down.gif" title="Down"/></a>
		</span>
		</td>
		<td width="37%" class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##title">##TITLE##</td>
		<td width="14%"class="main" align="left" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##zone">##ZONE##</td>
		<td width="27%"class="main" align="right" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##rates">##RATES##</td>
		<td  width="44%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
		<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
		<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'Edit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
		<img src="##IMAGE_PATH##template/img_bar.gif"/>
		<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'DeleteGroups','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
		<img src="##IMAGE_PATH##template/img_bar.gif"/>
		</span>
		<span id="##TYPE####ID##mupdate" style="display:none">
		<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'Update','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':groupValidate,'uptForm':'payment_tax_rates','customUpdate':doItemUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
	function getInfoTemplate()
	{
		ob_start();
		?>
		<table border="0" cellpadding="4" cellspacing="0" width="50%" align="center"  style="overflow:auto">
		<div class="hLineGray"></div>
		<tr align="center">
			<td width="10%" align="left" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_TITLE## &nbsp;&nbsp;</b></td>
			<td  align="left" style="overflow:hidden"  class="main">##TITLE##</td>
		</tr>
		<tr align="center">
		    <td width="5%" align="left" style="overflow:hidden" class="main"><b>##ENT_ZONE##&nbsp;&nbsp;</b></td>
			<td  align="left" style="overflow:hidden" class="main">##ZONE##</td>
		</tr> 
		<tr align="center">
			<td width="10%" align="left" class="main"><b>##ENT_RATES##&nbsp;&nbsp;</b></td>
			<td  align="left" class="main">##RATES##</td>
		</tr>
		<tr align="center">
		    <td align="left" class="main" valign="top"><b>##ENT_DESCRIPTION##&nbsp;&nbsp;</b></td>
			<td align="left" style=" overflow:auto;" class="main">
			 <div style="overflow:auto">##DESCRIPTION##</div>
			</td>
		</tr>
		</tr>
		</table>
		<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	
?>
