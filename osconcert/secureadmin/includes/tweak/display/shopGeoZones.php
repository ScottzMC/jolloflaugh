<?php
/*
    Freeway eCommerce
    http://www.openfreeway.org
    Copyright (c) 2007 ZacWare
    
    Released under the GNU General Public License

*/
	defined('_FEXEC') or die();
	class shopGeoZones
		{
		var $pagination;
		var $splitResult;
		var $type;

		function __construct() {
		$this->pagination=false;
		$this->splitResult=false;
		$this->type='sgz';
		}
		
		function doCountryDelete()
			{
			global $FREQUEST,$jsData;
			$country_id=$FREQUEST->postvalue('country_id','int',0);
			$zones_id=$FREQUEST->postvalue('zones_id','int',0);
            if ($zones_id>0){
      				
                     $jsData->VARS['storePage']['opened']['sgz']=array("id"=> $zones_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"sgz","params"=>"rID=$zones_id","style"=>"boxLevel1");

                }
				
				if ($country_id>0){
				tep_db_query("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where association_id = '" . (int)$country_id . "'");
				$this->doshopCountries($zones_id);
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_COUNTRY_DELETE_SUCCESS);
				tep_reset_seo_cache('customers');
				} else {
				echo "Err:" . TEXT_CUSTOMER_GROUPS_NOT_DELETED;
				}
			}
		
			function doCountryDeleteConfirm()
			{
			global $FREQUEST,$jsData;
			$country_id=$FREQUEST->getvalue('aID','int',0);
			$zones_id=$FREQUEST->getvalue('cID','int',0);
			$row_id=$FREQUEST->getvalue('rID','int',0);
			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_SUB_ZONE_INTRO . '</span>';
?>
			<form  name="prdDeleteSubmit" id="prdDeleteSubmit" action="shop_geo_zones.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="country_id" value="<?php echo tep_output_string($country_id);?>"/>
				<input type="hidden" name="zones_id" value="<?php echo tep_output_string($zones_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="prd<?php echo $country_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $country_id;?>,type:'prd',get:'CountryDelete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'prdDeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $row_id;?>,type:'prd',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php //echo $this->doInfo($group_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		
		}
		
		function doCountryUpdate()
		{
		global $FREQUEST,$jsData,$server_date;
			$zones_id=$FREQUEST->postvalue("zones_id","int",-1);
			$country_id=$FREQUEST->postvalue("country_id","int",-1);
			$assoc_id=$FREQUEST->postvalue('assoc_id','int',-1);
            
			
			$insert=true;
			if ($country_id>0) $insert=false;


				if ($zones_id>0 && $insert==true){
      				$jsData->VARS['storePage']['opened']['sgz']=array("id"=> $zones_id ,"get"=>"Info","result"=>"doDisplayResult","type"=>"sgz","params"=>"rID=$zones_id","style"=>"boxLevel1");
                 
      			}
			$zone_country_id=$FREQUEST->postvalue('zone_country_id');
			$sel_zone_id=$FREQUEST->postvalue('sel_zone_id');
			
				if ($insert){
				tep_db_query("insert into " . TABLE_ZONES_TO_GEO_ZONES . " (zone_country_id, zone_id, geo_zone_id, date_added) values ('" . (int)$zone_country_id . "', '" . (int)$sel_zone_id . "', '" . (int)$zones_id . "','" . tep_db_input($server_date) . "')");
					$zones_id=tep_db_insert_id();
				} else {
					tep_db_query("update " . TABLE_ZONES_TO_GEO_ZONES . " set zone_country_id = '" . (int)$zone_country_id . "', zone_id = " . (tep_not_null($sel_zone_id) ? "'" . (int)$sel_zone_id . "'" : 'null') . ", last_modified =now() where association_id = '" . (int)$assoc_id . "'");
				}
			if ($insert) {
					$this->doshopCountries();
			} 
			else {
			$country_query=tep_db_query("select countries_name from ".TABLE_COUNTRIES." where countries_id='".$zone_country_id."'");
			$country_result=tep_db_fetch_array($country_query);
			$country_name=$country_result['countries_name'];
			
			$jsData->VARS["replace"]=array('prd'. $country_id . "name"=>$country_name);
			$jsData->VARS["prevAction"]=array('id'=>$zones_id,'get'=>'shopCountries','type'=>$this->type,'style'=>'boxRow');
			$jsData->VARS["updateMenu"]=",normal,";
			}
			
		}
		
			function doCountryEdit()
			{
				global $FREQUEST,$jsData;
				$zones_id=$FREQUEST->getvalue("cID","int",0);
				$country_id=$FREQUEST->getvalue("rID","int",0);
				$assoc_id=$FREQUEST->getvalue('aID','int',0);

				$zones_country_info=array();
				$zones_country_query = tep_db_query("select a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.geo_zone_id, a.last_modified, a.date_added, z.zone_name from " . TABLE_ZONES_TO_GEO_ZONES . " a left join " . TABLE_COUNTRIES . " c on a.zone_country_id = c.countries_id left join " . TABLE_ZONES . " z on a.zone_id = z.zone_id where a.geo_zone_id = " . tep_db_input($zones_id) . " and association_id ='".tep_db_input($assoc_id)."' order by association_id");
				
					 if(tep_db_num_rows($zones_country_query)>0) $zones_country_info=tep_db_fetch_array($zones_country_query);

				 $cInfo=new objectInfo($zones_country_info);

				 $template=getInfoTemplate($zones_id);
				 echo tep_draw_form('country_list','shop_geo_zones.php', ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TEXT_INFO_COUNTRY,
												"NAME"=>tep_draw_pull_down_menu('zone_country_id', tep_get_countries(TEXT_ALL_COUNTRIES), $cInfo->zone_country_id, 'onChange="update_zone(this.form);"'),
												"TYPE"=>'prd',
												"ENT_DESCRIPTION"=> TEXT_INFO_COUNTRY_ZONE,
												"DESCRIPTION"=>tep_draw_pull_down_menu('sel_zone_id', tep_prepare_country_zones_pull_down($cInfo->zone_country_id), $cInfo->zone_id),
												"ID"=>$cInfo->zone_country_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('zones_id',$zones_id);
						echo tep_draw_hidden_field('country_id',$country_id);
						echo tep_draw_hidden_field('assoc_id',$assoc_id);
						echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				
		}
				function doZonesEdit()
				{
					global $FREQUEST,$jsData;
					$zones_id=$FREQUEST->getvalue("rID","int",0);
					$zones_info=array();
				 	$zones_query = tep_db_query("select geo_zone_id, geo_zone_name, geo_zone_description from " . TABLE_GEO_ZONES . " where geo_zone_id='".tep_db_input($zones_id)."' ");
				 		if(tep_db_num_rows($zones_query)>0) $zones_info=tep_db_fetch_array($zones_query);
				 	$cInfo=new objectInfo($zones_info);
				 	
					$template=getInfoTemplate($zones_id);
					echo tep_draw_form('shop_zones','shop_geo_zones.php', ' ' ,'post','');
					 $rep_array=array(			"ENT_NAME"=>TABLE_HEADING_ZONE_NAME,
												"NAME"=>tep_draw_input_field('geo_zone_name',$cInfo->geo_zone_name,'size=15 maxlength=30'),
												"TYPE"=>$this->type,
												"ENT_DESCRIPTION"=> TABLE_HEADING_ZONE_DESCRIPTION,
												"DESCRIPTION"=>tep_draw_input_field('geo_zone_description',$cInfo->geo_zone_description,'size=15 maxlength=30'),
												"ID"=>$cInfo->geo_zone_id,
												"IMAGE_PATH"=>DIR_WS_IMAGES,
												"FIRST_MENU_DISPLAY"=>""
											);
						echo tep_draw_hidden_field('zones_id',$cInfo->geo_zone_id);
						echo mergeTemplate($rep_array,$template);
						echo '</form>';
					$jsData->VARS["updateMenu"]=",update,";
					$display_mode_html=' style="display:none"';
				 
		}
			function doCountryList($where='',$zones_id=0,$search='')
			{
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);
			$query_split=false;
			define('TEXT_RECORDS','Sub Zone');
			$zones_country_sql= "select a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.geo_zone_id, a.last_modified, a.date_added, z.zone_name from " . TABLE_ZONES_TO_GEO_ZONES . " a left join " . TABLE_COUNTRIES . " c on a.zone_country_id = c.countries_id left join " . TABLE_ZONES . " z on a.zone_id = z.zone_id where a.geo_zone_id = " . tep_db_input($zones_id) . " order by association_id";
			$maxRows=$FSESSION->get('displayRowsCnt');
			if ($this->pagination && $maxRows!=-1){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=$maxRows;
				$query_split->parse($page,$zones_country_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'prd','pageNav':true,'closePrev':true,'get':'shopCountries','result':doTotalResult,params:'cID=". $zones_id ."&page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_COUNTRY,'##PAGE_NO##') . "'})";
							
						}
			}
			$sql_query=tep_db_query($zones_country_sql);
			
			$found=false;
			if (tep_db_num_rows($sql_query)>0) $found=true;
			if($found)
			{
			$template=getCountryListTemplate();
			$icnt=1;
			while($sql_result=tep_db_fetch_array($sql_query)){
					$rep_array=array(	"ID"=>$sql_result["zone_country_id"],
										"CID"=>$sql_result["geo_zone_id"],
										"AID"=>$sql_result["association_id"],
										"TYPE"=>'prd',
										"NAME"=>$sql_result["countries_name"],
										"IMAGE_PATH"=>DIR_WS_IMAGES,
										"STATUS"=>'',
										"UPDATE_RESULT"=>'doDisplayResult',
										"ALTERNATE_ROW_STYLE"=>($icnt%2==0?"listItemOdd":"listItemEven"),
										"ROW_CLICK_GET"=>'CountryEdit',
										"FIRST_MENU_DISPLAY"=>""
									);
				echo mergeTemplate($rep_array,$template);
				$icnt++;
			}}
			
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]='prd';
			} 
			return $found;			
		}
			function doshopCountries($zones_id=0)
			{
			global $FREQUEST,$jsData;
				if ($zones_id==0)
				{
				if($FREQUEST->postvalue('zones_id'))
				$zones_id=$FREQUEST->postvalue('zones_id','int',0);
				elseif($FREQUEST->getValue('cID'))
				$zones_id=$FREQUEST->getValue('cID','int',0);
				}
			$template=getCountryListTemplate();
			$rep_array=array(	"CID"=>$zones_id,
								"TYPE"=>"prd",
								"ID"=>-1,
								"NAME"=>TEXT_NEW_COUNTRY,
								"IMAGE_PATH"=>DIR_WS_IMAGES,
								"SEARCH_NEEDED"=>"display:normal",
								"UPDATING_ORDER"=>TEXT_UPDATING_ORDER,
								"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
								"UPDATE_RESULT"=>'doTotalResult',
								"ROW_CLICK_GET"=>'CountryEdit',
								"FIRST_MENU_DISPLAY"=>"display:none",
								"EDIT_MENU_DISPLAY"=>"display:none",
								"FLAG_ONE_RECORD"=>''
							);
		?>
				<div class="main" id="prd-1message"></div>
				<table border="0" width="100%" height="100%" id="prdTable">
					<tr>
						<td><?php 	echo mergeTemplate($rep_array,$template); ?>
						</td>
					</tr>
					<tr>
						<td><?php 	$this->doCountryList(" ",$zones_id); ?>
						</td>
					</tr>
				</table>
		<?php
			if ($this->splitResult && $this->splitResult->queryRows>0){ ?>
					<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table><?php 
			}
		
		
		}
		
		function doInfo($zones_id=0){
			global $FREQUEST,$FSESSION,$jsData;
			if ($zones_id==0) $zones_id=$FREQUEST->getvalue("rID","int",0);
?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td valign="top" style="border-top:solid 1px #C6CEEA;height:5px" class="smallText">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top" class="categoryInfo">
						<?php 
							//echo $this->doShopZonesInfo($category_id);
						?>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr height="20">
								<td valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="bulletTitle" valign="middle">
											<?php echo tep_image(DIR_WS_IMAGES . 'layout/bullet1.gif','','','','align=absmiddle') . '&nbsp;' .TABLE_HEADING_COUNTRY. '  (' . $zones_id. ')'; ?>
											
										</td>
										<td class="main" width="100">
										<?php 
											if ($this->pagination) {
												for ($icnt=MAX_DISPLAY_SEARCH_RESULTS,$n=MAX_DISPLAY_SEARCH_RESULTS*5;$icnt<=$n;$icnt+=MAX_DISPLAY_SEARCH_RESULTS){
													$pg_rows[]=array('id'=>$icnt,'text'=>$icnt);
												}
												$pg_rows[]=array('id'=>-1,'text'=>TEXT_ALL);
												echo TEXT_SHOW.'   :  ' . tep_draw_pull_down_menu('totalRows',$pg_rows,$FSESSION->displayRowsCnt,'onChange="javascript:doPageAction({id:'. $zones_id . ',type:\'prd\',get:\'shopCountries\',closePrev:true,pageNav:true,result:doTotalResult,params:\'cID='. $zones_id .'&rowsCnt=\'+this.value,message:page.template[\'INFO_LOADING_PRODUCTS\']});"');
											}
										?>
										</td>
										<td width="20"></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td style="padding-right:10px">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="boxLevel2">
									<tr height="4">
										<td class="topleft"></td>
										<td></td>
										<td class="topright"></td>
									</tr>
									<tr>
										<td width="4"></td>
										<td style="padding:5px">
											<div id="prdtotalContentResult">
											<?php
												echo $this->doshopCountries($zones_id);
											?>
											</div>
										</td>
										<td width="4"></td>
									</tr>
									<tr height="4">
										<td class="botleft"></td>
										<td></td>
										<td class="botright"></td>
									</tr>
								</table>
								<td>
							</tr>
							<tr height="10">
								<td></td>
							</tr>
					</table>
					</td>
				</tr>
			</table>
<?php
		}			
		
		function doDelete(){
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->postvalue('zones_id','int',0);
			
			if ($zones_id>0){
				tep_db_query("delete from ".TABLE_GEO_ZONES." where geo_zone_id='".$zones_id."'");
				tep_db_query("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)$zones_id . "'");
				$this->doshopGeoZones();
				$jsData->VARS["displayMessage"]=array('text'=>TEXT_ZONES_DELETE_SUCCESS);
				tep_reset_seo_cache('options');
			} else {
				echo "Err:" . TEXT_INSTRUCTOR_OPTIONS_NOT_DELETED;
			}
			
		}
		
		function doshopGeoZonesDelete(){
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->getvalue('rID','int',0);

			$delete_message='<p><span class="smallText">' . TEXT_INFO_DELETE_ZONE_INTRO. '</span>';
?>
			<form  name="<?php echo $this->type;?>DeleteSubmit" id="<?php echo $this->type;?>DeleteSubmit" action="shop_geo_zones.php" method="post" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="zones_id" value="<?php echo tep_output_string($zones_id);?>"/>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td class="main" id="<?php echo $this->type . $zones_id;?>message">
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
							<a href="javascript:void(0);" onClick="javascript:return doUpdateAction({id:<?php echo $zones_id;?>,type:'<?php echo $this->type;?>',get:'Delete',result:doTotalResult,message:page.template['PRD_DELETING'],'uptForm':'<?php echo $this->type;?>DeleteSubmit','imgUpdate':false,params:''})"><?php echo tep_image_button_delete('button_delete.gif');?></a>&nbsp;
							<a href="javascript:void(0);" onClick="javascript:return doCancelAction({id:<?php echo $zones_id;?>,type:'<?php echo $this->type;?>',get:'closeRow','style':'boxRow'})"><?php echo tep_image_button_cancel('button_cancel.gif');?></a>
						</td>
					</tr>
					<tr>
						<td><hr/></td>
					</tr>
					<tr>
						<td valign="top" class="categoryInfo"><?php // echo $this->doInfo($options_id);?></td>
					</tr>
				</table>
			</form>
<?php
			$jsData->VARS["updateMenu"]="";
		}
		
		function doshopGeoZones(){
			global $FREQUEST,$jsData;
			
			$template=getListTemplate();
				$rep_array=array(	"TYPE"=>$this->type,
									"ID"=>-1,
									"NAME"=>HEADING_NEW_TITLE,
									"IMAGE_PATH"=>DIR_WS_IMAGES,
									"STATUS"=>tep_image(DIR_WS_IMAGES . 'template/plus_add2.gif'),
									"UPDATE_RESULT"=>'doTotalResult',
									"ROW_CLICK_GET"=>'ZonesEdit',
									"FIRST_MENU_DISPLAY"=>"display:none"
								);

?>
			<div class="main" id="sgz-1message"></div>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<?php 	echo mergeTemplate($rep_array,$template); ?>
			</table>
			<table border="0" width="100%" height="100%" id="<?php echo $this->type;?>Table">
				<?php 	$this->doshopGeoZonesList();?>
			</table>
			<?php if (is_object($this->splitResult)){?>
				<table border="0" width="100%" height="100%">
						<?php echo $this->splitResult->pgLinksCombo(); ?>
				</table>
			<?php }
				
			 	
			}
		function doshopGeoZonesList($where='',$options_id=0,$search=''){
			global $FSESSION,$FREQUEST,$jsData;
			$page=$FREQUEST->getvalue('page','int',1);

			
			$query_split=false;
			 $zones_sql = "select geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added from " . TABLE_GEO_ZONES . " order by geo_zone_name";
			define('TEXT_RECORDS','tax zones');
			if ($this->pagination){
				$query_split=$this->splitResult = (new instance)->getSplitResult('OPTIONS');
				$query_split->maxRows=MAX_DISPLAY_SEARCH_RESULTS;
				$query_split->parse($page,$zones_sql);
						if ($query_split->queryRows > 0){ 
								$query_split->pageLink="doPageAction({'id':-1,'type':'" . $this->type ."','pageNav':true,'closePrev':true,'get':'shopGeoZones','result':doTotalResult,params:'page='+##PAGE_NO##,'message':'" . sprintf(INFO_LOADING_ZONES,'##PAGE_NO##') . "'})";
							
						}
			}
			$sql_query=tep_db_query($zones_sql);
			
			$found=false;
			if (tep_db_num_rows($sql_query)>0) $found=true;
			if($found)
			{
			$template=getListTemplate();
			$icnt=1;
			while($sql_result=tep_db_fetch_array($sql_query)){
					$rep_array=array(	"ID"=>$sql_result["geo_zone_id"],
										"TYPE"=>$this->type,
										"NAME"=>$sql_result["geo_zone_name"],
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
			echo '<div align="center">'.TEXT_EMPTY_ZONES.'</div>';
			}
			if (!isset($jsData->VARS["Page"])){
				$jsData->VARS["NUclearType"][]=$this->type;
			} 
			return $found;			
		}
		function doshopGeoZonesUpdate()
			{
			global $FREQUEST,$jsData;
			$zones_id=$FREQUEST->postvalue("zones_id","int",-1);
			
			$insert=true;
			if ($zones_id>0) $insert=false;
													
			$geo_zone_name=$FREQUEST->postvalue('geo_zone_name');
			$geo_zone_description=$FREQUEST->postvalue('geo_zone_description');
			
			$sql_data = array(  'geo_zone_name' => tep_db_prepare_input($geo_zone_name),
									'geo_zone_description' =>tep_db_prepare_input($geo_zone_description)
								 );	
			
				if ($insert){
					tep_db_perform(TABLE_GEO_ZONES,$sql_data);
					$zones_id=tep_db_insert_id();
				} else {
					tep_db_perform(TABLE_GEO_ZONES, $sql_data, 'update', "geo_zone_id = '" .$zones_id . "'");
				}
			if ($insert) {
				$this->doshopGeoZones();
			} else {
				$jsData->VARS["replace"]=array($this->type. $zones_id . "name"=>$geo_zone_name);
				$jsData->VARS["prevAction"]=array('id'=>$zones_id,'get'=>'Info','type'=>$this->type,'style'=>'boxRow');
				$this->doInfo($zones_id);
				$jsData->VARS["updateMenu"]=",normal,";
				}
			}
			
		
		}
		// (##ID##)
		function getListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="15" id="##TYPE####ID##bullet">##STATUS##</td>
									<td align="left" width="90%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'ZonesEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'shopGeoZonesDelete','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'shopGeoZonesUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':shopGeoZonesValidate,'uptForm':'shop_zones','customUpdate':doshopGeoZonesUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
	
	function getCountryListTemplate(){
		ob_start();
		getTemplateRowTop();
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" id="##TYPE####ID##">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="15" id="##TYPE####ID##bullet">##STATUS##</td>
									<td align="left" width="90%"class="main" onClick="javascript:doDisplayAction({'id':'##ID##','cid':'##CID##','get':'##ROW_CLICK_GET##','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});" id="##TYPE####ID##name">##NAME##</td>
									<td  width="10%" id="##TYPE####ID##menu" align="right" class="boxRowMenu">
										<span id="##TYPE####ID##mnormal" style="##FIRST_MENU_DISPLAY##">
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CountryEdit','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});"><img src="##IMAGE_PATH##template/edit_blue.gif" title="Edit"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										<a href="javascript:void(0)" onClick="javascript:return doDisplayAction({'id':'##ID##','get':'CountryDeleteConfirm','result':doDisplayResult,'style':'boxRow','type':'##TYPE##','params':'rID=##ID##&cID=##CID##&aID=##AID##'});"><img src="##IMAGE_PATH##template/delete_blue.gif" title="Delete"/></a>
										<img src="##IMAGE_PATH##template/img_bar.gif"/>
										</span>
										<span id="##TYPE####ID##mupdate" style="display:none">
										<a href="javascript:void(0)" onClick="javascript:return doUpdateAction({'id':##ID##,'get':'CountryUpdate','imgUpdate':true,'type':'##TYPE##','style':'boxRow','validate':CountryValidate,'uptForm':'country_list','customUpdate':doCountryUpdate,'result':##UPDATE_RESULT##,'message1':page.template['UPDATE_DATA'],'message':page.template['UPDATE_IMAGE']});"><img src="##IMAGE_PATH##template/img_save_green.gif" title="Save"/></a>
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
			<tr> <td class="main"><div style=" font-weight:bold; padding-top:10px; width:100%;height:20px;overflow:hidden"><!--##HEAD_NAME##--></div></td>
			
			<tr>
				<td width="10%" align="right" nowrap="nowrap" style="overflow:hidden;" class="main"><b>##ENT_NAME##</b></td>
				<td width="3%" align="left" style="overflow:hidden"  class="main">##NAME##</td>
				<td width="5%" align="right" style="overflow:hidden" class="main"><b>##ENT_DESCRIPTION##</b></td>
				<td width="10%"  align="left" style="overflow:hidden" class="main">##DESCRIPTION##</td>
			</tr>
		</table>
<?php
		$contents=ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	?>